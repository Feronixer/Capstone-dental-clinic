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

        return view("home", [
            'chatbotSetting' => $setting,
            'chatbotFaqs' => $faqs,
            'services' => $services,
        ]);
    }

    public function showAnnouncement() {
        // Fetch the latest announcement from database
        $announcement = Announcement::first();

        // Fetch upcoming active events
        $upcomingEvents = Event::active()->upcoming()->get();

        // Fetch past events (archive)
        $pastEvents = Event::active()->past()->take(3)->get();

        return view("announcement", compact('announcement', 'upcomingEvents', 'pastEvents'));
    }

}
