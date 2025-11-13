<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ChatConversation;
use App\Models\ChatCensoredWord;
use App\Models\ChatMessage;
use App\Models\ChatbotSetting;
use App\Services\ChatCensorshipService;
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
        $search = $request->input('search');

        $query = ChatConversation::with(['patient.info', 'staff.info', 'messages.sender.info']);

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

        $currentStaffId = Auth::guard('staff')->id();
        
        return response()->json([
            'conversations' => $conversations->map(function ($conversation) use ($currentStaffId) {
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
                        if ($lastMessage->sender_id == $currentStaffId) {
                            $lastSenderName = 'You';
                        } else {
                            if ($lastMessage->sender) {
                                $lastSenderName = $lastMessage->sender->info 
                                    ? $lastMessage->sender->info->first_name . ' ' . $lastMessage->sender->info->last_name 
                                    : $lastMessage->sender->username;
                            } else {
                                $lastSenderName = 'Staff';
                            }
                        }
                    } elseif ($lastMessage->sender_type === 'admin') {
                        if ($lastMessage->sender) {
                            $lastSenderName = $lastMessage->sender->info 
                                ? $lastMessage->sender->info->first_name . ' ' . $lastMessage->sender->info->last_name 
                                : $lastMessage->sender->username;
                        } else {
                            $lastSenderName = 'Admin';
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
                    'message' => ChatCensorshipService::censorText($message->message),
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

        $canAttachFiles = $this->can('attach_files');

        try {
            // Debug: Log what we're receiving
            Log::info('Staff sendMessage request', [
                'has_files' => $request->hasFile('files'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
                'message' => $request->input('message'),
            ]);

            $validationRules = [
                'message' => 'nullable|string|max:2000',
            ];

            if ($canAttachFiles) {
                $validationRules['files.*'] = 'file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt';
            } else {
                $validationRules['files'] = 'prohibited';
            }

            $request->validate($validationRules);

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
            if ($canAttachFiles && $request->hasFile('files')) {
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
                'attachments' => $canAttachFiles && !empty($attachments) ? $attachments : null,
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
                    'message' => ChatCensorshipService::censorText($message->message),
                    'attachments' => $message->attachments,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Staff sendMessage validation error', ['errors' => $e->errors()]);
            $messages = [];
            foreach ($e->errors() as $fieldErrors) {
                $messages = array_merge($messages, $fieldErrors);
            }
            $errorMessage = !empty($messages) ? implode(', ', $messages) : 'Invalid input.';
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $errorMessage
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
            Log::error('Staff: error toggling chat censorship: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update censorship setting. Please try again.',
            ], 500);
        }
    }

    public function getCensorshipStatus()
    {
        $setting = ChatbotSetting::first();

        return response()->json([
            'censorship_enabled' => $setting ? (bool) $setting->censorship_enabled : false,
        ]);
    }

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
                'created_by_id' => Auth::guard('staff')->id(),
                'created_by_type' => 'staff',
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
            Log::error('Staff: error adding blocklist word: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add word to blocklist. Please try again.',
            ], 500);
        }
    }

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
                    'created_by_id' => Auth::guard('staff')->id(),
                    'created_by_type' => 'staff',
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
            Log::error('Staff: error saving blocklist: ' . $e->getMessage(), [
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

