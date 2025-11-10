<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use App\Models\ChatbotSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

    /**
     * Save chatbot settings.
     */
    public function saveSettings(Request $request): RedirectResponse
    {
        // Check access control
        $accessCheck = $this->requireNavAccess('toothtalk');
        if ($accessCheck) {
            return $accessCheck;
        }

        $data = $request->validate([
            'enabled' => ['nullable'],
            'welcome_message' => ['required', 'string', 'max:255'],
            'quick_intents' => ['nullable', 'array'],
            'quick_intents.*.label' => ['required_with:quick_intents', 'string', 'max:60'],
            'quick_intents.*.value' => ['required_with:quick_intents', 'string', 'max:255'],
        ]);

        $setting = ChatbotSetting::first();
        if (!$setting) { $setting = new ChatbotSetting(); }
        $setting->enabled = (bool) $request->boolean('enabled');
        $setting->welcome_message = $data['welcome_message'];
        $setting->quick_intents = $data['quick_intents'] ?? [];
        $setting->save();

        return back()->with('success', 'Chatbot settings saved.');
    }

    /**
     * Store a new FAQ.
     */
    public function storeFaq(Request $request): RedirectResponse|JsonResponse
    {
        // Check access control
        $accessCheck = $this->requireNavAccess('toothtalk');
        if ($accessCheck) {
            return $accessCheck;
        }

        $data = $request->validate([
            'question' => ['required','string','max:255'],
            'answer' => ['required','string'],
            'is_active' => ['nullable'],
        ]);
        $nextOrder = (int) ChatbotFaq::max('order') + 1;
        $faq = ChatbotFaq::create([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'is_active' => $request->boolean('is_active'),
            'order' => $nextOrder,
        ]);
        
        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ added.',
                'faq' => $faq
            ]);
        }
        
        return back()->with('success', 'FAQ added.');
    }

    /**
     * Update an existing FAQ.
     */
    public function updateFaq(Request $request, int $id): RedirectResponse
    {
        // Check access control
        $accessCheck = $this->requireNavAccess('toothtalk');
        if ($accessCheck) {
            return $accessCheck;
        }

        $faq = ChatbotFaq::findOrFail($id);
        $data = $request->validate([
            'question' => ['required','string','max:255'],
            'answer' => ['required','string'],
            'is_active' => ['nullable'],
        ]);
        $faq->update([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'is_active' => $request->boolean('is_active'),
        ]);
        return back()->with('success', 'FAQ updated.');
    }

    /**
     * Delete an FAQ.
     */
    public function destroyFaq(int $id): RedirectResponse
    {
        // Check access control
        $accessCheck = $this->requireNavAccess('toothtalk');
        if ($accessCheck) {
            return $accessCheck;
        }

        $faq = ChatbotFaq::findOrFail($id);
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }
}
