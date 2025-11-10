@extends('layout.patient.app')
@section('content')

<style>
.team-page {
    background: linear-gradient(to bottom, #f0f9ff 0%, #e0f2fe 100%);
    padding: 4rem 2rem;
    min-height: 80vh;
}

.team-container {
    max-width: 1400px;
    margin: 0 auto;
}


.team-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
    color: #1e40af;
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0 auto 1.5rem;
    display: flex;
    justify-content: center;
    width: fit-content;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
}

.team-badge i {
    font-size: 1rem;
}

.team-title {
    font-size: 2.75rem;
    font-weight: 900;
    color: #1a1a1a;
    text-align: center;
    margin-bottom: 1rem;
    letter-spacing: -0.5px;
}

.team-subtitle {
    font-size: 1.1rem;
    color: #64748b;
    text-align: center;
    margin-bottom: 3rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    align-items: start;
}

.team-card {
    perspective: 1000px;
    height: 100%;
    min-height: 350px;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
}

@media (hover: none) and (pointer: coarse) {
    .team-card {
        cursor: pointer;
        user-select: none;
        -webkit-user-select: none;
    }
    
    .team-card:active {
        transform: scale(0.98);
    }
}

.team-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 350px;
    transition: transform 0.6s;
    transform-style: preserve-3d;
}

.team-card:hover .team-card-inner {
    transform: rotateY(180deg);
}

/* Flipped class should override hover on mobile */
.team-card.flipped .team-card-inner {
    transform: rotateY(180deg) !important;
}

@media (hover: none) and (pointer: coarse) {
    .team-card:hover .team-card-inner {
        transform: rotateY(0deg);
    }
    
    /* On mobile, only flipped class should work */
    .team-card.flipped .team-card-inner {
        transform: rotateY(180deg) !important;
    }
}

.team-card-front,
.team-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    min-height: 350px;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    background: white;
    border-top: 4px solid #3b82f6;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    top: 0;
    left: 0;
}

.team-card-front::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 12px 12px 0 0;
    z-index: 1;
}

.team-card-front {
    z-index: 2;
}

/* Mobile tap indicator */
@media (hover: none) and (pointer: coarse) {
    .team-card-front::after {
        content: 'Tap to see description';
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        color: #64748b;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        white-space: nowrap;
        opacity: 0.8;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .team-card.flipped .team-card-front::after {
        opacity: 0;
    }
    
    .team-card-back::before {
        content: 'Tap to go back';
        position: absolute;
        top: 1rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(0, 0, 0, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        white-space: nowrap;
        opacity: 0.8;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
}

.team-card-back {
    transform: rotateY(180deg);
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    justify-content: center;
    align-items: center;
    border-top: none;
}

.team-card:hover .team-card-front {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2);
}

.team-card:hover .team-card-back {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
}

.team-image-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 3;
}

.team-image {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #dbeafe;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.team-card:hover .team-image {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
}

.team-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    text-align: center;
    position: relative;
    z-index: 3;
}

.team-role {
    font-size: 1rem;
    font-weight: 600;
    color: #3b82f6;
    margin-bottom: 1rem;
    text-align: center;
    position: relative;
    z-index: 3;
}

.team-description {
    font-size: 0.95rem;
    line-height: 1.7;
    text-align: center;
    color: white;
}

@media (max-width: 992px) {
    .team-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .team-page {
        padding: 2rem 1rem;
    }

    .team-title {
        font-size: 2.25rem;
    }

    .team-subtitle {
        font-size: 1rem;
    }

    .team-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .team-page {
        padding: 3rem 1.5rem;
    }
}

