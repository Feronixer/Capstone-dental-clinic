<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToothTalk - @yield('title', 'Premium Dental Care')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo4.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo4.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo4.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 1000;
            color: #2196F3;
            text-decoration: none;
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

        /* Hamburger Menu Styles */
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
            background: #ffffff;
            box-shadow: -4px 0 30px rgba(0,0,0,0.2);
            z-index: 10001;
            transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            padding-bottom: env(safe-area-inset-bottom, 0);
            padding-top: env(safe-area-inset-top, 0);
        }

        .mobile-menu-overlay.active { right: 0; }

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
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .mobile-menu-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(1px);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            z-index: 10000;
        }
        .mobile-menu-backdrop.active { opacity: 1; visibility: visible; }

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

        .footer-section ul li a:hover {
            color: white;
            padding-left: 0.4rem;
        }

        .footer-section ul li a i {
            font-size: 0.8rem;
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
            .navbar {
                padding: 1rem;
                flex-direction: row;
                align-items: center;
                gap: 0.75rem;
                width: 100%;
            }

            .logo {
                font-size: 1.3rem;
            }

            .clinic-subtitle {
                font-size: 0.7rem !important;
            }

            .nav-links {
                display: none;
            }

            .menu-toggle {
                display: inline-flex !important;
                margin-left: auto;
                position: relative;
                pointer-events: auto;
                -webkit-tap-highlight-color: transparent;
                user-select: none;
            }

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

            .footer-about .footer-logo {
                width: 50px;
                height: 50px;
            }

            .footer-brand-sub {
                font-size: 0.75rem;
            }

            /* Responsive adjustments for services bullets */
            .footer-section ul.services-bullets li {
                padding-left: 1.3rem;
            }
            .footer-section ul.services-bullets li::before {
                width: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            /* Responsive adjustments for services bullets on small screens */
            .footer-section ul.services-bullets li {
                padding-left: 1.2rem;
            }
            .footer-section ul.services-bullets li::before {
                width: 0.9rem;
            }
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

        * {
            scrollbar-width: thin;
            scrollbar-color: #2196F3 #ffffff;
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
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('images/logo4.png') }}" alt="ToothTalk" class="logo-img">
            <div class="logo-text-wrapper">
                <span>Tooth<span style="color: #26a69a;">Talk</span></span>
                <span class="clinic-subtitle">JValera Dental Clinic</span>
            </div>
        </a>
        <div class="nav-links">
            <a href="{{ url('/') }}" class="nav-btn {{ request()->is('/') ? 'primary' : 'secondary' }}">
                <i class="bi bi-house-door-fill"></i>
                Home
            </a>
            <a href="{{ route('announcements') }}" class="nav-btn {{ request()->is('announcements') ? 'primary' : 'secondary' }}">
                <i class="bi bi-megaphone-fill"></i>
                Announcements
            </a>
            <a href="{{ route('about-us') }}" class="nav-btn {{ request()->is('about-us') ? 'primary' : 'secondary' }}">
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
                <span class="burger-icon" id="guestMenuCloseIcon">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </span>
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

    <!-- Main Content -->
    @yield('content')

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
                        <li><a href="{{ route('about-us') }}"><i class="bi bi-chevron-right"></i> About Us</a></li>
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
                <p>&copy; {{ date('Y') }} ToothTalk Appointment Scheduler for JValera Dental Clinic. All rights reserved.  | Designed with <i class="bi bi-heart-fill" style="color: #00EAFF; text-shadow: none !important; filter: none !important; box-shadow: none !important;"></i> for healthy smiles</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top" title="Scroll to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <script>
        // Mobile menu toggle for guest pages
        (function(){
            const toggle = document.getElementById('guestMenuToggle');
            const closeBtn = document.getElementById('guestMenuClose');
            const menu = document.getElementById('guestMobileMenu');
            const backdrop = document.getElementById('guestMenuBackdrop');
            const toggleIcon = document.getElementById('guestMenuToggleIcon');
            const closeIcon = document.getElementById('guestMenuCloseIcon');
            
            function openMenu(){
                menu.classList.add('active');
                menu.setAttribute('aria-hidden','false');
                backdrop.classList.add('active');
                backdrop.setAttribute('aria-hidden','false');
                toggleIcon?.classList.add('open');
                closeIcon?.classList.add('open');
                document.body.style.overflow = 'hidden'; // lock page scroll under menu
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
                closeIcon?.classList.remove('open');
                document.body.style.overflow = ''; // restore scroll
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

        // Scroll to Top Button
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
</body>
</html>

