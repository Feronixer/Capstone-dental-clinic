<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create an appointment confirmed notification
     */
    public static function appointmentConfirmed(Appointment $appointment): void
    {
        $appointmentDate = $appointment->start_datetime->format('F j, Y');
        $appointmentTime = $appointment->start_datetime->format('g:i A');

        Notification::create([
            'user_id' => $appointment->patient_id,
            'type' => Notification::TYPE_APPOINTMENT_CONFIRMED,
            'title' => 'Appointment Confirmed',
            'message' => "Your appointment on {$appointmentDate} at {$appointmentTime} has been confirmed.",
            'data' => [
                'appointment_id' => $appointment->id,
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
            ],
        ]);
    }

    /**
     * Create an appointment reminder notification
     */
    public static function appointmentReminder(Appointment $appointment): void
    {
        $appointmentDate = $appointment->start_datetime->format('F j, Y');
        $appointmentTime = $appointment->start_datetime->format('g:i A');
        $hoursUntil = Carbon::now()->diffInHours($appointment->start_datetime);

        $message = $hoursUntil <= 24
            ? "Reminder: You have an appointment tomorrow at {$appointmentTime}."
            : "Reminder: You have an appointment on {$appointmentDate} at {$appointmentTime}.";

        Notification::create([
            'user_id' => $appointment->patient_id,
            'type' => Notification::TYPE_APPOINTMENT_REMINDER,
            'title' => 'Appointment Reminder',
            'message' => $message,
            'data' => [
                'appointment_id' => $appointment->id,
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
            ],
        ]);
    }

    /**
     * Create an appointment rescheduled notification
     */
    public static function appointmentRescheduled(Appointment $appointment, Carbon $oldDateTime): void
    {
        $oldDate = $oldDateTime->format('F j, Y');
        $oldTime = $oldDateTime->format('g:i A');
        $newDate = $appointment->start_datetime->format('F j, Y');
        $newTime = $appointment->start_datetime->format('g:i A');

        Notification::create([
            'user_id' => $appointment->patient_id,
            'type' => Notification::TYPE_APPOINTMENT_RESCHEDULED,
            'title' => 'Appointment Rescheduled',
            'message' => "Your appointment has been rescheduled from {$oldDate} at {$oldTime} to {$newDate} at {$newTime}.",
            'data' => [
                'appointment_id' => $appointment->id,
                'old_date' => $oldDate,
                'old_time' => $oldTime,
                'new_date' => $newDate,
                'new_time' => $newTime,
            ],
        ]);
    }

    /**
     * Create an appointment cancelled notification
     */
    public static function appointmentCancelled(Appointment $appointment): void
    {
        $appointmentDate = $appointment->start_datetime->format('F j, Y');
        $appointmentTime = $appointment->start_datetime->format('g:i A');

        Notification::create([
            'user_id' => $appointment->patient_id,
            'type' => Notification::TYPE_APPOINTMENT_CANCELLED,
            'title' => 'Appointment Cancelled',
            'message' => "Your appointment on {$appointmentDate} at {$appointmentTime} has been cancelled.",
            'data' => [
                'appointment_id' => $appointment->id,
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
            ],
        ]);
    }

    /**
     * Create a record updated notification
     */
    public static function recordUpdated(int $patientId, string $recordType = 'dental record'): void
    {
        Notification::create([
            'user_id' => $patientId,
            'type' => Notification::TYPE_RECORD_UPDATED,
            'title' => 'Record Updated',
            'message' => "Your {$recordType} has been updated. Please review the changes.",
            'data' => [
                'record_type' => $recordType,
            ],
        ]);
    }

    /**
     * Create an announcement notification
     */
    public static function announcement(int $patientId, string $title, string $message, array $data = []): void
    {
        Notification::create([
            'user_id' => $patientId,
            'type' => Notification::TYPE_ANNOUNCEMENT,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Create a general notification
     */
    public static function general(int $patientId, string $title, string $message, array $data = []): void
    {
        Notification::create([
            'user_id' => $patientId,
            'type' => Notification::TYPE_GENERAL,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Broadcast announcement to all patients
     */
    public static function broadcastToAllPatients(string $title, string $message, array $data = []): void
    {
        $patients = User::whereHas('info', function($query) {
            $query->where('role_id', 3); // Patient role
        })->get();

        foreach ($patients as $patient) {
            self::announcement($patient->id, $title, $message, $data);
        }
    }

    /**
     * Clean up old read notifications
     */
    public static function cleanupOldNotifications(int $daysOld = 90): int
    {
        return Notification::where('is_read', true)
            ->where('created_at', '<', Carbon::now()->subDays($daysOld))
            ->delete();
    }
}

