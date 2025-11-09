<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    use CheckStaffAccess;

    /**
     * Display chat conversations page
     */
    public function index()
    {
        $user = Auth::guard('staff')->user();
        $accessControl = $user->accessControl ?? null;
        
        return view('staff.chat', compact('accessControl'));
    }

    /**
     * Get list of conversations
     */
    public function getConversations(Request $request)
    {
        $status = $request->input('status', 'active');
        $search = $request->input('search');

        $query = ChatConversation::with(['patient.info', 'staff.info', 'messages' => function($q) {
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
        $conversation = ChatConversation::with(['patient.info'])
            ->findOrFail($conversationId);

        // Mark messages as read when staff/admin views them
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
                    'attachments' => $message->attachments,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    /**
     * Send a message from staff
     */
    public function sendMessage(Request $request, $conversationId)
    {
        // Check if staff has permission to respond to chat
        if (!$this->can('respond_to_chat')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to send messages. You can only view conversations.'
            ], 403);
        }

        try {
            // Debug: Log what we're receiving
            Log::info('Staff sendMessage request', [
                'has_files' => $request->hasFile('files'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
                'message' => $request->input('message'),
            ]);

            $request->validate([
                'message' => 'nullable|string|max:2000',
                'files.*' => 'file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt',
            ]);

            // Ensure at least message or files are provided
            if (empty($request->message) && !$request->hasFile('files')) {
                Log::warning('Staff sendMessage: No message or files provided');
                return response()->json([
                    'success' => false,
                    'message' => 'Either a message or file attachment is required.'
                ], 422);
            }

            $staffId = Auth::guard('staff')->id();
            $conversation = ChatConversation::with('patient.info')->findOrFail($conversationId);

            // Assign staff to conversation if not already assigned
            if (!$conversation->staff_id) {
                $conversation->update(['staff_id' => $staffId]);
            }

            // Handle file uploads
            $attachments = [];
            if ($request->hasFile('files')) {
                Log::info('Processing files', ['count' => count($request->file('files'))]);
                foreach ($request->file('files') as $index => $file) {
                    try {
                        Log::info("Processing file {$index}", [
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                        ]);
                        
                        // Check file size (5MB = 5120 KB)
                        if ($file->getSize() > 5120 * 1024) {
                            throw new \Exception('File size exceeds 5MB limit: ' . $file->getClientOriginalName());
                        }
                        
                        $path = $file->store('chat_attachments', 'public');
                        $url = Storage::url($path);
                        // Ensure URL is absolute
                        if (!filter_var($url, FILTER_VALIDATE_URL)) {
                            $url = asset($url);
                        }
                        $attachments[] = [
                            'name' => $file->getClientOriginalName(),
                            'path' => $path,
                            'url' => $url,
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                        ];
                    } catch (\Exception $e) {
                        Log::error("Error processing file {$index}: " . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'message' => 'Error processing file: ' . $e->getMessage()
                        ], 422);
                    }
                }
                Log::info('Files processed successfully', ['attachments_count' => count($attachments)]);
            }

            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $staffId,
                'sender_type' => 'staff',
                'message' => $request->message ?? '',
                'attachments' => !empty($attachments) ? $attachments : null,
                'is_read' => true, // Staff messages are auto-read
                'read_at' => now(),
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);

            // Get patient name for activity log
            $patientName = $conversation->patient->info 
                ? $conversation->patient->info->first_name . ' ' . $conversation->patient->info->last_name 
                : $conversation->patient->username;

            // Truncate message for description (max 100 chars)
            $messagePreview = strlen($request->message ?? '') > 100 
                ? substr($request->message ?? '', 0, 100) . '...' 
                : ($request->message ?? '');

            // Log activity - Staff replied to patient in live chat
            ActivityLog::log(
                'replied',
                'live_chat',
                'Replied to patient ' . $patientName . ' in live chat: "' . $messagePreview . '"',
                $conversation->id,
                'ChatConversation',
                null,
                [
                    'conversation_id' => $conversation->id,
                    'patient_id' => $conversation->patient_id,
                    'patient_name' => $patientName,
                    'message_id' => $message->id,
                    'message_preview' => $messagePreview,
                ]
            );

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
                    'attachments' => $message->attachments,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Staff sendMessage validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['files.*'] ?? ['Invalid file'])
            ], 422);
        } catch (\Exception $e) {
            Log::error('Staff sendMessage error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
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

