@extends('layout.patient.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/patient-calendar.css') }}">

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

        <div class="announcement-card">
            <div class="announcement-banner">
                <h2 class="banner-title">Holiday Closure Notice</h2>
            </div>

            <div style="padding: 2.5rem;">
                <h3 class="content-title">IT'S A CELEBRATION!</h3>

                <p class="content-description">
                    We are closed on April 9, 2025. Clinical Operations Resume on April 10, 2025.
                </p>

                <div class="announcement-details">
                    <div class="detail-item">
                        <i class="bi bi-calendar-event"></i>
                        <span>April 9, 2025</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-clock"></i>
                        <span>All Day Closure</span>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>Main Clinic</span>
                    </div>
                </div>

                <div class="announcement-message">
                    Our dental clinic will be closed in observance of a regular non-working holiday. We apologize for any inconvenience this may cause and look forward to serving you when we resume operations on April 10, 2025.
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function closeNotification() {
    const notificationBar = document.getElementById('notificationBar');
    notificationBar.style.transition = 'all 0.3s ease';
    notificationBar.style.opacity = '0';
    notificationBar.style.transform = 'translateY(-100%)';
    setTimeout(() => {
        notificationBar.style.display = 'none';
    }, 300);
}
</script>

@endsection
