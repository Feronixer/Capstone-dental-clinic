<?php

namespace App\Services;

use App\Models\MailTemplate;
use App\Mail\AppointmentNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class MailService
{
    /**
     * Send email using template
     */
    public static function sendAppointmentEmail($type, $appointment)
    {
        // Get mail template
        $template = MailTemplate::where('type', $type)->first();

        if (!$template) {
            // Use default template if not found
            $template = self::getDefaultTemplate($type);
        }

        // Get patient info
        $patient = $appointment->patient;
        if (!$patient || !$patient->email) {
            \Log::warning("Cannot send email: Patient email not found for appointment {$appointment->id}");
            return false;
        }

        $patientInfo = $patient->info;
        $firstName = $patientInfo->first_name ?? $patient->name;

        // Get service name
        $serviceName = $appointment->service ? $appointment->service->service_name : 'your appointment';

        // Format datetime
        $datetime = Carbon::parse($appointment->start_datetime)->format('F d, Y g:i A');

        // Replace placeholders in template content
        $messageContent = str_replace(
            ['%firstname%', '%datetime%', '%rescheduledtime%', '%service%'],
            [$firstName, $datetime, $datetime, $serviceName],
            $template->content ?? $template
        );

        // Send email
        try {
            Mail::to($patient->email)->send(
                new AppointmentNotification(
                    $template->subject ?? "Appointment Notification - JValera Dental Clinic",
                    $messageContent,
                    $firstName
                )
            );

            \Log::info("Email sent successfully to {$patient->email} for appointment {$appointment->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get default template if none exists in database
     */
    private static function getDefaultTemplate($type)
    {
        $defaults = [
            'initial_confirmation' => 'Good Day! %firstname%, you have a schedule appointment on %datetime% with Dr. Justin Valera regarding on your %service% treatment.',
            'reminder' => 'Reminder: %firstname%, you have an appointment on %datetime% with Dr. Justin Valera for %service%.',
            'cancellation' => 'Dear %firstname%, your appointment on %datetime% has been cancelled.',
            'rescheduling' => 'Hello %firstname%, your appointment has been rescheduled to %datetime%.',
            'follow_up' => 'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment for %service%.',
        ];

        return $defaults[$type] ?? 'Hello %firstname%, you have an appointment on %datetime%.';
    }
}

