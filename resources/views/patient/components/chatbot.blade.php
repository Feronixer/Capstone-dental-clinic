@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
<style>
    .chatbot-toggle-btn {
        position: fixed;
        right: 24px;
        left: auto;
        bottom: 24px;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(33, 150, 243, 0.4), 0 0 0 0 rgba(33, 150, 243, 0.7);
        cursor: move;
        user-select: none;
        touch-action: none;
        z-index: 1000;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, opacity 0.2s ease;
        padding: 0;
        border: 3px solid rgba(255, 255, 255, 0.3);
        animation: bubblePulse 2s ease-in-out infinite;
    }
    
    @keyframes bubblePulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 8px 24px rgba(33, 150, 243, 0.4), 0 0 0 0 rgba(33, 150, 243, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(33, 150, 243, 0.5), 0 0 0 8px rgba(33, 150, 243, 0);
        }
    }
    
    /* Stop pulse animation when widget is open */
    .chatbot-widget.open ~ .chatbot-toggle-btn,
    .chatbot-toggle-btn:has(+ .chatbot-widget.open) {
        animation: none;
    }
    
    .chatbot-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 36px rgba(33, 150, 243, 0.5), 0 0 0 4px rgba(33, 150, 243, 0.3);
        animation: none;
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
        top: -2px;
        right: -2px;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 11px;
        font-size: 0.7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(239, 68, 68, 0.6), 0 0 0 2px rgba(239, 68, 68, 0.3);
        z-index: 10;
        animation: badgePulse 1.5s ease-in-out infinite;
    }

    @keyframes badgePulse {
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
        width: 70%;
        height: 70%;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        transition: transform 0.2s ease;
    }
    
    .chatbot-toggle-btn:hover img {
        transform: scale(1.1);
    }
    
    .chatbot-toggle-btn.dragging img {
        transform: scale(1.05);
    }

    .chatbot-widget {
        position: fixed !important;
        right: 24px !important;
        left: auto !important;
        bottom: 92px !important;
        width: 360px;
        max-width: calc(100vw - 32px);
        border-radius: 20px 20px 4px 20px;
        background: #ffffff;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.05);
        overflow: visible;
        display: none;
        flex-direction: column;
        z-index: 1000;
        opacity: 0;
        transform: scale(0.8) translateY(20px);
        transform-origin: bottom right;
        transition: opacity 0.3s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), border-radius 0.3s ease;
    }

    .chatbot-widget.open { 
        display: flex;
        opacity: 1;
        transform: scale(1) translateY(0);
        animation: widgetBounce 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    @keyframes widgetBounce {
        0% {
            opacity: 0;
            transform: scale(0.6) translateY(30px);
        }
        60% {
            transform: scale(1.05) translateY(-5px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    /* Chat bubble tail effect - points to the button (right side) */
    .chatbot-widget::before {
        content: '';
        position: absolute;
        bottom: -10px;
        right: 24px;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid #ffffff;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        transition: right 0.3s ease, left 0.3s ease;
    }
    
    /* Adjust tail position when widget is on left side */
    .chatbot-widget.align-left::before {
        right: auto;
        left: 24px;
    }
    
    /* Adjust border radius for left-aligned widget */
    .chatbot-widget.align-left {
        border-radius: 20px 20px 20px 4px;
    }

    .chatbot-header {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: #fff;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
    }

    .chatbot-body {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 0;
        overflow: hidden;
        background: #f8f9fa;
        border-radius: inherit;
    }

    .chatbot-messages {
        height: 320px;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #f8f9fa;
        flex: 1;
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

    .message {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.5rem;
        word-wrap: break-word;
        word-break: break-word;
        white-space: pre-wrap;
        position: relative;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        animation: messageSlideIn 0.3s ease-out;
    }
    
    @keyframes messageSlideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.bot {
        background: #ffffff;
        color: #1f2937;
        border: none;
        align-self: flex-start;
        text-align: left;
        border-top-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    }
    
    /* Add tail to bot messages */
    .message.bot::before {
        content: '';
        position: absolute;
        left: -8px;
        bottom: 0;
        width: 0;
        height: 0;
        border-right: 8px solid #ffffff;
        border-bottom: 8px solid transparent;
        border-top: 8px solid transparent;
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

    .message.user {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: #fff;
        align-self: flex-end;
        border-top-right-radius: 4px;
        box-shadow: 0 1px 2px rgba(33, 150, 243, 0.3);
    }
    
    /* Add tail to user messages */
    .message.user::after {
        content: '';
        position: absolute;
        right: -8px;
        bottom: 0;
        width: 0;
        height: 0;
        border-left: 8px solid #2196F3;
        border-bottom: 8px solid transparent;
        border-top: 8px solid transparent;
    }

    .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chip {
        background: #ffffff;
        color: #1976D2;
        border: 1px solid #e3f2fd;
        padding: 10px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .chip:hover { 
        background: #e3f2fd; 
        transform: translateY(-2px);
        box-shadow: 0 2px 6px rgba(33, 150, 243, 0.2);
        border-color: #90caf9;
    }
    
    .chip:active {
        transform: translateY(0);
    }

    .chatbot-input {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: #ffffff;
        border-top: 1px solid #e5e7eb;
    }

    .chatbot-input input[type="text"] {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        outline: none;
        transition: all 0.2s ease;
        background: #f9fafb;
        font-size: 0.9rem;
        color: #1f2937;
    }
    
    .chatbot-input input[type="text"]::placeholder {
        color: #9ca3af;
    }

    .chatbot-input input[type="text"]:focus {
        border: 1px solid #2196F3;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
    }

    .send-btn {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: #fff;
        border: none;
        padding: 12px;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
        flex-shrink: 0;
    }

    .send-btn:hover { 
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
    }
    
    .send-btn:active {
        transform: scale(0.95);
    }

    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 12px 16px;
        background: #ffffff;
        border-radius: 18px;
        border-top-left-radius: 4px;
        margin-bottom: 0;
        max-width: fit-content;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        position: relative;
    }
    
    .typing-indicator::before {
        content: '';
        position: absolute;
        left: -8px;
        bottom: 0;
        width: 0;
        height: 0;
        border-right: 8px solid #ffffff;
        border-bottom: 8px solid transparent;
        border-top: 8px solid transparent;
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

    @media (max-width: 480px) {
        .chatbot-toggle-btn:not(.dragged):not([style*="left"]) {
            right: 16px !important;
            left: auto !important;
            bottom: 16px !important;
            top: auto !important;
        }
        .chatbot-toggle-btn {
            opacity: 0.5;
            width: 60px;
            height: 60px;
        }
        .chatbot-toggle-btn.clicked {
            opacity: 1;
        }
        .chatbot-widget { 
            left: 16px !important; 
            width: auto; 
            border-radius: 20px 20px 4px 20px;
        }
        .chatbot-widget:not([style*="bottom"]) {
            right: 16px !important;
            bottom: 88px !important;
        }
        .chatbot-widget::before {
            right: 20px;
            left: auto;
        }
        .chatbot-messages { 
            height: 280px; 
            padding: 12px;
        }
        
        .message {
            max-width: 85%;
            padding: 10px 14px;
            font-size: 0.875rem;
        }
        
        .chatbot-input {
            padding: 10px 12px;
        }
        
        .chatbot-input input[type="text"] {
            padding: 10px 14px;
            font-size: 0.875rem;
        }
        
        .send-btn {
            width: 40px;
            height: 40px;
            padding: 10px;
        }
    }

    .chatbot-tabs {
        display: flex;
        gap: 0;
        padding: 8px;
        background: #ffffff;
        border-top: 1px solid #e5e7eb;
    }

    .chatbot-tab {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        position: relative;
    }

    .chatbot-tab:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .chatbot-tab.active {
        background: #2196F3;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.25);
    }

    .chatbot-tab.active:hover {
        background: #1976D2;
    }
    
    .chatbot-tab i {
        font-size: 1rem;
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
    }
    
    #chatbot-close:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        transform: scale(1.1);
    }
    
    #chatbot-close:active {
        transform: scale(0.95);
    }
    
    #chatbot-close i {
        color: #ffffff;
        font-size: 1.1rem;
    }

    [data-theme="dark"] .chatbot-widget {
        background: var(--dm-card-bg, #1e293b);
        color: var(--dm-text-primary, #f1f5f9);
        box-shadow: 0 20px 60px rgba(0,0,0,0.35);
    }

    [data-theme="dark"] .message.bot {
        background: rgba(33, 150, 243, 0.08);
        border-color: rgba(33, 150, 243, 0.25);
        color: var(--dm-text-primary, #f1f5f9);
    }

    [data-theme="dark"] .message.user {
        background: #3b82f6;
    }

    [data-theme="dark"] .chips .chip {
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        border-color: rgba(59, 130, 246, 0.25);
    }

    [data-theme="dark"] .chatbot-tab {
        background: rgba(148, 163, 184, 0.12);
        border-color: rgba(148, 163, 184, 0.2);
        color: var(--dm-text-muted, #94a3b8);
    }

    [data-theme="dark"] .chatbot-tab:hover {
        background: rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.3);
        color: #bfdbfe;
    }

    [data-theme="dark"] .chatbot-tab.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    [data-theme="dark"] .chatbot-input input[type="text"] {
        background: rgba(15, 23, 42, 0.4);
        border-color: rgba(148, 163, 184, 0.3);
        color: #e2e8f0;
    }

    [data-theme="dark"] .chatbot-input input[type="text"]:focus {
        border-color: rgba(96, 165, 250, 0.45);
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2);
    }

    [data-theme="dark"] .typing-indicator {
        background: rgba(59, 130, 246, 0.08);
    }

    [data-theme="dark"] .chatbot-body { background: var(--dm-card-bg, #1e293b); }

    [data-theme="dark"] .chatbot-messages { border-bottom-color: rgba(148, 163, 184, 0.2); }

    [data-theme="dark"] .chatbot-tabs { border-top-color: rgba(148, 163, 184, 0.2); }

    [data-theme="dark"] .chip:hover { background: rgba(59, 130, 246, 0.25); }

    [data-theme="dark"] .chatbot-toggle-btn { box-shadow: 0 10px 30px rgba(33, 150, 243, 0.35); }

    /* Adjust position when scroll-to-top button present */
    .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn {
        bottom: 90px;
    }

    .scroll-to-top-btn + #chatbot-toggle.chatbot-toggle-btn + .chatbot-widget {
        bottom: 158px;
    }
</style>

<div id="chatbot-toggle" class="chatbot-toggle-btn" aria-label="Open chat" title="Chat with us">
    <img src="{{ asset('images/chatbot-logo_3.png') }}" alt="ToothTalk Assistant">
    <span class="chatbot-unread-badge" id="patient-chat-badge" style="display: none;">0</span>
</div>

<div id="chatbot" class="chatbot-widget" role="dialog" aria-modal="false" aria-labelledby="chatbotTitle">
    <div class="chatbot-header">
        <div class="chatbot-title">
            <span class="badge-dot"></span>
            <span id="chatbotTitle">ToothTalk Assistant</span>
        </div>
        <button id="chatbot-close" class="send-btn" aria-label="Close chat" title="Close" style="background:#ffffff22;border:1px solid #ffffff33;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="chatbot-body">
        <div id="chatbot-messages" class="chatbot-messages" aria-live="polite"></div>
        <div class="chips" id="chatbot-chips"></div>
        <div class="chatbot-input" style="display:none;">
            <input id="chatbot-input" type="text" placeholder="Ask about services, hours, pricing..." autocomplete="off" />
            <button id="chatbot-send" class="send-btn" aria-label="Send message">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
        <div class="chatbot-tabs">
            <button id="tab-live-chat" class="chatbot-tab" data-tab="live-chat">
                <i class="bi bi-chat-dots me-1"></i> Live Chat
            </button>
            <button id="tab-faqs" class="chatbot-tab active" data-tab="faqs">
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

        if (!toggleBtn || !widget) {
            return;
        }

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
            
            const saved = localStorage.getItem('chatbot-button-position');
            if (saved) {
                try {
                    const pos = JSON.parse(saved);
                    const padding = pos.padding || SIDE_PADDING;
                    
                    // Ensure button is visible before positioning
                    toggleBtn.style.display = 'flex';
                    toggleBtn.style.visibility = 'visible';
                    toggleBtn.style.opacity = '';
                    
                    if (pos.side === 'left' || pos.side === 'right') {
                        // New format with side snapping
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
                            // Default position if top is invalid
                            toggleBtn.style.bottom = '24px';
                            toggleBtn.style.top = 'auto';
                        }
                        
                        toggleBtn.classList.add('dragged');
                        
                        // Only update positions if elements exist
                        if (widget) {
                            updateWidgetPosition();
                        }
                        updateScrollToTopPosition();
                    } else if (pos.left !== undefined && pos.top !== undefined) {
                        // Legacy format with absolute left/top
                        const centerX = pos.left + (toggleBtn.offsetWidth / 2);
                        const screenCenterX = window.innerWidth / 2;
                        const snapToLeft = centerX < screenCenterX;
                        
                        if (snapToLeft) {
                            toggleBtn.style.left = padding + 'px';
                            toggleBtn.style.right = 'auto';
                        } else {
                            toggleBtn.style.right = padding + 'px';
                            toggleBtn.style.left = 'auto';
                        }
                        
                        const maxY = window.innerHeight - toggleBtn.offsetHeight;
                        const top = Math.max(padding, Math.min(Math.max(0, pos.top), maxY - padding));
                        toggleBtn.style.top = top + 'px';
                        toggleBtn.style.bottom = 'auto';
                        toggleBtn.classList.add('dragged');
                        
                        if (widget) {
                            updateWidgetPosition();
                        }
                        updateScrollToTopPosition();
                    } else if (pos.right !== undefined && pos.bottom !== undefined) {
                        // Legacy support for old saved positions
                        toggleBtn.style.right = pos.right + 'px';
                        toggleBtn.style.left = 'auto';
                        toggleBtn.style.bottom = pos.bottom + 'px';
                        toggleBtn.style.top = 'auto';
                        
                        if (widget) {
                            updateWidgetPosition();
                        }
                        updateScrollToTopPosition();
                    }
                } catch (e) {
                    console.error('Error loading saved position:', e);
                    // Reset to default position on error
                    toggleBtn.style.left = '';
                    toggleBtn.style.right = '';
                    toggleBtn.style.top = '';
                    toggleBtn.style.bottom = '';
                    toggleBtn.classList.remove('dragged');
                }
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
            
            try {
                const rect = toggleBtn.getBoundingClientRect();
                const buttonBottom = window.innerHeight - rect.bottom;
                const buttonRight = window.innerWidth - rect.right;
                const buttonLeft = rect.left;
                const centerX = rect.left + (rect.width / 2);
                const screenCenterX = window.innerWidth / 2;
                const isOnLeft = centerX < screenCenterX;
                
                // On mobile, keep widget full width with margins
                if (window.innerWidth <= 480) {
                    widget.style.left = '16px';
                    widget.style.right = '16px';
                    widget.style.width = 'auto';
                    widget.style.bottom = (buttonBottom + 76) + 'px';
                    widget.style.top = 'auto';
                    widget.classList.remove('align-left', 'align-right');
                    widget.style.borderRadius = '20px 20px 4px 20px';
                } else {
                    // Keep widget aligned with button side on desktop
                    if (isOnLeft) {
                        // Button is on left, align widget to left
                        widget.style.left = buttonLeft + 'px';
                        widget.style.right = 'auto';
                        widget.classList.add('align-left');
                        widget.classList.remove('align-right');
                        widget.style.borderRadius = '20px 20px 20px 4px';
                    } else {
                        // Button is on right, align widget to right
                        widget.style.right = buttonRight + 'px';
                        widget.style.left = 'auto';
                        widget.classList.add('align-right');
                        widget.classList.remove('align-left');
                        widget.style.borderRadius = '20px 20px 4px 20px';
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
                    scrollToTopBtn.style.bottom = '100px';
                } else if (window.innerWidth <= 576) {
                    scrollToTopBtn.style.right = '16px';
                    scrollToTopBtn.style.bottom = '100px';
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

        let currentMode = 'faqs';
        let conversationId = null;
        let pollingInterval = null;
        let lastMessageId = null;
        let faqInitialized = false;
        let liveChatMessages = []; // Store live chat messages
        let faqMessages = []; // Store FAQ messages

        const quickIntents = {!! json_encode($chatbotSetting->quick_intents ?? []) !!};
        const faqRaw = @json($chatbotFaqs ?? []);
        const faqPairs = (faqRaw || []).map(function(f){
            return { q: (f.question || ''), a: (f.answer || '') };
        });

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function addMessage(text, sender) {
            const div = document.createElement('div');
            div.className = 'message ' + (sender === 'user' ? 'user' : 'bot');

            if (sender === 'bot') {
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
                div.innerHTML = formattedHTML || text;
            } else {
                div.textContent = text;
            }

            messagesEl.appendChild(div);
            scrollToBottom();
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
                addMessage(text.trim(), 'user');
                showTypingIndicator();
                const typingDelay = 1000 + Math.random() * 1000;
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(getBotReply(text), 'bot');
                }, typingDelay);
            } else {
                addMessage('Please wait for the chat to initialize...', 'bot');
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
            addMessage(text.trim(), 'user');
            showTypingIndicator();
            const typingDelay = 1000 + Math.random() * 1000;
            setTimeout(() => {
                hideTypingIndicator();
                addMessage(getBotReply(text), 'bot');
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
            widget.classList.add('open');
            widget.setAttribute('aria-hidden', 'false');
            // Stop pulse animation when widget is open
            toggleBtn.style.animation = 'none';
            updateWidgetPosition();

            if (!messagesEl.dataset.checked) {
                if (currentMode === 'faqs') {
                    chipsEl.style.display = 'flex';
                    chipsEl.innerHTML = '';
                    messagesEl.innerHTML = '';
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                        renderChips();
                        faqInitialized = true;
                    }, 800);
                } else if (currentMode === 'live-chat') {
                    chipsEl.style.display = 'none';
                    chipsEl.innerHTML = '';
                    const isAuth = await checkAuth();
                    if (isAuth) {
                        inputEl.placeholder = 'Type your message to staff...';
                        await initializeLiveChat();
                    } else {
                        messagesEl.innerHTML = '';
                        addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
                        const loginBtn = document.createElement('button');
                        loginBtn.className = 'chip';
                        loginBtn.textContent = 'Login to Chat with Staff';
                        loginBtn.style.background = '#0d6efd';
                        loginBtn.style.color = 'white';
                        loginBtn.style.marginTop = '10px';
                        loginBtn.style.width = '100%';
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
                const response = await fetch('{{ route("patient-chat.conversation") }}');
                const data = await response.json();
                conversationId = data.conversation_id;
                await loadMessages();
                startPolling();
            } catch (error) {
                console.error('Error initializing chat:', error);
            }
        }

        function restoreLiveChatMessages() {
            messagesEl.innerHTML = '';
            if (liveChatMessages.length === 0) {
                addMessage("Hello! 👋 Welcome to our dental clinic chat. Our staff is here to assist you with any questions or concerns. How can we help you today?", 'bot');
            } else {
                liveChatMessages.forEach(msg => {
                    const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                    addMessage(msg.message, sender);
                });
            }
        }

        async function loadMessages() {
            if (!conversationId) return;
            try {
                const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                const data = await response.json();
                
                // Store messages for restoration when switching tabs
                liveChatMessages = data.messages || [];
                
                // Show welcome message if no messages exist
                if (liveChatMessages.length === 0) {
                    addMessage("Hello! 👋 Welcome to our dental clinic chat. Our staff is here to assist you with any questions or concerns. How can we help you today?", 'bot');
                } else {
                    liveChatMessages.forEach(msg => {
                        const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                        addMessage(msg.message, sender);
                        if (!lastMessageId || msg.id > lastMessageId) {
                            lastMessageId = msg.id;
                        }
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
                        if (msg.id > lastMessageId) {
                            const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                            addMessage(msg.message, sender);
                            // Update stored messages
                            liveChatMessages.push(msg);
                            lastMessageId = msg.id;
                        }
                    });
                } catch (error) {
                    console.error('Error polling:', error);
                }
            }, 3000);
        }

        async function sendLiveMessage(text) {
            if (!conversationId) return;
            addMessage(text, 'user');
            // Store user message
            liveChatMessages.push({
                sender_type: 'patient',
                message: text,
                id: Date.now() // Temporary ID
            });
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
                    // Update the temporary message with the real one
                    const lastIndex = liveChatMessages.length - 1;
                    if (liveChatMessages[lastIndex] && liveChatMessages[lastIndex].id === Date.now()) {
                        liveChatMessages[lastIndex] = data.message;
                    } else {
                        liveChatMessages.push(data.message);
                    }
                    lastMessageId = data.message.id;
                }
            } catch (error) {
                addMessage('Error sending message. Please try again.', 'bot');
            }
        }

        function closeChat() {
            widget.classList.remove('open');
            widget.setAttribute('aria-hidden', 'true');
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
            // Store current messages before switching
            if (currentMode === 'live-chat') {
                // Save current live chat messages state
                const currentMessages = Array.from(messagesEl.children).map(el => {
                    const isUser = el.classList.contains('user');
                    return {
                        sender_type: isUser ? 'patient' : 'bot',
                        message: isUser ? el.textContent : el.innerHTML,
                        isUser: isUser
                    };
                }).filter(msg => msg.message && msg.message.trim());
                // Only update if we have messages (not just welcome message)
                if (currentMessages.length > 0) {
                    liveChatMessages = currentMessages;
                }
            } else if (currentMode === 'faqs') {
                // Save current FAQ messages state
                const currentMessages = Array.from(messagesEl.children).map(el => {
                    const isUser = el.classList.contains('user');
                    return {
                        sender_type: isUser ? 'patient' : 'bot',
                        message: isUser ? el.textContent : el.innerHTML,
                        isUser: isUser
                    };
                }).filter(msg => msg.message && msg.message.trim());
                if (currentMessages.length > 0) {
                    faqMessages = currentMessages;
                }
            }

            currentMode = mode;
            tabLiveChat.classList.toggle('active', mode === 'live-chat');
            tabFaqs.classList.toggle('active', mode === 'faqs');

            const inputContainer = document.querySelector('.chatbot-input');

            if (mode === 'live-chat') {
                titleEl.textContent = 'Live Chat - Staff';
                inputEl.placeholder = 'Type your message to staff...';
                inputContainer.style.display = 'flex';
                chipsEl.style.display = 'none';
                chipsEl.innerHTML = '';
                stopPolling();
                checkAuth().then(async isAuth => {
                    if (isAuth) {
                        inputEl.disabled = false;
                        sendBtn.disabled = false;
                        if (!conversationId) {
                            initializeLiveChat();
                        } else {
                            // Always load fresh messages from server to ensure we have the latest
                            messagesEl.innerHTML = '';
                            await loadMessages();
                            startPolling();
                        }
                    } else {
                        messagesEl.innerHTML = '';
                        addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
                        const loginBtn = document.createElement('button');
                        loginBtn.className = 'chip';
                        loginBtn.textContent = 'Login to Chat with Staff';
                        loginBtn.style.background = '#0d6efd';
                        loginBtn.style.color = 'white';
                        loginBtn.style.marginTop = '10px';
                        loginBtn.style.width = '100%';
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
                });
            } else {
                titleEl.textContent = 'ToothTalk Assistant';
                inputEl.placeholder = 'Ask about services, hours, pricing...';
                inputContainer.style.display = 'none';
                chipsEl.style.display = 'flex';
                chipsEl.innerHTML = '';
                stopPolling();
                inputEl.disabled = false;
                sendBtn.disabled = false;
                
                // Restore FAQ messages if available
                if (faqMessages.length > 0) {
                    messagesEl.innerHTML = '';
                    faqMessages.forEach(msg => {
                        addMessage(msg.message, msg.isUser ? 'user' : 'bot');
                    });
                    renderChips();
                } else {
                    messagesEl.innerHTML = '';
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                        renderChips();
                    }, 600);
                }
            }
        }

        toggleBtn.addEventListener('click', (e) => {
            // Don't open/close if user was dragging
            if (hasDragged) {
                hasDragged = false;
                return;
            }
            
            // Set opacity to 100% on mobile when clicked
            if (window.innerWidth <= 480) {
                toggleBtn.classList.add('clicked');
                toggleBtn.style.opacity = '1';
            }
            
            if (widget.classList.contains('open')) {
                closeChat();
            } else {
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

        tabLiveChat?.addEventListener('click', () => switchTab('live-chat'));
        tabFaqs?.addEventListener('click', () => switchTab('faqs'));

        widget.setAttribute('aria-hidden', 'true');
    })();

    // Chat unread count polling for patient
    let patientChatUnreadInterval = null;
    
    async function updatePatientChatUnreadCount() {
        try {
            const response = await fetch('{{ route("patient-chat.unread-count") }}');
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
            console.error('Error fetching patient chat unread count:', error);
        }
    }
    
    // Start polling for chat unread count
    if (document.getElementById('patient-chat-badge')) {
        updatePatientChatUnreadCount(); // Initial load
        patientChatUnreadInterval = setInterval(updatePatientChatUnreadCount, 10000); // Update every 10 seconds
    }
</script>
@endif

