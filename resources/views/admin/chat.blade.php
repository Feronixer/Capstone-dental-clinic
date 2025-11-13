@extends('layout.admin.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-primary"><i class="bi bi-chat-dots"></i> Live Chat Conversations</h2>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="chat-toggle-wrap">
                <span class="chat-status-label" id="chat-status-label">Online</span>
                <div class="form-check form-switch chat-online-toggle">
                    <input class="form-check-input" type="checkbox" role="switch" id="chat-online-toggle" checked>
                    <label class="form-check-label" for="chat-online-toggle"></label>
                </div>
            </div>
            <div class="chat-toggle-wrap">
                <span class="chat-status-label chat-censor-label status-off" id="chat-censor-label">Censor Off</span>
                <div class="form-check form-switch chat-censor-toggle">
                    <input class="form-check-input" type="checkbox" role="switch" id="chat-censor-toggle">
                    <label class="form-check-label" for="chat-censor-toggle"></label>
                </div>
            </div>
            <button class="btn btn-outline-primary chat-blocklist-btn" id="chat-blocklist-btn" type="button">
                <i class="bi bi-shield-lock me-1"></i> Blocklist
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Conversations List -->
        <div class="col-md-4">
            <div class="card shadow-lg chat-sidebar-card">
                <div class="card-header chat-header-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-white fw-bold">
                            <i class="bi bi-chat-dots-fill me-2"></i>Conversations
                        </h5>
                        <span class="badge bg-light text-primary" id="conversation-count">0</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 chat-search-section">
                        <div class="input-group input-group-lg chat-search-wrapper">
                            <span class="input-group-text chat-search-icon">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="search-conversations" class="form-control chat-search-input" placeholder="Search patients...">
                        </div>
                    </div>
                    <div id="conversations-list" class="conversations-list-container">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Interface -->
        <div class="col-md-8">
            <div class="card shadow-lg chat-main-card">
                <div class="card-header chat-header-success d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="chat-avatar-header me-3" id="chat-avatar-header">
                            <div class="avatar-circle-small">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold" id="chat-patient-name">
                                <i class="bi bi-person-fill me-2"></i>Select a conversation
                    </h5>
                            <small class="text-white-50" id="chat-status-text">Choose a conversation to start</small>
                        </div>
                    </div>
                    <div id="chat-actions" style="display: none;" class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-danger chat-delete-btn" id="delete-conversation-btn" type="button" title="Delete conversation">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 chat-body">
                    <div id="chat-messages" class="chat-messages-container">
                        <div class="chat-empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-chat-left-text"></i>
                        </div>
                            <h6 class="mt-3 mb-2">No conversation selected</h6>
                            <p class="text-muted mb-0">Select a conversation from the list to start chatting</p>
                    </div>
                    </div>
                    <div class="chat-input-section" id="chat-input-container" style="display: none;">
                        <div class="input-group input-group-lg chat-input-wrapper">
                            <input type="file" id="chat-file-input" class="d-none" multiple accept="image/*,application/pdf,.doc,.docx,.txt" data-max-size="5242880">
                            <button class="btn btn-outline-secondary chat-attach-btn" type="button" title="Attach file" id="chat-attach-btn">
                                <i class="bi bi-paperclip"></i>
                            </button>
                            <input type="text" id="chat-input" class="form-control chat-message-input" placeholder="Type your message..." autocomplete="off">
                            <button class="btn btn-primary chat-send-btn" id="send-message-btn" type="button">
                                <i class="bi bi-send-fill me-1"></i> Send
                            </button>
                        </div>
                        <div id="chat-attached-files" class="chat-attached-files-container" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Confirmation Modal for Delete -->
<div class="modal fade" id="deletePasswordModal" tabindex="-1" aria-labelledby="deletePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePasswordModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All messages in this conversation will be permanently deleted.
                </div>
                <p class="mb-3">Please enter your password to confirm deletion:</p>
                <div class="mb-3">
                    <label for="delete-password-input" class="form-label">Password</label>
                    <input type="password" class="form-control" id="delete-password-input" placeholder="Enter your password" autocomplete="current-password">
                    <div class="invalid-feedback" id="delete-password-error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                    <i class="bi bi-trash me-2"></i>Delete Conversation
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content success-modal-content">
            <div class="modal-header success-modal-header">
                <h5 class="modal-title text-white" id="successModalLabel">
                    <i class="bi bi-check-circle-fill me-2"></i>Success
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="success-icon mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h6 class="mb-2" id="success-message">Conversation deleted successfully.</h6>
                <p class="text-muted mb-0" id="success-description">The conversation and all its messages have been permanently removed.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success btn-lg px-5" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-2"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- File Size Warning Modal -->
<div class="modal fade" id="fileSizeWarningModal" tabindex="-1" aria-labelledby="fileSizeWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content file-size-warning-modal-content">
            <div class="modal-header file-size-warning-modal-header">
                <h5 class="modal-title text-white" id="fileSizeWarningModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>File Size Limit Exceeded
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="file-size-warning-icon mb-3 text-center">
                    <i class="bi bi-file-earmark-x"></i>
                </div>
                <div class="alert alert-warning mb-3 file-size-warning-alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>File Size Limit:</strong> The maximum file size allowed is <strong>5MB</strong>.
                </div>
                <p class="mb-3 text-center">The following file(s) exceed the size limit:</p>
                <div class="file-size-warning-list" id="file-size-warning-list">
                    <!-- File list will be inserted here -->
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-warning btn-lg px-5" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-2"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Blocklist Modal -->
<div class="modal fade" id="blocklistModal" tabindex="-1" aria-labelledby="blocklistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content chat-blocklist-modal">
            <div class="modal-header chat-blocklist-header">
                <h5 class="modal-title" id="blocklistModalLabel">
                    <i class="bi bi-shield-lock-fill me-2"></i>Live Chat Blocklist
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">
                    Words in this list will be automatically censored when censorship is enabled. Only the first and last letters remain visible.
                </p>
                <form id="blocklist-add-form" class="blocklist-add-form mb-4">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <i class="bi bi-plus-circle"></i>
                        </span>
                        <input type="text" id="blocklist-input" class="form-control" maxlength="100" placeholder="Enter a word or phrase to censor">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check-lg me-1"></i>Add
                        </button>
                    </div>
                    <div class="form-text mt-2">
                        Preview: <span id="blocklist-input-preview" class="fw-semibold text-primary">—</span>
                    </div>
                    <div id="blocklist-feedback" class="mt-2 text-danger d-none"></div>
                </form>
                <div id="blocklist-words-container" class="blocklist-words-container">
                    <div class="text-center text-muted py-4" id="blocklist-empty-state">
                        <i class="bi bi-shield-check mb-2 d-block" style="font-size: 2rem;"></i>
                        No custom words yet. Add one to start censoring it.
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <small class="text-muted">Default sensitive words are always protected.</small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="blocklist-save-btn">
                        <i class="bi bi-check-lg me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentConversationId = null;
let pollingInterval = null;
let lastMessageId = null;

// Current admin id for identifying own messages
const CURRENT_ADMIN_ID = @json(Auth::guard('admin')->id());

// Chat online status management
let chatOnlineStatus = true;
let isTogglingStatus = false; // Flag to prevent conflicts

// Chat censorship management
let chatCensorshipEnabled = false;
let isTogglingCensorship = false;
let blocklistWords = []; // Words from database
let blocklistTempWords = []; // Temporary words in modal (not yet saved)
let blocklistModalInstance = null;

async function loadOnlineStatus() {
    // Don't reload if we're currently toggling
    if (isTogglingStatus) return;
    
    try {
        const response = await fetch('{{ route("admin-chat.online-status") }}');
        const data = await response.json();
        chatOnlineStatus = data.is_online;
        updateToggleUI(chatOnlineStatus);
    } catch (error) {
        console.error('Error loading online status:', error);
    }
}

function updateToggleUI(isOnline) {
    const toggle = document.getElementById('chat-online-toggle');
    const label = document.getElementById('chat-status-label');
    if (toggle) {
        toggle.checked = isOnline;
    }
    if (label) {
        label.textContent = isOnline ? 'Online' : 'Offline';
        label.className = isOnline ? 'chat-status-label status-online' : 'chat-status-label status-offline';
    }
}

async function toggleOnlineStatus(isOnline) {
    // Prevent multiple simultaneous toggles
    if (isTogglingStatus) {
        console.log('Toggle already in progress, ignoring...');
        return;
    }
    
    isTogglingStatus = true;
    const toggle = document.getElementById('chat-online-toggle');
    if (toggle) {
        toggle.disabled = true;
    }
    
    try {
        const response = await fetch('{{ route("admin-chat.toggle-online-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_online: isOnline })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        if (data.success) {
            chatOnlineStatus = data.is_online;
            updateToggleUI(chatOnlineStatus);
            console.log('Status toggled successfully:', chatOnlineStatus ? 'Online' : 'Offline');
        } else {
            throw new Error(data.message || 'Failed to toggle status');
        }
    } catch (error) {
        console.error('Error toggling online status:', error);
        // Revert toggle on error
        updateToggleUI(!isOnline);
        alert('Failed to update chat status. Please try again.');
    } finally {
        isTogglingStatus = false;
        // Re-enable toggle
        if (toggle) {
            toggle.disabled = false;
        }
    }
}

// Censorship Management
const censorRoutes = {
    status: '{{ route("admin-chat.censorship-status") }}',
    toggle: '{{ route("admin-chat.toggle-censorship") }}',
    blocklistIndex: '{{ route("admin-chat.blocklist.index") }}',
    blocklistStore: '{{ route("admin-chat.blocklist.store") }}',
    blocklistDestroy: '{{ url('/admin/chat/blocklist') }}',
};

function maskWordClient(word) {
    if (!word) return '';

    const tokens = word.split(/(\s+)/);
    return tokens.map((segment) => {
        if (segment.trim() === '') {
            return segment;
        }

        const match = segment.match(/^([A-Za-z0-9]+)(.*)$/u);
        if (match) {
            const masked = maskCore(match[1]);
            return masked + match[2];
        }

        return maskCore(segment);
    }).join('');

    function maskCore(value) {
        const chars = Array.from(value);
        const length = chars.length;

        if (length === 0) return '';
        if (length === 1) return '*';
        if (length === 2) return `${chars[0]}*`;

        return `${chars[0]}${'*'.repeat(length - 2)}${chars[length - 1]}`;
    }
}

function showBlocklistFeedback(message, isError = true) {
    const feedback = document.getElementById('blocklist-feedback');
    if (!feedback) return;
    feedback.textContent = message;
    feedback.classList.remove('d-none', 'text-danger', 'text-success');
    feedback.classList.add(isError ? 'text-danger' : 'text-success');
}

function clearBlocklistFeedback() {
    const feedback = document.getElementById('blocklist-feedback');
    if (!feedback) return;
    feedback.classList.add('d-none');
    feedback.classList.remove('text-danger', 'text-success');
    feedback.textContent = '';
}

async function loadCensorshipStatus() {
    try {
        const response = await fetch(censorRoutes.status);
        const data = await response.json();
        chatCensorshipEnabled = !!data.censorship_enabled;
        updateCensorToggleUI(chatCensorshipEnabled);
    } catch (error) {
        console.error('Error loading censorship status:', error);
    }
}

function updateCensorToggleUI(isEnabled) {
    const toggle = document.getElementById('chat-censor-toggle');
    const label = document.getElementById('chat-censor-label');

    if (toggle) {
        toggle.checked = isEnabled;
    }

    if (label) {
        label.textContent = isEnabled ? 'Censor On' : 'Censor Off';
        label.classList.remove('status-on', 'status-off');
        label.classList.add(isEnabled ? 'status-on' : 'status-off');
    }
}

async function toggleCensorship(isEnabled) {
    if (isTogglingCensorship) {
        console.log('Censorship toggle already in progress, ignoring...');
        return;
    }

    isTogglingCensorship = true;
    const toggle = document.getElementById('chat-censor-toggle');
    if (toggle) {
        toggle.disabled = true;
    }

    try {
        const response = await fetch(censorRoutes.toggle, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ censorship_enabled: isEnabled })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        if (data.success) {
            chatCensorshipEnabled = !!data.censorship_enabled;
            updateCensorToggleUI(chatCensorshipEnabled);
        } else {
            throw new Error(data.message || 'Failed to update censorship setting');
        }
    } catch (error) {
        console.error('Error toggling censorship:', error);
        updateCensorToggleUI(!isEnabled);
        alert('Failed to update censorship setting. Please try again.');
    } finally {
        isTogglingCensorship = false;
        if (toggle) {
            toggle.disabled = false;
        }
    }
}

