<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Models\Service;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the currently authenticated patient's appointments (exclude cancelled)
        $appointments = Appointment::where('patient_id', auth()->id())
            ->where('status', '!=', 'Cancelled')
            ->with(['service'])
            ->orderBy('start_datetime', 'asc')
            ->get()
            ->map(function($appointment) {
                // Format dates as 'Y-m-d H:i:s' string without timezone to avoid JS conversion
                $data = $appointment->toArray();
                $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
                $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');
                return $data;
            });

        // Get upcoming appointments (future appointments only)
        $upcomingAppointments = Appointment::where('patient_id', auth()->id())
            ->where('start_datetime', '>=', Carbon::now())
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->with(['service'])
            ->orderBy('start_datetime', 'asc')
            ->limit(5)
            ->get();

        // Get ALL appointments (from all patients) for conflict checking - exclude cancelled
        $allAppointments = Appointment::where('start_datetime', '>=', Carbon::now()->startOfDay())
            ->where('status', '!=', 'Cancelled')
            ->get()
            ->map(function($appointment) {
                return [
                    'start_datetime' => $appointment->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $appointment->end_datetime->format('Y-m-d H:i:s'),
                ];
            });

        // Get blocked times for conflict checking
        $blockedTimes = \App\Models\BlockedTime::where('start_datetime', '>=', Carbon::now()->startOfDay())
            ->get()
            ->map(function($blockedTime) {
                return [
                    'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                ];
            });

        // Get services for the form
        $services = Service::active()->orderBy('service_name')->get();

        return view("patient.calendar", compact('appointments', 'upcomingAppointments', 'services', 'allAppointments', 'blockedTimes'));
    }

    /**
     * Submit appointment request (walk-in or reschedule)
     */
    public function submitRequest(Request $request)
    {
        try {
            \Log::info('Patient appointment request:', $request->all());

            $validated = $request->validate([
                'type' => 'required|in:emergency,reschedule',
                'reason' => 'required|string',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required',
                'service_id' => 'nullable|exists:services,id',
                'other_concern' => 'nullable|string|max:255',
                'existing_appointment_id' => 'nullable|exists:appointments,id'
            ]);

            // Create datetime string
            $requestedDateTime = Carbon::parse($request->date . ' ' . $request->time, 'Asia/Manila');

            // Determine service_id, other_concern, and duration based on request type
            $serviceId = $request->service_id;
            $otherConcern = $request->other_concern;
            $durationMinutes = null; // Will be set based on service or default to 30 for "Other"

            // If this is a reschedule request, get service_id and duration from existing appointment
            if ($request->type === 'reschedule' && $request->existing_appointment_id) {
                $existingAppointment = Appointment::with('service')->find($request->existing_appointment_id);

                if ($existingAppointment) {
                    $serviceId = $existingAppointment->service_id;
                    $otherConcern = $existingAppointment->service_id ? null : $existingAppointment->reason_for_visit;

                    // For reschedule, use the service's current default duration (to correct any discrepancies)
                    // If no service, use the stored duration from the appointment
                    if ($serviceId && $existingAppointment->service) {
                        $durationMinutes = $existingAppointment->service->default_duration_minutes;
                        \Log::info('Reschedule - Using service default duration:', [
                            'appointment_id' => $existingAppointment->id,
                            'service_id' => $serviceId,
                            'service_name' => $existingAppointment->service->service_name,
                            'service_default_duration' => $durationMinutes,
                            'appointment_stored_duration' => $existingAppointment->duration_minutes
                        ]);
                    } else {
                        // No service (custom/other), use the appointment's stored duration
                        $durationMinutes = $existingAppointment->duration_minutes;
                        \Log::info('Reschedule - Using appointment stored duration (no service):', [
                            'appointment_id' => $existingAppointment->id,
                            'duration_minutes' => $durationMinutes,
                            'reason_for_visit' => $existingAppointment->reason_for_visit
                        ]);
                    }
                } else {
                    \Log::error('Reschedule - Existing appointment not found:', [
                        'existing_appointment_id' => $request->existing_appointment_id
                    ]);
                }
            } else {
                // For emergency walk-in requests
                // If a service is selected, fetch its actual duration from database
                if ($serviceId) {
                    $service = Service::find($serviceId);
                    if ($service) {
                        // Use the service's default_duration_minutes from the database
                        $durationMinutes = $service->default_duration_minutes;
                    } else {
                        // Service not found, use fallback
                        $durationMinutes = 30;
                    }
                } elseif ($otherConcern) {
                    // If service_id is null but other_concern is provided (emergency walk-in with "Other" selected)
                    // Use default duration of 30 minutes for custom services
                    $serviceId = null;
                    $durationMinutes = 30;
                }
            }

            // Final safety check - if duration is still null, default to 30
            if ($durationMinutes === null) {
                $durationMinutes = 30;
            }

            $requestedEndDateTime = $requestedDateTime->copy()->addMinutes($durationMinutes);

            // Log the data before creating appointment request
            \Log::info('Creating AppointmentRequest with:', [
                'patient_id' => auth()->id(),
                'service_id' => $serviceId,
                'other_concern' => $otherConcern,
                'existing_appointment_id' => $request->existing_appointment_id,
                'request_type' => $request->type === 'emergency' ? 'walk-in' : 'reschedule',
                'duration_minutes' => $durationMinutes,
                'reason' => $request->reason
            ]);

            // Create the appointment request
            $appointmentRequest = AppointmentRequest::create([
                'patient_id' => auth()->id(),
                'service_id' => $serviceId,
                'other_concern' => $otherConcern,
                'existing_appointment_id' => $request->existing_appointment_id,
                'request_type' => $request->type === 'emergency' ? 'walk-in' : 'reschedule',
                'requested_datetime' => $requestedDateTime,
                'requested_end_datetime' => $requestedEndDateTime,
                'duration_minutes' => $durationMinutes,
                'reason' => $request->reason,
                'status' => 'Pending'
            ]);

            \Log::info('AppointmentRequest created:', [
                'id' => $appointmentRequest->id,
                'service_id' => $appointmentRequest->service_id,
                'duration_minutes' => $appointmentRequest->duration_minutes,
                'request_type' => $appointmentRequest->request_type
            ]);

            // Get patient info
            $patient = User::with('info')->find(auth()->id());
            $patientName = $patient->info ? trim($patient->info->first_name . ' ' . $patient->info->last_name) : $patient->name;

            // Format datetime for display
            $formattedDate = $requestedDateTime->format('F j, Y');
            $formattedTime = $requestedDateTime->format('g:i A');

            // Create notifications for all admins and staff
            $adminStaff = User::whereHas('info', function($query) {
                $query->whereIn('role_id', [1, 2]); // Admin and Staff
            })->get();

            foreach ($adminStaff as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'type' => 'appointment_request',
                    'title' => $request->type === 'emergency' ? 'New Walk-in Request' : 'New Reschedule Request',
                    'message' => "{$patientName} has requested a " .
                                ($request->type === 'emergency' ? 'walk-in appointment' : 'reschedule') .
                                " on {$formattedDate} at {$formattedTime}.",
                    'icon' => 'bi-calendar-plus',
                    'data' => json_encode([
                        'request_id' => $appointmentRequest->id,
                        'patient_id' => auth()->id(),
                        'patient_name' => $patientName,
                        'request_type' => $request->type,
                        'requested_date' => $formattedDate,
                        'requested_time' => $formattedTime,
                        'reason' => $request->reason
                    ])
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Your request has been submitted. You will be notified once it is reviewed.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error submitting appointment request:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error submitting request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
