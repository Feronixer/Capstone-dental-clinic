<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use App\Models\PatientHistory;
use App\Models\ProgressNote;
use App\Models\User;
use App\Models\Appointment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\NotificationService;

class PostProceduralController extends Controller
{
    /**
     * Display the post-procedural page
     */
    public function index()
    {
        $records = PatientRecord::with(['user.info', 'appointment.service', 'progressNotes', 'patientHistories'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view("admin.post-procedural", compact('records'));
    }

    /**
     * Get all patient records (API endpoint for AJAX)
     */
    public function getRecords()
    {
        try {
            // Get all user IDs who have at least one completed appointment
            $usersWithCompletedAppointments = Appointment::whereRaw('LOWER(status) = ?', ['completed'])
                ->distinct()
                ->pluck('patient_id')
                ->filter();

            // Get patient records for users who have completed appointments
            $patientRecords = PatientRecord::with(['user.info', 'appointment.service'])
                ->whereHas('user')
                ->whereIn('user_id', $usersWithCompletedAppointments)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($record) {
                    return $this->mapPatientRecord($record);
                });

            // Get patient histories for users who have completed appointments
            $patientHistories = PatientHistory::with(['patientRecord.user.info', 'patientRecord.appointment'])
                ->whereHas('patientRecord.user')
                ->whereHas('patientRecord', function($query) use ($usersWithCompletedAppointments) {
                    $query->whereIn('user_id', $usersWithCompletedAppointments);
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($history) {
                    return $this->mapPatientHistory($history);
                })
                ->filter(function($history) {
                    return $history['user_id'] !== null;
                });

            // Get progress notes for users who have completed appointments
            $progressNotes = ProgressNote::with(['patientRecord.user.info', 'patientRecord.appointment'])
                ->whereHas('patientRecord.user')
                ->whereHas('patientRecord', function($query) use ($usersWithCompletedAppointments) {
                    $query->whereIn('user_id', $usersWithCompletedAppointments);
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($note) {
                    return $this->mapProgressNote($note);
                })
                ->filter(function($note) {
                    return $note['user_id'] !== null;
                });

            $allRecords = $patientRecords->concat($patientHistories)->concat($progressNotes)->sortByDesc('created_at')->values();

            // Get all unique user IDs from records
            $userIds = $allRecords->pluck('user_id')->unique()->filter();

            // Get all completed appointments with treatments for these users
            $completedAppointments = Appointment::with('service')
                ->whereIn('patient_id', $userIds)
                ->where('status', 'Completed')
                ->get()
                ->groupBy('patient_id');

            // Build treatments map for each user with dates and appointment IDs
            $treatmentsMap = [];
            foreach ($completedAppointments as $patientId => $appointments) {
                $treatments = [];
                foreach ($appointments as $appointment) {
                    $treatmentName = $appointment->service?->service_name 
                        ?? $appointment->service?->name 
                        ?? 'N/A';
                    if ($treatmentName !== 'N/A') {
                        // Check if this treatment already exists (by name and date)
                        $treatmentDate = $appointment->start_datetime ? $appointment->start_datetime->format('Y-m-d') : null;
                        $exists = false;
                        foreach ($treatments as $existingTreatment) {
                            if ($existingTreatment['name'] === $treatmentName && 
                                $existingTreatment['date'] === $treatmentDate) {
                                $exists = true;
                                break;
                            }
                        }
                        if (!$exists) {
                            $treatments[] = [
                                'name' => $treatmentName,
                                'date' => $treatmentDate,
                                'appointment_id' => $appointment->id,
                                'start_datetime' => $appointment->start_datetime ? $appointment->start_datetime->toDateTimeString() : null
                            ];
                        }
                    }
                }
                // Sort by date descending (most recent first)
                usort($treatments, function($a, $b) {
                    if ($a['date'] === $b['date']) return 0;
                    return ($a['date'] > $b['date']) ? -1 : 1;
                });
                $treatmentsMap[$patientId] = $treatments;
            }

            return response()->json([
                'success' => true, 
                'records' => $allRecords,
                'treatments' => $treatmentsMap
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching records', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error loading records'], 500);
        }
    }

    /**
     * Search for patients by name or username (only those with appointments)
     */
    public function searchPatients(Request $request)
    {
        try {
            $searchTerm = $request->input('q', $request->input('search', ''));

            if (empty($searchTerm)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Search for patients (role_id = 3) who have at least one appointment
            $patients = User::with('info')
                ->where('role_id', 3)
                ->whereHas('appointments') // Only patients with appointments
                ->where(function ($query) use ($searchTerm) {
                    $query->where('username', 'LIKE', "%{$searchTerm}%")
                          ->orWhereHas('info', function ($q) use ($searchTerm) {
                              $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                                ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
                          });
                })
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->info ? $user->info->first_name . ' ' . $user->info->last_name : $user->username,
                        'first_name' => $user->info ? $user->info->first_name : '',
                        'last_name' => $user->info ? $user->info->last_name : '',
                        'birthdate' => $user->info && $user->info->birthday ? \Carbon\Carbon::parse($user->info->birthday)->format('Y-m-d') : '',
                        'age' => $user->info && $user->info->birthday
                            ? \Carbon\Carbon::parse($user->info->birthday)->age
                            : '',
                        'sex' => $user->info ? ($user->info->sex ?? $user->info->gender ?? '') : '',
                        'religion' => $user->info ? $user->info->religion : '',
                        'nationality' => $user->info ? $user->info->nationality : '',
                        'contact_number' => $user->info ? ($user->info->contact_number ?? $user->info->phone ?? '') : '',
                        'home_address' => $user->info ? $user->info->home_address : '',
                        'occupation' => $user->info ? $user->info->occupation : '',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $patients
            ]);
        } catch (\Exception $e) {
            \Log::error('Error searching patients: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error searching patients'
            ], 500);
        }
    }

    private function mapPatientRecord($record): array
    {
        return [
            'id' => $record->id,
            'type' => 'patient_record',
            'type_label' => 'Patient Record',
            'user_id' => $record->user_id,
            'patient_name' => $record->user?->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : ($record->user->name ?? 'N/A'),
            'username' => $record->user->name ?? 'N/A',
            'patient_number' => $record->patient_number ?? 'N/A',
            'related_info' => $record->home_address ?? 'No address',
            'sent_to_patient' => $record->sent_to_patient ?? false,
            'created_at' => $record->created_at,
            'data' => $record
        ];
    }

    private function mapPatientHistory($history): array
    {
        $user = $history->patientRecord?->user;
        $patientName = $user?->info ? $user->info->first_name . ' ' . $user->info->last_name : ($user?->name ?? 'N/A');

        return [
            'id' => $history->id,
            'type' => 'patient_history',
            'type_label' => 'Patient History',
            'patient_record_id' => $history->patient_record_id,
            'user_id' => $history->patientRecord?->user_id,
            'patient_name' => $patientName,
            'username' => $user?->name ?? 'N/A',
            'patient_number' => $history->patientRecord?->patient_number ?? 'N/A',
            'related_info' => 'Medical History Record',
            'sent_to_patient' => $history->sent_to_patient ?? false,
            'created_at' => $history->created_at,
            'data' => $history
        ];
    }

    private function mapProgressNote($note): array
    {
        $user = $note->patientRecord?->user;
        $patientName = $user?->info ? $user->info->first_name . ' ' . $user->info->last_name : ($user?->name ?? 'N/A');

        return [
            'id' => $note->id,
            'type' => 'progress_note',
            'type_label' => 'Progress Note',
            'patient_record_id' => $note->patient_record_id,
            'user_id' => $note->patientRecord?->user_id,
            'patient_name' => $patientName,
            'username' => $user?->name ?? 'N/A',
            'patient_number' => $note->patientRecord?->patient_number ?? 'N/A',
            'related_info' => $note->note_date ? 'Note: ' . \Carbon\Carbon::parse($note->note_date)->format('M d, Y') : 'No date',
            'sent_to_patient' => true,
            'created_at' => $note->created_at,
            'data' => $note
        ];
    }


    /**
     * Store or update patient record
     */
    public function storePatientRecord(Request $request)
    {
        try {
            \Log::info('Store Patient Record Request', $request->all());

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'appointment_id' => 'nullable|exists:appointments,id',
                'patient_number' => 'nullable|string|unique:patient_records,patient_number,' . ($request->input('id') ?? 'NULL') . ',id',
                'home_address' => 'nullable|string',
                'date_of_birth' => 'nullable|date',
                'age' => 'nullable|integer',
                'sex' => 'nullable|string',
                'nickname' => 'nullable|string',
                'religion' => 'nullable|string',
                'occupation' => 'nullable|string',
                'contact' => 'nullable|string',
                'guardian_name' => 'nullable|string',
                'guardian_contact' => 'nullable|string',
                'guardian_occupation' => 'nullable|string',
                'notes' => 'nullable|string',
                'other_notes' => 'nullable|string',
                'previous_dentist' => 'nullable|string',
                'last_dental_visit' => 'nullable|date',
                'treatment_done' => 'nullable|string',
                'physician_name' => 'nullable|string',
                'physician_specialty' => 'nullable|string',
                'physician_office_address' => 'nullable|string',
                'physician_contact' => 'nullable|string',
                'medical_history' => 'nullable|string',
                'health_questions' => 'nullable|string',
                'allergies_detail' => 'nullable|string',
            'is_pregnant' => 'nullable|boolean',
            'is_nursing' => 'nullable|boolean',
            'takes_birth_control' => 'nullable|boolean',
                'chief_complaint' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                'treatment_plan' => 'nullable|string',
                'sent_to_patient' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Normalize notes -> other_notes for storage
            if (isset($data['notes']) && (!isset($data['other_notes']) || $data['other_notes'] === null)) {
                $data['other_notes'] = $data['notes'];
            }

            // Convert string "null" to actual null (but not for critical fields)
            $criticalFields = ['user_id', 'sent_to_patient'];
            foreach ($data as $key => $value) {
                if (!in_array($key, $criticalFields) && $value === 'null') {
                    $data[$key] = null;
                }
            }

            // Ensure user_id is present
            if (!isset($data['user_id']) || empty($data['user_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient user_id is required. Please select a patient first.'
                ], 422);
            }

            // Validate that the patient has at least one appointment
            $user = User::find($data['user_id']);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found.'
                ], 404);
            }

