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
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #78350f;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.announcement-ticker {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0.75rem 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.announcement-ticker i {
    font-size: 1.1rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.announcement-content {
    flex: 1;
    overflow: hidden;
}

.announcement-text {
    display: block;
    animation: scroll-left 30s linear infinite;
    white-space: nowrap;
}

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
    background: rgba(120, 53, 15, 0.15);
    border: none;
    color: #78350f;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}

.close-announcement:hover {
    background: rgba(120, 53, 15, 0.25);
}

@media (max-width: 768px) {
    .announcement-ticker {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
    }
}
</style>
