@extends('layout.admin.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-primary"><i class="bi bi-chat-dots"></i> Live Chat Conversations</h2>
    </div>

    <div class="row">
        <!-- Conversations List -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Conversations</h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <div class="input-group">
                            <input type="text" id="search-conversations" class="form-control" placeholder="Search patients...">
                            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                        </div>
                        <div class="mt-2">
                            <select id="status-filter" class="form-select form-select-sm">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div id="conversations-list" style="max-height: 600px; overflow-y: auto;">
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
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" id="chat-patient-name">
                        <i class="bi bi-person me-2"></i>Select a conversation
                    </h5>
                    <div id="chat-actions" style="display: none;">
                        <select id="conversation-status" class="form-select form-select-sm me-2" style="width: auto; display: inline-block;">
                            <option value="active">Active</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="chat-messages" style="height: 500px; overflow-y: auto; padding: 15px; background: #f8f9fa;">
                        <div class="text-center text-muted p-4">
                            <i class="bi bi-chat-left-text" style="font-size: 3rem;"></i>
                            <p class="mt-3">Select a conversation to start chatting</p>
                        </div>
                    </div>
                    <div class="p-3 border-top" id="chat-input-container" style="display: none;">
                        <div class="input-group">
                            <input type="text" id="chat-input" class="form-control" placeholder="Type your message..." autocomplete="off">
                            <button class="btn btn-primary" id="send-message-btn" type="button">
                                <i class="bi bi-send-fill"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentConversationId = null;
let pollingInterval = null;
let lastMessageId = null;

async function loadConversations() {
    const status = document.getElementById('status-filter').value;
    const search = document.getElementById('search-conversations').value;
    const params = new URLSearchParams({ status, search });
    
    try {
        const response = await fetch(`{{ route('admin-chat.conversations') }}?${params}`);
        const data = await response.json();
        
        const listEl = document.getElementById('conversations-list');
        if (data.conversations.length === 0) {
            listEl.innerHTML = '<div class="text-center text-muted p-4">No conversations found</div>';
            return;
        }
        
        listEl.innerHTML = data.conversations.map(conv => `
            <div class="conversation-item p-3 border-bottom cursor-pointer" 
                 data-conversation-id="${conv.id}"
                 onclick="selectConversation(${conv.id}, '${conv.patient_name}')">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${conv.patient_name}</h6>
                        <small class="text-muted">${conv.patient_email}</small>
                        ${conv.staff_name ? `<small class="text-info d-block">Staff: ${conv.staff_name}</small>` : ''}
                        ${conv.last_message ? `<p class="mb-0 mt-1 text-truncate" style="max-width: 200px;">${conv.last_message}</p>` : ''}
                    </div>
                    <div class="text-end">
                        ${conv.unread_count > 0 ? `<span class="badge bg-danger">${conv.unread_count}</span>` : ''}
                        <small class="text-muted d-block">${formatDate(conv.last_message_at || conv.created_at)}</small>
                    </div>
                </div>
            </div>
        `).join('');
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

async function selectConversation(id, patientName) {
    currentConversationId = id;
    document.getElementById('chat-patient-name').innerHTML = `<i class="bi bi-person me-2"></i>${patientName}`;
    document.getElementById('chat-actions').style.display = 'block';
    document.getElementById('chat-input-container').style.display = 'block';
    
    // Update active state
    document.querySelectorAll('.conversation-item').forEach(el => {
        el.classList.remove('bg-light');
    });
    document.querySelector(`[data-conversation-id="${id}"]`).classList.add('bg-light');
    
    await loadMessages(id);
    startPolling();
}

async function loadMessages(conversationId) {
    try {
        const response = await fetch(`{{ url('/admin/chat/conversations') }}/${conversationId}/messages`);
        const data = await response.json();
        
        const messagesEl = document.getElementById('chat-messages');
        messagesEl.innerHTML = '';
        
        data.messages.forEach(msg => {
            addMessage(msg.message, msg.sender_type, msg.sender_name);
        });
        
        lastMessageId = data.messages.length > 0 ? data.messages[data.messages.length - 1].id : null;
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

function addMessage(text, senderType, senderName) {
    const messagesEl = document.getElementById('chat-messages');
    const div = document.createElement('div');
    const isAdmin = senderType === 'admin';
    
    div.className = `mb-3 ${isAdmin ? 'text-end' : ''}`;
    div.innerHTML = `
        <div class="d-inline-block ${isAdmin ? 'bg-primary text-white' : 'bg-white'} p-2 rounded" style="max-width: 70%;">
            ${!isAdmin ? `<small class="text-muted d-block">${senderName}</small>` : ''}
            <div>${text}</div>
            ${isAdmin ? `<small class="text-white-50 d-block mt-1">${senderName}</small>` : ''}
        </div>
    `;
    
    messagesEl.appendChild(div);
    messagesEl.scrollTop = messagesEl.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message || !currentConversationId) return;
    
    addMessage(message, 'admin', 'You');
    input.value = '';
    
    try {
        const response = await fetch(`{{ url('/admin/chat/conversations') }}/${currentConversationId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        if (data.success) {
            lastMessageId = data.message.id;
            loadConversations(); // Refresh list
        }
    } catch (error) {
        console.error('Error sending message:', error);
    }
}

function startPolling() {
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(async () => {
        if (!currentConversationId) return;
        try {
            const response = await fetch(`{{ route('admin-chat.messages', ':id') }}`.replace(':id', currentConversationId));
            const data = await response.json();
            
            data.messages.forEach(msg => {
                if (msg.id > lastMessageId) {
                    addMessage(msg.message, msg.sender_type, msg.sender_name);
                    lastMessageId = msg.id;
                }
            });
        } catch (error) {
            console.error('Error polling:', error);
        }
    }, 3000);
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
        const response = await fetch(`{{ url('/admin/chat/conversations') }}/${currentConversationId}/status`, {
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
.conversation-item:hover {
    background-color: #f8f9fa !important;
    cursor: pointer;
}
.cursor-pointer {
    cursor: pointer;
}
</style>
@endsection

