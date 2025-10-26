<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentMonth = request('month', Carbon::now()->month);
        $currentYear = request('year', Carbon::now()->year);

        $appointments = Appointment::whereMonth('start_datetime', $currentMonth)
            ->whereYear('start_datetime', $currentYear)
            ->with(['patient.info', 'service'])
            ->get();

        $patients = User::whereHas('info', function($query) {
            $query->where('role_id', 3); // Assuming role_id 3 is for patients
        })->with('info')->get()->map(function($patient) {
            $info = $patient->info;
            return [
                'id' => $patient->id,
                'name' => $info ? trim($info->first_name . ' ' . $info->last_name) : $patient->name,
                'first_name' => $info ? $info->first_name : '',
                'last_name' => $info ? $info->last_name : '',
                'phone' => $info ? $info->phone : '',
                'email' => $patient->email
            ];
        });

        $staff = User::whereHas('info', function($query) {
            $query->whereIn('role_id', [1, 2]); // Assuming role_id 1,2 are for admin/staff
        })->get();

        $services = Service::active()->orderBy('service_name')->get();

        return view("admin.appointment", compact('appointments', 'patients', 'staff', 'services', 'currentMonth', 'currentYear'));
    }

    /**
     * Get appointments for calendar view
     */
    public function getAppointments(Request $request): JsonResponse
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $appointments = Appointment::whereBetween('start_datetime', [$start, $end])
            ->with(['patient.info', 'service'])
            ->get();

        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Appointment creation request:', $request->all());
            \Log::info('Request headers:', $request->headers->all());
            \Log::info('Is AJAX request:', ['ajax' => $request->ajax()]);
            \Log::info('Content type:', ['content_type' => $request->header('Content-Type')]);
            \Log::info('Accept header:', ['accept' => $request->header('Accept')]);

            $request->validate([
                'patient_id' => 'required|exists:users,id',
                'service_id' => 'nullable|exists:services,id',
                'start_datetime' => 'required|date',
                'duration_minutes' => 'nullable|integer|min:15|max:480',
                'status' => 'nullable|in:Pending,Confirmed,Completed,Cancelled',
                'notes' => 'nullable|string',
                'reason_for_visit' => 'nullable|string|max:255',
                'is_new_patient' => 'nullable|boolean'
            ]);

            // Additional validation: Check if patient already has appointment on same date
            $startDateTime = Carbon::parse($request->start_datetime);
            $appointmentDate = $startDateTime->toDateString();

            $existingAppointment = Appointment::where('patient_id', $request->patient_id)
                ->whereDate('start_datetime', $appointmentDate)
                ->first();

            if ($existingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This patient already has an appointment on this date',
                    'errors' => ['patient_id' => ['This patient already has an appointment on this date']]
                ], 422);
            }

            // Additional validation: Check for time overlaps
            $endDateTime = $startDateTime->copy()->addMinutes($request->duration_minutes ?? 30);

            $overlappingAppointment = Appointment::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where(function($q) use ($startDateTime, $endDateTime) {
                    // New appointment starts before existing ends AND new appointment ends after existing starts
                    $q->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
                });
            })->first();

            if ($overlappingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot conflicts with an existing appointment',
                    'errors' => ['start_datetime' => ['This time slot conflicts with an existing appointment']]
                ], 422);
            }

            // Calculate end_datetime based on start_datetime and duration (already calculated above)

            $appointmentData = $request->all();
            $appointmentData['end_datetime'] = $endDateTime;

            \Log::info('Appointment data to create:', $appointmentData);

            $appointment = Appointment::create($appointmentData);

            \Log::info('Appointment created successfully:', ['id' => $appointment->id]);

            // Always return JSON for POST requests to this endpoint
            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'appointment' => $appointment->load(['patient.info', 'service'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Appointment creation error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating appointment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::with(['patient.info', 'service'])->findOrFail($id);

        if (request()->ajax()) {
            return response()->json($appointment);
        }

        return view('admin.appointment.show', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);

            $request->validate([
                'patient_id' => 'required|exists:users,id',
                'service_id' => 'nullable|exists:services,id',
                'start_datetime' => 'required|date',
                'duration_minutes' => 'nullable|integer|min:15|max:480',
                'status' => 'nullable|in:Pending,Confirmed,Completed,Cancelled',
                'notes' => 'nullable|string',
                'reason_for_visit' => 'nullable|string|max:255',
                'is_new_patient' => 'nullable|boolean'
            ]);

            // Calculate end_datetime based on start_datetime and duration
            $startDateTime = Carbon::parse($request->start_datetime);
            $endDateTime = $startDateTime->copy()->addMinutes($request->duration_minutes ?? 30);

            $appointmentData = $request->all();
            $appointmentData['end_datetime'] = $endDateTime;

            $appointment->update($appointmentData);

            // Always return JSON for AJAX requests or when Accept header includes JSON
            if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment updated successfully',
                    'appointment' => $appointment->load(['patient.info', 'service'])
                ]);
            }

            return redirect()->route('admin-appointment')->with('success', 'Appointment updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error updating appointment:', ['error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating appointment: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin-appointment')->with('error', 'Error updating appointment.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $appointment = Appointment::findOrFail($id);
            \Log::info('Found appointment:', ['appointment' => $appointment]);

            $appointment->delete();
            \Log::info('Appointment deleted successfully');

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Appointment deleted successfully']);
            }

            return redirect()->route('admin-appointment')->with('success', 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting appointment:', ['error' => $e->getMessage()]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error deleting appointment: ' . $e->getMessage()], 500);
            }

            return redirect()->route('admin-appointment')->with('error', 'Error deleting appointment.');
        }
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Completed,Cancelled'
        ]);

        $appointment->update(['status' => $request->status]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'appointment' => $appointment->load(['patient', 'staff'])
            ]);
        }

        return redirect()->route('admin-appointment')->with('success', 'Appointment status updated successfully.');
    }

    public function searchPatients(Request $request)
    {
        $query = $request->input('query');

        $patients = User::whereHas('info', function($q) use ($query) {
            $q->where('role_id', 3); // Only search for patients (role_id = 3)

            // If query is not empty, add search conditions
            if (!empty($query)) {
                $q->where(function($subQuery) use ($query) {
                    $subQuery->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                });
            }
        })->with('info')->orderBy('name')->get();

        // Format the response to include full name
        $formattedPatients = $patients->map(function($patient) {
            $info = $patient->info;
            return [
                'id' => $patient->id,
                'name' => $info ? trim($info->first_name . ' ' . $info->last_name) : $patient->name,
                'first_name' => $info ? $info->first_name : '',
                'last_name' => $info ? $info->last_name : '',
                'phone' => $info ? $info->phone : '',
                'email' => $patient->email
            ];
        });

        return response()->json($formattedPatients);
    }
}
