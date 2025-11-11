@extends('layout.staff.app')
@section('content')
@php
    $canAttachFiles = !$accessControl || $accessControl->can_attach_files;
@endphp
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-primary"><i class="bi bi-chat-dots"></i> Live Chat Conversations</h2>
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
                        <div class="mt-3">
                            <select id="status-filter" class="form-select form-select-sm chat-status-filter">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
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
                    <div id="chat-actions" style="display: none;">
                        <select id="conversation-status" class="form-select form-select-sm chat-status-select">
                            <option value="active">Active</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
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
                            <input type="file" id="chat-file-input" class="d-none" multiple accept="image/*,application/pdf,.doc,.docx,.txt" data-max-size="5242880" @if(!$canAttachFiles) disabled @endif>
                            @if($canAttachFiles)
                            <button class="btn btn-outline-secondary chat-attach-btn" type="button" title="Attach file" id="chat-attach-btn">
                                <i class="bi bi-paperclip"></i>
                            </button>
                            @endif
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

<script>
// Utility function to escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

let currentConversationId = null;
let pollingInterval = null;
let lastMessageId = null;
const canAttachFiles = @json($canAttachFiles);

async function loadConversations() {
    const status = document.getElementById('status-filter').value;
    const search = document.getElementById('search-conversations').value;
    const params = new URLSearchParams({ status, search });
    
    try {
        const response = await fetch(`{{ route('staff-chat.conversations') }}?${params}`);
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
                        ${conv.last_message ? `<p class="mb-0 mt-2 conversation-preview"><i class="bi bi-chat-left me-1"></i>${conv.last_message}</p>` : ''}
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

async function selectConversation(id, patientName, patientEmail) {
    currentConversationId = id;
    const initial = patientName.charAt(0).toUpperCase();
    document.getElementById('chat-patient-name').innerHTML = `<i class="bi bi-person-fill me-2"></i>${patientName}`;
    document.getElementById('chat-status-text').textContent = patientEmail;
    document.getElementById('chat-avatar-header').innerHTML = `<div class="avatar-circle-small">${initial}</div>`;
    document.getElementById('chat-actions').style.display = 'block';
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
        const response = await fetch(`{{ url('/staff/chat/conversations') }}/${conversationId}/messages`);
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
                addMessage(msg.message, msg.sender_type, msg.sender_name, msg.created_at, msg.attachments);
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
    const isStaff = senderType === 'staff' || senderType === 'admin';
    
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
    
    messageWrapper.className = `message-wrapper ${isStaff ? 'message-sent' : 'message-received'}`;
    messageWrapper.innerHTML = `
        <div class="message-bubble ${isStaff ? 'message-outgoing' : 'message-incoming'}">
            ${!isStaff ? `<div class="message-sender">${senderName}</div>` : ''}
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
    
    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
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
    const filesToSend = canAttachFiles ? attachedFiles.slice() : [];
    const messageText = message || '';
    
    // Clear input immediately for better UX
    input.value = '';
    
    // Show message immediately with placeholder attachments
    const placeholderAttachments = canAttachFiles && filesToSend.length > 0 ? filesToSend.map(f => ({
        name: f.name,
        url: '#',
        size: f.size,
        mime_type: f.type
    })) : null;
    
    if (messageText || placeholderAttachments) {
        addMessage(messageText || '📎 File attachment', 'staff', 'You', null, placeholderAttachments);
    }
    
    try {
        // Create FormData
        const formData = new FormData();
        
        // Add message (always send, even if empty)
        formData.append('message', messageText);
        
        // Add CSRF token
        formData.append('_token', '{{ csrf_token() }}');
        
        // Add files - use numeric index for Laravel array handling
        if (canAttachFiles && filesToSend.length > 0) {
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
        
        const response = await fetch(`{{ url('/staff/chat/conversations') }}/${currentConversationId}/send`, {
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
            if (canAttachFiles && data.message && data.message.attachments && data.message.attachments.length > 0) {
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
            alert('Error: ' + (data.message || 'Failed to send message'));
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
            const response = await fetch(`{{ url('/staff/chat/conversations') }}/${currentConversationId}/messages`);
            const data = await response.json();
            
            data.messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    addMessage(msg.message, msg.sender_type, msg.sender_name, msg.created_at, msg.attachments);
                    lastMessageId = msg.id;
                }
            });
        } catch (error) {
            console.error('Error polling:', error);
        }
    }, 3000);
}

// File attachment handling
let attachedFiles = [];

const chatAttachBtn = document.getElementById('chat-attach-btn');
const chatFileInput = document.getElementById('chat-file-input');

if (chatAttachBtn && canAttachFiles) {
    chatAttachBtn.addEventListener('click', function() {
        chatFileInput?.click();
});
}

if (chatFileInput) {
    if (canAttachFiles) {
        chatFileInput.addEventListener('change', function(e) {
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
    } else {
        chatFileInput.value = '';
    }
}

// Initialize modals
let fileSizeWarningModal = null;
if (typeof bootstrap !== 'undefined') {
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

function updateAttachedFilesDisplay() {
    const container = document.getElementById('chat-attached-files');
    if (!canAttachFiles) {
        container.style.display = 'none';
        container.innerHTML = '';
        return;
    }
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

document.getElementById('status-filter').addEventListener('change', loadConversations);
document.getElementById('search-conversations').addEventListener('input', loadConversations);
document.getElementById('send-message-btn').addEventListener('click', sendMessage);
document.getElementById('chat-input').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') sendMessage();
});
document.getElementById('conversation-status').addEventListener('change', async function() {
    if (!currentConversationId) return;
    try {
        const response = await fetch(`{{ url('/staff/chat/conversations') }}/${currentConversationId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: this.value })
        });
        if (response.ok) loadConversations();
    } catch (error) {
        console.error('Error updating status:', error);
    }
});

