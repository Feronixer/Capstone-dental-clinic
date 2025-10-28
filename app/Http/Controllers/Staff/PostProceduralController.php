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

        $records = PatientRecord::with(['user.info', 'appointment.service'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view("staff.post-procedural", compact('records'));
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
     * Search for patients
     */
    public function searchPatients(Request $request)
    {
        $searchTerm = $request->input('search', '');

        if (strlen($searchTerm) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search term must be at least 2 characters'
            ]);
        }

        // Only get patients who have at least one appointment
        $patients = User::with(['info', 'appointments.service'])
            ->where('role_id', 3) // Patient role
            ->whereHas('appointments') // Must have at least one appointment
            ->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhereHas('info', function($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%{$searchTerm}%")
                          ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    });
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'patients' => $patients
        ]);
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
     * Get patient history
     */
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
                'history_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only administrators can delete patient history records.'
            ], 403);
        }

        try {
            $history = PatientHistory::findOrFail($id);
            $history->delete();

            \Log::info('Admin deleted patient history via staff route', ['history_id' => $id, 'admin_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Patient history deleted successfully'
            ]);
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
                'note_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only administrators can delete progress notes.'
            ], 403);
        }

        try {
            $note = ProgressNote::findOrFail($id);
            $note->delete();

            \Log::info('Admin deleted progress note via staff route', ['note_id' => $id, 'admin_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Progress note deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting progress note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting note: ' . $e->getMessage()
            ], 500);
        }
    }
}

