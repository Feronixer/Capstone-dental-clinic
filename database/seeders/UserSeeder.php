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
            'name'     => 'Patient',
            'username' => 'patients',
            'email'    => 'patients@example.com',
            'password' => bcrypt('1234asdf'),
            'role_id'  => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

          ]);
          $user->info()->create([
            'first_name'  => 'Patient',
            'last_name'   => 'User',
            'phone'       => '09171234567',
            'address'     => '123 Admin St, Admin City',
            'age'         => 20,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
          ]);


        // User::factory()->count(count: 50)->create();
//        User::factory()
//            ->count(50)
//            ->hasInfo()
//            ->create();
    }
}
