<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PatientRecord as PatientRecordModel;
use App\Models\PatientHistory;
use App\Models\ProgressNote;
use App\Models\ChatbotSetting;
use App\Models\ChatbotFaq;

class PatientRecord extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Get all records for the authenticated patient with histories and progress notes
        $records = PatientRecordModel::where('user_id', $userId)
            ->where('sent_to_patient', true) // Only show records that have been sent
            ->with([
                'user.info',
                'appointment.service',
                'patientHistories' => function($query) {
                    $query->where('sent_to_patient', true) // Only show histories that have been sent
                          ->orderBy('visit_date', 'desc');
                },
                'progressNotes' => function($query) {
                    $query->orderBy('note_date', 'desc');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $chatbotSetting = ChatbotSetting::first() ?? ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => 'Hi! I\'m the ToothTalk Assistant. How can I help you today?',
            'quick_intents' => [
                ['label' => 'Clinic Hours', 'value' => 'What are your clinic hours?'],
                ['label' => 'Book Appointment', 'value' => 'How do I book an appointment?'],
                ['label' => 'Services', 'value' => 'What dental services do you offer?'],
            ],
        ]);

        $chatbotFaqs = ChatbotFaq::where('is_active', true)
            ->orderBy('order')
            ->get(['question', 'answer']);

        return view("patient.record", compact('records', 'chatbotSetting', 'chatbotFaqs'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $userId = Auth::id();

            // Check if access is verified
            $verified = session('record_access_verified', false);
            $verifiedAt = session('record_access_verified_at');
            
            if (!$verified || !$verifiedAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access not verified. Please verify your password first.',
                    'requires_auth' => true
                ], 403);
            }

            // Ensure $verifiedAt is a Carbon instance
            if (!$verifiedAt instanceof \Carbon\Carbon) {
                try {
                    $verifiedAt = \Carbon\Carbon::parse($verifiedAt);
                } catch (\Exception $e) {
                    // If parsing fails, clear session and return not verified
                    session()->forget(['record_access_verified', 'record_access_verified_at']);
                    return response()->json([
                        'success' => false,
                        'message' => 'Access not verified. Please verify your password first.',
                        'requires_auth' => true
                    ], 403);
                }
            }
            
            // Check if verification is still valid (30 minutes)
            $expiresAt = $verifiedAt->copy()->addMinutes(30);
            if (now()->gt($expiresAt)) {
                // Expired, clear session
                session()->forget(['record_access_verified', 'record_access_verified_at']);
                return response()->json([
                    'success' => false,
                    'message' => 'Access expired. Please verify your password again.',
                    'requires_auth' => true
                ], 403);
            }

            // Get the record and ensure it belongs to the authenticated patient
            $record = PatientRecordModel::where('id', $id)
                ->where('user_id', $userId)
                ->where('sent_to_patient', true)
                ->with([
                    'user.info',
                    'appointment.service',
                    'patientHistories' => function($query) {
                        $query->where('sent_to_patient', true)
                              ->orderBy('visit_date', 'desc');
                    },
                    'progressNotes' => function($query) {
                        $query->with('createdBy.info')->orderBy('note_date', 'asc');
                    }
                ])
                ->firstOrFail();

            // Ensure progressNotes are loaded and accessible
            $record->load('progressNotes');
            
            // Convert to array to ensure relationships are included
            $recordArray = $record->toArray();
            
            // Explicitly include progressNotes if they exist
            if ($record->progressNotes) {
                $recordArray['progressNotes'] = $record->progressNotes->toArray();
            }
            
            // Log for debugging
            \Log::info('Loading patient record', [
                'record_id' => $record->id,
                'progress_notes_count' => $record->progressNotes->count(),
                'progress_notes_loaded' => $record->progressNotes->count() > 0
            ]);

            return response()->json([
                'success' => true,
                'record' => $recordArray,
                'progress_notes_count' => $record->progressNotes->count() // Debug info
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found or access denied.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error loading patient record: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error loading record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download/Print the specified record
     */
    public function download($id)
    {
        $userId = Auth::id();

        // Get the record and ensure it belongs to the authenticated patient
        $record = PatientRecordModel::where('id', $id)
            ->where('user_id', $userId)
            ->with(['user.info', 'appointment.service'])
            ->firstOrFail();

        // Get creator information from ActivityLog
        $creator = null;
        $creatorRole = null;
        $activityLog = \App\Models\ActivityLog::where(function($query) {
                $query->where('record_type', 'PatientRecord')
                      ->orWhere('module', 'patient_record');
            })
            ->where('record_id', $record->id)
            ->where('action', 'created')
            ->with(['user.info', 'user.role'])
            ->orderBy('created_at', 'asc')
            ->first();
        
        if ($activityLog && $activityLog->user) {
            $creator = $activityLog->user;
            $creatorRole = $creator->role ? $creator->role->role : 'Staff';
        }

        // Return a print-friendly view
        return view('patient.pdf.record', compact('record', 'creator', 'creatorRole'));
    }

    /**
     * Get all records for the authenticated patient
     */
    public function getRecords()
    {
        $userId = Auth::id();

        $records = PatientRecordModel::where('user_id', $userId)
            ->where('sent_to_patient', true)
            ->with([
                'user.info',
                'appointment.service',
                'patientHistories' => function($query) {
                    $query->where('sent_to_patient', true)
                          ->orderBy('visit_date', 'desc');
                },
                'progressNotes' => function($query) {
                    $query->orderBy('note_date', 'desc');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }

    /**
     * Get a specific patient history
     */
    public function showHistory($id)
    {
        $userId = Auth::id();

        // Get the history and ensure it belongs to a record of the authenticated patient
        $history = PatientHistory::where('id', $id)
            ->where('sent_to_patient', true)
            ->whereHas('patientRecord', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('sent_to_patient', true);
            })
            ->with('patientRecord.user.info')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Download/Print a specific patient history
     */
    public function downloadHistory($id)
    {
        $userId = Auth::id();

        // Get the history and ensure it belongs to a record of the authenticated patient
        $history = PatientHistory::where('id', $id)
            ->where('sent_to_patient', true)
            ->whereHas('patientRecord', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('sent_to_patient', true);
            })
            ->with([
                'patientRecord.user.info', 
                'patientRecord.appointment.service',
                'createdBy.info'
            ])
            ->firstOrFail();

        // Get creator information from the model relationship
        $creator = $history->createdBy;
        $creatorRole = $history->created_by_role ?? 'Staff';

        return view('patient.pdf.history', compact('history', 'creator', 'creatorRole'));
    }

    /**
     * Get a specific progress note
     */
    public function showProgressNote($id)
    {
        $userId = Auth::id();

        // Get the note and ensure it belongs to a record of the authenticated patient
        $note = ProgressNote::where('id', $id)
            ->whereHas('patientRecord', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('sent_to_patient', true);
            })
            ->with('patientRecord.user.info')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'note' => $note
        ]);
    }

    /**
     * Download a specific progress note as PDF
     */
    public function downloadProgressNote($id)
    {
        $userId = Auth::id();

        // Get the note and ensure it belongs to a record of the authenticated patient
        $note = ProgressNote::where('id', $id)
            ->whereHas('patientRecord', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('sent_to_patient', true);
            })
            ->with('patientRecord.user.info')
            ->firstOrFail();

        return view('patient.pdf.progress-note', compact('note'));
    }

    /**
     * Download all progress notes for a patient record as consolidated PDF
     */
    public function downloadAllProgressNotes($recordId)
    {
        $userId = Auth::id();

        // Get the record and ensure it belongs to the authenticated patient
        $record = PatientRecordModel::where('id', $recordId)
            ->where('user_id', $userId)
            ->where('sent_to_patient', true)
            ->with([
                'user.info',
                'progressNotes' => function($query) {
                    $query->with('createdBy.info')->orderBy('note_date', 'asc');
                }
            ])
            ->firstOrFail();

        return view('patient.pdf.progress-notes-all', compact('record'));
    }

    /**
     * Verify password for record access
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        // Verify password
        if (Hash::check($request->password, $user->password)) {
            // Store verification in session (valid for 1 minute or until logout)
            // Use Carbon instance to ensure proper date handling
            $now = \Carbon\Carbon::now();
            session(['record_access_verified' => true]);
            session(['record_access_verified_at' => $now]);
            session()->save(); // Explicitly save session
            
            return response()->json([
                'success' => true,
                'message' => 'Password verified successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Incorrect password. Please try again.'
        ], 422);
    }

    /**
     * Check if record access is verified
     */
    public function checkAccess()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'verified' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        // Check if verification exists and is still valid (30 minutes)
        $verified = session('record_access_verified', false);
        $verifiedAt = session('record_access_verified_at');
        
        if ($verified && $verifiedAt) {
            // Ensure $verifiedAt is a Carbon instance
            if (!$verifiedAt instanceof \Carbon\Carbon) {
                try {
                    $verifiedAt = \Carbon\Carbon::parse($verifiedAt);
                } catch (\Exception $e) {
                    // If parsing fails, clear session and return not verified
                    session()->forget(['record_access_verified', 'record_access_verified_at']);
                    return response()->json([
                        'success' => true,
                        'verified' => false,
                        'message' => 'Access not verified.'
                    ]);
                }
            }
            
            // Use copy() to avoid mutating the original Carbon instance
            $expiresAt = $verifiedAt->copy()->addMinutes(30);
            if (now()->lt($expiresAt)) {
                return response()->json([
                    'success' => true,
                    'verified' => true,
                    'message' => 'Access verified.'
                ]);
            } else {
                // Expired, clear session
                session()->forget(['record_access_verified', 'record_access_verified_at']);
            }
        }

        return response()->json([
            'success' => true,
            'verified' => false,
            'message' => 'Access not verified.'
        ]);
    }
}
