<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Get or create conversation for patient
     */
    public function getConversation()
    {
        $patientId = Auth::id();
        
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
                    'message' => $message->message,
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
                'message' => $message->message,
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
}

