<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatCensoredWord;
use App\Models\ChatMessage;
use App\Models\ChatbotSetting;
use App\Services\ChatCensorshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $search = $request->input('search');

        $query = ChatConversation::with(['patient.info', 'staff.info', 'admin.info', 'messages.sender.info']);

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

        $currentAdminId = Auth::guard('admin')->id();
        
        return response()->json([
            'conversations' => $conversations->map(function ($conversation) use ($currentAdminId) {
                // Get last message with sender relationship loaded
                // Query directly from ChatMessage to avoid relationship ordering issues
                $lastMessage = ChatMessage::where('conversation_id', $conversation->id)
                    ->with('sender.info')
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->first();
                $lastSenderType = null;
                $lastSenderName = null;
                $lastSenderId = null;
                
                if ($lastMessage) {
                    $lastSenderType = $lastMessage->sender_type;
                    $lastSenderId = $lastMessage->sender_id;
                    
                    if ($lastMessage->sender_type === 'patient') {
                        $lastSenderName = 'Patient';
                    } elseif ($lastMessage->sender_type === 'staff') {
                        if ($lastMessage->sender) {
                            $lastSenderName = $lastMessage->sender->info 
                                ? $lastMessage->sender->info->first_name . ' ' . $lastMessage->sender->info->last_name 
                                : $lastMessage->sender->username;
                        } else {
                            $lastSenderName = 'Staff';
                        }
                    } elseif ($lastMessage->sender_type === 'admin') {
                        if ($lastMessage->sender_id == $currentAdminId) {
                            $lastSenderName = 'You';
                        } else {
                            if ($lastMessage->sender) {
                                $lastSenderName = $lastMessage->sender->info 
                                    ? $lastMessage->sender->info->first_name . ' ' . $lastMessage->sender->info->last_name 
                                    : $lastMessage->sender->username;
                            } else {
                                $lastSenderName = 'Admin';
                            }
                        }
                    }
                }
                
                // Check if last message has attachments
                $hasAttachments = $lastMessage && $lastMessage->attachments && !empty($lastMessage->attachments);
                
                return [
                    'id' => $conversation->id,
                    'patient_id' => $conversation->patient_id,
                    'patient_name' => $conversation->patient->info 
                        ? $conversation->patient->info->first_name . ' ' . $conversation->patient->info->last_name 
                        : $conversation->patient->username,
                    'patient_email' => $conversation->patient->email,
                    'status' => $conversation->status,
                    'unread_count' => $conversation->unreadMessagesCount(),
                    'last_message_at' => $conversation->last_message_at 
                        ? $conversation->last_message_at->format('Y-m-d H:i:s') 
                        : null,
                    'created_at' => $conversation->created_at->format('Y-m-d H:i:s'),
                    'last_sender_type' => $lastSenderType,
                    'last_sender_name' => $lastSenderName,
                    'last_sender_id' => $lastSenderId,
                    'last_message_text' => $lastMessage
                        ? ChatCensorshipService::censorText($lastMessage->message ?? '')
                        : null,
                    'last_message_has_attachments' => $hasAttachments,
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
                    'message' => ChatCensorshipService::censorText($message->message),
                    'attachments' => $message->attachments,
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
        try {
            // Debug: Log what we're receiving
            Log::info('Admin sendMessage request', [
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
                Log::warning('Admin sendMessage: No message or files provided');
                return response()->json([
                    'success' => false,
                    'message' => 'Either a message or file attachment is required.'
                ], 422);
            }

            $adminId = Auth::id();
            $conversation = ChatConversation::findOrFail($conversationId);

            // Assign admin to conversation if not already assigned
            if (!$conversation->admin_id) {
                $conversation->update(['admin_id' => $adminId]);
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
                'sender_id' => $adminId,
                'sender_type' => 'admin',
                'message' => $request->message ?? '',
                'attachments' => !empty($attachments) ? $attachments : null,
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
                    'message' => ChatCensorshipService::censorText($message->message),
                    'attachments' => $message->attachments,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Admin sendMessage validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['files.*'] ?? ['Invalid file'])
            ], 422);
        } catch (\Exception $e) {
            Log::error('Admin sendMessage error', [
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

    /**
     * Delete a conversation (Admin only)
     */
    public function deleteConversation(Request $request, $conversationId)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $admin = Auth::guard('admin')->user();

        // Verify admin is authenticated
        if (!$admin || $admin->role_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        // Verify password
        if (!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.',
            ], 422);
        }

        $conversation = ChatConversation::findOrFail($conversationId);
        
        // Delete all messages first (cascade should handle this, but being explicit)
        $conversation->messages()->delete();
        
        // Delete the conversation
        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted successfully',
        ]);
    }

    /**
     * Toggle chat online status
     */
    public function toggleOnlineStatus(Request $request)
    {
        try {
            $request->validate([
                'is_online' => 'required|boolean',
            ]);

            $setting = ChatbotSetting::first();
            if (!$setting) {
                $setting = ChatbotSetting::create([
                    'enabled' => true,
                    'is_online' => true,
                    'welcome_message' => '',
                    'quick_intents' => [],
                ]);
            }

            $setting->is_online = $request->boolean('is_online');
            $setting->save();

            return response()->json([
                'success' => true,
                'is_online' => $setting->is_online,
                'message' => $setting->is_online ? 'Chat is now online' : 'Chat is now offline',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error toggling chat online status: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update chat status. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle bad-word censorship on/off.
     */
    public function toggleCensorship(Request $request)
    {
        try {
            $request->validate([
                'censorship_enabled' => 'required|boolean',
            ]);

            $setting = ChatbotSetting::first();
            if (!$setting) {
                $setting = ChatbotSetting::create([
                    'enabled' => true,
                    'is_online' => true,
                    'censorship_enabled' => false,
                    'welcome_message' => '',
                    'quick_intents' => [],
                ]);
            }

            $setting->censorship_enabled = $request->boolean('censorship_enabled');
            $setting->save();

            ChatCensorshipService::resetCache();

            return response()->json([
                'success' => true,
                'censorship_enabled' => $setting->censorship_enabled,
                'message' => $setting->censorship_enabled
                    ? 'Censorship is now enabled.'
                    : 'Censorship has been disabled.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error toggling chat censorship: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update censorship setting. Please try again.',
            ], 500);
        }
    }

    /**
     * Get censorship status.
     */
    public function getCensorshipStatus()
    {
        $setting = ChatbotSetting::first();

        return response()->json([
            'censorship_enabled' => $setting ? (bool) $setting->censorship_enabled : false,
        ]);
    }

    /**
     * Retrieve custom blocked words.
     */
    public function getBlocklist()
    {
        $words = ChatCensoredWord::query()
            ->orderBy('word')
            ->get();

        return response()->json([
            'words' => $words->map(function (ChatCensoredWord $word) {
                return [
                    'id' => $word->id,
                    'word' => $word->word,
                    'masked' => ChatCensorshipService::maskedPreview($word->word),
                ];
            }),
        ]);
    }

    /**
     * Add a new word to the blocklist.
     */
    public function addBlocklistWord(Request $request)
    {
        try {
            $request->validate([
                'word' => 'required|string|max:100',
            ]);

            $word = trim($request->input('word'));

            if ($word === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a word to block.',
                ], 422);
            }

            if (mb_strlen($word) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Words must be at least two characters long.',
                ], 422);
            }

            if (ChatCensorshipService::isDefaultWord($word)) {
                return response()->json([
                    'success' => false,
                    'message' => 'That word is already part of the default blocklist.',
                ], 422);
            }

            $normalized = mb_strtolower($word);

            $duplicate = ChatCensoredWord::query()
                ->whereRaw('LOWER(word) = ?', [$normalized])
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'That word is already in the blocklist.',
                ], 422);
            }

            $newWord = ChatCensoredWord::create([
                'word' => $word,
                'created_by_id' => Auth::id(),
                'created_by_type' => 'admin',
            ]);

            ChatCensorshipService::resetCache();

            return response()->json([
                'success' => true,
                'word' => [
                    'id' => $newWord->id,
                    'word' => $newWord->word,
                    'masked' => ChatCensorshipService::maskedPreview($newWord->word),
                ],
                'message' => 'Word added to blocklist.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error adding blocklist word: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add word to blocklist. Please try again.',
            ], 500);
        }
    }

    /**
     * Remove a word from the blocklist.
     */
    public function removeBlocklistWord($wordId)
    {
        $word = ChatCensoredWord::findOrFail($wordId);
        $word->delete();

        ChatCensorshipService::resetCache();

        return response()->json([
            'success' => true,
            'message' => 'Word removed from blocklist.',
        ]);
    }

    /**
     * Save all blocklist words (bulk save).
     */
    public function saveBlocklist(Request $request)
    {
        try {
            $request->validate([
                'words' => 'required|array',
                'words.*' => 'required|string|max:100',
            ]);

            $words = collect($request->input('words', []))
                ->map(fn($word) => trim($word))
                ->filter(fn($word) => $word !== '' && mb_strlen($word) >= 2)
                ->unique()
                ->values()
                ->all();

            // Remove existing custom words
            ChatCensoredWord::query()->delete();

            // Add new words
            $savedWords = [];
            foreach ($words as $word) {
                // Allow all words/phrases to be saved, even if they match default words
                // This allows users to add custom phrases and see them in the modal
                $savedWords[] = ChatCensoredWord::create([
                    'word' => $word,
                    'created_by_id' => Auth::id(),
                    'created_by_type' => 'admin',
                ]);
            }

            ChatCensorshipService::resetCache();

            return response()->json([
                'success' => true,
                'words' => collect($savedWords)->map(function (ChatCensoredWord $word) {
                    return [
                        'id' => $word->id,
                        'word' => $word->word,
                        'masked' => ChatCensorshipService::maskedPreview($word->word),
                    ];
                }),
                'message' => 'Blocklist saved successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving blocklist: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save blocklist. Please try again.',
            ], 500);
        }
    }

    /**
     * Get chat online status
     */
    public function getOnlineStatus()
    {
        $setting = ChatbotSetting::first();
        $isOnline = $setting ? $setting->is_online : true;

        return response()->json([
            'is_online' => $isOnline,
        ]);
    }
}

