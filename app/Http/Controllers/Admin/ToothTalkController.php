<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use App\Models\ChatbotSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToothTalkController extends Controller
{
    public function index(): View
    {
        $setting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $faqs = ChatbotFaq::orderBy('order')->get();
        return view('admin.toothtalk', compact('setting', 'faqs'));
    }

    public function saveSettings(Request $request): RedirectResponse
    {
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

    public function storeFaq(Request $request): RedirectResponse|JsonResponse
    {
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

    public function updateFaq(Request $request, int $id): RedirectResponse
    {
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

    public function destroyFaq(int $id): RedirectResponse
    {
        $faq = ChatbotFaq::findOrFail($id);
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }
}
