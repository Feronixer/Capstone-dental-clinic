<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
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

        return view("staff.content-management", compact('announcement', 'services', 'mailTemplates'));
    }

    /**
     * Update announcement
     */
    public function updateAnnouncement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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

        $announcement->title = $request->input('title');
        $announcement->content = $request->input('content');

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
            'default_duration_minutes' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $service = Service::findOrFail($id);
        $service->update($request->all());

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
        // Staff CANNOT delete services - restricted
        \Log::warning('Staff attempted to delete service', [
            'staff_user' => auth()->user()->username,
            'service_id' => $id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to delete services. Please contact an administrator.'
        ], 403);
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
}
