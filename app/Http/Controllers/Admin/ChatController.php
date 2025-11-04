<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display chat conversations page
     */
    public function index()
    {
        return view('admin.chat');
    }

    /**
     * Get list of conversations
     */
    public function getConversations(Request $request)
    {
        $status = $request->input('status', 'active');
        $search = $request->input('search');

        $query = ChatConversation::with(['patient.info', 'staff.info', 'admin.info', 'messages' => function($q) {
            $q->latest()->limit(1);
        }]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('info', function($infoQ) use ($search) {
                      $infoQ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $conversations = $query->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return response()->json([
            'conversations' => $conversations->map(function ($conversation) {
                $lastMessage = $conversation->messages->first();
                return [
                    'id' => $conversation->id,
                    'patient_id' => $conversation->patient_id,
                    'patient_name' => $conversation->patient->info 
                        ? $conversation->patient->info->first_name . ' ' . $conversation->patient->info->last_name 
                        : $conversation->patient->username,
                    'patient_email' => $conversation->patient->email,
                    'staff_name' => $conversation->staff_id && $conversation->staff 
                        ? ($conversation->staff->info 
                            ? $conversation->staff->info->first_name . ' ' . $conversation->staff->info->last_name 
                            : $conversation->staff->username)
                        : null,
                    'status' => $conversation->status,
                    'unread_count' => $conversation->unreadMessagesCount(),
                    'last_message' => $lastMessage ? $lastMessage->message : null,
                    'last_message_at' => $conversation->last_message_at 
                        ? $conversation->last_message_at->format('Y-m-d H:i:s') 
                        : null,
                    'created_at' => $conversation->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'pagination' => [
                'current_page' => $conversations->currentPage(),
                'last_page' => $conversations->lastPage(),
                'per_page' => $conversations->perPage(),
                'total' => $conversations->total(),
            ],
        ]);
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages($conversationId)
    {
        $conversation = ChatConversation::with(['patient.info', 'staff.info'])
            ->findOrFail($conversationId);

        // Mark messages as read when admin views them
        $conversation->markAsRead();

        $messages = $conversation->messages()
            ->with('sender.info')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'patient_id' => $conversation->patient_id,
                'patient_name' => $conversation->patient->info 
                    ? $conversation->patient->info->first_name . ' ' . $conversation->patient->info->last_name 
                    : $conversation->patient->username,
                'patient_email' => $conversation->patient->email,
                'staff_id' => $conversation->staff_id,
                'status' => $conversation->status,
            ],
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender->info 
                        ? $message->sender->info->first_name . ' ' . $message->sender->info->last_name 
                        : $message->sender->username,
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    /**
     * Send a message from admin
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $adminId = Auth::id();
        $conversation = ChatConversation::findOrFail($conversationId);

        // Assign admin to conversation if not already assigned
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => $adminId]);
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $adminId,
            'sender_type' => 'admin',
            'message' => $request->message,
            'is_read' => true, // Admin messages are auto-read
            'read_at' => now(),
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
     * Update conversation status
     */
    public function updateStatus(Request $request, $conversationId)
    {
        $request->validate([
            'status' => 'required|in:active,resolved,closed',
        ]);

        $conversation = ChatConversation::findOrFail($conversationId);
        $conversation->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'status' => $conversation->status,
        ]);
    }

    /**
     * Get unread conversations count
     */
    public function getUnreadCount()
    {
        $count = ChatConversation::where('status', 'active')
            ->whereHas('messages', function($q) {
                $q->where('sender_type', 'patient')
                  ->where('is_read', false);
            })
            ->count();

        return response()->json(['count' => $count]);
    }
}