async function loadBlocklist() {
    try {
        const response = await fetch(censorRoutes.blocklistIndex);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        blocklistWords = Array.isArray(data.words) ? data.words : [];
        // Initialize temp list with current words when modal opens
        blocklistTempWords = blocklistWords.map(w => ({ word: w.word, id: w.id }));
        renderBlocklist();
    } catch (error) {
        console.error('Error loading blocklist:', error);
        showBlocklistFeedback('Unable to load blocklist. Please refresh and try again.');
    }
}

function renderBlocklist() {
    const container = document.getElementById('blocklist-words-container');
    const emptyState = document.getElementById('blocklist-empty-state');

    if (!container) return;

    container.innerHTML = '';

    if (!blocklistTempWords || blocklistTempWords.length === 0) {
        if (emptyState) {
            emptyState.classList.remove('d-none');
        }
        return;
    }

    if (emptyState) {
        emptyState.classList.add('d-none');
    }

    blocklistTempWords.forEach((word, index) => {
        const pill = document.createElement('div');
        pill.className = 'blocklist-word-pill';
        pill.innerHTML = `
            <span class="blocklist-word-text" title="${word.word}">
                ${maskWordClient(word.word)}
            </span>
            <button type="button" class="blocklist-remove" data-word-index="${index}" aria-label="Remove ${word.word}">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        container.appendChild(pill);
    });
}

function addBlocklistWord(word) {
    if (!word || word.trim() === '') {
        showBlocklistFeedback('Please provide a word to add.');
        return;
    }

    const trimmedWord = word.trim();
    
    // Check if word already exists in temp list
    const exists = blocklistTempWords.some(w => w.word.toLowerCase() === trimmedWord.toLowerCase());
    if (exists) {
        showBlocklistFeedback('That word is already in the list.');
        return;
    }

    // Check if word is too short
    if (trimmedWord.length < 2) {
        showBlocklistFeedback('Words must be at least two characters long.');
        return;
    }

    // Add to temporary list
    blocklistTempWords.push({ word: trimmedWord, id: null });
    blocklistTempWords.sort((a, b) => a.word.localeCompare(b.word));
    renderBlocklist();
    showBlocklistFeedback('Word added to list. Click "Save Changes" to save.', false);

    const input = document.getElementById('blocklist-input');
    if (input) {
        input.value = '';
        updateBlocklistPreview('');
    }
}

function removeBlocklistWord(index) {
    if (index === undefined || index === null) return;
    
    blocklistTempWords.splice(index, 1);
    renderBlocklist();
    showBlocklistFeedback('Word removed from list. Click "Save Changes" to save.', false);
}

async function saveBlocklist() {
    const saveButton = document.getElementById('blocklist-save-btn');
    if (saveButton) {
        saveButton.disabled = true;
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    }

    try {
        const words = blocklistTempWords.map(w => w.word);
        const response = await fetch('{{ route("admin-chat.blocklist.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ words })
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Failed to save blocklist.');
        }

        // Update the saved words list
        blocklistWords = Array.isArray(data.words) ? data.words : [];
        showBlocklistFeedback('Blocklist saved successfully!', false);
        
        // Close modal after a short delay
        setTimeout(() => {
            if (blocklistModalInstance) {
                blocklistModalInstance.hide();
            }
        }, 1500);
    } catch (error) {
        console.error('Error saving blocklist:', error);
        showBlocklistFeedback(error.message || 'Failed to save blocklist.');
    } finally {
        if (saveButton) {
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save Changes';
        }
    }
}

function updateBlocklistPreview(value) {
    const preview = document.getElementById('blocklist-input-preview');
    if (!preview) return;

    if (!value.trim()) {
        preview.textContent = '—';
        return;
    }

    preview.textContent = maskWordClient(value);
}

// Initialize online status on page load
loadOnlineStatus();
loadCensorshipStatus();

// Toggle event listener
document.getElementById('chat-online-toggle')?.addEventListener('change', function(e) {
    toggleOnlineStatus(e.target.checked);
});

document.getElementById('chat-censor-toggle')?.addEventListener('change', function(e) {
    toggleCensorship(e.target.checked);
});

const blocklistModalElement = document.getElementById('blocklistModal');
if (typeof bootstrap !== 'undefined' && blocklistModalElement) {
    blocklistModalInstance = new bootstrap.Modal(blocklistModalElement);

    blocklistModalElement.addEventListener('shown.bs.modal', () => {
        clearBlocklistFeedback();
        loadBlocklist();
        document.getElementById('blocklist-input')?.focus();
    });

    blocklistModalElement.addEventListener('hidden.bs.modal', () => {
        clearBlocklistFeedback();
        const input = document.getElementById('blocklist-input');
        if (input) {
            input.value = '';
        }
        updateBlocklistPreview('');
        // Reset temp list to saved words when modal closes
        blocklistTempWords = blocklistWords.map(w => ({ word: w.word, id: w.id }));
    });
}

document.getElementById('chat-blocklist-btn')?.addEventListener('click', () => {
    if (blocklistModalInstance) {
        blocklistModalInstance.show();
    }
});

document.getElementById('blocklist-add-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    clearBlocklistFeedback();
    const input = document.getElementById('blocklist-input');
    const value = input ? input.value.trim() : '';
    addBlocklistWord(value);
});

document.getElementById('blocklist-input')?.addEventListener('input', function(e) {
    updateBlocklistPreview(e.target.value);
    clearBlocklistFeedback();
});

document.getElementById('blocklist-words-container')?.addEventListener('click', function(e) {
    const button = e.target.closest('.blocklist-remove');
    if (!button) return;
    const index = button.getAttribute('data-word-index');
    removeBlocklistWord(parseInt(index));
});

document.getElementById('blocklist-save-btn')?.addEventListener('click', function() {
    saveBlocklist();
});

async function loadConversations() {
    const search = document.getElementById('search-conversations').value;
    const params = new URLSearchParams({ search });
    
    try {
        const response = await fetch(`{{ route('admin-chat.conversations') }}?${params}`);
        const data = await response.json();
        
        const listEl = document.getElementById('conversations-list');
        if (data.conversations.length === 0) {
            listEl.innerHTML = `
                <div class="empty-conversations-state">
                    <div class="empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h6 class="mt-3 mb-2">No conversations found</h6>
                    <p class="text-muted mb-0">Start a new conversation to get started</p>
                </div>
            `;
            document.getElementById('conversation-count').textContent = '0';
            return;
        }
        
        listEl.innerHTML = data.conversations.map(conv => `
            <div class="conversation-item p-3 border-bottom cursor-pointer" 
                 data-conversation-id="${conv.id}"
                 onclick="selectConversation(${conv.id}, '${conv.patient_name}', '${conv.patient_email}')">
                <div class="d-flex align-items-start gap-3">
                    <div class="conversation-avatar">
                        <div class="avatar-circle ${conv.unread_count > 0 ? 'has-unread' : ''}">${conv.patient_name.charAt(0).toUpperCase()}</div>
                        ${conv.unread_count > 0 ? `<span class="unread-badge pulse">${conv.unread_count}</span>` : ''}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 conversation-name fw-semibold">${conv.patient_name}</h6>
                            <small class="conversation-time">${formatDate(conv.last_message_at || conv.created_at)}</small>
                        </div>
                        <small class="conversation-email d-block">${conv.patient_email}</small>
                        ${getLastSenderBadge(conv.last_sender_type, conv.last_sender_name, conv.last_message_text, conv.last_message_has_attachments)}
                    </div>
                </div>
            </div>
        `).join('');
        
        // Update conversation count
        document.getElementById('conversation-count').textContent = data.conversations.length;
    } catch (error) {
        console.error('Error loading conversations:', error);
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    
    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (hours < 24) return `${hours}h ago`;
    if (days < 7) return `${days}d ago`;
    return date.toLocaleDateString();
}

function getLastSenderBadge(senderType, senderName, lastMessageText = null, hasAttachments = false) {
    if (!senderType || !senderName) return '';
    
    let senderLabel = '';
    let iconClass = '';
    
    if (senderType === 'patient') {
        senderLabel = 'Patient';
        iconClass = 'bi-person';
    } else if (senderType === 'staff') {
        senderLabel = `Staff - ${escapeHtml(senderName)}`;
        iconClass = 'bi-person-badge';
    } else if (senderType === 'admin') {
        if (senderName === 'You') {
            senderLabel = 'Admin(You)';
        } else {
            senderLabel = `Admin - ${escapeHtml(senderName)}`;
        }
        iconClass = 'bi-shield-check';
    }
    
    if (!senderLabel) return '';
    
    // Truncate message preview if too long
    let messagePreview = '';
    // If message has attachments and sender is admin or staff, show "Sent an attachment file"
    if (hasAttachments && (senderType === 'admin' || senderType === 'staff')) {
        messagePreview = `<div class="last-message-preview mt-1 attachment-preview-text">&lt;Sent an attachment file&gt;</div>`;
    } else if (lastMessageText) {
        const maxLength = 50;
        const truncated = lastMessageText.length > maxLength 
            ? lastMessageText.substring(0, maxLength) + '...' 
            : lastMessageText;
        messagePreview = `<div class="last-message-preview mt-1">${escapeHtml(truncated)}</div>`;
    }
    
    return `<div class="last-sender-container mt-1">
        <span class="last-sender-badge d-inline-flex align-items-center gap-1">
            <i class="bi ${iconClass}"></i>
            <span><strong>Last Sender:</strong> ${senderLabel}</span>
        </span>
        ${messagePreview}
    </div>`;
}

async function selectConversation(id, patientName, patientEmail) {
    currentConversationId = id;
    const initial = patientName.charAt(0).toUpperCase();
    document.getElementById('chat-patient-name').innerHTML = `<i class="bi bi-person-fill me-2"></i>${patientName}`;
    document.getElementById('chat-status-text').textContent = patientEmail;
    document.getElementById('chat-avatar-header').innerHTML = `<div class="avatar-circle-small">${initial}</div>`;
    document.getElementById('chat-actions').style.display = 'flex';
    document.getElementById('chat-input-container').style.display = 'block';
    
    // Update active state
    document.querySelectorAll('.conversation-item').forEach(el => {
        el.classList.remove('active', 'bg-light');
    });
    const selectedItem = document.querySelector(`[data-conversation-id="${id}"]`);
    if (selectedItem) {
        selectedItem.classList.add('active', 'bg-light');
        // Mark as read - remove unread badge
        const unreadBadge = selectedItem.querySelector('.unread-badge');
        if (unreadBadge) {
            unreadBadge.remove();
            selectedItem.querySelector('.avatar-circle').classList.remove('has-unread');
        }
    }
    
    await loadMessages(id);
    startPolling();
}

async function loadMessages(conversationId) {
    try {
        const response = await fetch(`{{ url('/admin/chat/conversations') }}/${conversationId}/messages`);
        const data = await response.json();
        
        const messagesEl = document.getElementById('chat-messages');
        messagesEl.innerHTML = '';
        
        // Show welcome message if no messages exist
        if (data.messages.length === 0) {
            const welcomeMessage = {
                message: "Hello! 👋 Welcome to J Valera dental clinic chat. I'm here to assist you with any questions or concerns. How can I help you today?",
                sender_type: 'patient',
                sender_name: 'System',
                created_at: new Date().toISOString()
            };
            addMessage(welcomeMessage.message, welcomeMessage.sender_type, welcomeMessage.sender_name, welcomeMessage.created_at, null);
        } else {
        data.messages.forEach(msg => {
                // Compute display name for admin side:
                // - If message from current admin -> "You"
                // - If from staff -> "Name (staff)"
                // - Else use provided name (patient/other)
                let displayName = msg.sender_name || '';
                if ((msg.sender_type === 'admin') && CURRENT_ADMIN_ID && msg.sender_id === CURRENT_ADMIN_ID) {
                    displayName = 'You';
                } else if (msg.sender_type === 'staff' && msg.sender_name) {
                    displayName = `${msg.sender_name} (staff)`;
                }
                addMessage(msg.message, msg.sender_type, displayName, msg.created_at, msg.attachments);
        });
        }
        
        lastMessageId = data.messages.length > 0 ? data.messages[data.messages.length - 1].id : null;
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

function addMessage(text, senderType, senderName, timestamp = null, attachments = null) {
    const messagesEl = document.getElementById('chat-messages');
    const messageWrapper = document.createElement('div');
    const isAdmin = senderType === 'admin' || senderType === 'staff';
    
    const timeStr = timestamp ? formatMessageTime(timestamp) : formatMessageTime(new Date().toISOString());
    
    // Build attachments HTML
    let attachmentsHtml = '';
    if (attachments && Array.isArray(attachments) && attachments.length > 0) {
        attachmentsHtml = '<div class="message-attachments">';
        attachments.forEach(attachment => {
            const isImage = attachment.mime_type && attachment.mime_type.startsWith('image/');
            const fileSize = (attachment.size / 1024).toFixed(1);
            if (isImage) {
                attachmentsHtml += `
                    <div class="attachment-item">
                        <a href="${attachment.url}" target="_blank" class="attachment-link">
                            <img src="${attachment.url}" alt="${attachment.name}" class="attachment-image" />
                            <span class="attachment-name">${escapeHtml(attachment.name)}</span>
                        </a>
                    </div>
                `;
            } else {
                attachmentsHtml += `
                    <div class="attachment-item">
                        <a href="${attachment.url}" target="_blank" download="${escapeHtml(attachment.name)}" class="attachment-link">
                            <i class="bi bi-file-earmark"></i>
                            <span class="attachment-name">${escapeHtml(attachment.name)}</span>
                            <span class="attachment-size">(${fileSize} KB)</span>
                        </a>
                    </div>
                `;
            }
        });
        attachmentsHtml += '</div>';
    }
    
    messageWrapper.className = `message-wrapper ${isAdmin ? 'message-sent' : 'message-received'}`;
    // Preserve the original senderType for identification (admin|staff|patient)
    const senderRole = (senderType === 'admin' || senderType === 'staff' || senderType === 'patient') ? senderType : 'system';
    // Build a clean label for tooltips
    let tooltipLabel = '';
    if (senderRole === 'admin' && (senderName === 'You' || senderName === 'You ')) {
        tooltipLabel = 'You (admin)';
    } else if ((senderRole === 'staff' || senderRole === 'patient')) {
        tooltipLabel = /\([\s\S]*\)$/.test(senderName) ? senderName : `${senderName} (${senderRole})`;
    } else {
        tooltipLabel = senderName || '';
    }
    messageWrapper.innerHTML = `
        <div class="message-bubble ${isAdmin ? 'message-outgoing' : 'message-incoming'}"
             data-sender-name="${escapeHtml(senderName || (isAdmin ? 'You' : ''))}"
             data-sender-type="${senderRole}"
             data-sender-label="${escapeHtml(tooltipLabel)}">
            ${!isAdmin ? `<div class="message-sender">${senderName}</div>` : ''}
            ${text ? `<div class="message-text">${escapeHtml(text)}</div>` : ''}
            ${attachmentsHtml}
            <div class="message-time">${timeStr}</div>
        </div>
    `;
    
    messagesEl.appendChild(messageWrapper);
    messagesEl.scrollTop = messagesEl.scrollHeight;
}

function formatMessageTime(timestamp) {
    if (!timestamp) return '';
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    
    // Just now (less than 1 minute)
    if (minutes < 1) {
        return 'just now';
    }
    
    // Minutes ago (less than 1 hour)
    if (minutes < 60) {
        return `${minutes} ${minutes === 1 ? 'min.' : 'mins.'} ago`;
    }
    
    // Hours ago (less than 3 hours old)
    if (hours < 3) {
        return `${hours} ${hours === 1 ? 'hour.' : 'hours.'} ago`;
    }
    
    // Today (less than 24 hours old, same date)
    const isToday = date.toDateString() === now.toDateString();
    if (isToday && hours < 24) {
        const hours12 = date.getHours() % 12 || 12;
        const minutes12 = date.getMinutes().toString().padStart(2, '0');
        const ampm = date.getHours() >= 12 ? 'PM' : 'AM';
        return `Today ${hours12}:${minutes12} ${ampm}`;
    }
    
    // Yesterday
    const yesterday = new Date(now);
    yesterday.setDate(yesterday.getDate() - 1);
    if (date.toDateString() === yesterday.toDateString()) {
        const hours12 = date.getHours() % 12 || 12;
        const minutes12 = date.getMinutes().toString().padStart(2, '0');
        const ampm = date.getHours() >= 12 ? 'PM' : 'AM';
        return `Yesterday ${hours12}:${minutes12} ${ampm}`;
    }
    
    // This week (within 7 days)
    if (days < 7) {
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const dayName = dayNames[date.getDay()];
        const hours12 = date.getHours() % 12 || 12;
        const minutes12 = date.getMinutes().toString().padStart(2, '0');
        const ampm = date.getHours() >= 12 ? 'PM' : 'AM';
        return `${dayName} ${hours12}:${minutes12} ${ampm}`;
    }
    
    // Older dates - full date format (MM/DD/YYYY HH:MM AM/PM)
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const year = date.getFullYear();
    const hours12 = date.getHours() % 12 || 12;
    const minutes12 = date.getMinutes().toString().padStart(2, '0');
    const ampm = date.getHours() >= 12 ? 'PM' : 'AM';
    return `${month}/${day}/${year} ${hours12}:${minutes12} ${ampm}`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function buildAttachmentsHtml(attachments) {
    if (!attachments || !Array.isArray(attachments) || attachments.length === 0) {
        return '';
    }
    
    let html = '<div class="message-attachments">';
    attachments.forEach(attachment => {
        const isImage = attachment.mime_type && attachment.mime_type.startsWith('image/');
        const fileSize = (attachment.size / 1024).toFixed(1);
        if (isImage) {
            html += `
                <div class="attachment-item">
                    <a href="${attachment.url}" target="_blank" class="attachment-link">
                        <img src="${attachment.url}" alt="${escapeHtml(attachment.name)}" class="attachment-image" />
                        <span class="attachment-name">${escapeHtml(attachment.name)}</span>
                    </a>
                </div>
            `;
        } else {
            html += `
                <div class="attachment-item">
                    <a href="${attachment.url}" target="_blank" download="${escapeHtml(attachment.name)}" class="attachment-link">
                        <i class="bi bi-file-earmark"></i>
                        <span class="attachment-name">${escapeHtml(attachment.name)}</span>
                        <span class="attachment-size">(${fileSize} KB)</span>
                    </a>
                </div>
            `;
        }
    });
    html += '</div>';
    return html;
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    // Check if there's something to send
    if ((!message || message === '') && attachedFiles.length === 0) return;
    if (!currentConversationId) return;
    
    // Store files before clearing input
    const filesToSend = attachedFiles.slice(); // Deep copy
    const messageText = message || '';
    
    // Clear input immediately for better UX
    input.value = '';
    
    // Show message immediately with placeholder attachments
    const placeholderAttachments = filesToSend.length > 0 ? filesToSend.map(f => ({
        name: f.name,
        url: '#',
        size: f.size,
        mime_type: f.type
    })) : null;
    
    if (messageText || placeholderAttachments) {
        addMessage(messageText || '📎 File attachment', 'admin', 'You', null, placeholderAttachments);
    }
    
    try {
        // Create FormData
        const formData = new FormData();
        
        // Add message (always send, even if empty)
        formData.append('message', messageText);
        
        // Add CSRF token
        formData.append('_token', '{{ csrf_token() }}');
        
        // Add files - use numeric index for Laravel array handling
        if (filesToSend.length > 0) {
            console.log('Sending files:', filesToSend.length);
            filesToSend.forEach((file, index) => {
                console.log(`Adding file ${index}:`, file.name, file.size, file.type);
                formData.append(`files[${index}]`, file);
            });
        }
        
        // Log FormData contents for debugging
        console.log('FormData entries:');
        for (let pair of formData.entries()) {
            console.log(pair[0], pair[1]);
        }
        
        const response = await fetch(`{{ url('/admin/chat/conversations') }}/${currentConversationId}/send`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const responseText = await response.text();
        console.log('Response status:', response.status);
        console.log('Response text:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse response:', e);
            throw new Error('Invalid response from server');
        }
        
        if (data.success) {
            console.log('Message sent successfully:', data);
            
            // Update the message with actual attachments from server
            if (data.message && data.message.attachments && data.message.attachments.length > 0) {
                const messagesEl = document.getElementById('chat-messages');
                const messageWrappers = messagesEl.querySelectorAll('.message-wrapper');
                if (messageWrappers.length > 0) {
                    const lastWrapper = messageWrappers[messageWrappers.length - 1];
                    const messageBubble = lastWrapper.querySelector('.message-bubble');
                    if (messageBubble) {
                        // Remove placeholder attachments if any
                        const existingAttachments = messageBubble.querySelector('.message-attachments');
                        if (existingAttachments) {
                            existingAttachments.remove();
                        }
                        // Add real attachments
                        const attachmentsHtml = buildAttachmentsHtml(data.message.attachments);
                        const messageTextEl = messageBubble.querySelector('.message-text');
                        if (messageTextEl && attachmentsHtml) {
                            messageTextEl.insertAdjacentHTML('afterend', attachmentsHtml);
                        } else if (attachmentsHtml) {
                            // If no message text, add attachments directly
                            messageBubble.insertAdjacentHTML('afterbegin', attachmentsHtml);
                        }
                    }
                }
            }
            
            lastMessageId = data.message.id;
            loadConversations();
            
            // Clear attached files only on success
            attachedFiles = [];
            updateAttachedFilesDisplay();
            document.getElementById('chat-file-input').value = '';
        } else {
            console.error('Error sending message:', data);
            showErrorModal(data.message || 'Failed to send message');
            // Remove the placeholder message on error
            const messagesEl = document.getElementById('chat-messages');
            const messageWrappers = messagesEl.querySelectorAll('.message-wrapper');
            if (messageWrappers.length > 0) {
                messageWrappers[messageWrappers.length - 1].remove();
            }
            // Don't clear files on error - keep them for retry
        }
    } catch (error) {
        console.error('Error sending message:', error);
        showErrorModal(error.message);
        // Remove the placeholder message on error
        const messagesEl = document.getElementById('chat-messages');
        const messageWrappers = messagesEl.querySelectorAll('.message-wrapper');
        if (messageWrappers.length > 0) {
            messageWrappers[messageWrappers.length - 1].remove();
        }
        // Don't clear files on error - keep them for retry
    }
}

function startPolling() {
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(async () => {
        if (!currentConversationId) return;
        try {
            const response = await fetch(`{{ url('/admin/chat/conversations') }}/${currentConversationId}/messages`);
            const data = await response.json();
            
            data.messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    let displayName = msg.sender_name || '';
                    if ((msg.sender_type === 'admin') && CURRENT_ADMIN_ID && msg.sender_id === CURRENT_ADMIN_ID) {
                        displayName = 'You';
                    } else if (msg.sender_type === 'staff' && msg.sender_name) {
                        displayName = `${msg.sender_name} (staff)`;
                    }
                    addMessage(msg.message, msg.sender_type, displayName, msg.created_at, msg.attachments);
                    lastMessageId = msg.id;
                }
            });
        } catch (error) {
            console.error('Error polling:', error);
        }
    }, 3000);
}

function stopPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
}

// File attachment handling
let attachedFiles = [];

document.getElementById('chat-attach-btn').addEventListener('click', function() {
    document.getElementById('chat-file-input').click();
});

document.getElementById('chat-file-input').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
    const invalidFiles = [];
    
    files.forEach(file => {
        // Check file size (5MB limit)
        if (file.size > maxSize) {
            invalidFiles.push(file.name);
            return;
        }
        
        // Check if file already exists
        if (!attachedFiles.find(f => f.name === file.name && f.size === file.size)) {
            attachedFiles.push(file);
        }
    });
    
    // Show error for files that exceed size limit
    if (invalidFiles.length > 0) {
        showFileSizeWarningModal(invalidFiles);
    }
    
    updateAttachedFilesDisplay();
    // Reset file input to allow selecting the same file again
    e.target.value = '';
});

function updateAttachedFilesDisplay() {
    const container = document.getElementById('chat-attached-files');
    if (attachedFiles.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'flex';
    container.innerHTML = attachedFiles.map((file, index) => `
        <div class="chat-attached-file-item">
            <i class="bi bi-file-earmark"></i>
            <span class="file-name" title="${file.name}">${file.name}</span>
            <span class="file-size">(${(file.size / 1024).toFixed(1)} KB)</span>
            <button type="button" class="file-remove" onclick="removeAttachedFile(${index})" title="Remove file">
                <i class="bi bi-x-circle"></i>
            </button>
        </div>
    `).join('');
}

function removeAttachedFile(index) {
    attachedFiles.splice(index, 1);
    updateAttachedFilesDisplay();
    // Reset file input
    document.getElementById('chat-file-input').value = '';
}

document.getElementById('search-conversations').addEventListener('input', loadConversations);
document.getElementById('send-message-btn').addEventListener('click', sendMessage);
document.getElementById('chat-input').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') sendMessage();
});

// Delete conversation handler (Admin only)
let deletePasswordModal = null;
let successModal = null;
let fileSizeWarningModal = null;
let errorModal = null;
if (typeof bootstrap !== 'undefined') {
    deletePasswordModal = new bootstrap.Modal(document.getElementById('deletePasswordModal'));
    successModal = new bootstrap.Modal(document.getElementById('successModal'));
    fileSizeWarningModal = new bootstrap.Modal(document.getElementById('fileSizeWarningModal'));
}

// Function to show file size warning modal
function showFileSizeWarningModal(invalidFiles) {
    const listContainer = document.getElementById('file-size-warning-list');
    listContainer.innerHTML = invalidFiles.map(fileName => `
        <div class="file-size-warning-item">
            <i class="bi bi-file-earmark-x text-danger me-2"></i>
            <span class="file-size-warning-name">${escapeHtml(fileName)}</span>
        </div>
    `).join('');
    
    if (fileSizeWarningModal) {
        fileSizeWarningModal.show();
    } else {
        // Fallback if Bootstrap modal is not available
        document.getElementById('fileSizeWarningModal').style.display = 'block';
    }
}

// Function to show error modal
function showErrorModal(message) {
    // Create a simple error modal or use existing modal
    const errorMessage = escapeHtml(message);
    if (fileSizeWarningModal) {
        document.getElementById('fileSizeWarningModalLabel').innerHTML = '<i class="bi bi-exclamation-circle-fill me-2"></i>Error';
        document.getElementById('file-size-warning-list').innerHTML = `
            <div class="file-size-warning-item">
                <i class="bi bi-exclamation-circle text-danger me-2"></i>
                <span class="file-size-warning-name">${errorMessage}</span>
            </div>
        `;
        fileSizeWarningModal.show();
    } else {
        alert('Error: ' + message);
    }
}

document.getElementById('delete-conversation-btn').addEventListener('click', function() {
    if (!currentConversationId) return;
    
    // Clear password input and errors
    document.getElementById('delete-password-input').value = '';
    document.getElementById('delete-password-input').classList.remove('is-invalid');
    document.getElementById('delete-password-error').textContent = '';
    
    // Show password modal
    if (deletePasswordModal) {
        deletePasswordModal.show();
    } else {
        // Fallback if Bootstrap modal is not available
        document.getElementById('deletePasswordModal').style.display = 'block';
    }
});

// Confirm delete button handler
document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
    const passwordInput = document.getElementById('delete-password-input');
    const password = passwordInput.value.trim();
    const errorDiv = document.getElementById('delete-password-error');
    
    // Validate password
    if (!password) {
        passwordInput.classList.add('is-invalid');
        errorDiv.textContent = 'Password is required.';
        return;
    }
    
    // Disable button during request
    const confirmBtn = document.getElementById('confirm-delete-btn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    
    try {
        const response = await fetch(`{{ url('/admin/chat/conversations') }}/${currentConversationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ password: password })
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Close modal
            if (deletePasswordModal) {
                deletePasswordModal.hide();
            } else {
                document.getElementById('deletePasswordModal').style.display = 'none';
            }
            
            // Reset chat interface
            currentConversationId = null;
            lastMessageId = null;
            stopPolling();
            
            // Clear messages
            const messagesEl = document.getElementById('chat-messages');
            messagesEl.innerHTML = `
                <div class="chat-empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                    <h6 class="mt-3 mb-2">No conversation selected</h6>
                    <p class="text-muted mb-0">Select a conversation from the list to start chatting</p>
                </div>
            `;
            
            // Hide input and actions
            document.getElementById('chat-input-container').style.display = 'none';
            document.getElementById('chat-actions').style.display = 'none';
            
            // Reset header
            document.getElementById('chat-patient-name').innerHTML = `<i class="bi bi-person-fill me-2"></i>Select a conversation`;
            document.getElementById('chat-status-text').textContent = 'Choose a conversation to start';
            document.getElementById('chat-avatar-header').innerHTML = `<div class="avatar-circle-small"><i class="bi bi-person"></i></div>`;
            
            // Reload conversations list
            loadConversations();
            
            // Show success modal
            if (successModal) {
                successModal.show();
            } else {
                // Fallback if Bootstrap modal is not available
                document.getElementById('successModal').style.display = 'block';
            }
        } else {
            // Show error
            passwordInput.classList.add('is-invalid');
            errorDiv.textContent = data.message || 'Incorrect password. Please try again.';
            passwordInput.value = '';
            passwordInput.focus();
        }
    } catch (error) {
        console.error('Error deleting conversation:', error);
        passwordInput.classList.add('is-invalid');
        errorDiv.textContent = 'Error deleting conversation. Please try again.';
    } finally {
        // Re-enable button
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalText;
    }
});

