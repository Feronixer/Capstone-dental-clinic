<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;
use App\Models\Service;
use App\Models\Announcement;
use App\Models\Event;

class HomeController extends Controller
{
    public function showHomePage() {
        $setting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $faqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question','answer']);
        $services = Service::orderBy('id')->get();

        return response()
            ->view("home", [
                'chatbotSetting' => $setting,
                'chatbotFaqs' => $faqs,
                'services' => $services,
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function showAnnouncement() {
        // Fetch the latest announcement from database
        $announcement = Announcement::first();

        // Fetch upcoming active events
        $upcomingEvents = Event::active()->upcoming()->get();

        // Fetch past events (archive)
        $pastEvents = Event::active()->past()->take(3)->get();

        // Fetch chatbot settings and FAQs
        $chatbotSetting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $chatbotFaqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question', 'answer']);

        return view("announcement", compact('announcement', 'upcomingEvents', 'pastEvents', 'chatbotSetting', 'chatbotFaqs'));
    }

}
