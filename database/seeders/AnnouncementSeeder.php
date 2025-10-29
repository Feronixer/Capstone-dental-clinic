<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Announcement::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Welcome to JValera Dental Clinic',
                'content' => 'We are committed to providing excellent dental care services. Book your appointment today!',
                'is_active' => true,
                'ticker_text' => 'The clinic will be closed on April 27, 2025 for regular maintenance. Emergency services will be available.',
                'show_ticker' => true
            ]
        );
    }
}