@media (max-width: 576px) {
    .team-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

/* ============================================
   DARK MODE STYLES FOR DEVELOPMENT TEAM PAGE
   ============================================ */

/* Main Page Dark Mode */
[data-theme="dark"] .team-page {
    background: linear-gradient(to bottom, #1e293b 0%, #0f172a 100%) !important;
}

[data-theme="dark"] .team-container {
    color: var(--dm-text-primary, #f1f5f9) !important;
}


[data-theme="dark"] .team-badge {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .team-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .team-subtitle {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .team-card-front {
    background: var(--dm-card-bg, #1e293b) !important;
    border-top-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .team-card-front::before {
    background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%) !important;
}

[data-theme="dark"] .team-card:hover .team-card-front {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4) !important;
}

[data-theme="dark"] .team-image {
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25) !important;
}

[data-theme="dark"] .team-card:hover .team-image {
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35) !important;
}

[data-theme="dark"] .team-name {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .team-role {
    color: #60a5fa !important;
}

[data-theme="dark"] .team-card-back {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .team-card:hover .team-card-back {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4) !important;
}

[data-theme="dark"] .team-description {
    color: white !important;
}

/* ========================================
   SCROLL REVEAL ANIMATIONS
   ======================================== */
/* Prevent overflow from reveal animations */
html, body {
    overflow-x: hidden;
    width: 100%;
}

.team-page {
    overflow-x: hidden;
    width: 100%;
}

.reveal-element {
    opacity: 0;
    will-change: opacity, transform;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    transition: opacity 1s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                transform 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    contain: layout style paint;
    max-width: 100%;
}

.reveal-element.reveal-fade {
    opacity: 0;
}

.reveal-element.reveal-fade.revealed {
    opacity: 1;
}

.reveal-element.reveal-slide-up {
    opacity: 0;
    transform: translateY(40px);
}

.reveal-element.reveal-slide-up.revealed {
    opacity: 1;
    transform: translateY(0);
}

.reveal-element.reveal-slide-left {
    opacity: 0;
    transform: translateX(-40px);
}

.reveal-element.reveal-slide-left.revealed {
    opacity: 1;
    transform: translateX(0);
}

.reveal-element.reveal-slide-right {
    opacity: 0;
    transform: translateX(40px);
}

.reveal-element.reveal-slide-right.revealed {
    opacity: 1;
    transform: translateX(0);
}

.reveal-element.reveal-scale {
    opacity: 0;
    transform: scale(0.95);
}

.reveal-element.reveal-scale.revealed {
    opacity: 1;
    transform: scale(1);
}

.reveal-delay-1 { transition-delay: 0.1s; }
.reveal-delay-2 { transition-delay: 0.2s; }
.reveal-delay-3 { transition-delay: 0.3s; }
.reveal-delay-4 { transition-delay: 0.4s; }
.reveal-delay-5 { transition-delay: 0.5s; }

@media (max-width: 768px) {
    .reveal-element {
        will-change: opacity, transform;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .reveal-element.reveal-slide-up {
        transform: translateY(25px);
    }
    .reveal-element.reveal-slide-left {
        transform: translateX(-25px);
    }
    .reveal-element.reveal-slide-right {
        transform: translateX(25px);
    }
    .reveal-element.reveal-scale {
        transform: scale(0.97);
    }
    .reveal-element {
        transition: opacity 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                    transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
}

@media (max-width: 480px) {
    .reveal-element.reveal-slide-up {
        transform: translateY(20px);
    }
    .reveal-element.reveal-slide-left,
    .reveal-element.reveal-slide-right {
        transform: translateX(20px);
    }
    .reveal-element {
        transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                    transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
}

@media (prefers-reduced-motion: reduce) {
    .reveal-element {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
}

/* Ensure footer is always visible on development team page */
.patient-footer {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    z-index: 1 !important;
}

.patient-footer .reveal-element {
    opacity: 1 !important;
    transform: none !important;
    transition: none !important;
}
</style>

<div class="team-page">
    <div class="team-container">
        <!-- Meet Our Development Team Section -->
        <div class="team-badge">
            <i class="bi bi-people-fill"></i>
            <span>Development Team</span>
        </div>
        <h2 class="team-title">Meet Our Development Team</h2>
        <p class="team-subtitle">The talented individuals behind the development of this dental clinic management system.</p>

        <div class="team-grid">
                <div class="team-card reveal-element reveal-fade">
                    <div class="team-card-inner">
                        <div class="team-card-front">
                            <div class="team-image-wrapper">
                                <img src="{{ asset('images/dev1.png') }}" alt="Josh Andrei D. Castillo" class="team-image">
                            </div>
                            <h3 class="team-name">Josh Andrei D. Castillo</h3>
                            <p class="team-role">Project Manager/Coordinator</p>
                        </div>
                        <div class="team-card-back">
                            <p class="team-description">Responsible for the overall implementation, execution, and coordination of the group. Ensures that the project is on track and that the team is working towards the same goals.</p>
                        </div>
                    </div>
                </div>

                <div class="team-card reveal-element reveal-fade reveal-delay-1">
                    <div class="team-card-inner">
                        <div class="team-card-front">
                            <div class="team-image-wrapper">
                                <img src="{{ asset('images/dev2.png') }}" alt="Angel Cuadernal" class="team-image">
                            </div>
                            <h3 class="team-name">Angel Cuadernal</h3>
                            <p class="team-role">Technical Lead</p>
                        </div>
                        <div class="team-card-back">
                            <p class="team-description">Responsible in managing group in the aspect of software development, data analysis, and meeting other technical deliverables. Ensures that the system is developed in a way that is efficient and effective.</p>
                        </div>
                    </div>
                </div>

                <div class="team-card reveal-element reveal-fade reveal-delay-2">
                    <div class="team-card-inner">
                        <div class="team-card-front">
                            <div class="team-image-wrapper">
                                <img src="{{ asset('images/dev3.png') }}" alt="John Roy D. Lalantacon" class="team-image">
                            </div>
                            <h3 class="team-name">John Roy D. Lalantacon</h3>
                            <p class="team-role">Design Lead</p>
                        </div>
                        <div class="team-card-back">
                            <p class="team-description">Responsible in front-end development and the visual or creative aspect of the project. Ensures that projects visual design is visually appealing, easy to use, and aligns with the target audience.</p>
                        </div>
                    </div>
                </div>

                <div class="team-card reveal-element reveal-fade reveal-delay-3">
                    <div class="team-card-inner">
                        <div class="team-card-front">
                            <div class="team-image-wrapper">
                                <img src="{{ asset('images/dev4.png') }}" alt="Aleck Joy G. Carpio" class="team-image">
                            </div>
                            <h3 class="team-name">Aleck Joy G. Carpio</h3>
                            <p class="team-role">Communication and Documentation Lead</p>
                        </div>
                        <div class="team-card-back">
                            <p class="team-description">Responsible in maintaining project documentation and other important records. Coordinating with technical and research advisers. Ensure Manuscript is aligned with the actual output to be developed.</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    function initTeamCards() {
        const teamCards = document.querySelectorAll('.team-card');
        
        // Check if device is touch-enabled
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        const isMobile = window.matchMedia('(hover: none) and (pointer: coarse)').matches;
        
        if (!isTouchDevice && !isMobile) {
            // Desktop - hover only, no click handlers needed
            return;
        }
        
        // Mobile/Tablet - add click handlers
        teamCards.forEach(function(card, cardIndex) {
            // Make card clickable
            card.style.cursor = 'pointer';
            
            // Create a unique handler for each card
            const cardClickHandler = function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get the specific card that was clicked
                const clickedCard = e.currentTarget;
                
                // Toggle only this specific card
                clickedCard.classList.toggle('flipped');
                
                console.log('Card ' + cardIndex + ' clicked, flipped:', clickedCard.classList.contains('flipped'));
            };
            
            // Attach click handler directly to this card
            card.addEventListener('click', cardClickHandler, false);
            
            // Add visual feedback
            card.addEventListener('touchstart', function(e) {
                card.style.opacity = '0.9';
            }, { passive: true });
            
            card.addEventListener('touchend', function(e) {
                setTimeout(function() {
                    card.style.opacity = '1';
                }, 150);
            }, { passive: true });
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTeamCards);
    } else {
        initTeamCards();
    }
})();
</script>

@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
@include('patient.components.chatbot')
@endif

<script>
// ========================================
// SCROLL REVEAL FUNCTIONALITY
// ========================================
(function() {
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.reveal-element').forEach(el => {
            el.classList.add('revealed');
        });
        return;
    }

    let isMobile = window.innerWidth <= 768;
    let observerOptions = {
        root: null,
        rootMargin: isMobile ? '0px 0px -50px 0px' : '0px 0px -100px 0px',
        threshold: isMobile ? 0.05 : 0.1
    };

    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    function initRevealElements() {
        const revealElements = document.querySelectorAll('.reveal-element');
        revealElements.forEach(el => {
            if (!el.classList.contains('revealed')) {
                observer.observe(el);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRevealElements);
    } else {
        initRevealElements();
    }

    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            const newIsMobile = window.innerWidth <= 768;
            if (newIsMobile !== isMobile) {
                const newObserverOptions = {
                    root: null,
                    rootMargin: newIsMobile ? '0px 0px -50px 0px' : '0px 0px -100px 0px',
                    threshold: newIsMobile ? 0.05 : 0.1
                };
                observer.disconnect();
                isMobile = newIsMobile;
                observerOptions = newObserverOptions;
                observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);
                const revealElements = document.querySelectorAll('.reveal-element');
                revealElements.forEach(el => {
                    if (!el.classList.contains('revealed')) {
                        observer.observe(el);
                    }
                });
            }
        }, 250);
    });
})();
</script>

@endsection

