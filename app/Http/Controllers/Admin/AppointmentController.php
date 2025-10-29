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

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Clean up expired blocked times
        $now = Carbon::now('Asia/Manila');
        \App\Models\BlockedTime::where('end_datetime', '<', $now)->delete();

        $currentMonth = request('month', Carbon::now()->month);
        $currentYear = request('year', Carbon::now()->year);

        // Create date range to cover the current month and adjacent months (for week/day views that span months)
        $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth()->subMonth();
        $endDate = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth()->addMonth();

        // Get only regular appointments (exclude blocked and cancelled status)
        // Fetch appointments for current month + previous and next months to cover week/day views
        $appointments = Appointment::whereBetween('start_datetime', [$startDate, $endDate])
            ->whereNotIn('status', ['blocked', 'Cancelled'])
            ->with(['patient.info', 'service'])
            ->get()
            ->map(function($appointment) {
                // Format dates as 'Y-m-d H:i:s' string without timezone to avoid JS conversion
                $data = $appointment->toArray();
                $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
                $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');
                return $data;
            });

        // Get blocked times separately and format dates for local timezone display
        // Only get blocked times that haven't expired yet (end_datetime is in the future or ongoing)
        $now = Carbon::now('Asia/Manila');
        $blockedTimes = \App\Models\BlockedTime::whereBetween('start_datetime', [$startDate, $endDate])
            ->where('end_datetime', '>=', $now) // Only get blocked times that haven't ended yet
            ->get()
            ->map(function($blockedTime) {
                // Format dates as 'Y-m-d H:i:s' string without timezone to avoid JS conversion
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

        return view("admin.appointment", compact('appointments', 'blockedTimes', 'patients', 'staff', 'services', 'currentMonth', 'currentYear'));
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
                'status' => 'nullable|in:Pending,Confirmed,Completed,Cancelled',
                'notes' => 'nullable|string',
                'reason_for_visit' => 'nullable|string|max:255',
                'is_new_patient' => 'nullable|boolean'
            ]);

            // Additional validation: Check if patient already has appointment on same date
            // Parse in Asia/Manila timezone to avoid UTC conversion
            $startDateTime = Carbon::parse($request->start_datetime, 'Asia/Manila');
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

            // Check for overlaps with blocked times
            $overlappingBlockedTime = \App\Models\BlockedTime::where(function($query) use ($startDateTime, $endDateTime) {
                $query->where('start_datetime', '<', $endDateTime)
                      ->where('end_datetime', '>', $startDateTime);
            })->first();

            if ($overlappingBlockedTime) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot conflicts with a blocked time',
                    'errors' => ['start_datetime' => ['This time slot conflicts with a blocked time']]
                ], 422);
            }

            // Check for overlaps with other appointments
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

            // Send confirmation email to patient
            try {
                MailService::sendAppointmentEmail('initial_confirmation', $appointment->load(['patient.info', 'service']));
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email:', ['error' => $e->getMessage()]);
                // Don't fail the appointment creation if email fails
            }

            // Send notification to patient
            try {
                NotificationService::appointmentConfirmed($appointment);
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

        // Ensure service relationship is loaded
        if ($appointment->service_id && !$appointment->relationLoaded('service')) {
            $appointment->load('service');
        }

        // Always return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            // Format dates as 'Y-m-d H:i:s' string without timezone to avoid JS conversion
            $data = $appointment->toArray();
            $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
            $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');
            return response()->json($data);
        }

        // For non-AJAX requests, return JSON as well (since we don't have a show view)
        $data = $appointment->toArray();
        $data['start_datetime'] = $appointment->start_datetime->format('Y-m-d H:i:s');
        $data['end_datetime'] = $appointment->end_datetime->format('Y-m-d H:i:s');
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
            // Parse in Asia/Manila timezone to avoid UTC conversion
            $startDateTime = Carbon::parse($request->start_datetime, 'Asia/Manila');
            $endDateTime = $startDateTime->copy()->addMinutes($request->duration_minutes ?? 30);

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

            // Check for overlaps with other appointments (excluding current one)
            $overlappingAppointment = Appointment::where('id', '!=', $id)
                ->where(function($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_datetime', '<', $endDateTime)
                          ->where('end_datetime', '>', $startDateTime);
                })->first();

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
    public function destroy(string $id)
    {
        // Only allow admins (role_id === 1) to delete appointments
        if (auth()->user()->role_id !== 1) {
            \Log::warning('Unauthorized delete attempt by staff user', [
                'user_id' => auth()->id(),
                'appointment_id' => $id
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only administrators can delete appointments.'
                ], 403);
            }

            return redirect()->route('admin-appointment')->with('error', 'Unauthorized. Only administrators can delete appointments.');
        }

        try {
            $appointment = Appointment::findOrFail($id);
            \Log::info('Found appointment:', ['appointment' => $appointment]);

            // Send cancellation email before deleting
            try {
                MailService::sendAppointmentEmail('cancellation', $appointment->load(['patient.info', 'service']));
            } catch (\Exception $e) {
                \Log::error('Failed to send cancellation email:', ['error' => $e->getMessage()]);
            }

            // Send cancellation notification
            try {
                NotificationService::appointmentCancelled($appointment);
            } catch (\Exception $e) {
                \Log::error('Failed to send cancellation notification:', ['error' => $e->getMessage()]);
            }

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
        try {
            $appointment = Appointment::with(['patient.info', 'service'])->findOrFail($id);

            // Get the old status before updating
            $oldStatus = $appointment->status;

            $validated = $request->validate([
                'status' => 'required|in:Pending,Confirmed,Completed,Cancelled',
                'notes' => 'nullable|string|max:500'
            ]);

            // Validate status transitions
            $validTransitions = [
                'Pending' => ['Confirmed', 'Cancelled'],
                'Confirmed' => ['Completed', 'Cancelled'],
                'Completed' => [], // Completed appointments cannot change status
                'Cancelled' => [] // Cancelled appointments cannot change status
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
            $appointment->update(['status' => $validated['status']]);

            // Add status change note if provided
            if (!empty($validated['notes'])) {
                $currentNotes = $appointment->notes ?? '';
                $statusChangeNote = "\n\n[" . now()->format('Y-m-d H:i') . "] Status changed to {$validated['status']}: {$validated['notes']}";
                $appointment->update(['notes' => $currentNotes . $statusChangeNote]);
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
                    'Cancelled' => "Your appointment for {$serviceName} on {$appointmentDate} has been cancelled."
                ];

                Notification::create([
                    'user_id' => $appointment->patient_id,
                    'type' => 'appointment_status',
                    'title' => "Appointment {$validated['status']}",
                    'message' => $notificationMessages[$validated['status']] ?? "Your appointment status has been updated to {$validated['status']}.",
                    'icon' => $validated['status'] === 'Confirmed' ? 'bi-check-circle' :
                             ($validated['status'] === 'Cancelled' ? 'bi-x-circle' : 'bi-info-circle'),
                    'data' => json_encode([
                        'appointment_id' => $appointment->id,
                        'old_status' => $oldStatus,
                        'new_status' => $validated['status'],
                        'service' => $serviceName,
                        'date' => $appointmentDate
                    ])
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send status change notification:', ['error' => $e->getMessage()]);
            }

            \Log::info('Appointment status updated:', [
                'appointment_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'updated_by' => auth()->id()
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
