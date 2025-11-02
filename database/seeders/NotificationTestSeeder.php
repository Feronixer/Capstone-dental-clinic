<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;
use App\Models\AppointmentRequest;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;

class NotificationTestSeeder extends Seeder
{
    /**
     * Run the database seeds for testing notification system.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Notification Test Data Seeder...');

        // Find or create test patient
        $patient = User::where('email', 'test_patient@clinic.com')->first();
        if (!$patient) {
            $this->command->warn('Test patient not found. Creating...');
            $patient = User::create([
                'name' => 'Test Patient',
                'username' => 'test_patient',
                'email' => 'test_patient@clinic.com',
                'password' => bcrypt('password'),
                'role_id' => 3, // Patient role
            ]);

            // Calculate birthday from age (25 years ago)
            $birthday = Carbon::now()->subYears(25)->subDays(rand(0, 365))->format('Y-m-d');
            $age = Carbon::parse($birthday)->age;

            $patient->info()->create([
                'first_name' => 'Test',
                'last_name' => 'Patient',
                'middle_name' => 'A',
                'phone' => '09123456789',
                'address' => '123 Test Street, Test City',
                'gender' => 'Male',
                'birthday' => $birthday,
                'age' => $age,
            ]);
        }

        $this->command->info('✅ Test patient ready: ' . $patient->email);

        // Create 50 diverse notifications for patient
        $this->command->info('📧 Creating 50 test notifications...');

        $notificationTypes = [
            [
                'type' => Notification::TYPE_APPOINTMENT_CONFIRMED,
                'title' => 'Appointment Confirmed',
                'message' => 'Your appointment for [DATE] at [TIME] has been confirmed.',
            ],
            [
                'type' => Notification::TYPE_APPOINTMENT_REMINDER,
                'title' => 'Appointment Reminder',
                'message' => 'Reminder: You have an appointment tomorrow at [TIME].',
            ],
            [
                'type' => Notification::TYPE_APPOINTMENT_CANCELLED,
                'title' => 'Appointment Cancelled',
                'message' => 'Your appointment for [DATE] has been cancelled.',
            ],
            [
                'type' => Notification::TYPE_APPOINTMENT_RESCHEDULED,
                'title' => 'Appointment Rescheduled',
                'message' => 'Your appointment has been rescheduled to [DATE] at [TIME].',
            ],
            [
                'type' => Notification::TYPE_RECORD_UPDATED,
                'title' => 'Medical Record Updated',
                'message' => 'Your medical record has been updated by the clinic.',
            ],
            [
                'type' => Notification::TYPE_ANNOUNCEMENT,
                'title' => 'Clinic Announcement',
                'message' => 'Important announcement from JValera Dental Clinic.',
            ],
        ];

        for ($i = 1; $i <= 50; $i++) {
            $notifType = $notificationTypes[array_rand($notificationTypes)];
            $isRead = $i % 3 == 0; // Every 3rd notification is read

            // Generate realistic dates
            $date = Carbon::now()->addDays(rand(1, 30))->format('F j, Y');
            $time = Carbon::now()->setHour(rand(9, 17))->setMinute([0, 15, 30, 45][rand(0, 3)])->format('g:i A');

            $message = str_replace('[DATE]', $date, $notifType['message']);
            $message = str_replace('[TIME]', $time, $message);

            Notification::create([
                'user_id' => $patient->id,
                'type' => $notifType['type'],
                'title' => $notifType['title'] . " #{$i}",
                'message' => $message,
                'is_read' => $isRead,
                'read_at' => $isRead ? Carbon::now()->subDays(rand(1, 10)) : null,
                'created_at' => Carbon::now()->subDays(rand(0, 60)),
                'data' => [
                    'test_id' => $i,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                ],
            ]);
        }

        $this->command->info('✅ Created 50 notifications');

        // Get first service for requests
        $service = Service::first();
        if (!$service) {
            $this->command->error('❌ No services found. Please run ServiceSeeder first.');
            return;
        }

        // Create 5 pending walk-in appointment requests
        $this->command->info('📝 Creating 5 pending walk-in requests...');

        for ($i = 1; $i <= 5; $i++) {
            $requestedDate = Carbon::now()->addDays($i + 1)->setHour(rand(9, 16))->setMinute([0, 30][rand(0, 1)]);

            AppointmentRequest::create([
                'patient_id' => $patient->id,
                'service_id' => $service->id,
                'requested_datetime' => $requestedDate,
                'duration_minutes' => 60,
                'request_type' => 'walk-in',
                'reason' => "Test walk-in request #{$i} - Dental checkup",
                'status' => 'Pending',
                'created_at' => Carbon::now()->subHours(rand(1, 24)),
            ]);
        }

        $this->command->info('✅ Created 5 pending walk-in requests');

        // Create 2 existing appointments for reschedule testing
        $this->command->info('📅 Creating 2 existing appointments for reschedule testing...');

        for ($i = 1; $i <= 2; $i++) {
            $startDate = Carbon::now()->addDays(10 + $i)->setHour(10 + $i)->setMinute(0);

            Appointment::create([
                'patient_id' => $patient->id,
                'service_id' => $service->id,
                'start_datetime' => $startDate,
                'end_datetime' => $startDate->copy()->addHour(),
                'duration_minutes' => 60,
                'status' => 'Confirmed',
                'reason_for_visit' => "Test appointment #{$i}",
                'notes' => 'Test appointment for reschedule testing',
            ]);
        }

        $this->command->info('✅ Created 2 existing appointments');

        // Create 2 pending reschedule requests
        $this->command->info('🔄 Creating 2 pending reschedule requests...');

        $existingAppts = Appointment::where('patient_id', $patient->id)
            ->where('status', 'Confirmed')
            ->take(2)
            ->get();

        foreach ($existingAppts as $index => $appt) {
            $newRequestedDate = $appt->start_datetime->copy()->addDays(7)->setHour(14);

            AppointmentRequest::create([
                'patient_id' => $patient->id,
                'service_id' => $appt->service_id,
                'existing_appointment_id' => $appt->id,
                'requested_datetime' => $newRequestedDate,
                'duration_minutes' => $appt->duration_minutes,
                'request_type' => 'reschedule',
                'reason' => "Test reschedule request #" . ($index + 1) . " - Need to change date",
                'status' => 'Pending',
                'created_at' => Carbon::now()->subHours(rand(1, 12)),
            ]);
        }

        $this->command->info('✅ Created 2 pending reschedule requests');

        // Summary
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('🎉 NOTIFICATION TEST DATA SEEDED SUCCESSFULLY!');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->table(
            ['Item', 'Count', 'Details'],
            [
                ['Notifications (Total)', '50', '~33 unread, ~17 read'],
                ['Walk-in Requests', '5', 'All pending'],
                ['Reschedule Requests', '2', 'All pending'],
                ['Existing Appointments', '2', 'For reschedule testing'],
            ]
        );
        $this->command->info('');
        $this->command->info('📝 Test Account:');
        $this->command->info('   Email: test_patient@clinic.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('🌐 Test URLs:');
        $this->command->info('   Patient Notifications: /patient/notifications');
        $this->command->info('   Staff Notifications: /staff/notifications');
        $this->command->info('   Admin Notifications: /admin/notifications');
        $this->command->info('');
        $this->command->info('✨ You can now run the test cases!');
        $this->command->info('═══════════════════════════════════════════════════');
    }
}

