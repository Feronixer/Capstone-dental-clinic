@extends('layout.patient.app')
@section('content')

<style>
    .main-wrapper {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        min-height: 80vh;
        padding: 0;
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
        justify-content: space-between;
        align-items: center;
        padding: 3rem 3rem;
        gap: 3rem;
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
        margin-bottom: 2rem;
    }

    /* Hero Card */
    .hero-card {
        flex: 1;
        max-width: 550px;
        position: relative;
        padding: 1rem;
        min-width: 0;
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

    /* Services Section */
    .services-section {
        padding: 5rem 3rem;
        background: linear-gradient(135deg, #0a2a6b 0%, #0b3b91 100%);
        position: relative;
        color: #ffffff;
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
        padding: 0 clamp(16px, 4vw, 60px);
        max-width: 1600px;
        margin: 0 auto;
        width: 100%;
    }

    .services-carousel {
        display: flex;
        gap: clamp(1rem, 2vw, 2rem);
        overflow-x: hidden;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
        width: 100%;
    }

    .services-carousel::-webkit-scrollbar {
        display: none;
    }

    .service-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        min-width: clamp(220px, 24vw, 280px);
        max-width: clamp(220px, 24vw, 280px);
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
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
        transform: translateY(-6px) scale(1.06);
        box-shadow: 0 16px 46px rgba(0, 0, 0, 0.18);
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
        }
    }

    @media (max-width: 1024px) {
        .hero-section {
            padding: 2rem;
            margin-bottom: 1.5rem;
            gap: 2rem;
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
            font-size: 2.4rem; 
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
            gap: 1.5rem;
            align-items: flex-start;
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
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }

        .hero-title {
            font-size: 2rem;
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
            font-size: 1.8rem;
        }
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
            font-size: 1.6rem;
        }

        .hero-description {
            font-size: 0.9rem;
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
        .feature-card.bottom { bottom: 6rem !important; right: -0.5rem !important; }
        .feature-text h4 { font-size: 0.95rem; }
        .feature-text p { font-size: 0.8rem; }

        .card-title {
            font-size: 1rem;
            bottom: 1rem;
        }

        .services-title {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <!-- Left Content -->
    <div class="hero-content">
        <div class="badge">
            <i class="bi bi-stars"></i>
            Welcome Back, {{ auth()->user()->name ?? 'Patient' }}!
        </div>

        <h1 class="hero-title">
            Have confidence<br>in your <span class="highlight">SMILE</span> in<br>no time!
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
<section class="services-section">
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
    // Services Carousel
    // compute and cache step size based on first card width + gap
    let __servicesStep = null;
    function getServicesStep() {
        if (__servicesStep) return __servicesStep;
        const carousel = document.getElementById('servicesCarousel');
        const firstCard = carousel ? carousel.querySelector('.service-card') : null;
        if (!firstCard) return 270;
        const styles = window.getComputedStyle(carousel);
        const gapRaw = (styles.columnGap || styles.gap || '0').replace('px','');
        const gap = parseFloat(gapRaw || '0') || 0;
        __servicesStep = Math.round(firstCard.offsetWidth + gap);
        return __servicesStep;
    }

    function scrollServices(direction) {
        const carousel = document.getElementById('servicesCarousel');
        const scrollAmount = getServicesStep();
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

    // Continuous auto-loop on hover over the carousel itself (seamless)
    let servicesRafId = null;
    let servicesBaseWidth = null; // half of content after duplication
    function ensureServicesLoopBuffer() {
        const car = document.getElementById('servicesCarousel');
        if (!car) return null;
        if (!car.dataset.loopDoubled) {
            car.insertAdjacentHTML('beforeend', car.innerHTML);
            car.dataset.loopDoubled = '1';
        }
        servicesBaseWidth = Math.floor(car.scrollWidth / 2);
        return car;
    }

    function startServicesHoverLoop() {
        const carousel = ensureServicesLoopBuffer();
        if (!carousel || servicesRafId) return;
        const speed = 1; // pixels per frame for smooth flow
        const tick = function() {
            if (carousel.scrollLeft >= servicesBaseWidth) {
                carousel.scrollLeft -= servicesBaseWidth; // wrap seamlessly
            }
            carousel.scrollLeft += speed;
            servicesRafId = requestAnimationFrame(tick);
        };
        servicesRafId = requestAnimationFrame(tick);
    }
    function stopServicesHoverLoop() {
        if (servicesRafId) {
            cancelAnimationFrame(servicesRafId);
            servicesRafId = null;
        }
    }
    (function bindCarouselHover() {
        const car = document.getElementById('servicesCarousel');
        if (!car) return;
        car.addEventListener('mouseenter', startServicesHoverLoop);
        car.addEventListener('mouseleave', stopServicesHoverLoop);
        car.addEventListener('touchstart', startServicesHoverLoop);
        ['touchend','touchcancel','blur'].forEach(function(evt){ car.addEventListener(evt, stopServicesHoverLoop); });
    })();
</script>

<!-- Feedback Section -->
<section class="feedback-section" id="feedback">
    <div class="feedback-container">
        <h2 class="feedback-title">
            Rate Your Experience
            <span class="title-badge" id="titleFeedbackBadge" style="display: none;">
                <i class="bi bi-exclamation-circle"></i> You have pending reviews
            </span>
        </h2>
        <p class="feedback-description">
            Help us improve our services by sharing your feedback
        </p>

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
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rate Your Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="feedbackForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Appointment</label>
                        <select class="form-select" id="appointmentSelect">
                            <option value="">Loading appointments...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Rating</label>
                        <div class="star-rating" id="starRating">
                            <i class="bi bi-star" data-rating="1"></i>
                            <i class="bi bi-star" data-rating="2"></i>
                            <i class="bi bi-star" data-rating="3"></i>
                            <i class="bi bi-star" data-rating="4"></i>
                            <i class="bi bi-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" id="ratingValue" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Comments (Optional)</label>
                        <textarea class="form-control" id="feedbackComment" rows="3"
                            placeholder="Share your experience with us..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" onclick="submitFeedback()">
                            <i class="bi bi-send me-2"></i>Submit Feedback
                        </button>
                    </div>
                </div>

                    <div id="noAppointmentsMessage" style="display: none;" class="text-center py-4 no-appointments-message">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No completed appointments to rate</p>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Feedback History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }

    [data-theme="dark"] .feedback-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    .title-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        padding: 0.5rem 1.25rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
        animation: gentle-pulse 2s ease-in-out infinite;
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
        margin-bottom: 3rem;
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
    .star-rating i.active {
        color: #ffd700;
        transform: scale(1.1);
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
            margin-bottom: 2rem;
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
        .chatbot-toggle-btn { right: 16px; bottom: 16px; }
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
        background: var(--dm-bg-secondary, #0f172a) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .chip {
        background: var(--dm-bg-tertiary, #334155) !important;
        color: #93c5fd !important;
        border-color: var(--dm-border-color, #475569) !important;
    }

    [data-theme="dark"] .chip:hover {
        background: var(--dm-bg-secondary, #1e293b) !important;
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

    [data-theme="dark"] .chatbot-tabs {
        border-top-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .chatbot-tab {
        background: var(--dm-bg-secondary, #0f172a) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-muted, #64748b) !important;
    }

    [data-theme="dark"] .chatbot-tab:hover {
        background: var(--dm-bg-tertiary, #334155) !important;
        border-color: #60a5fa !important;
    }

    [data-theme="dark"] .chatbot-tab.active {
        background: #2196F3 !important;
        color: #fff !important;
        border-color: #2196F3 !important;
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
</style>

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
            <input id="chatbot-input" type="text" placeholder="Type your message to staff..." autocomplete="off" />
            <button id="chatbot-send" class="send-btn" aria-label="Send message">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
        <div class="chatbot-tabs">
            <button id="tab-live-chat" class="chatbot-tab active" data-tab="live-chat">
                <i class="bi bi-chat-dots me-1"></i> Live Chat
            </button>
            <button id="tab-faqs" class="chatbot-tab" data-tab="faqs">
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
        const titleEl = document.getElementById('chatbotTitle');
        const tabLiveChat = document.getElementById('tab-live-chat');
        const tabFaqs = document.getElementById('tab-faqs');

        let conversationId = null;
        let pollingInterval = null;
        let isAuthenticated = true; // Patient is always authenticated on dashboard
        let lastMessageId = null;
        let currentMode = 'live-chat'; // 'live-chat' or 'faqs'
        let faqInitialized = false;

        // FAQ data
        const quickIntents = {!! json_encode($chatbotSetting->quick_intents ?? []) !!};
        const faqRaw = @json($chatbotFaqs ?? []);
        const faqPairs = (faqRaw || []).map(function(f){
            return { q: (f.question || ''), a: (f.answer || '') };
        });

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function addMessage(text, sender, messageId = null) {
            // Check if message already exists
            if (messageId) {
                const existing = messagesEl.querySelector(`[data-message-id="${messageId}"]`);
                if (existing) return;
            }

            const div = document.createElement('div');
            div.className = 'message ' + (sender === 'user' || sender === 'patient' ? 'user' : 'bot');
            if (messageId) div.setAttribute('data-message-id', messageId);

            if (sender === 'bot' || sender === 'staff' || sender === 'admin') {
                let lines = text.split('\n');
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
                        if (i < lines.length - 1) formattedHTML += '<br>';
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
            if (indicator) indicator.remove();
        }

        async function loadConversation() {
            try {
                const response = await fetch('{{ route("patient-chat.conversation") }}');
                const data = await response.json();
                conversationId = data.conversation_id;
                titleEl.textContent = 'Live Chat - Staff';
                await loadMessages();
                startPolling();
            } catch (error) {
                console.error('Error loading conversation:', error);
            }
        }

        async function loadMessages() {
            if (!conversationId) return;
            try {
                const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                const data = await response.json();
                
                messagesEl.innerHTML = '';
                data.messages.forEach(msg => {
                    const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                    addMessage(msg.message, sender, msg.id);
                    if (!lastMessageId || msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }
                });
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        // FAQ Bot Functions
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
                        sendMessage(intent.value);
                    }
                });
                chipsEl.appendChild(btn);
            });
        }

        async function sendMessage(text) {
            if (currentMode === 'faqs') {
                sendFaqMessage(text);
                return;
            }

            if (!text.trim() || !conversationId) return;

            const messageText = text.trim();
            addMessage(messageText, 'user');
            inputEl.value = '';
            inputEl.disabled = true;
            sendBtn.disabled = true;

            try {
                const response = await fetch('{{ route("patient-chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        conversation_id: conversationId,
                        message: messageText
                    })
                });

                const data = await response.json();
                if (data.success) {
                    lastMessageId = data.message.id;
                }
            } catch (error) {
                console.error('Error sending message:', error);
                addMessage('Sorry, there was an error sending your message. Please try again.', 'bot');
            } finally {
                inputEl.disabled = false;
                sendBtn.disabled = false;
                inputEl.focus();
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
                            addMessage(msg.message, sender, msg.id);
                            lastMessageId = msg.id;
                        }
                    });
                } catch (error) {
                    console.error('Error polling messages:', error);
                }
            }, 3000); // Poll every 3 seconds
        }

        function stopPolling() {
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
                chipsEl.style.display = 'none'; // Hide chips
                if (!conversationId) {
                    loadConversation();
                }
                startPolling();
            } else {
                titleEl.textContent = 'ToothTalk Assistant';
                inputEl.placeholder = 'Ask about services, hours, pricing...';
                inputContainer.style.display = 'none'; // Hide input
                chipsEl.style.display = 'flex'; // Show FAQ chips
                chipsEl.innerHTML = ''; // Clear any previous buttons
                stopPolling();
                if (!faqInitialized || messagesEl.innerHTML === '') {
                    messagesEl.innerHTML = '';
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                        renderChips();
                        faqInitialized = true;
                    }, 800);
                } else {
                    // If FAQ already initialized, just ensure chips are rendered
                    renderChips();
                }
            }
        }

        function openChat() {
            widget.classList.add('open');
            widget.setAttribute('aria-hidden', 'false');
            
            if (currentMode === 'live-chat' && !messagesEl.dataset.initialized) {
                chipsEl.style.display = 'none'; // Hide chips in live chat
                showTypingIndicator();
                loadConversation().then(() => {
                    hideTypingIndicator();
                    messagesEl.dataset.initialized = '1';
                });
            } else if (currentMode === 'faqs' && !faqInitialized) {
                chipsEl.style.display = 'flex'; // Show chips in FAQs
                messagesEl.innerHTML = '';
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                    renderChips();
                    faqInitialized = true;
                }, 800);
            }
            inputEl.focus();
        }

        function closeChat() {
            widget.classList.remove('open');
            widget.setAttribute('aria-hidden', 'true');
            stopPolling();
        }

        toggleBtn.addEventListener('click', () => {
            if (widget.classList.contains('open')) closeChat(); else openChat();
        });
        closeBtn.addEventListener('click', closeChat);
        tabLiveChat.addEventListener('click', () => switchTab('live-chat'));
        tabFaqs.addEventListener('click', () => switchTab('faqs'));
        sendBtn.addEventListener('click', () => {
            const v = inputEl.value;
            if (v.trim()) sendMessage(v);
        });
        inputEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const v = inputEl.value;
                if (v.trim()) sendMessage(v);
            }
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            stopPolling();
        });
    })();
</script>
@endif

@endsection
