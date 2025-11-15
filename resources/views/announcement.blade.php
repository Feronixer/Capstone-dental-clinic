@extends('layout.guest.app')
@section('content')

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

    nav.navbar {
        position: relative;
        pointer-events: auto;
    }

    nav.navbar * {
        pointer-events: auto;
    }

    /* Hamburger Menu Styles - Match home.blade.php */
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

    .mobile-menu-overlay {
        pointer-events: none;
    }

    .mobile-menu-overlay.active {
        pointer-events: auto;
    }

    .mobile-menu-backdrop {
        pointer-events: none;
    }

    .mobile-menu-backdrop.active {
        pointer-events: auto;
    }

    /* Main Content */
    .announcement-page {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        min-height: calc(100vh - 70px);
        padding: clamp(0.75rem, 1.5vw, 1rem) 0;
    }

    .announcement-content-wrapper {
        max-width: 100%;
        margin: 0 auto;
        padding: 0 clamp(0.75rem, 2vw, 1.5rem);
        width: 100%;
        box-sizing: border-box;
    }

    @media (min-width: 768px) {
        .announcement-content-wrapper {
            padding: 0 clamp(1rem, 2.5vw, 1.5rem);
        }
    }

    @media (min-width: 1200px) {
        .announcement-content-wrapper {
            padding: 0 clamp(1rem, 2.5vw, 2rem);
        }
    }

    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: clamp(0.75rem, 2vw, 1rem);
        animation: fadeInDown 0.6s ease-out;
    }

    .page-header h1 {
        font-size: clamp(1.25rem, 3vw, 1.75rem);
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-blue), var(--accent-teal));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.25rem;
    }

    .page-header p {
        color: var(--text-medium);
        font-size: clamp(0.8rem, 1.8vw, 0.9rem);
        margin-bottom: 0;
    }

    /* Main Announcement Card */
    .announcement-card {
        background: var(--bg-white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-bottom: 1rem;
        margin-left: 0;
        margin-right: 0;
        border: 1px solid rgba(33, 150, 243, 0.1);
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease-out;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .announcement-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .announcement-banner {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 50%, #0d47a1 100%);
        padding: clamp(0.75rem, 2vw, 1rem) clamp(1rem, 3vw, 1.5rem);
        text-align: center;
        position: relative;
        overflow: hidden;
        min-height: clamp(50px, 10vw, 60px);
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
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        font-weight: 700;
        margin: 0;
        position: relative;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.3px;
        line-height: 1.3;
        padding: 0 0.5rem;
    }

    .announcement-content-section {
        padding: clamp(0.75rem, 2vw, 1rem) clamp(1rem, 3vw, 1.5rem);
        position: relative;
    }

    .announcement-image-wrapper {
        margin-bottom: clamp(0.75rem, 2vw, 1rem);
        text-align: center;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        cursor: pointer;
        background: #f8f9fa;
        aspect-ratio: 16 / 9;
        width: 100%;
        display: block;
        max-height: 250px;
    }
    
    @media (max-width: 768px) {
        .announcement-image-wrapper {
            max-height: 180px;
            max-width: 100%;
        }
    }
    
    @media (min-width: 1200px) {
        .announcement-image-wrapper {
            max-width: 700px;
        }
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
        border-radius: 8px;
        transition: transform 0.3s ease, filter 0.3s ease;
        display: block;
    }

    .announcement-image-wrapper:hover img {
        transform: scale(1.05);
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
        border-left: 3px solid var(--primary-blue);
        padding: clamp(0.75rem, 2vw, 1rem) clamp(0.875rem, 2.5vw, 1.25rem);
        border-radius: 8px;
        color: var(--text-dark);
        line-height: 1.6;
        font-size: clamp(0.85rem, 1.8vw, 0.95rem);
        box-shadow: var(--shadow-sm);
        position: relative;
        margin-top: 0;
        margin-bottom: 0.75rem;
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
        gap: clamp(0.5rem, 1.5vw, 0.75rem);
        flex-wrap: wrap;
        margin-top: clamp(0.75rem, 2vw, 1rem);
        padding-top: clamp(0.75rem, 2vw, 1rem);
        border-top: 1px solid #f0f0f0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: clamp(0.3rem, 0.8vw, 0.4rem);
        color: var(--text-medium);
        font-weight: 500;
        font-size: clamp(0.7rem, 1.5vw, 0.8rem);
        padding: clamp(0.35rem, 0.8vw, 0.45rem) clamp(0.65rem, 1.5vw, 0.85rem);
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
        padding: clamp(0.4rem, 1vw, 0.5rem) clamp(1rem, 3vw, 1.5rem);
        border-radius: 50px;
        font-weight: 700;
        font-size: clamp(0.7rem, 1.7vw, 0.85rem);
        display: inline-flex;
        align-items: center;
        gap: clamp(0.375rem, 1vw, 0.5rem);
        box-shadow: 0 4px 12px rgba(38, 166, 154, 0.3);
        letter-spacing: 0.5px;
    }

    /* Archive Section */
    .archive-section-header {
        text-align: center;
        margin: clamp(2rem, 5vw, 2.5rem) 0 clamp(1.5rem, 3.5vw, 1.75rem) 0;
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }

    .archive-section-header h2 {
        font-size: clamp(1.25rem, 3.75vw, 1.875rem);
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
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
        font-size: clamp(0.85rem, 2vw, 0.95rem);
        margin-top: 0.75rem;
    }

    .archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(clamp(280px, 68vw, 340px), 1fr));
        gap: clamp(1rem, 3vw, 1.5rem);
        margin-bottom: clamp(1.5rem, 4vw, 2rem);
    }

    /* Archive Cards */
    .archive-card {
        background: var(--bg-white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-top: 4px solid;
        animation: fadeInUp 0.6s ease-out;
        position: relative;
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
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-xl);
    }

    .archive-card:hover::before {
        opacity: 1;
    }

    .archive-card-content {
        padding: clamp(1rem, 2.5vw, 1.25rem) clamp(1rem, 3vw, 1.5rem);
        position: relative;
        z-index: 1;
    }

    .archive-date {
        color: var(--text-light);
        font-size: clamp(0.7rem, 1.6vw, 0.8rem);
        font-weight: 700;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: clamp(0.375rem, 1vw, 0.5rem);
    }

    .archive-date i {
        font-size: 0.9rem;
    }

    .archive-title {
        font-size: clamp(1rem, 2.4vw, 1.2rem);
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .archive-content {
        color: var(--text-medium);
        font-size: clamp(0.8rem, 1.75vw, 0.875rem);
        line-height: 1.6;
        margin: 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: var(--bg-white);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--primary-light);
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    .empty-state h3 {
        color: var(--text-dark);
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-medium);
        font-size: 0.9rem;
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

    /* Responsive */
    @media (max-width: 360px) {
        .announcement-content-wrapper {
            padding: 0 0.5rem;
        }

        .announcement-banner {
            padding-top: 2rem;
            min-height: 85px;
        }

        .banner-title {
            font-size: 1.25rem;
        }

        .new-badge {
            top: 0.4rem;
            left: 0.4rem;
            padding: 0.25rem 0.55rem;
            font-size: 0.6rem;
        }

        .announcement-content-section {
            padding: 0.875rem 0.75rem;
        }

        .announcement-card {
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .announcement-message {
            padding: 0.75rem 0.75rem;
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .announcement-subheading p {
            font-size: 0.85rem !important;
        }

        .detail-item {
            font-size: 0.7rem;
            padding: 0.35rem 0.65rem;
        }
    }

    @media (min-width: 361px) and (max-width: 480px) {
        .announcement-banner {
            padding-top: 3rem;
            min-height: 80px;
        }

        .banner-title {
            font-size: 1.3rem;
            padding: 0.375rem 0.375rem;
        }

        .new-badge {
            top: 0.5rem;
            left: 0.5rem;
            padding: 0.3rem 0.65rem;
            font-size: 0.65rem;
        }

        .announcement-content-section {
            padding: 1rem 0.75rem;
        }

        .announcement-message {
            padding: 0.875rem 0.875rem;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .announcement-subheading p {
            font-size: 0.9rem !important;
        }
    }

    @media (max-width: 768px) {
        .menu-toggle {
            display: inline-flex !important;
            margin-left: auto;
            position: relative;
            pointer-events: auto;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }
    }

    @media (min-width: 481px) and (max-width: 768px) {
        .announcement-page {
            padding: 1.5rem 0;
        }

        .announcement-content-wrapper {
            padding: 0 clamp(0.5rem, 1.5vw, 0.75rem);
            max-width: 100%;
            width: 100%;
        }

        .announcement-card {
            margin-left: 0;
            margin-right: 0;
            border-radius: 12px;
        }

        .page-header {
            margin-bottom: 1.25rem;
        }

        .page-header h1 {
            font-size: clamp(1.25rem, 3.5vw, 1.75rem);
        }

        .announcement-banner {
            padding: clamp(1rem, 2.5vw, 1.25rem) clamp(0.875rem, 2.5vw, 1rem);
            min-height: clamp(70px, 14vw, 75px);
            padding-top: clamp(2.75rem, 6vw, 3rem);
        }

        .announcement-banner::after {
            font-size: clamp(3rem, 8vw, 4rem);
            right: -1rem;
        }

        .banner-title {
            font-size: 1.35rem;
            padding-top: 0.25rem;
        }

        .announcement-content-section {
            padding: 1.25rem clamp(1rem, 2.5vw, 1.5rem);
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
            grid-template-columns: 1fr;
            gap: clamp(1rem, 3vw, 1.5rem);
        }

        .archive-section-header {
            margin: clamp(1.5rem, 4vw, 2rem) 0 clamp(1.25rem, 3vw, 1.5rem) 0;
        }

        .archive-section-header h2 {
            font-size: clamp(1.25rem, 3vw, 1.5rem);
        }

        .new-badge {
            font-size: 0.75rem;
            padding: 0.4rem 1rem;
        }

        .event-badge {
            font-size: 0.8rem;
            padding: 0.6rem 1.5rem;
        }
    }
</style>

<main class="announcement-page">
    <div class="announcement-content-wrapper">

        <!-- Page Header -->
        <div class="page-header reveal-element reveal-slide-up">
            <h1><i class="bi bi-megaphone-fill me-2"></i>Announcements & Events</h1>
            <p>Stay updated with our latest news and upcoming activities</p>
        </div>

        @if($announcement)
            <div class="announcement-card reveal-slide-up">
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
            <div class="announcement-card reveal-fade">
                <div class="announcement-content-section" style="padding-top: 1.25rem; padding-bottom: 0;">
                    <span class="event-badge">
                        <i class="bi bi-calendar-check"></i>
                        UPCOMING EVENT
                    </span>
                </div>

                @if($event->image_path)
                    <div class="announcement-image-wrapper" style="margin: 1rem auto 0 auto;" onclick="openImageModal('{{ asset('storage/' . $event->image_path) }}', '{{ $event->title }}')">
                        <img src="{{ asset('storage/' . $event->image_path) }}?v={{ time() }}"
                             alt="{{ $event->title }}">
                    </div>
                @endif

                <div class="announcement-banner" style="background: linear-gradient(135deg, #26a69a 0%, #00897b 50%, #00695c 100%); margin-top: 1rem;">
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
        @if($pastEvents && $pastEvents->count() > 0)
            <div class="archive-section-header reveal-element reveal-slide-up">
                <h2>
                    <i class="bi bi-archive me-2"></i>Event Archive
                </h2>
                <p>Browse through our past events and announcements</p>
            </div>

            <div class="archive-grid">
                @php
                    $colors = ['#26a69a', '#2196F3', '#f59e0b'];
                    $colorIndex = 0;
                @endphp
                @foreach($pastEvents as $pastEvent)
                    <div class="archive-card reveal-element reveal-fade" style="border-top-color: {{ $colors[$colorIndex % 3] }};">
                        <div class="archive-card-content">
                            <div class="archive-date">
                                <i class="bi bi-calendar3"></i>
                                {{ $pastEvent->formatted_date }}
                            </div>
                            <h3 class="archive-title">
                                {{ $pastEvent->title }}
                            </h3>
                            <p class="archive-content">
                                {{ Str::limit($pastEvent->description, 150) }}
                            </p>
                        </div>
                    </div>
                    @php $colorIndex++; @endphp
                @endforeach
            </div>
        @endif
    </div>
</main>

<script>
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

/* Chatbot Styles - Base (All Screen Sizes) */
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chatbot-widget.open { 
    display: flex; 
}

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
    font-size: 1rem;
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
    flex: 1;
    overflow: hidden;
}

.chatbot-messages {
    height: 320px;
    overflow-y: auto;
    padding-right: 4px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    border-bottom: 1px solid #eef2f5;
    flex: 1;
    min-height: 200px;
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

.chip:hover { 
    background: #d2e9fb; 
    transform: translateY(-1px); 
}

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
    font-size: 0.9rem;
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
    transition: background 0.2s ease;
}

.send-btn:hover { 
    background: #1976D2; 
}

.send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

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

/* Mobile Responsive Adjustments */
@media (max-width: 768px) {
    .chatbot-widget {
        right: 16px;
        bottom: 92px;
        width: calc(100vw - 32px);
        max-width: 340px;
    }

    .chatbot-messages {
        height: 280px;
    }
}

@media (max-width: 480px) {
    .chatbot-toggle-btn {
        right: 16px;
        bottom: 16px;
        width: 56px;
        height: 56px;
    }

    .chatbot-widget { 
        right: 16px !important; 
        left: auto !important; 
        width: calc(100vw - 32px) !important;
        bottom: 80px;
    }
    
    .chatbot-messages { 
        height: 240px; 
    }

    .chatbot-title {
        font-size: 0.9rem;
    }
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


        let currentMode = 'faqs';
        let conversationId = null;
        let pollingInterval = null;
        let lastMessageId = null;
        let faqInitialized = false;

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
            if (indicator) indicator.remove();
        }

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

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        async function sendUserMessage(text) {
            if (!text.trim()) return;
            
            if (currentMode === 'live-chat' && conversationId) {
                await sendLiveMessage(text.trim());
                return;
            }
            
            if (currentMode === 'faqs') {
                addMessage(text.trim(), 'user');
                showTypingIndicator();
                const typingDelay = 1000 + Math.random() * 1000;
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(getBotReply(text), 'bot');
                }, typingDelay);
            } else {
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
            widget.classList.add('open');
            widget.setAttribute('aria-hidden', 'false');
            
            if (!messagesEl.dataset.checked) {
                if (currentMode === 'faqs') {
                    chipsEl.style.display = 'flex';
                    chipsEl.innerHTML = '';
                    messagesEl.innerHTML = '';
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                        renderChips();
                        faqInitialized = true;
                    }, 800);
                } else if (currentMode === 'live-chat') {
                    chipsEl.style.display = 'none';
                    chipsEl.innerHTML = '';
                    const isAuth = await checkAuth();
                    if (isAuth) {
                        inputEl.placeholder = 'Type your message to staff...';
                        await initializeLiveChat();
                    } else {
                        messagesEl.innerHTML = '';
                        addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
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
            widget.classList.remove('open');
            widget.setAttribute('aria-hidden', 'true');
            stopPolling();
        }

        function switchTab(mode) {
            currentMode = mode;
            tabLiveChat.classList.toggle('active', mode === 'live-chat');
            tabFaqs.classList.toggle('active', mode === 'faqs');
            
            const inputContainer = document.querySelector('.chatbot-input');
            
            if (mode === 'live-chat') {
                titleEl.textContent = 'Live Chat - Staff';
                inputEl.placeholder = 'Type your message to staff...';
                inputContainer.style.display = 'flex';
                chipsEl.style.display = 'none';
                chipsEl.innerHTML = '';
                stopPolling();
                checkAuth().then(isAuth => {
                    if (isAuth) {
                        messagesEl.innerHTML = '';
                        if (!conversationId) {
                            initializeLiveChat();
                        } else {
                            loadMessages();
                            startPolling();
                        }
                    } else {
                        messagesEl.innerHTML = '';
                        addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
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
                });
            } else {
                titleEl.textContent = 'ToothTalk Assistant';
                inputEl.placeholder = 'Ask about services, hours, pricing...';
                inputContainer.style.display = 'none';
                chipsEl.style.display = 'flex';
                chipsEl.innerHTML = '';
                stopPolling();
                inputEl.disabled = false;
                sendBtn.disabled = false;
                messagesEl.innerHTML = '';
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                    renderChips();
                    faqInitialized = true;
                }, 800);
            }
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

@endsection
