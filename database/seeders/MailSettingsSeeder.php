<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MailSetting;

class MailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $templates = [
            [
                'name' => 'Initial Confirmation',
                'structure' => "JValera Dental Clinic\n\nGood Day! %first% %last%, you have a scheduled appointment on %datetime% with Dr. Justin Valera regarding your %service% treatment.",
                'preview' => "JValera Dental Clinic\n\nGood Day! Angel Cuadernal, you have a scheduled appointment on April 15, 2025 3:00 PM with Dr. Justin Valera regarding your Flexible Dentures treatment."
            ],
            [
                'name' => 'Reminders',
                'structure' => "Reminder: Hello %first%, you have an appointment on %datetime%.",
                'preview' => "Reminder: Hello Angel, you have an appointment on April 15, 2025 3:00 PM."
            ],
            [
                'name' => 'Cancellation',
                'structure' => "We regret to inform you that your appointment on %datetime% has been cancelled.",
                'preview' => "We regret to inform you that your appointment on April 15, 2025 has been cancelled."
            ],
            [
                'name' => 'Rescheduling',
                'structure' => "Your appointment has been rescheduled to %datetime%.",
                'preview' => "Your appointment has been rescheduled to April 20, 2025 4:00 PM."
            ],
            [
                'name' => 'Follow Ups',
                'structure' => "Hello %first%, we would like to follow up regarding your recent %service% treatment.",
                'preview' => "Hello Angel, we would like to follow up regarding your recent Tooth Extraction."
            ],
        ];

        foreach ($templates as $template) {
            MailSetting::create($template);
        }
    }
}
