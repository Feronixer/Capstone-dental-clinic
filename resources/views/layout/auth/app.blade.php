<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    {{-- Section --}}
    @yield('content')

    {{-- Cross-tab logout notification --}}
    <script>
        (function() {
            // Determine which login page we're on
            const path = window.location.pathname;
            let logoutEventKey = 'auth_logout_event';

            if (path.includes('/admin/login')) {
                logoutEventKey = 'admin_logout_event';
            } else if (path.includes('/staff/login')) {
                logoutEventKey = 'staff_logout_event';
            } else if (path.includes('/login') && !path.includes('/admin') && !path.includes('/staff')) {
                logoutEventKey = 'patient_logout_event';
            }

            // Notify other tabs that user logged out and landed on login page
            localStorage.setItem(logoutEventKey, Date.now().toString());

            // Also set general logout event
            localStorage.setItem('auth_logout_event', Date.now().toString());

            // Clear session active flags
            localStorage.removeItem('admin_session_active');
            localStorage.removeItem('staff_session_active');
            localStorage.removeItem('patient_session_active');

            // Clean up events after a short delay
            setTimeout(function() {
                localStorage.removeItem(logoutEventKey);
                localStorage.removeItem('auth_logout_event');
            }, 500);
        })();
    </script>
</body>
</html>
