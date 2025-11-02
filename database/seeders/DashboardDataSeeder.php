<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;

class DashboardDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample patient data for demographics
        $samplePatients = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'gender' => 'Male', 'age' => 25, 'phone' => '09123456789', 'email' => 'john.doe@example.com'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'gender' => 'Female', 'age' => 30, 'phone' => '09234567890', 'email' => 'jane.smith@example.com'],
            ['first_name' => 'Michael', 'last_name' => 'Johnson', 'gender' => 'Male', 'age' => 15, 'phone' => '09345678901', 'email' => 'michael.j@example.com'],
            ['first_name' => 'Emily', 'last_name' => 'Brown', 'gender' => 'Female', 'age' => 35, 'phone' => '09456789012', 'email' => 'emily.brown@example.com'],
            ['first_name' => 'David', 'last_name' => 'Wilson', 'gender' => 'Male', 'age' => 12, 'phone' => '09567890123', 'email' => 'david.wilson@example.com'],
            ['first_name' => 'Sarah', 'last_name' => 'Davis', 'gender' => 'Female', 'age' => 28, 'phone' => '09678901234', 'email' => 'sarah.davis@example.com'],
            ['first_name' => 'Robert', 'last_name' => 'Martinez', 'gender' => 'Male', 'age' => 45, 'phone' => '09789012345', 'email' => 'robert.m@example.com'],
            ['first_name' => 'Lisa', 'last_name' => 'Garcia', 'gender' => 'Female', 'age' => 10, 'phone' => '09890123456', 'email' => 'lisa.garcia@example.com'],
        ];

        foreach ($samplePatients as $patient) {
            // Check if user already exists
            $username = strtolower($patient['first_name'] . '.' . $patient['last_name']);
            $existingUser = User::where('username', $username)->first();

            if ($existingUser) {
                $this->command->warn('User ' . $username . ' already exists, skipping...');
                continue;
            }

            // Calculate birthday from age
            $birthday = Carbon::now()->subYears($patient['age'])->subDays(rand(0, 365))->format('Y-m-d');
            $calculatedAge = Carbon::parse($birthday)->age;

            // Create user account (role_id = 3 for patients)
            $user = User::create([
                'username' => $username,
                'name' => $patient['first_name'] . ' ' . $patient['last_name'],
                'email' => $patient['email'],
                'password' => bcrypt('password123'),
                'role_id' => 3,
            ]);

            // Create user info
            UserInfo::create([
                'user_id' => $user->id,
                'first_name' => $patient['first_name'],
                'last_name' => $patient['last_name'],
                'gender' => $patient['gender'],
                'birthday' => $birthday,
                'age' => $calculatedAge,
                'phone' => $patient['phone'],
            ]);
        }

        // Add sample appointment ratings (for Service Feedback chart)
        // Get first service or create a sample one
        $service = Service::first();
        if (!$service) {
            $service = Service::create([
                'service_name' => 'Dental Cleaning',
                'description' => 'Regular teeth cleaning',
                'default_duration_minutes' => 30,
                'price' => 500.00,
                'is_active' => true,
            ]);
        }

        // Get all patients
        $patients = User::where('role_id', 3)->get();

        // Create sample completed appointments with ratings
        $ratingsDistribution = [
            5 => 8,  // 8 five-star ratings
            4 => 5,  // 5 four-star ratings
            3 => 3,  // 3 three-star ratings
            2 => 1,  // 1 two-star rating
            1 => 1,  // 1 one-star rating
        ];

        $appointmentCount = 0;
        foreach ($ratingsDistribution as $rating => $count) {
            for ($i = 0; $i < $count; $i++) {
                if ($appointmentCount >= $patients->count()) {
                    break; // Not enough patients
                }

                $patient = $patients[$appointmentCount];
                $startDate = Carbon::now()->subDays(rand(1, 30));

                Appointment::create([
                    'patient_id' => $patient->id,
                    'service_id' => $service->id,
                    'start_datetime' => $startDate,
                    'end_datetime' => $startDate->copy()->addMinutes(30),
                    'duration_minutes' => 30,
                    'status' => 'Completed',
                    'notes' => 'Sample completed appointment',
                    'rating' => $rating,
                    'patient_feedback' => 'Sample feedback for ' . $rating . ' star rating',
                    'rated_at' => $startDate->copy()->addDays(1),
                ]);

                $appointmentCount++;
            }
        }

        $this->command->info('Sample dashboard data created successfully!');
        $this->command->info('- Created ' . count($samplePatients) . ' sample patients');
        $this->command->info('- Created ' . $appointmentCount . ' sample appointments with ratings');
    }
}
