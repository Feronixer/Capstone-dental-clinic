<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\MailService;
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
    protected $description = 'Send automatic appointment reminders 24 hours and 3 hours before confirmed appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting appointment reminder check...');
        
        $now = Carbon::now('Asia/Manila');
        $reminder24hCount = 0;
        $reminder3hCount = 0;
        
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
        
        $this->info("Reminder check completed. 24h reminders: {$reminder24hCount}, 3h reminders: {$reminder3hCount}");
        
        return Command::SUCCESS;
    }
    
    /**
     * Send reminder email for appointment
     */
    private function sendReminder(Appointment $appointment, string $type): bool
    {
        try {
            // Check if patient has email
            if (!$appointment->patient || !$appointment->patient->email) {
                $this->warn("Skipping appointment {$appointment->id}: Patient email not found");
                return false;
            }
            
            // Send reminder email using MailService
            $result = MailService::sendAppointmentEmail('reminder', $appointment);
            
            if ($result) {
                \Log::info("Automatic {$type} reminder email sent for appointment {$appointment->id} to {$appointment->patient->email}");
                return true;
            } else {
                \Log::warning("Failed to send {$type} reminder email for appointment {$appointment->id}");
                return false;
            }
        } catch (\Exception $e) {
            \Log::error("Error sending {$type} reminder for appointment {$appointment->id}: " . $e->getMessage());
            $this->error("Error sending reminder: " . $e->getMessage());
            return false;
        }
    }
}
