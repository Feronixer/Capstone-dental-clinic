<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JValera Dental Clinic - Patient Portal</title>
    <script>
        // Initialize dark mode on page load
        (function() {
            const savedTheme = localStorage.getItem('darkMode') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
    </style>
</head>
<body>
    {{-- Top Announcement Bar --}}
    @include('layout.patient.top-header')

    {{-- Main Navigation Header --}}
    @include('layout.patient.header')

    {{-- Main Content --}}
    <main class="main-wrapper">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layout.patient.footer')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Session monitoring for cross-tab logout detection --}}
    <script>
        (function() {
            // Check if we're on patient portal
            const isPatientPortal = window.location.pathname.startsWith('/patient/');

            if (!isPatientPortal) return;

            // Monitor localStorage for logout events
            window.addEventListener('storage', function(e) {
                if (e.key === 'patient_logout_event' || e.key === 'auth_logout_event') {
                    // Patient logged out in another tab
                    window.location.href = '{{ route("login") }}';
                }
            });

            // Also poll for session changes (backup method)
            let lastSessionCheck = Date.now();
            setInterval(function() {
                // Check for logout events in localStorage
                const logoutEvent = localStorage.getItem('patient_logout_event');
                const generalLogoutEvent = localStorage.getItem('auth_logout_event');

                if (logoutEvent || generalLogoutEvent) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                // Check if session active flag was cleared (indicates logout in another tab)
                const sessionActive = localStorage.getItem('patient_session_active');
                if (!sessionActive) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }
            }, 2000); // Check every 2 seconds

            // Set patient session active flag
            localStorage.setItem('patient_session_active', Date.now().toString());
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
            const darkModeIcon = document.getElementById('darkModeIcon');
            if (darkModeIcon) {
                darkModeIcon.className = currentTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }

            // Update navigation icons
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
