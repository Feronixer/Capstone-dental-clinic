<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Portal - Dental Clinic</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Initialize dark mode on page load
        (function() {
            const savedTheme = localStorage.getItem('darkMode') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <style>
        /* Global Scrollbar Styles - White & Blue Theme */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #ffffff;
            border-radius: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-radius: 6px;
            border: 2px solid #ffffff;
            transition: background 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        }

        ::-webkit-scrollbar-corner {
            background: #ffffff;
        }

        [data-theme="dark"] ::-webkit-scrollbar-track {
            background: var(--dm-bg-secondary, #1e293b);
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-color: var(--dm-bg-secondary, #1e293b);
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        }

        [data-theme="dark"] ::-webkit-scrollbar-corner {
            background: var(--dm-bg-secondary, #1e293b);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #2196F3 #ffffff;
        }

        [data-theme="dark"] * {
            scrollbar-color: #2196F3 var(--dm-bg-secondary, #1e293b);
        }

        /* Inactivity Blur Styles */
        body.inactive-blur {
            position: relative;
        }

        body.inactive-blur::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            z-index: 99998;
            pointer-events: none;
        }

        body.inactive-blur > *:not(.inactivity-password-modal) {
            filter: blur(30px);
            transition: filter 0.5s ease;
            pointer-events: none;
            user-select: none;
        }

        body.inactive-blur > *:not(.inactivity-password-modal) * {
            pointer-events: none;
        }

        /* Password modal should not be blurred and always on top */
        .inactivity-password-modal {
            filter: none !important;
            -webkit-filter: none !important;
            pointer-events: auto !important;
            z-index: 99999 !important;
            position: fixed !important;
        }

        .inactivity-password-modal * {
            filter: none !important;
            -webkit-filter: none !important;
            pointer-events: auto !important;
        }

        /* Prevent any interaction with blurred content when modal is open */
        body.inactive-blur:has(.inactivity-password-modal:not(.hidden)) {
            overflow: hidden;
        }

        /* Password Modal Styles */
        .inactivity-password-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            backdrop-filter: blur(5px);
        }

        .inactivity-password-modal.hidden {
            display: none;
        }

        .inactivity-password-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            text-align: center;
            filter: none !important;
            -webkit-filter: none !important;
            position: relative;
            z-index: 1;
        }

        [data-theme="dark"] .inactivity-password-content {
            background: var(--dm-card-bg, #1e293b);
            color: var(--dm-text-primary, #f1f5f9);
        }

        .inactivity-password-content h3 {
            margin-bottom: 1rem;
            color: #1e293b;
            font-weight: 700;
        }

        [data-theme="dark"] .inactivity-password-content h3 {
            color: var(--dm-text-primary, #f1f5f9);
        }

        .inactivity-password-content p {
            margin-bottom: 1.5rem;
            color: #64748b;
        }

        [data-theme="dark"] .inactivity-password-content p {
            color: var(--dm-text-muted, #94a3b8);
        }

        .inactivity-password-content input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 1rem;
            filter: none !important;
            -webkit-filter: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        [data-theme="dark"] .inactivity-password-content input {
            background: var(--dm-bg-secondary, #0f172a);
            border-color: var(--dm-border-color, #334155);
            color: var(--dm-text-primary, #f1f5f9);
        }

        .inactivity-password-content .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            display: none;
        }

        .inactivity-password-content .error-message.show {
            display: block;
        }
    </style>
</head>
<body class="admin-body">
    {{-- Navigation Menu --}}
    @include('layout.staff.navigation')
    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Session monitoring for cross-tab logout detection --}}
    <script>
        (function() {
            // Check if we're on staff portal
            const isStaffPortal = window.location.pathname.startsWith('/staff/');

            if (!isStaffPortal) return;

            // Monitor localStorage for logout events
            window.addEventListener('storage', function(e) {
                if (e.key === 'staff_logout_event' || e.key === 'auth_logout_event') {
                    // Staff logged out in another tab
                    window.location.href = '{{ route("staff.login") }}';
                }
            });

            // Also poll for session changes (backup method)
            let lastSessionCheck = Date.now();
            setInterval(function() {
                // Check for logout events in localStorage
                const logoutEvent = localStorage.getItem('staff_logout_event');
                const generalLogoutEvent = localStorage.getItem('auth_logout_event');

                if (logoutEvent || generalLogoutEvent) {
                    window.location.href = '{{ route("staff.login") }}';
                    return;
                }

                // Check if session active flag was cleared (indicates logout in another tab)
                const sessionActive = localStorage.getItem('staff_session_active');
                if (!sessionActive) {
                    window.location.href = '{{ route("staff.login") }}';
                    return;
                }
            }, 2000); // Check every 2 seconds

            // Set staff session active flag
            localStorage.setItem('staff_session_active', Date.now().toString());
        })();
    </script>

    {{-- Dark Mode Toggle Script --}}
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('darkMode', newTheme);

            // Update icon in navigation
            const darkModeLinks = document.querySelectorAll('.dark-mode-toggle-btn');
            darkModeLinks.forEach(link => {
                const icon = link.querySelector('i');
                const span = link.querySelector('span');
                if (newTheme === 'dark') {
                    if (icon) icon.className = 'bi bi-sun';
                    if (span) span.textContent = 'Light Mode';
                } else {
                    if (icon) icon.className = 'bi bi-moon-stars';
                    if (span) span.textContent = 'Dark Mode';
                }
            });

            // Update icon in patient header
            const darkModeIcon = document.getElementById('darkModeIcon');
            if (darkModeIcon) {
                darkModeIcon.className = newTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        }

        // Update icon on page load
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const darkModeLinks = document.querySelectorAll('.dark-mode-toggle-btn');
            darkModeLinks.forEach(link => {
                const icon = link.querySelector('i');
                const span = link.querySelector('span');
                if (currentTheme === 'dark') {
                    if (icon) icon.className = 'bi bi-sun';
                    if (span) span.textContent = 'Light Mode';
                } else {
                    if (icon) icon.className = 'bi bi-moon-stars';
                    if (span) span.textContent = 'Dark Mode';
                }
            });
        });
    </script>

    {{-- Inactivity Password Modal --}}
    <div id="inactivityPasswordModal" class="inactivity-password-modal hidden">
        <div class="inactivity-password-content">
            <h3><i class="bi bi-shield-lock"></i> Session Locked</h3>
            <p>Your session has been locked due to inactivity. Please enter your password to continue.</p>
            <form id="inactivityPasswordForm">
                <input type="password" id="inactivityPassword" placeholder="Enter your password" autocomplete="current-password" required autofocus>
                <div class="error-message" id="inactivityPasswordError"></div>
                <button type="submit" class="btn btn-primary w-100" style="padding: 0.75rem; border-radius: 8px; font-weight: 600;">Unlock</button>
            </form>
        </div>
    </div>

    {{-- Inactivity Blur Script --}}
    <script>
        (function() {
            // Check if we're on staff portal
            const isStaffPortal = window.location.pathname.startsWith('/staff/');
            if (!isStaffPortal) return;

            let inactivityTimer;
            let passwordTimer;
            const INACTIVITY_TIMEOUT = 30000; // 30 seconds in milliseconds
            const PASSWORD_TIMEOUT = 600000; // 10 minutes in milliseconds
            const body = document.body;
            const passwordModal = document.getElementById('inactivityPasswordModal');
            const passwordForm = document.getElementById('inactivityPasswordForm');
            const passwordInput = document.getElementById('inactivityPassword');
            const passwordError = document.getElementById('inactivityPasswordError');
            let isPasswordModalOpen = false;

            // Function to reset the inactivity timer
            function resetInactivityTimer() {
                // If password modal is open, don't reset - require password first
                if (isPasswordModalOpen) {
                    return;
                }

                // Clear existing timers
                clearTimeout(inactivityTimer);
                clearTimeout(passwordTimer);
                
                // Remove blur if it exists
                body.classList.remove('inactive-blur');
                // Clear blur state from localStorage
                localStorage.removeItem('staff_inactivity_blurred');
                
                // Set blur timer (30 seconds)
                inactivityTimer = setTimeout(function() {
                    body.classList.add('inactive-blur');
                    // Save blur state to localStorage
                    localStorage.setItem('staff_inactivity_blurred', 'true');
                }, INACTIVITY_TIMEOUT);

                // Set password timer (1 minute)
                passwordTimer = setTimeout(function() {
                    showPasswordModal();
                }, PASSWORD_TIMEOUT);
            }

            // Function to show password modal
            function showPasswordModal() {
                isPasswordModalOpen = true;
                // Ensure blur is applied before showing modal
                body.classList.add('inactive-blur');
                passwordModal.classList.remove('hidden');
                passwordInput.value = '';
                passwordError.textContent = '';
                passwordError.classList.remove('show');
                passwordInput.focus();
                // Save state to localStorage
                localStorage.setItem('staff_inactivity_locked', 'true');
                localStorage.setItem('staff_inactivity_blurred', 'true');
            }

            // Function to close password modal
            function closePasswordModal() {
                isPasswordModalOpen = false;
                passwordModal.classList.add('hidden');
                passwordInput.value = '';
                passwordError.textContent = '';
                passwordError.classList.remove('show');
                // Clear state from localStorage
                localStorage.removeItem('staff_inactivity_locked');
                localStorage.removeItem('staff_inactivity_blurred');
            }

            // Handle password form submission
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const password = passwordInput.value;
                if (!password) {
                    passwordError.textContent = 'Please enter your password.';
                    passwordError.classList.add('show');
                    return;
                }

                // Disable form during verification
                const submitBtn = passwordForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Verifying...';

                // Verify password via AJAX
                fetch('{{ route("staff.verify-inactivity-password") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ password: password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Password correct - unlock session (blur will be removed in resetInactivityTimer)
                        closePasswordModal();
                        resetInactivityTimer();
                    } else {
                        // Password incorrect - keep blur and modal open
                        passwordError.textContent = data.message || 'Incorrect password. Please try again.';
                        passwordError.classList.add('show');
                        passwordInput.value = '';
                        passwordInput.focus();
                    }
                })
                .catch(error => {
                    passwordError.textContent = 'An error occurred. Please try again.';
                    passwordError.classList.add('show');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Unlock';
                });
            });

            // Activity events to monitor
            const activityEvents = [
                'mousedown',
                'mousemove',
                'mouseup',
                'click',
                'scroll',
                'wheel',
                'keydown',
                'keypress',
                'keyup',
                'touchstart',
                'touchmove',
                'touchend'
            ];

            // Add event listeners for all activity events
            activityEvents.forEach(function(event) {
                document.addEventListener(event, function(e) {
                    // If password modal is open, don't allow any activity to reset timer
                    // User must enter password first
                    if (isPasswordModalOpen) {
                        // Only allow interaction with the password modal itself
                        if (e.target !== passwordInput && 
                            e.target !== passwordForm && 
                            !passwordForm.contains(e.target) &&
                            !passwordModal.contains(e.target)) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                        return;
                    }
                    resetInactivityTimer();
                }, true);
            });

            // Restore state on page load
            function restoreInactivityState() {
                const isLocked = localStorage.getItem('staff_inactivity_locked') === 'true';
                const isBlurred = localStorage.getItem('staff_inactivity_blurred') === 'true';
                
                if (isLocked) {
                    // Password modal was open - restore it
                    isPasswordModalOpen = true;
                    body.classList.add('inactive-blur');
                    passwordModal.classList.remove('hidden');
                    passwordInput.focus();
                } else if (isBlurred) {
                    // Only blur was active - restore blur
                    body.classList.add('inactive-blur');
                    // Restart timers from where they left off
                    // Since we don't know exact time, restart from beginning
                    resetInactivityTimer();
                } else {
                    // No state to restore - start fresh
                    resetInactivityTimer();
                }
            }

            // Initialize on page load
            restoreInactivityState();
        })();
    </script>
</body>
</html>
