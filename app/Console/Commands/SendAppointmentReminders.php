<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\MailService;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic appointment reminders 24 hours, 3 hours before, and on the day of confirmed appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting appointment reminder check...');
        
        $now = Carbon::now('Asia/Manila');
        $reminder24hCount = 0;
        $reminder3hCount = 0;
        $reminderTodayCount = 0;
        
        // Find confirmed appointments that need 24-hour reminder
        // Appointment is exactly 24 hours away (with 5-minute window)
        $appointments24h = Appointment::where('status', 'Confirmed')
            ->where('reminder_24h_sent', false)
            ->whereBetween('start_datetime', [
                $now->copy()->addHours(24)->subMinutes(5),
                $now->copy()->addHours(24)->addMinutes(5)
            ])
            ->with(['patient.info', 'service'])
            ->get();
        
        foreach ($appointments24h as $appointment) {
            if ($this->sendReminder($appointment, '24h')) {
                $appointment->update([
                    'reminder_24h_sent' => true,
                    'reminder_24h_sent_at' => $now
                ]);
                $reminder24hCount++;
                $this->info("24-hour reminder sent for appointment ID: {$appointment->id}");
            }
        }
        
        // Find confirmed appointments that need 3-hour reminder
        // Appointment is exactly 3 hours away (with 5-minute window)
        $appointments3h = Appointment::where('status', 'Confirmed')
            ->where('reminder_3h_sent', false)
            ->whereBetween('start_datetime', [
                $now->copy()->addHours(3)->subMinutes(5),
                $now->copy()->addHours(3)->addMinutes(5)
            ])
            ->with(['patient.info', 'service'])
            ->get();
        
        foreach ($appointments3h as $appointment) {
            if ($this->sendReminder($appointment, '3h')) {
                $appointment->update([
                    'reminder_3h_sent' => true,
                    'reminder_3h_sent_at' => $now
                ]);
                $reminder3hCount++;
                $this->info("3-hour reminder sent for appointment ID: {$appointment->id}");
            }
        }
        
        // Find confirmed appointments scheduled today (same day reminder)
        // Check for appointments today that haven't passed yet
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        
        // Get appointments scheduled today that are in the future
        $appointmentsToday = Appointment::where('status', 'Confirmed')
            ->whereBetween('start_datetime', [$todayStart, $todayEnd])
            ->where('start_datetime', '>', $now) // Only future appointments today
            ->where(function($query) use ($now) {
                // Send reminder if:
                // 1. Appointment is within the next 2 hours, OR
                // 2. It's early morning (before 10 AM) and appointment is today
                $query->whereBetween('start_datetime', [
                    $now->copy(),
                    $now->copy()->addHours(2)
                ])->orWhere(function($q) use ($now) {
                    // If it's early morning (before 10 AM), send reminder for all appointments today
                    if ($now->hour < 10) {
                        $q->whereDate('start_datetime', $now->toDateString());
                    }
                });
            })
            ->with(['patient.info', 'service'])
            ->get();
        
        // Filter out appointments that already have reminders sent (to avoid duplicates)
        $appointmentsToday = $appointmentsToday->filter(function($appointment) use ($now) {
            // Only send today reminder if 24h and 3h reminders haven't been sent yet
            // OR if it's been more than 3 hours since the last reminder
            $hoursUntil = $now->diffInHours($appointment->start_datetime, false);
            
            // If appointment is more than 3 hours away, don't send today reminder yet
            if ($hoursUntil > 3) {
                return false;
            }
            
            // If 3h reminder was sent, don't send today reminder
            if ($appointment->reminder_3h_sent) {
                return false;
            }
            
            // If 24h reminder was sent but 3h wasn't, and it's less than 3 hours away, send today reminder
            if ($appointment->reminder_24h_sent && !$appointment->reminder_3h_sent && $hoursUntil <= 3) {
                return true;
            }
            
            // If no reminders were sent and it's today, send reminder
            if (!$appointment->reminder_24h_sent && !$appointment->reminder_3h_sent) {
                return true;
            }
            
            return false;
        });
        
        foreach ($appointmentsToday as $appointment) {
            if ($this->sendReminder($appointment, 'today')) {
                $reminderTodayCount++;
                $this->info("Today's reminder sent for appointment ID: {$appointment->id}");
            }
        }
        
        $this->info("Reminder check completed. 24h reminders: {$reminder24hCount}, 3h reminders: {$reminder3hCount}, Today reminders: {$reminderTodayCount}");
        
        return Command::SUCCESS;
    }
    
    /**
     * Send reminder email and notification for appointment
     */
    private function sendReminder(Appointment $appointment, string $type): bool
    {
        try {
            // Check if patient exists
            if (!$appointment->patient) {
                $this->warn("Skipping appointment {$appointment->id}: Patient not found");
                return false;
            }
            
            $emailSent = false;
            $notificationSent = false;
            
            // Send reminder email if patient has email
            if ($appointment->patient->email) {
                try {
                    $emailResult = MailService::sendAppointmentEmail('reminder', $appointment);
                    if ($emailResult) {
                        $emailSent = true;
                        $this->info("✓ Email sent for appointment {$appointment->id} to {$appointment->patient->email}");
                        \Log::info("Automatic {$type} reminder email sent for appointment {$appointment->id} to {$appointment->patient->email}");
                    } else {
                        $this->warn("✗ Failed to send {$type} reminder email for appointment {$appointment->id}");
                        \Log::warning("Failed to send {$type} reminder email for appointment {$appointment->id} - Check logs for details");
                    }
                } catch (\Exception $e) {
                    $this->error("✗ Exception sending email for appointment {$appointment->id}: " . $e->getMessage());
                    \Log::error("Exception sending {$type} reminder email for appointment {$appointment->id}: " . $e->getMessage());
                    \Log::error("Exception trace: " . $e->getTraceAsString());
                }
            } else {
                $this->warn("Skipping email for appointment {$appointment->id}: Patient email not found");
                \Log::warning("Skipping email for appointment {$appointment->id}: Patient email not found");
            }
            
            // Always create in-app notification regardless of email status
            try {
                NotificationService::appointmentReminder($appointment);
                $notificationSent = true;
                \Log::info("In-app reminder notification created for appointment {$appointment->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to create in-app notification for appointment {$appointment->id}: " . $e->getMessage());
            }
            
            // Return true if either email or notification was sent successfully
            return $emailSent || $notificationSent;
            
        } catch (\Exception $e) {
            \Log::error("Error sending {$type} reminder for appointment {$appointment->id}: " . $e->getMessage());
            $this->error("Error sending reminder: " . $e->getMessage());
            return false;
        }
    }
}
