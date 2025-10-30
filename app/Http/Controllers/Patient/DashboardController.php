<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the patient dashboard.
     */
    public function index()
    {
        // Fetch all services from database (same ones admin/staff manage)
        $services = Service::orderBy('id')->get();

        // Fetch chatbot data
        $chatbotSetting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $chatbotFaqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question','answer']);

        return view('patient.dashboard', [
            'services' => $services,
            'chatbotSetting' => $chatbotSetting,
            'chatbotFaqs' => $chatbotFaqs,
        ]);
    }
}

