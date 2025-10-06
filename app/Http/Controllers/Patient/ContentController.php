<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Service;

class ContentController extends Controller
{
    // Announcements list
    public function announcements()
    {
        // latest first; paginate for performance
        $announcements = Announcement::orderByDesc('created_at')->paginate(10);
        return view('patient.announcements.index', compact('announcements'));
    }

    // Announcement details
    public function announcementShow(Announcement $announcement)
    {
        return view('patient.announcements.show', compact('announcement'));
    }

    // Services list
    public function services()
    {
        $services = Service::orderBy('id', 'asc')->paginate(12);
        return view('patient.services.index', compact('services'));
    }

    // Service details
    public function serviceShow(Service $service)
    {
        return view('patient.services.show', compact('service'));
    }
}
