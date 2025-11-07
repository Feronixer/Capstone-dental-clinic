@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
<style>
    .chatbot-toggle-btn {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #2196F3;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(33, 150, 243, 0.4);
        cursor: pointer;
        z-index: 1000;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        padding: 8px;
    }

    .chatbot-toggle-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 14px 36px rgba(33, 150, 243, 0.45);
        background: #1976D2;
    }

    .chatbot-toggle-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .chatbot-widget {
        position: fixed;
        right: 24px;
        bottom: 92px;
        width: 340px;
        max-width: calc(100vw - 32px);
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        overflow: hidden;
        display: none;
        flex-direction: column;
        z-index: 1000;
    }

    .chatbot-widget.open { display: flex; }

    .chatbot-header {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: #fff;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
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
        gap: 10px;
        padding: 12px;
    }

    .chatbot-messages {
        height: 280px;
        overflow-y: auto;
        padding-right: 4px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        border-bottom: 1px solid #eef2f5;
    }

    .message {
        max-width: 82%;
        padding: 10px 12px;
        border-radius: 14px;
        font-size: 0.92rem;
        line-height: 1.6rem;
        word-wrap: break-word;
        word-break: break-word;
        white-space: pre-wrap;
    }

    .message.bot {
        background: #f5f9ff;
        color: #263238;
        border: 1px solid #e3f2fd;
        align-self: flex-start;
        text-align: left;
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
        background: #2196F3;
        color: #fff;
        align-self: flex-end;
    }

    .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chip {
        background: #e3f2fd;
        color: #1976D2;
        border: 1px solid #bbdefb;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.1s ease;
    }

    .chip:hover { background: #d2e9fb; transform: translateY(-1px); }

    .chatbot-input {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 0 0;
    }

    .chatbot-input input[type="text"] {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #dfe7ef;
        border-radius: 10px;
        outline: none;
        transition: border 0.2s ease, box-shadow 0.2s ease;
    }

    .chatbot-input input[type="text"]:focus {
        border: 1px solid #90caf9;
        box-shadow: 0 0 0 3px rgba(144,202,249,0.25);
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
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .send-btn:hover { background: #1976D2; }

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

    @media (max-width: 480px) {
        .chatbot-widget { right: 16px; left: 16px; width: auto; }
        .chatbot-messages { height: 240px; }
    }

    .chatbot-tabs {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #eef2f5;
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

        let currentMode = 'faqs';
        let conversationId = null;
        let pollingInterval = null;
        let lastMessageId = null;
        let faqInitialized = false;

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
                return 'Hello! Welcome to our dental clinic. How can I assist you today? You can ask about our services, hours, pricing, or schedule an appointment.';
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

        async function loadMessages() {
            if (!conversationId) return;
            try {
                const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                const data = await response.json();
                data.messages.forEach(msg => {
                    const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                    addMessage(msg.message, sender);
                    if (!lastMessageId || msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }
                });
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        function startPolling() {
            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(async () => {
                if (!conversationId) return;
                try {
                    const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                    const data = await response.json();
                    data.messages.forEach(msg => {
                        if (msg.id > lastMessageId) {
                            const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                            addMessage(msg.message, sender);
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
        }

        function switchTab(mode) {
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
                checkAuth().then(isAuth => {
                    if (isAuth) {
                        messagesEl.innerHTML = '';
                        inputEl.disabled = false;
                        sendBtn.disabled = false;
                        if (!conversationId) {
                            initializeLiveChat();
                        } else {
                            loadMessages();
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
                messagesEl.innerHTML = '';
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                    renderChips();
                }, 600);
            }
        }

        toggleBtn.addEventListener('click', () => {
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
</script>
@endif

