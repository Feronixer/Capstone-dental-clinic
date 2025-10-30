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

    /* Main Content */
    .announcement-page {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        min-height: calc(100vh - 70px);
        padding: 3rem 0;
    }

    .announcement-content-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        animation: fadeInDown 0.6s ease-out;
    }

    .page-header h1 {
        font-size: 2.75rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-blue), var(--accent-teal));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: var(--text-medium);
        font-size: 1.1rem;
    }

    /* Main Announcement Card */
    .announcement-card {
        background: var(--bg-white);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        margin-bottom: 3rem;
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
        padding: 3rem 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
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
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        position: relative;
        text-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
    }

    .announcement-content-section {
        padding: 3rem;
    }

    .announcement-image-wrapper {
        margin-bottom: 2.5rem;
        text-align: center;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .announcement-image-wrapper img {
        max-width: 100%;
        max-height: 400px;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 16px;
        transition: transform 0.4s ease;
        display: block;
        margin: 0 auto;
    }

    .announcement-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .announcement-message {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        border-left: 5px solid var(--primary-blue);
        padding: 2rem 2.5rem;
        border-radius: 12px;
        color: var(--text-dark);
        line-height: 1.8;
        font-size: 1.05rem;
        box-shadow: var(--shadow-sm);
        position: relative;
    }

    .announcement-message::before {
        content: '📋';
        position: absolute;
        top: 1rem;
        right: 1.5rem;
        font-size: 1.5rem;
        opacity: 0.3;
    }

    .new-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        margin-bottom: 1rem;
        animation: pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .announcement-details {
        display: flex;
        gap: 2.5rem;
        flex-wrap: wrap;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid #f0f0f0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--text-medium);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.75rem 1.5rem;
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
        font-size: 1.3rem;
    }

    /* Event Badge */
    .event-badge {
        background: linear-gradient(135deg, var(--accent-teal) 0%, var(--accent-teal-dark) 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 12px rgba(38, 166, 154, 0.3);
        letter-spacing: 0.5px;
    }

    /* Archive Section */
    .archive-section-header {
        text-align: center;
        margin: 4rem 0 3rem 0;
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }

    .archive-section-header h2 {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
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
        font-size: 1.05rem;
        margin-top: 1.5rem;
    }

    .archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* Archive Cards */
    .archive-card {
        background: var(--bg-white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-top: 5px solid;
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
        padding: 2rem;
        position: relative;
        z-index: 1;
    }

    .archive-date {
        color: var(--text-light);
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .archive-date i {
        font-size: 1rem;
    }

    .archive-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .archive-content {
        color: var(--text-medium);
        font-size: 0.95rem;
        line-height: 1.7;
        margin: 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: var(--bg-white);
        border-radius: 24px;
        box-shadow: var(--shadow-md);
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--primary-light);
        margin-bottom: 1.5rem;
        opacity: 0.8;
    }

    .empty-state h3 {
        color: var(--text-dark);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: var(--text-medium);
        font-size: 1rem;
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
    @media (max-width: 768px) {
        .announcement-page {
            padding: 2rem 0;
        }

        .announcement-content-wrapper {
            padding: 0 1rem;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .banner-title {
            font-size: 1.5rem;
        }

        .announcement-banner {
            padding: 2rem 1.5rem;
        }

        .announcement-banner::after {
            font-size: 4rem;
            right: -1rem;
        }

        .announcement-content-section {
            padding: 2rem 1.5rem;
        }

        .announcement-image-wrapper {
            max-width: 100%;
            margin-bottom: 2rem;
        }

        .announcement-image-wrapper img {
            max-height: 300px;
        }

        .announcement-message {
            padding: 1.5rem;
            font-size: 0.95rem;
        }

        .announcement-message::before {
            font-size: 1.2rem;
            top: 0.75rem;
            right: 1rem;
        }

        .announcement-details {
            gap: 1rem;
        }

        .detail-item {
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
        }

        .detail-item i {
            font-size: 1.1rem;
        }

        .archive-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .archive-section-header h2 {
            font-size: 1.75rem;
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
        <div class="page-header">
            <h1><i class="bi bi-megaphone-fill me-2"></i>Announcements & Events</h1>
            <p>Stay updated with our latest news and upcoming activities</p>
        </div>

        @if($announcement)
            <div class="announcement-card">
                <div class="announcement-banner">
                    <h2 class="banner-title">{{ $announcement->title }}</h2>
                </div>

                <div class="announcement-content-section">
                    @if($announcement->created_at->diffInDays(now()) < 7)
                        <span class="new-badge">
                            <i class="bi bi-star-fill"></i>
                            NEW
                        </span>
                    @endif

                    @if($announcement->image_path)
                        <div class="announcement-image-wrapper">
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}">
                        </div>
                    @endif

                    <div class="announcement-message">
                        {{ $announcement->content }}
                    </div>

                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="bi bi-calendar-event"></i>
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
            <div class="announcement-card">
                <div class="announcement-content-section" style="padding-top: 2rem; padding-bottom: 0;">
                    <span class="event-badge">
                        <i class="bi bi-calendar-check"></i>
                        UPCOMING EVENT
                    </span>
                </div>

                @if($event->image_path)
                    <div class="announcement-image-wrapper" style="margin: 1.5rem auto 0 auto;">
                        <img src="{{ asset('storage/' . $event->image_path) }}?v={{ time() }}"
                             alt="{{ $event->title }}">
                    </div>
                @endif

                <div class="announcement-banner" style="background: linear-gradient(135deg, #26a69a 0%, #00897b 50%, #00695c 100%); margin-top: 1.5rem;">
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
            <div class="archive-section-header">
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
                    <div class="archive-card" style="border-top-color: {{ $colors[$colorIndex % 3] }};">
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

@endsection