// Allow Enter key to submit password
document.getElementById('delete-password-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('confirm-delete-btn').click();
    }
});

// Clear errors when user types
document.getElementById('delete-password-input').addEventListener('input', function() {
    this.classList.remove('is-invalid');
    document.getElementById('delete-password-error').textContent = '';
});

// Click a message bubble to reveal who sent it
document.getElementById('chat-messages').addEventListener('click', function(e) {
    const bubble = e.target.closest('.message-bubble');
    if (!bubble) return;
    const preset = bubble.getAttribute('data-sender-label');
    let label = preset;
    if (!label) {
        const name = bubble.getAttribute('data-sender-name') || '';
        const type = bubble.getAttribute('data-sender-type') || '';
        if (name.toLowerCase() === 'you' && type === 'admin') {
            label = 'You (admin)';
        } else if (name.match(/\([\s\S]*\)$/)) {
            label = name;
        } else if (type) {
            label = `${name} (${type})`;
        } else {
            label = name;
        }
    }
    if (window.bootstrap && bootstrap.Tooltip) {
        try {
            bubble.setAttribute('data-bs-title', label);
            bubble.setAttribute('data-bs-placement', 'top');
            const tip = new bootstrap.Tooltip(bubble, { trigger: 'manual' });
            tip.show();
            setTimeout(() => { try { tip.dispose(); } catch(_) {} }, 1500);
        } catch (_) {
            alert(`Sent by: ${label}`);
        }
    } else {
        alert(`Sent by: ${label}`);
    }
});

