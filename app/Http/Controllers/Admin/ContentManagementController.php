<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Announcement;
use App\Models\MailSetting;

class ContentManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::all();
        $services = Service::all();
        $mailSettings = MailSetting::all();

        return view("admin.content-management", compact('announcements', 'services', 'mailSettings'));
    }

    /**
     * Add Announcement
     */
    public function addAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'body']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->back()->with('success', 'Announcement added!');
    }

    /**
     * Update Announcement
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $announcement = Announcement::findOrFail($id);
        $announcement->title = $request->title;
        $announcement->body = $request->body;

        if ($request->hasFile('image')) {
            $announcement->image = $request->file('image')->store('announcements', 'public');
        }

        $announcement->save();

        return redirect()->back()->with('success', 'Announcement updated!');
    }

    public function indexServices(Request $request)
{
    $services = Service::orderBy('id', 'asc')->get();

    if ($request->ajax()) {
        return response()->json([
            'html' => view('admin.partials.services-table', compact('services'))->render(),
            'pagination_html' => view('admin.partials.pagination-services', compact('services'))->render(),
        ]);
    }

    return view("admin.services.index", compact('services'));
}

/**
 * Store a newly created service.
 */
public function storeService(Request $request)
{
    $request->validate([
        'icon_fa' => 'required|string|max:255',
        'service' => 'required|string|max:255',
        'price' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    Service::create($request->only(['icon_fa', 'service', 'price', 'description']));

    return redirect()
        ->back()
        ->with('modal_success', 'Service added successfully!');
}

/**
 * Display a single service.
 */
public function showService($id)
{
    return response()->json(Service::findOrFail($id));
}

/**
 * Update the specified service.
 */
public function updateService(Request $request, $id)
{
    $request->validate([
        'icon_fa' => 'required|string|max:255',
        'service' => 'required|string|max:255',
        'price' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $service = Service::findOrFail($id);
    $service->update($request->only(['icon_fa', 'service', 'price', 'description']));

    return redirect()->back()->with('success', 'Service updated successfully.');
}

/**
 * Remove the specified service.
 */
public function destroyService($id)
{
    $service = Service::findOrFail($id);
    $service->delete();

    return redirect()->back()->with('modal_success', 'Service deleted successfully!');
}

    /**
     * Update Mail Settings
     */
    public function updateMailSetting(Request $request, $id)
    {
        $request->validate([
            'structure' => 'required|string',
            'preview' => 'required|string',
        ]);

        $mailSetting = MailSetting::findOrFail($id);
        $mailSetting->update($request->only(['structure', 'preview']));

        return redirect()->back()->with('success', 'Mail setting updated!');
    }
}
