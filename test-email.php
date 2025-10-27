<?php
// Quick Email Test Script
// Run this with: php test-email.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Services\MailService;

echo "🔍 Finding an appointment to test...\n\n";

$appointment = Appointment::with(['patient.info', 'service'])->first();

if (!$appointment) {
    echo "❌ No appointments found! Please create an appointment first.\n";
    exit(1);
}

$patient = $appointment->patient;

echo "📧 Sending test email...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Patient: {$patient->name}\n";
echo "Email: {$patient->email}\n";
echo "Service: " . ($appointment->service ? $appointment->service->service_name : 'N/A') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

try {
    MailService::sendAppointmentEmail('initial_confirmation', $appointment);
    echo "✅ Email sent successfully!\n\n";

    $mailer = env('MAIL_MAILER');
    if ($mailer === 'log') {
        echo "💡 Check storage/logs/laravel.log for the email content\n";
    } elseif (str_contains(env('MAIL_HOST'), 'mailtrap')) {
        echo "💡 Check your Mailtrap inbox at https://mailtrap.io\n";
    } else {
        echo "💡 Check {$patient->email} for the email\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n💡 Run: php artisan config:clear\n";
    echo "💡 Check your .env file for MAIL_* settings\n";
}

echo "\n";