// Load conversations on page load
loadConversations();
setInterval(loadConversations, 10000); // Refresh list every 10 seconds
</script>

<style>
/* Chat Online Status Toggle Styles */
.chat-status-label {
    font-size: 0.9rem;
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.chat-status-label.status-online {
    color: #10b981;
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border: 1px solid #10b981;
}

.chat-status-label.status-offline {
    color: #ef4444;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border: 1px solid #ef4444;
}

.chat-online-toggle .form-check-input {
    width: 3.5rem;
    height: 1.75rem;
    cursor: pointer;
    background-color: #cbd5e1;
    border: 2px solid #94a3b8;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chat-online-toggle .form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
}

.chat-online-toggle .form-check-input:focus {
    border-color: #10b981;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
}

.chat-online-toggle .form-check-input:not(:checked) {
    background-color: #ef4444;
    border-color: #ef4444;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    background-position: left center;
}

.chat-toggle-wrap {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.chat-censor-label {
    font-size: 0.9rem;
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    border: 1px solid transparent;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.chat-censor-label.status-on {
    color: #2563eb;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-color: #2563eb;
}

.chat-censor-label.status-off {
    color: #9333ea;
    background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    border-color: #a855f7;
}

.chat-censor-toggle .form-check-input {
    width: 3.5rem;
    height: 1.75rem;
    cursor: pointer;
    background-color: #c7d2fe;
    border: 2px solid #818cf8;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chat-censor-toggle .form-check-input:checked {
    background-color: #6366f1;
    border-color: #4f46e5;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
}

.chat-censor-toggle .form-check-input:not(:checked) {
    background-color: #ede9fe;
    border-color: #c4b5fd;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    background-position: left center;
}

.chat-censor-toggle .form-check-input:focus {
    border-color: #4f46e5;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
}

.chat-blocklist-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-weight: 600;
    border-radius: 999px;
    padding: 0.5rem 1.25rem;
    transition: all 0.3s ease;
    border-width: 2px;
}

.chat-blocklist-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(59, 130, 246, 0.25);
}

.chat-blocklist-modal {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(37, 99, 235, 0.25);
}

.chat-blocklist-header {
    background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%) !important;
    color: #fff;
    border-bottom: none;
}

