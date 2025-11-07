@php
    $announcement = \App\Models\Announcement::first();
    $showTicker = $announcement && $announcement->show_ticker && $announcement->ticker_text;
@endphp

@if($showTicker)
<div class="top-header-bar">
    <div class="announcement-ticker">
        <i class="bi bi-megaphone-fill me-2"></i>
        <div class="announcement-content">
            <span class="announcement-text">
                <strong>Announcement:</strong> {{ $announcement->ticker_text }}
            </span>
        </div>
        <button class="close-announcement" onclick="this.parentElement.style.display='none'">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>
@endif

<style>
.top-header-bar {
    background: linear-gradient(135deg, #0a2a6b 0%, #0b3b91 100%);
    color: #ffffff;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1000;
}

.announcement-ticker {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0.6rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.announcement-ticker i {
    font-size: 1.1rem;
    color: #ffffff;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.announcement-content { flex: 1; overflow: hidden; }

.announcement-text {
    display: block;
    animation: scroll-left 30s linear infinite;
    white-space: nowrap;
    color: #ffffff;
}

.announcement-text strong { color: #ffffff; }

@keyframes scroll-left {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

.announcement-content:hover .announcement-text {
    animation-play-state: paused;
}

.close-announcement {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}

.close-announcement:hover {
    background: rgba(255, 255, 255, 0.25);
}

/* Dark mode: keep the same navy styling and ensure text is readable */
[data-theme="dark"] .top-header-bar { background: linear-gradient(135deg, #0a2a6b 0%, #0b3b91 100%) !important; color: #ffffff !important; }
[data-theme="dark"] .announcement-ticker i { color: #ffffff !important; }
[data-theme="dark"] .announcement-text { color: #ffffff !important; }
[data-theme="dark"] .announcement-text strong { color: #ffffff !important; }
[data-theme="dark"] .close-announcement { background: rgba(255,255,255,0.15) !important; color: #ffffff !important; }
[data-theme="dark"] .close-announcement:hover { background: rgba(255,255,255,0.25) !important; }

@media (max-width: 768px) {
    .announcement-ticker {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        gap: 0.5rem;
    }

    .announcement-ticker i {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .announcement-ticker {
        padding: 0.65rem 0.75rem;
        font-size: 0.8rem;
    }

    .announcement-ticker i {
        font-size: 0.9rem;
    }

    .close-announcement {
        width: 20px;
        height: 20px;
        font-size: 0.8rem;
    }
}
</style>
