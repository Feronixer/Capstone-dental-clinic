<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all patients (role_id = 3)
        $patients = User::whereHas('info', function($query) {
            $query->where('role_id', 3);
        })->get();

        if ($patients->isEmpty()) {
            $this->command->warn('No patients found. Skipping notification seeding.');
            return;
        }

        foreach ($patients as $patient) {
            // Create some sample notifications for each patient
            $notifications = [
                [
                    'type' => Notification::TYPE_APPOINTMENT_CONFIRMED,
                    'title' => 'Appointment Confirmed',
                    'message' => 'Your appointment on ' . Carbon::now()->addDays(7)->format('F j, Y') . ' at 10:00 AM has been confirmed.',
                    'data' => [
                        'appointment_date' => Carbon::now()->addDays(7)->format('F j, Y'),
                        'appointment_time' => '10:00 AM',
                    ],
                    'created_at' => Carbon::now()->subMinutes(2),
                ],
                [
                    'type' => Notification::TYPE_RECORD_UPDATED,
                    'title' => 'Record Updated',
                    'message' => 'Your dental record has been updated. Please review the changes.',
                    'data' => [
                        'record_type' => 'dental record',
                    ],
                    'created_at' => Carbon::now()->subHours(1),
                ],
                [
                    'type' => Notification::TYPE_APPOINTMENT_REMINDER,
                    'title' => 'Appointment Reminder',
                    'message' => 'Reminder: You have an appointment tomorrow at 2:00 PM.',
                    'data' => [
                        'appointment_date' => Carbon::now()->addDay()->format('F j, Y'),
                        'appointment_time' => '2:00 PM',
                    ],
                    'created_at' => Carbon::now()->subHours(5),
                    'is_read' => true,
                    'read_at' => Carbon::now()->subHours(4),
                ],
            ];

            foreach ($notifications as $notificationData) {
                Notification::create(array_merge([
                    'user_id' => $patient->id,
                ], $notificationData));
            }
        }

        $this->command->info('Notifications seeded successfully!');
    }
}
