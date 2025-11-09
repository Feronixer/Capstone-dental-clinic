<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use App\Models\ChatbotSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ToothTalkController extends Controller
{
    use CheckStaffAccess;

    /**
     * Display the ToothTalk chatbot configuration page.
     */
    public function index(): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('staff.login')->withErrors(['error' => 'Please login as staff to access this page.']);
        }

        $user = Auth::guard('staff')->user();
        if ($user->role_id !== 2) {
            Auth::guard('staff')->logout();
            return redirect()->route('staff.login')->withErrors(['error' => 'Access denied. This portal is for staff members only.']);
        }

        // Check access control
        $accessCheck = $this->requireNavAccess('toothtalk');
        if ($accessCheck) {
            return $accessCheck;
        }

        $setting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $faqs = ChatbotFaq::orderBy('order')->get();
        return view('staff.toothtalk', compact('setting', 'faqs'));
    }
}
