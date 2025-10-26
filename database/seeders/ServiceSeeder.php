<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_name' => 'Tooth Extraction',
                'description' => 'Surgical removal of teeth that cannot be saved or are causing problems',
                'price' => 1500.00,
                'price_notes' => 'Per tooth',
                'default_duration_minutes' => 45,
                'icon_class' => 'bi-tooth',
                'is_active' => true
            ],
            [
                'service_name' => 'Emax Dental Crown',
                'description' => 'High-quality ceramic crown for superior aesthetics and durability',
                'price' => 25000.00,
                'price_notes' => 'Includes lab work',
                'default_duration_minutes' => 120,
                'icon_class' => 'bi-gem',
                'is_active' => true
            ],
            [
                'service_name' => 'Zirconia Crown',
                'description' => 'Strong and durable zirconia crown for posterior teeth',
                'price' => 20000.00,
                'price_notes' => 'Includes lab work',
                'default_duration_minutes' => 120,
                'icon_class' => 'bi-shield-check',
                'is_active' => true
            ],
            [
                'service_name' => 'Flexible Denture',
                'description' => 'Comfortable and flexible partial denture for missing teeth',
                'price' => 35000.00,
                'price_notes' => 'Full arch',
                'default_duration_minutes' => 90,
                'icon_class' => 'bi-teeth',
                'is_active' => true
            ],
            [
                'service_name' => 'Consultation',
                'description' => 'Initial dental examination and treatment planning',
                'price' => 500.00,
                'price_notes' => 'First visit',
                'default_duration_minutes' => 30,
                'icon_class' => 'bi-chat-dots',
                'is_active' => true
            ],
            [
                'service_name' => 'Teeth Cleaning',
                'description' => 'Professional dental cleaning and scaling',
                'price' => 2000.00,
                'price_notes' => 'Regular cleaning',
                'default_duration_minutes' => 60,
                'icon_class' => 'bi-brush',
                'is_active' => true
            ],
            [
                'service_name' => 'Filling',
                'description' => 'Tooth restoration using composite or amalgam filling',
                'price' => 3000.00,
                'price_notes' => 'Per surface',
                'default_duration_minutes' => 45,
                'icon_class' => 'bi-patch-plus',
                'is_active' => true
            ],
            [
                'service_name' => 'Root Canal',
                'description' => 'Endodontic treatment to save infected teeth',
                'price' => 8000.00,
                'price_notes' => 'Single canal',
                'default_duration_minutes' => 90,
                'icon_class' => 'bi-tools',
                'is_active' => true
            ],
            [
                'service_name' => 'Orthodontics',
                'description' => 'Braces and aligner treatment for teeth straightening',
                'price' => 50000.00,
                'price_notes' => 'Full treatment',
                'default_duration_minutes' => 60,
                'icon_class' => 'bi-braces',
                'is_active' => true
            ],
            [
                'service_name' => 'Dental Implant',
                'description' => 'Surgical placement of titanium implant for tooth replacement',
                'price' => 45000.00,
                'price_notes' => 'Per implant',
                'default_duration_minutes' => 120,
                'icon_class' => 'bi-arrow-up-circle',
                'is_active' => true
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