.blocklist-add-form .input-group-text {
    background: transparent;
    border: none;
    font-size: 1.1rem;
    color: #2563eb;
}

.blocklist-add-form .form-control {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-color: #bfdbfe;
    font-weight: 500;
}

.blocklist-add-form .form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.2);
}

.blocklist-words-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.blocklist-word-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
    border: 1px solid #bfdbfe;
    border-radius: 999px;
    padding: 0.5rem 0.75rem 0.5rem 1rem;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.blocklist-word-pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
}

.blocklist-word-text {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.blocklist-remove {
    background: transparent;
    border: none;
    color: #1d4ed8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.blocklist-remove:hover {
    background: rgba(37, 99, 235, 0.15);
    color: #1e3a8a;
}

[data-theme="dark"] .chat-censor-label.status-on {
    color: #60a5fa;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.2) 0%, rgba(59, 130, 246, 0.25) 100%);
    border-color: rgba(59, 130, 246, 0.6);
}

[data-theme="dark"] .chat-censor-label.status-off {
    color: #c084fc;
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.2) 0%, rgba(147, 51, 234, 0.2) 100%);
    border-color: rgba(147, 51, 234, 0.5);
}

[data-theme="dark"] .chat-censor-toggle .form-check-input {
    background-color: #4c1d95;
    border-color: #6d28d9;
}

