<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>ToothTalk - Premium Dental Care</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo7.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo7.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo7.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html { 
            width: 100%; 
            overflow-x: hidden; 
            max-width: 100vw;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        /* Prevent overflow in containers */
        img, video, iframe, embed, object {
            max-width: 100%;
            height: auto;
        }

        section, main, article, header, footer {
            max-width: 100%;
            box-sizing: border-box;
        }

        [data-theme="dark"] body {
            background: linear-gradient(135deg, var(--dm-bg-primary) 0%, var(--dm-bg-secondary) 100%) !important;
        }

        /* Dark Mode Hero Section (match patient dashboard) */
        [data-theme="dark"] .hero-title {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }
        [data-theme="dark"] .hero-title .highlight {
            color: #60a5fa !important;
        }
        [data-theme="dark"] .hero-title .highlight:hover {
            color: #93c5fd !important;
            text-shadow: 0 6px 18px rgba(147, 197, 253, 0.55), 0 0 16px rgba(147,197,253,0.7);
            filter: saturate(1.1);
        }
        [data-theme="dark"] .hero-description { color: var(--dm-text-muted, #94a3b8) !important; }
        [data-theme="dark"] .badge { background: rgba(59,130,246,0.2) !important; color: #93c5fd !important; }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            background: transparent;
            position: relative;
            z-index: 20;
            pointer-events: auto;
        }

        .navbar * {
            pointer-events: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: #2196F3;
        }

        .logo-text-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0;
            align-items: flex-start;
            justify-content: center;
        }

        .logo span {
            font-weight: 800;
        }

        .clinic-subtitle {
            font-size: 0.8rem;
            opacity: 1;
            color: #1976D2;
            font-weight: 600;
            margin-top: 1px;
            line-height: 1.2;
            display: block !important;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }

        .logo-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            display: inline-block;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        /* Hamburger Menu Styles - Match announcement.blade.php */
        .menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #2196F3;
            color: #fff;
            border: none;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 10002;
        }

        /* When menu is open, pin the toggle above everything */
        .menu-toggle.fixed-open {
            position: fixed;
            top: 12px;
            right: 12px;
            background: #ffffff;
            color: #1976D2;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Burger icon (hamburger -> X) */
        .burger-icon {
            position: relative;
            width: 22px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .burger-icon .bar {
            position: absolute;
            left: 0;
            width: 100%;
            height: 2px;
            display: block;
            background: currentColor;
            border-radius: 2px;
            transition: transform 0.25s ease, top 0.25s ease, opacity 0.2s ease;
        }
        .burger-icon .bar:nth-child(1) { top: 0; }
        .burger-icon .bar:nth-child(2) { top: 7px; }
        .burger-icon .bar:nth-child(3) { top: 14px; }
        .burger-icon.open .bar:nth-child(1) { top: 7px; transform: rotate(45deg); }
        .burger-icon.open .bar:nth-child(2) { opacity: 0; }
        .burger-icon.open .bar:nth-child(3) { top: 7px; transform: rotate(-45deg); }

        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            right: -100%;
            width: 320px;
            max-width: 85vw;
            height: 100vh;
            height: 100dvh; /* Dynamic viewport height - accounts for system navigation */
            background: #ffffff;
            box-shadow: -4px 0 30px rgba(0,0,0,0.2);
            z-index: 10001;
            transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            padding-bottom: env(safe-area-inset-bottom, 0);
            padding-top: env(safe-area-inset-top, 0);
            pointer-events: none;
        }

        .mobile-menu-overlay.active {
            right: 0;
            pointer-events: auto;
        }

        .mobile-menu-backdrop {
            pointer-events: none;
        }

        .mobile-menu-backdrop.active {
            pointer-events: auto;
        }

        .mobile-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: #ffffff;
            color: #1976D2;
            position: sticky;
            top: 0;
            z-index: 2;
            box-shadow: 0 1px 0 rgba(0,0,0,0.06);
        }

        .mobile-menu-close {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(33, 150, 243, 0.1);
            border: none;
            color: #1976D2;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .mobile-menu-close:hover {
            background: rgba(33, 150, 243, 0.2);
            color: #2196F3;
        }

        .mobile-nav { 
            padding: 0.75rem; 
            display: flex; 
            flex-direction: column; 
            gap: 0.75rem; 
            flex: 1;
        }
        .mobile-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #ffffff;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 6px 18px rgba(33, 150, 243, 0.25);
        }
        .mobile-nav a:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(33,150,243,0.35); }

        /* Account section at bottom */
        .mobile-account-section {
            margin-top: auto;
            padding: 0.75rem;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .mobile-account-label {
            color: #495057;
            font-size: 0.95rem;
            font-weight: 600;
            padding-left: 0.25rem;
            display: block;
        }

        .mobile-account-item {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.75rem;
            padding: 0.9rem 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .mobile-account-item:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            transform: translateY(-2px);
        }

        .mobile-account-item-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2196F3;
        }

        .mobile-account-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.65rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Backdrop behind the menu */
        .mobile-menu-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(1px);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            z-index: 9999; /* Behind the menu-toggle (10002) and overlay (10001) */
        }
        .mobile-menu-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .nav-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .nav-btn.primary {
            background: #2196F3;
            color: white;
        }

        .nav-btn.primary:hover {
            background: #1976D2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        }

        .nav-btn.secondary {
            background: white;
            color: #2196F3;
            border: 2px solid #2196F3;
        }

        .nav-btn.secondary:hover {
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 3rem;
            gap: 4rem;
            max-width: 1400px;
            margin: 0 auto;
            margin-bottom: 2rem; /* spacing before next section */
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(33, 150, 243, 0.1);
            color: #2196F3;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .hero-title {
            font-size: 68px;
            font-weight: 800;
            color: #263238;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }

        .hero-title .highlight {
            color: #2196F3;
        }

        /* Hover animation for SMILE (match patient dashboard) */
        .hero-title .highlight {
            position: relative;
            transition: color 220ms ease, filter 220ms ease, text-shadow 220ms ease;
        }
        .hero-title .highlight:hover {
            color: #ffffff;
            text-shadow: 0 6px 18px rgba(10, 42, 107, 0.65), 0 0 10px rgba(10,42,107,0.35);
            filter: saturate(1.2);
        }

        .hero-title .no-time {
            white-space: nowrap;
            display: inline-block;
        }

        .hero-description {
            font-size: 1.1rem;
            color: #546e7a;
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .patient-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            background: #2196F3;
            color: white;
            padding: 1rem 2rem;
            border-radius: 30px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 3rem;
        }

        .patient-login-btn:hover {
            background: #1976D2;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.3);
        }

        .staff-admin-section {
            margin-top: 2rem;
        }

        .staff-admin-section h3 {
            font-size: 0.9rem;
            color: #78909c;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .login-buttons {
            display: flex;
            gap: 1rem;
        }

        .login-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.9rem 1.8rem;
            border-radius: 30px;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .login-btn.staff {
            background: #1e88e5;
            color: white;
        }

        .login-btn.staff:hover {
            background: #1976d2;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 136, 229, 0.3);
        }

        .login-btn.admin {
            background: #455a64;
            color: white;
        }

        .login-btn.admin:hover {
            background: #37474f;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(69, 90, 100, 0.3);
        }

       /* Hero Card */
        .hero-card {
            flex: 1;
            max-width: 550px;
            position: relative;
            padding: 1rem;
        }

        .main-card {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.95) 0%, rgba(25, 118, 210, 0.98) 50%, rgba(21, 101, 192, 1) 100%);
            border-radius: 30px;
            padding: 3rem;
            min-height: 450px;
            box-shadow: 0 15px 45px rgba(0, 0, 128, 0.15), 0 8px 20px rgba(0, 0, 128, 0.12), 0 4px 10px rgba(0, 0, 128, 0.08);
            position: relative;
            overflow: visible;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            z-index: 1;
        }

        .main-card-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 135%;
            max-width: none;
            height: auto;
            min-height: 100%;
            object-fit: cover;
            opacity: 1;
            z-index: 1;
            filter: brightness(1) contrast(1) drop-shadow(0 8px 24px rgba(128, 128, 128, 0.25));
            pointer-events: none;
            mix-blend-mode: normal;
        }

        .main-card::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -15%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: 2;
            filter: blur(20px);
        }

        .main-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: 2;
            filter: blur(25px);
        }

        .card-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.4rem;
            font-weight: 700;
            text-align: left;
            position: absolute;
            bottom: 2rem;
            left: 2rem;
            width: calc(100% - 4rem);
            z-index: 15;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35), 0 1px 4px rgba(0, 0, 0, 0.25), 0 0 10px rgba(0, 234, 255, 0.6), 0 0 20px rgba(0, 234, 255, 0.4), 0 0 30px rgba(0, 234, 255, 0.3);
            opacity: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title i {
            color: #00EAFF;
            font-size: 1.6rem;
            filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.8)) drop-shadow(0 0 16px rgba(0, 234, 255, 0.6));
            animation: arrow-pulse 2s ease-in-out infinite;
        }

        @keyframes arrow-pulse {
            0%, 100% {
                transform: translateX(0);
                filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.8)) drop-shadow(0 0 16px rgba(0, 234, 255, 0.6));
            }
            50% {
                transform: translateX(5px);
                filter: drop-shadow(0 0 12px rgba(0, 234, 255, 1)) drop-shadow(0 0 24px rgba(0, 234, 255, 0.8));
            }
        }

        .feature-card {
            position: absolute;
            background: white;
            border-radius: 20px;
            padding: 1.3rem 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 128, 0.12), 0 4px 12px rgba(0, 0, 128, 0.08), 0 2px 6px rgba(0, 0, 128, 0.06);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            max-width: 250px;
            z-index: 15;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 128, 0.15), 0 6px 18px rgba(0, 0, 128, 0.12), 0 3px 9px rgba(0, 0, 128, 0.08);
        }

        .feature-card.top {
            top: 2rem;
            right: -2rem;
        }

        .feature-card.bottom {
            bottom: 8rem;
            right: -2rem;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2196F3;
            font-size: 1.4rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 128, 0.08), 0 1px 4px rgba(0, 0, 128, 0.06);
        }

        .feature-text h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #263238;
            margin-bottom: 0.3rem;
        }

        .feature-text p {
            font-size: 0.85rem;
            color: #78909c;
            line-height: 1.4;
        }

        @media (max-width: 1024px) {
            .hero-section {
                padding: 2rem;
                margin-bottom: 1.5rem;
                gap: 2rem;
                justify-content: center;
            }

            .hero-content {
                max-width: 100%;
                align-items: flex-start;
                flex: 1;
            }

            .hero-card {
                flex: 1;
                max-width: 100%;
            }

            .hero-title { font-size: 42px; }

            .main-card {
                min-height: 420px;
                padding: 1.5rem 1.5rem 2.25rem;
                width: 100%;
            }

            .main-card-image {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 135%;
                max-width: none;
                height: auto;
                min-height: 100%;
                object-fit: cover;
                opacity: 1;
                z-index: 1;
                filter: brightness(1) contrast(1) drop-shadow(0 8px 24px rgba(128, 128, 128, 0.25));
                pointer-events: none;
                mix-blend-mode: normal;
            }

            /* Keep cards absolutely positioned on right side */
            .feature-card { 
                position: absolute !important;
                max-width: 200px;
                padding: 0.85rem 0.9rem;
                box-shadow: 0 8px 22px rgba(0,0,0,0.12);
                z-index: 10;
            }
            .feature-card.top { top: 0.5rem !important; right: 0.5rem !important; }
            .feature-card.bottom { bottom: 5rem !important; right: 0.5rem !important; }
            .feature-text h4 { font-size: 0.95rem; }
            .feature-text p { font-size: 0.8rem; }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: row; /* keep in one line */
                align-items: center;
                gap: 0.75rem;
                width: 100%;
            }

            .logo { flex: 1; font-size: 1.3rem; }
            .clinic-subtitle { font-size: 0.7rem !important; }
            .nav-links { display: none; }
            .menu-toggle {
                display: inline-flex !important;
                margin-left: auto;
                position: relative;
                pointer-events: auto;
                -webkit-tap-highlight-color: transparent;
                user-select: none;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .hero-section {
                flex-direction: row; /* keep side-by-side at 768px */
                padding: 1.5rem 1rem;
                gap: 1.5rem;
                align-items: flex-start;
                justify-content: center;
            }

            .hero-content {
                max-width: 100%;
                align-items: flex-start;
                flex: 1;
                min-width: 0;
            }

            .hero-card {
                flex: 1;
                max-width: 100%;
                min-width: 0;
                padding: 0.5rem;
            }

            .badge {
                font-size: 16px;
                padding: 8px 16px;
            }

            .hero-title {
                font-size: 72px;
            }

            .patient-login-btn {
                margin-bottom: 2rem;
                width: 100%;
                max-width: 100%;
                justify-content: center;
            }

            .login-buttons {
                width: 100%;
            }

            .login-btn {
                width: 100%;
                max-width: 100%;
                justify-content: center;
            }

            .staff-admin-section {
                width: 100%;
                align-items: flex-start;
            }

            .main-card {
                min-height: 360px;
                padding: 1.25rem 1.25rem 2.25rem;
                border-radius: 24px;
                position: relative;
                overflow: visible;
                width: 100%;
            }

            .main-card-image {
                position: absolute;
                top: 50%;
                left: 60%;
                transform: translate(-50%, -50%);
                width: 135%;
                max-width: none;
                height: auto;
                min-height: 100%;
                object-fit: cover;
                opacity: 1;
                z-index: 1;
                filter: brightness(1) contrast(1) drop-shadow(0 8px 24px rgba(128, 128, 128, 0.25));
                pointer-events: none;
                mix-blend-mode: normal;
            }

            .card-title {
                position: absolute;
                left: 1rem;
                bottom: -2.5rem;
                width: calc(100% - 2rem);
                text-align: left;
                font-size: 2rem;
            }

            .card-title i {
            color: #00EAFF;
            font-size: 2rem;
            filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.8)) drop-shadow(0 0 16px rgba(0, 234, 255, 0.6));
            animation: arrow-pulse 2s ease-in-out infinite;
        }

            .feature-card {
                position: absolute !important;
                padding: 0.9rem 1rem;
                max-width: 240px;
                z-index: 10;
            }
            .feature-card.top { top: 0.75rem !important; right: 0.75rem !important; }
            .feature-card.bottom { bottom: 2.25rem !important; right: 0.75rem !important; }

            .login-buttons { flex-direction: column; align-items: flex-start; }
            .login-btn { font-size: 0.98rem; padding: 0.85rem 1.6rem; border-radius: 28px; }
        }

        /* Stack hero vertically on medium-small screens */
        @media (max-width: 640px) {
            .hero-section {
                flex-direction: column;
                gap: 5rem;
                margin-bottom: 7rem;
                align-items: stretch;
            }

            .hero-content {
                width: 100%;
                max-width: 100%;
            }

            .hero-card {
                width: 100%;
                max-width: 100%;
                padding: 0.5rem;
            }

            .card-title {
                position: absolute;
                left: 1rem;
                bottom: -2.5rem;
                width: calc(100% - 2rem);
                text-align: left;
                font-size: 1.5rem;
            }            

            /* Keep cards absolutely positioned on right side */
            .feature-card { 
                position: absolute !important;
                max-width: 200px;
                padding: 0.85rem 0.9rem;
                box-shadow: 0 8px 22px rgba(0,0,0,0.12);
                z-index: 10;
            }
            .feature-card.top { top: 0.5rem !important; right: -0.5rem !important; }
            .feature-card.bottom { bottom: 8rem !important; right: -0.5rem !important; }
            .feature-text h4 { font-size: 0.95rem; }
            .feature-text p { font-size: 0.8rem; }
        }            

        /* Extra-small phones - keep absolute positioning like tablet */
        @media (max-width: 576px) {
            .hero-section {
                padding: 1rem 0.75rem;
                gap: 5rem;
            }

            .hero-content {
                width: 100%;
                max-width: 100%;
            }

            .hero-card { 
                padding: 0.25rem; 
                width: 100%;
                max-width: 100%;
            }
            
            .hero-title {
                font-size: 52px;
            }
            
            .main-card { 
                min-height: 320px; 
                padding: 1rem 1rem 2rem;
                position: relative;
                overflow: visible;
                width: 100%;
            }
            .main-card-image { 
                position: absolute;
                top: 50%;
                left: 60%;
                transform: translate(-50%, -50%);
                width: 135%;
                max-width: none;
                height: auto;
                min-height: 100%;
                object-fit: cover;
                opacity: 1;
                z-index: 1;
                filter: brightness(1) contrast(1) drop-shadow(0 8px 24px rgba(128, 128, 128, 0.25));
                pointer-events: none;
                mix-blend-mode: normal;
            }
            /* Keep cards absolutely positioned on right side */
            .feature-card { 
                position: absolute !important;
                max-width: 200px;
                padding: 0.85rem 0.9rem;
                box-shadow: 0 8px 22px rgba(0,0,0,0.12);
                z-index: 10;
            }
            .feature-card.top { top: 0.5rem !important; right: -0.5rem !important; }
            .feature-card.bottom { bottom: 7rem !important; right: -0.5rem !important; }
            .feature-text h4 { font-size: 0.95rem; }
            .feature-text p { font-size: 0.8rem; }
        }

        /* Services Section - match patient dashboard */
        .services-section {
            padding: 5rem 3rem;
            background: linear-gradient(135deg, #0a2a6b 0%, #0b3b91 100%);
            position: relative;
            color: #ffffff;
            overflow: hidden;
            margin-top: 1rem; /* clear separation from hero */
        }

        .services-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .services-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            text-align: center;
            margin-bottom: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .services-subtitle {
            text-align: center;
            color: #ffffff;
            opacity: 0.85;
            font-size: 1rem;
            margin-bottom: clamp(1.25rem, 2vw, 2.75rem);
        }

        .services-carousel-wrapper {
            position: relative;
            padding: 0;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
            overflow: visible;
            height: clamp(400px, 50vh, 600px);
            min-height: 400px;
            perspective: 1200px;
            perspective-origin: center center;
        }

        .services-carousel {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.1s linear;
        }

        .services-carousel::-webkit-scrollbar {
            display: none;
        }

        .service-card {
            position: absolute;
            background: white;
            border-radius: 20px;
            padding: clamp(1.5rem, 3vw, 2rem);
            width: clamp(200px, 25vw, 280px);
            height: clamp(200px, 25vw, 280px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1), 0 0 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            backface-visibility: hidden;
            transform-style: preserve-3d;
            opacity: 0;
            visibility: hidden;
        }
        
        .service-card.initialized {
            opacity: 1;
            visibility: visible;
        }

        .service-card:hover {
            transform: translateZ(80px) scale(1.1) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25), 0 0 40px rgba(0, 0, 0, 0.15);
            z-index: 100;
        }

        .service-icon-box {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .service-icon-box i {
            font-size: 3rem;
            color: #1e293b;
        }

        .service-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.4;
        }

        .carousel-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .carousel-nav-btn:hover {
            background: #f1f5f9;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .carousel-nav-btn.prev {
            left: 0;
        }

        .carousel-nav-btn.next {
            right: 0;
        }

        .carousel-nav-btn i {
            font-size: 1.5rem;
            color: #1e293b;
        }

        .carousel-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Service Modal */
        .service-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .service-modal.active {
            display: flex;
        }

        .service-modal-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .service-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
            transition: color 0.2s;
        }

        .service-modal-close:hover {
            color: #1e293b;
        }

        .service-modal-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .service-modal-icon i {
            font-size: 3rem;
            color: #0d9488;
        }

        .service-modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            text-align: center;
            margin-bottom: 1rem;
        }

        .service-modal-description {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .service-modal-duration {
            background: #f1f5f9;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            margin-top: 1rem;
        }

        .service-modal-duration-label {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .service-modal-duration-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d9488;
        }

        @media (max-width: 1024px) {
            .services-section {
                padding: 4rem 2rem;
            }

            .services-carousel-wrapper {
                padding: 0 50px;
            }
        }

        @media (max-width: 768px) {
            .services-carousel-wrapper {
                height: clamp(350px, 45vh, 500px);
                min-height: 350px;
            }
            
            .service-card {
                width: clamp(180px, 30vw, 240px);
                height: clamp(180px, 30vw, 240px);
                padding: clamp(1rem, 2vw, 1.5rem);
            }
        }
        
        @media (max-width: 480px) {
            .services-carousel-wrapper {
                height: clamp(300px, 40vh, 450px);
                min-height: 300px;
            }
            
            .service-card {
                width: clamp(160px, 35vw, 200px);
                height: clamp(160px, 35vw, 200px);
                padding: 1rem;
            }
        }
        
        @media (max-width: 1024px) {
            .services-section {
                padding: 4rem 2rem;
            }

            .services-carousel-wrapper {
                padding: 0 50px;
            }
        }

        @media (max-width: 768px) {
            .services-section {
                padding: 3rem 1rem;
            }

            .services-title {
                font-size: 2rem;
            }

            .services-carousel-wrapper {
                padding: 0 16px;
            }

            .carousel-nav-btn.prev { left: 8px; }
            .carousel-nav-btn.next { right: 8px; }

            .carousel-nav-btn {
                width: 40px;
                height: 40px;
            }
        }

        /* Footer Section */
        .footer {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            color: white;
            padding: 1.5rem 2rem 0.75rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .footer-about {
            max-width: 320px;
        }

        .footer-about h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-about .footer-logo {
            width: 55px;
            height: 55px;
            object-fit: contain;
            display: inline-block;
        }

        .footer-brand-text {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
            align-items: flex-start;
        }

        .footer-brand-main {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            line-height: 1.2;
            margin-bottom: 0;
        }

        .footer-brand-divider {
            width: 100%;
            margin: 0.1rem 0;
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            height: 0;
        }

        .footer-brand-sub {
            font-size: 0.8rem;
            font-weight: 600;
            color: #00EAFF;
            line-height: 1.2;
            letter-spacing: 0.02em;
            text-shadow: none !important;
            filter: none !important;
            box-shadow: none !important;
        }

        /* Dark Mode Styles for Footer Brand Sub */
        [data-theme="dark"] .footer-brand-sub {
            color: #00EAFF !important;
            text-shadow: none !important;
            filter: none !important;
            box-shadow: none !important;
        }

        .footer-about p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.3;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
        }

        .footer-social {
            display: flex;
            gap: 0.75rem;
        }

        .footer-social a {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .footer-social a:hover {
            background: white;
            color: #2196F3;
            transform: translateY(-2px);
        }

        .footer-section h4 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        /* Services bullets aligned with other lists */
        .footer-section ul.services-bullets {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .footer-section ul.services-bullets li {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 0.5rem;
            padding-left: 1.2rem;
            position: relative;
            text-indent: 0;
            font-size: 0.85rem;
        }
        .footer-section ul.services-bullets li::before {
            content: '\25CF';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: #ffffff;
            font-size: 0.75rem;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 0.75rem;
        }

        .footer-section ul li a:hover {
            color: white;
            padding-left: 0.4rem;
        }

        .footer-section ul li a i {
            font-size: 0.8rem;
        }

        .footer-contact {
            padding-right: 80px;
        }

        .footer-contact p {
            color: rgba(255, 255, 255, 1);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            line-height: 1.4;
            font-size: 0.85rem;
        }

        .footer-contact p i {
            font-size: 1rem;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
            color: #00EAFF;
            text-shadow: none !important;
            filter: none !important;
            box-shadow: none !important;
        }

        .footer-contact p span {
            flex: 1;
            white-space: nowrap;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 0.75rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        .footer-bottom p {
            margin: 0;
        }

        @media (max-width: 1024px) {
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .footer-about {
                max-width: 100%;
            }

            .footer-contact {
                padding-right: 80px;
            }
        }

        @media (max-width: 768px) {
            .footer {
                padding: 1.25rem 1.5rem 0.75rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .footer-about {
                max-width: 100%;
            }

            .footer-contact {
                padding-right: 0;
            }

            .footer-contact p span {
                white-space: normal;
            }

            .footer-brand-main {
                font-size: 1.5rem;
            }

            .footer-brand-sub {
                font-size: 0.75rem;
            }

            .footer-about .footer-logo {
                width: 50px;
                height: 50px;
            }

            /* Responsive adjustments for services bullets */
            .footer-section ul.services-bullets li {
                padding-left: 1.3rem;
                text-indent: 0;
            }
            .footer-section ul.services-bullets li::before {
                width: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            /* Responsive adjustments for services bullets on small screens */
            .footer-section ul.services-bullets li {
                padding-left: 1.2rem;
                text-indent: 0;
            }
            .footer-section ul.services-bullets li::before {
                width: 0.9rem;
            }
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
                bottom: 88px;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .scroll-to-top-btn {
                right: 16px;
                bottom: 80px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        /* Chatbot */
        .chatbot-toggle-btn {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #2196F3;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(33,150,243,0.4);
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            padding: 8px;
        }

        .chatbot-toggle-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 14px 36px rgba(33,150,243,0.45);
            background: #1976D2;
        }

        .chatbot-toggle-btn img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .chatbot-widget {
        position: fixed !important;
        right: 24px !important;
        left: auto !important;
        bottom: 92px !important;
        width: 20vw;
        min-width: 300px;
        max-width: calc(85vw - 24px);
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
        transition: opacity 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                    transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .chatbot-widget.resizing {
            transition: none !important;
        }
        
        /* Opening animation */
        .chatbot-widget.opening {
            display: flex !important;
            animation: chatbotOpen 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
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
            display: flex;
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        
        /* Resize handle styling */
        .chatbot-widget::-webkit-resizer {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-radius: 0 0 20px 0;
        }
        
        .chatbot-widget::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.3) 0%, rgba(25, 118, 210, 0.3) 100%);
            border-radius: 0 0 20px 0;
            pointer-events: none;
            z-index: 1;
        }
        
        .chatbot-widget:hover::after {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.5) 0%, rgba(25, 118, 210, 0.5) 100%);
        }

        .chatbot-widget.open { display: flex; }

        .chatbot-header {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #fff;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: move;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        
        .chatbot-header:active {
            cursor: grabbing;
        }
        
        .chatbot-header.dragging {
            cursor: grabbing !important;
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

        /* Style for bullet lists in bot messages */
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
        }

        .send-btn:hover { background: #1976D2; }

        /* Typing Indicator */
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

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.5;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .chatbot-widget {
                right: 16px !important;
                left: 16px !important;
                width: auto !important;
                max-width: calc(100vw - 32px);
                bottom: 88px !important;
                resize: both;
            }
            
            .chatbot-toggle-btn {
                right: 20px !important;
                bottom: 20px !important;
                width: 56px;
                height: 56px;
            }
            
            .chatbot-messages {
                height: 260px;
                padding: 12px;
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
                padding: 10px;
            }
            
            .chatbot-input {
                padding: 10px 12px;
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
            
            .chatbot-toggle-btn {
                right: 16px !important;
                bottom: 16px !important;
                width: 52px;
                height: 52px;
                opacity: 0.9;
            }
            
            .chatbot-toggle-btn:active,
            .chatbot-toggle-btn:focus {
                opacity: 1;
            }
            
            .chatbot-messages {
                height: 240px;
                padding: 10px;
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
                padding: 8px;
                gap: 8px;
            }
            
            .chatbot-input {
                padding: 8px 10px;
                gap: 6px;
            }
            
            .chatbot-input input[type="text"] {
                padding: 8px 12px;
                font-size: 0.85rem;
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
                height: 220px;
                padding: 8px;
            }
            
            .message {
                max-width: 90%;
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) and (orientation: landscape) {
            .chatbot-widget {
                max-height: calc(100vh - 100px);
                max-height: calc(100dvh - 100px);
            }
            
            .chatbot-messages {
                height: 200px;
                max-height: calc(100vh - 280px);
                max-height: calc(100dvh - 280px);
            }
        }

        @media (max-width: 480px) and (orientation: landscape) {
            .chatbot-widget {
                bottom: 60px !important;
                max-height: calc(100vh - 80px);
                max-height: calc(100dvh - 80px);
            }
            
            .chatbot-messages {
                height: 180px;
                max-height: calc(100vh - 260px);
                max-height: calc(100dvh - 260px);
            }
            
            .chatbot-toggle-btn {
                bottom: 8px !important;
            }
        }

        /* Fix for mobile browsers with address bar */
        @media (max-width: 768px) {
            .chatbot-widget {
                max-height: calc(100vh - 100px);
                max-height: calc(100dvh - 100px);
            }
            
            .chatbot-messages {
                max-height: calc(100vh - 300px);
                max-height: calc(100dvh - 300px);
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
                font-size: 16px !important;
            }
        }

        @media (max-width: 480px) {
            .chatbot-input input[type="text"] {
                font-size: 16px !important;
            }
        }

        /* Chatbot Tabs */
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

        /* ========================================
           SCROLL REVEAL ANIMATIONS
           ======================================== */
        /* Prevent overflow from reveal animations */
        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }
        
        /* Remove reveal animations - elements visible immediately */
        .reveal-element {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
            max-width: 100%;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo4.png') }}" alt="ToothTalk" class="logo-img">
            <div class="logo-text-wrapper">
                <span>Tooth<span style="color: #26a69a;">Talk</span></span>
                <span class="clinic-subtitle">JValera Dental Clinic</span>
            </div>
        </div>
        <div class="nav-links">
            <a href="{{ url('/') }}" class="nav-btn primary">
                <i class="bi bi-house-door-fill"></i>
                Home
            </a>
            <a href="{{ route('announcements') }}" class="nav-btn secondary">
                <i class="bi bi-megaphone-fill"></i>
                Announcements
            </a>
            <a href="{{ route('about-us') }}" class="nav-btn secondary">
                <i class="bi bi-info-circle-fill"></i>
                About Us
            </a>
        </div>
        <button id="guestMenuToggle" class="menu-toggle" aria-label="Open menu">
            <span class="burger-icon" id="guestMenuToggleIcon">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </span>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div id="guestMenuBackdrop" class="mobile-menu-backdrop" aria-hidden="true"></div>
    <div id="guestMobileMenu" class="mobile-menu-overlay" aria-hidden="true">
        <div class="mobile-menu-header">
            <div class="logo" style="gap: 0.5rem; font-size: 1.1rem;">
                <img src="{{ asset('images/logo4.png') }}" alt="ToothTalk" class="logo-img" style="width:40px;height:40px;">
                <div class="logo-text-wrapper">
                    <span>Tooth<span style="color:#26a69a;">Talk</span></span>
                    <span class="clinic-subtitle" style="font-size: 0.7rem;">JValera Dental Clinic</span>
                </div>
            </div>
            <button id="guestMenuClose" class="mobile-menu-close" aria-label="Close menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <nav class="mobile-nav">
            <a href="{{ url('/') }}"><i class="bi bi-house-door"></i> <span>Home</span></a>
            <a href="{{ route('announcements') }}"><i class="bi bi-megaphone"></i> <span>Announcements</span></a>
            <a href="{{ route('about-us') }}"><i class="bi bi-info-circle"></i> <span>About Us</span></a>
        </nav>
        <div class="mobile-account-section">
            <span class="mobile-account-label">Account</span>
            <a href="{{ route('login') }}" class="mobile-account-item">
                <div class="mobile-account-item-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <span class="mobile-account-badge">Guest</span>
            </a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section reveal-element reveal-slide-up">
        <!-- Left Content -->
        <div class="hero-content">
            <div class="badge">
                <i class="bi bi-stars"></i>
                Premium Dental Care since 2024
            </div>

            <h1 class="hero-title">
                Have confidence<br>in your <span class="highlight">SMILE</span><br><span class="no-time">in no time!</span>
            </h1>

            <p class="hero-description">
                Become our patient today! Our clinic staff will register you, and keep you updated with your dental needs.
            </p>

            {{-- Patient Portal --}}
            @auth('web')
                {{-- Patient is logged in --}}
                <a href="{{ route('patient-dashboard') }}" class="patient-login-btn">
                    <i class="bi bi-speedometer2"></i>
                    Go to Dashboard
                </a>
            @else
                {{-- Patient is not logged in --}}
                <a href="{{ route('login') }}" class="patient-login-btn">
                    <i class="bi bi-person-circle"></i>
                    Patient Login
                </a>
            @endauth

            {{-- Staff/Admin links removed for security --}}
        </div>

        <!-- Right Card -->
        <div class="hero-card">
            <div class="main-card">
                <img src="{{ asset('images/clinic.png') }}" alt="Dental Clinic" class="main-card-image">

                <div class="feature-card top">
                    <div class="feature-icon">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Pain-Free</h4>
                        <p>Advanced anesthesia</p>
                    </div>
                </div>

                <div class="feature-card bottom">
                    <div class="feature-icon">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Sterile</h4>
                        <p>Highest safety standards</p>
                    </div>
                </div>
            </div>
        </div>
</section>

    <!-- Services Section -->
    <section class="services-section reveal-element reveal-fade">
        <div class="services-container">
            <h2 class="services-title">OUR SERVICES</h2>
            <p class="services-subtitle">We offer a comprehensive range of premium dental services</p>

            <div class="services-carousel-wrapper">
                <button class="carousel-nav-btn prev" onclick="scrollServices('prev')" id="servicesPrevBtn">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="services-carousel" id="servicesCarousel">
                    @forelse($services as $service)
                    <div class="service-card" onclick="openServiceModal({{ $service->id }}, {{ json_encode($service->service_name) }}, {{ json_encode($service->description) }}, {{ $service->default_duration_minutes }}, {{ json_encode($service->icon_class ?? 'bi-gear') }})">
                        <div class="service-icon-box">
                            @php $ic = $service->icon_class; @endphp
                            @if($ic && \Illuminate\Support\Str::startsWith($ic,'uploaded:'))
                                <img src="{{ asset('storage/' . \Illuminate\Support\Str::after($ic,'uploaded:')) }}" alt="icon" style="width:80px;height:80px;object-fit:contain;" class="theme-adapt" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" onload="this.style.display='block'; this.nextElementSibling.style.display='none';">
                                <i class="bi bi-gear" style="display:none;"></i>
                            @else
                                <i class="bi {{ $ic ?? 'bi-gear' }}"></i>
                            @endif
                        </div>
                        <h3>{{ $service->service_name }}</h3>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5" style="color: white; min-width: 100%;">
                        <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.5;"></i>
                        <p class="mt-3">No services available at the moment</p>
                    </div>
                    @endforelse
                </div>

                <button class="carousel-nav-btn next" onclick="scrollServices('next')" id="servicesNextBtn">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Service Modal -->
    <div class="service-modal" id="serviceModal" onclick="closeServiceModalOnBackdrop(event)">
        <div class="service-modal-content" onclick="event.stopPropagation()">
            <button class="service-modal-close" onclick="closeServiceModal()">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="service-modal-icon">
                <img id="modalServiceImg" class="d-none" style="width:80px;height:80px;object-fit:contain;display:none;" alt="icon" onerror="this.style.display='none'; document.getElementById('modalServiceIcon').style.display='flex'; document.getElementById('modalServiceIcon').className='bi bi-gear';">
                <i class="bi bi-gear" id="modalServiceIcon"></i>
            </div>
            <h2 class="service-modal-title" id="modalServiceName"></h2>
            <p class="service-modal-description" id="modalServiceDescription"></p>
            <div class="service-modal-duration">
                <div class="service-modal-duration-label">Duration</div>
                <div class="service-modal-duration-value" id="modalServiceDuration"></div>
            </div>
        </div>
    </div>

    <script>
        // 3D Carousel with Hover Lock (No Auto-Rotation)
        let rotationAngle = 0;
        let cards = [];
        let totalCards = 0;
        let hoveredCardIndex = null;
        let updateLoopId = null;
        let scrollTargetRotationAngle = null;
        let isScrollingAnimated = false;
        
        // Touch/swipe variables
        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartY = 0;
        let touchEndY = 0;
        const minSwipeDistance = 50; // Minimum distance for a swipe
        
        function getRadius() {
            // Responsive radius based on screen size
            const width = window.innerWidth;
            if (width <= 480) return 280;
            if (width <= 768) return 320;
            if (width <= 1024) return 380;
            return 450;
        }
        
        function init3DCarousel() {
            const carousel = document.getElementById('servicesCarousel');
            if (!carousel) return;
            
            cards = Array.from(carousel.querySelectorAll('.service-card'));
            totalCards = cards.length;
            
            if (totalCards === 0) return;
            
            // Initialize rotation angle to center the first card
            const angleStep = 360 / totalCards;
            rotationAngle = 0; // Start with first card centered
            
            // Add hover event listeners to each card
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', () => lockCardToCenter(index));
                card.addEventListener('mouseleave', () => unlockCard());
            });
            
            // Add touch/swipe support for mobile
            const carouselWrapper = carousel.closest('.services-carousel-wrapper');
            if (carouselWrapper) {
                carouselWrapper.addEventListener('touchstart', handleTouchStart, { passive: true });
                carouselWrapper.addEventListener('touchend', handleTouchEnd, { passive: false });
            }
            
            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    updateCarouselPosition();
                }, 200);
            });
            
            // Wait for images to load before positioning
            const images = carousel.querySelectorAll('img');
            let imagesLoaded = 0;
            const totalImages = images.length;
            
            if (totalImages === 0) {
                // No images, position immediately
                updateCarouselPosition();
            } else {
                // Wait for all images to load
                images.forEach(img => {
                    if (img.complete) {
                        imagesLoaded++;
                    } else {
                        img.addEventListener('load', () => {
                            imagesLoaded++;
                            if (imagesLoaded === totalImages) {
                                updateCarouselPosition();
                            }
                        });
                        img.addEventListener('error', () => {
                            imagesLoaded++;
                            if (imagesLoaded === totalImages) {
                                updateCarouselPosition();
                            }
                        });
                    }
                });
                
                // Fallback: position after timeout if images don't load
                setTimeout(() => {
                    if (imagesLoaded < totalImages) {
                        updateCarouselPosition();
                    }
                }, 1000);
                
                // Position immediately if all images are already loaded
                if (imagesLoaded === totalImages) {
                    updateCarouselPosition();
                }
            }
        }
        
        function updateCarouselPosition() {
            const carousel = document.getElementById('servicesCarousel');
            if (!carousel) return;
            
            // Get carousel dimensions for proper centering
            // Cards are positioned relative to the carousel, so use carousel's dimensions
            // Force a reflow to ensure dimensions are calculated correctly (especially on mobile)
            carousel.offsetHeight; // Force reflow
            let carouselWidth = carousel.offsetWidth;
            let carouselHeight = carousel.offsetHeight;
            
            // If carousel dimensions are not available, try wrapper or window
            if (!carouselWidth || carouselWidth <= 0) {
                const wrapper = carousel.closest('.services-carousel-wrapper');
                if (wrapper) {
                    wrapper.offsetHeight; // Force reflow for wrapper
                    carouselWidth = wrapper.clientWidth || wrapper.offsetWidth;
                } else {
                    carouselWidth = window.innerWidth;
                }
            }
            if (!carouselHeight || carouselHeight <= 0) {
                const wrapper = carousel.closest('.services-carousel-wrapper');
                if (wrapper) {
                    wrapper.offsetHeight; // Force reflow for wrapper
                    carouselHeight = wrapper.clientHeight || wrapper.offsetHeight;
                } else {
                    carouselHeight = 500;
                }
            }
            
            // Final fallback
            if (!carouselWidth || carouselWidth <= 0) {
                carouselWidth = window.innerWidth;
            }
            if (!carouselHeight || carouselHeight <= 0) {
                carouselHeight = 500;
            }
            
            const radius = getRadius();
            const angleStep = 360 / totalCards;
            
            let targetAngleForInterpolation = null;
            
            // Smoothly interpolate to target rotation if hovering
            if (hoveredCardIndex !== null) {
                // Calculate target angle to center the hovered card
                // The card at index should be at angle 0 (front center)
                let hoverTargetAngle = -(angleStep * hoveredCardIndex);
                
                // Snap to nearest card position for perfect centering
                const nearestCardIndex = Math.round(hoverTargetAngle / angleStep);
                hoverTargetAngle = nearestCardIndex * angleStep;
                
                // Normalize target angle to keep it within reasonable bounds
                while (hoverTargetAngle >= 360) hoverTargetAngle -= 360;
                while (hoverTargetAngle < 0) hoverTargetAngle += 360;
                
                targetAngleForInterpolation = hoverTargetAngle;
                isScrollingAnimated = false; // Hover takes precedence, stop scroll animation
                scrollTargetRotationAngle = null;
            } else if (isScrollingAnimated && scrollTargetRotationAngle !== null) {
                // Smooth animation for button clicks
                targetAngleForInterpolation = scrollTargetRotationAngle;
            }
            
            if (targetAngleForInterpolation !== null) {
                let diff = targetAngleForInterpolation - rotationAngle;
                
                // Normalize angle difference to shortest path
                while (diff > 180) diff -= 360;
                while (diff < -180) diff += 360;
                
                // Smooth interpolation with adaptive speed
                // Faster on mobile for swipes, slower for hover
                const isMobile = window.innerWidth <= 768;
                const animationSpeed = hoveredCardIndex !== null 
                    ? 0.2  // Hover: keep smooth
                    : (isMobile ? 0.55 : 0.4);  // Mobile swipes: faster, desktop: medium
                rotationAngle += diff * animationSpeed;
                
                // Check if we're close enough to target
                if (Math.abs(diff) < 0.05) {
                    rotationAngle = targetAngleForInterpolation; // Lock to exact position
                    // Normalize rotation angle
                    while (rotationAngle >= 360) rotationAngle -= 360;
                    while (rotationAngle < 0) rotationAngle += 360;
                    if (hoveredCardIndex === null && isScrollingAnimated) {
                        // Scroll animation finished
                        isScrollingAnimated = false;
                        scrollTargetRotationAngle = null;
                    }
                }
            }
            
            cards.forEach((card, index) => {
                // Ensure card has dimensions before positioning
                if (!card.offsetWidth || !card.offsetHeight) {
                    // Force reflow to get dimensions
                    card.style.display = 'none';
                    card.offsetHeight; // Trigger reflow
                    card.style.display = '';
                }
                
                const angle = (angleStep * index + rotationAngle) * (Math.PI / 180);
                const x = Math.sin(angle) * radius;
                const z = Math.cos(angle) * radius;
                
                // Calculate opacity and scale based on z position (depth)
                // z ranges from -radius (back) to +radius (front)
                const normalizedZ = (z + radius) / (radius * 2); // 0 to 1
                const opacity = 0.5 + (normalizedZ * 0.5); // 0.5 to 1
                const scale = 0.75 + (normalizedZ * 0.25); // 0.75 to 1
                
                // Position card in center of carousel
                // Use responsive card dimensions for mobile
                const isMobile = window.innerWidth <= 768;
                const defaultCardWidth = isMobile ? 180 : 200;
                const defaultCardHeight = isMobile ? 180 : 200;
                const cardWidth = card.offsetWidth || defaultCardWidth;
                const cardHeight = card.offsetHeight || defaultCardHeight;
                const left = (carouselWidth / 2) + x - (cardWidth / 2);
                const top = (carouselHeight / 2) - (cardHeight / 2);
                
                card.style.left = `${left}px`;
                card.style.top = `${top}px`;
                card.style.transform = `
                    translateZ(${z}px) 
                    scale(${scale})
                `;
                card.style.opacity = opacity;
                card.style.visibility = 'visible';
                card.classList.add('initialized');
                card.style.zIndex = Math.round(normalizedZ * 100);
            });
        }
        
        // Continuous update loop for hover locking and scroll animation
        function startUpdateLoop() {
            function update() {
                updateCarouselPosition();
                // Continue loop if either hover or scroll animation is active
                if (hoveredCardIndex !== null || isScrollingAnimated) {
                    updateLoopId = requestAnimationFrame(update);
                } else {
                    // If neither is active, stop the loop
                    stopUpdateLoop();
                }
            }
            if (!updateLoopId) { // Only start if not already running
                updateLoopId = requestAnimationFrame(update);
            }
        }
        
        function stopUpdateLoop() {
            if (updateLoopId) {
                cancelAnimationFrame(updateLoopId);
                updateLoopId = null;
            }
        }
        
        function lockCardToCenter(index) {
            isScrollingAnimated = false; // Stop any ongoing scroll animation
            scrollTargetRotationAngle = null; // Clear scroll target
            hoveredCardIndex = index;
            startUpdateLoop(); // Ensure the main update loop is running for hover animation
        }
        
        function unlockCard() {
            hoveredCardIndex = null;
            // The startUpdateLoop's update function will now check if both hoveredCardIndex and isScrollingAnimated are null and stop itself.
        }
        
        function scrollServices(direction) {
            hoveredCardIndex = null; // Clear any hover lock
            // Don't stop the update loop - let it continue for smooth rapid clicks
            
            const angleStep = 360 / totalCards;
            
            // Determine the base angle for calculation:
            // If a scroll animation is already active, build upon its target.
            // Otherwise, use the current rotation angle.
            const baseAngle = isScrollingAnimated && scrollTargetRotationAngle !== null 
                ? scrollTargetRotationAngle 
                : rotationAngle;
            
            // Calculate the new target angle by moving exactly one card position
            let newTargetAngle;
            if (direction === 'next') {
                newTargetAngle = baseAngle + angleStep;
            } else {
                newTargetAngle = baseAngle - angleStep;
            }
            
            // Snap the new target to the nearest card's center position
            // This ensures it always aligns perfectly with a card, even with rapid clicks
            const nearestCardIndex = Math.round(newTargetAngle / angleStep);
            scrollTargetRotationAngle = nearestCardIndex * angleStep;
            
            // Normalize target angle to keep it within reasonable bounds
            while (scrollTargetRotationAngle >= 360) scrollTargetRotationAngle -= 360;
            while (scrollTargetRotationAngle < 0) scrollTargetRotationAngle += 360;
            
            isScrollingAnimated = true;
            startUpdateLoop(); // Ensure the main update loop is running to animate the scroll
        }
        
        // Touch/swipe handlers for mobile
        function handleTouchStart(e) {
            const touch = e.touches[0];
            touchStartX = touch.clientX;
            touchStartY = touch.clientY;
        }
        
        function handleTouchEnd(e) {
            if (!touchStartX || !touchStartY) return;
            
            const touch = e.changedTouches[0];
            touchEndX = touch.clientX;
            touchEndY = touch.clientY;
            
            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;
            const absDeltaX = Math.abs(deltaX);
            const absDeltaY = Math.abs(deltaY);
            
            // Check if it's a horizontal swipe (more horizontal than vertical)
            if (absDeltaX > absDeltaY && absDeltaX > minSwipeDistance) {
                // Prevent default to avoid scrolling the page
                e.preventDefault();
                
                if (deltaX > 0) {
                    // Swipe right - go to next
                    scrollServices('next');
                } else {
                    // Swipe left - go to previous
                    scrollServices('prev');
                }
            }
            
            // Reset touch coordinates
            touchStartX = 0;
            touchStartY = 0;
            touchEndX = 0;
            touchEndY = 0;
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Longer delay for mobile to ensure layout is ready
            const isMobile = window.innerWidth <= 768;
            const delay = isMobile ? 300 : 100;
            setTimeout(() => {
                init3DCarousel();
                // Force an additional update after a short delay for mobile
                if (isMobile) {
                    setTimeout(() => {
                        updateCarouselPosition();
                    }, 200);
                }
            }, delay);
        });
        
        // Also initialize when window loads (for images)
        window.addEventListener('load', function() {
            if (totalCards === 0) {
                init3DCarousel();
            } else {
                // Recalculate positions after all assets load
                updateCarouselPosition();
                // Additional update for mobile after layout settles
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        updateCarouselPosition();
                    }, 200);
                }
            }
        });

        // Service Modal
        function openServiceModal(id, name, description, duration, iconClass) {
            document.getElementById('modalServiceName').textContent = name;
            document.getElementById('modalServiceDescription').textContent = description;
            document.getElementById('modalServiceDuration').textContent = duration + ' minutes';
            
            const iconEl = document.getElementById('modalServiceIcon');
            const imgEl = document.getElementById('modalServiceImg');
            
            // Completely reset both elements first - remove all event handlers
            imgEl.onerror = null;
            imgEl.onload = null;
            imgEl.src = ''; // Clear previous image source
            
            // Reset visibility - hide image by default, show icon
            imgEl.classList.add('d-none');
            imgEl.style.display = 'none';
            iconEl.classList.remove('d-none');
            iconEl.style.display = 'flex';
            
            // Validate and clean iconClass
            if (!iconClass || typeof iconClass !== 'string') {
                iconClass = 'bi-gear';
            }
            iconClass = iconClass.trim();
            
            // Check if it's an uploaded image
            if (iconClass.startsWith('uploaded:')) {
                const imagePath = '/storage/' + iconClass.replace('uploaded:', '');
                
                // Set up fresh error handler for failed image loads
                imgEl.onerror = function() {
                    // Image failed to load - hide image and show default icon
                    this.onerror = null; // Remove handler to prevent loops
                    this.onload = null;
                    imgEl.classList.add('d-none');
                    imgEl.style.display = 'none';
                    imgEl.src = '';
                    iconEl.classList.remove('d-none');
                    iconEl.style.display = 'flex';
                    iconEl.className = 'bi bi-gear';
                };
                
                // Set up fresh success handler
                imgEl.onload = function() {
                    // Image loaded successfully - show image and hide icon
                    this.onload = null; // Remove handler
                    imgEl.classList.remove('d-none');
                    imgEl.style.display = 'block';
                    iconEl.classList.add('d-none');
                    iconEl.style.display = 'none';
                };
                
                // Set the image source with cache busting to ensure fresh load
                imgEl.src = imagePath + '?v=' + Date.now();
            } else {
                // It's a Bootstrap icon - set icon class and ensure it's visible
                const iconClassClean = iconClass || 'bi-gear';
                // Ensure it starts with 'bi-'
                const finalIconClass = iconClassClean.startsWith('bi-') ? iconClassClean : ('bi-' + iconClassClean);
                iconEl.className = 'bi ' + finalIconClass;
                iconEl.classList.remove('d-none');
                iconEl.style.display = 'flex';
                imgEl.classList.add('d-none');
                imgEl.style.display = 'none';
                imgEl.src = ''; // Clear any previous image
            }
            
            document.getElementById('serviceModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeServiceModal() {
            document.getElementById('serviceModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        function closeServiceModalOnBackdrop(event) {
            if (event.target.id === 'serviceModal') {
                closeServiceModal();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeServiceModal();
            }
        });
    </script>

    <!-- Footer Section -->
    <footer class="footer reveal-element reveal-slide-up">
        <div class="footer-container">
            <div class="footer-content">
                <!-- About Section -->
                <div class="footer-about reveal-element reveal-fade">
                    <h3>
                        <img src="{{ asset('images/logo7.png') }}" alt="ToothTalk" class="footer-logo">
                        <div class="footer-brand-text">
                            <span class="footer-brand-main">ToothTalk</span>
                            <hr class="footer-brand-divider">
                            <span class="footer-brand-sub">JValera Dental Clinic</span>
                        </div>
                    </h3>
                    <p>
                        We offer premium dental care services since 2024.<br>
                        We're committed to providing utmost care and<br>
                        attention to your dental needs.
                    </p>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/profile.php?id=61555389276989" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/JValeradentalclinic" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section reveal-element reveal-fade reveal-delay-1">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ url('/') }}"><i class="bi bi-chevron-right"></i> Home</a></li>
                        <li><a href="{{ url('about-us') }}"><i class="bi bi-chevron-right"></i> About Us</a></li>
                        <li><a href="{{ route('announcements') }}"><i class="bi bi-chevron-right"></i> Announcements</a></li>
                        <li><a href="{{ route('login') }}"><i class="bi bi-chevron-right"></i> Patient Portal</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="footer-section reveal-element reveal-fade reveal-delay-2">
                    <h4>Our Services</h4>
                    <ul class="services-bullets">
                        <li>General Dentistry</li>
                        <li>Cosmetic Dentistry</li>
                        <li>Orthodontics</li>
                        <li>Teeth Whitening</li>
                        <li>Emergency Care</li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-section footer-contact reveal-element reveal-fade reveal-delay-3">
                    <h4>Contact Info</h4>
                    <p>
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Policarpio St. Gen. T. de Leon Valenzuela City</span>
                    </p>
                    <p>
                        <i class="bi bi-telephone-fill"></i>
                        <span>(+63)915 622 9695</span>
                    </p>
                    <p>
                        <i class="bi bi-envelope-fill"></i>
                        <span>jvaleradentalclinic@gmail.com</span>
                    </p>
                    <p>
                        <i class="bi bi-clock-fill"></i>
                        <span>Tuesday to Saturday: 11:00 AM to 6:00 PM.</span>
                    </p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom reveal-element reveal-fade">
                <p>&copy; {{ date('Y') }} ToothTalk Appointment Scheduler for JValera Dental Clinic. All rights reserved. | Designed with <i class="bi bi-heart-fill" style="color: #00EAFF; text-shadow: none !important; filter: none !important; box-shadow: none !important;"></i> for healthy smiles</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top" title="Scroll to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    @if(!empty($chatbotSetting) && $chatbotSetting->enabled)
    <!-- Chatbot Toggle Button -->
    <div id="chatbot-toggle" class="chatbot-toggle-btn" aria-label="Open chat" title="Chat with us">
        <img src="{{ asset('images/chatbot-logo_3.png') }}" alt="ToothTalk Assistant">
    </div>

    <!-- Chatbot Widget -->
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
            let headerEl = null;

            let currentMode = 'faqs'; // Start with FAQs for guests
            let conversationId = null;
            let pollingInterval = null;
            let lastMessageId = null;
            let faqInitialized = false;
            
            // Drag functionality
            let isDragging = false;
            let isResizing = false;
            let dragStartX = 0;
            let dragStartY = 0;
            let initialLeft = 0;
            let initialTop = 0;
            let initialRight = 0;
            let initialBottom = 0;

            // FAQ data
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
                    // Process text line by line
                    let lines = text.split('\n');
                    let formattedHTML = '';

                    for (let i = 0; i < lines.length; i++) {
                        let line = lines[i].trim();
                        if (!line) continue;

                        // Check if line ends with colon (section header)
                        if (line.endsWith(':')) {
                            formattedHTML += `<span class="section-header">${line}</span>`;
                        }
                        // Check if line starts with bullet
                        else if (line.startsWith('•')) {
                            formattedHTML += `<span class="bullet-item">${line}</span>`;
                        }
                        // Regular text
                        else {
                            formattedHTML += line;
                            // Add spacing after sentences if next line exists
                            if (i < lines.length - 1) {
                                formattedHTML += '<br>';
                            }
                        }
                    }

                    div.innerHTML = formattedHTML;
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
                if (indicator) {
                    indicator.remove();
                }
            }

            function normalize(s) {
                return String(s)
                    .toLowerCase()
                    .replace(/&nbsp;/g, ' ')
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, ' ')
                    .trim();
            }

            // Use only database FAQs - no hardcoded answers

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

            // Precompute FAQ tokens
            const faqIndexed = (faqPairs || []).map(p => ({ q: p.q, a: p.a, tokens: tokenize(p.q || '') }));

            function getBotReply(query) {
                const q = normalize(query);
                const qLower = q.toLowerCase();
                const qTokens = tokenize(q);

                // Check for help requests and general queries first
                const helpPatterns = ['help', 'assist', 'support', 'can you', 'could you', 'need help', 'i need', 'i want', 'how can', 'what can'];
                const greetingPatterns = ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'greetings'];
                const servicePatterns = ['service', 'treatment', 'procedure', 'what do you', 'what services', 'offer', 'available'];
                const hoursPatterns = ['hours', 'open', 'close', 'time', 'when', 'what time', 'schedule', 'availability'];
                const pricePatterns = ['price', 'cost', 'fee', 'payment', 'how much', 'expensive', 'charge'];
                const appointmentPatterns = ['appointment', 'book', 'schedule', 'reserve', 'visit', 'see dentist'];

                // Check for help requests first
                if (helpPatterns.some(pattern => qLower.includes(pattern))) {
                    return 'Of course! I\'m here to help you. You can ask me about:\n\n• Our clinic hours and availability\n• Services and treatments we offer\n• Appointment scheduling\n• Pricing information\n• General questions about dental care\n\nWhat would you like to know more about?';
                }

                // Check for greetings
                if (greetingPatterns.some(pattern => qLower.includes(pattern))) {
                    return 'Hello! Welcome to our dental clinic. How can I assist you today? You can ask about our services, hours, pricing, or schedule an appointment.';
                }

                // Check for service-related queries
                if (servicePatterns.some(pattern => qLower.includes(pattern))) {
                    return 'We offer a comprehensive range of dental services including:\n\n• General dentistry (cleanings, check-ups)\n• Cosmetic dentistry (whitening, veneers)\n• Orthodontics (braces, aligners)\n• Root canals and fillings\n• Crowns and bridges\n• Implants\n• Emergency dental care\n\nWould you like to know more about a specific service?';
                }

                // Check for hours-related queries
                if (hoursPatterns.some(pattern => qLower.includes(pattern))) {
                    return 'Our clinic hours are:\n\n• Tuesday to Saturday: 11:00 AM to 6:00 PM\n• Sunday and Monday: Closed\n\nWe recommend scheduling an appointment in advance. Would you like to book one?';
                }

                // Check for pricing queries
                if (pricePatterns.some(pattern => qLower.includes(pattern))) {
                    return 'Pricing varies depending on the service and treatment needed. For specific pricing information, please contact our office or schedule a consultation. We\'d be happy to provide a detailed quote based on your needs.';
                }

                // Check for appointment queries
                if (appointmentPatterns.some(pattern => qLower.includes(pattern))) {
                    return 'You can schedule an appointment by:\n\n• Logging into your patient portal and using the calendar\n• Contacting us directly at (63)915 622 9695\n• Visiting our clinic at Policarpio St. Gen. T. de Leon Valenzuela City\n\nWould you like help with anything else?';
                }

                // 1) Fuzzy match FAQs by token overlap
                let best = { score: 0, inter: 0, a: null };
                for (const item of faqIndexed) {
                    if (!item.tokens.length) continue;
                    const { inter, jaccard } = overlapScore(qTokens, item.tokens);
                    // Lower threshold for matching - be more lenient
                    const score = inter >= 1 ? jaccard + 0.15 : jaccard;
                    if (score > best.score) best = { score, inter, a: item.a };
                }
                // Lower the threshold for FAQ matching
                if (best.a && (best.score >= 0.15 || best.inter >= 1)) return best.a;

                // 2) More helpful fallback message
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
                
                // If live chat is active and authenticated, use it
                if (currentMode === 'live-chat' && conversationId) {
                    await sendLiveMessage(text.trim());
                    return;
                }
                
                // Otherwise use FAQ chatbot
                if (currentMode === 'faqs') {
                    addMessage(text.trim(), 'user');
                    showTypingIndicator();
                    const typingDelay = 1000 + Math.random() * 1000;
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(getBotReply(text), 'bot');
                    }, typingDelay);
                } else {
                    // Live chat mode but not authenticated or not initialized
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
                // Remove closing class if present
                widget.classList.remove('closing');
                widget.classList.remove('open');
                
                // Show widget and start opening animation
                widget.style.display = 'flex';
                widget.setAttribute('aria-hidden', 'false');
                widget.classList.add('opening');
                
                // After animation completes, switch to open state
                setTimeout(() => {
                    widget.classList.remove('opening');
                    widget.classList.add('open');
                }, 400); // Match animation duration
                
                // Ensure drag is initialized when widget opens
                if (!headerEl && widget) {
                    headerEl = widget.querySelector('.chatbot-header');
                    if (headerEl) {
                        initDrag();
                    }
                }
                
                if (!messagesEl.dataset.checked) {
                    if (currentMode === 'faqs') {
                        // FAQs mode - show chips for guests
                        chipsEl.style.display = 'flex'; // Show chips in FAQs
                        chipsEl.innerHTML = ''; // Clear any previous buttons or login buttons
                        messagesEl.innerHTML = ''; // Clear messages
                        showTypingIndicator();
                        setTimeout(() => {
                            hideTypingIndicator();
                            addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                            renderChips(); // Render FAQ chips
                            faqInitialized = true;
                        }, 800);
                    } else if (currentMode === 'live-chat') {
                        chipsEl.style.display = 'none'; // Hide FAQ chips in live chat
                        chipsEl.innerHTML = ''; // Clear any FAQ chips
                        const isAuth = await checkAuth();
                        if (isAuth) {
                            inputEl.placeholder = 'Type your message to staff...';
                            await initializeLiveChat();
                        } else {
                            messagesEl.innerHTML = '';
                            addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
                            // Add login button to messages area, not chips
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
                // Remove open and opening classes, add closing class
                widget.classList.remove('open');
                widget.classList.remove('opening');
                widget.classList.add('closing');
                widget.setAttribute('aria-hidden', 'true');
                
                // Wait for closing animation to complete before hiding
                setTimeout(() => {
                    widget.classList.remove('closing');
                    widget.style.display = 'none';
                }, 300); // Match animation duration
                
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            function switchTab(mode) {
                currentMode = mode;
                tabLiveChat.classList.toggle('active', mode === 'live-chat');
                tabFaqs.classList.toggle('active', mode === 'faqs');
                
                const inputContainer = document.querySelector('.chatbot-input');
                
                if (mode === 'live-chat') {
                    titleEl.textContent = 'Live Chat - Staff';
                    inputEl.placeholder = 'Type your message to staff...';
                    inputContainer.style.display = 'flex'; // Show input
                    chipsEl.style.display = 'none'; // Hide FAQ chips
                    chipsEl.innerHTML = ''; // Clear any FAQ chips
                    stopPolling();
                    // Check auth and initialize live chat
                    checkAuth().then(isAuth => {
                        if (isAuth) {
                            messagesEl.innerHTML = ''; // Clear messages
                            if (!conversationId) {
                                initializeLiveChat();
                            } else {
                                loadMessages();
                                startPolling();
                            }
                        } else {
                            messagesEl.innerHTML = '';
                            addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
                            // Show login button in messages area, not chips
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
                            // Add login button to messages area, not chips
                            const loginContainer = document.createElement('div');
                            loginContainer.style.marginTop = '10px';
                            loginContainer.appendChild(loginBtn);
                            messagesEl.appendChild(loginContainer);
                            inputEl.disabled = true;
                            sendBtn.disabled = true;
                        }
                    });
                } else {
                    // FAQs mode
                    titleEl.textContent = 'ToothTalk Assistant';
                    inputEl.placeholder = 'Ask about services, hours, pricing...';
                    inputContainer.style.display = 'none'; // Hide input
                    chipsEl.style.display = 'flex'; // Show FAQ chips
                    chipsEl.innerHTML = ''; // Clear any login buttons or previous chips
                    stopPolling();
                    inputEl.disabled = false;
                    sendBtn.disabled = false;
                    // Always show FAQ chips for guests - reset and show welcome
                    messagesEl.innerHTML = '';
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                        renderChips(); // Render FAQ chips
                        faqInitialized = true;
                    }, 800);
                }
            }

            // Drag functionality
            function initDrag() {
                if (!widget || !headerEl) return;
                
                // Prevent dragging on buttons and interactive elements
                function shouldAllowDrag(target) {
                    const interactiveElements = ['button', 'input', 'textarea', 'a', 'select'];
                    let element = target;
                    while (element && element !== headerEl) {
                        if (interactiveElements.includes(element.tagName.toLowerCase()) || 
                            element.classList.contains('chatbot-tab') ||
                            element.closest('button')) {
                            return false;
                        }
                        element = element.parentElement;
                    }
                    return true;
                }
                
                function startDrag(clientX, clientY) {
                    isDragging = true;
                    widget.classList.add('resizing');
                    headerEl.classList.add('dragging');
                    
                    const rect = widget.getBoundingClientRect();
                    dragStartX = clientX;
                    dragStartY = clientY;
                    
                    // Get current position
                    const computedStyle = window.getComputedStyle(widget);
                    initialRight = parseFloat(computedStyle.right) || 0;
                    initialBottom = parseFloat(computedStyle.bottom) || 0;
                    initialLeft = parseFloat(computedStyle.left) || 0;
                    initialTop = parseFloat(computedStyle.top) || 0;
                    
                    // Switch to left/top positioning for dragging
                    if (computedStyle.right !== 'auto') {
                        widget.style.right = 'auto';
                        widget.style.left = rect.left + 'px';
                    }
                    if (computedStyle.bottom !== 'auto') {
                        widget.style.bottom = 'auto';
                        widget.style.top = rect.top + 'px';
                    }
                }
                
                function handleDrag(clientX, clientY) {
                    if (!isDragging) return;
                    
                    const deltaX = clientX - dragStartX;
                    const deltaY = clientY - dragStartY;
                    
                    const newLeft = initialLeft + deltaX;
                    const newTop = initialTop + deltaY;
                    
                    // Constrain to viewport
                    const maxLeft = window.innerWidth - widget.offsetWidth;
                    const maxTop = window.innerHeight - widget.offsetHeight;
                    
                    widget.style.left = Math.max(0, Math.min(newLeft, maxLeft)) + 'px';
                    widget.style.top = Math.max(0, Math.min(newTop, maxTop)) + 'px';
                    widget.style.right = 'auto';
                    widget.style.bottom = 'auto';
                }
                
                function stopDrag() {
                    if (isDragging) {
                        isDragging = false;
                        widget.classList.remove('resizing');
                        headerEl.classList.remove('dragging');
                    }
                }
                
                // Mouse events
                headerEl.addEventListener('mousedown', (e) => {
                    if (!shouldAllowDrag(e.target)) return;
                    startDrag(e.clientX, e.clientY);
                    e.preventDefault();
                });
                
                document.addEventListener('mousemove', (e) => {
                    handleDrag(e.clientX, e.clientY);
                });
                
                document.addEventListener('mouseup', stopDrag);
                
                // Touch events for mobile
                headerEl.addEventListener('touchstart', (e) => {
                    if (!shouldAllowDrag(e.target)) return;
                    const touch = e.touches[0];
                    startDrag(touch.clientX, touch.clientY);
                    e.preventDefault();
                }, { passive: false });
                
                document.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    const touch = e.touches[0];
                    handleDrag(touch.clientX, touch.clientY);
                    e.preventDefault();
                }, { passive: false });
                
                document.addEventListener('touchend', stopDrag);
                
                // Handle resize detection
                let resizeObserver = null;
                if (window.ResizeObserver) {
                    resizeObserver = new ResizeObserver(() => {
                        if (!isDragging) {
                            widget.classList.add('resizing');
                            setTimeout(() => {
                                widget.classList.remove('resizing');
                            }, 300);
                        }
                    });
                    resizeObserver.observe(widget);
                }
            }
            
            // Initialize drag when widget is available
            function setupDrag() {
                if (widget) {
                    headerEl = widget.querySelector('.chatbot-header');
                    if (headerEl) {
                        initDrag();
                    }
                }
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupDrag);
            } else {
                setupDrag();
            }
            

            toggleBtn.addEventListener('click', () => {
                if (widget.classList.contains('open')) closeChat(); else openChat();
            });
            closeBtn.addEventListener('click', closeChat);
            tabLiveChat.addEventListener('click', () => switchTab('live-chat'));
            tabFaqs.addEventListener('click', () => switchTab('faqs'));
            sendBtn.addEventListener('click', () => {
                const v = inputEl.value; 
                inputEl.value = ''; 
                sendUserMessage(v);
            });
            inputEl.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') { 
                    const v = inputEl.value; 
                    inputEl.value = ''; 
                    sendUserMessage(v); 
                }
            });
        })();
    </script>
    @endif

    <script>
        // Mobile menu toggle for guest pages
        (function(){
            const toggle = document.getElementById('guestMenuToggle');
            const closeBtn = document.getElementById('guestMenuClose');
            const menu = document.getElementById('guestMobileMenu');
            const backdrop = document.getElementById('guestMenuBackdrop');
            const toggleIcon = document.getElementById('guestMenuToggleIcon');
            
            function openMenu(){
                menu.classList.add('active');
                menu.setAttribute('aria-hidden','false');
                backdrop.classList.add('active');
                backdrop.setAttribute('aria-hidden','false');
                toggleIcon?.classList.add('open');
                // lock page scroll under menu
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
                // pin toggle on top
                toggle?.classList.add('fixed-open');
                toggle?.setAttribute('aria-label','Close menu');
            }
            
            function closeMenu(){
                menu.classList.remove('active');
                menu.setAttribute('aria-hidden','true');
                backdrop.classList.remove('active');
                backdrop.setAttribute('aria-hidden','true');
                toggleIcon?.classList.remove('open');
                // restore scroll
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
                toggle?.classList.remove('fixed-open');
                toggle?.setAttribute('aria-label','Open menu');
            }
            
            toggle?.addEventListener('click', function(e){
                e.stopPropagation();
                if (menu.classList.contains('active')) closeMenu(); else openMenu();
            });
            
            closeBtn?.addEventListener('click', function(){ closeMenu(); });
            
            document.addEventListener('click', function(e){
                if (menu.classList.contains('active') && !menu.contains(e.target) && e.target !== toggle) { 
                    closeMenu(); 
                }
            });
            
            backdrop?.addEventListener('click', closeMenu);
        })();

        // ========================================
        // SCROLL TO TOP BUTTON
        // ========================================
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

        // ========================================
        // SCROLL REVEAL FUNCTIONALITY - DISABLED
        // ========================================
        // Reveal animations removed - all elements visible immediately
        (function() {
            // Make all reveal elements visible immediately
            document.querySelectorAll('.reveal-element').forEach(el => {
                el.classList.add('revealed');
                el.style.opacity = '1';
                el.style.transform = 'none';
            });
        })();

        // ========================================
        // CROSS-TAB SYNCHRONIZATION
        // Notify other tabs when auth changes
        // ========================================

        // Store current auth state as a timestamp in localStorage
        function updateAuthState() {
            const authState = {
                timestamp: Date.now(),
                hasPatientLogin: !!document.querySelector('.patient-login-btn'),
                hasStaffLogin: !!document.querySelector('.login-btn.staff'),
                hasAdminLogin: !!document.querySelector('.login-btn.admin'),
                hasGoToDashboard: document.body.innerText.includes('Go to Dashboard')
            };
            localStorage.setItem('homepage_auth_state', JSON.stringify(authState));
        }

        // Listen for storage changes from other tabs
        window.addEventListener('storage', function(e) {
            if (e.key === 'auth_logout_event' || e.key === 'homepage_auth_state') {
                console.log('Auth state changed in another tab (key: ' + e.key + '), reloading in 2 seconds...');
                setTimeout(function() {
                    window.location.reload();
                }, 2000); // 2 second delay
            }
        });

        // Store initial auth state for comparison
        let myAuthState = {
            hasPatientLogin: !!document.querySelector('.patient-login-btn'),
            hasStaffLogin: !!document.querySelector('.login-btn.staff'),
            hasAdminLogin: !!document.querySelector('.login-btn.admin'),
            hasGoToDashboard: document.body.innerText.includes('Go to Dashboard')
        };

        // Update auth state when page loads (for other tabs to detect)
        updateAuthState();

        // Polling mechanism: Check if OTHER tabs changed auth state
        setInterval(function() {
            const storedState = localStorage.getItem('homepage_auth_state');
            if (storedState) {
                try {
                    const parsedState = JSON.parse(storedState);

                    // Compare stored state with our current page state
                    if (parsedState.hasPatientLogin !== myAuthState.hasPatientLogin ||
                        parsedState.hasStaffLogin !== myAuthState.hasStaffLogin ||
                        parsedState.hasAdminLogin !== myAuthState.hasAdminLogin ||
                        parsedState.hasGoToDashboard !== myAuthState.hasGoToDashboard) {

                        console.log('Auth state mismatch detected!');
                        console.log('My state:', myAuthState);
                        console.log('Stored state:', parsedState);
                        console.log('Reloading to sync in 2 seconds...');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000); // 2 second delay
                    }
                } catch (e) {
                    console.error('Error parsing auth state:', e);
                }
            }
        }, 500); // Check every 500ms for faster detection

        // ========================================
        // CACHE PREVENTION
        // ========================================

        // Force page reload when navigating back from cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });

        // Check if we're returning from a dashboard/login/logout
        if (document.referrer && (
            document.referrer.includes('/dashboard') ||
            document.referrer.includes('/login') ||
            document.referrer.includes('/logout')
        )) {
            if (!sessionStorage.getItem('homePageReloaded')) {
                sessionStorage.setItem('homePageReloaded', 'true');
                window.location.reload();
            }
        }

        // Clear the reload flag when leaving the page
        window.addEventListener('beforeunload', function() {
            sessionStorage.removeItem('homePageReloaded');
        });
    </script>
</body>
</html>
