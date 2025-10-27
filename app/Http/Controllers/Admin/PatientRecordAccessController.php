<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PatientRecord;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientRecordAccessController extends Controller
{
    /**
     * Display the patient records access page
     */
    public function index()
    {
        $totalPatients = User::where('role_id', 3)->count();

        return view('admin.patient-records', compact('totalPatients'));
    }

    /**
     * Search for patients
     */
    public function searchPatients(Request $request)
    {
        $query = $request->input('query', '');
        $status = $request->input('status', '');

        $patients = User::where('role_id', 3)
            ->with('info')
            ->withCount('appointments');

        // Search by name, email, or ID
        if ($query) {
            $patients->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('id', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%");
            });
        }

        // Filter by status
        if ($status === 'with_appointments') {
            $patients->has('appointments');
        } elseif ($status === 'with_records') {
            $patients->whereHas('patientRecords');
        } elseif ($status === 'active') {
            $patients->whereHas('appointments', function($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('status', 'pending');
            });
        }

        $results = $patients->orderBy('name', 'asc')
            ->limit(50)
            ->get()
            ->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                    'username' => $patient->username,
                    'appointments_count' => $patient->appointments_count,
                    'has_records' => $patient->patientRecords()->exists(),
                    'phone' => $patient->info->phone ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'patients' => $results
        ]);
    }

    /**
     * Get detailed patient information
     */
    public function getPatientDetails($patientId)
    {
        $patient = User::with('info')
            ->where('id', $patientId)
            ->where('role_id', 3)
            ->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        // Get patient record
        $record = PatientRecord::where('user_id', $patientId)->first();

        // Get patient history
        $history = [];
        if ($record) {
            $history = DB::table('patient_histories')
                ->where('patient_record_id', $record->id)
                ->orderBy('visit_date', 'desc')
                ->get()
                ->toArray();
        }

        // Get progress notes
        $notes = [];
        if ($record) {
            $notes = DB::table('progress_notes')
                ->where('patient_record_id', $record->id)
                ->orderBy('note_date', 'desc')
                ->get()
                ->toArray();
        }

        // Get appointments
        $appointments = Appointment::where('patient_id', $patientId)
            ->with('service')
            ->orderBy('start_datetime', 'desc')
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'start_datetime' => $appointment->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $appointment->end_datetime->format('Y-m-d H:i:s'),
                    'status' => $appointment->status,
                    'notes' => $appointment->notes,
                    'service' => $appointment->service ? [
                        'id' => $appointment->service->id,
                        'service_name' => $appointment->service->service_name,
                        'price' => $appointment->service->price
                    ] : null
                ];
            });

        return response()->json([
            'success' => true,
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'username' => $patient->username,
                'info' => $patient->info
            ],
            'record' => $record,
            'history' => $history,
            'notes' => $notes,
            'appointments' => $appointments
        ]);
    }

    /**
     * Export patient record as PDF (optional feature)
     */
    public function exportPatientRecord($patientId)
    {
        // This can be implemented later with a PDF library like DomPDF
        // For now, return a message
        return response()->json([
            'success' => false,
            'message' => 'PDF export feature coming soon'
        ]);
    }
}