            $hasAppointment = Appointment::where('patient_id', $data['user_id'])->exists();
            if (!$hasAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only patients with appointments can have patient records. Please create an appointment for this patient first.'
                ], 422);
            }

            // Automatically set appointment_id to the latest confirmed or completed appointment if not provided
            if (empty($data['appointment_id'])) {
                // First, try to find the latest confirmed or completed appointment that doesn't have a record yet
                $latestAppointment = Appointment::where('patient_id', $data['user_id'])
                    ->whereIn('status', ['Confirmed', 'Completed', 'confirmed', 'completed'])
                    ->whereDoesntHave('patientRecord') // Only get appointments without existing records
                    ->orderBy('start_datetime', 'desc')
                    ->first();

                // If no unrecorded confirmed/completed appointment found, try to find any appointment without a record
                if (!$latestAppointment) {
                    $latestAppointment = Appointment::where('patient_id', $data['user_id'])
                        ->whereDoesntHave('patientRecord')
                        ->orderBy('start_datetime', 'desc')
                        ->first();
                }

                // If still no appointment found, get the latest one (even if it has a record)
                if (!$latestAppointment) {
                    $latestAppointment = Appointment::where('patient_id', $data['user_id'])
                        ->whereIn('status', ['Confirmed', 'Completed', 'confirmed', 'completed'])
                        ->orderBy('start_datetime', 'desc')
                        ->first();
                }

                if ($latestAppointment) {
                    $data['appointment_id'] = $latestAppointment->id;
                    \Log::info('Automatically assigned appointment', [
                        'appointment_id' => $latestAppointment->id,
                        'status' => $latestAppointment->status
                    ]);
                }
            }

            // Disallow creating post-procedural record if appointment is not confirmed/completed
            if (!empty($data['appointment_id'])) {
                $appointment = Appointment::with('service')->find($data['appointment_id']);
                if ($appointment) {
                    // Check if a record already exists for this specific appointment (iterative - each appointment gets its own record)
                    $existingRecordForAppointment = PatientRecord::where('appointment_id', $appointment->id)->first();
                    if ($existingRecordForAppointment && empty($data['id'])) {
                        // If updating an existing record, allow it. Otherwise, prevent duplicate records for same appointment
                        return response()->json([
                            'success' => false,
                            'message' => 'A post-procedural record already exists for this appointment. Each appointment should have only one record. Please edit the existing record instead.'
                        ], 422);
                    }

                    // Normalize status: trim whitespace and convert to lowercase for comparison
                    $status = strtolower(trim((string) $appointment->status));
                    \Log::info('Checking appointment status', [
                        'appointment_id' => $appointment->id,
                        'status' => $appointment->status,
                        'normalized_status' => $status
                    ]);
                    
                    if (!in_array($status, ['confirmed', 'completed'])) {
                        // Check if there are any confirmed or completed appointments for this patient without records
                        $validAppointments = Appointment::where('patient_id', $data['user_id'])
                            ->whereIn('status', ['Confirmed', 'Completed', 'confirmed', 'completed'])
                            ->whereDoesntHave('patientRecord')
                            ->orderBy('start_datetime', 'desc')
                            ->get();
                        
                        if ($validAppointments->count() > 0) {
                            // Use the latest valid appointment without a record
                            $validAppointment = $validAppointments->first();
                            $data['appointment_id'] = $validAppointment->id;
                            $appointment = $validAppointment->load('service');
                            \Log::info('Switched to valid appointment without record', [
                                'old_appointment_id' => $appointment->id,
                                'new_appointment_id' => $validAppointment->id,
                                'status' => $validAppointment->status
                            ]);
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'Post-procedural records can only be created for Confirmed or Completed appointments. This patient has no confirmed or completed appointments without existing records.'
                            ], 422);
                        }
                    }

                    // Automatically populate treatment_done from appointment service if not provided
                    if (empty($data['treatment_done']) && $appointment->service) {
                        $data['treatment_done'] = $appointment->service->service_name;
                        \Log::info('Auto-populated treatment_done from appointment service', [
                            'treatment_done' => $data['treatment_done'],
                            'appointment_id' => $appointment->id
                        ]);
                    } elseif (empty($data['treatment_done']) && $appointment->service_id) {
                        $service = \App\Models\Service::find($appointment->service_id);
                        if ($service) {
                            $data['treatment_done'] = $service->service_name;
                            \Log::info('Auto-populated treatment_done from service_id', [
                                'treatment_done' => $data['treatment_done'],
                                'service_id' => $appointment->service_id
                            ]);
                        }
                    }
                } else {
                    \Log::warning('Appointment not found', ['appointment_id' => $data['appointment_id']]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointment not found.'
                    ], 404);
                }
            } else {
                // No appointment_id provided and no appointments found
                return response()->json([
                    'success' => false,
                    'message' => 'No appointment found. Post-procedural records require a Confirmed or Completed appointment.'
                ], 422);
            }

            // Generate patient number if not exists (do this BEFORE filtering)
            \Log::info('Checking patient_number generation', [
                'has_patient_number' => !empty($data['patient_number']),
                'patient_number_value' => $data['patient_number'] ?? 'not set',
                'has_id' => !empty($data['id']),
                'id_value' => $data['id'] ?? 'not set'
            ]);

            // For iterative records: Each appointment gets its own unique record number
            // Format: PN-{patient_base_number}-{appointment_sequence}
            if (empty($data['patient_number']) && empty($data['id'])) {
                // Creating new record - generate iterative patient number based on appointment
                $basePatientNumber = null;
                
                // Check if patient has any existing records to get base number
                $existingPatientRecord = PatientRecord::where('user_id', $data['user_id'])
                    ->orderBy('created_at', 'asc')
                    ->first();
                
                if ($existingPatientRecord && $existingPatientRecord->patient_number) {
                    // Extract base number (before the dash if it exists)
                    $baseParts = explode('-', $existingPatientRecord->patient_number);
                    if (count($baseParts) >= 2) {
                        $basePatientNumber = $baseParts[0] . '-' . $baseParts[1]; // e.g., "PN-000001"
                    } else {
                        $basePatientNumber = $existingPatientRecord->patient_number;
                    }
                } else {
                    // First record for this patient - generate base number
                    $maxId = PatientRecord::max('id') ?? 0;
                    $basePatientNumber = 'PN-' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
                }
                
                // Count how many records this patient already has for this appointment sequence
                $recordCount = PatientRecord::where('user_id', $data['user_id'])
                    ->where('patient_number', 'like', $basePatientNumber . '%')
                    ->count();
                
                // Generate unique number: base number + sequence (if multiple records)
                if ($recordCount > 0) {
                    $data['patient_number'] = $basePatientNumber . '-' . str_pad($recordCount + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $data['patient_number'] = $basePatientNumber;
                }
                
                \Log::info('Generated iterative patient number', [
                    'patient_number' => $data['patient_number'],
                    'base_number' => $basePatientNumber,
                    'record_count' => $recordCount,
                    'appointment_id' => $data['appointment_id'] ?? 'N/A'
                ]);
            } elseif (!empty($data['patient_number'])) {
                // Patient number already exists
                \Log::info('Using EXISTING patient number', ['patient_number' => $data['patient_number']]);
            } elseif (!empty($data['id'])) {
                // Updating existing record - get patient number from database
                $existingRecord = PatientRecord::find($data['id']);
                if ($existingRecord && $existingRecord->patient_number) {
                    $data['patient_number'] = $existingRecord->patient_number;
                    \Log::info('Loaded patient_number from existing record', ['patient_number' => $data['patient_number']]);
                } else {
                    // Shouldn't happen, but generate if missing
                    $maxId = PatientRecord::max('id') ?? 0;
                    $data['patient_number'] = 'PN-' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
                    \Log::warning('Had to generate patient_number for existing record', ['patient_number' => $data['patient_number']]);
                }
            }

            // Keep important fields even if empty
            $importantFields = ['user_id', 'id', 'patient_number', 'appointment_id', 'sent_to_patient', 'sent_at', 'notes', 'other_notes'];

            // Remove empty strings and null values for optional fields, but keep important fields
            $filteredData = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $importantFields) || ($value !== '' && $value !== null)) {
                    $filteredData[$key] = $value;
                }
            }
            $data = $filteredData;

            // Automatically send to patient when saving (unless explicitly set to false)
            if (!isset($data['sent_to_patient'])) {
                $data['sent_to_patient'] = true;
            }

            // Automatically set sent_at timestamp if sent_to_patient is true
            if (isset($data['sent_to_patient']) && $data['sent_to_patient']) {
                $data['sent_at'] = now();
            }

            // Ensure patient_number is present
            if (empty($data['patient_number'])) {
                \Log::error('Patient number is missing after filtering!', ['data' => $data]);
                return response()->json([
                    'success' => false,
                    'message' => 'Patient number generation failed. Please try again.'
                ], 500);
            }

            \Log::info('Final data before save', ['data' => $data]);

            // Get the authenticated admin user
            $adminUser = Auth::guard('admin')->user();
            $adminUserId = $adminUser ? $adminUser->id : null;

            // Update or create the record
            if (!empty($data['id'])) {
                $record = PatientRecord::find($data['id']);
                if ($record) {
                    $oldValues = $record->toArray();
                    $record->update($data);
                    \Log::info('Admin updated patient record', ['record_id' => $record->id]);

                    // Log activity with explicit user_id
                    ActivityLog::log(
                        'updated',
                        'patient_record',
                        'Updated patient record for ' . ($record->user->info->first_name ?? '') . ' ' . ($record->user->info->last_name ?? ''),
                        $record->id,
                        'PatientRecord',
                        $oldValues,
                        $record->fresh()->toArray(),
                        $adminUserId
                    );
                } else {
                    // ID provided but record doesn't exist, create new
                    unset($data['id']); // Remove invalid ID
                    $record = PatientRecord::create($data);
                    \Log::info('Admin created patient record', ['record_id' => $record->id]);

                    // Log activity with explicit user_id
                    ActivityLog::log(
                        'created',
                        'patient_record',
                        'Added patient record for ' . ($record->user->info->first_name ?? '') . ' ' . ($record->user->info->last_name ?? ''),
                        $record->id,
                        'PatientRecord',
                        null,
                        $record->toArray(),
                        $adminUserId
                    );
                }
            } else {
                // Creating new record
                unset($data['id']); // Make sure id is not set for new records
                $record = PatientRecord::create($data);
                \Log::info('Admin created patient record', ['record_id' => $record->id]);

                // Log activity with explicit user_id
                ActivityLog::log(
                    'created',
                    'patient_record',
                    'Added patient record for ' . ($record->user->info->first_name ?? '') . ' ' . ($record->user->info->last_name ?? ''),
                    $record->id,
                    'PatientRecord',
                    null,
                    $record->toArray(),
                    $adminUserId
                );
            }

            \Log::info('Patient record saved successfully', ['record_id' => $record->id]);

            // Send notification to patient about record update
            try {
                NotificationService::recordUpdated($record->user_id, 'medical record');
            } catch (\Exception $e) {
                \Log::error('Failed to send record update notification:', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient record saved and sent to patient successfully',
                'data' => $record->load(['user.info', 'appointment.service'])
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving patient record', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving record: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Send record to patient
     */
    public function sendToPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'record_id' => 'required|exists:patient_records,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $record = PatientRecord::findOrFail($request->input('record_id'));
        $record->user_id = $request->input('user_id');
        $record->sent_to_patient = true;
        $record->sent_at = now();
        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Record sent to patient successfully'
        ]);
    }

    /**
     * Get patient record by user ID
     */
    public function getPatientRecordByUser($userId)
    {
        try {
            $record = PatientRecord::with(['user.info', 'appointment.service', 'progressNotes', 'patientHistories'])
                ->where('user_id', $userId)
                ->first();

            $completedAppointments = Appointment::with('service')
                ->where('patient_id', $userId)
                ->whereRaw('LOWER(status) = ?', ['completed'])
                ->orderBy('start_datetime', 'desc')
                ->get();

            if ($record) {
                $record->completed_appointments = $completedAppointments;

                return response()->json([
                    'success' => true,
                    'data' => $record,
                    'userInfo' => $record->user->info ?? null,
                    'completed_appointments' => $completedAppointments
                ]);
            } else {
                // Return user info even if no record exists
                $user = User::with('info')->find($userId);
                return response()->json([
                    'success' => false,
                    'message' => 'No patient record found for this user',
                    'userInfo' => $user->info ?? null,
                    'completed_appointments' => $completedAppointments
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching patient record by user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching patient record'
            ], 500);
        }
    }

    /**
     * Get patient record by record ID
     */
    public function getPatientRecord($recordId)
    {
        try {
            $record = PatientRecord::with(['user.info', 'appointment.service', 'progressNotes.appointment.service', 'patientHistories'])
                ->find($recordId);

            if ($record) {
                // Get all completed appointments for this patient
                $completedAppointments = Appointment::with('service')
                    ->where('patient_id', $record->user_id)
                    ->whereRaw('LOWER(status) = ?', ['completed'])
                    ->orderBy('start_datetime', 'desc')
                    ->get();

                $record->completed_appointments = $completedAppointments;

                return response()->json([
                    'success' => true,
                    'data' => $record
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Patient record not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error fetching patient record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching patient record'
            ], 500);
        }
    }

    /**
     * Get patient history
     */
    public function getPatientHistory($recordId)
    {
        $history = PatientHistory::where('patient_record_id', $recordId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Store or update patient history
     */
    public function storePatientHistory(Request $request)
    {
        try {
            \Log::info('Store Patient History Request', $request->all());

            // Allow both workflows: with patient_id (new) or patient_record_id (existing)
            $validator = Validator::make($request->all(), [
            'patient_id' => 'required_without:patient_record_id|exists:users,id',
            'patient_record_id' => 'required_without:patient_id|exists:patient_records,id',
            // Dental History
            'previous_dentist' => 'nullable|string',
            'last_dental_visit' => 'nullable|date',
            'treatment_done' => 'nullable|string',
            // Medical History
            'physician_name' => 'nullable|string',
            'physician_specialty' => 'nullable|string',
            'physician_office_address' => 'nullable|string',
            'physician_contact' => 'nullable|string',
            // Health Questions
            'good_health' => 'nullable|string',
            'under_treatment' => 'nullable|string',
            'treatment_condition' => 'nullable|string',
            'serious_illness' => 'nullable|string',
            'illness_details' => 'nullable|string',
            'been_hospitalized' => 'nullable|string',
            'hospitalization_reason' => 'nullable|string',
            'taking_drugs' => 'nullable|string',
            'medications' => 'nullable|string',
            'tobacco_use' => 'nullable|string',
            'alcohol_use' => 'nullable|string',
            'recreational_drugs' => 'nullable|string',
            // Allergies
            'allergy_anesthesia' => 'nullable|boolean',
            'allergy_sulfa' => 'nullable|boolean',
            'allergy_antibiotics' => 'nullable|boolean',
            'allergy_aspirin' => 'nullable|boolean',
            'allergy_analgesics' => 'nullable|boolean',
            'allergy_latex' => 'nullable|boolean',
            'food_allergy_details' => 'nullable|string',
            'other_allergy_details' => 'nullable|string',
            // For Women
            'is_pregnant' => 'nullable|string',
            'is_nursing' => 'nullable|string',
            'birth_control' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::error('Patient History Validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Get or find patient_record_id
        $patientRecordId = $request->patient_record_id;

        // If patient_id is provided instead, find or create patient record
        if ($request->patient_id && !$patientRecordId) {
            $patientRecord = PatientRecord::firstOrCreate(
                ['user_id' => $request->patient_id],
                [
                    'patient_number' => 'P' . str_pad($request->patient_id, 6, '0', STR_PAD_LEFT),
                    'sent_to_patient' => true
                ]
            );
            $patientRecordId = $patientRecord->id;
        }

        // Create or update patient history
        $user = Auth::guard('admin')->user();
        $historyData = [
            'patient_record_id' => $patientRecordId,
            'visit_date' => $request->visit_date ?? $request->last_dental_visit ?? now()->toDateString(),
            // Dental History
            'previous_dentist' => $request->previous_dentist,
            'last_dental_visit' => $request->last_dental_visit,
            'treatment_done' => $request->treatment_done,
            // Medical History
            'physician_name' => $request->physician_name,
            'physician_specialty' => $request->physician_specialty,
            'physician_office_address' => $request->physician_office_address,
            'physician_contact' => $request->physician_contact,
            // Health Questions
            'good_health' => $request->good_health,
            'under_treatment' => $request->under_treatment,
            'treatment_condition' => $request->treatment_condition,
            'serious_illness' => $request->serious_illness,
            'illness_details' => $request->illness_details,
            'been_hospitalized' => $request->been_hospitalized,
            'hospitalization_reason' => $request->hospitalization_reason,
            'taking_drugs' => $request->taking_drugs,
            'medications' => $request->medications,
            'tobacco_use' => $request->tobacco_use,
            'alcohol_use' => $request->alcohol_use,
            'recreational_drugs' => $request->recreational_drugs,
            // Allergies
            'allergy_anesthesia' => $request->allergy_anesthesia ?? 0,
            'allergy_sulfa' => $request->allergy_sulfa ?? 0,
            'allergy_antibiotics' => $request->allergy_antibiotics ?? 0,
            'allergy_aspirin' => $request->allergy_aspirin ?? 0,
            'allergy_analgesics' => $request->allergy_analgesics ?? 0,
            'allergy_latex' => $request->allergy_latex ?? 0,
            'food_allergy_details' => $request->food_allergy_details,
            'other_allergy_details' => $request->other_allergy_details,
            // For Women
            'is_pregnant' => $request->is_pregnant,
            'is_nursing' => $request->is_nursing,
            'birth_control' => $request->birth_control,
            // Automatically send to patient
            'sent_to_patient' => true,
            'sent_at' => now()
        ];
        
        // Only set created_by for new histories
        if (!$request->id) {
            $historyData['created_by_user_id'] = $user->id;
            $historyData['created_by_role'] = 'admin';
        }
        
        $history = PatientHistory::updateOrCreate(
            ['id' => $request->id], // If id exists, update; otherwise create
            $historyData
        );

        // Also mark the parent patient record as sent
        $patientRecord = PatientRecord::find($patientRecordId);
        if ($patientRecord) {
            $patientRecord->update([
                'sent_to_patient' => true,
                'sent_at' => now()
            ]);
        }

            \Log::info('Patient history saved successfully', ['history_id' => $history->id]);

            // Send notification to patient about history update
            try {
                if ($patientRecord) {
                    NotificationService::recordUpdated($patientRecord->user_id, 'medical history');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send history update notification:', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient history saved and sent to patient successfully',
                'data' => $history
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving patient history', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving patient history: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePatientHistory(Request $request, $id)
    {
        try {
            \Log::info('Update Patient History Request', $request->all());

            $validator = Validator::make($request->all(), [
                'patient_record_id' => 'required|exists:patient_records,id',
                'visit_date' => 'nullable|date',
                // Dental History
                'previous_dentist' => 'nullable|string|max:255',
                'last_dental_visit' => 'nullable|date',
                'treatment_done' => 'nullable|string',
                // Medical History
                'physician_name' => 'nullable|string|max:255',
                'physician_specialty' => 'nullable|string|max:255',
                'physician_office_address' => 'nullable|string',
                'physician_contact' => 'nullable|string|max:20',
                // Health questions
                'good_health' => 'nullable|in:yes,no',
                'under_treatment' => 'nullable|in:yes,no',
                'treatment_condition' => 'nullable|string',
                'serious_illness' => 'nullable|in:yes,no',
                'illness_details' => 'nullable|string',
                'been_hospitalized' => 'nullable|in:yes,no',
                'hospitalization_reason' => 'nullable|string',
                'taking_drugs' => 'nullable|in:yes,no',
                'medications' => 'nullable|string',
                'tobacco_use' => 'nullable|in:yes,no',
                'alcohol_use' => 'nullable|in:yes,no',
                'recreational_drugs' => 'nullable|in:yes,no',
                // Allergies
                'allergy_anesthesia' => 'nullable|boolean',
                'allergy_sulfa' => 'nullable|boolean',
                'allergy_antibiotics' => 'nullable|boolean',
                'allergy_aspirin' => 'nullable|boolean',
                'allergy_analgesics' => 'nullable|boolean',
                'allergy_latex' => 'nullable|boolean',
                'food_allergy_details' => 'nullable|string',
                'other_allergy_details' => 'nullable|string',
                // For women
                'is_pregnant' => 'nullable|in:yes,no',
                'is_nursing' => 'nullable|in:yes,no',
                'birth_control' => 'nullable|in:yes,no',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $patientHistory = PatientHistory::findOrFail($id);

            $patientHistory->update([
                'patient_record_id' => $request->patient_record_id,
                'visit_date' => $request->visit_date ?? $request->last_dental_visit ?? $patientHistory->visit_date ?? now()->toDateString(),
                // Dental History
                'previous_dentist' => $request->previous_dentist,
                'last_dental_visit' => $request->last_dental_visit,
                'treatment_done' => $request->treatment_done,
                // Medical History
                'physician_name' => $request->physician_name,
                'physician_specialty' => $request->physician_specialty,
                'physician_office_address' => $request->physician_office_address,
                'physician_contact' => $request->physician_contact,
                // Health questions
                'good_health' => $request->good_health,
                'under_treatment' => $request->under_treatment,
                'treatment_condition' => $request->treatment_condition,
                'serious_illness' => $request->serious_illness,
                'illness_details' => $request->illness_details,
                'been_hospitalized' => $request->been_hospitalized,
                'hospitalization_reason' => $request->hospitalization_reason,
                'taking_drugs' => $request->taking_drugs,
                'medications' => $request->medications,
                'tobacco_use' => $request->tobacco_use,
                'alcohol_use' => $request->alcohol_use,
                'recreational_drugs' => $request->recreational_drugs,
                // Allergies
                'allergy_anesthesia' => $request->allergy_anesthesia ?? 0,
                'allergy_sulfa' => $request->allergy_sulfa ?? 0,
                'allergy_antibiotics' => $request->allergy_antibiotics ?? 0,
                'allergy_aspirin' => $request->allergy_aspirin ?? 0,
                'allergy_analgesics' => $request->allergy_analgesics ?? 0,
                'allergy_latex' => $request->allergy_latex ?? 0,
                'food_allergy_details' => $request->food_allergy_details,
                'other_allergy_details' => $request->other_allergy_details,
                // For Women
                'is_pregnant' => $request->is_pregnant,
                'is_nursing' => $request->is_nursing,
                'birth_control' => $request->birth_control,
                // Automatically send to patient
                'sent_to_patient' => true,
                'sent_at' => now()
            ]);

            // Also mark the parent patient record as sent
            $patientRecord = PatientRecord::find($request->patient_record_id);
            if ($patientRecord) {
                $patientRecord->update([
                    'sent_to_patient' => true,
                    'sent_at' => now()
                ]);
            }

            \Log::info('Patient history updated successfully', ['history_id' => $patientHistory->id]);

            // Send notification to patient about history update
            try {
                if ($patientRecord) {
                    NotificationService::recordUpdated($patientRecord->user_id, 'medical history');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send history update notification:', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient history updated and sent to patient successfully',
                'data' => $patientHistory
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating patient history: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating patient history: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get progress notes
     */
    public function getProgressNotes($recordId)
    {
        $appointmentId = request()->query('appointment_id');
        
        $query = ProgressNote::where('patient_record_id', $recordId);
        
        if ($appointmentId) {
            $query->where('appointment_id', $appointmentId);
        }
        
        $notes = $query->with('appointment.service')
            ->orderBy('note_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    /**
     * Store or update progress note
     */
    public function storeProgressNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_record_id' => 'required|exists:patient_records,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'note_date' => 'required|date',
            'progress_description' => 'required|string',
            'treatment_response' => 'nullable|string',
            'amount_paid' => 'nullable|numeric|min:0',
            'balance' => 'nullable|numeric|min:0',
            'conforme' => 'nullable|string|max:255',
            'next_steps' => 'nullable|string',
            'other_notes' => 'nullable|string',
            'status' => 'nullable|in:ongoing,completed,followup_needed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::guard('admin')->user();
        $noteData = $request->all();
        if (!$request->id) {
            // Only set created_by for new notes
            $noteData['created_by_user_id'] = $user->id;
            $noteData['created_by_role'] = 'admin';
        }
        
        $note = ProgressNote::updateOrCreate(
            ['id' => $request->id],
            $noteData
        );

        // Send notification to patient about progress note
        try {
            $record = PatientRecord::find($request->input('patient_record_id'));
            if ($record) {
                NotificationService::recordUpdated($record->user_id, 'progress note');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send progress note notification:', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress note saved successfully',
            'data' => $note
        ]);
    }

    public function updateProgressNote(Request $request, $id)
    {
        try {
            \Log::info('Update Progress Note Request', $request->all());

            $validator = Validator::make($request->all(), [
                'patient_record_id' => 'required|exists:patient_records,id',
                'appointment_id' => 'nullable|exists:appointments,id',
                'note_date' => 'required|date',
                'progress_description' => 'required|string',
                'treatment_response' => 'nullable|string',
                'amount_paid' => 'nullable|numeric|min:0',
                'balance' => 'nullable|numeric|min:0',
                'conforme' => 'nullable|string|max:255',
                'next_steps' => 'nullable|string',
                'other_notes' => 'nullable|string',
                'status' => 'nullable|in:ongoing,completed,followup_needed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $note = ProgressNote::findOrFail($id);
            $note->update($request->all());

            // Send notification to patient about progress note update
            try {
                $record = PatientRecord::find($request->input('patient_record_id'));
                if ($record) {
                    NotificationService::recordUpdated($record->user_id, 'progress note');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send progress note notification:', ['error' => $e->getMessage()]);
            }

            \Log::info('Progress note updated successfully', ['note_id' => $note->id]);

            return response()->json([
                'success' => true,
                'message' => 'Progress note updated successfully',
                'data' => $note
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating progress note: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating progress note: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete patient record
     */
    public function destroyPatientRecord($id)
    {
        $record = PatientRecord::findOrFail($id);
        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient record deleted successfully'
        ]);
    }

    /**
     * Delete patient history (single record by ID or all by patient_record_id)
     */
    public function destroyPatientHistory($id)
    {
        try {
            \Log::info('Delete Patient History Request', ['id' => $id]);

            // Check if we're deleting by patient_record_id (all histories) or by history id (single)
            $patientHistories = PatientHistory::where('patient_record_id', $id)->get();

            if ($patientHistories->count() > 0) {
                // Delete all histories for this patient record
                PatientHistory::where('patient_record_id', $id)->delete();
                \Log::info('All patient histories deleted for record', ['patient_record_id' => $id, 'count' => $patientHistories->count()]);

                return response()->json([
                    'success' => true,
                    'message' => 'All patient histories deleted successfully'
                ]);
            } else {
                // Try deleting by history ID
                $history = PatientHistory::findOrFail($id);
                $history->delete();
                \Log::info('Patient history deleted successfully', ['history_id' => $id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Patient history deleted successfully'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting patient history', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting patient history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete progress note (single note by ID or all by patient_record_id)
     */
    public function destroyProgressNote($id)
    {
        try {
            $appointmentId = request()->query('appointment_id');
            \Log::info('Delete Progress Note Request', ['id' => $id, 'appointment_id' => $appointmentId]);

            // If appointment_id is provided, delete progress notes for that specific appointment
            if ($appointmentId) {
                $query = ProgressNote::where('patient_record_id', $id)
                    ->where('appointment_id', $appointmentId);
                
                $count = $query->count();
                $query->delete();
                
                \Log::info('Progress notes deleted for appointment', [
                    'patient_record_id' => $id,
                    'appointment_id' => $appointmentId,
                    'count' => $count
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Progress notes for selected procedure deleted successfully ({$count} note(s))"
                ]);
            }

            // Check if we're deleting by patient_record_id (all notes) or by note id (single)
            $progressNotes = ProgressNote::where('patient_record_id', $id)->get();

            if ($progressNotes->count() > 0) {
                // Delete all notes for this patient record
                ProgressNote::where('patient_record_id', $id)->delete();
                \Log::info('All progress notes deleted for record', ['patient_record_id' => $id, 'count' => $progressNotes->count()]);

                return response()->json([
                    'success' => true,
                    'message' => 'All progress notes deleted successfully'
                ]);
            } else {
                // Try deleting by note ID
                $note = ProgressNote::findOrFail($id);
                $note->delete();
                \Log::info('Progress note deleted successfully', ['note_id' => $id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Progress note deleted successfully'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting progress note', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete progress note'
            ], 500);
        }
    }

    /**
     * Store multiple progress notes at once
     */
    public function storeProgressNotes(Request $request)
    {
        try {
            \Log::info('Store Progress Notes Request', $request->all());

            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|exists:users,id',
                'notes' => 'required|array|min:1',
                'notes.*.appointmentId' => 'required|exists:appointments,id',
                'notes.*.date' => 'required|date',
                'notes.*.progressNote' => 'nullable|string',
                'notes.*.amountPaid' => 'nullable|numeric|min:0',
                'notes.*.balance' => 'nullable|numeric|min:0',
                'notes.*.conforme' => 'nullable|string|max:255',
                'other_notes' => 'nullable|string',
                'send_to_patient' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $patientId = $request->input('patient_id');

            // Get or create patient record
            $patientRecord = PatientRecord::where('user_id', $patientId)->first();

            if (!$patientRecord) {
                // Create a new patient record if it doesn't exist
                $maxId = PatientRecord::max('id') ?? 0;
                $patientRecord = PatientRecord::create([
                    'user_id' => $patientId,
                    'patient_number' => 'PN-' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT),
                    'sent_to_patient' => true,
                    'sent_at' => now()
                ]);
            }

            // Get existing notes count before adding new ones
            $existingNotesCount = ProgressNote::where('patient_record_id', $patientRecord->id)->count();

            // Save each progress note
            $savedNotes = [];
            foreach ($request->input('notes') as $noteData) {
                // Skip empty rows
                if (empty($noteData['progressNote']) && 
                    empty($noteData['amountPaid']) && empty($noteData['balance']) && empty($noteData['conforme'])) {
                    continue;
                }

                $user = Auth::guard('admin')->user();
                $note = ProgressNote::create([
                    'patient_record_id' => $patientRecord->id,
                    'appointment_id' => $noteData['appointmentId'],
                    'note_date' => $noteData['date'],
                    'progress_description' => $noteData['progressNote'] ?? null,
                    'amount_paid' => isset($noteData['amountPaid']) && $noteData['amountPaid'] !== '' ? $noteData['amountPaid'] : null,
                    'balance' => isset($noteData['balance']) && $noteData['balance'] !== '' ? $noteData['balance'] : null,
                    'conforme' => $noteData['conforme'] ?? null,
                    'created_by_user_id' => $user->id,
                    'created_by_role' => 'admin',
                    'status' => 'ongoing'
                ]);

                $savedNotes[] = $note;
            }

            // Only update sent_to_patient if not already set, or if new notes were added
            if (!$patientRecord->sent_to_patient || count($savedNotes) > 0) {
                $patientRecord->update([
                    'sent_to_patient' => true,
                    'sent_at' => now()
                ]);
            }

            // Only send notification if NEW notes were added (not just updating existing ones)
            $newNotesCount = ProgressNote::where('patient_record_id', $patientRecord->id)->count();
            if ($newNotesCount > $existingNotesCount && count($savedNotes) > 0) {
                try {
                    NotificationService::recordUpdated($patientId, 'progress notes');
                } catch (\Exception $e) {
                    \Log::error('Failed to send progress notes notification:', ['error' => $e->getMessage()]);
                }
            }

            \Log::info('Progress notes saved successfully', [
                'patient_record_id' => $patientRecord->id,
                'notes_count' => count($savedNotes)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Progress notes saved and sent to patient successfully',
                'data' => [
                    'patient_record' => $patientRecord,
                    'notes' => $savedNotes
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving progress notes', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving progress notes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify admin password before allowing edits/deletes
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $admin = Auth::guard('admin')->user();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password verified successfully.'
        ]);
    }
}