[data-theme="dark"] .chat-censor-toggle .form-check-input:checked {
    background-color: #4f46e5;
    border-color: #4338ca;
}

[data-theme="dark"] .chat-censor-toggle .form-check-input:not(:checked) {
    background-color: #312e81;
    border-color: #4338ca;
}

[data-theme="dark"] .chat-censor-toggle .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.35);
}

[data-theme="dark"] .chat-blocklist-btn {
    border-color: rgba(96, 165, 250, 0.6) !important;
    color: #93c5fd !important;
}

[data-theme="dark"] .chat-blocklist-btn:hover {
    box-shadow: 0 6px 18px rgba(59, 130, 246, 0.35);
}

[data-theme="dark"] .chat-blocklist-modal {
    background: var(--dm-card-bg, #1f2937) !important;
    box-shadow: 0 20px 60px rgba(37, 99, 235, 0.35);
}

[data-theme="dark"] .chat-blocklist-header {
    background: linear-gradient(135deg, #1d4ed8 0%, #312e81 100%) !important;
}

[data-theme="dark"] .blocklist-add-form .input-group-text {
    color: #93c5fd !important;
}

[data-theme="dark"] .blocklist-add-form .form-control {
    background: var(--dm-bg-secondary, #0f172a) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .blocklist-add-form .form-control:focus {
    border-color: #60a5fa !important;
    box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.25) !important;
}

[data-theme="dark"] .blocklist-word-pill {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.2) 0%, rgba(96, 165, 250, 0.2) 100%) !important;
    border-color: rgba(96, 165, 250, 0.35) !important;
    color: var(--dm-text-primary, #f8fafc) !important;
}

[data-theme="dark"] .blocklist-remove {
    color: #bfdbfe !important;
}

[data-theme="dark"] .blocklist-remove:hover {
    background: rgba(59, 130, 246, 0.35) !important;
    color: #e0f2fe !important;
}

[data-theme="dark"] .chat-status-label.status-online {
    color: #34d399;
    background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
    border-color: #10b981;
}

[data-theme="dark"] .chat-status-label.status-offline {
    color: #fca5a5;
    background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
    border-color: #ef4444;
}

[data-theme="dark"] .chat-online-toggle .form-check-input {
    background-color: #475569;
    border-color: #64748b;
}

[data-theme="dark"] .chat-online-toggle .form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
}

[data-theme="dark"] .chat-online-toggle .form-check-input:not(:checked) {
    background-color: #ef4444;
    border-color: #ef4444;
}

[data-theme="dark"] .chat-online-toggle .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.3);
}

