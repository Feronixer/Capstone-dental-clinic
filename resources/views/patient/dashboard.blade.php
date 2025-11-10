@extends('layout.patient.app')
@section('content')

<style>
    .main-wrapper {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        min-height: 80vh;
        padding: 0;
        overflow-x: hidden;
        overflow-y: visible;
        width: 100%;
        max-width: 100vw;
    }

    [data-theme="dark"] .main-wrapper {
        background: linear-gradient(135deg, var(--dm-bg-primary, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
    }

    /* Dark Mode Hero Section */
    [data-theme="dark"] .hero-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .hero-title .highlight {
        color: #60a5fa !important;
    }

    [data-theme="dark"] .hero-title .highlight:hover {
        color: #00EAFF !important; /* neon light blue on hover */
        text-shadow: 0 2px 4px rgba(0, 234, 255, 0.3), 0 0 8px rgba(0, 234, 255, 0.4), 0 0 16px rgba(0, 234, 255, 0.3), 0 1px 2px rgba(255, 255, 255, 0.2) !important;
        filter: saturate(1.1);
    }

    [data-theme="dark"] .hero-description {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .badge {
        background: rgba(59, 130, 246, 0.2) !important;
        color: #93c5fd !important;
    }

    [data-theme="dark"] .main-card {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
        box-shadow: 0 20px 60px rgba(30, 64, 175, 0.4) !important;
    }

    /* Modern Color Palette */
    :root {
        --primary-blue: #2196F3;
        --primary-dark: #1976D2;
        --primary-light: #BBDEFB;
        --accent-teal: #26a69a;
        --accent-teal-dark: #00897b;
        --text-dark: #263238;
        --text-medium: #546e7a;
        --text-light: #78909c;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
        --shadow-xl: 0 12px 48px rgba(0, 0, 0, 0.15);
    }

    [data-theme="dark"] .feature-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .feature-card:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .feature-text h4 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .feature-text p {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .feature-icon {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.2) 100%) !important;
        color: #93c5fd !important;
    }

    /* Hero Section */
    .hero-section {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 4.7rem 3rem;
        gap: 5rem;
        max-width: 1400px;
        margin: 0 auto;
        overflow: visible;
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }

    .hero-content {
        flex: 1;
        max-width: 600px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 0;
        min-width: 0;
        position: relative;
        z-index: 1;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(33, 150, 243, 0.1);
        color: #1976D2;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 24px;
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
        position: relative;
        transition: color 220ms ease, filter 220ms ease, text-shadow 220ms ease;
    }

    .hero-title .highlight:hover {
        color: #ffffff; /* invert to white on hover */
        text-shadow: 0 6px 18px rgba(10, 42, 107, 0.65), 0 0 10px rgba(10,42,107,0.35);
        filter: saturate(1.2);
    }

    .hero-description {
        font-size: 1.1rem;
        color: #546e7a;
        line-height: 1.7;
        margin-bottom: 1rem;
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
        align-items: center;
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
    }

    .feature-text p {
        font-size: 0.85rem;
        color: #78909c;
        line-height: 1.4;
        margin-bottom: 0;
    }

    /* Services Section */
    .services-section {
        padding: 5rem 3rem;
        background: linear-gradient(135deg, #0a2a6b 0%, #0b3b91 100%);
        position: relative;
        color: #ffffff;
        overflow-x: hidden;
        width: 100%;
        box-sizing: border-box;
    }

    [data-theme="dark"] .services-section {
        background: linear-gradient(135deg, #0a2a6b 0%, #0b3b91 100%) !important;
        color: #ffffff !important;
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

    [data-theme="dark"] .services-title { color: #ffffff !important; }
    [data-theme="dark"] .services-subtitle { color: #ffffff !important; opacity: 0.85 !important; }

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
        box-sizing: border-box;
        touch-action: pan-y pinch-zoom; /* Allow vertical scrolling and pinch zoom, but handle horizontal swipes */
    }
    
    /* On mobile, allow horizontal panning for swipe */
    @media (max-width: 768px), (hover: none) {
        .services-carousel-wrapper {
            touch-action: pan-x pan-y pinch-zoom;
        }
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
        box-sizing: border-box;
        overflow: hidden;
    }
    
    .service-card.initialized {
        opacity: 1;
        visibility: visible;
    }

    [data-theme="dark"] .service-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .service-card:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .service-icon-box i {
        color: #00EAFF !important;
        filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.4)) drop-shadow(0 0 16px rgba(0, 234, 255, 0.2));
    }

    [data-theme="dark"] .service-card h3 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .services-empty-state {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .services-empty-state i {
        opacity: 0.4 !important;
    }

    .service-card:hover {
        transform: translateZ(80px) scale(1.05) !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25), 0 0 40px rgba(0, 0, 0, 0.15);
        z-index: 100;
    }
    
    /* Disable hover effects on mobile/touch devices */
    @media (max-width: 768px), (hover: none) {
        .service-card:hover {
            transform: none !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1), 0 0 20px rgba(0, 0, 0, 0.05) !important;
            z-index: auto !important;
        }
        
        [data-theme="dark"] .service-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4) !important;
        }
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
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        word-wrap: break-word;
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

    [data-theme="dark"] .carousel-nav-btn {
        background: var(--dm-card-bg, #1e293b) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .carousel-nav-btn:hover {
        background: var(--dm-bg-tertiary, #334155) !important;
    }

    [data-theme="dark"] .carousel-nav-btn i {
        color: #00EAFF !important;
        filter: drop-shadow(0 0 6px rgba(0, 234, 255, 0.4)) drop-shadow(0 0 12px rgba(0, 234, 255, 0.2));
    }
    
    [data-theme="dark"] .carousel-nav-btn:hover i {
        color: #5CECFF !important;
        filter: drop-shadow(0 0 10px rgba(0, 234, 255, 0.6)) drop-shadow(0 0 20px rgba(0, 234, 255, 0.3));
    }

    .carousel-nav-btn:hover {
        background: #f1f5f9;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .carousel-nav-btn.prev { left: clamp(0px, 1vw, 8px); }

    .carousel-nav-btn.next { right: clamp(0px, 1vw, 8px); }

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

    [data-theme="dark"] .service-modal-content {
        background: var(--dm-card-bg, #1e293b) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6) !important;
    }

    [data-theme="dark"] .service-modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .service-modal-description {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .service-modal-close {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .service-modal-close:hover {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .service-modal-duration {
        background: var(--dm-bg-secondary, #0f172a) !important;
    }

    [data-theme="dark"] .service-modal-duration-label {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .service-modal-duration-value {
        color: #10b981 !important;
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
    
    [data-theme="dark"] .service-modal-icon i {
        color: #00EAFF !important;
        filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.4)) drop-shadow(0 0 16px rgba(0, 234, 255, 0.2));
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

    @media (max-width: 1200px) {
        .hero-section {
            padding: 2.5rem 2rem;
            gap: 2rem;
            overflow: visible;
        }
    }

    @media (max-width: 1024px) {
        .hero-section {
            padding: 2rem;
            margin-bottom: 1.5rem;
            gap: 2rem;
            overflow: visible;
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

        .hero-title { 
            font-size: 50px; 
        }

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

        .services-section {
            padding: 4rem 2rem;
        }

        .services-carousel-wrapper {
            padding: 0 50px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            flex-direction: row; /* keep side-by-side at 768px */
            padding: 1.5rem 1rem;
            gap: 1rem;
            align-items: flex-start;
            overflow: visible;
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
            margin-bottom: 1rem;
        }

        .hero-description {
            font-size: 1rem;
            margin-bottom: 1.5rem;
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

        .services-section {
            padding: 3rem 1rem;
        }

        .services-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .services-subtitle {
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .services-carousel-wrapper {
            padding: 0 16px;
            height: clamp(350px, 45vh, 500px);
            min-height: 350px;
        }

        .carousel-nav-btn.prev { 
            left: 8px; 
        }
        
        .carousel-nav-btn.next { 
            right: 8px; 
        }

        .carousel-nav-btn {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .service-card {
            width: clamp(180px, 30vw, 240px);
            height: clamp(180px, 30vw, 240px);
            padding: clamp(1rem, 2vw, 1.5rem);
        }

        .service-icon-box {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
        }

        .service-icon-box i {
            font-size: 2.2rem;
        }

        .service-card h3 {
            font-size: 1rem;
        }

        .services-empty-state {
            padding: 2rem 1rem;
        }

        .services-empty-state i {
            font-size: 3rem !important;
        }

        .services-empty-state p {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 480px) {
        .hero-title {
            font-size: 76px;
            margin-bottom: 1rem;
            line-height: 1.1;
            text-align: left;
        }

        .main-card {
            min-height: 320px;
            padding: 1rem 1rem 2rem;
            border-radius: 24px;
            position: relative;
            overflow: visible;
            width: 100%;
        }

        .main-card-image {
            position: absolute;
            top: 20%;
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

        .main-card::before {
            width: 200px;
            height: 200px;
            top: -30%;
            right: -10%;
        }

        .main-card::after {
            width: 180px;
            height: 180px;
            bottom: -25%;
            left: -8%;
        }

        .card-title {
            position: absolute;
            left: 1rem;
            bottom: 1rem;
            width: calc(100% - 2rem);
            text-align: left;
            font-size: 1.2rem;
        }

        .card-title i {
            color: #00EAFF;
            font-size: 1.4rem;
            filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.8)) drop-shadow(0 0 16px rgba(0, 234, 255, 0.6));
            animation: arrow-pulse 2s ease-in-out infinite;
        }

        .services-section {
            padding: 2.5rem 1rem;
        }

        .services-title {
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
        }

        .services-subtitle {
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .services-carousel-wrapper {
            padding: 0 12px;
            height: clamp(300px, 40vh, 450px);
            min-height: 300px;
        }

        .carousel-nav-btn.prev { 
            left: 4px; 
        }
        
        .carousel-nav-btn.next { 
            right: 4px; 
        }

        .carousel-nav-btn {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
        
        .service-card {
            width: clamp(160px, 35vw, 200px);
            height: clamp(160px, 35vw, 200px);
            padding: 1rem;
        }

        .service-icon-box {
            width: 50px;
            height: 50px;
            margin-bottom: 0.75rem;
        }

        .service-icon-box i {
            font-size: 1.8rem;
        }

        .service-card h3 {
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .services-empty-state {
            padding: 1.5rem 0.75rem;
        }

        .services-empty-state i {
            font-size: 2.5rem !important;
        }

        .services-empty-state p {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .services-section {
            padding: 2rem 0.75rem;
        }

        .services-title {
            font-size: 1.4rem;
        }

        .services-subtitle {
            font-size: 0.8rem;
            margin-bottom: 1.25rem;
        }

        .services-carousel-wrapper {
            padding: 0 8px;
            height: clamp(280px, 38vh, 400px);
            min-height: 280px;
        }

        .carousel-nav-btn {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }

        .carousel-nav-btn.prev { 
            left: 2px; 
        }
        
        .carousel-nav-btn.next { 
            right: 2px; 
        }

        .service-card {
            width: clamp(140px, 38vw, 180px);
            height: clamp(140px, 38vw, 180px);
            padding: 0.75rem;
        }

        .service-icon-box {
            width: 45px;
            height: 45px;
            margin-bottom: 0.5rem;
        }

        .service-icon-box i {
            font-size: 1.5rem;
        }

        .service-card h3 {
            font-size: 0.85rem;
            line-height: 1.2;
        }

        .services-empty-state {
            padding: 1.25rem 0.5rem;
        }

        .services-empty-state i {
            font-size: 2rem !important;
        }

        .services-empty-state p {
            font-size: 0.8rem;
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

    /* Stack hero vertically on medium-small screens */
    @media (max-width: 640px) {
        .hero-section {
            flex-direction: column;
            gap: 2rem;
            margin-bottom: 3rem;
            align-items: stretch;
            overflow: visible;
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
            gap: 3rem;
            overflow: visible;
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
            font-size: 48px;
        }

        .hero-description {
            font-size: 0.9rem;
        }
        
        .main-card { 
            min-height: 420px; 
            padding: 1rem 1rem 2rem;
            position: relative;
            overflow: visible;
            width: 100%;
        }
        .main-card-image { 
            position: absolute;
            top: 55%;
            left: 55%;
            transform: translate(-50%, -50%);
            width: 120%;
            max-width: none;
            height: auto;
            min-height: 100%;
            object-fit: contain;
            object-position: center bottom;
            opacity: 1;
            z-index: 1;
            filter: brightness(1.05) contrast(1.1) drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
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
        .feature-card.top { top: 1.5rem !important; right: -0.5rem !important; }
        .feature-card.bottom { bottom: 11rem !important; right: -0.5rem !important; }
        .feature-text h4 { font-size: 0.95rem; }
        .feature-text p { font-size: 0.8rem; }

        .card-title {
            font-size: 1rem;
            bottom: 1rem;
        }

        /* Services Section - Editable Styles */
        .services-section {
            padding: 2.5rem 1rem;
        }

        .services-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .services-subtitle {
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .services-carousel-wrapper {
            padding: 0 12px;
            height: clamp(300px, 40vh, 450px);
            min-height: 300px;
        }

        .carousel-nav-btn.prev { 
            left: 4px; 
        }
        
        .carousel-nav-btn.next { 
            right: 4px; 
        }

        .carousel-nav-btn {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
        
        .service-card {
            width: clamp(160px, 35vw, 200px);
            height: clamp(160px, 35vw, 200px);
            padding: 1rem;
        }

        .service-icon-box {
            width: 50px;
            height: 50px;
            margin-bottom: 0.75rem;
        }

        .service-icon-box i {
            font-size: 1.8rem;
        }

        .service-card h3 {
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .services-empty-state {
            padding: 1.5rem 0.75rem;
        }

        .services-empty-state i {
            font-size: 2.5rem !important;
        }

        .services-empty-state p {
            font-size: 0.85rem;
        }
    }
    @media (min-width: 250px) and (max-width: 350px) {
        .hero-section {
            padding: 0.5rem;
            gap: 1.5rem;
            overflow: visible;
        }

        .hero-title {
            font-size: 2.8rem;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .hero-description {
            font-size: 0.85rem;
        }

        .badge {
            font-size: 14px;
            padding: 6px 12px;
        }

        .main-card {
            min-height: 300px;
            padding: 0.75rem 0.75rem 1.5rem;
        }

        .main-card-image {
            top: 10.5rem;
            left: 55%;
        }

        .feature-card {
            max-width: 180px;
            padding: 0.75rem 0.8rem;
        }

        .feature-text h4 {
            font-size: 0.9rem;
        }

        .feature-text p {
            font-size: 0.75rem;
        }

        .feature-card.top { top: 1.5rem !important; right: -0.5rem !important; }
        .feature-card.bottom { bottom: 5.8rem !important; right: -0.5rem !important; }
        .feature-text h4 { font-size: 0.95rem; }
        .feature-text p { font-size: 0.8rem; }

        .feature-text h4 { font-size: 0.9rem; }
        .feature-text p { bottom: 0; font-size: 0.6rem; }

        .card-title {
            font-size: 0.9rem;
        }

        .services-title {
            font-size: 1.3rem;
        }

        .service-card h3 {
            font-size: 0.4rem;
            line-height: 1.3;
        }

        .service-card.initialized h3 {
            font-size: 0.85rem;
            line-height: 1.3;
        }

    }

    /* Navigation Bar Positioning Fixes */
    .patient-header .header-container {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 1rem;
        width: 100%;
        box-sizing: border-box;
    }

    .patient-header .header-logo {
        display: flex !important;
        align-items: center !important;
        gap: 1rem;
        flex-shrink: 0;
        min-width: 0;
    }

    .patient-header .header-nav {
        flex: 1 !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        min-width: 0;
    }

    .patient-header .nav-menu {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .patient-header .nav-item {
        display: flex !important;
        align-items: center !important;
        position: relative;
        flex-shrink: 0;
        min-width: 0;
    }

    .patient-header .nav-item .nav-link {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    /* Fix nav-link::before size for desktop */
    .patient-header .nav-item .nav-link::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
        opacity: 0;
        transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        z-index: 0;
        box-sizing: border-box;
    }

    .patient-header .nav-item .nav-link:hover::before {
        opacity: 1 !important;
    }

    .patient-header .nav-item .nav-link.active::before {
        opacity: 0 !important;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    }

    /* Ensure nav-link content appears above ::before */
    .patient-header .nav-item .nav-link i,
    .patient-header .nav-item .nav-link span {
        position: relative;
        z-index: 1;
    }

    .patient-header .header-container-right {
        display: flex !important;
        align-items: center !important;
        gap: 0.9rem;
        flex-shrink: 0;
        margin-left: auto;
    }

    .patient-header .header-actions {
        display: flex !important;
        align-items: center !important;
        gap: 0.9rem;
    }

    .patient-header .user-profile-btn {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem;
    }

    .patient-header .user-info {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        justify-content: center !important;
    }

    /* Desktop Navigation Bar Size Reduction */
    @media (min-width: 992px) {
        .patient-header .header-container {
            padding: 0.6rem 2rem !important;
        }

        .patient-header .logo-img {
            width: 48px !important;
            height: 48px !important;
        }

        .patient-header .clinic-name {
            font-size: 1.4rem !important;
        }

        .patient-header .nav-item .nav-link {
            padding: 0.55rem 1rem !important;
            font-size: 0.88rem !important;
            gap: 0.4rem !important;
        }

        .patient-header .nav-item .nav-link i {
            font-size: 1rem !important;
        }

        .patient-header .nav-menu {
            gap: 0.4rem !important;
        }

        .patient-header .icon-btn {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.95rem !important;
        }

        .patient-header .profile-avatar {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            min-height: 32px !important;
            font-size: 0.75rem !important;
        }

        .patient-header .user-name {
            font-size: 0.85rem !important;
        }

        .patient-header .user-role {
            font-size: 0.7rem !important;
        }

        .patient-header .user-profile-btn {
            padding: 0.4rem 0.85rem !important;
            gap: 0.6rem !important;
        }

        .patient-header .header-container-right {
            gap: 0.7rem !important;
        }

        .patient-header .header-actions {
            gap: 0.7rem !important;
        }

        .patient-header .header-logo {
            gap: 0.75rem !important;
        }
    }

    /* Navigation Menu Responsiveness */
    @media (min-width: 1600px) {
        .patient-header .header-container {
            padding: 0.7rem 2.5rem !important;
        }

        .patient-header .logo-img {
            width: 50px !important;
            height: 50px !important;
        }

        .patient-header .clinic-name {
            font-size: 1.5rem !important;
        }

        .patient-header .nav-item {
            flex-shrink: 0;
            min-width: 0;
        }

        .patient-header .nav-item .nav-link {
            padding: 0.6rem 1.2rem !important;
            font-size: 0.9rem !important;
            min-width: fit-content;
        }

        .patient-header .nav-item .nav-link i {
            font-size: 1.05rem !important;
        }

        .patient-header .nav-menu {
            gap: 0.45rem !important;
        }
    }

    @media (min-width: 1400px) and (max-width: 1599px) {
        .patient-header .header-container {
            padding: 0.7rem 2.25rem !important;
        }

        .patient-header .logo-img {
            width: 50px !important;
            height: 50px !important;
        }

        .patient-header .clinic-name {
            font-size: 1.5rem !important;
        }

        .patient-header .nav-item {
            flex-shrink: 0;
            min-width: 0;
        }

        .patient-header .nav-item .nav-link {
            padding: 0.6rem 1.15rem !important;
            font-size: 0.9rem !important;
            min-width: fit-content;
        }

        .patient-header .nav-item .nav-link i {
            font-size: 1.05rem !important;
        }

        .patient-header .nav-menu {
            gap: 0.45rem !important;
        }
    }

    @media (min-width: 1200px) and (max-width: 1399px) {
        .patient-header .header-container {
            padding: 0.65rem 2rem !important;
        }

        .patient-header .logo-img {
            width: 48px !important;
            height: 48px !important;
        }

        .patient-header .clinic-name {
            font-size: 1.4rem !important;
        }

        .patient-header .nav-item {
            flex-shrink: 0;
            min-width: 0;
        }

        .patient-header .nav-item .nav-link {
            padding: 0.55rem 1.1rem !important;
            font-size: 0.88rem !important;
            min-width: fit-content;
        }

        .patient-header .nav-item .nav-link i {
            font-size: 1rem !important;
        }

        .patient-header .nav-menu {
            gap: 0.4rem !important;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .patient-header .header-container {
            padding: 0.6rem 1.5rem !important;
        }

        .patient-header .logo-img {
            width: 48px !important;
            height: 48px !important;
        }

        .patient-header .clinic-name {
            font-size: 1.4rem !important;
        }

        .patient-header .nav-item {
            flex-shrink: 0;
            min-width: 0;
        }

        .patient-header .nav-item .nav-link {
            padding: 0.55rem 1rem !important;
            font-size: 0.85rem !important;
            min-width: fit-content;
        }

        .patient-header .nav-item .nav-link i {
            font-size: 0.95rem !important;
        }

        .patient-header .nav-menu {
            gap: 0.4rem !important;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .patient-header .header-container {
            padding: 0.7rem 1.5rem;
        }

        .patient-header .nav-item {
            flex-shrink: 0;
            min-width: 0;
        }

        .patient-header .nav-item .nav-link {
            padding: 0.6rem 1rem;
            font-size: 0.88rem;
            min-width: fit-content;
        }

        .patient-header .nav-item .nav-link i {
            font-size: 1.05rem;
        }

        .patient-header .nav-menu {
            gap: 0.4rem;
        }
    }

    @media (max-width: 1200px) {
        .patient-header .header-nav {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .patient-header .nav-menu {
            flex-wrap: nowrap;
            overflow: visible;
            justify-content: center;
            width: 100%;
        }

        .patient-header .nav-item {
            flex-shrink: 0;
            min-width: 0;
            max-width: 100%;
        }

        .patient-header .nav-item .nav-link {
            white-space: nowrap;
            min-width: fit-content;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    @media (max-width: 992px) {
        .patient-header .header-nav {
            display: none !important;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section reveal-element reveal-slide-up">
    <!-- Left Content -->
    <div class="hero-content">
        <div class="badge">
            <i class="bi bi-stars"></i>
            Welcome Back, @if(auth()->user()->info){{ trim(auth()->user()->info->first_name . ' ' . auth()->user()->info->last_name) }}@else{{ auth()->user()->name ?? 'User' }}@endif!
        </div>

        <h1 class="hero-title">
            Have confidence<br>in your <span class="highlight">SMILE</span><br>in no time!
        </h1>

        <p class="hero-description">
            Experience world-class dental care with cutting-edge technology and a compassionate team dedicated to your oral health and beautiful smile.
        </p>
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

            <h2 class="card-title">JValera Dental Clinic <i class="bi bi-arrow-right"></i></h2>
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
                            <img src="{{ asset('storage/' . \Illuminate\Support\Str::after($ic,'uploaded:')) }}" alt="icon" style="width:80px;height:80px;object-fit:contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" onload="this.style.display='block'; this.nextElementSibling.style.display='none';">
                            <i class="bi bi-gear" style="display:none;"></i>
                        @else
                            <i class="bi {{ $ic ?? 'bi-gear' }}"></i>
                        @endif
                    </div>
                    <h3>{{ $service->service_name }}</h3>
                </div>
                @empty
                <div class="col-12 text-center py-5 services-empty-state" style="color: white; min-width: 100%;">
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
    let isSwipeProcessing = false; // Prevent multiple swipes from being processed simultaneously
    
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
        
        // Check if mobile device
        const isMobile = window.innerWidth <= 768 || 'ontouchstart' in window;
        
        // Add hover event listeners to each card (only on desktop)
        if (!isMobile) {
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', () => lockCardToCenter(index));
                card.addEventListener('mouseleave', () => unlockCard());
            });
        }
        
        // Add touch/swipe event listeners for mobile
        if (isMobile) {
            let touchStartX = 0;
            let touchStartY = 0;
            let touchEndX = 0;
            let touchEndY = 0;
            let isSwiping = false;
            let swipeThreshold = 50; // Minimum distance for a swipe
            
            const carouselWrapper = carousel.closest('.services-carousel-wrapper') || carousel;
            
            carouselWrapper.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                isSwiping = true;
            }, { passive: true });
            
            carouselWrapper.addEventListener('touchmove', (e) => {
                if (!isSwiping) return;
                touchEndX = e.touches[0].clientX;
                touchEndY = e.touches[0].clientY;
                
                // Check if this is a horizontal swipe
                const deltaX = Math.abs(touchEndX - touchStartX);
                const deltaY = Math.abs(touchEndY - touchStartY);
                
                // If horizontal movement is greater than vertical, prevent default scrolling
                if (deltaX > deltaY && deltaX > 10) {
                    e.preventDefault();
                }
            }, { passive: false });
            
            carouselWrapper.addEventListener('touchend', (e) => {
                if (!isSwiping) return;
                isSwiping = false;
                
                // Prevent processing multiple swipes simultaneously or while animation is in progress
                if (isSwipeProcessing || isScrollingAnimated) {
                    touchStartX = 0;
                    touchStartY = 0;
                    touchEndX = 0;
                    touchEndY = 0;
                    return;
                }
                
                const deltaX = touchEndX - touchStartX;
                const deltaY = touchEndY - touchStartY;
                const absDeltaX = Math.abs(deltaX);
                const absDeltaY = Math.abs(deltaY);
                
                // Only process swipe if horizontal movement is greater than vertical
                if (absDeltaX > absDeltaY && absDeltaX > swipeThreshold) {
                    // Set flag to prevent multiple swipes
                    isSwipeProcessing = true;
                    
                    // Determine swipe direction
                    if (deltaX < 0) {
                        // Swipe left - rotate left (counter-clockwise)
                        scrollServices('prev');
                    } else {
                        // Swipe right - rotate right (clockwise)
                        scrollServices('next');
                    }
                    
                    // Reset flag after animation completes (fallback timeout)
                    // The flag will also be reset when animation completes in updateCarouselPosition
                    setTimeout(() => {
                        isSwipeProcessing = false;
                    }, 300); // Fallback timeout in case animation doesn't complete properly
                }
                
                // Reset touch positions
                touchStartX = 0;
                touchStartY = 0;
                touchEndX = 0;
                touchEndY = 0;
            });
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
            // Use faster speed for button clicks (0.3) than hover (0.2) for better responsiveness
            const animationSpeed = hoveredCardIndex !== null ? 0.2 : 0.3;
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
                    isSwipeProcessing = false; // Reset swipe processing flag when animation completes
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
            const defaultCardWidth = isMobile ? 200 : 280;
            const defaultCardHeight = isMobile ? 200 : 280;
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

    // Image Modal for full-size viewing
    function openImageModal(imageSrc, imageTitle) {
        const modal = document.createElement('div');
        modal.className = 'image-modal';
        modal.innerHTML = `
            <div class="image-modal-overlay" onclick="closeImageModal()">
                <div class="image-modal-content" onclick="event.stopPropagation()">
                    <button class="image-modal-close" onclick="closeImageModal()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <img src="${imageSrc}" alt="${imageTitle}" class="image-modal-img">
                    <div class="image-modal-title">${imageTitle}</div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeImageModal() {
        const modal = document.querySelector('.image-modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(modal);
                document.body.style.overflow = '';
            }, 300);
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

</script>

<style>
.image-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-modal.show {
    opacity: 1;
}

.image-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.image-modal-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.image-modal-close {
    position: absolute;
    top: -50px;
    right: 0;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 10;
}

.image-modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.image-modal-img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.image-modal-title {
    color: white;
    margin-top: 1rem;
    font-size: 1.25rem;
    font-weight: 600;
    text-align: center;
}

@media (max-width: 768px) {
    .image-modal-content {
        max-width: 95vw;
        max-height: 95vh;
    }

    .image-modal-close {
        top: -40px;
        width: 35px;
        height: 35px;
        font-size: 1.25rem;
    }

    .image-modal-img {
        max-height: 75vh;
    }

    .image-modal-title {
        font-size: 1rem;
    }
}
</style>

<!-- Feedback Section -->
<section class="feedback-section reveal-element reveal-slide-up" id="feedback">
    <div class="feedback-container">
        <h2 class="feedback-title">
            Rate Your Experience
        </h2>
        <p class="feedback-description">
            Help us improve our services by sharing your feedback
        </p>
        <div class="feedback-badge-wrapper">
            <span class="title-badge" id="titleFeedbackBadge" style="display: none;">
                <i class="bi bi-exclamation-circle"></i> You have pending reviews
            </span>
        </div>

        <div class="feedback-cards">
            <!-- Give Feedback Card -->
            <div class="feedback-action-card" id="giveFeedbackCard">
                <div class="feedback-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h3>Give Feedback</h3>
                <p>Rate your recent appointment</p>
                <button class="feedback-btn" onclick="openFeedbackModal()">
                    <i class="bi bi-star me-2"></i>Rate Now
                </button>
                <span class="pending-count" id="pendingFeedbackCount" style="display: none;">0</span>
            </div>

            <!-- View History Card -->
            <div class="feedback-action-card" id="viewHistoryCard">
                <div class="feedback-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3>Feedback History</h3>
                <p>View your past ratings</p>
                <button class="feedback-btn feedback-btn-secondary" onclick="openHistoryModal()">
                    <i class="bi bi-list-ul me-2"></i>View History
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content feedback-modal-content">
            <div class="modal-header feedback-modal-header border-0">
                <h5 class="modal-title feedback-modal-title fw-bold">Rate Your Appointment</h5>
                <button type="button" class="btn-close feedback-modal-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body feedback-modal-body">
                <div id="feedbackForm">
                    <div class="mb-4">
                        <label class="form-label feedback-form-label fw-bold">Select Appointment</label>
                        <select class="form-select feedback-form-select" id="appointmentSelect">
                            <option value="">Loading appointments...</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label feedback-form-label fw-bold">Your Rating</label>
                        <div class="star-rating" id="starRating">
                            <i class="bi bi-star" data-rating="1"></i>
                            <i class="bi bi-star" data-rating="2"></i>
                            <i class="bi bi-star" data-rating="3"></i>
                            <i class="bi bi-star" data-rating="4"></i>
                            <i class="bi bi-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" id="ratingValue" value="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label feedback-form-label fw-bold">Comments (Optional)</label>
                        <textarea class="form-control feedback-form-textarea" id="feedbackComment" rows="3"
                            placeholder="Share your experience with us..."></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn feedback-submit-btn" onclick="submitFeedback()">
                            <i class="bi bi-send me-2"></i>Submit Feedback
                        </button>
                    </div>
                </div>

                <div id="noAppointmentsMessage" style="display: none;" class="text-center py-5 no-appointments-message">
                    <i class="bi bi-calendar-x no-appointments-icon"></i>
                    <p class="no-appointments-text mt-3">No completed appointments to rate</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content feedback-modal-content">
            <div class="modal-header feedback-modal-header border-0">
                <h5 class="modal-title feedback-modal-title fw-bold">Feedback History</h5>
                <button type="button" class="btn-close feedback-modal-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body feedback-modal-body">
                <div id="feedbackHistory">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden;">
            <div class="modal-body p-0">
                <div style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); padding: 2.5rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: white; border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        <i class="bi bi-check-circle-fill" style="color: #4caf50; font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Success!</h4>
                    <p class="text-white mb-4" id="successMessage">Thank you for your feedback!</p>
                    <button type="button" class="btn btn-light fw-bold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 25px;">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden;">
            <div class="modal-body p-0">
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 2.5rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: white; border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        <i class="bi bi-exclamation-circle-fill" style="color: #ef4444; font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Oops!</h4>
                    <p class="text-white mb-4" id="errorMessage">Something went wrong</p>
                    <button type="button" class="btn btn-light fw-bold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 25px;">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Feedback Section */
    .feedback-section {
        padding: 4rem 3rem;
        background: #f8f9fa;
        overflow-x: hidden;
        width: 100%;
        box-sizing: border-box;
    }

    [data-theme="dark"] .feedback-section {
        background: var(--dm-bg-primary, #1e293b) !important;
    }

    .feedback-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .feedback-title {
        font-size: 2rem;
        font-weight: 800;
        color: #263238;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    [data-theme="dark"] .feedback-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    .feedback-badge-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .title-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #1976D2;
        padding: 0.5rem 1.25rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
        animation: gentle-pulse 2s ease-in-out infinite;
    }

    [data-theme="dark"] .title-badge {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.2) 100%) !important;
        color: #60a5fa !important;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4) !important;
    }

    [data-theme="dark"] .title-badge i {
        color: #60a5fa !important;
    }

    @keyframes gentle-pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.03);
        }
    }

    .feedback-description {
        text-align: center;
        color: #546e7a;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    [data-theme="dark"] .feedback-description {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    .feedback-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .feedback-action-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
    }

    [data-theme="dark"] .feedback-action-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .feedback-action-card:hover {
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.2) !important;
    }

    [data-theme="dark"] .feedback-action-card h3 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .feedback-action-card p {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    .feedback-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(33, 150, 243, 0.15);
    }

    .feedback-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .feedback-action-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #263238;
        margin-bottom: 0.5rem;
    }

    .feedback-action-card p {
        color: #78909c;
        margin-bottom: 1.5rem;
    }

    .feedback-btn {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .feedback-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .feedback-btn-secondary {
        background: linear-gradient(135deg, #546e7a 0%, #37474f 100%);
    }

    .pending-count {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        animation: pulse-badge 2s ease-in-out infinite;
        z-index: 10;
    }

    @keyframes pulse-badge {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.6);
        }
    }

    /* Feedback Modal Styles */
    .feedback-modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 8px 24px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        overflow: hidden;
    }

    .feedback-modal-header {
        padding: 1.5rem 2rem;
        background: transparent;
        border-bottom: 1px solid #e5e7eb;
    }

    .feedback-modal-title {
        font-size: 1.5rem;
        color: #1e293b;
        margin: 0;
    }

    .feedback-modal-close {
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }

    .feedback-modal-close:hover {
        opacity: 1;
    }

    .feedback-modal-body {
        padding: 2rem;
    }

    .feedback-form-label {
        color: #37474f;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }

    .feedback-form-select,
    .feedback-form-textarea {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .feedback-form-select:focus,
    .feedback-form-textarea:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
        outline: none;
    }

    .feedback-submit-btn {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
    }

    .feedback-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
    }

    .feedback-submit-btn:active {
        transform: translateY(0);
    }

    .no-appointments-icon {
        font-size: 3rem;
        opacity: 0.3;
        color: #64748b;
    }

    .no-appointments-text {
        color: #64748b;
        font-size: 1rem;
    }

    /* Star Rating */
    .star-rating {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        font-size: 2.5rem;
        margin: 1rem 0;
    }

    .star-rating i {
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star-rating i:hover,
    .star-rating i.active,
    .star-rating i.bi-star-fill {
        color: #0a2a6b;
        transform: scale(1.1);
        filter: drop-shadow(0 2px 4px rgba(10, 42, 107, 0.3));
    }

    /* Dark Mode Styles for Feedback Modal */
    [data-theme="dark"] .feedback-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 8px 24px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .feedback-modal-header {
        border-bottom-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .feedback-modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .feedback-form-label {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .feedback-form-select,
    [data-theme="dark"] .feedback-form-textarea {
        background: var(--dm-bg-secondary, #0f172a) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .feedback-form-select:focus,
    [data-theme="dark"] .feedback-form-textarea:focus {
        border-color: #00EAFF !important;
        box-shadow: 0 0 0 4px rgba(0, 234, 255, 0.2) !important;
    }

    [data-theme="dark"] .feedback-form-select::placeholder,
    [data-theme="dark"] .feedback-form-textarea::placeholder {
        color: var(--dm-text-muted, #64748b) !important;
    }

    [data-theme="dark"] .no-appointments-icon {
        color: var(--dm-text-muted, #64748b) !important;
        opacity: 0.3 !important;
    }

    [data-theme="dark"] .no-appointments-text {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    /* Star Rating Dark Mode */
    [data-theme="dark"] .star-rating i {
        color: #475569 !important;
    }

    [data-theme="dark"] .star-rating i:hover,
    [data-theme="dark"] .star-rating i.active,
    [data-theme="dark"] .star-rating i.bi-star-fill {
        color: #00EAFF !important;
        transform: scale(1.1);
        filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.6)) drop-shadow(0 0 16px rgba(0, 234, 255, 0.3));
    }

    /* History Cards */
    .history-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    [data-theme="dark"] .history-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .history-card h6 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .history-card p {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .history-card small {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    .history-stars {
        color: #ffd700;
        font-size: 1.2rem;
    }

    @media (max-width: 992px) {
        .feedback-section {
            padding: 3.5rem 2rem;
        }
    }

    @media (max-width: 768px) {
        .feedback-section {
            padding: 3rem 1rem;
        }

        .feedback-title {
            font-size: 1.6rem;
        }

        .title-badge {
            font-size: 0.85rem;
            padding: 0.4rem 1rem;
        }

        .feedback-description {
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .feedback-badge-wrapper {
            margin-bottom: 1.5rem;
        }

        .feedback-cards {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .feedback-action-card {
            padding: 1.5rem;
        }

        .feedback-icon {
            width: 70px;
            height: 70px;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .feedback-action-card h3 {
            font-size: 1.2rem;
        }

        .feedback-btn {
            padding: 0.65rem 1.5rem;
            font-size: 0.9rem;
        }

        .pending-count {
            width: 32px;
            height: 32px;
            font-size: 0.85rem;
        }

        .star-rating {
            font-size: 2rem;
        }
    }

    @media (max-width: 576px) {
        .feedback-section {
            padding: 2rem 1rem;
        }

        .feedback-title {
            font-size: 1.4rem;
        }

        .title-badge {
            font-size: 0.8rem;
        }

        .feedback-badge-wrapper {
            margin-bottom: 1.25rem;
        }

        .feedback-action-card {
            padding: 1.25rem;
        }

        .feedback-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .feedback-action-card h3 {
            font-size: 1.1rem;
        }

        .feedback-action-card p {
            font-size: 0.9rem;
        }

        .star-rating {
            font-size: 1.8rem;
            gap: 0.3rem;
        }

        .modal-dialog {
            margin: 1rem;
        }

        .history-card {
            padding: 1rem;
        }

        .history-stars {
            font-size: 1rem;
        }
    }
</style>

<script>
let feedbackModal, historyModal, successModal, errorModal;
let completedAppointments = [];

document.addEventListener('DOMContentLoaded', function() {
    feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
    historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
    successModal = new bootstrap.Modal(document.getElementById('successModal'));
    errorModal = new bootstrap.Modal(document.getElementById('errorModal'));

    loadPendingFeedbackCount();
});

function openFeedbackModal() {
    fetch('/patient/feedback/appointments')
        .then(response => response.json())
        .then(data => {
            completedAppointments = data;
            const select = document.getElementById('appointmentSelect');

            if (data.length === 0) {
                document.getElementById('feedbackForm').style.display = 'none';
                document.getElementById('noAppointmentsMessage').style.display = 'block';
            } else {
                document.getElementById('feedbackForm').style.display = 'block';
                document.getElementById('noAppointmentsMessage').style.display = 'none';

                select.innerHTML = '<option value="">Select an appointment</option>';
                data.forEach(apt => {
                    select.innerHTML += `<option value="${apt.id}">${apt.service_name} - ${apt.date} at ${apt.time}</option>`;
                });
            }

            resetForm();
            feedbackModal.show();
        })
        .catch(error => {
            console.error('Error loading appointments:', error);
            showErrorModal('Failed to load appointments');
        });
}

function openHistoryModal() {
    historyModal.show();
    const historyDiv = document.getElementById('feedbackHistory');
    historyDiv.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';

    fetch('/patient/feedback/history')
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                historyDiv.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No feedback history yet</p>
                    </div>
                `;
            } else {
                historyDiv.innerHTML = '';
                data.forEach(feedback => {
                    const stars = '★'.repeat(feedback.rating) + '☆'.repeat(5 - feedback.rating);
                    historyDiv.innerHTML += `
                        <div class="history-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">${feedback.service_name}</h6>
                                    <small class="text-muted">Appointment: ${feedback.appointment_date}</small>
                                </div>
                                <div class="history-stars">${stars}</div>
                            </div>
                            ${feedback.comment ? `<p class="text-muted mb-0 mt-2">"${feedback.comment}"</p>` : ''}
                            <small class="text-muted d-block mt-2">Submitted: ${feedback.submitted_at}</small>
                        </div>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading history:', error);
            historyDiv.innerHTML = '<div class="alert alert-danger">Failed to load feedback history</div>';
        });
}

// Star rating functionality
document.getElementById('starRating').addEventListener('click', function(e) {
    if (e.target.classList.contains('bi-star') || e.target.classList.contains('bi-star-fill')) {
        const rating = parseInt(e.target.dataset.rating);
        document.getElementById('ratingValue').value = rating;

        const stars = document.querySelectorAll('#starRating i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill', 'active');
            } else {
                star.classList.remove('bi-star-fill', 'active');
                star.classList.add('bi-star');
            }
        });
    }
});

function submitFeedback() {
    const appointmentId = document.getElementById('appointmentSelect').value;
    const rating = document.getElementById('ratingValue').value;
    const comment = document.getElementById('feedbackComment').value;

    if (!appointmentId) {
        showErrorModal('Please select an appointment');
        return;
    }

    if (rating === '0') {
        showErrorModal('Please select a rating');
        return;
    }

    fetch('/patient/feedback/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            appointment_id: appointmentId,
            rating: parseInt(rating),
            feedback_comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            feedbackModal.hide();
            showSuccessModal(data.message);
            loadPendingFeedbackCount();
        } else {
            showErrorModal(data.message || 'Failed to submit feedback');
        }
    })
    .catch(error => {
        console.error('Error submitting feedback:', error);
        showErrorModal('Failed to submit feedback');
    });
}

function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    successModal.show();
}

function showErrorModal(message) {
    document.getElementById('errorMessage').textContent = message;
    errorModal.show();
}

function resetForm() {
    document.getElementById('appointmentSelect').value = '';
    document.getElementById('ratingValue').value = '0';
    document.getElementById('feedbackComment').value = '';

    const stars = document.querySelectorAll('#starRating i');
    stars.forEach(star => {
        star.classList.remove('bi-star-fill', 'active');
        star.classList.add('bi-star');
    });
}

function loadPendingFeedbackCount() {
    fetch('/patient/feedback/appointments')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Pending feedback appointments:', data);
            const count = data.length;
            const badge = document.getElementById('pendingFeedbackCount');
            const titleBadge = document.getElementById('titleFeedbackBadge');

            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                    console.log(`Showing badge with count: ${count}`);
                } else {
                    badge.style.display = 'none';
                    console.log('No pending feedback - hiding badge');
                }
            } else {
                console.error('Badge element not found');
            }

            // Show/hide title badge
            if (titleBadge) {
                if (count > 0) {
                    titleBadge.style.display = 'inline-flex';
                } else {
                    titleBadge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error loading pending feedback count:', error);
        });
}
</script>

<!-- Chatbot Styles -->
<style>
    /* Chatbot - Messenger Style Bubble */
    .chatbot-toggle-btn {
        position: fixed !important;
        right: 24px;
        bottom: 24px;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        display: flex !important;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(33, 150, 243, 0.4), 0 0 0 0 rgba(33, 150, 243, 0.7);
        cursor: pointer;
        z-index: 1000 !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, opacity 0.2s ease;
        padding: 0;
        border: 3px solid rgba(255, 255, 255, 0.3);
        user-select: none;
        touch-action: none;
        animation: messengerBubblePulse 2s ease-in-out infinite;
        opacity: 1 !important;
        visibility: visible !important;
    }

    @keyframes messengerBubblePulse {
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
        animation: messengerBadgePulse 1.5s ease-in-out infinite;
    }

    @keyframes messengerBadgePulse {
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
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

    /* Style for bullet lists in bot messages */
    .message.bot .bullet-item {
        display: block;
        padding-left: 1.2em;
        text-indent: -1.2em;
        margin: 0.3em 0;
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
        background: #e3f2fd;
        color: #1976D2;
        border: 1px solid #bbdefb;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .chip:hover { 
        background: #d2e9fb; 
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(33, 150, 243, 0.15);
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
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
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
        }
        
        .chatbot-toggle-btn:not(.dragged) {
            right: 16px !important;
            bottom: 16px !important;
            width: 56px;
            height: 56px;
        }
        
        .chatbot-toggle-btn.dragged {
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
            right: 12px !important;
            left: 12px !important;
            bottom: 80px !important;
            max-width: calc(100vw - 24px);
            border-radius: 12px;
        }
        
        .chatbot-toggle-btn:not(.dragged) {
            right: 12px !important;
            bottom: 12px !important;
            width: 52px;
            height: 52px;
            opacity: 0.9;
        }
        
        .chatbot-toggle-btn.dragged {
            width: 52px;
            height: 52px;
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
        
        .chatbot-header {
            padding: 8px 10px;
        }
        
        .chatbot-body {
            padding: 6px;
        }
        
        .chatbot-input {
            padding: 6px 8px;
        }
        
        .send-btn {
            width: 32px;
            height: 32px;
            padding: 6px;
        }
    }

    /* Landscape orientation for mobile */
    @media (max-width: 768px) and (orientation: landscape) {
        .chatbot-widget {
            max-height: calc(100vh - 100px);
            max-height: calc(100dvh - 100px); /* Dynamic viewport height */
        }
        
        .chatbot-messages {
            height: 200px;
            max-height: calc(100vh - 280px);
            max-height: calc(100dvh - 280px); /* Dynamic viewport height */
        }
    }

    @media (max-width: 480px) and (orientation: landscape) {
        .chatbot-widget {
            bottom: 60px !important;
            max-height: calc(100vh - 80px);
            max-height: calc(100dvh - 80px); /* Dynamic viewport height */
        }
        
        .chatbot-messages {
            height: 180px;
            max-height: calc(100vh - 260px);
            max-height: calc(100dvh - 260px); /* Dynamic viewport height */
        }
        
        .chatbot-toggle-btn {
            bottom: 8px !important;
        }
    }

    /* Fix for mobile browsers with address bar */
    @media (max-width: 768px) {
        .chatbot-widget {
            max-height: calc(100vh - 100px);
            max-height: calc(100dvh - 100px); /* Dynamic viewport height */
        }
        
        .chatbot-messages {
            max-height: calc(100vh - 300px);
            max-height: calc(100dvh - 300px); /* Dynamic viewport height */
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
            font-size: 16px !important; /* Prevents zoom on iOS */
        }
    }

    @media (max-width: 480px) {
        .chatbot-input input[type="text"] {
            font-size: 16px !important; /* Prevents zoom on iOS */
        }
    }

    /* Dark Mode for Chatbot */
    [data-theme="dark"] .chatbot-widget {
        background: var(--dm-card-bg, #1e293b) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .chatbot-messages {
        border-bottom-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .message.bot {
        background: #334155 !important;
        color: #f1f5f9 !important;
        border: none !important;
    }

    [data-theme="dark"] .message.bot::before {
        border-right-color: #334155 !important;
    }

    [data-theme="dark"] .message.user {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
        color: #fff !important;
    }

    [data-theme="dark"] .message.user::after {
        border-left-color: #2196F3 !important;
    }

    [data-theme="dark"] .chip {
        background: #334155 !important;
        color: #e2e8f0 !important;
        border-color: #475569 !important;
    }

    [data-theme="dark"] .chip:hover {
        background: #475569 !important;
        border-color: #60a5fa !important;
        color: #f1f5f9 !important;
    }

    [data-theme="dark"] .chatbot-input input[type="text"] {
        background: var(--dm-bg-secondary, #0f172a) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .chatbot-input input[type="text"]::placeholder {
        color: var(--dm-text-muted, #64748b) !important;
    }

    [data-theme="dark"] .chatbot-input input[type="text"]:focus {
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2) !important;
    }

    [data-theme="dark"] .typing-indicator {
        background: var(--dm-bg-secondary, #0f172a) !important;
    }

    [data-theme="dark"] .typing-indicator span {
        background: #60a5fa !important;
    }


    /* Dark Mode for Modals */
    [data-theme="dark"] .modal-content {
        background: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .modal-header {
        border-bottom-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .form-label {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .form-select,
    [data-theme="dark"] .form-control {
        background: var(--dm-bg-secondary, #0f172a) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .form-select:focus,
    [data-theme="dark"] .form-control:focus {
        background: var(--dm-bg-secondary, #0f172a) !important;
        border-color: #3b82f6 !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }

    [data-theme="dark"] .form-control::placeholder {
        color: var(--dm-text-muted, #64748b) !important;
    }

    [data-theme="dark"] .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    [data-theme="dark"] .no-appointments-message i {
        opacity: 0.3 !important;
        color: var(--dm-text-muted, #64748b) !important;
    }

    [data-theme="dark"] .no-appointments-message .text-muted {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] #feedbackHistory .text-center .text-muted {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] #feedbackHistory .text-center i {
        opacity: 0.3 !important;
        color: var(--dm-text-muted, #64748b) !important;
    }

    /* ========================================
       SCROLL REVEAL ANIMATIONS
       ======================================== */
    /* Prevent overflow from reveal animations */
    html {
        overflow-x: hidden !important;
        overflow-y: hidden;
        width: 100%;
        max-width: 100vw;
        box-sizing: border-box;
        height: 100%;
    }
    
    body {
        overflow-x: hidden !important;
        overflow-y: auto;
        width: 100%;
        max-width: 100vw;
        box-sizing: border-box;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        height: 100%;
    }
    
    /* Hide scrollbar but keep scroll functionality */
    body::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }
    
    body {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }
    
    * {
        box-sizing: border-box;
    }
    
    .main-wrapper {
        overflow-x: hidden;
        overflow-y: visible;
        width: 100%;
        max-width: 100vw;
        box-sizing: border-box;
    }
    
    /* Remove reveal animations - elements visible immediately */
    .reveal-element {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
        max-width: 100%;
    }
</style>

@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
@include('patient.components.chatbot')
@endif

@endsection
