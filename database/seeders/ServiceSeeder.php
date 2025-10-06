<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ServiceSeeder::class);
    }
}

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'icon_fa' => 'fa-tooth',
            'service' => 'Teeth Cleaning',
            'price' => 500.00,
            'description' => 'Professional cleaning to remove plaque and tartar.',
        ]);

        Service::create([
            'icon_fa' => 'fa-tooth',
            'service' => 'Tooth Extraction',
            'price' => 1500.00,
            'description' => 'Safe removal of a tooth by a dental professional.',
        ]);

        Service ::create([
            'icon_fa' => 'fa-tooth',
            'service' => 'Dental Filling',
            'price' => 800.00,
            'description' => 'Restoration of a tooth damaged by decay.',
        ]);
    }
}
