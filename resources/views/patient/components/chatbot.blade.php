@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
<style>
    .chatbot-toggle-btn {
        position: fixed !important;
        right: 24px;
        bottom: 24px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #2196F3;
        color: white;
        display: flex !important;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(33,150,243,0.4);
        cursor: pointer;
        z-index: 1000 !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        padding: 8px;
        user-select: none;
        touch-action: none;
    }

    .chatbot-toggle-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 14px 36px rgba(33,150,243,0.45);
        background: #1976D2;
    }
    
    .chatbot-toggle-btn.dragging {
        cursor: grabbing;
        transition: none;
        box-shadow: 0 15px 40px rgba(33, 150, 243, 0.6), 0 0 0 6px rgba(33, 150, 243, 0.2);
        animation: none;
        transform: scale(1.1);
    }
    
    .chatbot-toggle-btn:active {
        transform: scale(0.95);
    }
    
    .chatbot-toggle-btn.dragged {
        /* When dragged, allow inline styles to override */
        right: auto !important;
        bottom: auto !important;
        left: auto !important;
        top: auto !important;
    }
    
    .chatbot-toggle-btn.dragged[style*="left"] {
        right: auto !important;
    }
    
    .chatbot-toggle-btn.dragged[style*="right"] {
        left: auto !important;
    }

    .chatbot-unread-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        min-width: 24px;
        height: 24px;
        padding: 0 7px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
        color: white;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.7), 0 0 0 3px rgba(239, 68, 68, 0.3);
        z-index: 10;
        animation: messengerBadgePulse 1.5s ease-in-out infinite;
        line-height: 1;
    }

    @keyframes messengerBadgePulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 3px 10px rgba(239, 68, 68, 0.6), 0 0 0 2px rgba(239, 68, 68, 0.3);
        }
        50% {
            transform: scale(1.15);
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.8), 0 0 0 4px rgba(239, 68, 68, 0.4);
        }
    }

    .chatbot-toggle-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    @media (max-width: 992px) {
        body.mobile-menu-open .chatbot-toggle-btn,
        body.mobile-menu-open .chatbot-widget {
            opacity: 0 !important;
            pointer-events: none !important;
            visibility: hidden !important;
        }
    }

    .chatbot-widget {
        position: fixed !important;
        right: 24px !important;
        left: auto !important;
        bottom: 92px !important;
        width: 360px;
        min-width: 320px;
        max-width: 420px;
        height: 500px;
        min-height: 400px;
        max-height: calc(100vh - 120px);
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 25px 70px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.05);
        overflow: hidden;
        display: none;
        flex-direction: column;
        z-index: 1000;
        resize: both;
        cursor: default;
        opacity: 0;
        transform: translateY(20px) scale(0.9);
        /* Transition removed - using animations instead to prevent conflicts */
    }
    
    .chatbot-widget.resizing {
        transition: none !important;
    }
    
    /* Opening animation */
    .chatbot-widget.opening {
        display: flex !important;
        animation: chatbotOpen 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        will-change: opacity, transform;
    }
    
    @keyframes chatbotOpen {
        0% {
            opacity: 0;
            transform: translateY(20px) scale(0.9);
        }
        50% {
            transform: translateY(-5px) scale(1.02);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* Closing animation */
    .chatbot-widget.closing {
        animation: chatbotClose 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        will-change: opacity, transform;
    }
    
    @keyframes chatbotClose {
        0% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        100% {
            opacity: 0;
            transform: translateY(20px) scale(0.9);
        }
    }
    
    /* Open state - no animation, just visible */
    .chatbot-widget.open { 
        display: flex !important;
        opacity: 1 !important;
        transform: translateY(0) scale(1) !important;
        animation: none !important;
        will-change: auto;
        visibility: visible !important;
    }
    
    /* Ensure widget doesn't get hidden when open */
    .chatbot-widget.open[style*="display: none"] {
        display: flex !important;
    }

    .chatbot-header {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 50%, #1565C0 100%);
        color: #fff;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        cursor: move;
        user-select: none;
    }
    
    .chatbot-header:active {
        cursor: grabbing;
    }

    .chatbot-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
    }

    .chatbot-title .badge-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #A5D6A7;
        box-shadow: 0 0 0 4px rgba(165,214,167,0.25);
        transition: all 0.3s ease;
    }

    .chatbot-title .badge-dot.online {
        background: #A5D6A7;
        box-shadow: 0 0 0 4px rgba(165,214,167,0.25);
    }

    .chatbot-title .badge-dot.offline {
        background: #ef4444;
        box-shadow: 0 0 0 4px rgba(239,68,68,0.25);
    }

    .chatbot-body {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 12px;
        gap: 0;
        overflow: hidden;
    }

    .chatbot-messages {
        flex: 1;
        min-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 12px 8px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 10px;
    }
    
    /* Custom scrollbar for messages */
    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .chatbot-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .chatbot-messages::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
    
    .chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    .message-wrapper {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 12px;
        width: 100%;
        background: none !important;
        border: none !important;
        padding: 0 !important;
        box-shadow: none !important;
    }
    
    .message {
        max-width: 82%;
        padding: 12px 14px;
        border-radius: 16px;
        font-size: 0.92rem;
        line-height: 1.6rem;
        word-wrap: break-word;
        word-break: break-word;
        white-space: pre-wrap;
        position: relative;
    }
    
    .message-time {
        font-size: 0.7rem;
        color: #94a3b8;
        padding: 0 4px;
        margin-top: 4px;
        font-weight: 500;
        display: block;
        width: 100%;
    }
    
    .message-wrapper:has(.message.user) {
        align-items: flex-end;
    }
    
    .message-wrapper:has(.message.user) .message-time {
        text-align: right;
        padding-right: 4px;
    }
    
    .message-wrapper:has(.message.bot) .message-time,
    .message-wrapper:has(.message.staff) .message-time,
    .message-wrapper:has(.message.admin) .message-time {
        text-align: left;
        padding-left: 4px;
    }
    
    .message-wrapper:has(.message.bot),
    .message-wrapper:has(.message.staff),
    .message-wrapper:has(.message.admin) {
        align-items: flex-start;
    }

    .message.bot,
    .message.staff,
    .message.admin {
        background: linear-gradient(135deg, #f5f9ff 0%, #e8f4fd 100%);
        color: #263238;
        border: 1px solid #e3f2fd;
        align-self: flex-start;
        text-align: left;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .message.bot .bullet-item {
        display: block;
        padding-left: 1.2em;
        text-indent: -1.2em;
        margin: 0.3em 0;
    }

    .message.bot .section-header {
        font-weight: 600;
        margin-top: 0.8em;
        margin-bottom: 0.3em;
        display: block;
    }

    .message.bot .section-header:first-child {
        margin-top: 0;
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
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 8px 12px;
        transition: all 0.2s ease;
    }

    .message.bot .attachment-item {
        background: rgba(0, 0, 0, 0.05);
    }

    .attachment-item-image {
        padding: 8px;
        background: transparent;
    }

    .message.bot .attachment-item-image {
        background: transparent;
    }

    .attachment-link {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: inherit;
        width: 100%;
    }

    .attachment-link-image {
        width: auto;
        cursor: pointer;
    }

    .attachment-link:hover {
        opacity: 0.8;
    }

    .attachment-link-image:hover {
        opacity: 1;
    }

    .attachment-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 6px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .attachment-image:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .attachment-name {
        font-size: 0.75rem;
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

    .message.user {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: #fff;
        align-self: flex-end;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
    }

    /* Remove triangle pointers from message bubbles */
    .message.user::after,
    .message.bot::before,
    .message.staff::before,
    .message.admin::before {
        display: none !important;
    }

    .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        flex-shrink: 0;
        margin-bottom: 8px;
    }

    .chip {
        background: #e3f2fd;
        color: #1976D2;
        border: 1px solid #bbdefb;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .chip:hover { 
        background: #d2e9fb; 
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(33, 150, 243, 0.15);
    }

    .chatbot-input {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 0;
        margin-bottom: 8px;
        flex-shrink: 0;
    }

    .chatbot-request {
    margin-bottom: 10px;
    background: #f1f5f9;
    border: 1px solid #d0d7e2;
    border-radius: 10px;
    padding: 12px;
    }

    .chatbot-request .request-title {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .chatbot-request .request-subtitle {
        margin: 4px 0 8px;
        color: #475569;
        font-size: 0.9rem;
    }

    .chatbot-request textarea {
        width: 100%;
        border-radius: 8px;
    border: 1px solid #cbd5e1;
        padding: 8px 10px;
        resize: vertical;
        font-size: 0.95rem;
    background: #f8fafc;
    }

    .chatbot-request .request-btn {
        margin-top: 8px;
        width: 100%;
    }

.chatbot-request .request-feedback {
    margin-top: 6px;
    font-size: 0.9rem;
}

[data-theme="dark"] .chatbot-request {
    background: #111827;
    border-color: #1f2937;
}

[data-theme="dark"] .chatbot-request .request-title {
    color: #e5e7eb;
}

[data-theme="dark"] .chatbot-request .request-subtitle {
    color: #cbd5e1;
}

[data-theme="dark"] .chatbot-request textarea {
    background: #0f172a;
    color: #e5e7eb;
    border-color: #334155;
}

.chatbot-request .request-cancel-btn {
    margin-top: 6px;
    width: 100%;
    background: #e2e8f0;
    border: 1px solid #cbd5e1;
    color: #0f172a;
}

.chatbot-request .request-cancel-btn:hover {
    background: #cbd5e1;
}

    .chatbot-request .request-feedback {
        margin-top: 6px;
        font-size: 0.9rem;
    }

.chatbot-request-toggle {
    margin: 6px 0 10px;
}

.request-toggle-btn {
    width: 100%;
    background: #2563eb;
    border: 1px solid #1d4ed8;
    color: #f8fafc;
    transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}

.request-toggle-btn:hover {
    background: #1d4ed8;
}

.request-toggle-btn:disabled,
.request-toggle-btn[disabled] {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #94a3b8;
    cursor: not-allowed;
    box-shadow: none;
    pointer-events: none;
}

.request-toggle-btn:disabled:hover,
.request-toggle-btn[disabled]:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #94a3b8;
}

[data-theme="dark"] .chatbot-request {
    background: #0f172a;
    border-color: #1e293b;
}

[data-theme="dark"] .chatbot-request .request-title {
    color: #e2e8f0;
}

[data-theme="dark"] .chatbot-request .request-subtitle {
    color: #cbd5e1;
}

[data-theme="dark"] .chatbot-request textarea {
    background: #111827;
    color: #e2e8f0;
    border-color: #334155;
}

[data-theme="dark"] .request-toggle-btn {
    background: #1d4ed8;
    border-color: #1e3a8a;
    color: #e2e8f0;
}

[data-theme="dark"] .request-toggle-btn:hover {
    background: #1e3a8a;
}

[data-theme="dark"] .request-toggle-btn:disabled,
[data-theme="dark"] .request-toggle-btn[disabled] {
        background: #2b3442;
    border-color: #334155;
    color: #6b7280;
    cursor: not-allowed;
    box-shadow: none;
    pointer-events: none;
}

[data-theme="dark"] .request-toggle-btn:disabled:hover,
[data-theme="dark"] .request-toggle-btn[disabled]:hover {
        background: #2b3442;
        border-color: #334155;
        color: #6b7280;
}

[data-theme="dark"] .chatbot-request .request-cancel-btn {
    background: #1f2937;
    border-color: #334155;
    color: #e2e8f0;
}

[data-theme="dark"] .chatbot-request .request-cancel-btn:hover {
    background: #111827;
}

    .chatbot-input input[type="text"] {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #dfe7ef;
        border-radius: 10px;
        outline: none;
        transition: border 0.2s ease, box-shadow 0.2s ease;
        font-size: 0.875rem;
    }

    .chatbot-input input[type="text"]:focus {
        border: 1px solid #90caf9;
        box-shadow: 0 0 0 3px rgba(144,202,249,0.25);
    }

    .chatbot-input input[type="text"]::placeholder {
        color: #9ca3af;
    }

    .send-btn {
        background: #2196F3;
        color: #fff;
        border: none;
        padding: 10px 12px;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .send-btn:hover { 
        background: #1976D2;
    }

    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 10px 14px;
        background: #E3F2FD;
        border-radius: 16px;
        margin-bottom: 8px;
        max-width: fit-content;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2196F3;
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
        30% { transform: translateY(-10px); opacity: 1; }
    }

    @media (max-width: 768px) {
        .chatbot-widget {
            right: 16px !important;
            left: 16px !important;
            width: auto !important;
            min-width: calc(100vw - 32px) !important;
            max-width: calc(100vw - 32px);
            height: calc(100vh - 120px) !important;
            min-height: 400px !important;
            max-height: calc(100vh - 120px);
            bottom: 88px !important;
        }
        
        .chatbot-widget.resize {
            resize: none;
        }
        
        .chatbot-toggle-btn:not(.dragged) {
            right: 20px !important;
            bottom: 20px !important;
            width: 56px;
            height: 56px;
        }
        
        .chatbot-toggle-btn.dragged {
            width: 56px;
            height: 56px;
        }
        
        .chatbot-messages {
            flex: 1;
            min-height: 200px;
            padding: 12px 8px;
        }
        
        .message {
            max-width: 85%;
            padding: 10px 14px;
            font-size: 0.875rem;
        }
        
        .chatbot-header {
            padding: 12px 14px;
        }
        
        .chatbot-body {
            height: 100%;
            padding: 10px;
        }
        
        .chatbot-input {
            padding: 8px 0;
            margin-bottom: 8px;
        }
        
        .chatbot-tabs {
            padding: 8px 10px 12px;
            margin-top: auto;
        }
        
        .chatbot-input input[type="text"] {
            padding: 10px 14px;
            font-size: 0.875rem;
        }
        
        .send-btn {
            padding: 10px;
            width: 40px;
            height: 40px;
        }
        
        .chip {
            padding: 8px 12px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .chatbot-widget {
            right: 16px !important;
            left: 16px !important;
            bottom: 80px !important;
            max-width: calc(100vw - 32px);
            border-radius: 12px;
        }
        
        .chatbot-toggle-btn:not(.dragged) {
            right: 16px !important;
            bottom: 16px !important;
            width: 52px;
            height: 52px;
            opacity: 0.9;
        }
        
        .chatbot-toggle-btn.dragged {
            width: 52px;
            height: 52px;
        }
        
        .chatbot-toggle-btn:active,
        .chatbot-toggle-btn:focus {
            opacity: 1;
        }
        
        .chatbot-messages {
            flex: 1;
            min-height: 180px;
            padding: 10px 8px;
            gap: 6px;
        }
        
        .message {
            max-width: 88%;
            padding: 8px 12px;
            font-size: 0.85rem;
            line-height: 1.4rem;
            border-radius: 12px;
        }
        
        .message.bot {
            border-radius: 12px 12px 12px 4px;
        }
        
        .message.user {
            border-radius: 12px 12px 4px 12px;
        }
        
        .chatbot-header {
            padding: 10px 12px;
        }
        
        .chatbot-title {
            font-size: 0.9rem;
        }
        
        .chatbot-title .badge-dot {
            width: 8px;
            height: 8px;
        }
        
        .chatbot-body {
            height: 100%;
            padding: 8px;
        }
        
        .chatbot-input {
            padding: 8px 0;
            margin-bottom: 6px;
        }
        
        .chatbot-tabs {
            padding: 6px 8px 10px;
            margin-top: auto;
        }
        
        .chatbot-input input[type="text"] {
            padding: 8px 12px;
            font-size: 0.8rem;
            border-radius: 20px;
        }
        
        .send-btn {
            width: 36px;
            height: 36px;
            padding: 8px;
            border-radius: 50%;
        }
        
        .chip {
            padding: 6px 10px;
            font-size: 0.75rem;
            border-radius: 16px;
        }
        
        .typing-indicator {
            padding: 8px 12px;
            border-radius: 12px;
        }
        
        .typing-indicator span {
            width: 6px;
            height: 6px;
        }
        
        #chatbot-close {
            width: 32px;
            height: 32px;
        }
        
        #chatbot-close i {
            font-size: 1rem;
        }
    }

    @media (max-width: 360px) {
        .chatbot-widget {
            right: 8px !important;
            left: 8px !important;
            bottom: 72px !important;
            max-width: calc(100vw - 16px);
        }
        
        .chatbot-toggle-btn {
            right: 8px !important;
            bottom: 8px !important;
            width: 48px;
            height: 48px;
        }
        
        .chatbot-messages {
            flex: 1;
            min-height: 160px;
            padding: 8px 6px;
        }
        
        .message {
            max-width: 90%;
            padding: 6px 10px;
            font-size: 0.8rem;
        }
        
        .chatbot-body {
            height: 100%;
            padding: 8px;
        }
        
        .chatbot-input {
            padding: 6px 0;
            margin-bottom: 6px;
        }
        
        .chatbot-tabs {
            padding: 6px 8px 10px;
            margin-top: auto;
        }
    }

    @media (max-width: 768px) and (orientation: landscape) {
        .chatbot-widget {
            max-height: calc(100vh - 100px);
            max-height: calc(100dvh - 100px); /* Dynamic viewport height */
        }
        
        .chatbot-messages {
            height: 200px;
            max-height: calc(100vh - 280px);
            max-height: calc(100dvh - 280px); /* Dynamic viewport height */
        }
    }

    @media (max-width: 480px) and (orientation: landscape) {
        .chatbot-widget {
            bottom: 60px !important;
            max-height: calc(100vh - 80px);
            max-height: calc(100dvh - 80px); /* Dynamic viewport height */
        }
        
        .chatbot-messages {
            flex: 1;
            min-height: 160px;
            max-height: calc(100vh - 260px);
            max-height: calc(100dvh - 260px); /* Dynamic viewport height */
        }
        
        .chatbot-body {
            height: 100%;
        }
        
        .chatbot-tabs {
            padding: 8px 10px 12px;
            margin-top: auto;
        }
        
        .chatbot-toggle-btn {
            bottom: 8px !important;
        }
    }

    /* Fix for mobile browsers with address bar */
    @media (max-width: 768px) {
        .chatbot-widget {
            max-height: calc(100vh - 100px);
            max-height: calc(100dvh - 100px); /* Dynamic viewport height */
        }
        
        .chatbot-messages {
            flex: 1;
            min-height: 200px;
            max-height: calc(100vh - 300px);
            max-height: calc(100dvh - 300px); /* Dynamic viewport height */
        }
        
        .chatbot-body {
            height: 100%;
        }
        
        .chatbot-tabs {
            padding: 8px 10px 0;
            margin-top: auto;
        }
    }

    /* Ensure touch targets are at least 44x44px for accessibility */
    @media (max-width: 768px) {
        .chip,
        .send-btn,
        #chatbot-close {
            min-width: 44px;
            min-height: 44px;
        }
    }

    /* Prevent text size adjustment on iOS */
    @media (max-width: 768px) {
        .chatbot-input input[type="text"] {
            -webkit-text-size-adjust: 100%;
            font-size: 16px !important; /* Prevents zoom on iOS */
        }
    }

    @media (max-width: 480px) {
        .chatbot-input input[type="text"] {
            font-size: 16px !important; /* Prevents zoom on iOS */
        }
    }


    .chatbot-tabs {
        display: flex;
        gap: 8px;
        padding: 8px 12px 12px;
        border-top: 1px solid #eef2f5;
        margin-top: auto;
        flex-shrink: 0;
    }

    .chatbot-tab {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #dfe7ef;
        border-radius: 8px;
        background: #f8f9fa;
        color: #64748b;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .chatbot-tab:hover {
        background: #e9ecef;
        border-color: #90caf9;
    }

    .chatbot-tab.active {
        background: #2196F3;
        color: #fff;
        border-color: #2196F3;
    }

    .chatbot-tab.active:hover {
        background: #1976D2;
        border-color: #1976D2;
    }
    
    /* Close button styling */
    #chatbot-close {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: none !important;
        border-radius: 10px !important;
    }
    
    #chatbot-close:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        transform: scale(1.05);
    }
    
    #chatbot-close:active {
        transform: scale(0.95);
    }
    
    #chatbot-close i {
        color: #ffffff;
        font-size: 1.1rem;
    }

    [data-theme="dark"] .chatbot-widget {
        background: #1e293b;
        color: #f1f5f9;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    [data-theme="dark"] .chatbot-body {
        background: #1e293b;
    }

    [data-theme="dark"] .chatbot-messages {
        background: #1e293b;
        border-bottom-color: rgba(148, 163, 184, 0.2);
    }

    [data-theme="dark"] .message.bot,
    [data-theme="dark"] .message.staff,
    [data-theme="dark"] .message.admin {
        background: #334155;
        border: 1px solid #475569;
        color: #f1f5f9;
    }

    /* Remove triangle pointers in dark mode as well */
    [data-theme="dark"] .message.user::after,
    [data-theme="dark"] .message.bot::before,
    [data-theme="dark"] .message.staff::before,
    [data-theme="dark"] .message.admin::before {
        display: none !important;
    }

    [data-theme="dark"] .chips .chip {
        background: #334155;
        color: #e2e8f0;
        border-color: #475569;
    }

    [data-theme="dark"] .chips .chip:hover {
        background: #475569;
        border-color: #60a5fa;
        color: #f1f5f9;
    }

    [data-theme="dark"] .chatbot-tabs {
        border-top-color: rgba(148, 163, 184, 0.2);
    }

    [data-theme="dark"] .chatbot-tab {
        background: #334155;
        border-color: #475569;
        color: #cbd5e1;
    }

    [data-theme="dark"] .chatbot-tab:hover {
        background: #475569;
        border-color: #60a5fa;
    }

    [data-theme="dark"] .chatbot-tab.active {
        background: #2196F3;
        color: #fff;
        border-color: #2196F3;
    }

    [data-theme="dark"] .chatbot-tab.active:hover {
        background: #1976D2;
        border-color: #1976D2;
    }

    [data-theme="dark"] .chatbot-input {
        background: #1e293b;
        border-top-color: rgba(148, 163, 184, 0.2);
    }

    [data-theme="dark"] .chatbot-input input[type="text"] {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }

    [data-theme="dark"] .chatbot-input input[type="text"]::placeholder {
        color: #94a3b8;
    }

    [data-theme="dark"] .chatbot-input input[type="text"]:focus {
        border-color: #60a5fa;
        background: #1e293b;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2);
    }

    [data-theme="dark"] .typing-indicator {
        background: #334155;
    }

    [data-theme="dark"] .typing-indicator::before {
        border-right-color: #334155;
    }

    [data-theme="dark"] .typing-indicator span {
        background: #60a5fa;
    }

    [data-theme="dark"] .chatbot-toggle-btn {
        box-shadow: 0 10px 30px rgba(33, 150, 243, 0.4);
    }

    [data-theme="dark"] .chatbot-messages::-webkit-scrollbar-thumb {
        background: #475569;
    }

    [data-theme="dark"] .chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    [data-theme="dark"] .message-time {
        color: #94a3b8;
    }

    [data-theme="dark"] .message.bot .attachment-item,
    [data-theme="dark"] .message.staff .attachment-item,
    [data-theme="dark"] .message.admin .attachment-item {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    [data-theme="dark"] .message.bot .attachment-item:hover,
    [data-theme="dark"] .message.staff .attachment-item:hover,
    [data-theme="dark"] .message.admin .attachment-item:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    [data-theme="dark"] .attachment-name {
        color: #e2e8f0;
    }

    [data-theme="dark"] .attachment-size {
        color: #94a3b8;
    }

    /* Adjust position when scroll-to-top button present */
    @media (min-width: 769px) {
        .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn {
            bottom: 90px;
        }

        .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn + .chatbot-widget {
            bottom: 158px;
        }
    }
    
    @media (max-width: 768px) {
        .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn {
            bottom: 20px;
        }

        .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn + .chatbot-widget {
            bottom: 88px;
        }
    }
    
    @media (max-width: 480px) {
        .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn {
            bottom: 16px;
        }

        .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn + .chatbot-widget {
            bottom: 80px;
        }
    }

    /* Image Modal for Chatbot Attachments */
    .chatbot-image-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .chatbot-image-modal.show {
        opacity: 1;
    }

    .chatbot-image-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .chatbot-image-modal-content {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .chatbot-image-modal-close {
        position: absolute;
        top: -50px;
        right: 0;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .chatbot-image-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .chatbot-image-modal-img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    }

    .chatbot-image-modal-title {
        color: white;
        margin-top: 1rem;
        font-size: 1.25rem;
        font-weight: 600;
        text-align: center;
    }

    @media (max-width: 768px) {
        .chatbot-image-modal-content {
            max-width: 95vw;
            max-height: 95vh;
        }

        .chatbot-image-modal-close {
            top: -40px;
            width: 35px;
            height: 35px;
            font-size: 1.25rem;
        }

        .chatbot-image-modal-img {
            max-height: 75vh;
        }

        .chatbot-image-modal-title {
            font-size: 1rem;
        }
    }
</style>

<div id="chatbot-toggle" class="chatbot-toggle-btn" aria-label="Open chat" title="Chat with us">
    <img src="{{ asset('images/chatbot-logo_3.png') }}" alt="ToothTalk Assistant">
    <span class="chatbot-unread-badge" id="patient-chat-badge" style="display: none;">0</span>
</div>

<div id="chatbot" class="chatbot-widget" role="dialog" aria-modal="false" aria-labelledby="chatbotTitle">
    <div class="chatbot-header">
        <div class="chatbot-title">
            <span class="badge-dot online"></span>
            <span id="chatbotTitle">Live Chat</span>
        </div>
        <button id="chatbot-close" class="send-btn" aria-label="Close chat" title="Close" style="background:#ffffff22;border:1px solid #ffffff33;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="chatbot-body">
        <div id="chatbot-messages" class="chatbot-messages" aria-live="polite"></div>
        <div class="chips" id="chatbot-chips"></div>
        <div class="chatbot-request-toggle" id="chatbot-request-toggle" style="display:none;">
            <button class="send-btn request-toggle-btn" id="chatbot-request-open">
                <i class="bi bi-envelope-open me-1"></i> Request chat access
            </button>
        </div>
        <div class="chatbot-request" id="chatbot-enable-request" style="display:none;">
            <p class="request-title">Request to re-enable live chat</p>
            <p class="request-subtitle">Tell us why:</p>
            <textarea id="chatbot-request-reason" rows="3" maxlength="500" placeholder="Example: I need to follow up on my treatment plan."></textarea>
            <button id="chatbot-request-submit" class="send-btn request-btn" aria-label="Request enable">
                <i class="bi bi-send-fill me-1"></i> Send request
            </button>
            <button id="chatbot-request-cancel" class="send-btn request-cancel-btn" type="button">
                Cancel
            </button>
            <div class="request-feedback text-success" id="chatbot-request-feedback" style="display:none;"></div>
        </div>
        <div class="chatbot-input" id="chatbot-input-container" style="display:none;">
            <input id="chatbot-input" type="text" placeholder="Ask about services, hours, pricing..." autocomplete="off" />
            <button id="chatbot-send" class="send-btn" aria-label="Send message">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
        <div class="chatbot-tabs" id="chatbot-tabs">
            <button id="tab-live-chat" class="chatbot-tab active" data-tab="live-chat">
                <i class="bi bi-chat-dots me-1"></i> Live Chat
            </button>
            <button id="tab-faqs" class="chatbot-tab" data-tab="faqs">
                <i class="bi bi-question-circle me-1"></i> FAQs
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const widget = document.getElementById('chatbot');
        const closeBtn = document.getElementById('chatbot-close');
        const messagesEl = document.getElementById('chatbot-messages');
        const inputEl = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send');
        const chipsEl = document.getElementById('chatbot-chips');
        const tabLiveChat = document.getElementById('tab-live-chat');
        const tabFaqs = document.getElementById('tab-faqs');
        const titleEl = document.getElementById('chatbotTitle');
        const badgeDotEl = widget ? widget.querySelector('.chatbot-title .badge-dot') : null;

        if (!toggleBtn || !widget) {
            return;
        }
        
        const headerEl = widget.querySelector('.chatbot-header');
        
        // Widget drag and resize functionality
        const DRAG_ENABLED = false;
        let isWidgetDragging = false;
        let isWidgetResizing = false;
        let widgetDragStartX = 0;
        let widgetDragStartY = 0;
        let widgetInitialX = 0;
        let widgetInitialY = 0;
        let widgetResizeStartX = 0;
        let widgetResizeStartY = 0;
        let widgetInitialWidth = 0;
        let widgetInitialHeight = 0;
        let resizeHandle = null;

        function updateBadgeDotVisibilityForMode(mode) {
            if (!badgeDotEl) return;
            const activeMode = mode || currentMode;
            if (activeMode === 'faqs') {
                badgeDotEl.style.display = 'none';
            } else {
                badgeDotEl.style.display = '';
            }
        }
        
        // Load saved widget position and size
        function loadSavedWidgetState() {
            if (DRAG_ENABLED) {
                const saved = localStorage.getItem('chatbot-widget-state');
                if (saved) {
                    try {
                        const state = JSON.parse(saved);
                        if (state.width) widget.style.width = state.width + 'px';
                        if (state.height) widget.style.height = state.height + 'px';
                        if (state.left !== undefined) {
                            widget.style.left = state.left + 'px';
                            widget.style.right = 'auto';
                        }
                        if (state.top !== undefined) {
                            widget.style.top = state.top + 'px';
                            widget.style.bottom = 'auto';
                        }
                    } catch (e) {
                        console.error('Error loading widget state:', e);
                    }
                }
            } else {
                // Reset to default anchored position (CSS controls it)
                widget.style.left = '';
                widget.style.top = '';
                widget.style.right = '';
                widget.style.bottom = '';
                localStorage.removeItem('chatbot-widget-state');
            }
        }
        
        // Save widget position and size
        function saveWidgetState() {
            const rect = widget.getBoundingClientRect();
            const state = {
                width: rect.width,
                height: rect.height,
                left: rect.left,
                top: rect.top
            };
            localStorage.setItem('chatbot-widget-state', JSON.stringify(state));
        }
        
        // Widget drag handlers
        if (headerEl) {
            headerEl.addEventListener('mousedown', (e) => {
                if (e.target === closeBtn || closeBtn.contains(e.target)) return;
                isWidgetDragging = true;
                widgetDragStartX = e.clientX;
                widgetDragStartY = e.clientY;
                const rect = widget.getBoundingClientRect();
                widgetInitialX = rect.left;
                widgetInitialY = rect.top;
                widget.classList.add('resizing');
                e.preventDefault();
            });
            
            headerEl.addEventListener('touchstart', (e) => {
                if (e.target === closeBtn || closeBtn.contains(e.target)) return;
                const touch = e.touches[0];
                isWidgetDragging = true;
                widgetDragStartX = touch.clientX;
                widgetDragStartY = touch.clientY;
                const rect = widget.getBoundingClientRect();
                widgetInitialX = rect.left;
                widgetInitialY = rect.top;
                widget.classList.add('resizing');
                e.preventDefault();
            });
        }
        
        if (DRAG_ENABLED) document.addEventListener('mousemove', (e) => {
            if (isWidgetDragging) {
                const deltaX = e.clientX - widgetDragStartX;
                const deltaY = e.clientY - widgetDragStartY;
                let newX = widgetInitialX + deltaX;
                let newY = widgetInitialY + deltaY;
                
                // Constrain to viewport
                const maxX = window.innerWidth - widget.offsetWidth;
                const maxY = window.innerHeight - widget.offsetHeight;
                newX = Math.max(0, Math.min(newX, maxX));
                newY = Math.max(0, Math.min(newY, maxY));
                
                widget.style.left = newX + 'px';
                widget.style.top = newY + 'px';
                widget.style.right = 'auto';
                widget.style.bottom = 'auto';
            }
        });
        
        if (DRAG_ENABLED) document.addEventListener('touchmove', (e) => {
            if (isWidgetDragging) {
                const touch = e.touches[0];
                const deltaX = touch.clientX - widgetDragStartX;
                const deltaY = touch.clientY - widgetDragStartY;
                let newX = widgetInitialX + deltaX;
                let newY = widgetInitialY + deltaY;
                
                // Constrain to viewport
                const maxX = window.innerWidth - widget.offsetWidth;
                const maxY = window.innerHeight - widget.offsetHeight;
                newX = Math.max(0, Math.min(newX, maxX));
                newY = Math.max(0, Math.min(newY, maxY));
                
                widget.style.left = newX + 'px';
                widget.style.top = newY + 'px';
                widget.style.right = 'auto';
                widget.style.bottom = 'auto';
                e.preventDefault();
            }
        });
        
        if (DRAG_ENABLED) document.addEventListener('mouseup', () => {
            if (isWidgetDragging) {
                isWidgetDragging = false;
                widget.classList.remove('resizing');
                saveWidgetState();
            }
        });
        
        if (DRAG_ENABLED) document.addEventListener('touchend', () => {
            if (isWidgetDragging) {
                isWidgetDragging = false;
                widget.classList.remove('resizing');
                saveWidgetState();
            }
        });
        
        // Create resize handle
        function createResizeHandle() {
            if (resizeHandle) return;
            resizeHandle = document.createElement('div');
            resizeHandle.style.cssText = `
                position: absolute;
                bottom: 0;
                right: 0;
                width: 20px;
                height: 20px;
                cursor: nwse-resize;
                background: linear-gradient(135deg, transparent 0%, transparent 40%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.1) 60%, transparent 60%);
                z-index: 10;
            `;
            widget.appendChild(resizeHandle);
            
            const resizeMoveHandler = (e) => {
                if (isWidgetResizing) {
                    const deltaX = e.clientX - widgetResizeStartX;
                    const deltaY = e.clientY - widgetResizeStartY;
                    let newWidth = widgetInitialWidth + deltaX;
                    let newHeight = widgetInitialHeight - deltaY;
                    
                    // Constrain sizes
                    newWidth = Math.max(300, Math.min(newWidth, window.innerWidth - 20));
                    newHeight = Math.max(400, Math.min(newHeight, window.innerHeight - 20));
                    
                    widget.style.width = newWidth + 'px';
                    widget.style.height = newHeight + 'px';
                }
            };
            
            const resizeUpHandler = () => {
                if (isWidgetResizing) {
                    isWidgetResizing = false;
                    widget.classList.remove('resizing');
                    saveWidgetState();
                    document.removeEventListener('mousemove', resizeMoveHandler);
                    document.removeEventListener('mouseup', resizeUpHandler);
                }
            };
            
            resizeHandle.addEventListener('mousedown', (e) => {
                isWidgetResizing = true;
                widgetResizeStartX = e.clientX;
                widgetResizeStartY = e.clientY;
                widgetInitialWidth = widget.offsetWidth;
                widgetInitialHeight = widget.offsetHeight;
                widget.classList.add('resizing');
                document.addEventListener('mousemove', resizeMoveHandler);
                document.addEventListener('mouseup', resizeUpHandler);
                e.preventDefault();
            });
        }
        
        // Create resize handle when widget opens
        const observer = new MutationObserver(() => {
            if (widget.classList.contains('open') && !resizeHandle) {
                createResizeHandle();
            }
        });
        observer.observe(widget, { attributes: true, attributeFilter: ['class'] });
        
        // Load saved state on page load
        loadSavedWidgetState();

        // Drag functionality for chatbot button
        let isDragging = false;
        let hasDragged = false;
        let dragStartX = 0;
        let dragStartY = 0;
        let initialX = 0;
        let initialY = 0;
        let currentX = 0;
        let currentY = 0;
        const SIDE_PADDING = 16; // Padding from the edge when snapping to side

        // Load saved position from localStorage
        function loadSavedPosition() {
            if (!toggleBtn) return;
            if (!DRAG_ENABLED) {
                // Clear any saved position and stick to default CSS position (bottom-right)
                localStorage.removeItem('chatbot-button-position');
                toggleBtn.classList.remove('dragged');
                toggleBtn.style.left = '';
                toggleBtn.style.right = '';
                toggleBtn.style.top = '';
                toggleBtn.style.bottom = '';
                if (widget) updateWidgetPosition();
                updateScrollToTopPosition();
                return;
            }
            const saved = localStorage.getItem('chatbot-button-position');
            if (!saved) return;
            try {
                const pos = JSON.parse(saved);
                const padding = pos.padding || SIDE_PADDING;
                toggleBtn.style.display = 'flex';
                toggleBtn.style.visibility = 'visible';
                toggleBtn.style.opacity = '';
                if (pos.side === 'left') {
                    toggleBtn.style.left = padding + 'px';
                    toggleBtn.style.right = 'auto';
                } else {
                    toggleBtn.style.right = padding + 'px';
                    toggleBtn.style.left = 'auto';
                }
                if (pos.top !== undefined && !isNaN(pos.top)) {
                    const maxY = window.innerHeight - toggleBtn.offsetHeight;
                    const top = Math.max(padding, Math.min(Math.max(0, pos.top), maxY - padding));
                    toggleBtn.style.top = top + 'px';
                    toggleBtn.style.bottom = 'auto';
                } else {
                    toggleBtn.style.bottom = '24px';
                    toggleBtn.style.top = 'auto';
                }
                toggleBtn.classList.add('dragged');
                if (widget) updateWidgetPosition();
                updateScrollToTopPosition();
            } catch (e) {
                console.error('Error loading saved position:', e);
            }
        }

        // Snap button to nearest side (left or right) with padding
        function snapToSide() {
            // Get current position BEFORE clearing styles
            const rect = toggleBtn.getBoundingClientRect();
            const buttonWidth = toggleBtn.offsetWidth;
            const buttonHeight = toggleBtn.offsetHeight;
            
            // Calculate button center position using getBoundingClientRect
            // This always gives us the actual position regardless of CSS positioning
            const buttonCenterX = rect.left + (rect.width / 2);
            const screenCenterX = window.innerWidth / 2;
            
            // Determine which side to snap to based on button center position
            const snapToLeft = buttonCenterX < screenCenterX;
            
            console.log('Snapping button:', {
                buttonCenterX,
                screenCenterX,
                snapToLeft,
                currentLeft: rect.left,
                currentRight: window.innerWidth - rect.right
            });
            
            // Get current Y position (keep vertical position)
            let currentY = rect.top;
            // Constrain Y to viewport
            const maxY = window.innerHeight - buttonHeight;
            currentY = Math.max(SIDE_PADDING, Math.min(currentY, maxY - SIDE_PADDING));
            
            // Add smooth transition for snapping
            toggleBtn.style.transition = 'left 0.3s ease, right 0.3s ease, top 0.3s ease';
            
            // Remove dragged class temporarily to clear any CSS rules
            toggleBtn.classList.remove('dragged');
            
            // Clear any existing positioning first
            toggleBtn.style.left = '';
            toggleBtn.style.right = '';
            toggleBtn.style.top = '';
            toggleBtn.style.bottom = '';
            
            // Force reflow to ensure styles are cleared
            toggleBtn.offsetHeight;
            
            // Add dragged class back
            toggleBtn.classList.add('dragged');
            
            if (snapToLeft) {
                // Snap to left side
                toggleBtn.style.left = SIDE_PADDING + 'px';
                toggleBtn.style.right = 'auto';
                toggleBtn.style.setProperty('left', SIDE_PADDING + 'px', 'important');
                toggleBtn.style.setProperty('right', 'auto', 'important');
                console.log('Snapped to LEFT at', SIDE_PADDING + 'px');
            } else {
                // Snap to right side
                toggleBtn.style.right = SIDE_PADDING + 'px';
                toggleBtn.style.left = 'auto';
                toggleBtn.style.setProperty('right', SIDE_PADDING + 'px', 'important');
                toggleBtn.style.setProperty('left', 'auto', 'important');
                console.log('Snapped to RIGHT at', SIDE_PADDING + 'px');
            }
            
            toggleBtn.style.top = currentY + 'px';
            toggleBtn.style.bottom = 'auto';
            toggleBtn.style.setProperty('top', currentY + 'px', 'important');
            toggleBtn.style.setProperty('bottom', 'auto', 'important');
            
            // Remove transition after animation completes
            setTimeout(() => {
                toggleBtn.style.transition = '';
            }, 300);
            
            updateWidgetPosition();
            updateScrollToTopPosition();
        }

        // Save position to localStorage
        function savePosition() {
            if (!DRAG_ENABLED) return;
            const rect = toggleBtn.getBoundingClientRect();
            const centerX = rect.left + (rect.width / 2);
            const screenCenterX = window.innerWidth / 2;
            const snapToLeft = centerX < screenCenterX;
            
            const position = {
                side: snapToLeft ? 'left' : 'right',
                top: rect.top,
                padding: SIDE_PADDING
            };
            localStorage.setItem('chatbot-button-position', JSON.stringify(position));
        }

        // Update widget position relative to button
        function updateWidgetPosition() {
            if (!widget || !toggleBtn) return;
            
            // Don't update position if widget is not open or is closing
            if (!widget.classList.contains('open') && !widget.classList.contains('opening')) {
                return;
            }
            
            try {
                const rect = toggleBtn.getBoundingClientRect();
                const buttonBottom = window.innerHeight - rect.bottom;
                const buttonRight = window.innerWidth - rect.right;
                const buttonLeft = rect.left;
                const centerX = rect.left + (rect.width / 2);
                const screenCenterX = window.innerWidth / 2;
                const isOnLeft = centerX < screenCenterX;
                
                // Ensure widget stays visible during position update
                if (widget.classList.contains('open') || widget.classList.contains('opening')) {
                    widget.style.display = 'flex';
                }
                
                // During dragging, disable transitions for instant movement
                if (isDragging) {
                    widget.style.transition = 'none';
                } else {
                    // Smooth transition when not dragging (only for position, not display)
                    widget.style.transition = 'left 0.3s ease, right 0.3s ease, bottom 0.3s ease, border-radius 0.3s ease';
                }
                
                // On mobile, keep widget full width with margins
                if (window.innerWidth <= 480) {
                    widget.style.left = '16px';
                    widget.style.right = '16px';
                    widget.style.width = 'auto';
                    widget.style.bottom = (buttonBottom + 76) + 'px';
                    widget.style.top = 'auto';
                    widget.classList.remove('align-left', 'align-right');
                    widget.style.borderRadius = '16px';
                } else {
                    // Keep widget aligned with button side on desktop
                    if (isOnLeft) {
                        // Button is on left, align widget to left
                        widget.style.left = buttonLeft + 'px';
                        widget.style.right = 'auto';
                        widget.classList.add('align-left');
                        widget.classList.remove('align-right');
                        widget.style.borderRadius = '16px';
                    } else {
                        // Button is on right, align widget to right
                        widget.style.right = buttonRight + 'px';
                        widget.style.left = 'auto';
                        widget.classList.add('align-right');
                        widget.classList.remove('align-left');
                        widget.style.borderRadius = '16px';
                    }
                    widget.style.bottom = (buttonBottom + 76) + 'px';
                    widget.style.top = 'auto';
                }
            } catch (e) {
                console.error('Error updating widget position:', e);
            }
        }

        // Update scroll-to-top button position based on chatbot position
        function updateScrollToTopPosition() {
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');
            if (!scrollToTopBtn) return;
            
            const rect = toggleBtn.getBoundingClientRect();
            const centerX = rect.left + (rect.width / 2);
            const screenCenterX = window.innerWidth / 2;
            const isOnRight = centerX >= screenCenterX;
            
            // If chatbot is on the right side, move scroll-to-top button to lower right
            // Otherwise, keep it in its default position
            if (isOnRight) {
                // Chatbot is on right, place scroll-to-top in lower right
                if (window.innerWidth <= 480) {
                    scrollToTopBtn.style.right = '16px';
                    scrollToTopBtn.style.bottom = '80px';
                } else if (window.innerWidth <= 768) {
                    scrollToTopBtn.style.right = '20px';
                    scrollToTopBtn.style.bottom = '88px';
                } else {
                    scrollToTopBtn.style.right = '24px';
                    scrollToTopBtn.style.bottom = '100px';
                }
            } else {
                // Chatbot is on left, scroll-to-top can stay in default position
                // Reset to default (handled by CSS)
                scrollToTopBtn.style.right = '';
                scrollToTopBtn.style.bottom = '';
            }
        }

        // Mouse drag handlers
        function handleMouseDown(e) {
            if (!DRAG_ENABLED) return;
            isDragging = false;
            hasDragged = false;
            dragStartX = e.clientX;
            dragStartY = e.clientY;
            const rect = toggleBtn.getBoundingClientRect();
            // Calculate initial position relative to button's current position
            // This works regardless of whether button uses left/right or top/bottom
            initialX = e.clientX - rect.left;
            initialY = e.clientY - rect.top;
            
            // Store current button position for reference
            currentX = rect.left;
            currentY = rect.top;
            
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
            e.preventDefault();
        }

        function handleMouseMove(e) {
            if (!DRAG_ENABLED) return;
            if (!dragStartX || !dragStartY) return;
            
            const deltaX = Math.abs(e.clientX - dragStartX);
            const deltaY = Math.abs(e.clientY - dragStartY);
            
            // Start dragging if moved more than 5px
            if (deltaX > 5 || deltaY > 5) {
                if (!isDragging) {
                    isDragging = true;
                    hasDragged = true;
                    toggleBtn.classList.add('dragging');
                    // Remove transition during dragging for smooth movement
                    toggleBtn.style.transition = 'none';
                }
                
                // Calculate new position based on mouse position
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                
                // Constrain to viewport with padding
                const maxX = window.innerWidth - toggleBtn.offsetWidth - SIDE_PADDING;
                const maxY = window.innerHeight - toggleBtn.offsetHeight - SIDE_PADDING;
                
                currentX = Math.max(SIDE_PADDING, Math.min(currentX, maxX));
                currentY = Math.max(SIDE_PADDING, Math.min(currentY, maxY));
                
                // Apply position using left/top for consistent dragging
                toggleBtn.style.left = currentX + 'px';
                toggleBtn.style.right = 'auto';
                toggleBtn.style.top = currentY + 'px';
                toggleBtn.style.bottom = 'auto';
                toggleBtn.classList.add('dragged');
                
                updateWidgetPosition();
            }
        }

        function handleMouseUp(e) {
            if (!DRAG_ENABLED) return;
            if (isDragging) {
                // Restore transition for snapping animation
                toggleBtn.style.transition = '';
                // Snap to nearest side when dragging ends
                snapToSide();
                savePosition();
            }
            
            isDragging = false;
            toggleBtn.classList.remove('dragging');
            dragStartX = 0;
            dragStartY = 0;
            
            document.removeEventListener('mousemove', handleMouseMove);
            document.removeEventListener('mouseup', handleMouseUp);
        }

        // Touch drag handlers
        function handleTouchStart(e) {
            if (!DRAG_ENABLED) return;
            isDragging = false;
            hasDragged = false;
            const touch = e.touches[0];
            dragStartX = touch.clientX;
            dragStartY = touch.clientY;
            const rect = toggleBtn.getBoundingClientRect();
            // Calculate initial position relative to button's current position
            initialX = touch.clientX - rect.left;
            initialY = touch.clientY - rect.top;
            
            // Store current button position for reference
            currentX = rect.left;
            currentY = rect.top;
            
            document.addEventListener('touchmove', handleTouchMove, { passive: false });
            document.addEventListener('touchend', handleTouchEnd);
        }

        function handleTouchMove(e) {
            if (!DRAG_ENABLED) return;
            if (!dragStartX || !dragStartY) return;
            
            const touch = e.touches[0];
            const deltaX = Math.abs(touch.clientX - dragStartX);
            const deltaY = Math.abs(touch.clientY - dragStartY);
            
            // Start dragging if moved more than 5px
            if (deltaX > 5 || deltaY > 5) {
                e.preventDefault();
                
                if (!isDragging) {
                    isDragging = true;
                    hasDragged = true;
                    toggleBtn.classList.add('dragging');
                    // Remove transition during dragging for smooth movement
                    toggleBtn.style.transition = 'none';
                }
                
                // Calculate new position based on touch position
                currentX = touch.clientX - initialX;
                currentY = touch.clientY - initialY;
                
                // Constrain to viewport with padding
                const maxX = window.innerWidth - toggleBtn.offsetWidth - SIDE_PADDING;
                const maxY = window.innerHeight - toggleBtn.offsetHeight - SIDE_PADDING;
                
                currentX = Math.max(SIDE_PADDING, Math.min(currentX, maxX));
                currentY = Math.max(SIDE_PADDING, Math.min(currentY, maxY));
                
                // Apply position using left/top for consistent dragging
                toggleBtn.style.left = currentX + 'px';
                toggleBtn.style.right = 'auto';
                toggleBtn.style.top = currentY + 'px';
                toggleBtn.style.bottom = 'auto';
                toggleBtn.classList.add('dragged');
                
                updateWidgetPosition();
            }
        }

        function handleTouchEnd(e) {
            if (!DRAG_ENABLED) return;
            if (isDragging) {
                // Restore transition for snapping animation
                toggleBtn.style.transition = '';
                // Snap to nearest side when dragging ends
                snapToSide();
                savePosition();
            }
            
            isDragging = false;
            toggleBtn.classList.remove('dragging');
            dragStartX = 0;
            dragStartY = 0;
            
            document.removeEventListener('touchmove', handleTouchMove);
            document.removeEventListener('touchend', handleTouchEnd);
        }

        // Initialize drag functionality
        toggleBtn.addEventListener('mousedown', handleMouseDown);
        toggleBtn.addEventListener('touchstart', handleTouchStart, { passive: true });
        
        // Ensure button is visible on load
        if (toggleBtn) {
            toggleBtn.style.display = 'flex';
            toggleBtn.style.visibility = 'visible';
        }
        
        // Load saved position on page load
        loadSavedPosition();
        
        // Update widget and scroll-to-top positions on page load
        setTimeout(() => {
            // Double-check button is visible
            if (toggleBtn) {
                const rect = toggleBtn.getBoundingClientRect();
                // If button is off-screen, reset to default position
                if (rect.width === 0 || rect.height === 0 || 
                    rect.left < -100 || rect.left > window.innerWidth + 100 ||
                    rect.top < -100 || rect.top > window.innerHeight + 100) {
                    console.warn('Chatbot button is off-screen, resetting position');
                    toggleBtn.style.left = '';
                    toggleBtn.style.right = '';
                    toggleBtn.style.top = '';
                    toggleBtn.style.bottom = '';
                    toggleBtn.classList.remove('dragged');
                    localStorage.removeItem('chatbot-button-position');
                }
            }
            
            if (widget) {
                updateWidgetPosition();
            }
            updateScrollToTopPosition();
        }, 100);
        
        // Update widget position on window resize
        window.addEventListener('resize', () => {
            if (!isDragging) {
                updateWidgetPosition();
                updateScrollToTopPosition();
            }
        });

        let currentMode = 'live-chat';
        let conversationId = null;
        let pollingInterval = null;
        let lastMessageId = null;
        let faqInitialized = false;
        let chatOnlineStatus = true;
        let chatCensorshipEnabled = false;
        let onlineStatusInterval = null;
        let chatDisabledForPatient = false;
        let requestFormOpened = false;
        let requestAlreadySent = false;
        let isWidgetOpening = false;
        let isWidgetClosing = false;
        const DISABLED_NOTICE_KEY = 'chatbot-disabled-notice';
        // Use Maps to store messages with unique keys to prevent duplicates
        const liveChatMessagesMap = new Map(); // key -> message object
        const faqMessagesMap = new Map(); // key -> message object
        // Global Set to track all message IDs that have been added to DOM
        const addedMessageIds = new Set();
        const addedMessageKeys = new Set();

        function maskWordForClient(word) {
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

        // Chat online status management
        async function checkOnlineStatus() {
            try {
                const response = await fetch('{{ route("chat.online-status") }}');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                const wasOnline = chatOnlineStatus;
                chatDisabledForPatient = !!data.chat_disabled;
                requestAlreadySent = !!(data.chat_enable_requested ?? data.chat_enable_requested_at);
                if (!chatDisabledForPatient) {
                    // Reset request state once chat is re-enabled
                    requestAlreadySent = false;
                    clearDisabledNotice();
                }
                chatOnlineStatus = data.is_online !== undefined ? data.is_online : true;
                chatCensorshipEnabled = data.censorship_enabled !== undefined ? data.censorship_enabled : chatCensorshipEnabled;
                
                // Update badge-dot color
                if (badgeDotEl) {
                    badgeDotEl.classList.remove('online', 'offline');
                    if (chatOnlineStatus) {
                        badgeDotEl.classList.add('online');
                    } else {
                        badgeDotEl.classList.add('offline');
                    }
                }
                
                // Always update input and send button state when in live-chat mode
                if (currentMode === 'live-chat') {
                    updateChatInputState(chatOnlineStatus && !chatDisabledForPatient);
                }
            } catch (error) {
                console.error('Error checking online status:', error);
                // On error, assume offline to be safe
                chatOnlineStatus = false;
                if (currentMode === 'live-chat') {
                    updateChatInputState(false);
                }
            }
        }

        function getStoredDisabledNotice() {
            try {
                const raw = localStorage.getItem(DISABLED_NOTICE_KEY);
                if (!raw) return null;
                const parsed = JSON.parse(raw);
                if (parsed && parsed.timestamp && parsed.text) return parsed;
            } catch (_) {}
            return null;
        }

        function storeDisabledNotice(text, timestamp) {
            try {
                localStorage.setItem(DISABLED_NOTICE_KEY, JSON.stringify({ text, timestamp }));
            } catch (_) {}
        }

        function clearDisabledNotice() {
            try {
                localStorage.removeItem(DISABLED_NOTICE_KEY);
            } catch (_) {}
        }

        function updateChatInputState(isOnline) {
            const inputEl = document.getElementById('chatbot-input');
            const sendBtn = document.getElementById('chatbot-send');
            const inputContainer = document.getElementById('chatbot-input-container');
            const requestContainer = document.getElementById('chatbot-enable-request');
            const requestToggle = document.getElementById('chatbot-request-toggle');
            
            // Only update if we're in live-chat mode and input container is visible
            if (currentMode !== 'live-chat' || !inputContainer || inputContainer.style.display === 'none') {
                return;
            }
            
            if (inputEl) {
                const disabledForPatient = chatDisabledForPatient && currentMode === 'live-chat';
                const shouldDisable = !isOnline || disabledForPatient;
                inputEl.disabled = shouldDisable;
                if (shouldDisable) {
                    inputEl.placeholder = disabledForPatient
                        ? 'Live chat has been disabled for your account.'
                        : 'Chat is currently offline. Please try again later.';
                    inputEl.value = ''; // Clear any text
                } else {
                    inputEl.placeholder = 'Type your message for the clinic...';
                }
            }
            
            if (sendBtn) {
                sendBtn.disabled = !isOnline || chatDisabledForPatient;
            }

            // If chat is enabled, reset request state
            if (!chatDisabledForPatient) {
                requestFormOpened = false;
            }

            // Show request toggle/panel when disabled for patient
            if (requestContainer) {
                const showRequest = chatDisabledForPatient && requestFormOpened && !requestAlreadySent;
                requestContainer.style.display = showRequest ? 'block' : 'none';
            }
            if (requestToggle) {
                const showToggle = chatDisabledForPatient && !requestFormOpened && !requestAlreadySent;
                requestToggle.style.display = showToggle ? 'block' : 'none';
                const btn = requestToggle.querySelector('button');
                if (btn) {
                    if (requestAlreadySent) {
                        btn.setAttribute('disabled', 'disabled');
                    } else {
                        btn.removeAttribute('disabled');
                    }
                }
            }
            
            // Show/hide offline message
            if (!isOnline || chatDisabledForPatient) {
                const existingOfflineMsg = Array.from(messagesEl.children).find(wrapper => {
                    const msgDiv = wrapper.querySelector('.message.bot');
                    return msgDiv && (msgDiv.textContent.includes('Chat is currently offline') || msgDiv.textContent.includes('disabled for your account'));
                });
                
                if (!existingOfflineMsg) {
                    const offlineText = chatDisabledForPatient
                        ? 'Live chat has been disabled for your account. Please contact the clinic if you need assistance.'
                        : 'Chat is currently offline. Our staff will be back online soon. Please try again later.';
                    let timestamp = new Date().toISOString();
                    if (chatDisabledForPatient) {
                        const stored = getStoredDisabledNotice();
                        if (stored && stored.text === offlineText) {
                            timestamp = stored.timestamp;
                        } else {
                            storeDisabledNotice(offlineText, timestamp);
                        }
                    }
                    addMessage(offlineText, 'bot', null, timestamp);
                } else if (chatDisabledForPatient) {
                    // Ensure stored notice exists for persistence on refresh
                    const msgDiv = existingOfflineMsg.querySelector('.message.bot');
                    const stored = getStoredDisabledNotice();
                    if (!stored && msgDiv) {
                        storeDisabledNotice(msgDiv.textContent.trim(), existingOfflineMsg.getAttribute('data-timestamp') || new Date().toISOString());
                    }
                }
            } else {
                // Remove offline message when going online
                const offlineMsgs = Array.from(messagesEl.children).filter(wrapper => {
                    const msgDiv = wrapper.querySelector('.message.bot');
                    return msgDiv && (msgDiv.textContent.includes('Chat is currently offline') || msgDiv.textContent.includes('disabled for your account'));
                });
                offlineMsgs.forEach(wrapper => wrapper.remove());
                clearDisabledNotice();
            }
        }

    function openRequestForm() {
        if (requestAlreadySent) return;
        requestFormOpened = true;
        const requestContainer = document.getElementById('chatbot-enable-request');
        const requestToggle = document.getElementById('chatbot-request-toggle');
        const bodyEl = document.querySelector('#chatbot .chatbot-body');
        if (requestToggle) requestToggle.style.display = 'none';
        if (requestContainer) {
            requestContainer.style.display = 'block';
            const reasonEl = document.getElementById('chatbot-request-reason');
            reasonEl?.focus();
        }
        if (bodyEl) {
            // Scroll to bottom to reveal the full form
            bodyEl.scrollTop = bodyEl.scrollHeight;
        }
        updateChatInputState(chatOnlineStatus && !chatDisabledForPatient);
    }

    async function sendEnableRequest() {
        const reasonEl = document.getElementById('chatbot-request-reason');
        const feedbackEl = document.getElementById('chatbot-request-feedback');
        const submitBtn = document.getElementById('chatbot-request-submit');
        if (!reasonEl || !submitBtn) return;

        const reason = (reasonEl.value || '').trim();
        if (reason.length < 10) {
            if (feedbackEl) {
                feedbackEl.style.display = 'block';
                feedbackEl.classList.remove('text-success');
                feedbackEl.classList.add('text-danger');
                feedbackEl.textContent = 'Please provide a reason (at least 10 characters).';
            }
            return;
        }

        submitBtn.disabled = true;
        if (feedbackEl) feedbackEl.style.display = 'none';

        try {
            const response = await fetch('{{ route("patient-chat.request-enable") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason })
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to send request.');
            }

            reasonEl.value = '';
            requestAlreadySent = true;
            requestFormOpened = false;
            const requestContainer = document.getElementById('chatbot-enable-request');
            const requestToggle = document.getElementById('chatbot-request-toggle');
            if (requestContainer) requestContainer.style.display = 'none';
            if (requestToggle) {
                requestToggle.style.display = 'block';
                const btn = requestToggle.querySelector('button');
                if (btn) {
                    btn.setAttribute('disabled', 'disabled');
                }
            }
            if (feedbackEl) {
                feedbackEl.style.display = 'block';
                feedbackEl.classList.remove('text-danger');
                feedbackEl.classList.add('text-success');
                feedbackEl.textContent = data.message || 'Request sent.';
            }
        } catch (error) {
            if (feedbackEl) {
                feedbackEl.style.display = 'block';
                feedbackEl.classList.remove('text-success');
                feedbackEl.classList.add('text-danger');
                feedbackEl.textContent = error.message || 'Failed to send request.';
            }
        } finally {
            submitBtn.disabled = false;
        }
    }

        // Start polling for online status
        function startOnlineStatusPolling() {
            if (onlineStatusInterval) clearInterval(onlineStatusInterval);
            checkOnlineStatus(); // Check immediately
            onlineStatusInterval = setInterval(checkOnlineStatus, 3000); // Check every 3 seconds for faster updates
        }

        // Stop polling for online status
        function stopOnlineStatusPolling() {
            if (onlineStatusInterval) {
                clearInterval(onlineStatusInterval);
                onlineStatusInterval = null;
            }
        }
        
        // Helper function to create a unique key for a message
        function createMessageKey(msg) {
            if (msg.id) {
                return `id_${msg.id}`;
            }
            const attachmentKey = msg.attachments && Array.isArray(msg.attachments) 
                ? msg.attachments.map(a => (a.url || a.name || '')).sort().join('|')
                : (msg.attachments ? (msg.attachments.url || msg.attachments.name || '') : '');
            return `${msg.message || ''}_${msg.created_at || ''}_${msg.sender_type || ''}_${attachmentKey}`;
        }
        
        // Helper function to get messages array from Map
        function getMessagesFromMap(map) {
            return Array.from(map.values());
        }
        
        // Helper function to add message to Map (prevents duplicates)
        function addMessageToMap(map, msg) {
            const key = createMessageKey(msg);
            if (!map.has(key)) {
                map.set(key, msg);
                console.log('Added message to map:', key, msg);
            } else {
                console.log('Duplicate message skipped:', key);
            }
        }

        const quickIntents = {!! json_encode($chatbotSetting->quick_intents ?? []) !!};
        const faqRaw = @json($chatbotFaqs ?? []);
        const faqPairs = (faqRaw || []).map(function(f){
            return { q: (f.question || ''), a: (f.answer || '') };
        });

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        // Format time indicator
        function formatTimeIndicator(dateString) {
            if (!dateString) return '';
            
            const now = new Date();
            const msgDate = new Date(dateString);
            const diffMs = now - msgDate;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            
            // Just now (less than 1 minute)
            if (diffMins < 1) {
                return 'just now';
            }
            
            // Minutes ago (less than 1 hour)
            if (diffMins < 60) {
                return `${diffMins} ${diffMins === 1 ? 'min' : 'mins'}. ago`;
            }
            
            // Today (same day)
            const isToday = msgDate.toDateString() === now.toDateString();
            if (isToday) {
                const hours = msgDate.getHours();
                const minutes = msgDate.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const displayHours = hours % 12 || 12;
                const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
                return `Today ${displayHours}:${displayMinutes} ${ampm}`;
            }
            
            // Yesterday
            const yesterday = new Date(now);
            yesterday.setDate(yesterday.getDate() - 1);
            if (msgDate.toDateString() === yesterday.toDateString()) {
                const hours = msgDate.getHours();
                const minutes = msgDate.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const displayHours = hours % 12 || 12;
                const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
                return `Yesterday ${displayHours}:${displayMinutes} ${ampm}`;
            }
            
            // This week (within 7 days)
            if (diffDays < 7) {
                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                const dayName = days[msgDate.getDay()];
                const hours = msgDate.getHours();
                const minutes = msgDate.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const displayHours = hours % 12 || 12;
                const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
                return `${dayName}. ${displayHours}:${displayMinutes} ${ampm}`;
            }
            
            // Older dates - full date format
            const month = msgDate.getMonth() + 1;
            const day = msgDate.getDate();
            const year = msgDate.getFullYear();
            const hours = msgDate.getHours();
            const minutes = msgDate.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
            return `${month}/${day}/${year} ${displayHours}:${displayMinutes} ${ampm}`;
        }

        // Helper function to check if message already exists in DOM
        function messageExistsInDOM(messageId, text, sender, timestamp, attachments) {
            // First check by message ID if available
            if (messageId) {
                const existingById = Array.from(messagesEl.children).find(wrapper => {
                    return wrapper.getAttribute('data-message-id') === String(messageId);
                });
                if (existingById) return true;
            }
            
            // Check by content, sender, timestamp, and attachments
            const attachmentKey = attachments && Array.isArray(attachments) 
                ? attachments.map(a => (a.url || a.name || '')).sort().join('|')
                : (attachments ? (attachments.url || attachments.name || '') : '');
            
            const existingByContent = Array.from(messagesEl.children).find(wrapper => {
                const messageDiv = wrapper.querySelector('.message');
                if (!messageDiv) return false;
                
                const wrapperTimestamp = wrapper.getAttribute('data-timestamp');
                const isUser = messageDiv.classList.contains('user');
                const msgSender = sender === 'user' ? 'user' : sender;
                const matchesSender = (isUser && msgSender === 'user') || 
                                     (!isUser && (messageDiv.classList.contains(msgSender) || (msgSender === 'bot' && messageDiv.classList.contains('bot'))));
                const matchesContent = messageDiv.textContent === text || messageDiv.innerHTML.includes(text);
                const matchesTimestamp = wrapperTimestamp === timestamp;
                
                // Check attachments match
                let matchesAttachments = true;
                if (attachments && (Array.isArray(attachments) ? attachments.length > 0 : true)) {
                    const domAttachments = messageDiv.querySelectorAll('.message-attachments .attachment-item a');
                    const msgAttachments = Array.isArray(attachments) ? attachments : [attachments];
                    if (domAttachments.length !== msgAttachments.length) {
                        matchesAttachments = false;
                    } else {
                        const domUrls = Array.from(domAttachments).map(a => a.href).sort();
                        const msgUrls = msgAttachments.map(a => a.url || '').filter(Boolean).sort();
                        matchesAttachments = domUrls.length === msgUrls.length && 
                                            domUrls.every((url, i) => url === msgUrls[i]);
                    }
                } else {
                    const domAttachments = messageDiv.querySelectorAll('.message-attachments .attachment-item');
                    matchesAttachments = domAttachments.length === 0;
                }
                
                return matchesSender && matchesContent && matchesTimestamp && matchesAttachments;
            });
            
            return !!existingByContent;
        }

        function buildMessageContentHTML(text, sender, attachments = null) {
            let contentHTML = '';

            if (sender === 'bot' || sender === 'staff' || sender === 'admin') {
                let lines = String(text).split('\n');
                let formattedHTML = '';
                for (let i = 0; i < lines.length; i++) {
                    let line = lines[i].trim();
                    if (!line) continue;
                    if (line.endsWith(':')) {
                        formattedHTML += `<span class="section-header">${line}</span>`;
                    } else if (line.startsWith('•')) {
                        formattedHTML += `<span class="bullet-item">${line}</span>`;
                    } else {
                        formattedHTML += line;
                        if (i < lines.length - 1) {
                            formattedHTML += '<br>';
                        }
                    }
                }
                contentHTML = formattedHTML || text;
            } else {
                contentHTML = text;
            }

            const hasAttachmentHTML = contentHTML.includes('message-attachments') || contentHTML.includes('attachment-item');
            if (attachments && Array.isArray(attachments) && attachments.length > 0 && !hasAttachmentHTML) {
                contentHTML += '<div class="message-attachments">';
                attachments.forEach(attachment => {
                    if (!attachment || !attachment.url || !attachment.name) return;

                    const isImage = attachment.mime_type && attachment.mime_type.startsWith('image/');
                    const fileSize = attachment.size ? (attachment.size / 1024).toFixed(1) : '0';
                    if (isImage) {
                        const escapedUrl = attachment.url.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                        const escapedName = escapeHtml(attachment.name).replace(/'/g, "\\'").replace(/"/g, '&quot;');
                        contentHTML += `
                            <div class="attachment-item attachment-item-image">
                                <a href="javascript:void(0)" onclick="openChatbotImageModal('${escapedUrl}', '${escapedName}')" class="attachment-link attachment-link-image">
                                    <img src="${attachment.url}" alt="${escapeHtml(attachment.name)}" class="attachment-image" />
                                </a>
                            </div>
                        `;
                    } else {
                        contentHTML += `
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
                contentHTML += '</div>';
            }

            return contentHTML;
        }

        function addMessage(text, sender, attachments = null, timestamp = null, messageId = null, options = {}) {
            const { skipTracking = false, pending = false } = options || {};
            // Check if message already exists in DOM before adding
            if (messageExistsInDOM(messageId, text, sender, timestamp, attachments)) {
                return null; // Skip if already exists
            }
            
            // Create unique key for tracking
            const attachmentKey = attachments && Array.isArray(attachments) 
                ? attachments.map(a => (a.url || a.name || '')).sort().join('|')
                : (attachments ? (attachments.url || attachments.name || '') : '');
            const messageKey = `${text}_${timestamp}_${sender}_${attachmentKey}`;
            
            // Check if we've already added this message (by ID or key)
            if (messageId && addedMessageIds.has(String(messageId))) {
                return null; // Skip if already added
            }
            if (!skipTracking && addedMessageKeys.has(messageKey)) {
                return null; // Skip if already added
            }
            
            // Mark as added
            if (!skipTracking && messageId) {
                addedMessageIds.add(String(messageId));
            }
            if (!skipTracking) {
                addedMessageKeys.add(messageKey);
            }
            
            const wrapper = document.createElement('div');
            wrapper.className = 'message-wrapper';
            if (pending) {
                wrapper.classList.add('pending');
            }
            
            // Store the original ISO timestamp as a data attribute for preservation
            if (timestamp) {
                wrapper.setAttribute('data-timestamp', timestamp);
            } else {
                // If no timestamp provided, use current time and store it
                timestamp = new Date().toISOString();
                wrapper.setAttribute('data-timestamp', timestamp);
            }
            
            // Store message ID if provided
            if (messageId) {
                wrapper.setAttribute('data-message-id', messageId);
            }
            
            const div = document.createElement('div');
            div.className = 'message ' + (sender === 'user' ? 'user' : (sender === 'staff' ? 'staff' : (sender === 'admin' ? 'admin' : 'bot')));

            const contentHTML = buildMessageContentHTML(text, sender, attachments);
            div.innerHTML = contentHTML;
            wrapper.appendChild(div);
            
            // Add time indicator (only once)
            const timeDiv = document.createElement('div');
            timeDiv.className = 'message-time';
            if (timestamp) {
                timeDiv.textContent = formatTimeIndicator(timestamp);
            } else {
                // Use current time if no timestamp provided
                timeDiv.textContent = formatTimeIndicator(new Date().toISOString());
            }
            wrapper.appendChild(timeDiv);
            
            messagesEl.appendChild(wrapper);
            scrollToBottom();
            return wrapper;
        }

        function finalizePendingMessage(tempId, serverMessage) {
            const wrapper = messagesEl.querySelector(`.message-wrapper[data-message-id="${tempId}"]`);
            if (!wrapper) return;

            const existingWrapper = messagesEl.querySelector(`.message-wrapper[data-message-id="${serverMessage.id}"]`);
            if (existingWrapper && existingWrapper !== wrapper) {
                wrapper.remove();
                return;
            }

            const normalizedAttachments = serverMessage.attachments && Array.isArray(serverMessage.attachments)
                ? serverMessage.attachments
                : (serverMessage.attachments ? [serverMessage.attachments] : null);

            const sender = serverMessage.sender_type === 'patient' ? 'user' : serverMessage.sender_type;
            const messageDiv = wrapper.querySelector('.message');
            if (messageDiv) {
                messageDiv.innerHTML = buildMessageContentHTML(serverMessage.message, sender, normalizedAttachments);
            }

            const timeDiv = wrapper.querySelector('.message-time');
            if (timeDiv) {
                timeDiv.textContent = formatTimeIndicator(serverMessage.created_at);
            }

            wrapper.setAttribute('data-message-id', serverMessage.id);
            wrapper.setAttribute('data-timestamp', serverMessage.created_at);
            wrapper.classList.remove('pending');

            addedMessageIds.add(String(serverMessage.id));
            const key = createMessageKey(serverMessage);
            addedMessageKeys.add(key);
        }

        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'typing-indicator';
            typingDiv.id = 'typing-indicator';
            typingDiv.innerHTML = '<span></span><span></span><span></span>';
            messagesEl.appendChild(typingDiv);
            scrollToBottom();
        }

        function hideTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function normalize(s) {
            return String(s)
                .toLowerCase()
                .replace(/&nbsp;/g, ' ')
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        const stopWords = new Set(['the','a','an','is','are','do','i','you','we','how','what','where','when','why','to','for','of','and','or','in','on','at','with','get','does','it','this','that','about']);

        function tokenize(text) {
            return normalize(text).split(' ').filter(w => w && !stopWords.has(w));
        }

        function overlapScore(aTokens, bTokens) {
            const a = new Set(aTokens);
            const b = new Set(bTokens);
            let inter = 0;
            a.forEach(t => { if (b.has(t)) inter++; });
            const union = a.size + b.size - inter || 1;
            return { inter, jaccard: inter / union };
        }

        const faqIndexed = (faqPairs || []).map(p => ({ q: p.q, a: p.a, tokens: tokenize(p.q || '') }));

        function getBotReply(query) {
            const q = normalize(query);
            const qLower = q.toLowerCase();
            const qTokens = tokenize(q);

            const helpPatterns = ['help', 'assist', 'support', 'can you', 'could you', 'need help', 'i need', 'i want', 'how can', 'what can'];
            const greetingPatterns = ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'greetings'];
            const servicePatterns = ['service', 'treatment', 'procedure', 'what do you', 'what services', 'offer', 'available'];
            const hoursPatterns = ['hours', 'open', 'close', 'time', 'when', 'what time', 'schedule', 'availability'];
            const pricePatterns = ['price', 'cost', 'fee', 'payment', 'how much', 'expensive', 'charge'];
            const appointmentPatterns = ['appointment', 'book', 'schedule', 'reserve', 'visit', 'see dentist'];

            if (helpPatterns.some(pattern => qLower.includes(pattern))) {
                return 'Of course! I\'m here to help you. You can ask me about:\n\n• Our clinic hours and availability\n• Services and treatments we offer\n• Appointment scheduling\n• Pricing information\n• General questions about dental care\n\nWhat would you like to know more about?';
            }

            if (greetingPatterns.some(pattern => qLower.includes(pattern))) {
                return 'Hello! Welcome to J Valera dental clinic. How can I assist you today? You can ask about our services, hours, pricing, or schedule an appointment.';
            }

            if (servicePatterns.some(pattern => qLower.includes(pattern))) {
                return 'We offer a comprehensive range of dental services including:\n\n• General dentistry (cleanings, check-ups)\n• Cosmetic dentistry (whitening, veneers)\n• Orthodontics (braces, aligners)\n• Root canals and fillings\n• Crowns and bridges\n• Implants\n• Emergency dental care\n\nWould you like to know more about a specific service?';
            }

            if (hoursPatterns.some(pattern => qLower.includes(pattern))) {
                return 'Our clinic hours are:\n\n• Tuesday to Saturday: 11:00 AM to 6:00 PM\n• Sunday and Monday: Closed\n\nWe recommend scheduling an appointment in advance. Would you like to book one?';
            }

            if (pricePatterns.some(pattern => qLower.includes(pattern))) {
                return 'Pricing varies depending on the service and treatment needed. For specific pricing information, please contact our office or schedule a consultation. We\'d be happy to provide a detailed quote based on your needs.';
            }

            if (appointmentPatterns.some(pattern => qLower.includes(pattern))) {
                return 'You can schedule an appointment by:\n\n• Logging into your patient portal and using the calendar\n• Contacting us directly at (63)915 622 9695\n• Visiting our clinic at Policarpio St. Gen. T. de Leon Valenzuela City\n\nWould you like help with anything else?';
            }

            let best = { score: 0, inter: 0, a: null };
            for (const item of faqIndexed) {
                if (!item.tokens.length) continue;
                const { inter, jaccard } = overlapScore(qTokens, item.tokens);
                const score = inter >= 1 ? jaccard + 0.15 : jaccard;
                if (score > best.score) best = { score, inter, a: item.a };
            }
            if (best.a && (best.score >= 0.15 || best.inter >= 1)) return best.a;

            return 'I\'m here to help! You can ask me about:\n\n• Clinic hours and availability\n• Our dental services\n• Appointment scheduling\n• Pricing information\n• General questions\n\nOr feel free to browse our FAQs for more detailed information. What would you like to know?';
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        async function sendUserMessage(text) {
            if (!text.trim()) return;

            if (currentMode === 'live-chat' && conversationId) {
                await sendLiveMessage(text.trim());
                return;
            }

            if (currentMode === 'faqs') {
                addMessage(text.trim(), 'user', null, new Date().toISOString());
                showTypingIndicator();
                const typingDelay = 1000 + Math.random() * 1000;
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(getBotReply(text), 'bot', null, new Date().toISOString());
                }, typingDelay);
            } else {
                addMessage('Please wait for the chat to initialize...', 'bot', null, new Date().toISOString());
            }
        }

        function renderChips() {
            chipsEl.innerHTML = '';
            quickIntents.forEach(intent => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'chip';
                btn.textContent = intent.label;
                btn.addEventListener('click', () => {
                    if (currentMode === 'faqs') {
                        sendFaqMessage(intent.value);
                    } else {
                        sendUserMessage(intent.value);
                    }
                });
                chipsEl.appendChild(btn);
            });
        }

        function sendFaqMessage(text) {
            if (!text.trim()) return;
            addMessage(text.trim(), 'user', null, new Date().toISOString());
            showTypingIndicator();
            const typingDelay = 1000 + Math.random() * 1000;
            setTimeout(() => {
                hideTypingIndicator();
                addMessage(getBotReply(text), 'bot', null, new Date().toISOString());
            }, typingDelay);
        }

        async function checkAuth() {
            try {
                const response = await fetch('{{ route("chat.check-auth") }}');
                const data = await response.json();
                return data.authenticated;
            } catch (error) {
                return false;
            }
        }

        async function openChat() {
            // Prevent multiple simultaneous opens
            if (isWidgetOpening || widget.classList.contains('open') || widget.classList.contains('opening')) {
                return;
            }

            // Prevent opening if currently closing
            if (isWidgetClosing) {
                return;
            }

            isWidgetOpening = true;
            isWidgetClosing = false;

            // Remove all state classes first
            widget.classList.remove('closing', 'open', 'opening');
            
            // Ensure widget is hidden initially
            widget.style.display = 'none';
            widget.style.opacity = '0';
            widget.style.transform = 'translateY(20px) scale(0.9)';
            
            // Use requestAnimationFrame to ensure smooth transition
            requestAnimationFrame(() => {
                // Show widget first
                widget.style.display = 'flex';
                widget.setAttribute('aria-hidden', 'false');
                
                // Force reflow to ensure display change is applied
                void widget.offsetWidth;
                
                // Now add opening class to trigger animation
                widget.classList.add('opening');
                
                // Remove transition during animation to prevent conflicts
                const originalTransition = widget.style.transition;
                widget.style.transition = 'none';
                
                // Restore transition after a brief moment
                requestAnimationFrame(() => {
                    widget.style.transition = originalTransition;
                });
            });

            // Handle animation end
            const handleOpenAnimationEnd = (event) => {
                // Only handle if this is our widget and our opening animation
                if (event.target !== widget || event.animationName !== 'chatbotOpen') {
                    return;
                }
                
                // Ensure widget is still in opening state before transitioning
                if (widget.classList.contains('opening') && !isWidgetClosing) {
                    widget.classList.remove('opening');
                    widget.classList.add('open');
                    // Ensure widget stays visible
                    widget.style.display = 'flex';
                    widget.style.opacity = '1';
                    widget.style.transform = 'translateY(0) scale(1)';
                    isWidgetOpening = false;
                }
            };

            // Add one-time listener
            widget.addEventListener('animationend', handleOpenAnimationEnd, { once: true });
            
            // Fallback timeout (slightly longer than animation) - ensure widget stays open
            setTimeout(() => {
                if (widget.classList.contains('opening') && !isWidgetClosing) {
                    widget.classList.remove('opening');
                    widget.classList.add('open');
                    // Ensure widget stays visible
                    widget.style.display = 'flex';
                    widget.style.opacity = '1';
                    widget.style.transform = 'translateY(0) scale(1)';
                }
                isWidgetOpening = false;
            }, 500);
            
            // Stop pulse animation when widget is open
            toggleBtn.style.animation = 'none';
            
            // Set title based on current mode
            if (currentMode === 'live-chat') {
                titleEl.textContent = 'Live Chat';
            } else {
                titleEl.textContent = 'FAQs about the Clinic';
            }
            updateBadgeDotVisibilityForMode(currentMode);
            
            // Update position after animation starts (not during)
            setTimeout(() => {
                if (!isWidgetClosing) {
                    updateWidgetPosition();
                }
            }, 50);
            
            // Safeguard: Ensure widget stays visible after animation completes
            setTimeout(() => {
                if (widget.classList.contains('open') && !isWidgetClosing) {
                    // Force widget to stay visible
                    widget.style.display = 'flex';
                    widget.style.opacity = '1';
                    widget.style.transform = 'translateY(0) scale(1)';
                    widget.style.visibility = 'visible';
                }
            }, 600);

            if (!messagesEl.dataset.checked) {
                if (currentMode === 'faqs') {
                    chipsEl.style.display = 'flex';
                    chipsEl.innerHTML = '';
                    messagesEl.innerHTML = '';
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot', null, new Date().toISOString());
                        renderChips();
                        faqInitialized = true;
                    }, 800);
                } else if (currentMode === 'live-chat') {
                    chipsEl.style.display = 'none';
                    chipsEl.innerHTML = '';
                    const inputContainer = document.getElementById('chatbot-input-container');
                    const tabsContainer = document.getElementById('chatbot-tabs');
                    if (inputContainer) {
                        inputContainer.style.display = 'flex';
                    }
                    if (tabsContainer) {
                        tabsContainer.style.display = 'flex';
                    }
                    const isAuth = await checkAuth();
                    if (isAuth) {
                        // Check online status and update input state
                        await checkOnlineStatus();
                        updateChatInputState(chatOnlineStatus);
                        await initializeLiveChat();
                    } else {
                        messagesEl.innerHTML = '';
                        addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot', null, new Date().toISOString());
                        const loginBtn = document.createElement('button');
                        loginBtn.className = 'chip';
                        loginBtn.textContent = 'Login to Chat with Staff';
                        loginBtn.style.background = '#2196F3';
                        loginBtn.style.color = 'white';
                        loginBtn.style.marginTop = '10px';
                        loginBtn.style.width = '100%';
                        loginBtn.style.padding = '12px 16px';
                        loginBtn.style.borderRadius = '999px';
                        loginBtn.style.border = 'none';
                        loginBtn.style.fontWeight = '600';
                        loginBtn.style.cursor = 'pointer';
                        loginBtn.style.transition = 'all 0.2s ease';
                        loginBtn.addEventListener('mouseenter', () => {
                            loginBtn.style.background = '#1976D2';
                        });
                        loginBtn.addEventListener('mouseleave', () => {
                            loginBtn.style.background = '#2196F3';
                        });
                        loginBtn.addEventListener('click', () => {
                            window.location.href = '{{ route("login") }}';
                        });
                        const loginContainer = document.createElement('div');
                        loginContainer.style.marginTop = '10px';
                        loginContainer.appendChild(loginBtn);
                        messagesEl.appendChild(loginContainer);
                        inputEl.disabled = true;
                        sendBtn.disabled = true;
                    }
                }
                messagesEl.dataset.checked = '1';
            }
            inputEl.focus();
        }

        async function initializeLiveChat() {
            try {
                // Check online status before initializing
                await checkOnlineStatus();
                const response = await fetch('{{ route("patient-chat.conversation") }}');
                const data = await response.json();
                conversationId = data.conversation_id;
                chatDisabledForPatient = !!data.chat_disabled;
                // Update UI with latest disabled state
                updateChatInputState(chatOnlineStatus && !chatDisabledForPatient);
                titleEl.textContent = 'Live Chat';
                await loadMessages();
                startPolling();
            } catch (error) {
                console.error('Error initializing chat:', error);
            }
        }

        function restoreLiveChatMessages() {
            // Clear DOM completely first
            messagesEl.innerHTML = '';
            // Clear tracking sets when clearing DOM
            addedMessageIds.clear();
            addedMessageKeys.clear();
            
            const liveChatMessages = getMessagesFromMap(liveChatMessagesMap);
            console.log('Restoring live chat messages:', liveChatMessages.length, liveChatMessages);
            
            if (liveChatMessages.length === 0) {
                addMessage("Hello! 👋 Welcome to our dental clinic chat. Our staff is here to assist you with any questions or concerns. How can we help you today?", 'bot', null, new Date().toISOString());
            } else {
                // Messages in Map are already unique, just restore them
                liveChatMessages.forEach(msg => {
                    const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                    // Ensure attachments is an array or null
                    const attachments = msg.attachments && Array.isArray(msg.attachments) ? msg.attachments : (msg.attachments ? [msg.attachments] : null);
                    addMessage(msg.message, sender, attachments, msg.created_at, msg.id);
                });
            }
        }

        async function loadMessages() {
            if (!conversationId) return;
            try {
                const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                const data = await response.json();
                
                // Clear Map and add all messages from server (Map prevents duplicates)
                liveChatMessagesMap.clear();
                console.log('Loading messages from server:', data.messages?.length || 0);
                (data.messages || []).forEach(msg => {
                    addMessageToMap(liveChatMessagesMap, msg);
                    if (!lastMessageId || msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }
                });
                
                const liveChatMessages = getMessagesFromMap(liveChatMessagesMap);
                console.log('Messages in map after loading:', liveChatMessages.length);
                
                // Show welcome message if no messages exist
                if (liveChatMessages.length === 0) {
                    addMessage("Hello! 👋 Welcome to our dental clinic chat. Our staff is here to assist you with any questions or concerns. How can we help you today?", 'bot', null, new Date().toISOString());
                } else {
                    // Clear DOM completely first
                    messagesEl.innerHTML = '';
                    // Clear tracking sets when clearing DOM
                    addedMessageIds.clear();
                    addedMessageKeys.clear();
                    
                    // Messages in Map are already unique, just display them
                    liveChatMessages.forEach(msg => {
                        const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                        // Ensure attachments is an array or null
                        const attachments = msg.attachments && Array.isArray(msg.attachments) ? msg.attachments : (msg.attachments ? [msg.attachments] : null);
                        addMessage(msg.message, sender, attachments, msg.created_at, msg.id);
                    });
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        function startPolling() {
            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(async () => {
                if (!conversationId || currentMode !== 'live-chat') return;
                try {
                    const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                    const data = await response.json();
                    data.messages.forEach(msg => {
                        // Check if message already exists in DOM to prevent duplicates
                        const existingMessage = Array.from(messagesEl.children).find(wrapper => {
                            const messageDiv = wrapper.querySelector('.message');
                            if (!messageDiv) return false;
                            const wrapperTimestamp = wrapper.getAttribute('data-timestamp');
                            // Simple check: compare message content, sender type, timestamp, and attachments
                            const isUser = messageDiv.classList.contains('user');
                            const msgSender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                            const matchesSender = (isUser && msgSender === 'user') || 
                                                 (!isUser && (messageDiv.classList.contains(msgSender) || (msgSender === 'bot' && messageDiv.classList.contains('bot'))));
                            const matchesContent = messageDiv.textContent === msg.message || messageDiv.innerHTML.includes(msg.message);
                            const matchesTimestamp = wrapperTimestamp === msg.created_at;
                            
                            // Check attachments match
                            let matchesAttachments = true;
                            if (msg.attachments) {
                                const domAttachments = messageDiv.querySelectorAll('.message-attachments .attachment-item a');
                                const msgAttachments = Array.isArray(msg.attachments) ? msg.attachments : [msg.attachments];
                                if (domAttachments.length !== msgAttachments.length) {
                                    matchesAttachments = false;
                                } else {
                                    const domUrls = Array.from(domAttachments).map(a => a.href).sort();
                                    const msgUrls = msgAttachments.map(a => a.url || '').filter(Boolean).sort();
                                    matchesAttachments = domUrls.length === msgUrls.length && 
                                                        domUrls.every((url, i) => url === msgUrls[i]);
                                }
                            } else {
                                // No attachments in message, check DOM has no attachments
                                const domAttachments = messageDiv.querySelectorAll('.message-attachments .attachment-item');
                                matchesAttachments = domAttachments.length === 0;
                            }
                            
                            return matchesSender && matchesContent && matchesTimestamp && matchesAttachments;
                        });
                        
                        if (!existingMessage && msg.id > lastMessageId) {
                            // Add to Map (Map prevents duplicates automatically)
                            addMessageToMap(liveChatMessagesMap, msg);
                            
                            const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                            // Ensure attachments is an array or null
                            const attachments = msg.attachments && Array.isArray(msg.attachments) ? msg.attachments : (msg.attachments ? [msg.attachments] : null);
                            addMessage(msg.message, sender, attachments, msg.created_at, msg.id);
                            lastMessageId = msg.id;
                            
                            // Visual indicator for new messages from staff/admin
                            if (sender === 'staff' || sender === 'admin') {
                                // Update unread badge
                                updatePatientChatUnreadCount();
                                
                                // Show notification if widget is closed
                                if (!widget.classList.contains('open')) {
                                    // Add pulse animation to toggle button
                                    toggleBtn.style.animation = 'messengerBadgePulse 1.5s ease-in-out infinite';
                                    
                                    // Browser notification (if permission granted)
                                    if (Notification.permission === 'granted') {
                                        try {
                                            new Notification('New Message from Clinic', {
                                                body: msg.message.substring(0, 50) + (msg.message.length > 50 ? '...' : ''),
                                                icon: '{{ asset("images/chatbot-logo_3.png") }}',
                                                tag: 'chat-message-' + msg.id,
                                                requireInteraction: false
                                            });
                                        } catch (e) {
                                            // Ignore notification errors
                                        }
                                    }
                                } else {
                                    // Widget is open, just scroll to bottom
                                    scrollToBottom();
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error polling:', error);
                }
            }, 3000);
        }

        async function sendLiveMessage(text) {
            if (!conversationId) return;
            
            // Check online status before sending
            if (!chatOnlineStatus) {
                addMessage('Chat is currently offline. Please try again later.', 'bot', null, new Date().toISOString());
                return;
            }
            
            const now = new Date().toISOString();
            const tempId = Date.now();
            addMessage(text, 'user', null, now, tempId, { skipTracking: true, pending: true });
            // Store user message temporarily
            const tempMsg = {
                sender_type: 'patient',
                message: text,
                id: tempId,
                created_at: now
            };
            addMessageToMap(liveChatMessagesMap, tempMsg);
            inputEl.value = '';

            try {
                const response = await fetch('{{ route("patient-chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        conversation_id: conversationId,
                        message: text
                    })
                });
                const data = await response.json();
                if (data.success) {
                    // Remove temporary message and add real one
                    const tempKey = createMessageKey(tempMsg);
                    liveChatMessagesMap.delete(tempKey);
                    addMessageToMap(liveChatMessagesMap, data.message);
                    finalizePendingMessage(tempId, data.message);
                    lastMessageId = data.message.id;
                } else {
                    // Remove temporary message on error
                    const tempKey = createMessageKey(tempMsg);
                    liveChatMessagesMap.delete(tempKey);
                    const messageWrappers = messagesEl.querySelectorAll('.message-wrapper');
                    if (messageWrappers.length > 0) {
                        const lastWrapper = messageWrappers[messageWrappers.length - 1];
                        if (lastWrapper.querySelector('.message.user')) {
                            lastWrapper.remove();
                        }
                    }
                    addMessage(data.message || 'Error sending message. Please try again.', 'bot', null, new Date().toISOString());
                    // Update online status if error indicates offline
                    if (response.status === 403) {
                        await checkOnlineStatus();
                    }
                }
            } catch (error) {
                // Remove temporary message on error
                const tempKey = createMessageKey(tempMsg);
                liveChatMessagesMap.delete(tempKey);
                const messageWrappers = messagesEl.querySelectorAll('.message-wrapper');
                if (messageWrappers.length > 0) {
                    const lastWrapper = messageWrappers[messageWrappers.length - 1];
                    if (lastWrapper.querySelector('.message.user')) {
                        lastWrapper.remove();
                    }
                }
                addMessage('Error sending message. Please try again.', 'bot', null, new Date().toISOString());
            }
        }

        function closeChat() {
            // Prevent closing if already closing or not open
            if (isWidgetClosing || (!widget.classList.contains('open') && !widget.classList.contains('opening'))) {
                return;
            }

            // Prevent closing if currently opening
            if (isWidgetOpening) {
                return;
            }

            isWidgetClosing = true;
            isWidgetOpening = false;

            // Remove open and opening classes, add closing class
            widget.classList.remove('open', 'opening');
            widget.classList.add('closing');
            widget.setAttribute('aria-hidden', 'true');

            // Remove transition during closing animation to prevent conflicts
            const originalTransition = widget.style.transition;
            widget.style.transition = 'none';

            // Handle animation end
            const handleCloseAnimationEnd = (event) => {
                // Only handle if this is our widget
                if (event.target !== widget) {
                    return;
                }
                
                // Remove listener to prevent multiple calls
                widget.removeEventListener('animationend', handleCloseAnimationEnd);
                
                // Only proceed if still in closing state
                if (widget.classList.contains('closing')) {
                    widget.classList.remove('closing');
                    widget.style.display = 'none';
                    widget.style.transition = originalTransition;
                    isWidgetClosing = false;
                }
            };

            // Add one-time listener
            widget.addEventListener('animationend', handleCloseAnimationEnd, { once: true });
            
            // Fallback timeout (slightly longer than animation)
            setTimeout(() => {
                if (widget.classList.contains('closing')) {
                    widget.classList.remove('closing');
                }
                widget.style.display = 'none';
                widget.style.transition = originalTransition;
                isWidgetClosing = false;
            }, 350);
            
            stopPolling();
            
            // Restart pulse animation when widget is closed
            toggleBtn.style.animation = 'bubblePulse 2s ease-in-out infinite';
            
            // Reset opacity to 50% on mobile when chatbot is closed
            if (window.innerWidth <= 480) {
                toggleBtn.classList.remove('clicked');
                toggleBtn.style.opacity = '0.5';
            }
        }

        function switchTab(mode) {
            // Don't switch if already in this mode
            if (currentMode === mode) return;
            
            // Store current messages before switching
            if (currentMode === 'live-chat') {
                // Clear Map first
                liveChatMessagesMap.clear();
                console.log('Saving live chat messages from DOM');
                
                // Save current live chat messages state
                const currentMessages = Array.from(messagesEl.children).map(wrapper => {
                    // Get the actual message div inside the wrapper
                    const messageDiv = wrapper.querySelector('.message');
                    if (!messageDiv) return null;
                    
                    const isUser = messageDiv.classList.contains('user');
                    const isStaff = messageDiv.classList.contains('staff');
                    const isAdmin = messageDiv.classList.contains('admin');
                    const isBot = messageDiv.classList.contains('bot');
                    
                    // Extract attachments FIRST (before getting message content)
                    const attachmentDivs = messageDiv.querySelectorAll('.message-attachments .attachment-item');
                    console.log('Live-chat: Found attachment divs:', attachmentDivs.length, 'for message:', messageDiv);
                    const attachments = attachmentDivs.length > 0 ? Array.from(attachmentDivs).map(attDiv => {
                        const link = attDiv.querySelector('a');
                        const img = attDiv.querySelector('img');
                        const nameSpan = attDiv.querySelector('.attachment-name');
                        const sizeSpan = attDiv.querySelector('.attachment-size');
                        
                        // Get attachment name - for images, check img alt and onclick; for files, check nameSpan, download, etc.
                        let attachmentName = null;
                        
                        // For images, try img alt attribute first, then onclick parameter
                        if (img && img.alt) {
                            attachmentName = img.alt.trim();
                        } else if (link) {
                            // Try to extract name from onclick attribute for images
                            let onclickStr = null;
                            if (link.onclick && typeof link.onclick === 'function') {
                                onclickStr = link.onclick.toString();
                            } else if (link.getAttribute('onclick')) {
                                onclickStr = link.getAttribute('onclick');
                            }
                            
                            if (onclickStr) {
                                // Extract both URL and name from onclick: openChatbotImageModal('url', 'name')
                                const onclickMatch = onclickStr.match(/openChatbotImageModal\(['"]([^'"]+)['"]\s*,\s*['"]([^'"]+)['"]/);
                                if (onclickMatch && onclickMatch[2]) {
                                    attachmentName = onclickMatch[2].trim();
                                }
                            }
                        }
                        
                        // For non-image files, try nameSpan, download attribute, then link text
                        if (!attachmentName) {
                            if (nameSpan && nameSpan.textContent) {
                                attachmentName = nameSpan.textContent.trim();
                            } else if (link && link.getAttribute('download')) {
                                attachmentName = link.getAttribute('download');
                            } else if (link && link.textContent) {
                                attachmentName = link.textContent.trim();
                            }
                        }
                        
                        // For images, we can still proceed even without a name (use filename from URL)
                        // For files, we need a name
                        const isImage = img && img.src;
                        if (!attachmentName && !isImage) {
                            console.warn('No attachment name found for non-image file');
                            return null;
                        }
                        
                        // For images without a name, try to extract from URL
                        if (!attachmentName && isImage && img.src) {
                            const urlMatch = img.src.match(/\/([^\/]+\.(jpg|jpeg|png|gif|webp|bmp|svg))$/i);
                            if (urlMatch) {
                                attachmentName = urlMatch[1];
                            } else {
                                attachmentName = 'image';
                            }
                        }
                        
                        // For images, get URL from img.src (since link.href is javascript:void(0))
                        // For other files, get URL from link.href or link.getAttribute('href')
                        let attachmentUrl = null;
                        let mimeType = 'application/pdf';
                        
                        if (img && img.src) {
                            // Image attachment - get URL from img.src
                            attachmentUrl = img.src;
                            // Determine mime type from image src or extension
                            const urlMatch = attachmentUrl.match(/\.(jpg|jpeg|png|gif|webp|bmp|svg)/i);
                            const ext = urlMatch ? urlMatch[1].toLowerCase() : 'jpeg';
                            mimeType = `image/${ext === 'jpg' ? 'jpeg' : ext}`;
                        } else if (link) {
                            // Try to get URL from href attribute (more reliable than link.href property)
                            const hrefAttr = link.getAttribute('href');
                            const hrefProp = link.href; // Browser-resolved URL
                            
                            console.log('Live-chat: Extracting file attachment - hrefAttr:', hrefAttr, 'hrefProp:', hrefProp);
                            
                            // Check if hrefAttr is a valid file URL (not javascript:void(0) or #)
                            if (hrefAttr && hrefAttr !== 'javascript:void(0)' && hrefAttr !== '#' && !hrefAttr.startsWith('javascript:')) {
                                // Non-image file attachment - get URL from href attribute
                                // If it's a relative URL, use the resolved href property
                                if (hrefAttr.startsWith('http://') || hrefAttr.startsWith('https://') || hrefAttr.startsWith('/')) {
                                    attachmentUrl = hrefAttr;
                                } else if (hrefProp && hrefProp !== window.location.href && !hrefProp.endsWith('#')) {
                                    // Use resolved URL if attribute is relative
                                    attachmentUrl = hrefProp;
                                } else {
                                    attachmentUrl = hrefAttr;
                                }
                                
                                // Try to determine mime type from file extension
                                const urlMatch = attachmentUrl.match(/\.([a-z0-9]+)/i);
                                if (urlMatch) {
                                    const ext = urlMatch[1].toLowerCase();
                                    const mimeMap = {
                                        'pdf': 'application/pdf',
                                        'doc': 'application/msword',
                                        'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'txt': 'text/plain',
                                        'zip': 'application/zip',
                                        'rar': 'application/x-rar-compressed',
                                        'xls': 'application/vnd.ms-excel',
                                        'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                    };
                                    mimeType = mimeMap[ext] || 'application/pdf';
                                }
                            } else if (hrefProp && hrefProp !== 'javascript:void(0)' && hrefProp !== window.location.href && !hrefProp.endsWith('#') && !hrefProp.includes('#')) {
                                // Fallback to link.href property if attribute didn't work
                                // Make sure it's not just the current page URL
                                attachmentUrl = hrefProp;
                                const urlMatch = attachmentUrl.match(/\.([a-z0-9]+)/i);
                                if (urlMatch) {
                                    const ext = urlMatch[1].toLowerCase();
                                    const mimeMap = {
                                        'pdf': 'application/pdf',
                                        'doc': 'application/msword',
                                        'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'txt': 'text/plain',
                                        'zip': 'application/zip',
                                        'rar': 'application/x-rar-compressed'
                                    };
                                    mimeType = mimeMap[ext] || 'application/pdf';
                                }
                            } else {
                                // Try to extract URL from onclick attribute for images (check both function and attribute)
                                let onclickStr = null;
                                if (link.onclick && typeof link.onclick === 'function') {
                                    onclickStr = link.onclick.toString();
                                } else if (link.getAttribute('onclick')) {
                                    onclickStr = link.getAttribute('onclick');
                                }
                                
                                if (onclickStr) {
                                    const onclickMatch = onclickStr.match(/openChatbotImageModal\(['"]([^'"]+)['"]/);
                                    if (onclickMatch && onclickMatch[1]) {
                                        attachmentUrl = onclickMatch[1];
                                        const urlMatch = attachmentUrl.match(/\.(jpg|jpeg|png|gif|webp|bmp|svg)/i);
                                        const ext = urlMatch ? urlMatch[1].toLowerCase() : 'jpeg';
                                        mimeType = `image/${ext === 'jpg' ? 'jpeg' : ext}`;
                                    }
                                }
                            }
                        }
                        
                        if (attachmentUrl) {
                            const attachment = {
                                url: attachmentUrl,
                                name: attachmentName,
                                size: sizeSpan ? parseFloat(sizeSpan.textContent.match(/[\d.]+/)?.[0] || 0) * 1024 : 0,
                                mime_type: mimeType
                            };
                            console.log('Successfully extracted attachment:', attachment);
                            return attachment;
                        }
                        console.warn('Failed to extract attachment URL for:', attachmentName, 'hrefAttr:', link?.getAttribute('href'), 'hrefProp:', link?.href, 'img:', img?.src);
                        return null;
                    }).filter(Boolean) : null;
                    
                    if (attachments && attachments.length > 0) {
                        console.log('Live-chat: Total attachments extracted for this message:', attachments.length, attachments);
                    } else if (attachmentDivs.length > 0) {
                        console.warn('Live-chat: Found attachment divs but failed to extract any attachments!');
                    }
                    
                    // Get message content WITHOUT attachments HTML
                    // Clone the message div to avoid modifying the original
                    const messageClone = messageDiv.cloneNode(true);
                    // Remove attachment HTML from clone
                    const attachmentContainer = messageClone.querySelector('.message-attachments');
                    if (attachmentContainer) {
                        attachmentContainer.remove();
                    }
                    // Get text content (for user messages) or HTML without attachments (for bot/staff/admin)
                    const messageContent = isUser ? messageClone.textContent.trim() : messageClone.innerHTML.trim();
                    
                    // Get original ISO timestamp from data attribute (preserves original time)
                    const originalTimestamp = wrapper.getAttribute('data-timestamp');
                    
                    // Try to get message ID from data attribute
                    let messageId = wrapper.getAttribute('data-message-id');
                    if (messageId) {
                        messageId = parseInt(messageId) || messageId;
                    }
                    
                    // Determine sender type
                    let senderType = 'bot';
                    if (isUser) senderType = 'patient';
                    else if (isStaff) senderType = 'staff';
                    else if (isAdmin) senderType = 'admin';
                    
                    const savedMsg = {
                        id: messageId || null,
                        sender_type: senderType,
                        message: messageContent,
                        attachments: attachments,
                        // Use original timestamp if available, otherwise use current time
                        created_at: originalTimestamp || new Date().toISOString(),
                        isUser: isUser
                    };
                    console.log('Live-chat: Saving message:', savedMsg.id, 'with attachments:', savedMsg.attachments?.length || 0, 'Full message:', savedMsg);
                    return savedMsg;
                }).filter(msg => {
                    // Keep message if it has content OR attachments (files-only messages are valid)
                    const isValid = msg && (msg.message?.trim() || (msg.attachments && msg.attachments.length > 0));
                    if (!isValid) {
                        console.warn('Filtered out message (no content or attachments):', msg);
                    }
                    return isValid;
                });
                
                // Only update if we have messages (not just welcome message)
                if (currentMessages.length > 0) {
                    console.log('Saving', currentMessages.length, 'messages to liveChatMessagesMap');
                    // Add messages to Map (Map prevents duplicates automatically)
                    currentMessages.forEach(msg => {
                        console.log('Adding message to map:', msg.id, 'attachments:', msg.attachments?.length || 0);
                        addMessageToMap(liveChatMessagesMap, msg);
                    });
                    console.log('Saved messages to map:', liveChatMessagesMap.size);
                } else {
                    console.warn('No messages to save to liveChatMessagesMap');
                }
            } else if (currentMode === 'faqs') {
                // Clear Map first
                faqMessagesMap.clear();
                console.log('Saving FAQ messages from DOM');
                
                // Save current FAQ messages state
                const currentMessages = Array.from(messagesEl.children).map(wrapper => {
                    // Get the actual message div inside the wrapper
                    const messageDiv = wrapper.querySelector('.message');
                    if (!messageDiv) return null;
                    
                    const isUser = messageDiv.classList.contains('user');
                    const isBot = messageDiv.classList.contains('bot');
                    
                    // Extract attachments FIRST (before getting message content)
                    const attachmentDivs = messageDiv.querySelectorAll('.message-attachments .attachment-item');
                    const attachments = attachmentDivs.length > 0 ? Array.from(attachmentDivs).map(attDiv => {
                        const link = attDiv.querySelector('a');
                        const img = attDiv.querySelector('img');
                        const nameSpan = attDiv.querySelector('.attachment-name');
                        const sizeSpan = attDiv.querySelector('.attachment-size');
                        
                        // Get attachment name - for images, check img alt and onclick; for files, check nameSpan, download, etc.
                        let attachmentName = null;
                        
                        // For images, try img alt attribute first, then onclick parameter
                        if (img && img.alt) {
                            attachmentName = img.alt.trim();
                        } else if (link) {
                            // Try to extract name from onclick attribute for images
                            let onclickStr = null;
                            if (link.onclick && typeof link.onclick === 'function') {
                                onclickStr = link.onclick.toString();
                            } else if (link.getAttribute('onclick')) {
                                onclickStr = link.getAttribute('onclick');
                            }
                            
                            if (onclickStr) {
                                // Extract both URL and name from onclick: openChatbotImageModal('url', 'name')
                                const onclickMatch = onclickStr.match(/openChatbotImageModal\(['"]([^'"]+)['"]\s*,\s*['"]([^'"]+)['"]/);
                                if (onclickMatch && onclickMatch[2]) {
                                    attachmentName = onclickMatch[2].trim();
                                }
                            }
                        }
                        
                        // For non-image files, try nameSpan, download attribute, then link text
                        if (!attachmentName) {
                            if (nameSpan && nameSpan.textContent) {
                                attachmentName = nameSpan.textContent.trim();
                            } else if (link && link.getAttribute('download')) {
                                attachmentName = link.getAttribute('download');
                            } else if (link && link.textContent) {
                                attachmentName = link.textContent.trim();
                            }
                        }
                        
                        // For images, we can still proceed even without a name (use filename from URL)
                        // For files, we need a name
                        const isImage = img && img.src;
                        if (!attachmentName && !isImage) {
                            console.warn('FAQ: No attachment name found for non-image file');
                            return null;
                        }
                        
                        // For images without a name, try to extract from URL
                        if (!attachmentName && isImage && img.src) {
                            const urlMatch = img.src.match(/\/([^\/]+\.(jpg|jpeg|png|gif|webp|bmp|svg))$/i);
                            if (urlMatch) {
                                attachmentName = urlMatch[1];
                            } else {
                                attachmentName = 'image';
                            }
                        }
                        
                        // For images, get URL from img.src (since link.href is javascript:void(0))
                        // For other files, get URL from link.href or link.getAttribute('href')
                        let attachmentUrl = null;
                        let mimeType = 'application/pdf';
                        
                        if (img && img.src) {
                            // Image attachment - get URL from img.src
                            attachmentUrl = img.src;
                            // Determine mime type from image src or extension
                            const urlMatch = attachmentUrl.match(/\.(jpg|jpeg|png|gif|webp|bmp|svg)/i);
                            const ext = urlMatch ? urlMatch[1].toLowerCase() : 'jpeg';
                            mimeType = `image/${ext === 'jpg' ? 'jpeg' : ext}`;
                        } else if (link) {
                            // Try to get URL from href attribute (more reliable than link.href property)
                            const hrefAttr = link.getAttribute('href');
                            if (hrefAttr && hrefAttr !== 'javascript:void(0)' && hrefAttr !== '#') {
                                // Non-image file attachment - get URL from href attribute
                                attachmentUrl = hrefAttr;
                                // Try to determine mime type from file extension
                                const urlMatch = attachmentUrl.match(/\.([a-z0-9]+)/i);
                                if (urlMatch) {
                                    const ext = urlMatch[1].toLowerCase();
                                    const mimeMap = {
                                        'pdf': 'application/pdf',
                                        'doc': 'application/msword',
                                        'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'txt': 'text/plain',
                                        'zip': 'application/zip',
                                        'rar': 'application/x-rar-compressed',
                                        'xls': 'application/vnd.ms-excel',
                                        'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                    };
                                    mimeType = mimeMap[ext] || 'application/pdf';
                                }
                            } else if (link.href && link.href !== 'javascript:void(0)' && link.href !== window.location.href + '#') {
                                // Fallback to link.href property if attribute didn't work
                                attachmentUrl = link.href;
                                const urlMatch = attachmentUrl.match(/\.([a-z0-9]+)/i);
                                if (urlMatch) {
                                    const ext = urlMatch[1].toLowerCase();
                                    const mimeMap = {
                                        'pdf': 'application/pdf',
                                        'doc': 'application/msword',
                                        'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        'txt': 'text/plain',
                                        'zip': 'application/zip',
                                        'rar': 'application/x-rar-compressed'
                                    };
                                    mimeType = mimeMap[ext] || 'application/pdf';
                                }
                            } else {
                                // Try to extract URL from onclick attribute for images (check both function and attribute)
                                let onclickStr = null;
                                if (link.onclick && typeof link.onclick === 'function') {
                                    onclickStr = link.onclick.toString();
                                } else if (link.getAttribute('onclick')) {
                                    onclickStr = link.getAttribute('onclick');
                                }
                                
                                if (onclickStr) {
                                    const onclickMatch = onclickStr.match(/openChatbotImageModal\(['"]([^'"]+)['"]/);
                                    if (onclickMatch && onclickMatch[1]) {
                                        attachmentUrl = onclickMatch[1];
                                        const urlMatch = attachmentUrl.match(/\.(jpg|jpeg|png|gif|webp|bmp|svg)/i);
                                        const ext = urlMatch ? urlMatch[1].toLowerCase() : 'jpeg';
                                        mimeType = `image/${ext === 'jpg' ? 'jpeg' : ext}`;
                                    }
                                }
                            }
                        }
                        
                        if (attachmentUrl) {
                            const attachment = {
                                url: attachmentUrl,
                                name: attachmentName,
                                size: sizeSpan ? parseFloat(sizeSpan.textContent.match(/[\d.]+/)?.[0] || 0) * 1024 : 0,
                                mime_type: mimeType
                            };
                            console.log('Successfully extracted attachment:', attachment);
                            return attachment;
                        }
                        console.warn('Failed to extract attachment URL for:', attachmentName, 'hrefAttr:', link?.getAttribute('href'), 'hrefProp:', link?.href, 'img:', img?.src);
                        return null;
                    }).filter(Boolean) : null;
                    
                    // Get message content WITHOUT attachments HTML
                    // Clone the message div to avoid modifying the original
                    const messageClone = messageDiv.cloneNode(true);
                    // Remove attachment HTML from clone
                    const attachmentContainer = messageClone.querySelector('.message-attachments');
                    if (attachmentContainer) {
                        attachmentContainer.remove();
                    }
                    // Get text content (for user messages) or HTML without attachments (for bot)
                    const messageContent = isUser ? messageClone.textContent.trim() : messageClone.innerHTML.trim();
                    
                    // Get original ISO timestamp from data attribute (preserves original time)
                    const originalTimestamp = wrapper.getAttribute('data-timestamp');
                    
                    // Try to get message ID from data attribute
                    let messageId = wrapper.getAttribute('data-message-id');
                    if (messageId) {
                        messageId = parseInt(messageId) || messageId;
                    }
                    
                    return {
                        id: messageId || null,
                        sender_type: isUser ? 'patient' : 'bot',
                        message: messageContent,
                        attachments: attachments,
                        // Use original timestamp if available, otherwise use current time
                        created_at: originalTimestamp || new Date().toISOString(),
                        isUser: isUser
                    };
                }).filter(msg => {
                    // Keep message if it has content OR attachments (files-only messages are valid)
                    return msg && (msg.message?.trim() || (msg.attachments && msg.attachments.length > 0));
                });
                
                if (currentMessages.length > 0) {
                    // Add messages to Map (Map prevents duplicates automatically)
                    currentMessages.forEach(msg => {
                        addMessageToMap(faqMessagesMap, msg);
                    });
                    console.log('Saved FAQ messages to map:', faqMessagesMap.size);
                }
            }

            // Update mode and tabs
            currentMode = mode;
            updateBadgeDotVisibilityForMode(mode);
            tabLiveChat.classList.toggle('active', mode === 'live-chat');
            tabFaqs.classList.toggle('active', mode === 'faqs');

            const inputContainer = document.getElementById('chatbot-input-container');
            const tabsContainer = document.getElementById('chatbot-tabs');

            // Ensure tabs are always visible
            if (tabsContainer) {
                tabsContainer.style.display = 'flex';
            }

            // Add fade transition
            messagesEl.style.opacity = '0';
            messagesEl.style.transition = 'opacity 0.2s ease';

            setTimeout(() => {
                // Clear tracking sets when switching tabs (DOM will be cleared/restored)
                addedMessageIds.clear();
                addedMessageKeys.clear();
                
                if (mode === 'live-chat') {
                    titleEl.textContent = 'Live Chat';
                    inputEl.placeholder = 'Type your message to staff...';
                    // Always show input and tabs in Live Chat mode
                    if (inputContainer) {
                        inputContainer.style.display = 'flex';
                    }
                    if (tabsContainer) {
                        tabsContainer.style.display = 'flex';
                    }
                    chipsEl.style.display = 'none';
                    chipsEl.innerHTML = '';
                    stopPolling();
                    checkAuth().then(async isAuth => {
                        if (isAuth) {
                            // Check online status and update input state
                            await checkOnlineStatus();
                            updateChatInputState(chatOnlineStatus);
                            if (!conversationId) {
                                messagesEl.innerHTML = '';
                                await initializeLiveChat();
                            } else {
                                // Restore messages if available, otherwise load from server
                                const liveChatMessages = getMessagesFromMap(liveChatMessagesMap);
                                console.log('Restoring live chat messages from map:', liveChatMessages.length);
                                
                                if (liveChatMessages.length > 0) {
                                    // Clear DOM completely first
                                    messagesEl.innerHTML = '';
                                    // Clear tracking sets when clearing DOM
                                    addedMessageIds.clear();
                                    addedMessageKeys.clear();
                                    
                                    // Messages in Map are already unique, just restore them
                                    liveChatMessages.forEach(msg => {
                                        const sender = msg.isUser ? 'user' : (msg.sender_type || 'bot');
                                        // Ensure attachments is an array or null
                                        const attachments = msg.attachments && Array.isArray(msg.attachments) ? msg.attachments : (msg.attachments ? [msg.attachments] : null);
                                        addMessage(msg.message, sender, attachments, msg.created_at, msg.id);
                                    });
                                } else {
                                    // Load from server if no saved messages
                                    await loadMessages();
                                }
                                startPolling();
                            }
                        } else {
                            messagesEl.innerHTML = '';
                            addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot', null, new Date().toISOString());
                            const loginBtn = document.createElement('button');
                            loginBtn.className = 'chip';
                            loginBtn.textContent = 'Login to Chat with Staff';
                            loginBtn.style.background = '#2196F3';
                            loginBtn.style.color = 'white';
                            loginBtn.style.marginTop = '10px';
                            loginBtn.style.width = '100%';
                            loginBtn.style.padding = '12px 16px';
                            loginBtn.style.borderRadius = '999px';
                            loginBtn.style.border = 'none';
                            loginBtn.style.fontWeight = '600';
                            loginBtn.style.cursor = 'pointer';
                            loginBtn.style.transition = 'all 0.2s ease';
                            loginBtn.addEventListener('mouseenter', () => {
                                loginBtn.style.background = '#1976D2';
                            });
                            loginBtn.addEventListener('mouseleave', () => {
                                loginBtn.style.background = '#2196F3';
                            });
                            loginBtn.addEventListener('click', () => {
                                window.location.href = '{{ route("login") }}';
                            });
                            const loginContainer = document.createElement('div');
                            loginContainer.style.marginTop = '10px';
                            loginContainer.appendChild(loginBtn);
                            messagesEl.appendChild(loginContainer);
                            inputEl.disabled = true;
                            sendBtn.disabled = true;
                        }
                        messagesEl.style.opacity = '1';
                    });
                } else {
                    titleEl.textContent = 'FAQs about the Clinic';
                    inputEl.placeholder = 'Ask about services, hours, pricing...';
                    // Hide input in FAQs mode, but keep tabs visible
                    if (inputContainer) {
                        inputContainer.style.display = 'none';
                    }
                    if (tabsContainer) {
                        tabsContainer.style.display = 'flex';
                    }
                    chipsEl.style.display = 'flex';
                    chipsEl.innerHTML = '';
                    stopPolling();
                    inputEl.disabled = false;
                    sendBtn.disabled = false;
                    
                    // Restore FAQ messages if available
                    const faqMessages = getMessagesFromMap(faqMessagesMap);
                    console.log('Restoring FAQ messages from map:', faqMessages.length);
                    
                    if (faqMessages.length > 0) {
                        // Clear DOM completely first
                        messagesEl.innerHTML = '';
                        // Clear tracking sets when clearing DOM
                        addedMessageIds.clear();
                        addedMessageKeys.clear();
                        
                        // Messages in Map are already unique, just restore them
                        faqMessages.forEach(msg => {
                            const attachments = msg.attachments && Array.isArray(msg.attachments) ? msg.attachments : (msg.attachments ? [msg.attachments] : null);
                            addMessage(msg.message, msg.isUser ? 'user' : 'bot', attachments, msg.created_at || new Date().toISOString(), msg.id);
                        });
                        renderChips();
                        messagesEl.style.opacity = '1';
                    } else {
                        messagesEl.innerHTML = '';
                        // Clear tracking sets when clearing DOM
                        addedMessageIds.clear();
                        addedMessageKeys.clear();
                        showTypingIndicator();
                        setTimeout(() => {
                            hideTypingIndicator();
                            addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot', null, new Date().toISOString());
                            renderChips();
                            messagesEl.style.opacity = '1';
                        }, 600);
                    }
                }
            }, 200);
        }

        toggleBtn.addEventListener('click', (e) => {
            // Don't open/close if user was dragging
            if (hasDragged) {
                hasDragged = false;
                return;
            }
            
            // Prevent action if widget is currently animating
            if (isWidgetOpening || isWidgetClosing) {
                return;
            }
            
            // Set opacity to 100% on mobile when clicked
            if (window.innerWidth <= 480) {
                toggleBtn.classList.add('clicked');
                toggleBtn.style.opacity = '1';
            }
            
            // Check current state and toggle accordingly
            if (widget.classList.contains('open')) {
                closeChat();
            } else if (!widget.classList.contains('opening')) {
                openChat();
            }
        });

        closeBtn.addEventListener('click', closeChat);

        sendBtn.addEventListener('click', () => {
            if (currentMode === 'faqs') {
                sendFaqMessage(inputEl.value);
                inputEl.value = '';
            } else {
                sendUserMessage(inputEl.value);
                inputEl.value = '';
            }
        });

        inputEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (currentMode === 'faqs') {
                    sendFaqMessage(inputEl.value);
                } else {
                    sendUserMessage(inputEl.value);
                }
                inputEl.value = '';
            }
        });

        const requestSubmitBtn = document.getElementById('chatbot-request-submit');
        requestSubmitBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            sendEnableRequest();
        });

        const requestOpenBtn = document.getElementById('chatbot-request-open');
        requestOpenBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            if (requestAlreadySent || requestOpenBtn.disabled) return;
            openRequestForm();
        });

        const requestCancelBtn = document.getElementById('chatbot-request-cancel');
        requestCancelBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            requestFormOpened = false;
            const requestContainer = document.getElementById('chatbot-enable-request');
            const requestToggle = document.getElementById('chatbot-request-toggle');
            const reasonEl = document.getElementById('chatbot-request-reason');
            const feedbackEl = document.getElementById('chatbot-request-feedback');
            if (requestContainer) requestContainer.style.display = 'none';
            if (requestToggle && !requestAlreadySent) {
                requestToggle.style.display = 'block';
            }
            if (reasonEl) reasonEl.value = '';
            if (feedbackEl) feedbackEl.style.display = 'none';
            updateChatInputState(chatOnlineStatus && !chatDisabledForPatient);
        });

        tabLiveChat?.addEventListener('click', () => switchTab('live-chat'));
        tabFaqs?.addEventListener('click', () => switchTab('faqs'));

        widget.setAttribute('aria-hidden', 'true');
        
        // Initialize online status polling
        startOnlineStatusPolling();
    })();

    // Chat unread count polling for patient
    let patientChatUnreadInterval = null;
    
    async function updatePatientChatUnreadCount() {
        try {
            const response = await fetch('{{ route("patient-chat.unread-count") }}');
            
            // Check if response is OK and content type is JSON
            if (!response.ok) {
                return; // Silently fail if endpoint returns error
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return; // Silently fail if response is not JSON
            }
            
            const data = await response.json();
            const badge = document.getElementById('patient-chat-badge');
            
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        } catch (error) {
            // Silently handle errors - don't log to console to avoid noise
        }
    }
    
    // Request notification permission on page load
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().catch(() => {
            // Ignore permission errors
        });
    }
    
    // Start polling for chat unread count
    if (document.getElementById('patient-chat-badge')) {
        updatePatientChatUnreadCount(); // Initial load
        patientChatUnreadInterval = setInterval(updatePatientChatUnreadCount, 10000); // Update every 10 seconds
    }

    // Image Modal for chatbot attachments
    function openChatbotImageModal(imageSrc, imageTitle) {
        const modal = document.createElement('div');
        modal.className = 'chatbot-image-modal';
        modal.innerHTML = `
            <div class="chatbot-image-modal-overlay" onclick="closeChatbotImageModal()">
                <div class="chatbot-image-modal-content" onclick="event.stopPropagation()">
                    <button class="chatbot-image-modal-close" onclick="closeChatbotImageModal()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <img src="${imageSrc}" alt="${imageTitle}" class="chatbot-image-modal-img">
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeChatbotImageModal() {
        const modal = document.querySelector('.chatbot-image-modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(modal);
                document.body.style.overflow = '';
            }, 300);
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeChatbotImageModal();
        }
    });
</script>
@endif

