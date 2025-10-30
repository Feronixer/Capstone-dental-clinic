<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatbotFaq;

class ChatbotFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What are your clinic hours?',
                'answer' => 'We\'re open Mon-Fri 8:00 AM - 6:00 PM, Sat 9:00 AM - 2:00 PM',
                'is_active' => true,
                'order' => 1
            ],
            [
                'question' => 'How do I book an appointment?',
                'answer' => 'Click "Patient Login" to access our booking system, or call (555) 123-4567',
                'is_active' => true,
                'order' => 2
            ],
            [
                'question' => 'What services do you offer?',
                'answer' => "General Dentistry:\n• Tooth Restoration (Fillings)\n• Tooth Extraction\n\nSpecialized Treatments:\n• Wisdom Tooth Removal\n• Root Canal Treatment (Endodontics)\n\nAesthetic & Prosthetic Services:\n• Dental Crowns (Porcelain Fused to Metal, Emax, Zirconia)\n• Veneers\n• Orthodontics (Braces)",
                'is_active' => true,
                'order' => 3
            ],
            [
                'question' => 'Do you accept insurance?',
                'answer' => 'Yes, we accept most major dental insurance plans',
                'is_active' => true,
                'order' => 4
            ],
            [
                'question' => 'How much does a cleaning cost?',
                'answer' => 'Basic cleaning starts at $80. Contact us for a detailed estimate',
                'is_active' => true,
                'order' => 5
            ],
            [
                'question' => 'What if I have a dental emergency?',
                'answer' => 'Call us immediately at (555) 123-4567 for emergency appointments',
                'is_active' => true,
                'order' => 6
            ],
            [
                'question' => 'How do I get a patient account?',
                'answer' => 'Click "Register" to create an account, or contact us for assistance',
                'is_active' => true,
                'order' => 7
            ],
            [
                'question' => 'Where is the clinic located?',
                'answer' => 'We are located at Policarpio St. Gen. T. de Leon, Valenzuela City',
                'is_active' => true,
                'order' => 8
            ],
            [
                'question' => 'Do you offer payment plans?',
                'answer' => 'Yes, we offer flexible payment plans for major treatments. Contact us for details',
                'is_active' => true,
                'order' => 9
            ],
            [
                'question' => 'How often should I visit the dentist?',
                'answer' => 'We recommend regular check-ups every 6 months for optimal oral health',
                'is_active' => true,
                'order' => 10
            ]
        ];

        foreach ($faqs as $faq) {
            ChatbotFaq::create($faq);
        }
    }
}
