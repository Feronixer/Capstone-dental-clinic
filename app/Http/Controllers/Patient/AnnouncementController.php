<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementArchive;
use App\Models\Event;
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch the latest announcement from database
        $announcement = Announcement::first();

        // Fetch upcoming active events
        $upcomingEvents = Event::active()->upcoming()->get();

        // Fetch past events (archive)
        $pastEvents = Event::active()->past()->take(3)->get();

        // Fetch archived announcements (most recent 5 for display)
        $archivedAnnouncements = AnnouncementArchive::orderBy('archived_at', 'desc')
            ->get();

        $chatbotSetting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $chatbotFaqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question', 'answer']);

        return view('patient.announcement', compact('announcement', 'upcomingEvents', 'pastEvents', 'archivedAnnouncements', 'chatbotSetting', 'chatbotFaqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
