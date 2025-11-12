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
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->autoCancelStalePendingAppointments();
        $patientId = auth()->id();
        $appointments = $this->getPatientAppointments($patientId);
        $upcomingAppointments = $this->getUpcomingAppointments();
        $reschedulableAppointments = $this->getReschedulableAppointments($patientId);
        $pendingRequests = $this->getPendingRequests();
        $appointmentHistory = $this->getAppointmentHistory();
        $allAppointments = $this->getAllAppointmentsForConflicts();
        $blockedTimes = $this->getBlockedTimes();
        $services = $this->getServices();

        // Debug: Log appointment counts
        \Log::info('CalendarController@index - Appointment counts', [
            'patient_appointments' => count($appointments),
            'all_appointments' => count($allAppointments),
            'patient_id' => $patientId
        ]);

        $chatbotSetting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $chatbotFaqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question', 'answer']);

        return view('patient.calendar', [
            'appointments' => $appointments,
            'upcomingAppointments' => $upcomingAppointments,
            'reschedulableAppointments' => $reschedulableAppointments,
            'pendingRequests' => $pendingRequests,
            'appointmentHistory' => $appointmentHistory,
            'services' => $services,
            'allAppointments' => $allAppointments,
            'blockedTimes' => $blockedTimes,
            'chatbotSetting' => $chatbotSetting,
            'chatbotFaqs' => $chatbotFaqs,
        ]);
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

    /**
     * Automatically cancel pending appointments whose appointment date has passed
     */
    private function autoCancelStalePendingAppointments(): void
    {
        try {
            $now = Carbon::now('Asia/Manila');
            $todayStart = $now->copy()->startOfDay();

            $pendingAppointments = Appointment::where('status', 'Pending')
                ->whereDate('start_datetime', '<', $todayStart)
                ->get();

            foreach ($pendingAppointments as $appointment) {
                $existingNotes = trim((string) $appointment->notes);
                $autoNote = '[' . $now->format('Y-m-d H:i') . '] Automatically cancelled because the appointment remained pending past its date.';

                if (stripos($existingNotes, 'Automatically cancelled because the appointment remained pending past its date.') === false) {
                    $existingNotes = $existingNotes !== ''
                        ? $existingNotes . "\n\n" . $autoNote
                        : $autoNote;
                }

                $appointment->update([
                    'status' => 'Cancelled',
                    'notes' => $existingNotes,
                ]);

                \Log::info('Auto-cancelled pending appointment past date (patient calendar)', [
                    'appointment_id' => $appointment->id,
                    'start_datetime' => $appointment->start_datetime,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error auto-cancelling stale pending appointments (patient calendar):', ['error' => $e->getMessage()]);
        }
    }

    private function getUpcomingAppointments()
    {
        // Get future appointments (from today onwards) that are not cancelled, completed, or missed
        // This includes: Pending, Confirmed appointments only
        $today = Carbon::now()->startOfDay();
        
        return Appointment::where('patient_id', auth()->id())
            ->where('start_datetime', '>=', $today)
            ->where(function($query) {
                // Include only Pending and Confirmed appointments
                // Exclude Cancelled, Completed, and Missed appointments
                $query->whereNull('status')
                      ->orWhereIn('status', ['Pending', 'Confirmed']);
            })
            ->with(['service'])
            ->orderBy('start_datetime', 'asc')
            ->limit(15)
            ->get();
    }

    private function getReschedulableAppointments(int $patientId)
    {
        $autoCancelPhrase = 'automatically cancelled because the appointment remained pending past its date.';

        return Appointment::where('patient_id', $patientId)
            ->with(['service'])
            ->orderBy('start_datetime', 'desc')
            ->limit(40)
            ->get()
            ->filter(function ($appointment) use ($autoCancelPhrase) {
                $status = strtolower($appointment->status ?? 'pending');
                $notes = strtolower((string) $appointment->notes);

                if (in_array($status, ['pending', 'confirmed'])) {
                    return true;
                }

                if ($status === 'missed') {
                    return true;
                }

                if ($status === 'cancelled' && str_contains($notes, strtolower($autoCancelPhrase))) {
                    return true;
                }

                return false;
            })
            ->values();
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
        $patientId = auth()->id();
        
        // Get appointments from past month to future months for calendar display
        // This allows patients to see booked time slots in the calendar view
        $startDate = Carbon::now()->subMonth()->startOfDay();
        $endDate = Carbon::now()->addMonths(6)->endOfDay();
        
        // Get ALL appointments (not filtered by patient_id) for calendar display
        $allAppointments = Appointment::whereBetween('start_datetime', [$startDate, $endDate])
            ->where('status', '!=', 'Cancelled')
            ->with(['service', 'patient.info'])
            ->orderBy('start_datetime', 'asc')
            ->get();
        
        // Debug: Log appointment counts
        \Log::info('getAllAppointmentsForConflicts - Raw appointments', [
            'total_appointments' => $allAppointments->count(),
            'patient_id' => $patientId,
            'date_range' => [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]
        ]);
        
        return $allAppointments->map(function($apt) use ($patientId) {
                $data = [
                    'id' => $apt->id,
                    'patient_id' => $apt->patient_id,
                    'service_id' => $apt->service_id,
                    'duration_minutes' => $apt->duration_minutes,
                    'status' => $apt->status ?? 'Pending',
                    'notes' => $apt->notes,
                    'reason_for_visit' => $apt->reason_for_visit,
                    'is_new_patient' => $apt->is_new_patient,
                    'rescheduled_at' => $apt->rescheduled_at?->format('Y-m-d H:i:s'),
                    'original_datetime' => $apt->original_datetime?->format('Y-m-d H:i:s'),
                    'start_datetime' => $apt->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $apt->end_datetime->format('Y-m-d H:i:s'),
                    'created_at' => $apt->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $apt->updated_at->format('Y-m-d H:i:s'),
                    'is_own_appointment' => $apt->patient_id == $patientId, // Mark if it's the patient's own appointment
                ];

                if ($apt->relationLoaded('service') && $apt->service) {
                    $data['service'] = [
                        'id' => $apt->service->id,
                        'service_name' => $apt->service->service_name,
                        'default_duration_minutes' => $apt->service->default_duration_minutes,
                    ];
                } else {
                    $data['service'] = null;
                }

                // Include patient name for other patients' appointments (for display only)
                if ($apt->patient_id != $patientId && $apt->relationLoaded('patient') && $apt->patient) {
                    if ($apt->patient->relationLoaded('info') && $apt->patient->info) {
                        $data['patient_name'] = trim($apt->patient->info->first_name . ' ' . $apt->patient->info->last_name);
                    } else {
                        $data['patient_name'] = $apt->patient->name ?? 'Other Patient';
                    }
                }

                return $data;
            })
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
                'type' => 'required|in:emergency,reschedule,book',
                'reason' => 'required|string',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required_if:type,emergency,reschedule',
                'service_id' => 'nullable|exists:services,id',
                'other_concern' => 'nullable|string|max:255',
                'existing_appointment_id' => 'nullable|exists:appointments,id'
            ]);

            // For booking type, use a default time (will be assigned by admin/staff)
            // For emergency/reschedule, use the provided time
            if ($request->type === 'book') {
                // Use a placeholder time - admin/staff will assign the actual time
                $requestedDateTime = Carbon::parse($request->date . ' 11:00:00', 'Asia/Manila');
            } else {
                $requestedDateTime = Carbon::parse($request->date . ' ' . $request->time, 'Asia/Manila');
            }

            // Validate date is not in past
            if ($error = $this->validateRequestDateTime($requestedDateTime)) {
                return $error;
            }

            // Check if rescheduling a missed or cancelled appointment (prevent this)
            if ($request->type === 'reschedule' && $request->existing_appointment_id) {
                $existingAppointment = Appointment::find($request->existing_appointment_id);
                if ($existingAppointment) {
                    $status = $existingAppointment->status ?? 'Pending';
                    $notes = strtolower((string) $existingAppointment->notes);
                    $isAutoCancelled = $status === 'Cancelled' && str_contains($notes, 'automatically cancelled because the appointment remained pending past its date.');
                    $isMissed = $status === 'Missed';

                    if ($status === 'Cancelled' && !$isAutoCancelled) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot reschedule cancelled appointments unless they were automatically cancelled by the system.',
                            'errors' => ['existing_appointment_id' => ['Cannot reschedule cancelled appointments']]
                        ], 422);
                    }

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

            // For booking type, skip time validation (time will be assigned by admin/staff)
            // For emergency/reschedule, validate clinic hours
            if ($request->type !== 'book') {
                // Validate clinic hours: 11:00 AM to 6:00 PM only
                $appointmentTime = $requestedDateTime->copy()->setTime($requestedDateTime->hour, $requestedDateTime->minute, 0);
                $clinicOpen = Carbon::parse($requestedDateTime->toDateString() . ' 11:00:00', 'Asia/Manila');
                $clinicClose = Carbon::parse($requestedDateTime->toDateString() . ' 18:00:00', 'Asia/Manila');
                
                if ($appointmentTime->lt($clinicOpen) || $appointmentTime->gte($clinicClose)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointments can only be scheduled between 11:00 AM and 6:00 PM. The clinic is closed outside these hours.',
                        'errors' => ['requested_datetime' => ['Appointments can only be scheduled between 11:00 AM and 6:00 PM']]
                    ], 422);
                }

                // Determine service and duration
                [$serviceId, $otherConcern, $durationMinutes] = $this->determineServiceAndDuration($request);

                // Check for conflicts
                if ($error = $this->checkTimeConflicts($requestedDateTime, $durationMinutes)) {
                    return $error;
                }

                $requestedEndDateTime = $requestedDateTime->copy()->addMinutes($durationMinutes);
                
                // Validate that appointment end time doesn't exceed clinic closing time (6:00 PM)
                if ($requestedEndDateTime->gt($clinicClose)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointment end time exceeds clinic closing time (6:00 PM). Please adjust the appointment time or select a shorter service.',
                        'errors' => ['requested_datetime' => ['Appointment end time exceeds clinic closing time (6:00 PM)']]
                    ], 422);
                }
            } else {
                // For booking type, determine service and duration but skip time validation
                [$serviceId, $otherConcern, $durationMinutes] = $this->determineServiceAndDuration($request);
                // Use placeholder end time (will be updated when admin/staff assigns time)
                $requestedEndDateTime = $requestedDateTime->copy()->addMinutes($durationMinutes ?? 30);
                
                // Prevent booking on clinic closed days (full-day closure 00:00–23:59)
                $isClosedDay = \App\Models\BlockedTime::whereDate('start_datetime', $requestedDateTime->toDateString())
                    ->whereRaw("DATE_FORMAT(start_datetime, '%H:%i') = '00:00'")
                    ->whereRaw("DATE_FORMAT(end_datetime, '%H:%i') = '23:59'")
                    ->exists();
                if ($isClosedDay) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The clinic is closed on this date. Please select a different date for your appointment request.',
                        'errors' => ['date' => ['The clinic is closed on this date.']]
                    ], 422);
                }
            }

            // Create appointment request
            $appointmentRequest = $this->createAppointmentRequest($request, $serviceId, $otherConcern, $durationMinutes, $requestedDateTime, $requestedEndDateTime);

            // Send notifications
            $this->sendNotificationToStaff($appointmentRequest, $request, $requestedDateTime);

            // Customize success message based on request type
            $successMessage = 'Your request has been submitted. You will be notified once it is reviewed.';
            if ($request->type === 'book') {
                $successMessage = 'Your appointment booking request has been submitted. Admin or staff will review your request and assign an appointment time. You will be notified once your appointment is scheduled.';
            }

            return response()->json([
                'success' => true,
                'message' => $successMessage
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
            // Determine request type
            $requestType = 'walk-in';
            if ($request->type === 'reschedule') {
                $requestType = 'reschedule';
            } else if ($request->type === 'book') {
                $requestType = 'book'; // Use 'book' for booking requests
            }

            \Log::info('Creating AppointmentRequest with:', [
                'patient_id' => auth()->id(),
                'service_id' => $serviceId,
                'other_concern' => $otherConcern,
                'existing_appointment_id' => $request->existing_appointment_id,
                'request_type' => $requestType,
                'duration_minutes' => $durationMinutes,
                'reason' => $request->reason
            ]);

            $appointmentRequest = AppointmentRequest::create([
                'patient_id' => auth()->id(),
                'service_id' => $serviceId,
                'other_concern' => $otherConcern,
                'existing_appointment_id' => $request->existing_appointment_id,
                'request_type' => $requestType,
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

            // Determine notification title and message based on request type
            $title = 'New Appointment Request';
            $message = '';
            
            if ($request->type === 'emergency') {
                $title = 'New Walk-in Request';
                $message = "{$patientName} has requested a walk-in appointment for {$serviceName} on {$formattedDate} at {$formattedTime}.";
            } else if ($request->type === 'reschedule') {
                $title = 'New Reschedule Request';
                $message = "{$patientName} has requested to reschedule an appointment for {$serviceName} on {$formattedDate} at {$formattedTime}.";
            } else if ($request->type === 'book') {
                $title = 'New Appointment Booking Request';
                $message = "{$patientName} has requested to book an appointment for {$serviceName} on {$formattedDate}. Please assign an appointment time.";
            }

            foreach ($adminStaff as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'type' => 'appointment_request',
                    'title' => $title,
                    'message' => $message,
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

    /**
     * Poll for appointment updates since last check
     */
    public function poll(Request $request): JsonResponse
    {
        $patientId = auth()->id();
        $lastCheck = $request->input('last_check');
        
        // Get all appointments
        $appointments = $this->getPatientAppointments($patientId);
        $upcomingAppointments = $this->getUpcomingAppointments();
        $pendingRequests = $this->getPendingRequests();
        $appointmentHistory = $this->getAppointmentHistory();
        
        // Check if there are updates since last check
        $hasUpdates = false;
        if ($lastCheck) {
            $lastCheckTime = Carbon::parse($lastCheck);
            $hasUpdates = Appointment::where('patient_id', $patientId)
                ->where(function($query) use ($lastCheckTime) {
                    $query->where('created_at', '>', $lastCheckTime)
                          ->orWhere('updated_at', '>', $lastCheckTime);
                })
                ->exists();
        } else {
            $hasUpdates = true; // Initial load
        }
        
        return response()->json([
            'appointments' => $appointments,
            'upcoming_appointments' => $upcomingAppointments->map(function($apt) {
                return [
                    'id' => $apt->id,
                    'start_datetime' => $apt->start_datetime->format('Y-m-d H:i:s'),
                    'status' => $apt->status,
                    'service_name' => $apt->service?->service_name ?? $apt->reason_for_visit,
                    'service_id' => $apt->service_id,
                ];
            }),
            'pending_requests' => $pendingRequests->map(function($req) {
                return [
                    'id' => $req->id,
                    'status' => $req->status,
                    'requested_datetime' => $req->requested_datetime->format('Y-m-d H:i:s'),
                ];
            }),
            'appointment_history' => $appointmentHistory->map(function($apt) {
                return [
                    'id' => $apt->id,
                    'start_datetime' => $apt->start_datetime->format('Y-m-d H:i:s'),
                    'status' => $apt->status,
                ];
            }),
            'has_updates' => $hasUpdates,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
