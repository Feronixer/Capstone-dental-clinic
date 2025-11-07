<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Services\MailService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Maatwebsite\Excel\Facades\Excel; // Removed - using CSV export instead

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->autoMarkMissedAppointments();
        $this->cleanupExpiredBlockedTimes();

        $dateValidation = $this->validateAndGetRequestedDate();
        if ($dateValidation instanceof \Illuminate\Http\RedirectResponse) {
            return $dateValidation;
        }

        [$currentMonth, $currentYear, $startDate, $endDate] = $dateValidation;
        $appointments = $this->getAppointmentsForView($startDate, $endDate);
        $blockedTimes = $this->getBlockedTimesForView($startDate, $endDate);
        $patients = $this->getPatientsForView();
        $staff = $this->getStaffForView();
        $services = $this->getServicesForView();

        return view("admin.appointment", compact('appointments', 'blockedTimes', 'patients', 'staff', 'services', 'currentMonth', 'currentYear'));
    }

    private function cleanupExpiredBlockedTimes(): void
    {
        \App\Models\BlockedTime::where('end_datetime', '<', Carbon::now('Asia/Manila'))->delete();
    }

    /**
     * @return array{0: int, 1: int, 2: Carbon, 3: Carbon}|\Illuminate\Http\RedirectResponse
     */
    private function validateAndGetRequestedDate()
    {
        $serverNow = Carbon::now('Asia/Manila');
        $requestedMonth = (int) request('month', $serverNow->month);
        $requestedYear = (int) request('year', $serverNow->year);
        $requestedDate = Carbon::create($requestedYear, $requestedMonth, 1, 0, 0, 0, 'Asia/Manila');

        if ($this->isInvalidDate($requestedDate, $serverNow, $requestedMonth, $requestedYear)) {
            return redirect()->route('admin-appointment', ['view' => request('view', 'month')])
                ->with('error', 'Invalid date detected. Showing current month.');
        }

        $startDate = Carbon::create($requestedYear, $requestedMonth, 1)->startOfMonth()->subMonth();
        $endDate = Carbon::create($requestedYear, $requestedMonth, 1)->endOfMonth()->addMonth();

        return [$requestedMonth, $requestedYear, $startDate, $endDate];
    }

    private function isInvalidDate(Carbon $requestedDate, Carbon $serverNow, int $requestedMonth, int $requestedYear): bool
    {
        $maxFutureDate = $serverNow->copy()->addYears(2);
        $suspiciousFutureDate = $serverNow->copy()->addDays(7);
        $minPastDate = $serverNow->copy()->subYear();

        if (($requestedDate->gt($suspiciousFutureDate) && $requestedDate->month != $serverNow->month) ||
            $requestedDate->gt($maxFutureDate) ||
            $requestedDate->lt($minPastDate)) {
            \Log::warning('Invalid date requested', [
                'requested_month' => $requestedMonth,
                'requested_year' => $requestedYear,
                'server_date' => $serverNow->format('Y-m-d H:i:s')
            ]);
            return true;
        }
        return false;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getAppointmentsForView(Carbon $startDate, Carbon $endDate)
    {
        return Appointment::whereBetween('start_datetime', [$startDate, $endDate])
            ->whereNotIn('status', ['blocked'])
            ->with(['patient.info', 'service'])
            ->get()
            ->map(function($appointment) {
                $data = $appointment->toArray();
                $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
                $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');
                $data['status'] = $appointment->status ?? 'Pending';

                if (!$appointment->service && $appointment->service_id) {
                    $service = Service::find($appointment->service_id);
                    if ($service) {
                        $data['service'] = [
                            'id' => $service->id,
                            'service_name' => $service->service_name,
                            'default_duration_minutes' => $service->default_duration_minutes,
                            'description' => $service->description,
                            'price' => $service->price,
                        ];
                    }
                }

                return $data;
            });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getBlockedTimesForView(Carbon $startDate, Carbon $endDate)
    {
        return \App\Models\BlockedTime::whereBetween('start_datetime', [$startDate, $endDate])
            ->where('end_datetime', '>=', Carbon::now('Asia/Manila'))
            ->get()
            ->map(function($blockedTime) {
                return [
                    'id' => $blockedTime->id,
                    'title' => $blockedTime->title,
                    'start_datetime' => $blockedTime->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $blockedTime->end_datetime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $blockedTime->duration_minutes,
                    'notes' => $blockedTime->notes,
                    'created_at' => $blockedTime->created_at,
                    'updated_at' => $blockedTime->updated_at,
                ];
            });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getPatientsForView()
    {
        return User::whereHas('info', function($query) {
            $query->where('role_id', 3);
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
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getStaffForView()
    {
        return User::whereHas('info', function($query) {
            $query->whereIn('role_id', [1, 2]);
        })->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getServicesForView()
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
     * Get appointments for calendar view
     */
    public function getAppointments(Request $request): JsonResponse
    {
        // Parse in Asia/Manila timezone to avoid UTC conversion
        $start = Carbon::parse($request->start, 'Asia/Manila');
        $end = Carbon::parse($request->end, 'Asia/Manila');

        $appointments = Appointment::whereBetween('start_datetime', [$start, $end])
            ->with(['patient.info', 'service'])
            ->get()
            ->map(function($appointment) {
                // Format dates as 'Y-m-d H:i:s' string without timezone
                $data = $appointment->toArray();
                $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
                $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');
                return $data;
            });

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

            // Regular appointment validation
            $request->validate([
                'patient_id' => 'required|exists:users,id',
                'service_id' => 'nullable|exists:services,id',
                'start_datetime' => 'required|date',
                'duration_minutes' => 'nullable|integer|min:15|max:480',
                'status' => 'nullable|in:Pending,Confirmed,Completed,Cancelled,Missed',
                'notes' => 'nullable|string',
                'reason_for_visit' => 'nullable|string|max:255',
                'is_new_patient' => 'nullable|boolean'
            ]);

            // Parse in Asia/Manila timezone to avoid UTC conversion
            $startDateTime = Carbon::parse($request->start_datetime, 'Asia/Manila');

            // CRITICAL: Validate that appointment is not in the past using SERVER time
            $serverNow = Carbon::now('Asia/Manila');
            if ($startDateTime->lt($serverNow)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot schedule appointments in the past. Please select a future date and time.',
                    'errors' => ['start_datetime' => ['Cannot schedule appointments in the past']]
                ], 422);
            }

            // Validate clinic hours: 11:00 AM to 6:00 PM only
            $appointmentHour = $startDateTime->hour;
            $appointmentMinute = $startDateTime->minute;
            $appointmentTime = $startDateTime->copy()->setTime($appointmentHour, $appointmentMinute, 0);
            $clinicOpen = Carbon::parse($startDateTime->toDateString() . ' 11:00:00', 'Asia/Manila');
            $clinicClose = Carbon::parse($startDateTime->toDateString() . ' 18:00:00', 'Asia/Manila');
            
            if ($appointmentTime->lt($clinicOpen) || $appointmentTime->gte($clinicClose)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointments can only be scheduled between 11:00 AM and 6:00 PM. The clinic is closed outside these hours.',
                    'errors' => ['start_datetime' => ['Appointments can only be scheduled between 11:00 AM and 6:00 PM']]
                ], 422);
            }

            // Get duration from service if not provided
            $durationMinutes = $request->duration_minutes;
            if (!$durationMinutes && $request->service_id) {
                $service = \App\Models\Service::find($request->service_id);
                if ($service) {
                    $durationMinutes = $service->default_duration_minutes;
                }
            }
            // Fallback to 30 minutes if no service or duration provided
            $durationMinutes = $durationMinutes ?? 30;

            // Additional validation: Check for time overlaps
            $endDateTime = $startDateTime->copy()->addMinutes($durationMinutes);
            
            // Validate that appointment end time doesn't exceed clinic closing time (6:00 PM)
            $clinicClose = Carbon::parse($startDateTime->toDateString() . ' 18:00:00', 'Asia/Manila');
            if ($endDateTime->gt($clinicClose)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment end time exceeds clinic closing time (6:00 PM). Please adjust the appointment time or duration.',
                    'errors' => ['start_datetime' => ['Appointment end time exceeds clinic closing time (6:00 PM)']]
                ], 422);
            }

            // Check for overlaps with blocked times
            $overlappingBlockedTime = \App\Models\BlockedTime::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })->first();

            if ($overlappingBlockedTime) {
                // Check if it's a full day closure
                $isFullDayClosure = $overlappingBlockedTime->start_datetime->format('H:i') === '00:00' &&
                                    $overlappingBlockedTime->end_datetime->format('H:i') === '23:59';

                $message = $isFullDayClosure
                    ? 'The clinic is closed on this date. Please select a different date for the appointment.'
                    : 'This time slot is blocked. Please select a different time slot.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['start_datetime' => [$message]]
                ], 422);
            }

            // Check for overlaps with other appointments (exclude cancelled appointments)
            $overlappingAppointment = Appointment::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where(function($q) use ($startDateTime, $endDateTime) {
                    // New appointment starts before existing ends AND new appointment ends after existing starts
                    $q->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
                })
                // Exclude cancelled appointments - they don't block time slots
                ->where('status', '!=', 'Cancelled');
            })->first();

            // Check that this patient doesn't already have an overlapping appointment
            $patientOverlap = Appointment::where('patient_id', $request->patient_id)
                ->where('status', '!=', 'Cancelled')
                ->where(function($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
                })
                ->first();

            if ($patientOverlap) {
                return response()->json([
                    'success' => false,
                    'message' => 'This patient already has an appointment that overlaps this time',
                    'errors' => ['patient_id' => ['This patient already has an appointment that overlaps this time']]
                ], 422);
            }

            if ($overlappingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot conflicts with an existing appointment',
                    'errors' => ['start_datetime' => ['This time slot conflicts with an existing appointment']]
                ], 422);
            }

            // Calculate end_datetime based on start_datetime and duration (already calculated above)

            $appointmentData = $request->all();
            $appointmentData['duration_minutes'] = $durationMinutes;
            $appointmentData['end_datetime'] = $endDateTime;

            // Ensure status has a default value if not provided
            if (!isset($appointmentData['status']) || empty($appointmentData['status'])) {
                $appointmentData['status'] = 'Pending';
            }

            \Log::info('Appointment data to create:', $appointmentData);

            $appointment = Appointment::create($appointmentData);

            \Log::info('Appointment created successfully:', ['id' => $appointment->id]);

            // Send notification to patient
            try {
                NotificationService::appointmentCreated($appointment);
            } catch (\Exception $e) {
                \Log::error('Failed to send notification:', ['error' => $e->getMessage()]);
            }

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

        // If service relationship is null but service_id exists, try to load it
        if (!$appointment->service && $appointment->service_id) {
            $service = Service::find($appointment->service_id);
            if ($service) {
                $appointment->setRelation('service', $service);
            }
        }

        // Always return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            // Format dates as 'Y-m-d H:i:s' string without timezone to avoid JS conversion
            $data = $appointment->toArray();
            $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
            $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');

            // Ensure service is included even if null
            if (!isset($data['service']) && $appointment->service_id) {
                $data['service'] = null;
            }

            return response()->json($data);
        }

        // For non-AJAX requests, return JSON as well (since we don't have a show view)
        $data = $appointment->toArray();
        $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
        $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');

        // Ensure service is included even if null
        if (!isset($data['service']) && $appointment->service_id) {
            $data['service'] = null;
        }

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);

            // Regular appointment validation
            // Prevent rescheduling cancelled appointments
            if ($appointment->status === 'Cancelled') {
                if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot reschedule cancelled appointments.',
                        'errors' => ['status' => ['Cannot reschedule cancelled appointments']]
                    ], 422);
                }
                return redirect()->back()->withErrors(['status' => 'Cannot reschedule cancelled appointments.']);
            }

            $request->validate([
                'patient_id' => 'required|exists:users,id',
                'service_id' => 'nullable|exists:services,id',
                'start_datetime' => 'required|date',
                'duration_minutes' => 'nullable|integer|min:15|max:480',
                'status' => 'nullable|in:Pending,Confirmed,Completed,Cancelled,Missed',
                'notes' => 'nullable|string',
                'reason_for_visit' => 'nullable|string|max:255',
                'is_new_patient' => 'nullable|boolean'
            ]);

            // Get duration from service if not provided
            $durationMinutes = $request->duration_minutes;
            if (!$durationMinutes && $request->service_id) {
                $service = \App\Models\Service::find($request->service_id);
                if ($service) {
                    $durationMinutes = $service->default_duration_minutes;
                }
            }
            // Fallback to 30 minutes if no service or duration provided
            $durationMinutes = $durationMinutes ?? 30;

            // Calculate end_datetime based on start_datetime and duration
            // Parse in Asia/Manila timezone to avoid UTC conversion
            $startDateTime = Carbon::parse($request->start_datetime, 'Asia/Manila');
            $endDateTime = $startDateTime->copy()->addMinutes($durationMinutes);

            // CRITICAL: Validate that appointment is not in the past using SERVER time
            $serverNow = Carbon::now('Asia/Manila');
            if ($startDateTime->lt($serverNow)) {
                if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot reschedule appointments to a past date or time. Please select a future date and time.',
                        'errors' => ['start_datetime' => ['Cannot reschedule appointments to a past date or time']]
                    ], 422);
                }
                return redirect()->back()->withErrors(['start_datetime' => 'Cannot reschedule appointments to a past date or time.']);
            }

            // Validate clinic hours: 11:00 AM to 6:00 PM only
            $appointmentTime = $startDateTime->copy()->setTime($startDateTime->hour, $startDateTime->minute, 0);
            $clinicOpen = Carbon::parse($startDateTime->toDateString() . ' 11:00:00', 'Asia/Manila');
            $clinicClose = Carbon::parse($startDateTime->toDateString() . ' 18:00:00', 'Asia/Manila');
            
            if ($appointmentTime->lt($clinicOpen) || $appointmentTime->gte($clinicClose)) {
                if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointments can only be scheduled between 11:00 AM and 6:00 PM. The clinic is closed outside these hours.',
                        'errors' => ['start_datetime' => ['Appointments can only be scheduled between 11:00 AM and 6:00 PM']]
                    ], 422);
                }
                return redirect()->back()->withErrors(['start_datetime' => 'Appointments can only be scheduled between 11:00 AM and 6:00 PM.']);
            }
            
            // Validate that appointment end time doesn't exceed clinic closing time (6:00 PM)
            if ($endDateTime->gt($clinicClose)) {
                if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointment end time exceeds clinic closing time (6:00 PM). Please adjust the appointment time or duration.',
                        'errors' => ['start_datetime' => ['Appointment end time exceeds clinic closing time (6:00 PM)']]
                    ], 422);
                }
                return redirect()->back()->withErrors(['start_datetime' => 'Appointment end time exceeds clinic closing time (6:00 PM).']);
            }

            // Check for overlaps with blocked times
            $overlappingBlockedTime = \App\Models\BlockedTime::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })->first();

            if ($overlappingBlockedTime) {
                if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'This time slot conflicts with a blocked time',
                        'errors' => ['start_datetime' => ['This time slot conflicts with a blocked time']]
                    ], 422);
                }
                return redirect()->back()->withErrors(['start_datetime' => 'This time slot conflicts with a blocked time']);
            }

            // Check for overlaps with other appointments (excluding current one and cancelled appointments)
            $overlappingAppointment = Appointment::where('id', '!=', $id)
                ->where(function($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_datetime', '<', $endDateTime)
                          ->where('end_datetime', '>', $startDateTime);
                })
                // Exclude cancelled appointments - they don't block time slots
                ->where('status', '!=', 'Cancelled')
                ->first();

            if ($overlappingAppointment) {
                if ($request->ajax() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'This time slot conflicts with another appointment',
                        'errors' => ['start_datetime' => ['This time slot conflicts with another appointment']]
                    ], 422);
                }
                return redirect()->back()->withErrors(['start_datetime' => 'This time slot conflicts with another appointment']);
            }

            $appointmentData = $request->all();
            $appointmentData['duration_minutes'] = $durationMinutes;
            $appointmentData['end_datetime'] = $endDateTime;

            // Check if datetime changed (rescheduling)
            $isRescheduling = $appointment->start_datetime->ne($startDateTime);
            $oldDateTime = $appointment->start_datetime->copy();

            // If rescheduling, track the original datetime and set rescheduled_at timestamp
            if ($isRescheduling) {
                // Only set original_datetime if it hasn't been set before (first reschedule)
                if (!$appointment->original_datetime) {
                    $appointmentData['original_datetime'] = $appointment->start_datetime;
                }
                $appointmentData['rescheduled_at'] = now();
            }

            // Reset reminder flags if appointment datetime changed (rescheduled)
            $oldStartDateTime = $appointment->start_datetime;
            if ($oldStartDateTime && $oldStartDateTime->ne($startDateTime)) {
                $appointmentData['reminder_24h_sent'] = false;
                $appointmentData['reminder_3h_sent'] = false;
                $appointmentData['reminder_24h_sent_at'] = null;
                $appointmentData['reminder_3h_sent_at'] = null;
            }
            
            $appointment->update($appointmentData);

            // Send rescheduling email and notification if datetime changed
            if ($isRescheduling) {
                try {
                    MailService::sendAppointmentEmail('rescheduling', $appointment->load(['patient.info', 'service']));
                } catch (\Exception $e) {
                    \Log::error('Failed to send rescheduling email:', ['error' => $e->getMessage()]);
                }

                try {
                    NotificationService::appointmentRescheduled($appointment, $oldDateTime);
                } catch (\Exception $e) {
                    \Log::error('Failed to send rescheduling notification:', ['error' => $e->getMessage()]);
                }
            }

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
    public function destroy(Request $request, string $id)
    {
        // Only allow admins (role_id === 1) to delete appointments
        $adminUser = Auth::guard('admin')->user();

        if (!$adminUser || $adminUser->role_id !== 1) {
            \Log::warning('Unauthorized delete attempt by staff user', [
                'user_id' => $adminUser?->id,
                'appointment_id' => $id
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only administrators can delete appointments.'
                ], 403);
            }

            return redirect()->route('admin-appointment')->with('error', 'Unauthorized. Only administrators can delete appointments.');
        }

        try {
            // Validate password if force delete is requested
            if ($request->boolean('force')) {
                // Require password confirmation for deletion
                $request->validate([
                    'password' => 'required|string'
                ]);

                // Verify password
                $admin = Auth::guard('admin')->user();

                if (!$admin || !Hash::check($request->password, $admin->password)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Incorrect password.'
                        ], 403);
                    }

                    return redirect()->back()->withErrors(['password' => 'Incorrect password.']);
                }

                $appointment = Appointment::findOrFail($id);
                $appointment->delete();

                \Log::info('Appointment force deleted:', [
                    'appointment_id' => $id,
                    'deleted_by' => Auth::guard('admin')->id()
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => true, 'message' => 'Appointment deleted successfully']);
                }

                return redirect()->route('admin-appointment')->with('success', 'Appointment deleted successfully.');
            }

            $appointment = Appointment::findOrFail($id);

            // Check if appointment is already cancelled
            if ($appointment->status === 'Cancelled') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Appointment is already cancelled.']);
                }
                return redirect()->route('admin-appointment')->with('error', 'Appointment is already cancelled.');
            }

            // Prevent cancelling rescheduled appointments
            if (!is_null($appointment->rescheduled_at)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cannot cancel rescheduled appointments. The original appointment was already cancelled when it was rescheduled.']);
                }
                return redirect()->route('admin-appointment')->with('error', 'Cannot cancel rescheduled appointments. The original appointment was already cancelled when it was rescheduled.');
            }

            // Prevent cancelling confirmed appointments
            if ($appointment->status === 'Confirmed') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cannot cancel confirmed appointments.']);
                }
                return redirect()->route('admin-appointment')->with('error', 'Cannot cancel confirmed appointments.');
            }

            $oldStatus = $appointment->status;
            \Log::info('Cancelling appointment:', ['appointment_id' => $appointment->id, 'old_status' => $oldStatus]);

            // Update status to Cancelled instead of deleting
            $appointment->update(['status' => 'Cancelled']);

            // Reload relationships for email/notification
            $appointment->load(['patient.info', 'service']);

            // Send cancellation email
            try {
                MailService::sendAppointmentEmail('cancellation', $appointment);
                \Log::info("Cancellation email sent for appointment {$appointment->id}");
            } catch (\Exception $e) {
                \Log::error('Failed to send cancellation email:', ['error' => $e->getMessage()]);
            }

            // Send cancellation notification
            try {
                NotificationService::appointmentCancelled($appointment);
                \Log::info("Cancellation notification sent for appointment {$appointment->id}");
            } catch (\Exception $e) {
                \Log::error('Failed to send cancellation notification:', ['error' => $e->getMessage()]);
            }

            \Log::info('Appointment cancelled successfully', ['appointment_id' => $appointment->id, 'old_status' => $oldStatus]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Appointment cancelled successfully']);
            }

            return redirect()->route('admin-appointment')->with('success', 'Appointment cancelled successfully.');
        } catch (\Exception $e) {
            \Log::error('Error cancelling appointment:', ['error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error cancelling appointment: ' . $e->getMessage()], 500);
            }

            return redirect()->route('admin-appointment')->with('error', 'Error cancelling appointment.');
        }
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            $appointment = Appointment::with(['patient.info', 'service'])->findOrFail($id);

            // Get the old status before updating
            $oldStatus = $appointment->status;

            $validated = $request->validate([
                'status' => 'required|in:Pending,Confirmed,Completed,Cancelled,Missed',
                'notes' => 'nullable|string|max:500'
            ]);

            // Prevent cancelling rescheduled appointments
            if ($validated['status'] === 'Cancelled' && !is_null($appointment->rescheduled_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel rescheduled appointments. The original appointment was already cancelled when it was rescheduled.'
                ], 422);
            }

            // Validate status transitions
            $validTransitions = [
                'Pending' => ['Confirmed', 'Cancelled'],
                'Confirmed' => ['Completed'], // Confirmed appointments can only be completed, not cancelled
                'Completed' => [], // Completed appointments cannot change status
                'Cancelled' => [], // Cancelled appointments cannot change status
                'Missed' => [] // Missed appointments cannot change status
            ];

            if (!in_array($validated['status'], $validTransitions[$oldStatus] ?? [])) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot change status from {$oldStatus} to {$validated['status']}"
                ], 422);
            }

            // Additional validation: Only allow "Completed" status if appointment date is today
            if ($validated['status'] === 'Completed') {
                $appointmentDate = Carbon::parse($appointment->start_datetime)->timezone('Asia/Manila')->startOfDay();
                $today = Carbon::now('Asia/Manila')->startOfDay();

                if (!$appointmentDate->isSameDay($today)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointments can only be marked as Completed on the day of the appointment'
                    ], 422);
                }
            }

            // Update appointment status
            $updateData = ['status' => $validated['status']];
            
            // Reset reminder flags if status changes from Confirmed to something else
            // or if status changes to Confirmed (so reminders can be sent again)
            if ($oldStatus === 'Confirmed' && $validated['status'] !== 'Confirmed') {
                $updateData['reminder_24h_sent'] = false;
                $updateData['reminder_3h_sent'] = false;
                $updateData['reminder_24h_sent_at'] = null;
                $updateData['reminder_3h_sent_at'] = null;
            } elseif ($oldStatus !== 'Confirmed' && $validated['status'] === 'Confirmed') {
                // Reset reminder flags when appointment is newly confirmed
                $updateData['reminder_24h_sent'] = false;
                $updateData['reminder_3h_sent'] = false;
                $updateData['reminder_24h_sent_at'] = null;
                $updateData['reminder_3h_sent_at'] = null;
            }
            
            $appointment->update($updateData);

            // Add status change note if provided
            if (!empty($validated['notes'])) {
                $currentNotes = $appointment->notes ?? '';
                // For cancelled appointments, just append the notes without timestamp prefix
                if ($validated['status'] === 'Cancelled') {
                    $appointment->update(['notes' => $currentNotes . ($currentNotes ? "\n\n" : '') . $validated['notes']]);
                } else {
                    // For other statuses, keep the timestamp format
                    $statusChangeNote = "\n\n[" . now()->format('Y-m-d H:i') . "] Status changed to {$validated['status']}: {$validated['notes']}";
                    $appointment->update(['notes' => $currentNotes . $statusChangeNote]);
                }
            }

            // Send notification to patient
            try {
                $patientName = $appointment->patient->info ?
                    trim($appointment->patient->info->first_name . ' ' . $appointment->patient->info->last_name) :
                    $appointment->patient->name;

                $serviceName = $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit;
                $appointmentDate = $appointment->start_datetime->format('F j, Y \a\t g:i A');

                $notificationMessages = [
                    'Confirmed' => "Your appointment for {$serviceName} on {$appointmentDate} has been confirmed.",
                    'Completed' => "Your appointment for {$serviceName} on {$appointmentDate} has been marked as completed.",
                    'Cancelled' => "Your appointment for {$serviceName} on {$appointmentDate} has been cancelled.",
                    'Missed' => "Your appointment for {$serviceName} on {$appointmentDate} has been marked as missed."
                ];

                Notification::create([
                    'user_id' => $appointment->patient_id,
                    'type' => 'appointment_status',
                    'title' => "Appointment {$validated['status']}",
                    'message' => $notificationMessages[$validated['status']] ?? "Your appointment status has been updated to {$validated['status']}.",
                    'icon' => $validated['status'] === 'Confirmed' ? 'bi-check-circle' :
                             ($validated['status'] === 'Cancelled' ? 'bi-x-circle' :
                             ($validated['status'] === 'Missed' ? 'bi-exclamation-triangle' : 'bi-info-circle')),
                    'data' => json_encode([
                        'appointment_id' => $appointment->id,
                        'old_status' => $oldStatus,
                        'new_status' => $validated['status'],
                        'service' => $serviceName,
                        'date' => $appointmentDate
                    ])
                ]);

                // Send automated email based on status change
                if ($validated['status'] === 'Confirmed') {
                    try {
                        MailService::sendAppointmentEmail('initial_confirmation', $appointment);
                        \Log::info("Automated initial confirmation email sent for appointment {$appointment->id}");
                    } catch (\Exception $e) {
                        \Log::error("Failed to send automated initial confirmation email: " . $e->getMessage());
                    }
                } elseif ($validated['status'] === 'Cancelled') {
                    try {
                        MailService::sendAppointmentEmail('cancellation', $appointment);
                        \Log::info("Automated cancellation email sent for appointment {$appointment->id}");
                    } catch (\Exception $e) {
                        \Log::error("Failed to send automated cancellation email: " . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send status change notification:', ['error' => $e->getMessage()]);
            }

            \Log::info('Appointment status updated:', [
                'appointment_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'updated_by' => Auth::guard('admin')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Appointment status updated to {$validated['status']} successfully",
                'appointment' => $appointment->fresh()->load(['patient.info', 'service'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating appointment status:', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchPatients(Request $request)
    {
        $query = $request->input('query', '');

        // Get all patients (role_id = 3) - both with and without UserInfo
        $patientsQuery = User::where('role_id', 3)->with('info');

        // If query is not empty, add search conditions
        if (!empty($query)) {
            $patientsQuery->where(function($q) use ($query) {
                // Search by user ID if query is numeric
                if (is_numeric($query)) {
                    $q->where('id', $query);
                }

                // Search by username
                $q->orWhere('username', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('name', 'like', "%{$query}%");

                // Search in UserInfo (first_name, last_name, phone)
                $q->orWhereHas('info', function($subQuery) use ($query) {
                    $subQuery->where('first_name', 'like', "%{$query}%")
                             ->orWhere('last_name', 'like', "%{$query}%")
                             ->orWhere('phone', 'like', "%{$query}%")
                             ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
                });
            });
        }

        $patients = $patientsQuery->orderBy('name')->get();

        // Format the response to include full name
        $formattedPatients = $patients->map(function($patient) {
            $info = $patient->info;
            return [
                'id' => $patient->id,
                'name' => $info ? trim($info->first_name . ' ' . $info->last_name) : ($patient->name ?: $patient->username),
                'first_name' => $info ? $info->first_name : '',
                'last_name' => $info ? $info->last_name : '',
                'phone' => $info ? $info->phone : '',
                'email' => $patient->email
            ];
        });

        return response()->json($formattedPatients);
    }

    /**
     * Display appointment table view
     */
    public function table(Request $request)
    {
        // Include all appointment statuses except 'blocked' (this includes Cancelled, Pending, Confirmed, Completed, Missed)
        $query = Appointment::with(['patient.info', 'service'])
            ->whereNotIn('status', ['blocked']);

        // Search by patient name or service
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search by patient name
                $q->whereHas('patient.info', function($subQ) use ($search) {
                    $subQ->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })
                // Search by service name
                ->orWhereHas('service', function($subQ) use ($search) {
                    $subQ->where('service_name', 'like', "%{$search}%");
                })
                // Search by reason_for_visit (for appointments without service)
                ->orWhere('reason_for_visit', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by rescheduled
        if ($request->has('rescheduled') && $request->rescheduled !== 'all') {
            if ($request->rescheduled === 'yes') {
                $query->whereNotNull('rescheduled_at');
            } else {
                $query->whereNull('rescheduled_at');
            }
        }

        // Filter by emergency (check notes or reason_for_visit contains "emergency")
        if ($request->has('emergency') && $request->emergency !== 'all') {
            if ($request->emergency === 'yes') {
                $query->where(function($q) {
                    $q->where('notes', 'like', '%emergency%')
                      ->orWhere('notes', 'like', '%Emergency%')
                      ->orWhere('reason_for_visit', 'like', '%emergency%')
                      ->orWhere('reason_for_visit', 'like', '%Emergency%');
                });
            } else {
                // Not emergency: neither notes nor reason_for_visit contains "emergency"
                $query->where(function($q) {
                    $q->where(function($subQ) {
                        $subQ->whereNull('notes')
                             ->orWhere(function($nQ) {
                                 $nQ->where('notes', 'not like', '%emergency%')
                                    ->where('notes', 'not like', '%Emergency%');
                             });
                    })
                    ->where(function($subQ) {
                        $subQ->whereNull('reason_for_visit')
                             ->orWhere(function($rQ) {
                                 $rQ->where('reason_for_visit', 'not like', '%emergency%')
                                    ->where('reason_for_visit', 'not like', '%Emergency%');
                             });
                    });
                });
            }
        }

        // Filter by month
        if ($request->has('month') && $request->month !== 'all') {
            $monthYear = explode('-', $request->month);
            if (count($monthYear) === 2) {
                $month = $monthYear[0];
                $year = $monthYear[1];
                $query->whereYear('start_datetime', $year)
                      ->whereMonth('start_datetime', $month);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'start_datetime');
        $sortOrder = $request->get('sort_order', 'desc');

        $validSortFields = ['id', 'start_datetime', 'status', 'duration_minutes', 'rescheduled_at', 'patient_id', 'service_id', 'created_at'];
        if (!in_array($sortBy, $validSortFields)) {
            $sortBy = 'start_datetime';
        }

        if ($sortOrder !== 'asc' && $sortOrder !== 'desc') {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $validPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $validPerPage)) {
            $perPage = 10;
        }

        // Get paginated appointments
        $appointments = $query->paginate($perPage)
            ->withQueryString()
            ->through(function($appointment) {
                // If service relationship is null but service_id exists, try to load it
                if (!$appointment->service && $appointment->service_id) {
                    $service = Service::find($appointment->service_id);
                    if ($service) {
                        $appointment->setRelation('service', $service);
                    }
                }
                return $appointment;
            });

        // Get filter counts for UI
        $totalCount = Appointment::whereNotIn('status', ['blocked'])->count();
        $statusCounts = [
            'all' => $totalCount,
            'Pending' => Appointment::where('status', 'Pending')->whereNotIn('status', ['blocked'])->count(),
            'Confirmed' => Appointment::where('status', 'Confirmed')->whereNotIn('status', ['blocked'])->count(),
            'Completed' => Appointment::where('status', 'Completed')->whereNotIn('status', ['blocked'])->count(),
            'Cancelled' => Appointment::where('status', 'Cancelled')->whereNotIn('status', ['blocked'])->count(),
            'Missed' => Appointment::where('status', 'Missed')->whereNotIn('status', ['blocked'])->count(),
        ];
        $rescheduledCount = Appointment::whereNotNull('rescheduled_at')->whereNotIn('status', ['blocked'])->count();
        $emergencyCount = Appointment::where(function($q) {
            $q->where('notes', 'like', '%emergency%')
              ->orWhere('notes', 'like', '%Emergency%')
              ->orWhere('reason_for_visit', 'like', '%emergency%')
              ->orWhere('reason_for_visit', 'like', '%Emergency%');
        })->whereNotIn('status', ['blocked'])->count();

        // Get available months from appointments
        $availableMonths = Appointment::whereNotIn('status', ['blocked'])
            ->selectRaw('YEAR(start_datetime) as year, MONTH(start_datetime) as month')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function($item) {
                $monthName = Carbon::create($item->year, $item->month, 1)->format('F Y');
                return [
                    'value' => $item->month . '-' . $item->year,
                    'label' => $monthName
                ];
            });

        return view('admin.appointment-table', compact(
            'appointments',
            'statusCounts',
            'rescheduledCount',
            'emergencyCount',
            'totalCount',
            'availableMonths'
        ))->with('perPage', $perPage)->with('search', $request->get('search', ''));
    }

    /**
     * Export appointments to Excel
     */
    public function exportExcel(Request $request)
    {
        // Build query with same filters as table method
        $query = Appointment::with(['patient.info', 'service'])
            ->whereNotIn('status', ['blocked']);

        // Search by patient name or service
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient.info', function($subQ) use ($search) {
                    $subQ->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })
                ->orWhereHas('service', function($subQ) use ($search) {
                    $subQ->where('service_name', 'like', "%{$search}%");
                })
                ->orWhere('reason_for_visit', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by rescheduled
        if ($request->has('rescheduled') && $request->rescheduled !== 'all') {
            if ($request->rescheduled === 'yes') {
                $query->whereNotNull('rescheduled_at');
            } else {
                $query->whereNull('rescheduled_at');
            }
        }

        // Filter by emergency
        if ($request->has('emergency') && $request->emergency !== 'all') {
            if ($request->emergency === 'yes') {
                $query->where(function($q) {
                    $q->where('notes', 'like', '%emergency%')
                      ->orWhere('notes', 'like', '%Emergency%')
                      ->orWhere('reason_for_visit', 'like', '%emergency%')
                      ->orWhere('reason_for_visit', 'like', '%Emergency%');
                });
            } else {
                $query->where(function($q) {
                    $q->where(function($subQ) {
                        $subQ->whereNull('notes')
                             ->orWhere(function($nQ) {
                                 $nQ->where('notes', 'not like', '%emergency%')
                                    ->where('notes', 'not like', '%Emergency%');
                             });
                    })
                    ->where(function($subQ) {
                        $subQ->whereNull('reason_for_visit')
                             ->orWhere(function($rQ) {
                                 $rQ->where('reason_for_visit', 'not like', '%emergency%')
                                    ->where('reason_for_visit', 'not like', '%Emergency%');
                             });
                    });
                });
            }
        }

        // Filter by month
        if ($request->has('month') && $request->month !== 'all') {
            $monthYear = explode('-', $request->month);
            if (count($monthYear) === 2) {
                $month = $monthYear[0];
                $year = $monthYear[1];
                $query->whereYear('start_datetime', $year)
                      ->whereMonth('start_datetime', $month);
            }
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'start_datetime');
        $sortOrder = $request->get('sort_order', 'desc');
        $validSortFields = ['id', 'start_datetime', 'status', 'duration_minutes', 'rescheduled_at', 'patient_id', 'service_id', 'created_at'];
        if (in_array($sortBy, $validSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('start_datetime', 'desc');
        }

        // Get all appointments (no pagination for export)
        $appointments = $query->get()->map(function($appointment) {
            // If service relationship is null but service_id exists, try to load it
            if (!$appointment->service && $appointment->service_id) {
                $service = Service::find($appointment->service_id);
                if ($service) {
                    $appointment->setRelation('service', $service);
                }
            }
            return $appointment;
        });

        $fileName = 'appointments_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        // Prepare CSV data
        $headers = [
            'No.', 'Patient Name', 'Patient ID', 'Phone', 'Email', 'Service',
            'Date', 'Start Time', 'End Time', 'Duration (minutes)', 'Status',
            'Rescheduled', 'Emergency', 'Notes', 'Created At', 'Updated At'
        ];

        $data = $appointments->map(function($appointment, $index) {
            $patientName = 'Unknown Patient';
            $patientId = $appointment->patient_id ?? 'N/A';
            $phone = '';
            $email = '';

            if ($appointment->patient && $appointment->patient->info) {
                $info = $appointment->patient->info;
                $patientName = trim(($info->first_name ?? '') . ' ' . ($info->last_name ?? ''));
                $phone = $info->phone ?? '';
                $email = $appointment->patient->email ?? '';
            } elseif ($appointment->patient) {
                $patientName = $appointment->patient->name ?? 'Unknown Patient';
                $email = $appointment->patient->email ?? '';
            }

            $serviceName = 'No Service';
            if ($appointment->service && $appointment->service->service_name) {
                $serviceName = $appointment->service->service_name;
            } elseif ($appointment->reason_for_visit) {
                $serviceName = $appointment->reason_for_visit;
            }

            $startDate = Carbon::parse($appointment->start_datetime);
            $endDate = Carbon::parse($appointment->end_datetime);
            $isRescheduled = !is_null($appointment->rescheduled_at) ? 'Yes' : 'No';
            $isEmergency = 'No';
            if ($appointment->notes && (stripos($appointment->notes, 'emergency') !== false)) {
                $isEmergency = 'Yes';
            } elseif ($appointment->reason_for_visit && (stripos($appointment->reason_for_visit, 'emergency') !== false)) {
                $isEmergency = 'Yes';
            }

            return [
                $index + 1,
                $patientName,
                $patientId,
                $phone,
                $email,
                $serviceName,
                $startDate->format('Y-m-d'),
                $startDate->format('h:i A'),
                $endDate->format('h:i A'),
                $appointment->duration_minutes ?? 0,
                $appointment->status ?? 'Pending',
                $isRescheduled,
                $isEmergency,
                $appointment->notes ?? '',
                $appointment->created_at ? Carbon::parse($appointment->created_at)->format('Y-m-d h:i A') : '',
                $appointment->updated_at ? Carbon::parse($appointment->updated_at)->format('Y-m-d h:i A') : ''
            ];
        });

        // Generate CSV
        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Auto-mark Confirmed appointments as Missed if they weren't completed on the appointment date
     */
    private function autoMarkMissedAppointments()
    {
        try {
            $now = Carbon::now('Asia/Manila');
            $todayStart = $now->copy()->startOfDay();

            // Get all confirmed appointments that are from yesterday or earlier
            $confirmedAppointments = Appointment::where('status', 'Confirmed')
                ->whereDate('start_datetime', '<', $todayStart)
                ->get();

            foreach ($confirmedAppointments as $appointment) {
                // Mark as missed
                $appointment->update([
                    'status' => 'Missed',
                    'notes' => ($appointment->notes ?? '') . "\n\n[" . $now->format('Y-m-d H:i') . "] Automatically marked as Missed."
                ]);

                // Send notification to patient
                try {
                    $patientName = $appointment->patient->info ?
                        trim($appointment->patient->info->first_name . ' ' . $appointment->patient->info->last_name) :
                        $appointment->patient->name;

                    $serviceName = $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit;
                    $appointmentDate = $appointment->start_datetime->format('F j, Y \a\t g:i A');

                    Notification::create([
                        'user_id' => $appointment->patient_id,
                        'type' => 'appointment_status',
                        'title' => 'Appointment Missed',
                        'message' => "Your appointment for {$serviceName} on {$appointmentDate} has been marked as missed.",
                        'icon' => 'bi-exclamation-triangle',
                        'data' => json_encode([
                            'appointment_id' => $appointment->id,
                            'old_status' => 'Confirmed',
                            'new_status' => 'Missed',
                            'service' => $serviceName,
                            'date' => $appointmentDate
                        ])
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send missed appointment notification:', ['error' => $e->getMessage()]);
                }

                \Log::info('Auto-marked appointment as missed:', [
                    'appointment_id' => $appointment->id,
                    'patient_id' => $appointment->patient_id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error auto-marking missed appointments:', ['error' => $e->getMessage()]);
        }
    }
}
