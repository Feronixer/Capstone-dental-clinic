<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Notification;
use App\Services\MailService;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get pending appointment requests
        $pendingRequests = AppointmentRequest::with(['patient.info', 'service'])
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view("staff.notification", compact('pendingRequests'));
    }

    /**
     * Approve appointment request
     */
    public function approveRequest(Request $request, $id)
    {
        try {
            $appointmentRequest = AppointmentRequest::with(['patient.info', 'service', 'existingAppointment.service'])->findOrFail($id);

            if ($appointmentRequest->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed'
                ], 400);
            }

            // For reschedule requests, use the duration from the request (based on original appointment)
            // For walk-in requests, validate and use the provided duration
            if ($appointmentRequest->isWalkIn()) {
                $request->validate([
                    'duration_minutes' => 'nullable|integer|min:15|max:480'
                ]);
            }

            // Start a database transaction to ensure data consistency
            \DB::beginTransaction();

            // Store old appointment reference for reschedule requests (needed for both cancellation and tracking)
            $oldAppointment = null;

            try {
                // If this is a reschedule request, cancel the old appointment
                if ($appointmentRequest->request_type === 'reschedule' && $appointmentRequest->existing_appointment_id) {
                    $oldAppointment = Appointment::find($appointmentRequest->existing_appointment_id);
                    if ($oldAppointment) {
                        // Load relationships for the old appointment
                        $oldAppointment->load(['service']);

                        // Format the old appointment date for notification
                        $oldFormattedDate = $oldAppointment->start_datetime->format('F j, Y');
                        $oldFormattedTime = $oldAppointment->start_datetime->format('g:i A');

                        // Cancel the old appointment
                        $oldAppointment->update(['status' => 'Cancelled']);

                        // Notify patient about the cancellation of old appointment
                        Notification::create([
                            'user_id' => $appointmentRequest->patient_id,
                            'type' => Notification::TYPE_APPOINTMENT_CANCELLED,
                            'title' => 'Previous Appointment Cancelled',
                            'message' => "Your appointment on {$oldFormattedDate} at {$oldFormattedTime} has been cancelled as part of your approved reschedule request.",
                            'data' => [
                                'old_appointment_id' => $oldAppointment->id,
                                'cancelled_date' => $oldFormattedDate,
                                'cancelled_time' => $oldFormattedTime,
                                'reason' => 'Rescheduled to new date'
                            ]
                        ]);

                        \Log::info('Old appointment cancelled during reschedule approval', [
                            'old_appointment_id' => $oldAppointment->id,
                            'new_request_id' => $appointmentRequest->id
                        ]);
                    }
                }

                // Determine service_id and reason_for_visit based on the request
                $serviceId = $appointmentRequest->service_id;
                $reasonForVisit = $appointmentRequest->reason;

                // If other_concern is provided, use it as the reason_for_visit and service_id remains null
                if ($appointmentRequest->other_concern) {
                    $reasonForVisit = $appointmentRequest->other_concern;
                    $serviceId = null; // Set service_id to null for "Other" concerns
                }

                // Determine duration based on request type
                if ($appointmentRequest->isWalkIn()) {
                    // For walk-in requests, use the duration from request if provided, otherwise use the appointment request's duration
                    $durationMinutes = $request->input('duration_minutes', $appointmentRequest->duration_minutes);
                    // If still no duration, get from service if available
                    if (!$durationMinutes && $serviceId) {
                        $service = \App\Models\Service::find($serviceId);
                        if ($service) {
                            $durationMinutes = $service->default_duration_minutes;
                        }
                    }
                    // Fallback to 30 minutes
                    $durationMinutes = $durationMinutes ?? 30;
                } else {
                    // For reschedule requests, always use the duration from the appointment request (from original appointment)
                    $durationMinutes = $appointmentRequest->duration_minutes;
                }

                // Validate clinic hours: 11:00 AM to 6:00 PM only
                $appointmentTime = $appointmentRequest->requested_datetime->copy()->setTime($appointmentRequest->requested_datetime->hour, $appointmentRequest->requested_datetime->minute, 0);
                $clinicOpen = Carbon::parse($appointmentRequest->requested_datetime->toDateString() . ' 11:00:00', 'Asia/Manila');
                $clinicClose = Carbon::parse($appointmentRequest->requested_datetime->toDateString() . ' 18:00:00', 'Asia/Manila');
                
                if ($appointmentTime->lt($clinicOpen) || $appointmentTime->gte($clinicClose)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointments can only be scheduled between 11:00 AM and 6:00 PM. The clinic is closed outside these hours.',
                        'errors' => ['requested_datetime' => ['Appointments can only be scheduled between 11:00 AM and 6:00 PM']]
                    ], 422);
                }

                // Recalculate end_datetime based on the final duration
                $endDateTime = $appointmentRequest->requested_datetime->copy()->addMinutes($durationMinutes);
                
                // Validate that appointment end time doesn't exceed clinic closing time (6:00 PM)
                if ($endDateTime->gt($clinicClose)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointment end time exceeds clinic closing time (6:00 PM). Please adjust the appointment time or duration.',
                        'errors' => ['requested_datetime' => ['Appointment end time exceeds clinic closing time (6:00 PM)']]
                    ], 422);
                }

                // Prepare notes - add "Emergency" prefix for walk-in requests
                $notes = $appointmentRequest->reason;
                if ($appointmentRequest->isWalkIn()) {
                    // Check if "emergency" is already in the notes, if not add it
                    if (stripos($notes, 'emergency') === false) {
                        $notes = 'Emergency: ' . $notes;
                    }
                }

                // Create the new appointment
                // Ensure duration is set from service if not already set
                if (!$durationMinutes && $serviceId) {
                    $service = \App\Models\Service::find($serviceId);
                    if ($service) {
                        $durationMinutes = $service->default_duration_minutes;
                    }
                }
                $durationMinutes = $durationMinutes ?? 30;

                $appointmentData = [
                    'patient_id' => $appointmentRequest->patient_id,
                    'service_id' => $serviceId,
                    'start_datetime' => $appointmentRequest->requested_datetime,
                    'end_datetime' => $endDateTime,
                    'duration_minutes' => $durationMinutes,
                    'status' => 'Confirmed', // Rescheduled appointments are confirmed, NOT cancelled
                    'notes' => $notes,
                    'reason_for_visit' => $reasonForVisit
                ];

                // If this is a reschedule request, track the rescheduling information
                // Use the already-loaded $oldAppointment variable to avoid duplicate query
                if ($oldAppointment && $oldAppointment->exists) {
                    $appointmentData['original_datetime'] = $oldAppointment->start_datetime;
                    $appointmentData['rescheduled_at'] = now();
                }

                $appointment = Appointment::create($appointmentData);

                // Load the service relationship to ensure it's available
                $appointment->load(['service', 'patient.info']);

                // Update request status
                $appointmentRequest->update([
                    'status' => 'Approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now()
                ]);

                // Notify patient
                $formattedDate = $appointment->start_datetime->format('F j, Y');
                $formattedTime = $appointment->start_datetime->format('g:i A');

                Notification::create([
                    'user_id' => $appointmentRequest->patient_id,
                    'type' => Notification::TYPE_APPOINTMENT_CONFIRMED,
                    'title' => 'Appointment Request Approved',
                    'message' => "Your " . ($appointmentRequest->isWalkIn() ? 'walk-in' : 'reschedule') .
                               " request for {$formattedDate} at {$formattedTime} has been approved!",
                    'data' => [
                        'appointment_id' => $appointment->id,
                        'appointment_date' => $formattedDate,
                        'appointment_time' => $formattedTime,
                    ]
                ]);

                // Send automated email based on request type
                try {
                    if ($appointmentRequest->request_type === 'reschedule') {
                        // For reschedule requests, send rescheduling email
                        MailService::sendAppointmentEmail('rescheduling', $appointment);
                        \Log::info("Automated rescheduling email sent for approved reschedule request {$appointmentRequest->id}, appointment {$appointment->id}");
                    } elseif ($appointmentRequest->isWalkIn()) {
                        // For emergency/walk-in requests, send initial confirmation email
                        MailService::sendAppointmentEmail('initial_confirmation', $appointment);
                        \Log::info("Automated initial confirmation email sent for approved walk-in request {$appointmentRequest->id}, appointment {$appointment->id}");
                    }
                } catch (\Exception $e) {
                    \Log::error("Failed to send automated email for approved request: " . $e->getMessage());
                    // Don't fail the approval if email fails
                }

                // Commit the transaction
                \DB::commit();

                // Log the created appointment for debugging
                \Log::info('Appointment created successfully', [
                    'appointment_id' => $appointment->id,
                    'service_id' => $appointment->service_id,
                    'service_name' => $appointment->service ? $appointment->service->service_name : 'null',
                    'start_datetime' => $appointment->start_datetime,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Appointment request approved and appointment created',
                    'appointment' => [
                        'id' => $appointment->id,
                        'month' => $appointment->start_datetime->month,
                        'year' => $appointment->start_datetime->year,
                    ]
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error approving appointment request:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error approving request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deny appointment request
     */
    public function denyRequest(Request $request, $id)
    {
        try {
            $appointmentRequest = AppointmentRequest::with(['patient.info', 'service', 'existingAppointment.service'])->findOrFail($id);

            if ($appointmentRequest->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed'
                ], 400);
            }

            $reason = $request->input('reason', 'No reason provided');

            // Update request status
            $appointmentRequest->update([
                'status' => 'Denied',
                'reviewed_by' => auth()->id(),
                'review_notes' => $reason,
                'reviewed_at' => now()
            ]);

            // Notify patient
            $formattedDate = $appointmentRequest->requested_datetime->format('F j, Y');
            $formattedTime = $appointmentRequest->requested_datetime->format('g:i A');

            Notification::create([
                'user_id' => $appointmentRequest->patient_id,
                'type' => Notification::TYPE_APPOINTMENT_CANCELLED,
                'title' => 'Appointment Request Denied',
                'message' => "Your " . ($appointmentRequest->isWalkIn() ? 'walk-in' : 'reschedule') .
                           " request for {$formattedDate} at {$formattedTime} has been denied. Reason: {$reason}",
                'data' => [
                    'request_id' => $appointmentRequest->id,
                    'requested_date' => $formattedDate,
                    'requested_time' => $formattedTime,
                    'reason' => $reason
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment request denied'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error denying appointment request:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error denying request: ' . $e->getMessage()
            ], 500);
        }
    }
}
