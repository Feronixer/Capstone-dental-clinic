<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use App\Models\PatientHistory;
use App\Models\ProgressNote;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostProceduralController extends Controller
{
    /**
     * Display the post-procedural page
     */
    public function index()
    {
        $records = PatientRecord::with(['user.info', 'appointment.service'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view("admin.post-procedural", compact('records'));
    }

    /**
     * Get patient record details
     */
    public function getPatientRecord($id)
    {
        $record = PatientRecord::with(['user.info', 'appointment.service'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $record
        ]);
    }

    /**
     * Get patient record by user ID
     */
    public function getPatientRecordByUser($userId)
    {
        $record = PatientRecord::with(['user.info', 'appointment.service'])
            ->where('user_id', $userId)
            ->first();

        $user = User::with('info')->find($userId);

        return response()->json([
            'success' => true,
            'data' => $record,
            'userInfo' => $user ? $user->info : null
        ]);
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

            // Automatically set appointment_id to the latest appointment if not provided
            if (empty($data['appointment_id'])) {
                $latestAppointment = Appointment::where('patient_id', $data['user_id'])
                    ->orderBy('start_datetime', 'desc')
                    ->first();

                if ($latestAppointment) {
                    $data['appointment_id'] = $latestAppointment->id;
                    \Log::info('Automatically assigned latest appointment', ['appointment_id' => $latestAppointment->id]);
                }
            }

            // Generate patient number if not exists (do this BEFORE filtering)
            \Log::info('Checking patient_number generation', [
                'has_patient_number' => !empty($data['patient_number']),
                'patient_number_value' => $data['patient_number'] ?? 'not set',
                'has_id' => !empty($data['id']),
                'id_value' => $data['id'] ?? 'not set'
            ]);

            if (empty($data['patient_number']) && empty($data['id'])) {
                // Creating new record - generate patient number
                $maxId = PatientRecord::max('id') ?? 0;
                $data['patient_number'] = 'PN-' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
                \Log::info('Generated NEW patient number', ['patient_number' => $data['patient_number']]);
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
            $importantFields = ['user_id', 'id', 'patient_number', 'appointment_id', 'sent_to_patient', 'sent_at'];

            // Remove empty strings and null values for optional fields, but keep important fields
            $filteredData = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $importantFields) || ($value !== '' && $value !== null)) {
                    $filteredData[$key] = $value;
                }
            }
            $data = $filteredData;

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

            // Update or create the record
            if (!empty($data['id'])) {
                $record = PatientRecord::find($data['id']);
                if ($record) {
                    $record->update($data);
                } else {
                    // ID provided but record doesn't exist, create new
                    unset($data['id']); // Remove invalid ID
                    $record = PatientRecord::create($data);
                }
            } else {
                // Creating new record
                unset($data['id']); // Make sure id is not set for new records
                $record = PatientRecord::create($data);
            }

            \Log::info('Patient record saved successfully', ['record_id' => $record->id]);

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
     * Search patients by name (only those with appointments)
     */
    public function searchPatients(Request $request)
    {
        try {
            $searchTerm = $request->input('q');

            if (empty($searchTerm)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Only get patients who have at least one appointment
            $patients = User::with(['info', 'appointments.service'])
                ->where('role_id', 3) // Patient role
                ->whereHas('appointments') // Must have at least one appointment
                ->where(function($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhereHas('info', function($q) use ($searchTerm) {
                            $q->where('first_name', 'like', "%{$searchTerm}%")
                              ->orWhere('last_name', 'like', "%{$searchTerm}%");
                        });
                })
                ->limit(10)
                ->get();

            // Add appointment info to each patient
            $patientsWithAppointments = $patients->map(function($patient) {
                $latestAppointment = $patient->appointments()->latest('start_datetime')->first();
                $patient->latest_appointment = $latestAppointment;
                $patient->total_appointments = $patient->appointments()->count();
                return $patient;
            });

            return response()->json([
                'success' => true,
                'data' => $patientsWithAppointments
            ]);
        } catch (\Exception $e) {
            \Log::error('Error searching patients with appointments', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error searching patients: ' . $e->getMessage(),
                'data' => []
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
     * Get patient history
     */
    public function getPatientHistory($recordId)
    {
        $history = PatientHistory::where('patient_record_id', $recordId)->get();

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
        $validator = Validator::make($request->all(), [
            'patient_record_id' => 'required|exists:patient_records,id',
            'visit_date' => 'required|date',
            'procedure_performed' => 'nullable|string',
            'materials_used' => 'nullable|string',
            'anesthesia_used' => 'nullable|string',
            'complications' => 'nullable|string',
            'post_operative_instructions' => 'nullable|string',
            'follow_up_notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $history = PatientHistory::updateOrCreate(
            ['id' => $request->id],
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => 'Patient history saved successfully',
            'data' => $history
        ]);
    }

    /**
     * Get progress notes
     */
    public function getProgressNotes($recordId)
    {
        $notes = ProgressNote::where('patient_record_id', $recordId)
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
            'note_date' => 'required|date',
            'progress_description' => 'required|string',
            'treatment_response' => 'nullable|string',
            'next_steps' => 'nullable|string',
            'status' => 'required|in:ongoing,completed,followup_needed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $note = ProgressNote::updateOrCreate(
            ['id' => $request->id],
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress note saved successfully',
            'data' => $note
        ]);
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
     * Delete patient history
     */
    public function destroyPatientHistory($id)
    {
        $history = PatientHistory::findOrFail($id);
        $history->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient history deleted successfully'
        ]);
    }

    /**
     * Delete progress note
     */
    public function destroyProgressNote($id)
    {
        $note = ProgressNote::findOrFail($id);
        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progress note deleted successfully'
        ]);
    }
}