/* Card Enhancements */
.chat-sidebar-card,
.chat-main-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
}

.chat-header-primary {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 50%, #1565c0 100%) !important;
    border: none;
    padding: 1.5rem 1.75rem;
    box-shadow: 0 4px 16px rgba(33, 150, 243, 0.3);
    position: relative;
    overflow: hidden;
}

.chat-header-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
    pointer-events: none;
}

.chat-header-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%) !important;
    border: none;
    padding: 1.5rem 1.75rem;
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    position: relative;
    overflow: hidden;
}

.chat-header-success::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
    pointer-events: none;
}

.chat-search-section {
    background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
    border-bottom: 2px solid #e2e8f0;
    padding: 1.25rem 1.5rem !important;
}

.chat-search-wrapper {
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.chat-search-wrapper:focus-within {
    box-shadow: 0 6px 20px rgba(33, 150, 243, 0.15);
    border-color: #2196F3;
    transform: translateY(-2px);
}

.chat-search-icon {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border: none;
    color: #3b82f6;
    font-size: 1.1rem;
}

.chat-search-input {
    border: none;
    background: white;
    padding: 0.75rem 1rem;
}

.chat-search-input:focus {
    box-shadow: none;
    background: white;
}


.conversations-list-container {
    max-height: 600px;
    overflow-y: auto;
    background: white;
}

.conversations-list-container::-webkit-scrollbar {
    width: 6px;
}

.conversations-list-container::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.conversations-list-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.conversations-list-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Conversation List Styles */
.conversation-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    border-left: 3px solid transparent;
}

.conversation-item:hover {
    background: linear-gradient(to right, #f8fafc 0%, #ffffff 100%) !important;
    transform: translateX(4px);
    box-shadow: -2px 0 8px rgba(33, 150, 243, 0.1);
}

.conversation-item.active {
    background: linear-gradient(to right, #dbeafe 0%, #eff6ff 100%) !important;
    border-left: 5px solid #2196F3;
    box-shadow: -4px 0 16px rgba(33, 150, 243, 0.2);
    transform: translateX(2px);
    position: relative;
}

.conversation-item.active::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, #2196F3 0%, #1976D2 100%);
    border-radius: 0 3px 3px 0;
}

.conversation-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-circle {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
    transition: all 0.3s ease;
    border: 3px solid white;
}

.avatar-circle.has-unread {
    box-shadow: 0 4px 16px rgba(33, 150, 243, 0.5), 0 0 0 2px rgba(239, 68, 68, 0.3);
    animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 4px 16px rgba(33, 150, 243, 0.5), 0 0 0 2px rgba(239, 68, 68, 0.3);
    }
    50% {
        box-shadow: 0 4px 20px rgba(33, 150, 243, 0.6), 0 0 0 3px rgba(239, 68, 68, 0.5);
    }
}

.avatar-circle-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.unread-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-radius: 50%;
    min-width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    z-index: 10;
}

.unread-badge.pulse {
    animation: pulse-badge 1.5s ease-in-out infinite;
}

@keyframes pulse-badge {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.conversation-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.conversation-email {
    font-size: 0.8rem;
    color: #1e40af;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.last-sender-container {
    display: flex;
    flex-direction: column;
}

.last-sender-badge {
    font-size: 0.75rem;
    color: #3b82f6;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    padding: 0.25rem 0.625rem;
    border-radius: 8px;
    border: 1px solid #bfdbfe;
    font-weight: 500;
    box-shadow: 0 1px 3px rgba(59, 130, 246, 0.1);
    transition: all 0.2s ease;
    width: fit-content;
}

.last-sender-badge:hover {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);
    transform: translateY(-1px);
}

.last-sender-badge i {
    font-size: 0.875rem;
    color: #2563eb;
}

.last-sender-badge strong {
    font-weight: 700;
    color: #1e40af;
}

.last-message-preview {
    font-size: 0.8rem;
    color: #64748b;
    line-height: 1.4;
    margin-top: 0.25rem;
    padding-left: 0.125rem;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.attachment-preview-text {
    font-style: italic;
    color: #3b82f6 !important;
    font-weight: 500;
}

.conversation-preview {
    font-size: 0.85rem;
    color: #475569;
    line-height: 1.5;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.conversation-time {
    font-size: 0.75rem;
    white-space: nowrap;
    color: #94a3b8;
    font-weight: 500;
}

.min-w-0 {
    min-width: 0;
}

/* Chat Body Styles */
.chat-body {
    background: #f8fafc;
}

.chat-messages-container {
    height: 500px;
    overflow-y: auto;
    padding: 24px !important;
    background: linear-gradient(to bottom, #f1f5f9 0%, #ffffff 100%) !important;
    position: relative;
}

.chat-messages-container::-webkit-scrollbar {
    width: 8px;
}

.chat-messages-container::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.chat-messages-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.chat-messages-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.chat-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
    padding: 3rem;
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 50%, #7dd3fc 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #0ea5e9;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 24px rgba(14, 165, 233, 0.2);
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.empty-conversations-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #bae6fd 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #0ea5e9;
    margin: 0 auto;
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.15);
    animation: float 3s ease-in-out infinite;
}

/* Message Styles */

.message-wrapper {
    display: flex;
    margin-bottom: 16px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-sent {
    justify-content: flex-end;
}

.message-received {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 75%;
    padding: 16px 20px;
    border-radius: 22px;
    position: relative;
    word-wrap: break-word;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
}

.message-bubble:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    transform: translateY(-2px);
}

.message-incoming {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%);
    border-bottom-left-radius: 8px;
    color: #1e293b;
    border: 1.5px solid #e2e8f0;
    position: relative;
}

.message-incoming::before {
    content: '';
    position: absolute;
    left: -8px;
    bottom: 0;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 8px 12px 0;
    border-color: transparent #f8fafc transparent transparent;
}

.message-outgoing {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 50%, #1565c0 100%);
    border-bottom-right-radius: 8px;
    color: white;
    box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
    position: relative;
}

.message-outgoing::after {
    content: '';
    position: absolute;
    right: -8px;
    bottom: 0;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 0 12px 8px;
    border-color: transparent transparent #1976D2 transparent;
}

.message-sender {
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 4px;
    color: #64748b;
}

.message-outgoing .message-sender {
    color: rgba(255, 255, 255, 0.9);
}

.message-text {
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 4px;
}

.message-time {
    font-size: 0.7rem;
    opacity: 0.7;
    text-align: right;
    margin-top: 4px;
}

.message-attachments {
    margin-top: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.attachment-item {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.2s ease;
}

.message-incoming .attachment-item {
    background: rgba(0, 0, 0, 0.05);
}

.attachment-link {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: inherit;
    width: 100%;
}

.attachment-link:hover {
    opacity: 0.8;
}

.attachment-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 6px;
    object-fit: cover;
}

.attachment-name {
    font-size: 0.875rem;
    font-weight: 500;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.attachment-size {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-left: 4px;
}

.attachment-item i {
    font-size: 1.25rem;
    color: #3b82f6;
}

.message-incoming .message-time {
    color: #64748b;
}

.message-outgoing .message-time {
    color: rgba(255, 255, 255, 0.9);
}

/* Input Styles */
.chat-input-section {
    background: linear-gradient(to top, #ffffff 0%, #f8fafc 100%);
    border-top: 2px solid #e2e8f0;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
}

.chat-input-wrapper {
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    background: white;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.chat-input-wrapper:focus-within {
    box-shadow: 0 8px 24px rgba(33, 150, 243, 0.2);
    border-color: #2196F3;
    transform: translateY(-2px);
}

.chat-attach-btn {
    border: none;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #64748b;
    padding: 0.875rem 1.125rem;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.chat-attach-btn:hover {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    color: #3b82f6;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.chat-message-input {
    border: none;
    padding: 0.875rem 1.25rem;
    font-size: 0.95rem;
    background: white;
    transition: all 0.2s ease;
}

.chat-message-input:focus {
    box-shadow: none;
    background: white;
    border: none;
}

.chat-send-btn {
    border: none;
    padding: 0.875rem 2rem;
    font-weight: 700;
    font-size: 0.95rem;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 50%, #1565c0 100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(33, 150, 243, 0.4);
    border-radius: 0;
    position: relative;
    overflow: hidden;
}

.chat-send-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.chat-send-btn:hover::before {
    left: 100%;
}

.chat-send-btn:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565c0 50%, #0d47a1 100%);
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 8px 24px rgba(33, 150, 243, 0.5);
}

.chat-send-btn:active {
    transform: translateY(-1px) scale(0.98);
}


.chat-delete-btn {
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.chat-delete-btn:hover {
    background: #dc2626 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.4);
}

.chat-delete-btn:active {
    transform: translateY(0);
}

.cursor-pointer {
    cursor: pointer;
}

/* Password Modal Styles */
#deletePasswordModal .modal-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    border-bottom: none;
}

#deletePasswordModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

#deletePasswordModal .alert-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #f59e0b;
    border-radius: 8px;
}

#deletePasswordModal .form-control:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
}

#deletePasswordModal .btn-danger {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border: none;
    transition: all 0.3s ease;
}

