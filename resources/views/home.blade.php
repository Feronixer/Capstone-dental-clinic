<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>ToothTalk - Premium Dental Care</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            background: transparent;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2196F3;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: #2196F3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
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
            justify-content: space-between;
            align-items: center;
            padding: 3rem 3rem;
            gap: 3rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(33, 150, 243, 0.1);
            color: #2196F3;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #263238;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero-title .highlight {
            color: #2196F3;
        }

        .hero-description {
            font-size: 1.1rem;
            color: #546e7a;
            line-height: 1.7;
            margin-bottom: 2rem;
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
        }

        .main-card {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-radius: 30px;
            padding: 3rem;
            min-height: 450px;
            box-shadow: 0 20px 60px rgba(33, 150, 243, 0.3);
            position: relative;
            overflow: hidden;
        }

        .main-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .card-title {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
        }

        .feature-card {
            position: absolute;
            background: white;
            border-radius: 20px;
            padding: 1.3rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            max-width: 250px;
            z-index: 10;
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
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2196F3;
            font-size: 1.3rem;
            flex-shrink: 0;
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
                flex-direction: column;
                padding: 2rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .feature-card {
                position: relative;
                right: auto;
                top: auto;
                bottom: auto;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .hero-title {
                font-size: 2rem;
            }

            .login-buttons {
                flex-direction: column;
            }
        }

        /* Services Section */
        .services-section {
            padding: 5rem 3rem;
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            position: relative;
        }

        .services-container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
        }

        .services-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            text-align: center;
            margin-bottom: 3rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .services-carousel-wrapper {
            position: relative;
            padding: 0 60px;
        }

        .services-carousel {
            display: flex;
            gap: 2rem;
            overflow-x: hidden;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .services-carousel::-webkit-scrollbar {
            display: none;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            min-width: 250px;
            max-width: 250px;
            flex-shrink: 0;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
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
            .services-section {
                padding: 3rem 1rem;
            }

            .services-title {
                font-size: 2rem;
            }

            .services-carousel-wrapper {
                padding: 0 40px;
            }

            .service-card {
                min-width: 200px;
                max-width: 200px;
                padding: 1.5rem;
            }

            .carousel-nav-btn {
                width: 40px;
                height: 40px;
            }
        }

        /* Footer Section */
        .footer {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            color: white;
            padding: 4rem 3rem 2rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-about h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-about .footer-logo {
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2196F3;
            font-size: 1rem;
            font-weight: 800;
        }

        .footer-about p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .footer-social a:hover {
            background: white;
            color: #2196F3;
            transform: translateY(-3px);
        }

        .footer-section h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.8rem;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-section ul li a:hover {
            color: white;
            padding-left: 0.5rem;
        }

        .footer-section ul li a i {
            font-size: 0.9rem;
        }

        .footer-contact p {
            color: rgba(255, 255, 255, 1);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .footer-contact p i {
            font-size: 1.2rem;
            flex-shrink: 0;
            width: 24px;
            text-align: center;
            color: #FFA726;
        }

        .footer-contact p span {
            flex: 1;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-bottom p {
            margin: 0;
        }

        @media (max-width: 1024px) {
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .footer {
                padding: 3rem 1.5rem 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
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

        @media (max-width: 480px) {
            .chatbot-widget { right: 16px; left: 16px; width: auto; }
            .chatbot-messages { height: 240px; }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <div class="logo-icon">TT</div>
            <span>ToothTalk</span>
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
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <!-- Left Content -->
        <div class="hero-content">
            <div class="badge">
                <i class="bi bi-stars"></i>
                Premium Dental Care Since 2005
            </div>

            <h1 class="hero-title">
                Have confidence<br>in your <span class="highlight">SMILE</span> in<br>no time!
            </h1>

            <p class="hero-description">
                Experience world-class dental care with cutting-edge technology and a compassionate team dedicated to your oral health and beautiful smile.
            </p>

            {{-- Patient Portal --}}
            @if(!Auth::guard('staff')->check() && !Auth::guard('admin')->check())
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
            @endif

            {{-- Staff/Admin Portal --}}
            @if(!Auth::guard('web')->check())
                <div class="staff-admin-section">
                    @if(Auth::guard('staff')->check())
                        {{-- Staff is logged in - show only staff dashboard --}}
                        <h3>Welcome back, Staff!</h3>
                        <div class="login-buttons">
                            <a href="{{ route('staff-dashboard') }}" class="login-btn staff">
                                <i class="bi bi-speedometer2"></i>
                                Go to Dashboard
                            </a>
                        </div>
                    @elseif(Auth::guard('admin')->check())
                        {{-- Admin is logged in - show only admin dashboard --}}
                        <h3>Welcome back, Admin!</h3>
                        <div class="login-buttons">
                            <a href="{{ route('admin-dashboard') }}" class="login-btn admin">
                                <i class="bi bi-speedometer2"></i>
                                Go to Dashboard
                            </a>
                        </div>
                    @else
                        {{-- No one is logged in - show both login options --}}
                        <h3>Staff & Admin Access</h3>
                        <div class="login-buttons">
                            <a href="{{ route('staff.login') }}" class="login-btn staff">
                                <i class="bi bi-person-badge-fill"></i>
                                Staff Login
                            </a>
                            <a href="{{ route('admin.login') }}" class="login-btn admin">
                                <i class="bi bi-shield-fill-check"></i>
                                Admin Login
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right Card -->
        <div class="hero-card">
            <div class="main-card">
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

                <h2 class="card-title">Advanced Dental Clinic Environment</h2>
            </div>
    </div>
</section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="services-container">
            <h2 class="services-title">OUR SERVICES</h2>

            <div class="services-carousel-wrapper">
                <button class="carousel-nav-btn prev" onclick="scrollServices('prev')" id="servicesPrevBtn">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="services-carousel" id="servicesCarousel">
                    @forelse($services as $service)
                    <div class="service-card" onclick="openServiceModal({{ $service->id }}, '{{ $service->service_name }}', '{{ addslashes($service->description) }}', {{ $service->default_duration_minutes }}, '{{ $service->icon_class ?? 'bi-gear' }}')">
                        <div class="service-icon-box">
                            <i class="bi {{ $service->icon_class ?? 'bi-gear' }}"></i>
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
                <i class="bi" id="modalServiceIcon"></i>
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
        // Services Carousel
        function scrollServices(direction) {
            const carousel = document.getElementById('servicesCarousel');
            const scrollAmount = 270; // card width (250px) + gap (20px)
            const maxScroll = carousel.scrollWidth - carousel.clientWidth;

            if (direction === 'next') {
                const currentScroll = carousel.scrollLeft;
                const nextScroll = currentScroll + scrollAmount;

                if (nextScroll >= maxScroll - 10) {
                    // At the end, loop back to the beginning
                    carousel.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                }
            } else {
                const currentScroll = carousel.scrollLeft;

                if (currentScroll <= 10) {
                    // At the beginning, loop to the end
                    carousel.scrollTo({ left: maxScroll, behavior: 'smooth' });
                } else {
                    carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                }
            }
        }

        // Service Modal
        function openServiceModal(id, name, description, duration, iconClass) {
            document.getElementById('modalServiceName').textContent = name;
            document.getElementById('modalServiceDescription').textContent = description;
            document.getElementById('modalServiceDuration').textContent = duration + ' minutes';
            document.getElementById('modalServiceIcon').className = 'bi ' + iconClass;
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
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <!-- About Section -->
                <div class="footer-about">
                    <h3>
                        <span class="footer-logo">TT</span>
                        ToothTalk
                    </h3>
                    <p>
                        Premium dental care services since 2005. We're committed to providing world-class dental treatments with cutting-edge technology and compassionate care.
                    </p>
                    <div class="footer-social">
                        <a href="#" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ url('/') }}"><i class="bi bi-chevron-right"></i> Home</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> About Us</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Services</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Announcements</a></li>
                        <li><a href="{{ route('login') }}"><i class="bi bi-chevron-right"></i> Patient Portal</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="footer-section">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Cosmetic Dentistry</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Laser Dentistry</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Oral Surgery</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Periodontics</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Dental Crowns</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-section footer-contact">
                    <h4>Contact Info</h4>
                    <p>
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Policarpio St. Gen. T. de Leon Valenzuela City</span>
                    </p>
                    <p>
                        <i class="bi bi-telephone-fill"></i>
                        <span>(555) 123-4567</span>
                    </p>
                    <p>
                        <i class="bi bi-envelope-fill"></i>
                        <span>info@toothtalk.com</span>
                    </p>
                    <p>
                        <i class="bi bi-clock-fill"></i>
                        <span>Mon-Fri: 8am-6pm, Sat: 9am-2pm</span>
                    </p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} ToothTalk Dental Clinic. All rights reserved. | Designed with <i class="bi bi-heart-fill" style="color: #ff5252;"></i> for healthy smiles</p>
            </div>
        </div>
    </footer>

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
            <div class="chatbot-input">
                <input id="chatbot-input" type="text" placeholder="Ask about services, hours, pricing..." autocomplete="off" />
                <button id="chatbot-send" class="send-btn" aria-label="Send message">
                    <i class="bi bi-send-fill"></i>
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
                const qTokens = tokenize(q);
                // 1) Fuzzy match FAQs by token overlap
                let best = { score: 0, inter: 0, a: null };
                for (const item of faqIndexed) {
                    if (!item.tokens.length) continue;
                    const { inter, jaccard } = overlapScore(qTokens, item.tokens);
                    const score = inter >= 2 ? jaccard + 0.1 : jaccard; // slight boost if >=2 overlapping keywords
                    if (score > best.score) best = { score, inter, a: item.a };
                }
                if (best.a && (best.score >= 0.25 || best.inter >= 2)) return best.a;

                // 2) Fallback generic message
                return 'Thanks for your message! Please check our FAQs or ask a specific question.';
            }

            function sendUserMessage(text) {
                if (!text.trim()) return;
                addMessage(text.trim(), 'user');

                // Show typing indicator
                showTypingIndicator();

                // Simulate bot thinking time (1-2 seconds)
                const typingDelay = 1000 + Math.random() * 1000;

                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(getBotReply(text), 'bot');
                }, typingDelay);
            }

            function renderChips() {
                chipsEl.innerHTML = '';
                quickIntents.forEach(intent => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'chip';
                    btn.textContent = intent.label;
                    btn.addEventListener('click', () => sendUserMessage(intent.value));
                    chipsEl.appendChild(btn);
                });
            }

            function openChat() {
                widget.classList.add('open');
                widget.setAttribute('aria-hidden', 'false');
                if (!messagesEl.dataset.welcomed) {
                    // Show typing indicator before welcome message
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                        renderChips();
                    }, 800);
                    messagesEl.dataset.welcomed = '1';
                }
                inputEl.focus();
            }

            function closeChat() {
                widget.classList.remove('open');
                widget.setAttribute('aria-hidden', 'true');
            }

            toggleBtn.addEventListener('click', () => {
                if (widget.classList.contains('open')) closeChat(); else openChat();
            });
            closeBtn.addEventListener('click', closeChat);
            sendBtn.addEventListener('click', () => {
                const v = inputEl.value; inputEl.value = ''; sendUserMessage(v);
            });
            inputEl.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') { const v = inputEl.value; inputEl.value = ''; sendUserMessage(v); }
            });
        })();
    </script>
    @endif

    <script>
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
