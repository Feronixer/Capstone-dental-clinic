<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        $patientId = auth()->id();
        $appointments = $this->getPatientAppointments($patientId);
        $upcomingAppointments = $this->getUpcomingAppointments();
        $pendingRequests = $this->getPendingRequests();
        $appointmentHistory = $this->getAppointmentHistory();
        $allAppointments = $this->getAllAppointmentsForConflicts();
        $blockedTimes = $this->getBlockedTimes();
        $services = $this->getServices();

        return view("patient.calendar", compact(
            'appointments', 'upcomingAppointments', 'pendingRequests',
            'appointmentHistory', 'services', 'allAppointments', 'blockedTimes'
        ));
    }

    private function getPatientAppointments(int $patientId): array
    {
        $appointments = Appointment::where('patient_id', $patientId)
            ->with(['service'])
            ->orderBy('start_datetime', 'asc')
            ->get();

        return $appointments->map(function($appointment) {
            $data = [
                'id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'service_id' => $appointment->service_id,
                'duration_minutes' => $appointment->duration_minutes,
                'status' => $appointment->status ?? 'Pending',
                'notes' => $appointment->notes,
                'reason_for_visit' => $appointment->reason_for_visit,
                'is_new_patient' => $appointment->is_new_patient,
                'rescheduled_at' => $appointment->rescheduled_at?->format('Y-m-d H:i:s'),
                'original_datetime' => $appointment->original_datetime?->format('Y-m-d H:i:s'),
                'start_datetime' => $appointment->start_datetime->format('Y-m-d H:i:s'),
                'end_datetime' => $appointment->end_datetime->format('Y-m-d H:i:s'),
                'created_at' => $appointment->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $appointment->updated_at->format('Y-m-d H:i:s'),
            ];

            if ($appointment->relationLoaded('service') && $appointment->service) {
                $data['service'] = [
                    'id' => $appointment->service->id,
                    'service_name' => $appointment->service->service_name,
                    'default_duration_minutes' => $appointment->service->default_duration_minutes,
                ];
            } else {
                $data['service'] = null;
            }

            return $data;
        })->values()->toArray();
    }

    private function getUpcomingAppointments()
    {
        return Appointment::where('patient_id', auth()->id())
            ->where('start_datetime', '>=', Carbon::now())
            ->where(function($query) {
                $query->whereNull('status')
                      ->orWhereIn('status', ['Pending', 'Confirmed']);
            })
            ->with(['service'])
            ->orderBy('start_datetime', 'asc')
            ->limit(5)
            ->get();
    }

    private function getPendingRequests()
    {
        return AppointmentRequest::where('patient_id', auth()->id())
            ->where('status', 'Pending')
            ->with(['service', 'existingAppointment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getAppointmentHistory()
    {
        return Appointment::where('patient_id', auth()->id())
            ->where(function($query) {
                $query->where(function($q) {
                    $q->where('start_datetime', '<', Carbon::now())
                      ->whereIn('status', ['Completed', 'Cancelled', 'Missed']);
                })->orWhere('status', 'Cancelled');
            })
            ->with(['service'])
            ->orderBy('start_datetime', 'desc')
            ->limit(10)
            ->get();
    }

    private function getAllAppointmentsForConflicts(): array
    {
        return Appointment::where('start_datetime', '>=', Carbon::now()->startOfDay())
            ->where('status', '!=', 'Cancelled')
            ->get()
            ->map(fn($apt) => [
                'start_datetime' => $apt->start_datetime->format('Y-m-d H:i:s'),
                'end_datetime' => $apt->end_datetime->format('Y-m-d H:i:s'),
            ])
            ->values()
            ->toArray();
    }

    private function getBlockedTimes(): array
    {
        return \App\Models\BlockedTime::where('start_datetime', '>=', Carbon::now()->startOfDay())
            ->get()
            ->map(fn($bt) => [
                'id' => $bt->id,
                'title' => $bt->title,
                'start_datetime' => $bt->start_datetime->format('Y-m-d H:i:s'),
                'end_datetime' => $bt->end_datetime->format('Y-m-d H:i:s'),
                'duration_minutes' => $bt->duration_minutes,
                'notes' => $bt->notes,
            ])
            ->values()
            ->toArray();
    }

    private function getServices()
    {
        return Service::active()
            ->orderBy('id', 'desc')
            ->get()
            ->unique('service_name')
            ->values()
            ->sortBy('service_name')
            ->values();
    }

    /**
     * Get current server time (for client synchronization)
     */
    public function getServerTime(): JsonResponse
    {
        $serverTime = Carbon::now('Asia/Manila');
        return response()->json([
            'success' => true,
            'server_time' => $serverTime->format('Y-m-d H:i:s'),
            'server_timestamp' => $serverTime->timestamp,
            'timezone' => 'Asia/Manila',
            'date' => $serverTime->format('Y-m-d'),
            'time' => $serverTime->format('H:i:s'),
            'datetime' => $serverTime->toIso8601String()
        ]);
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

            $requestedDateTime = Carbon::parse($request->date . ' ' . $request->time, 'Asia/Manila');

            // Validate date is not in past
            if ($error = $this->validateRequestDateTime($requestedDateTime)) {
                return $error;
            }

            // Check if rescheduling a missed or cancelled appointment (prevent this)
            if ($request->type === 'reschedule' && $request->existing_appointment_id) {
                $existingAppointment = Appointment::find($request->existing_appointment_id);
                if ($existingAppointment && $existingAppointment->status === 'Missed') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot reschedule missed appointments. Please book a new appointment instead.',
                        'errors' => ['existing_appointment_id' => ['Cannot reschedule missed appointments']]
                    ], 422);
                }
                if ($existingAppointment && $existingAppointment->status === 'Cancelled') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot reschedule cancelled appointments. Please book a new appointment instead.',
                        'errors' => ['existing_appointment_id' => ['Cannot reschedule cancelled appointments']]
                    ], 422);
                }
                // Also verify the appointment belongs to the current patient
                if ($existingAppointment && $existingAppointment->patient_id !== auth()->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized: This appointment does not belong to you.',
                        'errors' => ['existing_appointment_id' => ['Unauthorized access']]
                    ], 403);
                }
            }

            // Determine service and duration
            [$serviceId, $otherConcern, $durationMinutes] = $this->determineServiceAndDuration($request);

            // Check for conflicts
            if ($error = $this->checkTimeConflicts($requestedDateTime, $durationMinutes)) {
                return $error;
            }

            $requestedEndDateTime = $requestedDateTime->copy()->addMinutes($durationMinutes);

            // Create appointment request
            $appointmentRequest = $this->createAppointmentRequest($request, $serviceId, $otherConcern, $durationMinutes, $requestedDateTime, $requestedEndDateTime);

            // Send notifications
            $this->sendNotificationToStaff($appointmentRequest, $request, $requestedDateTime);

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

    private function validateRequestDateTime(Carbon $requestedDateTime)
    {
            $serverNow = Carbon::now('Asia/Manila');
            if ($requestedDateTime->lt($serverNow)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot request appointments in the past. Please select a future date and time.',
                    'errors' => ['time' => ['Cannot request appointments in the past']]
                ], 422);
        }
        return null;
            }

    private function determineServiceAndDuration(Request $request): array
    {
            $serviceId = $request->service_id;
            $otherConcern = $request->other_concern;
        $durationMinutes = null;

            if ($request->type === 'reschedule' && $request->existing_appointment_id) {
            [$serviceId, $otherConcern, $durationMinutes] = $this->getRescheduleDetails($request->existing_appointment_id);
        } else {
            [$serviceId, $otherConcern, $durationMinutes] = $this->getEmergencyDetails($serviceId, $otherConcern);
        }

        return [$serviceId, $otherConcern, $durationMinutes ?? 30];
    }

    private function getRescheduleDetails($appointmentId): array
    {
        $existingAppointment = Appointment::with('service')->find($appointmentId);

        if (!$existingAppointment) {
            \Log::error('Reschedule - Existing appointment not found:', ['existing_appointment_id' => $appointmentId]);
            return [null, null, null];
        }

                    if ($existingAppointment->status === 'Missed') {
                throw new \Exception('Cannot reschedule missed appointments. Please book a new appointment instead.');
            }
            if ($existingAppointment->status === 'Cancelled') {
                throw new \Exception('Cannot reschedule cancelled appointments. Please book a new appointment instead.');
            }

                    $serviceId = $existingAppointment->service_id;
        $otherConcern = $serviceId ? null : $existingAppointment->reason_for_visit;

                    if ($serviceId && $existingAppointment->service) {
                        $durationMinutes = $existingAppointment->service->default_duration_minutes;
                    } else {
                        $durationMinutes = $existingAppointment->duration_minutes;
        }

        return [$serviceId, $otherConcern, $durationMinutes];
    }

    private function getEmergencyDetails($serviceId, $otherConcern): array
    {
        $durationMinutes = null;

                if ($serviceId) {
                    $service = Service::find($serviceId);
            $durationMinutes = $service ? $service->default_duration_minutes : 30;
                } elseif ($otherConcern) {
                    $serviceId = null;
                    $durationMinutes = 30;
            }

        return [$serviceId, $otherConcern, $durationMinutes];
            }

    private function checkTimeConflicts(Carbon $requestedDateTime, int $durationMinutes)
    {
            $requestedEndDateTime = $requestedDateTime->copy()->addMinutes($durationMinutes);

        // Check blocked times
            $blockedTime = \App\Models\BlockedTime::where(function($query) use ($requestedDateTime, $requestedEndDateTime) {
                $query->where('start_datetime', '<', $requestedEndDateTime)
                      ->where('end_datetime', '>', $requestedDateTime);
            })->first();

            if ($blockedTime) {
                $isFullDayClosure = $blockedTime->start_datetime->format('H:i') === '00:00' &&
                                    $blockedTime->end_datetime->format('H:i') === '23:59';

                $message = $isFullDayClosure
                    ? 'The clinic is closed on this date. Please select a different date for your appointment request.'
                    : 'This time slot is blocked. Please select a different time slot for your appointment request.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['time' => [$message]]
                ], 422);
            }

        // Check existing appointments
            $conflictingAppointment = Appointment::where(function($query) use ($requestedDateTime, $requestedEndDateTime) {
                $query->where('start_datetime', '<', $requestedEndDateTime)
                      ->where('end_datetime', '>', $requestedDateTime)
                      ->where('status', '!=', 'Cancelled');
            })->first();

            if ($conflictingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot is already booked. Please select a different time slot.',
                    'errors' => ['time' => ['This time slot is already booked. Please select a different time slot.']]
                ], 422);
            }

        return null;
    }

    private function createAppointmentRequest(Request $request, $serviceId, $otherConcern, $durationMinutes, Carbon $requestedDateTime, Carbon $requestedEndDateTime)
    {
            \Log::info('Creating AppointmentRequest with:', [
                'patient_id' => auth()->id(),
                'service_id' => $serviceId,
                'other_concern' => $otherConcern,
                'existing_appointment_id' => $request->existing_appointment_id,
                'request_type' => $request->type === 'emergency' ? 'walk-in' : 'reschedule',
                'duration_minutes' => $durationMinutes,
                'reason' => $request->reason
            ]);

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

        return $appointmentRequest;
    }

    private function sendNotificationToStaff($appointmentRequest, Request $request, Carbon $requestedDateTime)
    {
            $patient = User::with('info')->find(auth()->id());
            $patientName = $patient->info ? trim($patient->info->first_name . ' ' . $patient->info->last_name) : $patient->name;

            $formattedDate = $requestedDateTime->format('F j, Y');
            $formattedTime = $requestedDateTime->format('g:i A');

        $serviceName = $this->getServiceName($appointmentRequest->service_id, $appointmentRequest->other_concern);

            $adminStaff = User::whereHas('info', function($query) {
            $query->whereIn('role_id', [1, 2]);
            })->get();

            foreach ($adminStaff as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'type' => 'appointment_request',
                    'title' => $request->type === 'emergency' ? 'New Walk-in Request' : 'New Reschedule Request',
                    'message' => "{$patientName} has requested a " .
                                ($request->type === 'emergency' ? 'walk-in appointment' : 'reschedule') .
                                " for {$serviceName} on {$formattedDate} at {$formattedTime}.",
                    'icon' => 'bi-calendar-plus',
                    'data' => json_encode([
                        'request_id' => $appointmentRequest->id,
                        'patient_id' => auth()->id(),
                        'patient_name' => $patientName,
                        'service_name' => $serviceName,
                        'request_type' => $request->type,
                        'requested_date' => $formattedDate,
                        'requested_time' => $formattedTime,
                        'reason' => $request->reason
                    ])
                ]);
        }
    }

    private function getServiceName($serviceId, $otherConcern): string
    {
        if ($serviceId) {
            $service = Service::find($serviceId);
            return $service ? $service->service_name : 'Service Not Found';
        }
        return $otherConcern ?: 'Unknown Service';
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
