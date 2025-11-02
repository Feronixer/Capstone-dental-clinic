<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrCreate to prevent duplicates
        $roles = [
            ['role' => 'Admin'],
            ['role' => 'Staff'],
            ['role' => 'Patient'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['role' => $role['role']],
                [
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
