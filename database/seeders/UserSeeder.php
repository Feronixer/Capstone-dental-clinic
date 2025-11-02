<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'     => 'Admin User',
            'username' => 'admin123',
            'email'    => 'admin@example.com',
            'password' => bcrypt('1234asdf'),
            'role_id'  => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        // Calculate birthday from age (30 years ago)
        $birthday = Carbon::now()->subYears(30)->subDays(100)->format('Y-m-d');
        $age = Carbon::parse($birthday)->age;

        $user->info()->create([
            'first_name'  => 'Admin',
            'last_name'   => 'User',
            'phone'       => '09171234567',
            'address'     => '123 Admin St, Admin City',
            'gender'      => 'Male',
            'birthday'    => $birthday,
            'age'         => $age,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);
        // Create some specific test patients
        User::factory()->patient()->count(10)->create();

        // Create some staff members
        User::factory()->staff()->count(5)->create();

        // Create random users (mix of staff and patients)
        User::factory()->count(50)->create();
    }
}
