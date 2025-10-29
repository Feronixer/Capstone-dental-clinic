<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use App\Models\PatientHistory;
use App\Models\ProgressNote;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class PostProceduralController extends Controller
{
    /**
     * Display the post-procedural page for staff
     */
    public function index()
    {
        // Check if user is staff (role_id 1 or 2)
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 2])) {
            abort(403, 'Unauthorized access');
        }

        $records = PatientRecord::with(['user.info', 'appointment.service', 'progressNotes', 'patientHistories'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view("staff.post-procedural", compact('records'));
    }

    /**
     * Get all patient records (API endpoint for AJAX)
     */
    public function getRecords()
    {
        try {
            $patientRecords = PatientRecord::with(['user.info', 'appointment.service'])->get()->map([$this, 'mapPatientRecord']);
            $patientHistories = PatientHistory::with(['patientRecord.user.info'])->get()->map([$this, 'mapPatientHistory']);
            $progressNotes = ProgressNote::with(['patientRecord.user.info'])->get()->map([$this, 'mapProgressNote']);

            $allRecords = $patientRecords->concat($patientHistories)->concat($progressNotes)->sortByDesc('created_at')->values();

            return response()->json(['success' => true, 'records' => $allRecords]);
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
                    'data' => [],
                    'patients' => []
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
                        'birthdate' => $user->info ? $user->info->birthdate : '',
                        'age' => $user->info && $user->info->birthdate
                            ? \Carbon\Carbon::parse($user->info->birthdate)->age
                            : '',
                        'sex' => $user->info ? $user->info->sex : '',
                        'religion' => $user->info ? $user->info->religion : '',
                        'nationality' => $user->info ? $user->info->nationality : '',
                        'contact_number' => $user->info ? $user->info->contact_number : '',
                        'home_address' => $user->info ? $user->info->home_address : '',
                        'occupation' => $user->info ? $user->info->occupation : '',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $patients,
                'patients' => $patients // Also include patients key for compatibility
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
            'patient_name' => $patientName,
            'username' => $user?->name ?? 'N/A',
            'patient_number' => $history->patientRecord?->patient_number ?? 'N/A',
            'related_info' => $history->visit_date ? 'Visit: ' . \Carbon\Carbon::parse($history->visit_date)->format('M d, Y') : 'No visit date',
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
            \Log::info('Staff Store Patient Record Request', $request->all());

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

                // Custom error message for user_id
                $errors = $validator->errors();
                $message = 'Validation failed: ' . $errors->first();

                if ($errors->has('user_id')) {
                    $message = 'Please select a patient before saving the record.';
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => $errors
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
                }
            }

            // Update or create the record
            if (isset($data['id']) && !empty($data['id'])) {
                $record = PatientRecord::findOrFail($data['id']);
                $record->update($data);
                \Log::info('Staff updated patient record', ['record_id' => $record->id]);
            } else {
                $record = PatientRecord::create($data);
                \Log::info('Staff created patient record', ['record_id' => $record->id]);
            }

            // Send notification to patient about record update
            try {
                NotificationService::recordUpdated($record->user_id, 'medical record');
            } catch (\Exception $e) {
                \Log::error('Failed to send record update notification:', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient record saved successfully',
                'data' => $record->load(['user.info', 'appointment.service'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving patient record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete patient record - RESTRICTED TO ADMIN ONLY
     */
    public function destroyPatientRecord($id)
    {
        // Check if user is admin (role_id = 1)
        $user = Auth::user();
        if ($user->role_id !== 1) {
            \Log::warning('Staff user attempted to delete patient record', [
                'user_id' => $user->id,
                'record_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only administrators can delete patient records.'
            ], 403);
        }

        try {
            $record = PatientRecord::findOrFail($id);
            $record->delete();

            \Log::info('Admin deleted patient record via staff route', ['record_id' => $id, 'admin_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Patient record deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting patient record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting record: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Send record to patient
     */
    public function sendToPatient(Request $request)
    {
        try {
            $recordId = $request->input('record_id');

            $record = PatientRecord::findOrFail($recordId);
            $record->sent_to_patient = true;
            $record->save();

            \Log::info('Staff sent record to patient', ['record_id' => $recordId]);

            return response()->json([
                'success' => true,
                'message' => 'Record sent to patient successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending record to patient: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error sending record: ' . $e->getMessage()
            ], 500);
        }
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

            if ($record) {
                return response()->json([
                    'success' => true,
                    'data' => $record,
                    'userInfo' => $record->user->info ?? null
                ]);
            } else {
                // Return user info even if no record exists
                $user = User::with('info')->find($userId);
                return response()->json([
                    'success' => false,
                    'message' => 'No patient record found for this user',
                    'userInfo' => $user->info ?? null
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

    public function getPatientHistory($patientRecordId)
    {
        $histories = PatientHistory::where('patient_record_id', $patientRecordId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $histories
        ]);
    }

    /**
     * Store patient history
     */
    public function storePatientHistory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'patient_record_id' => 'required|exists:patient_records,id',
                'history_date' => 'required|date',
                'condition' => 'required|string',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $history = PatientHistory::create($request->all());
            \Log::info('Staff created patient history', ['history_id' => $history->id]);

            // Send notification to patient about history update
            try {
                $record = PatientRecord::find($request->input('patient_record_id'));
                if ($record) {
                    NotificationService::recordUpdated($record->user_id, 'medical history');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send history update notification:', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient history saved successfully',
                'data' => $history
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving patient history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete patient history - RESTRICTED TO ADMIN ONLY
     */
    public function destroyPatientHistory($id)
    {
        // Check if user is admin (role_id = 1)
        $user = Auth::user();
        if ($user->role_id !== 1) {
            \Log::warning('Staff user attempted to delete patient history', [
                'user_id' => $user->id,
                'id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only administrators can delete patient history records.'
            ], 403);
        }

        try {
            \Log::info('Delete Patient History Request', ['id' => $id, 'admin_id' => $user->id]);

            // Check if we're deleting by patient_record_id (all histories) or by history id (single)
            $patientHistories = PatientHistory::where('patient_record_id', $id)->get();

            if ($patientHistories->count() > 0) {
                // Delete all histories for this patient record
                PatientHistory::where('patient_record_id', $id)->delete();
                \Log::info('Admin deleted all patient histories for record via staff route', ['patient_record_id' => $id, 'count' => $patientHistories->count(), 'admin_id' => $user->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'All patient histories deleted successfully'
                ]);
            } else {
                // Try deleting by history ID
                $history = PatientHistory::findOrFail($id);
                $history->delete();
                \Log::info('Admin deleted patient history via staff route', ['history_id' => $id, 'admin_id' => $user->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Patient history deleted successfully'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting patient history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get progress notes
     */
    public function getProgressNotes($patientRecordId)
    {
        $notes = ProgressNote::where('patient_record_id', $patientRecordId)
            ->orderBy('note_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    /**
     * Store progress note
     */
    public function storeProgressNote(Request $request)
    {
        try {
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

            // Check if updating existing note
            if ($request->has('id') && $request->input('id')) {
                $note = ProgressNote::findOrFail($request->input('id'));
                $note->update($request->all());
                \Log::info('Staff updated progress note', ['note_id' => $note->id]);
            } else {
                $note = ProgressNote::create($request->all());
                \Log::info('Staff created progress note', ['note_id' => $note->id]);
            }

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
        } catch (\Exception $e) {
            \Log::error('Error saving progress note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete progress note - RESTRICTED TO ADMIN ONLY
     */
    public function destroyProgressNote($id)
    {
        // Check if user is admin (role_id = 1)
        $user = Auth::user();
        if ($user->role_id !== 1) {
            \Log::warning('Staff user attempted to delete progress note', [
                'user_id' => $user->id,
                'id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only administrators can delete progress notes.'
            ], 403);
        }

        try {
            \Log::info('Delete Progress Note Request', ['id' => $id, 'admin_id' => $user->id]);

            // Check if we're deleting by patient_record_id (all notes) or by note id (single)
            $progressNotes = ProgressNote::where('patient_record_id', $id)->get();

            if ($progressNotes->count() > 0) {
                // Delete all notes for this patient record
                ProgressNote::where('patient_record_id', $id)->delete();
                \Log::info('Admin deleted all progress notes for record via staff route', ['patient_record_id' => $id, 'count' => $progressNotes->count(), 'admin_id' => $user->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'All progress notes deleted successfully'
                ]);
            } else {
                // Try deleting by note ID
                $note = ProgressNote::findOrFail($id);
                $note->delete();
                \Log::info('Admin deleted progress note via staff route', ['note_id' => $id, 'admin_id' => $user->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Progress note deleted successfully'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting progress note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple progress notes at once
     */
    public function storeProgressNotes(Request $request)
    {
        try {
            \Log::info('Staff Store Progress Notes Request', $request->all());

            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|exists:users,id',
                'notes' => 'required|array|min:1',
                'notes.*.date' => 'required|date',
                'notes.*.progressNote' => 'nullable|string',
                'notes.*.oralHygiene' => 'nullable|string',
                'notes.*.conformedPractices' => 'nullable|string',
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

            // Save each progress note
            $savedNotes = [];
            foreach ($request->input('notes') as $noteData) {
                // Skip empty rows
                if (empty($noteData['progressNote']) && empty($noteData['oralHygiene']) && empty($noteData['conformedPractices'])) {
                    continue;
                }

                $note = ProgressNote::create([
                    'patient_record_id' => $patientRecord->id,
                    'note_date' => $noteData['date'],
                    'progress_description' => $noteData['progressNote'] ?? null,
                    'treatment_response' => $noteData['oralHygiene'] ?? null,
                    'next_steps' => $noteData['conformedPractices'] ?? null,
                    'status' => 'ongoing'
                ]);

                $savedNotes[] = $note;
            }

            // Add other notes to patient record if provided
            if ($request->has('other_notes') && !empty($request->input('other_notes'))) {
                $currentNotes = $patientRecord->notes ?? '';
                $timestamp = now()->format('Y-m-d H:i');
                $newNote = "\n\n[{$timestamp}] Progress Note - Other Notes:\n{$request->input('other_notes')}";
                $patientRecord->update([
                    'notes' => $currentNotes . $newNote,
                    'sent_to_patient' => true,
                    'sent_at' => now()
                ]);
            } else {
                // Mark as sent even if no other notes
                $patientRecord->update([
                    'sent_to_patient' => true,
                    'sent_at' => now()
                ]);
            }

            // Send notification to patient
            try {
                NotificationService::recordUpdated($patientId, 'progress notes');
            } catch (\Exception $e) {
                \Log::error('Failed to send progress notes notification:', ['error' => $e->getMessage()]);
            }

            \Log::info('Progress notes saved successfully by staff', [
                'patient_record_id' => $patientRecord->id,
                'notes_count' => count($savedNotes),
                'staff_id' => Auth::id()
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
}

