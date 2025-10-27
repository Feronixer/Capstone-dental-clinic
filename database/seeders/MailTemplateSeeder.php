<?php

namespace Database\Seeders;

use App\Models\MailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'type' => 'initial_confirmation',
                'subject' => 'Appointment Confirmation - JValera Dental Clinic',
                'content' => 'Good Day! %firstname%, you have a schedule appointment on %datetime% with Dr. Justin Valera regarding on your %service% treatment.'
            ],
            [
                'type' => 'reminder',
                'subject' => 'Appointment Reminder - JValera Dental Clinic',
                'content' => 'Reminder: %firstname%, you have an appointment on %datetime% with Dr. Justin Valera for %service%.'
            ],
            [
                'type' => 'cancellation',
                'subject' => 'Appointment Cancelled - JValera Dental Clinic',
                'content' => 'Dear %firstname%, your appointment on %datetime% has been cancelled. If you have any questions, please contact us.'
            ],
            [
                'type' => 'rescheduling',
                'subject' => 'Appointment Rescheduled - JValera Dental Clinic',
                'content' => 'Hello %firstname%, your appointment has been rescheduled to %datetime%. Please let us know if you have any concerns.'
            ],
            [
                'type' => 'follow_up',
                'subject' => 'Follow-up Reminder - JValera Dental Clinic',
                'content' => 'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment for your %service% treatment.'
            ]
        ];

        foreach ($templates as $template) {
            MailTemplate::updateOrCreate(
                ['type' => $template['type']],
                $template
            );
        }
    }
}
