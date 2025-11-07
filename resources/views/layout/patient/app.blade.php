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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            color: #333;
        }

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

        body.no-scroll {
            overflow: hidden;
        }

        /* Scroll to Top Button */
        .scroll-to-top-btn {
            position: fixed;
            right: 24px;
            bottom: 100px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.85) 0%, rgba(25, 118, 210, 0.85) 100%);
            color: white;
            border: none;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
            cursor: pointer;
            z-index: 999;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            font-size: 1.2rem;
        }

        .scroll-to-top-btn.show {
            display: flex;
            opacity: 0.85;
            transform: translateY(0);
        }

        .scroll-to-top-btn:hover {
            opacity: 1;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(33, 150, 243, 0.5);
            background: linear-gradient(135deg, rgba(25, 118, 210, 0.95) 0%, rgba(21, 101, 192, 0.95) 100%);
        }

        .scroll-to-top-btn:active {
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .scroll-to-top-btn {
                right: 20px;
                bottom: 90px;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .scroll-to-top-btn {
                right: 16px;
                bottom: 75px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
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

    <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top" title="Scroll to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/realtime-updates.js') }}"></script>
    <script>
        // Set user role for real-time updates
        window.userRole = 'patient';
    </script>

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

    {{-- Scroll to Top Button Script --}}
    <script>
        (function() {
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');
            
            if (!scrollToTopBtn) return;

            // Show/hide button based on scroll position
            function toggleScrollButton() {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.classList.add('show');
                } else {
                    scrollToTopBtn.classList.remove('show');
                }
            }

            // Scroll to top function
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // Event listeners
            window.addEventListener('scroll', toggleScrollButton);
            scrollToTopBtn.addEventListener('click', scrollToTop);

            // Initial check
            toggleScrollButton();
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
