<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - Dental Clinic</title>
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
    </style>
</head>
<body class="admin-body">
    {{-- Navigation Menu --}}
    @include('layout.admin.navigation')
    {{-- Main --}}
    <main>
        @yield('content')
    </main>

    {{-- Session monitoring for cross-tab logout detection --}}
    <script>
        (function() {
            // Check if we're on admin portal
            const isAdminPortal = window.location.pathname.startsWith('/admin/');

            if (!isAdminPortal) return;

            // Monitor localStorage for logout events
            window.addEventListener('storage', function(e) {
                if (e.key === 'admin_logout_event' || e.key === 'auth_logout_event') {
                    // Admin logged out in another tab
                    window.location.href = '{{ route("admin.login") }}';
                }
            });

            // Also poll for session changes (backup method)
            let lastSessionCheck = Date.now();
            setInterval(function() {
                // Check for logout events in localStorage
                const logoutEvent = localStorage.getItem('admin_logout_event');
                const generalLogoutEvent = localStorage.getItem('auth_logout_event');

                if (logoutEvent || generalLogoutEvent) {
                    window.location.href = '{{ route("admin.login") }}';
                    return;
                }

                // Check if session active flag was cleared (indicates logout in another tab)
                const sessionActive = localStorage.getItem('admin_session_active');
                if (!sessionActive) {
                    window.location.href = '{{ route("admin.login") }}';
                    return;
                }
            }, 2000); // Check every 2 seconds

            // Set admin session active flag
            localStorage.setItem('admin_session_active', Date.now().toString());
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
</body>
</html>
