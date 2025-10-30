@extends('layout.guest.app')
@section('content')

<style>
    /* Main Content */
    .announcement-page {
        padding: 0;
    }

    .announcement-content-wrapper {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .announcement-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 3px solid #2196F3;
        margin-bottom: 2rem;
    }

    .announcement-banner {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        padding: 5rem 2rem;
        text-align: center;
    }

    .banner-title {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .content-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2196F3;
        margin-bottom: 1.5rem;
    }

    .content-description {
        font-size: 1rem;
        color: #546e7a;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .announcement-details {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        color: #546e7a;
        font-weight: 500;
    }

    .detail-item i {
        color: #2196F3;
        font-size: 1.2rem;
    }

    .announcement-message {
        background: #f5f5f5;
        border-left: 4px solid #2196F3;
        padding: 1.5rem;
        border-radius: 8px;
        color: #546e7a;
        line-height: 1.7;
    }

    /* Archive Cards */
    .archive-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .archive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .notification-bar {
            padding: 0.75rem 1rem;
        }

        .notification-left {
            gap: 0.75rem;
        }

        .notification-icon {
            font-size: 1.1rem;
        }

        .notification-text {
            font-size: 0.85rem;
        }

        .notification-close {
            font-size: 1rem;
        }

        .announcement-content-wrapper {
            padding: 1rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .banner-title {
            font-size: 1.5rem;
        }

        .content-title {
            font-size: 1.5rem;
        }

        .announcement-banner {
            padding: 3rem 1rem;
        }

        .announcement-content {
            padding: 1.5rem;
        }

        .announcement-details {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<main class="announcement-page">
    <!-- Main Content -->
    <div class="announcement-content-wrapper">

        @if($announcement)
            <div class="announcement-card">
                <div class="announcement-banner">
                    <h2 class="banner-title">{{ $announcement->title }}</h2>
                </div>

                <div style="padding: 2.5rem;">
                    @if($announcement->image_path)
                        <div style="margin-bottom: 2rem; text-align: center;">
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" style="max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        </div>
                    @endif

                    <div class="announcement-message">
                        {{ $announcement->content }}
                    </div>

                    <div class="announcement-details" style="margin-top: 2rem;">
                        <div class="detail-item">
                            <i class="bi bi-calendar-event"></i>
                            <span>Posted {{ $announcement->created_at->format('F d, Y') }}</span>
                        </div>
                        @if($announcement->updated_at != $announcement->created_at)
                            <div class="detail-item">
                                <i class="bi bi-clock"></i>
                                <span>Updated {{ $announcement->updated_at->format('F d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="announcement-card">
                <div style="padding: 3rem; text-align: center;">
                    <i class="bi bi-megaphone" style="font-size: 4rem; color: #bbdefb; margin-bottom: 1rem;"></i>
                    <h3 style="color: #546e7a; margin-bottom: 1rem;">No Announcements</h3>
                    <p style="color: #78909c;">There are currently no announcements to display. Please check back later.</p>
                </div>
            </div>
        @endif

        <!-- Upcoming Events -->
        @forelse($upcomingEvents as $event)
            <div class="announcement-card">
                <div style="padding: 1.5rem 2.5rem 0 2.5rem;">
                    <span style="background: linear-gradient(135deg, #26a69a 0%, #1e8e82 100%); color: white; padding: 0.5rem 1.5rem; border-radius: 25px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-calendar-check"></i>
                        UPCOMING EVENT
                    </span>
                </div>

                @if($event->image_path)
                    <div style="padding: 1.5rem 2.5rem 0 2.5rem;">
                        <img src="{{ asset('storage/' . $event->image_path) }}?v={{ time() }}" alt="{{ $event->title }}" style="width: 100%; height: auto; border-radius: 12px; max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <div class="announcement-banner" style="background: linear-gradient(135deg, #26a69a 0%, #1e8e82 100%); margin-top: 1.5rem;">
                    <h2 class="banner-title">{{ $event->title }}</h2>
                </div>

                <div style="padding: 2.5rem;">
                    <div class="announcement-message" style="border-left: 4px solid #26a69a;">
                        {{ $event->description }}
                    </div>

                    <div class="announcement-details" style="margin-top: 2rem;">
                        <div class="detail-item">
                            <i class="bi bi-calendar-event" style="color: #26a69a;"></i>
                            <span>{{ $event->formatted_date }}</span>
                        </div>
                        @if($event->event_time)
                            <div class="detail-item">
                                <i class="bi bi-clock" style="color: #26a69a;"></i>
                                <span>{{ $event->formatted_time }}</span>
                            </div>
                        @endif
                        @if($event->location)
                            <div class="detail-item">
                                <i class="bi bi-geo-alt" style="color: #26a69a;"></i>
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
        <div style="margin-top: 3rem; margin-bottom: 2rem;">
            <h2 style="font-size: 2rem; font-weight: 800; color: #2C3E50; text-align: center; margin-bottom: 1rem;">Announcement Archive</h2>
            <p style="text-align: center; color: #546e7a; font-size: 1rem; margin-bottom: 2.5rem;">Previous announcements and updates from our clinic.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Archive Card 1 -->
            <div class="archive-card" style="border-top: 4px solid #26a69a;">
                <div style="padding: 1.5rem;">
                    <div style="color: #64748b; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.75rem;">March 15, 2025</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #2C3E50; margin-bottom: 1rem;">New Dental Technology Implementation</h3>
                    <p style="color: #546e7a; font-size: 0.95rem; line-height: 1.6; margin: 0;">We've upgraded our equipment with the latest digital imaging technology for more accurate diagnoses and comfortable treatments.</p>
                </div>
            </div>

            <!-- Archive Card 2 -->
            <div class="archive-card" style="border-top: 4px solid #2196F3;">
                <div style="padding: 1.5rem;">
                    <div style="color: #64748b; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.75rem;">February 28, 2025</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #2C3E50; margin-bottom: 1rem;">Extended Hours for February</h3>
                    <p style="color: #546e7a; font-size: 0.95rem; line-height: 1.6; margin: 0;">To accommodate more patients, we extended our clinic hours throughout February. Regular hours resume in March.</p>
                </div>
            </div>

            <!-- Archive Card 3 -->
            <div class="archive-card" style="border-top: 4px solid #26a69a;">
                <div style="padding: 1.5rem;">
                    <div style="color: #64748b; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.75rem;">January 10, 2025</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #2C3E50; margin-bottom: 1rem;">New Dental Hygienist Joins Our Team</h3>
                    <p style="color: #546e7a; font-size: 0.95rem; line-height: 1.6; margin: 0;">We're excited to welcome our new dental hygienist who brings 8 years of experience in preventive dental care.</p>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

