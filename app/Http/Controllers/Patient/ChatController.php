<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatbotSetting;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\User;
use App\Services\ChatCensorshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ChatController extends Controller
{
    /**
     * Get or create conversation for patient
     */
    public function getConversation()
    {
        $patientId = Auth::id();
        $patient = Auth::user();
        
        $conversation = ChatConversation::where('patient_id', $patientId)
            ->where('status', '!=', 'closed')
            ->first();

        if (!$conversation) {
            $conversation = ChatConversation::create([
                'patient_id' => $patientId,
                'status' => 'active',
            ]);
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'status' => $conversation->status,
            'chat_disabled' => (bool) ($patient?->chat_disabled ?? false),
        ]);
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages(Request $request)
    {
        $patientId = Auth::id();
        $conversationId = $request->input('conversation_id');

        $conversation = ChatConversation::where('id', $conversationId)
            ->where('patient_id', $patientId)
            ->firstOrFail();

        $messages = $conversation->messages()
            ->with('sender.info')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages->map(function ($message) {
                // Ensure attachments is properly formatted
                $attachments = $message->attachments;
                if ($attachments && !is_array($attachments)) {
                    $attachments = json_decode($attachments, true);
                }
                
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender->info 
                        ? $message->sender->info->first_name . ' ' . $message->sender->info->last_name 
                        : $message->sender->username,
                    'message' => ChatCensorshipService::censorText($message->message),
                    'attachments' => $attachments,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    /**
     * Send a message from patient
     */
    public function sendMessage(Request $request)
    {
        $patient = Auth::user();
        if ($patient && $patient->chat_disabled) {
            return response()->json([
                'success' => false,
                'message' => 'Live chat has been disabled for your account. Please contact the clinic for assistance.',
            ], 403);
        }

        // Check if chat is online
        $setting = ChatbotSetting::first();
        $isOnline = $setting ? $setting->is_online : true;
        
        if (!$isOnline) {
            return response()->json([
                'success' => false,
                'message' => 'Chat is currently offline. Please try again later.',
            ], 403);
        }

        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required|string|max:2000',
        ]);

        $patientId = Auth::id();
        $conversation = ChatConversation::where('id', $request->conversation_id)
            ->where('patient_id', $patientId)
            ->firstOrFail();

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $patientId,
            'sender_type' => 'patient',
            'message' => $request->message,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender_name' => $message->sender->info 
                    ? $message->sender->info->first_name . ' ' . $message->sender->info->last_name 
                    : $message->sender->username,
                'message' => ChatCensorshipService::censorText($message->message),
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Check if user is authenticated
     */
    public function checkAuth()
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Get unread messages count for patient
     */
    public function getUnreadCount()
    {
        $patientId = Auth::id();
        
        // Count unread messages from staff/admin
        $count = ChatMessage::whereHas('conversation', function($q) use ($patientId) {
                $q->where('patient_id', $patientId)
                  ->where('status', '!=', 'closed');
            })
            ->whereIn('sender_type', ['admin', 'staff'])
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get chat online status
     */
    public function getOnlineStatus()
    {
        $setting = ChatbotSetting::first();
        $isOnline = $setting ? $setting->is_online : true;
        $patient = Auth::user();
        $isDisabledForPatient = (bool) ($patient?->chat_disabled ?? false);
        $requested = false;
        if ($patient) {
            if (Schema::hasColumn('users', 'chat_enable_requested_at')) {
                $requested = (bool) $patient->chat_enable_requested_at;
            } else {
                $requested = Cache::get($this->requestCacheKey($patient->id), false);
            }
        }

        return response()->json([
            'is_online' => $isOnline && !$isDisabledForPatient,
            'censorship_enabled' => $setting ? (bool) $setting->censorship_enabled : false,
            'chat_disabled' => $isDisabledForPatient,
            'chat_enable_requested_at' => $patient?->chat_enable_requested_at,
            'chat_enable_requested' => $requested,
        ]);
    }

    /**
     * Allow patient to request enabling live chat when disabled.
     */
    public function requestEnable(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        $patient = Auth::user();
        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $cacheKey = $this->requestCacheKey($patient->id);

        // Prevent duplicate requests within the same disabled session
        if (Schema::hasColumn('users', 'chat_enable_requested_at')) {
            if ($patient->chat_enable_requested_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already sent a request. Please wait for the team to review it.',
                ], 429);
            }
        } elseif (Cache::get($cacheKey)) {
            return response()->json([
                'success' => false,
                'message' => 'You already sent a request. Please wait for the team to review it.',
            ], 429);
        }

        // Log the request for staff/admin to review
        ActivityLog::log(
            'request',
            'live_chat',
            'Patient requested chat re-enable: "' . $request->reason . '"',
            $patient->id,
            'User',
            $patient->id,
            [
                'patient_id' => $patient->id,
                'patient_email' => $patient->email,
                'patient_name' => $patient->info?->first_name . ' ' . $patient->info?->last_name,
                'reason' => $request->reason,
            ]
        );

        // Notify admins and staff
        $adminsAndStaff = User::query()
            ->whereIn('role_id', [1, 2])
            ->get();

        foreach ($adminsAndStaff as $recipient) {
            Notification::create([
                'user_id' => $recipient->id,
                'type' => Notification::TYPE_GENERAL,
                'title' => 'Chat access request',
                'message' => 'Patient ' . ($patient->info?->first_name . ' ' . $patient->info?->last_name ?: $patient->username) . ' requested chat re-enable.',
                'icon' => 'bi-envelope-open',
                'data' => [
                    'patient_id' => $patient->id,
                    'patient_email' => $patient->email,
                    'reason' => $request->reason,
                ],
                'is_read' => false,
            ]);
        }

        // Mark that the patient requested enable if column exists
        if (Schema::hasColumn('users', 'chat_enable_requested_at')) {
            $patient->chat_enable_requested_at = now();
            $patient->save();
        } else {
            // Fallback to cache if column is missing; cleared when re-enabled
            Cache::put($cacheKey, true, now()->addDays(7));
        }

        return response()->json([
            'success' => true,
            'message' => 'Request sent. Our team will review and enable chat if appropriate.',
        ]);
    }

    private function requestCacheKey(int $patientId): string
    {
        return 'chat_enable_request_block_' . $patientId;
    }
}

