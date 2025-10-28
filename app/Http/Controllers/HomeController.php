<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;

class HomeController extends Controller
{
    public function showHomePage() {
        $setting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $faqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question','answer']);
        return view("home", [
            'chatbotSetting' => $setting,
            'chatbotFaqs' => $faqs,
        ]);
    }

}
