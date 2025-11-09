@extends('layout.patient.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/patient-calendar.css') }}">

<style>
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

    /* Main Content */
    .announcement-page {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        min-height: calc(100vh - 70px);
        padding: 1rem 0;
    }

    .announcement-content-wrapper {
        max-width: 100%;
        margin: 0 auto;
        padding: 0 clamp(1rem, 3vw, 2rem);
        width: 100%;
        box-sizing: border-box;
    }

    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: 1rem;
        animation: fadeInDown 0.6s ease-out;
    }

    .page-header h1 {
        font-size: clamp(1.5rem, 3vw, 1.875rem);
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-blue), var(--accent-teal));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.25rem;
    }

    .page-header p {
        color: var(--text-medium);
        font-size: clamp(0.8rem, 1.5vw, 0.9rem);
    }

    /* Main Announcement Card */
    .announcement-card {
        background: var(--bg-white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        margin-bottom: 2rem;
        border: 1px solid rgba(33, 150, 243, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        animation: fadeInUp 0.6s ease-out;
    }

    .announcement-card:hover {
        box-shadow: var(--shadow-xl);
        transform: translateY(-4px);
    }

    .announcement-banner {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 50%, #0d47a1 100%);
        padding: clamp(1rem, 3vw, 1.5rem) clamp(1rem, 4vw, 2rem);
        text-align: center;
        position: relative;
        overflow: hidden;
        min-height: clamp(70px, 15vw, 80px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .announcement-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    .announcement-banner::after {
        content: '📢';
        position: absolute;
        font-size: 8rem;
        opacity: 0.1;
        right: -2rem;
        top: 50%;
        transform: translateY(-50%) rotate(15deg);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(180deg); }
    }

    .banner-title {
        color: white;
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0;
        position: relative;
        text-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
        line-height: 1.3;
        padding: 0 0.5rem;
    }

    .announcement-content-section {
        padding: clamp(1rem, 3vw, 1.5rem) clamp(1rem, 4vw, 2rem);
        position: relative;
    }

    .announcement-image-wrapper {
        margin-bottom: clamp(1rem, 2.5vw, 1.25rem);
        text-align: center;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        max-width: 100%;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        cursor: pointer;
        background: #f8f9fa;
        aspect-ratio: 3840 / 2000;
        width: 100%;
        display: block;
    }

    .announcement-image-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0);
        transition: background 0.3s ease;
        z-index: 1;
        border-radius: 16px;
    }

    .announcement-image-wrapper:hover::before {
        background: rgba(0, 0, 0, 0.2);
    }


    .announcement-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
        transition: transform 0.4s ease, filter 0.3s ease;
        display: block;
        aspect-ratio: 3840 / 2000;
    }

    .announcement-image-wrapper:hover img {
        transform: scale(1.03);
        filter: brightness(0.95);
    }

    .announcement-subheading {
        margin-bottom: 1rem !important;
    }

    .announcement-subheading p {
        font-size: clamp(0.9rem, 2.2vw, 1.1rem) !important;
        line-height: 1.4 !important;
    }

    .announcement-message {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        border-left: 4px solid var(--primary-blue);
        padding: clamp(0.875rem, 2.5vw, 1.25rem) clamp(1rem, 3vw, 1.5rem);
        border-radius: 10px;
        color: var(--text-dark);
        line-height: 1.6;
        font-size: clamp(0.85rem, 2vw, 0.95rem);
        box-shadow: var(--shadow-sm);
        position: relative;
        margin-top: 0;
        margin-bottom: 1rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }


    .new-badge {
        display: inline-flex;
        align-items: center;
        gap: clamp(0.25rem, 0.75vw, 0.375rem);
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: clamp(0.3rem, 0.8vw, 0.4rem) clamp(0.65rem, 2vw, 1rem);
        border-radius: 50px;
        font-weight: 700;
        font-size: clamp(0.65rem, 1.6vw, 0.8rem);
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        position: absolute;
        top: clamp(0.5rem, 2vw, 1rem);
        left: clamp(0.5rem, 3vw, 1.5rem);
        z-index: 10;
        animation: pulse-badge 2s ease-in-out infinite;
        margin-bottom: 0.5rem;
        white-space: nowrap;
    }

    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .announcement-details {
        display: flex;
        gap: clamp(0.75rem, 2vw, 1rem);
        flex-wrap: wrap;
        margin-top: clamp(1rem, 2.5vw, 1.25rem);
        padding-top: clamp(1rem, 2.5vw, 1.25rem);
        border-top: 2px solid #f0f0f0;
    }

    [data-theme="dark"] .announcement-details {
        border-top-color: var(--dm-border-color, #334155) !important;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: clamp(0.375rem, 1vw, 0.5rem);
        color: var(--text-medium);
        font-weight: 600;
        font-size: clamp(0.75rem, 1.75vw, 0.875rem);
        padding: clamp(0.4rem, 1vw, 0.5rem) clamp(0.75rem, 2vw, 1rem);
        background: var(--bg-light);
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        background: white;
        box-shadow: var(--shadow-sm);
        transform: translateX(4px);
    }

    .detail-item i {
        color: var(--primary-blue);
        font-size: 1.1rem;
    }

    /* Event Badge */
    .event-badge {
        background: linear-gradient(135deg, var(--accent-teal) 0%, var(--accent-teal-dark) 100%);
        color: white;
        padding: 0.4rem 1.25rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: clamp(0.75rem, 1.25vw, 0.8rem);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 4px 12px rgba(38, 166, 154, 0.3);
        letter-spacing: 0.5px;
    }

    /* Archive Section */
    .archive-section-header {
        text-align: center;
        margin: 1.5rem 0 1rem 0;
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }

    .archive-section-header h2 {
        font-size: clamp(1.25rem, 2.5vw, 1.5rem);
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.375rem;
        position: relative;
        display: inline-block;
    }

    .archive-section-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--accent-teal));
        border-radius: 2px;
    }

    .archive-section-header p {
        color: var(--text-medium);
        font-size: clamp(0.8rem, 1.5vw, 0.9rem);
        margin-top: 0.5rem;
    }

    .archive-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    /* Archive Cards */
    .archive-card {
        background: var(--bg-white);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-top: 4px solid;
        animation: fadeInUp 0.6s ease-out;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 400px;
    }

    .archive-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, transparent 0%, rgba(33, 150, 243, 0.03) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .archive-card:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: var(--shadow-xl);
    }

    .archive-card:hover::before {
        opacity: 1;
    }

    .archive-image {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 0;
        margin-bottom: 0;
        flex-shrink: 0;
        cursor: pointer;
        transition: transform 0.3s ease;
        display: block;
    }

    .archive-card:hover .archive-image {
        transform: scale(1.03);
    }


    .archive-card-content {
        padding: 0.625rem 0.75rem;
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
    }

    .archive-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .archive-date {
        color: var(--text-light);
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 0;
        text-transform: none;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .archive-date i {
        font-size: 0.75rem;
    }

    .archive-title {
        font-size: clamp(0.85rem, 1.5vw, 0.9rem);
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.375rem;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .archive-subheading {
        font-size: clamp(0.75rem, 1.25vw, 0.8rem);
        font-weight: 600;
        color: var(--accent-teal);
        margin-bottom: 0.375rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .archive-content {
        color: var(--text-medium);
        font-size: clamp(0.7rem, 1.25vw, 0.75rem);
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
        min-height: 0;
    }

    .archive-meta {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        font-size: clamp(0.65rem, 1.25vw, 0.7rem);
        margin-top: auto;
        padding-top: 0.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .archive-meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: var(--text-medium);
    }

    .archive-meta-item i {
        font-size: 0.75rem;
        color: var(--primary-blue);
        flex-shrink: 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1.5rem;
        background: var(--bg-white);
        border-radius: 12px;
        box-shadow: var(--shadow-md);
    }

    .empty-state i {
        font-size: clamp(3rem, 6vw, 3.5rem);
        color: var(--primary-light);
        margin-bottom: 0.75rem;
        opacity: 0.8;
    }

    .empty-state h3 {
        color: var(--text-dark);
        font-size: clamp(1rem, 2vw, 1.15rem);
        font-weight: 700;
        margin-bottom: 0.375rem;
    }

    .empty-state p {
        color: var(--text-medium);
        font-size: clamp(0.85rem, 1.5vw, 0.9rem);
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============================================
       DARK MODE STYLES FOR ANNOUNCEMENT PAGE
       ============================================ */

    /* Main Page Dark Mode */
    [data-theme="dark"] .announcement-page {
        background: linear-gradient(135deg, var(--dm-bg-primary, #0f172a) 0%, var(--dm-bg-secondary, #1e293b) 100%) !important;
    }

    /* Page Header Dark Mode */
    [data-theme="dark"] .page-header h1 {
        background: linear-gradient(135deg, #60a5fa, #14b8a6) !important;
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent !important;
        background-clip: text !important;
    }

    [data-theme="dark"] .page-header p {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    /* Announcement Card Dark Mode */
    [data-theme="dark"] .announcement-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .announcement-card:hover {
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.5) !important;
    }

    /* Banner remains the same (blue gradient) - already has good contrast */

    /* Content Section Dark Mode */
    [data-theme="dark"] .announcement-content-section {
        background: transparent !important;
    }

    /* Announcement Message Dark Mode */
    [data-theme="dark"] .announcement-message {
        background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
        border-left-color: #3b82f6 !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* Detail Items Dark Mode */
    [data-theme="dark"] .detail-item {
        background: var(--dm-bg-primary, #0f172a) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .detail-item:hover {
        background: var(--dm-bg-tertiary, #334155) !important;
        border-color: #3b82f6 !important;
    }

    [data-theme="dark"] .detail-item i {
        color: #60a5fa !important;
    }

    [data-theme="dark"] .detail-item span {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* Archive Section Header Dark Mode */
    [data-theme="dark"] .archive-section-header h2 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .archive-section-header p {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    /* Archive Cards Dark Mode */
    [data-theme="dark"] .archive-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .archive-card:hover {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .archive-card::before {
        background: linear-gradient(135deg, transparent 0%, rgba(59, 130, 246, 0.1) 100%) !important;
    }

    [data-theme="dark"] .archive-date {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .archive-date i {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .archive-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .archive-content {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .archive-subheading {
        color: #14b8a6 !important;
    }

    [data-theme="dark"] .archive-meta {
        border-top-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .archive-meta-item {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .archive-meta-item i {
        color: #60a5fa !important;
    }

    @media (max-width: 1400px) {
        .archive-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }
    }

    @media (max-width: 1200px) {
        .archive-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.625rem;
        }
    }

    @media (max-width: 992px) {
        .archive-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    /* Empty State Dark Mode */
    [data-theme="dark"] .empty-state {
        background: var(--dm-card-bg, #1e293b) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .empty-state i {
        color: #60a5fa !important;
        opacity: 0.6 !important;
    }

    [data-theme="dark"] .empty-state h3 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .empty-state p {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    /* Image Wrapper Dark Mode */
    [data-theme="dark"] .announcement-image-wrapper {
        background: var(--dm-bg-primary, #0f172a) !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .announcement-image-wrapper:hover::before {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    /* Event Message with Teal Border Dark Mode */
    [data-theme="dark"] .announcement-message[style*="border-left-color: var(--accent-teal)"] {
        border-left-color: #14b8a6 !important;
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.1) 0%, var(--dm-bg-secondary, #1e293b) 100%) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* Event Detail Items with Teal Icons Dark Mode */
    [data-theme="dark"] .detail-item i[style*="color: var(--accent-teal)"] {
        color: #14b8a6 !important;
    }

    /* Badges remain the same (they have good contrast) */

    /* Responsive */
    @media (max-width: 768px) {
        .announcement-page {
            padding: 1.5rem 0;
        }

        .announcement-content-wrapper {
            padding: 0 clamp(1rem, 3vw, 1.5rem);
            max-width: 100%;
            width: 100%;
        }

        .page-header {
            margin-bottom: 1.25rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
        }

        .banner-title {
            font-size: 1.35rem;
            padding: 0.25rem;
        }

        .announcement-banner {
            padding: clamp(1rem, 2.5vw, 1.25rem) clamp(0.875rem, 2.5vw, 1rem);
            min-height: clamp(70px, 14vw, 75px);
            padding-top: clamp(2.75rem, 6vw, 3rem);
        }

        .announcement-banner::after {
            font-size: 4rem;
            right: -1rem;
        }

        .announcement-content-section {
            padding: 1.25rem 1rem;
        }

        .announcement-image-wrapper {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
        }

        .announcement-image-wrapper img {
            height: auto;
            width: 100%;
            display: block;
        }

        .announcement-subheading {
            margin-bottom: 0.75rem !important;
        }

        .announcement-subheading p {
            font-size: 0.95rem !important;
            line-height: 1.5 !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .announcement-message {
            padding: 1rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }


        .new-badge {
            padding: 0.35rem 0.75rem;
            font-size: 0.7rem;
            top: 0.75rem;
            left: 0.75rem;
            right: auto;
            max-width: calc(100% - 1.5rem);
            white-space: nowrap;
        }

        .announcement-details {
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            flex-direction: column;
        }

        .detail-item {
            font-size: 0.8rem;
            padding: 0.45rem 0.875rem;
            width: 100%;
            justify-content: flex-start;
        }

        .detail-item i {
            font-size: 1rem;
        }

        .archive-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .archive-card {
            min-height: 480px;
        }

        .archive-image {
            height: 120px;
        }

        .archive-section-header {
            margin: 2rem 0 1.5rem 0;
        }

        .archive-section-header h2 {
            font-size: 1.5rem;
        }

        .new-badge {
            font-size: 0.75rem;
            padding: 0.4rem 1rem;
        }

        .event-badge {
            font-size: 0.8rem;
            padding: 0.5rem 1.25rem;
        }
    }
</style>

<main class="announcement-page">
    <div class="announcement-content-wrapper">

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="bi bi-megaphone-fill me-2"></i>Announcements & Events</h1>
            <p>Stay updated with our latest news and upcoming activities</p>
        </div>

        @if($announcement)
            <div class="announcement-card reveal-element reveal-slide-up">
                <div class="announcement-banner">
                    @if($announcement->created_at->diffInDays(now()) < 7)
                        <span class="new-badge">
                            <i class="bi bi-star-fill"></i>
                            NEW
                        </span>
                    @endif
                    <h2 class="banner-title">{{ $announcement->title }}</h2>
                </div>

                <div class="announcement-content-section">

                    @if($announcement->image_path)
                        <div class="announcement-image-wrapper" onclick="openImageModal('{{ asset('storage/' . $announcement->image_path) }}', '{{ $announcement->title }}')">
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}">
                        </div>
                    @endif

                    @if($announcement->subheading)
                        <div class="announcement-subheading">
                            <p class="mb-0" style="font-weight: 600; color: var(--accent-teal);">
                                {{ $announcement->subheading }}
                            </p>
                        </div>
                    @endif

                    <div class="announcement-message">
                        {{ $announcement->content }}
                    </div>

                    <div class="announcement-details">
                        @if($announcement->date_start)
                            <div class="detail-item">
                                <i class="bi bi-calendar-event"></i>
                                <span>{{ $announcement->formatted_date_range }}</span>
                            </div>
                        @endif

                        @if($announcement->time_start || $announcement->is_whole_day)
                            <div class="detail-item">
                                <i class="bi bi-clock-fill"></i>
                                <span>{{ $announcement->formatted_time_range }}</span>
                            </div>
                        @endif

                        <div class="detail-item">
                            <i class="bi bi-calendar-check"></i>
                            <span>Posted {{ $announcement->created_at->format('F d, Y') }}</span>
                        </div>
                        @if($announcement->updated_at != $announcement->created_at)
                            <div class="detail-item">
                                <i class="bi bi-clock-history"></i>
                                <span>Updated {{ $announcement->updated_at->format('F d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-megaphone"></i>
                <h3>No Announcements Yet</h3>
                <p>There are currently no announcements to display. Please check back later for updates.</p>
            </div>
        @endif

        <!-- Upcoming Events -->
        @forelse($upcomingEvents as $event)
            <div class="announcement-card reveal-element reveal-fade">
                <div class="announcement-content-section" style="padding-top: 0.875rem; padding-bottom: 0;">
                    <span class="event-badge">
                        <i class="bi bi-calendar-check"></i>
                        UPCOMING EVENT
                    </span>
                </div>

                @if($event->image_path)
                    <div class="announcement-image-wrapper" style="margin: 0.75rem auto 0 auto;" onclick="openImageModal('{{ asset('storage/' . $event->image_path) }}', '{{ $event->title }}')">
                        <img src="{{ asset('storage/' . $event->image_path) }}?v={{ time() }}"
                             alt="{{ $event->title }}">
                    </div>
                @endif

                <div class="announcement-banner" style="background: linear-gradient(135deg, #26a69a 0%, #00897b 50%, #00695c 100%); margin-top: 0.75rem;">
                    <h2 class="banner-title">{{ $event->title }}</h2>
                </div>

                <div class="announcement-content-section">
                    <div class="announcement-message" style="border-left-color: var(--accent-teal); background: linear-gradient(135deg, #f0f9f8 0%, #e0f2f1 100%);">
                        {{ $event->description }}
                    </div>

                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="bi bi-calendar-event" style="color: var(--accent-teal);"></i>
                            <span>{{ $event->formatted_date }}</span>
                        </div>
                        @if($event->event_time)
                            <div class="detail-item">
                                <i class="bi bi-clock-fill" style="color: var(--accent-teal);"></i>
                                <span>{{ $event->formatted_time }}</span>
                            </div>
                        @endif
                        @if($event->location)
                            <div class="detail-item">
                                <i class="bi bi-geo-alt-fill" style="color: var(--accent-teal);"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <!-- Show nothing if no upcoming events -->
        @endforelse

        <!-- Announcement Archive -->
        @if($archivedAnnouncements->count() > 0)
            <div class="archive-section-header reveal-element reveal-slide-up">
                <h2>
                    <i class="bi bi-archive me-2"></i>Announcement Archive
                </h2>
                <p>Explore our previous announcements and clinic updates</p>
            </div>

            <div class="archive-grid">
                @php
                    $colors = ['#26a69a', '#2196F3', '#f59e0b', '#8b5cf6', '#ec4899'];
                    $colorIndex = 0;
                @endphp
                @foreach($archivedAnnouncements->take(5) as $archive)
                    <div class="archive-card reveal-element reveal-fade" style="border-top-color: {{ $colors[$colorIndex % 5] }};">
                        @if($archive->image_path)
                            <img src="{{ asset('storage/' . $archive->image_path) }}" 
                                 alt="{{ $archive->title }}" 
                                 class="archive-image"
                                 onclick="openImageModal('{{ asset('storage/' . $archive->image_path) }}', '{{ $archive->title }}')">
                        @else
                            <div class="archive-image" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image" style="font-size: 2rem; color: #90caf9; opacity: 0.5;"></i>
                            </div>
                        @endif
                        <div class="archive-card-content">
                            <div class="archive-card-header">
                                <div class="archive-date">
                                    <i class="bi bi-calendar3"></i>
                                    @if($archive->date_start && $archive->formatted_date_range)
                                        {{ strtoupper($archive->formatted_date_range) }}
                                    @else
                                        {{ strtoupper($archive->archived_at->format('F j, Y')) }}
                                    @endif
                                </div>
                            </div>
                            <h3 class="archive-title">
                                {{ $archive->title }}
                            </h3>
                            @if($archive->subheading)
                                <p class="archive-subheading">
                                    {{ Str::limit($archive->subheading, 60) }}
                                </p>
                            @endif
                            <p class="archive-content">
                                {{ Str::limit(strip_tags($archive->content), 120) }}
                            </p>
                            <div class="archive-meta">
                                <div class="archive-meta-item">
                                    <i class="bi bi-clock-fill"></i>
                                    <span>
                                        @if($archive->time_start && !$archive->is_whole_day)
                                            {{ $archive->formatted_time_range ?? '' }}
                                        @else
                                            Whole Day
                                        @endif
                                    </span>
                                </div>
                                <div class="archive-meta-item">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>{{ $archive->archived_at->format('F j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $colorIndex++; @endphp
                @endforeach
            </div>
        @endif
    </div>
</main>

<script>
function closeNotification() {
    const notificationBar = document.getElementById('notificationBar');
    if (notificationBar) {
    notificationBar.style.transition = 'all 0.3s ease';
    notificationBar.style.opacity = '0';
    notificationBar.style.transform = 'translateY(-100%)';
    setTimeout(() => {
        notificationBar.style.display = 'none';
    }, 300);
}
}

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

// ========================================
// SCROLL REVEAL FUNCTIONALITY - DISABLED
// ========================================
// Reveal animations removed - all elements visible immediately
(function() {
    document.querySelectorAll('.reveal-element').forEach(el => {
        el.classList.add('revealed');
        el.style.opacity = '1';
        el.style.transform = 'none';
    });
})();
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

/* ========================================
   SCROLL REVEAL ANIMATIONS - REMOVED
   ======================================== */
/* Prevent overflow */
html, body {
    overflow-x: hidden;
    width: 100%;
}

.announcement-page {
    overflow-x: hidden;
    width: 100%;
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