// Load conversations on page load
loadConversations();
setInterval(loadConversations, 10000); // Refresh list every 10 seconds
</script>

<style>
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

.chat-status-filter {
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    background: white;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.chat-status-filter:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
    outline: none;
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
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.25rem;
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

.chat-status-select {
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(10px);
}

.chat-status-select option {
    background: #10b981;
    color: white;
}

.cursor-pointer {
    cursor: pointer;
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

[data-theme="dark"] .conversation-email,
[data-theme="dark"] .conversation-time {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .conversation-preview {
    color: var(--dm-text-secondary, #cbd5e1) !important;
}

[data-theme="dark"] .avatar-circle {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
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

[data-theme="dark"] #chat-input-container {
    background: var(--dm-card-bg, #1e293b) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
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

[data-theme="dark"] .chat-status-filter {
    background: var(--dm-bg-secondary, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .chat-status-filter:focus {
    border-color: #60a5fa !important;
    box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2) !important;
}

[data-theme="dark"] .chat-status-filter option {
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

[data-theme="dark"] .empty-state-icon {
    background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #2563eb 100%) !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .empty-icon {
    background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #2563eb 100%) !important;
    color: #60a5fa !important;
}

/* File Size Warning Modal Styles */
#fileSizeWarningModal .file-size-warning-modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

#fileSizeWarningModal .file-size-warning-modal-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%) !important;
    border-bottom: none;
    padding: 1.5rem;
}

#fileSizeWarningModal .file-size-warning-icon {
    font-size: 4rem;
    color: #f59e0b;
    margin-bottom: 1rem;
}

#fileSizeWarningModal .file-size-warning-icon i {
    display: inline-block;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

#fileSizeWarningModal .file-size-warning-alert {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #f59e0b;
    border-radius: 8px;
    color: #92400e;
}

#fileSizeWarningModal .file-size-warning-list {
    max-height: 200px;
    overflow-y: auto;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

#fileSizeWarningModal .file-size-warning-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    border-left: 3px solid #f59e0b;
    transition: all 0.2s ease;
}

#fileSizeWarningModal .file-size-warning-item:last-child {
    margin-bottom: 0;
}

#fileSizeWarningModal .file-size-warning-item:hover {
    background: #fff7ed;
    transform: translateX(4px);
}

#fileSizeWarningModal .file-size-warning-name {
    font-weight: 500;
    color: #1e293b;
    word-break: break-word;
}

#fileSizeWarningModal .file-size-warning-list::-webkit-scrollbar {
    width: 6px;
}

#fileSizeWarningModal .file-size-warning-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

#fileSizeWarningModal .file-size-warning-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

#fileSizeWarningModal .file-size-warning-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

#fileSizeWarningModal .btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

#fileSizeWarningModal .btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
}

#fileSizeWarningModal .btn-warning:active {
    transform: translateY(0);
}

/* Dark Mode Styles for File Size Warning Modal */
[data-theme="dark"] #fileSizeWarningModal .file-size-warning-modal-content {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-modal-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-icon {
    color: #fbbf24 !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-alert {
    background: linear-gradient(135deg, #78350f 0%, #92400e 100%);
    border-color: #f59e0b;
    color: #fef3c7;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-list {
    background: var(--dm-bg-secondary, #0f172a) !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-item {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-left-color: #f59e0b !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-item:hover {
    background: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-name {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-list::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #0f172a) !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-list::-webkit-scrollbar-thumb {
    background: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] #fileSizeWarningModal .file-size-warning-list::-webkit-scrollbar-thumb:hover {
    background: var(--dm-text-muted, #64748b) !important;
}

[data-theme="dark"] #fileSizeWarningModal .modal-body {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #fileSizeWarningModal .modal-body p {
    color: var(--dm-text-secondary, #cbd5e1) !important;
}
</style>
@endsection

