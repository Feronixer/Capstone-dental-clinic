<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementArchive;
use App\Models\Service;
use App\Models\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentManagementController extends Controller
{
    /**
     * Display the content management page
     */
    public function index()
    {
        $announcement = Announcement::first();
        $services = Service::orderBy('id')->get();
        $mailTemplates = MailTemplate::all()->keyBy('type');

        return view("admin.content-management", compact('announcement', 'services', 'mailTemplates'));
    }

    /**
     * Display announcement archives
     */
    public function announcementArchives()
    {
        $archives = AnnouncementArchive::with('archivedBy')
            ->orderBy('archived_at', 'desc')
            ->paginate(9);

        return view("admin.announcement-archives", compact('archives'));
    }

    /**
     * Delete an announcement archive
     */
    public function deleteArchive($id)
    {
        try {
            $archive = AnnouncementArchive::findOrFail($id);

            // Delete the image file if it exists
            if ($archive->image_path && Storage::disk('public')->exists($archive->image_path)) {
                Storage::disk('public')->delete($archive->image_path);
            }

            // Delete the archive
            $archive->delete();

            return response()->json([
                'success' => true,
                'message' => 'Archive deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting archive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting archive: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new announcement (archives the old one)
     */
    public function createNewAnnouncement(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'content' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i',
            'is_whole_day' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        // Add conditional validation for date_end
        if ($request->filled('date_end')) {
            $rules['date_end'] .= '|after_or_equal:date_start';
        }

        // Add conditional validation for time_end (only if not whole day and both times are provided)
        $isWholeDay = $request->has('is_whole_day') && $request->input('is_whole_day') == '1';
        if (!$isWholeDay && $request->filled('time_start') && $request->filled('time_end')) {
            $rules['time_end'] .= '|after:time_start';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $existingAnnouncement = Announcement::first();

            // Archive the old announcement if it exists
            if ($existingAnnouncement) {
                AnnouncementArchive::createFromAnnouncement($existingAnnouncement, auth()->id());
                // Delete the old announcement
                $existingAnnouncement->delete();
            }

            // Create new announcement
            $announcement = new Announcement();
            $announcement->title = $request->input('title');
            $announcement->subheading = $request->input('subheading') ?: null;
            $announcement->content = $request->input('content');
            $announcement->date_start = $request->input('date_start');
            $announcement->date_end = $request->filled('date_end') ? $request->input('date_end') : null;
            $announcement->is_whole_day = $request->has('is_whole_day') && $request->input('is_whole_day') == '1';
            
            // Handle time fields
            if ($announcement->is_whole_day) {
                $announcement->time_start = null;
                $announcement->time_end = null;
            } else {
                $announcement->time_start = $request->filled('time_start') ? $request->input('time_start') : null;
                $announcement->time_end = $request->filled('time_end') ? $request->input('time_end') : null;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('announcements', 'public');
                $announcement->image_path = $path;
            }

            $announcement->save();

            return response()->json([
                'success' => true,
                'message' => 'New announcement created successfully. Old announcement has been archived.',
                'data' => $announcement
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating announcement: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update announcement (does not archive)
     */
    public function updateAnnouncement(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'content' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i',
            'is_whole_day' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        // Add conditional validation for date_end
        if ($request->filled('date_end')) {
            $rules['date_end'] .= '|after_or_equal:date_start';
        }

        // Add conditional validation for time_end (only if not whole day and both times are provided)
        $isWholeDay = $request->has('is_whole_day') && $request->input('is_whole_day') == '1';
        if (!$isWholeDay && $request->filled('time_start') && $request->filled('time_end')) {
            $rules['time_end'] .= '|after:time_start';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $announcement = Announcement::first();

            if (!$announcement) {
                // If no announcement exists, create a new one
                $announcement = new Announcement();
            }

            $announcement->title = $request->input('title');
            $announcement->subheading = $request->input('subheading') ?: null;
            $announcement->content = $request->input('content');
            $announcement->date_start = $request->input('date_start');
            $announcement->date_end = $request->filled('date_end') ? $request->input('date_end') : null;
            $announcement->is_whole_day = $request->has('is_whole_day') && $request->input('is_whole_day') == '1';
            
            // Handle time fields
            if ($announcement->is_whole_day) {
                $announcement->time_start = null;
                $announcement->time_end = null;
            } else {
                $announcement->time_start = $request->filled('time_start') ? $request->input('time_start') : null;
                $announcement->time_end = $request->filled('time_end') ? $request->input('time_end') : null;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($announcement->image_path && Storage::disk('public')->exists($announcement->image_path)) {
                    Storage::disk('public')->delete($announcement->image_path);
                }

                $path = $request->file('image')->store('announcements', 'public');
                $announcement->image_path = $path;
            }

            $announcement->save();

            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully',
                'data' => $announcement
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating announcement: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update ticker notification
     */
    public function updateTicker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticker_text' => 'required|string',
            'show_ticker' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $announcement = Announcement::first();

        if (!$announcement) {
            $announcement = new Announcement();
        }

        // Just update ticker, don't archive
        $announcement->ticker_text = $request->input('ticker_text');
        $announcement->show_ticker = $request->input('show_ticker', true);
        $announcement->save();

        return response()->json([
            'success' => true,
            'message' => 'Ticker notification updated successfully',
            'data' => $announcement
        ]);
    }

    /**
     * Store a new service
     */
    public function storeService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'default_duration_minutes' => 'required|integer|min:1',
            'icon_class' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $service = Service::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => $service
        ]);
    }

    /**
     * Update a service
     */
    public function updateService(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'default_duration_minutes' => 'required|integer|min:1',
            'icon_upload' => 'nullable|image|mimes:jpeg,png,webp,gif,svg,ico|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $service = Service::findOrFail($id);
        $service->update($request->except('icon_upload'));

        if ($request->hasFile('icon_upload')) {
            $path = $request->file('icon_upload')->store('service-icons', 'public');
            $service->icon_class = 'uploaded:' . $path;
            $service->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => $service
        ]);
    }

    /**
     * Delete a service
     */
    public function destroyService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }

    /**
     * Update mail template
     */
    public function updateMailTemplate(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $template = MailTemplate::updateOrCreate(
            ['type' => $type],
            [
                'subject' => $request->input('subject'),
                'content' => $request->input('content')
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Mail template updated successfully',
            'data' => $template
        ]);
    }

    /**
     * Get patients with appointments
     */
    public function getPatientsWithAppointments()
    {
        $patients = \App\Models\User::whereHas('appointments')
            ->with('info')
            ->withCount('appointments')
            ->get()
            ->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                    'appointments_count' => $patient->appointments_count
                ];
            });

        return response()->json([
            'success' => true,
            'patients' => $patients
        ]);
    }

    /**
     * Get appointments for a specific patient
     */
    public function getPatientAppointments($patientId)
    {
        $appointments = \App\Models\Appointment::where('patient_id', $patientId)
            ->with(['patient.info', 'service'])
            ->orderBy('start_datetime', 'desc')
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'start_datetime' => $appointment->start_datetime->format('Y-m-d H:i:s'),
                    'end_datetime' => $appointment->end_datetime->format('Y-m-d H:i:s'),
                    'status' => $appointment->status,
                    'rescheduled_at' => $appointment->rescheduled_at ? $appointment->rescheduled_at->format('Y-m-d H:i:s') : null,
                    'original_datetime' => $appointment->original_datetime ? $appointment->original_datetime->format('Y-m-d H:i:s') : null,
                    'patient' => [
                        'id' => $appointment->patient->id,
                        'name' => $appointment->patient->name,
                        'email' => $appointment->patient->email,
                        'info' => $appointment->patient->info
                    ],
                    'service' => $appointment->service ? [
                        'id' => $appointment->service->id,
                        'service_name' => $appointment->service->service_name
                    ] : null
                ];
            });

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ]);
    }

    /**
     * Send email to patient
     */
    public function sendPatientEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'email_type' => 'required|in:initial_confirmation,reminder,cancellation,rescheduling,follow_up'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $appointment = \App\Models\Appointment::with(['patient.info', 'service'])
                ->findOrFail($request->appointment_id);

            // Validate that rescheduling email can only be sent to rescheduled appointments
            if ($request->email_type === 'rescheduling' && !$appointment->rescheduled_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment has not been rescheduled. You can only send a rescheduling notice to appointments that have been rescheduled.',
                    'errors' => ['email_type' => ['Appointment must be rescheduled first']]
                ], 422);
            }

            // Send email using MailService
            \App\Services\MailService::sendAppointmentEmail($request->email_type, $appointment);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully',
                'data' => [
                    'patient_email' => $appointment->patient->email,
                    'email_type' => $request->email_type
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending patient email: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get patients by situation/filter
     */
    public function getPatientsBySituation(Request $request)
    {
        $situation = $request->input('situation');

        $query = \App\Models\Appointment::with(['patient.info', 'service']);

        switch ($situation) {
            case 'today':
                // Appointments scheduled for today
                $query->whereDate('start_datetime', today())
                      ->whereIn('status', ['scheduled', 'confirmed']);
                break;

            case 'tomorrow':
                // Appointments scheduled for tomorrow
                $query->whereDate('start_datetime', today()->addDay())
                      ->whereIn('status', ['scheduled', 'confirmed']);
                break;

            case 'this_week':
                // Appointments this week
                $query->whereBetween('start_datetime', [today(), today()->endOfWeek()])
                      ->whereIn('status', ['scheduled', 'confirmed']);
                break;

            case 'rescheduled':
                // Appointments that have been rescheduled
                $query->whereNotNull('rescheduled_at')
                      ->whereIn('status', ['scheduled', 'confirmed']);
                break;

            case 'completed':
                // Recently completed appointments (last 7 days)
                $query->where('status', 'completed')
                      ->where('start_datetime', '>=', today()->subDays(7));
                break;

            case 'pending':
                // Pending/unconfirmed appointments
                $query->where('status', 'scheduled')
                      ->where('start_datetime', '>=', today());
                break;

            case 'upcoming':
                // All upcoming appointments
                $query->where('start_datetime', '>=', today())
                      ->whereIn('status', ['scheduled', 'confirmed']);
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid situation filter'
                ], 400);
        }

        $appointments = $query->orderBy('start_datetime', 'asc')->get();

        // Group by patient and get their appointments
        $patientsWithAppointments = [];
        foreach ($appointments as $appointment) {
            $patientId = $appointment->patient_id;

            if (!isset($patientsWithAppointments[$patientId])) {
                $patientsWithAppointments[$patientId] = [
                    'patient_id' => $patientId,
                    'patient_name' => $appointment->patient->name,
                    'patient_email' => $appointment->patient->email,
                    'patient_info' => $appointment->patient->info,
                    'appointments' => []
                ];
            }

            $patientsWithAppointments[$patientId]['appointments'][] = [
                'id' => $appointment->id,
                'start_datetime' => $appointment->start_datetime->format('Y-m-d H:i:s'),
                'service' => $appointment->service ? $appointment->service->service_name : 'No service',
                'status' => $appointment->status,
                'rescheduled' => $appointment->rescheduled_at ? true : false
            ];
        }

        return response()->json([
            'success' => true,
            'situation' => $situation,
            'count' => count($patientsWithAppointments),
            'patients' => array_values($patientsWithAppointments)
        ]);
    }

    /**
     * Send bulk email to multiple patients
     */
    public function sendBulkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_ids' => 'required|array',
            'appointment_ids.*' => 'exists:appointments,id',
            'email_type' => 'required|in:initial_confirmation,reminder,cancellation,rescheduling,follow_up'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $appointments = \App\Models\Appointment::with(['patient.info', 'service'])
                ->whereIn('id', $request->appointment_ids)
                ->get();

            $successCount = 0;
            $failedCount = 0;
            $errors = [];

            foreach ($appointments as $appointment) {
                try {
                    // Validate rescheduling emails
                    if ($request->email_type === 'rescheduling' && !$appointment->rescheduled_at) {
                        $failedCount++;
                        $errors[] = "Appointment #{$appointment->id} has not been rescheduled";
                        continue;
                    }

                    // Send email using MailService
                    \App\Services\MailService::sendAppointmentEmail($request->email_type, $appointment);
                    $successCount++;

                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Failed to send to {$appointment->patient->email}: " . $e->getMessage();
                    \Log::error("Bulk email error for appointment {$appointment->id}: " . $e->getMessage());
                }
            }

            $message = "Sent {$successCount} email(s) successfully";
            if ($failedCount > 0) {
                $message .= ", {$failedCount} failed";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'total' => count($appointments),
                    'success_count' => $successCount,
                    'failed_count' => $failedCount,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error sending bulk emails: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send bulk emails: ' . $e->getMessage()
            ], 500);
        }
    }
}