#deletePasswordModal .btn-danger:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
}

#deletePasswordModal .btn-danger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Dark Mode Styles for Password Modal */
[data-theme="dark"] #deletePasswordModal .modal-content {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #deletePasswordModal .modal-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #deletePasswordModal .modal-body {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #deletePasswordModal .alert-warning {
    background: linear-gradient(135deg, #78350f 0%, #92400e 100%);
    border-color: #f59e0b;
    color: #fef3c7;
}

[data-theme="dark"] #deletePasswordModal .form-control {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #deletePasswordModal .form-control:focus {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: #dc2626 !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.3);
}

[data-theme="dark"] #deletePasswordModal .btn-secondary {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #deletePasswordModal .btn-secondary:hover {
    background: var(--dm-bg-quaternary, #475569) !important;
}

/* Success Modal Styles */
#successModal .success-modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

#successModal .success-modal-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    border-bottom: none;
    padding: 1.5rem;
}

#successModal .success-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #10b981;
    font-size: 3rem;
    animation: successPulse 0.6s ease-out;
}

@keyframes successPulse {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

#successModal .modal-body {
    padding: 2rem 1.5rem;
}

#successModal .modal-body h6 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

#successModal .modal-body p {
    font-size: 0.95rem;
    color: #64748b;
}

#successModal .btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

#successModal .btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

#successModal .btn-success:active {
    transform: translateY(0);
}

/* Dark Mode Styles for Success Modal */
[data-theme="dark"] #successModal .success-modal-content {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #successModal .success-modal-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #successModal .success-icon {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
    color: #10b981;
}

[data-theme="dark"] #successModal .modal-body h6 {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #successModal .modal-body p {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] #successModal .btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

[data-theme="dark"] #successModal .btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5);
}

/* Dark Mode Styles for Chat */
[data-theme="dark"] .card {
    background-color: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .card-header {
    background-color: var(--dm-bg-secondary, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .card-body {
    background-color: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #chat-messages {
    background: linear-gradient(to bottom, #0f172a 0%, #1e293b 100%) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #chat-messages .text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .conversation-item {
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .conversation-item:hover {
    background-color: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .conversation-item.active {
    background-color: rgba(33, 150, 243, 0.15) !important;
    border-left-color: #3b82f6 !important;
}

[data-theme="dark"] .conversation-item.bg-light {
    background-color: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .conversation-name {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .conversation-time {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .conversation-email {
    color: #00d4ff !important;
    text-shadow: none !important;
}

[data-theme="dark"] .conversation-preview {
    color: var(--dm-text-secondary, #cbd5e1) !important;
}

[data-theme="dark"] .avatar-circle {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
}

[data-theme="dark"] .message-incoming {
    background: var(--dm-bg-tertiary, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .message-outgoing {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
}

[data-theme="dark"] .message-sender {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .message-incoming .message-time {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] #chat-input-container {
    background: var(--dm-card-bg, #1e293b) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .form-control {
    background-color: var(--dm-bg-secondary, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-control::placeholder {
    color: var(--dm-text-muted, #64748b) !important;
}

[data-theme="dark"] .form-control:focus {
    background-color: var(--dm-bg-secondary, #0f172a) !important;
    border-color: #60a5fa !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2) !important;
}

[data-theme="dark"] .form-select {
    background-color: var(--dm-bg-secondary, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-select:focus {
    background-color: var(--dm-bg-secondary, #0f172a) !important;
    border-color: #60a5fa !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .border-bottom {
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .border-top {
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .btn-outline-secondary {
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .btn-outline-secondary:hover {
    background-color: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Additional Dark Mode Styles */
[data-theme="dark"] .conversations-list-container {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .conversations-list-container::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #0f172a) !important;
}

[data-theme="dark"] .conversations-list-container::-webkit-scrollbar-thumb {
    background: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] .conversations-list-container::-webkit-scrollbar-thumb:hover {
    background: var(--dm-text-muted, #64748b) !important;
}

[data-theme="dark"] .chat-search-section {
    background: linear-gradient(to bottom, var(--dm-bg-secondary, #0f172a) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .chat-search-wrapper {
    background: var(--dm-bg-secondary, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .chat-search-wrapper:focus-within {
    border-color: #60a5fa !important;
    box-shadow: 0 6px 20px rgba(96, 165, 250, 0.2) !important;
}

[data-theme="dark"] .chat-search-icon {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .chat-search-input {
    background: var(--dm-bg-secondary, #0f172a) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .chat-search-input:focus {
    background: var(--dm-bg-secondary, #0f172a) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}


[data-theme="dark"] .chat-body {
    background: var(--dm-bg-secondary, #0f172a) !important;
}

[data-theme="dark"] .chat-messages-container::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #0f172a) !important;
}

[data-theme="dark"] .chat-messages-container::-webkit-scrollbar-thumb {
    background: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] .chat-messages-container::-webkit-scrollbar-thumb:hover {
    background: var(--dm-text-muted, #64748b) !important;
}

[data-theme="dark"] .chat-empty-state h6,
[data-theme="dark"] .chat-empty-state p {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .empty-conversations-state h6,
[data-theme="dark"] .empty-conversations-state p {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .chat-input-section {
    background: linear-gradient(to top, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .chat-input-wrapper {
    background: var(--dm-bg-secondary, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .chat-input-wrapper:focus-within {
    border-color: #60a5fa !important;
    box-shadow: 0 8px 24px rgba(96, 165, 250, 0.3) !important;
}

[data-theme="dark"] .chat-attach-btn {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .chat-attach-btn:hover {
    background: var(--dm-border-color, #475569) !important;
    color: #60a5fa !important;
    transform: translateY(-1px) !important;
}

.chat-attached-files-container {
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    border-top: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.chat-attached-file-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.875rem;
    color: #475569;
}

.chat-attached-file-item .file-name {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.chat-attached-file-item .file-remove {
    cursor: pointer;
    color: #ef4444;
    font-size: 1rem;
    padding: 0;
    background: none;
    border: none;
    display: flex;
    align-items: center;
}

.chat-attached-file-item .file-remove:hover {
    color: #dc2626;
}

[data-theme="dark"] .chat-attached-files-container {
    background: var(--dm-bg-secondary, #0f172a) !important;
    border-top-color: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] .chat-attached-file-item {
    background: var(--dm-bg-tertiary, #1e293b) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .chat-message-input {
    background: var(--dm-bg-secondary, #0f172a) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .chat-message-input:focus {
    background: var(--dm-bg-secondary, #0f172a) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .chat-status-select {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

[data-theme="dark"] .chat-status-select option {
    background: #10b981 !important;
    color: white !important;
}

[data-theme="dark"] .conversation-item {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .conversation-item.active {
    background: rgba(59, 130, 246, 0.2) !important;
}

[data-theme="dark"] .last-sender-badge {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.2) 100%) !important;
    color: #60a5fa !important;
    border-color: rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .last-sender-badge:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.25) 100%) !important;
}

[data-theme="dark"] .last-sender-badge i {
    color: #60a5fa !important;
}

[data-theme="dark"] .last-sender-badge strong {
    color: #93c5fd !important;
}

[data-theme="dark"] .last-message-preview {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .attachment-preview-text {
    color: #60a5fa !important;
    font-style: italic;
}

[data-theme="dark"] .empty-state-icon {
    background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #2563eb 100%) !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .empty-icon {
    background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #2563eb 100%) !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .chat-delete-btn {
    background: rgba(220, 38, 38, 0.2) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: #fca5a5 !important;
}

[data-theme="dark"] .chat-delete-btn:hover {
    background: rgba(220, 38, 38, 0.4) !important;
    color: #fee2e2 !important;
}
</style>
@endsection

