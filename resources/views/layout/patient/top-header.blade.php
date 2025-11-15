@php
    $announcement = \App\Models\Announcement::first();
    $showTicker = $announcement && $announcement->show_ticker && $announcement->ticker_text;
@endphp

@if($showTicker)
<div class="top-header-bar">
    <div class="announcement-ticker">
        <div class="announcement-content">
            <span class="announcement-text">
                <i class="bi bi-megaphone-fill me-2 announcement-bell"></i>
                <strong>Announcement:</strong> {{ $announcement->ticker_text }}
            </span>
        </div>
        <button class="close-announcement" id="announcementCloseBtn" type="button">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>
<!-- Disable Announcement Modal -->
<div class="announce-modal-backdrop" id="announceModal" aria-hidden="true" style="display:none;">
    <div class="announce-modal">
        <div class="announce-modal-header">
            <span class="announce-modal-title">Disable Announcement Ticker</span>
            <button class="announce-modal-close" id="announceModalClose" type="button" aria-label="Close">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="announce-modal-body">
            <p>Do you want to disable the Announcement Ticker for 10 minutes?</p>
        </div>
        <div class="announce-modal-actions">
            <button type="button" class="announce-btn ignore" id="announceIgnore">Ignore</button>
            <button type="button" class="announce-btn confirm" id="announceConfirm">Yes, disable for 10 mins.</button>
        </div>
    </div>
    <div class="announce-modal-overlay"></div>
</div>
<script>
(function() {
    const STORAGE_KEY = 'patient_announce_snooze_until';
    const ANIMATION_START_KEY = 'patient_ticker_animation_start';
    const ANIMATION_DURATION = 30000; // 30 seconds in milliseconds
    const bar = document.querySelector('.top-header-bar');
    const modal = document.getElementById('announceModal');
    const btnClose = document.getElementById('announcementCloseBtn');
    const btnIgnore = document.getElementById('announceIgnore');
    const btnConfirm = document.getElementById('announceConfirm');
    const btnModalClose = document.getElementById('announceModalClose');
    const announcementText = document.querySelector('.announcement-text');

    function nowMs() { return Date.now(); }
    function isSnoozed() {
        try { const until = Number(localStorage.getItem(STORAGE_KEY)); return !!until && nowMs() < until; }
        catch(e){ return false; }
    }
    function showModal(){ if(modal){ modal.style.display='block'; document.body.style.overflow='hidden'; } }
    function hideModal(){ if(modal){ modal.style.display='none'; document.body.style.overflow=''; } }
    function hideBar(){ if(bar){ bar.style.display='none'; } }
    function snooze10Minutes(){
        try { localStorage.setItem(STORAGE_KEY, String(nowMs() + 10*60*1000)); } catch(e){}
    }
    
    // Continuous animation logic
    function initContinuousAnimation() {
        if (!announcementText) return;
        
        try {
            let animationStart = Number(localStorage.getItem(ANIMATION_START_KEY));
            const currentTime = nowMs();
            
            // If no start time exists or it's been too long, start fresh
            if (!animationStart || (currentTime - animationStart) > ANIMATION_DURATION * 10) {
                animationStart = currentTime;
                localStorage.setItem(ANIMATION_START_KEY, String(animationStart));
            }
            
            // Calculate elapsed time within the current animation cycle
            const elapsed = (currentTime - animationStart) % ANIMATION_DURATION;
            
            // Calculate negative delay to start animation at the correct point
            const negativeDelay = -(elapsed / 1000); // Convert to seconds
            
            // Apply the animation with negative delay to continue from where it left off
            announcementText.style.animationDelay = negativeDelay + 's';
            announcementText.style.animationPlayState = 'running';
            
            // Update start time periodically to prevent drift
            setTimeout(function() {
                const newStart = nowMs() - (elapsed % ANIMATION_DURATION);
                localStorage.setItem(ANIMATION_START_KEY, String(newStart));
            }, 1000);
            
        } catch(e) {
            console.warn('Could not initialize continuous animation:', e);
        }
    }
    
    if (isSnoozed()) { 
        hideBar(); 
    } else {
        // Initialize continuous animation if ticker is visible
        initContinuousAnimation();
    }
    
    if (btnClose) btnClose.addEventListener('click', function(e){ e.preventDefault(); showModal(); });
    if (btnModalClose) btnModalClose.addEventListener('click', hideModal);
    if (btnIgnore) btnIgnore.addEventListener('click', function(){ hideModal(); hideBar(); });
    if (btnConfirm) btnConfirm.addEventListener('click', function(){ snooze10Minutes(); hideModal(); hideBar(); });
})();
</script>
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

