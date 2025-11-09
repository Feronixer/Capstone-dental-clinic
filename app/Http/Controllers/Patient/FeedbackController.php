<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    /**
     * Display the feedback form
     */
    public function index()
    {
        $chatbotSetting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => '',
            'quick_intents' => [],
        ]);
        $chatbotFaqs = ChatbotFaq::where('is_active', true)->orderBy('order')->get(['question', 'answer']);

        return view('patient.feedback', compact('chatbotSetting', 'chatbotFaqs'));
    }

    /**
     * Get completed appointments that haven't been rated yet
     */
    public function getCompletedAppointments()
    {
        $userId = Auth::id();

        // Debug: Get all appointments for this patient
        $allAppointments = Appointment::where('patient_id', $userId)->get();

        \Log::info('===== FEEDBACK DEBUG =====');
        \Log::info('Patient ID: ' . $userId);
        \Log::info('Total appointments: ' . $allAppointments->count());

        foreach ($allAppointments as $apt) {
            \Log::info('Appointment ID ' . $apt->id . ': ', [
                'status' => $apt->status,
                'rating' => $apt->rating,
                'rating_is_null' => is_null($apt->rating),
                'start_datetime' => $apt->start_datetime,
            ]);
        }

        $appointments = Appointment::where('patient_id', $userId)
            ->where('status', 'Completed')
            ->whereNull('rating')
            ->with('service')
            ->orderBy('end_datetime', 'desc')
            ->get();

        \Log::info('Completed unrated appointments found: ' . $appointments->count());

        $mapped = $appointments->map(function($appointment) {
            return [
                'id' => $appointment->id,
                'service_name' => $appointment->service ? $appointment->service->service_name : 'Other',
                'date' => Carbon::parse($appointment->start_datetime)->format('M j, Y'),
                'time' => Carbon::parse($appointment->start_datetime)->format('g:i A'),
            ];
        });

        return response()->json($mapped);
    }

    /**
     * Debug method to see all appointments
     */
    public function debugAppointments()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->with('service')
            ->orderBy('start_datetime', 'desc')
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'service_name' => $appointment->service ? $appointment->service->service_name : 'Other',
                    'date' => Carbon::parse($appointment->start_datetime)->format('M j, Y'),
                    'time' => Carbon::parse($appointment->start_datetime)->format('g:i A'),
                    'status' => $appointment->status,
                    'rating' => $appointment->rating,
                    'has_feedback' => $appointment->rating !== null,
                ];
            });

        return response()->json([
            'total_appointments' => $appointments->count(),
            'completed_count' => Appointment::where('patient_id', Auth::id())->where('status', 'Completed')->count(),
            'unrated_count' => Appointment::where('patient_id', Auth::id())->where('status', 'Completed')->whereNull('rating')->count(),
            'appointments' => $appointments
        ]);
    }

    /**
     * Submit feedback for an appointment
     */
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback_comment' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('patient_id', Auth::id())
            ->where('status', 'Completed')
            ->whereNull('rating')
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found or already rated.'
            ], 404);
        }

        $appointment->update([
            'rating' => $request->rating,
            'patient_feedback' => $request->feedback_comment,
            'rated_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!'
        ]);
    }

    /**
     * Get feedback history
     */
    public function getFeedbackHistory()
    {
        try {
            $feedbacks = Appointment::where('patient_id', Auth::id())
                ->where('status', 'Completed')
                ->whereNotNull('rating')
                ->with('service')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'service_name' => $appointment->service ? $appointment->service->service_name : 'Other',
                        'rating' => $appointment->rating,
                        'comment' => $appointment->patient_feedback,
                        'submitted_at' => $appointment->rated_at
                            ? Carbon::parse($appointment->rated_at)->timezone('Asia/Manila')->format('M j, Y g:i A')
                            : Carbon::parse($appointment->updated_at)->timezone('Asia/Manila')->format('M j, Y g:i A'),
                        'appointment_date' => Carbon::parse($appointment->start_datetime)->format('M j, Y'),
                    ];
                });

            return response()->json($feedbacks);
        } catch (\Exception $e) {
            \Log::error('Error loading feedback history: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load feedback history',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