.announcement-bell {
    font-size: 1.1rem;
    color: #ffffff;
    animation: bell-ring 2s ease-in-out infinite;
    transform-origin: center center;
    display: inline-block;
    vertical-align: middle;
    position: relative;
}

@keyframes bell-ring {
    0%, 100% {
        transform: rotate(0deg);
    }
    10% {
        transform: rotate(-15deg);
    }
    20% {
        transform: rotate(12deg);
    }
    30% {
        transform: rotate(-10deg);
    }
    40% {
        transform: rotate(8deg);
    }
    50% {
        transform: rotate(-6deg);
    }
    60% {
        transform: rotate(4deg);
    }
    70% {
        transform: rotate(-3deg);
    }
    80% {
        transform: rotate(2deg);
    }
    90% {
        transform: rotate(-1deg);
    }
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

/* Modal styles (light and dark, matching system UI) */
.announce-modal-backdrop { position: fixed; inset: 0; z-index: 2000; }
.announce-modal-overlay { position: absolute; inset: 0; background: rgba(15,23,42,0.45); backdrop-filter: blur(1.5px); }
.announce-modal {
    position: relative; z-index: 1; width: min(520px, 92vw); margin: 10vh auto 0;
    background: #ffffff; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    overflow: hidden; display: flex; flex-direction: column;
}
.announce-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.9rem 1rem; border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
}
.announce-modal-title { font-weight: 800; color: #0f172a; }
.announce-modal-close {
    width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #334155; cursor: pointer;
}
.announce-modal-close:hover { background: #f1f5f9; }
.announce-modal-body { padding: 1rem; color: #334155; font-weight: 600; }
.announce-modal-actions { display: flex; gap: 0.5rem; justify-content: flex-end; padding: 0.75rem 1rem 1rem; }
.announce-btn { border: none; border-radius: 10px; padding: 0.55rem 0.9rem; font-weight: 700; cursor: pointer; }
.announce-btn.ignore { background: #e2e8f0; color: #0f172a; }
.announce-btn.ignore:hover { background: #cbd5e1; }
/* Red confirm per request */
.announce-btn.confirm { background: #ef4444; color: #ffffff; box-shadow: 0 6px 14px rgba(239,68,68,0.3); }
.announce-btn.confirm:hover { background: #dc2626; }

/* Dark mode for modal */
[data-theme="dark"] .announce-modal { background: #0f172a !important; color: #e2e8f0 !important; box-shadow: 0 20px 60px rgba(0,0,0,0.6); }
[data-theme="dark"] .announce-modal-header { background: linear-gradient(135deg, #0b1220 0%, #0f172a 100%) !important; border-bottom-color: #1f2a44 !important; }
[data-theme="dark"] .announce-modal-close { background: #0b1220 !important; color: #cbd5e1 !important; border-color: #1f2a44 !important; }
[data-theme="dark"] .announce-btn.ignore { background: #1f2937 !important; color: #e5e7eb !important; }
[data-theme="dark"] .announce-btn.ignore:hover { background: #374151 !important; }
[data-theme="dark"] .announce-btn.confirm { background: #ef4444 !important; color: #ffffff !important; }

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
