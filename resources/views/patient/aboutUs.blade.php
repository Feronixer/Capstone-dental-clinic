@extends('layout.patient.app')
@section('content')

<style>
.about-page {
    background: white;
    padding: 3rem 2rem;
    min-height: 80vh;
}

.about-container {
    max-width: 1400px;
    margin: 0 auto;
}

.about-header {
    margin-bottom: 3rem;
}

.about-main-title {
    font-size: 5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #3b82f6 0%, #14b8a6 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
    letter-spacing: -1px;
}

.about-clinic-title {
    font-size: 2rem;
    font-weight: 900;
    color: #001f3f;
    margin-bottom: 2rem;
}

.about-content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: start;
}

.about-text-section {
    padding-right: 2rem;
}

.about-description {
    font-size: 1.05rem;
    line-height: 1.9;
    color: #5a5a5a;
    text-align: justify;
}

.dentist-card {
    perspective: 1000px;
    width: 100%;
    height: 100%;
    min-height: 400px;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
}

@media (hover: none) and (pointer: coarse) {
    .dentist-card {
        cursor: pointer;
        user-select: none;
        -webkit-user-select: none;
    }
    
    .dentist-card:active {
        transform: scale(0.98);
    }
}

.dentist-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 400px;
    transition: transform 0.6s;
    transform-style: preserve-3d;
}

.dentist-card:hover .dentist-card-inner {
    transform: rotateY(180deg);
}

/* Flipped class should override hover on mobile */
.dentist-card.flipped .dentist-card-inner {
    transform: rotateY(180deg) !important;
}

@media (hover: none) and (pointer: coarse) {
    .dentist-card:hover .dentist-card-inner {
        transform: rotateY(0deg);
    }
    
    /* On mobile, only flipped class should work */
    .dentist-card.flipped .dentist-card-inner {
        transform: rotateY(180deg) !important;
    }
}

.dentist-card-front,
.dentist-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    min-height: 400px;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    top: 0;
    left: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem;
}

.dentist-card-front {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
    z-index: 2;
}

.dentist-card-front::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 8s ease-in-out infinite;
    z-index: 1;
}

/* Mobile tap indicator */
@media (hover: none) and (pointer: coarse) {
    .dentist-card-front::after {
        content: 'Tap to see more';
        position: absolute;
        bottom: 1rem;
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
    
    .dentist-card.flipped .dentist-card-front::after {
        opacity: 0;
    }
    
    .dentist-card-back::before {
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

.dentist-card-back {
    transform: rotateY(180deg);
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    text-align: center;
}

.dentist-card:hover .dentist-card-front {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2);
}

.dentist-card:hover .dentist-card-back {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
}

.dentist-icon {
    width: 500px;
    height: 500px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 10;
    overflow: hidden;
}

.dentist-icon i {
    font-size: 4rem;
    color: white;
}

.dentist-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    position: relative;
    z-index: 2;
}

.dentist-card:hover .dentist-icon img {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

.dentist-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 3;
}

.dentist-role {
    font-size: 1.2rem;
    opacity: 0.95;
    position: relative;
    z-index: 3;
}

.dentist-description {
    font-size: 1.05rem;
    line-height: 1.8;
    text-align: center;
    color: white;
    padding: 0 1rem;
}

@keyframes float {
    0%, 100% {
        transform: translate(0, 0) rotate(0deg);
    }
    50% {
        transform: translate(-20px, -20px) rotate(5deg);
    }
}

/* Location Section */
.location-section {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 2px solid #e0e0e0;
}

.location-title {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1a1a1a;
    text-align: center;
    margin-bottom: 1.5rem;
}

.location-address {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-size: 1.1rem;
    color: #5a5a5a;
    margin-bottom: 2rem;
}

.location-address i {
    font-size: 1.5rem;
    color: #3b82f6;
}

.map-container {
    width: 100%;
    height: 450px;
    min-height: 450px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}

.map-container iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: 0;
}

/* Features Highlight Section */
.features-section {
    margin-top: 1.75rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.875rem;
    margin-top: 1rem;
}

.feature-card {
    background: white;
    border-radius: 8px;
    padding: 0.875rem;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: none;
}

.feature-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
}

.feature-icon-wrapper {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.625rem;
    box-shadow: 0 1px 4px rgba(59, 130, 246, 0.15);
}

.feature-icon-wrapper i {
    font-size: 1.25rem;
    color: white;
}

.feature-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.375rem;
    line-height: 1.2;
}

.feature-description {
    font-size: 0.8rem;
    line-height: 1.5;
    color: #64748b;
}


@media (max-width: 992px) {
    .about-content-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .about-text-section {
        padding-right: 0;
    }

    .about-main-title {
        font-size: 4.5rem;
    }

    .about-clinic-title {
        font-size: 3.5rem;
    }

    .about-image-section {
        min-height: 300px;
    }

    .dentist-card {
        min-height: 300px;
    }

    .dentist-card-inner {
        min-height: 300px;
    }

    .dentist-card-front,
    .dentist-card-back {
        min-height: 300px;
    }

    .dentist-icon {
        width: 100px;
        height: 100px;
    }

    .dentist-icon i {
        font-size: 3rem;
    }

    .location-title {
        font-size: 2rem;
    }

    .location-address {
        font-size: 1rem;
    }

    .map-container {
        height: 350px;
        min-height: 350px;
    }
    
    .map-container iframe {
        height: 100%;
    }

    .features-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .feature-card {
        padding: 0.75rem;
    }

    .feature-icon-wrapper {
        width: 36px;
        height: 36px;
        margin-bottom: 0.5rem;
    }

    .feature-icon-wrapper i {
        font-size: 1.1rem;
    }

    .feature-title {
        font-size: 0.875rem;
    }

    .feature-description {
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .about-page {
        padding: 1.5rem 1rem;
    }

    .about-container {
        max-width: 100%;
    }

    .about-header {
        margin-bottom: 2rem;
    }

    .about-main-title {
        font-size: 3.3rem;
        line-height: 1.2;
    }

    .about-clinic-title {
        font-size: clamp(1.2rem, 4vw, 1.3rem);
    }

    .about-description {
        font-size: clamp(0.95rem, 2.5vw, 1rem);
        text-align: left;
        line-height: 1.7;
    }

    .about-image-section {
        min-height: 280px;
    }

    .dentist-card {
        min-height: 280px;
    }

    .dentist-card-inner {
        min-height: 280px;
    }

    .dentist-card-front,
    .dentist-card-back {
        min-height: 280px;
        padding: 2rem 1.5rem;
    }

    .dentist-icon {
        width: 100px;
        height: 100px;
        margin-bottom: 1rem;
    }

    .dentist-icon i {
        font-size: 3rem;
    }

    .dentist-name {
        font-size: clamp(1.3rem, 4vw, 1.5rem);
    }

    .dentist-role {
        font-size: clamp(0.9rem, 2.5vw, 1rem);
    }

    .dentist-description {
        font-size: clamp(0.9rem, 2.5vw, 0.95rem);
    }

    .location-section {
        margin-top: 2rem;
        padding-top: 2rem;
    }

    .location-title {
        font-size: clamp(1.5rem, 5vw, 1.75rem);
    }

    .location-address {
        font-size: clamp(0.85rem, 2.5vw, 0.95rem);
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }

    .location-address i {
        font-size: 1.25rem;
    }

    .map-container {
        height: 280px;
        min-height: 280px;
    }
    
    .map-container iframe {
        height: 100%;
    }

    .features-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .feature-card {
        padding: 1rem;
        min-height: auto;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .feature-icon-wrapper {
        width: calc((1.1rem * 1.2) + 0.375rem + (0.95rem * 1.5));
        height: calc((1.1rem * 1.2) + 0.375rem + (0.95rem * 1.5));
        aspect-ratio: 1;
        margin-bottom: 0;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-icon-wrapper i {
        font-size: 1.43rem;
    }

    .feature-text-content {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .feature-title {
        font-size: clamp(1rem, 3vw, 1.1rem);
        margin-bottom: 0.375rem;
        line-height: 1.2;
    }

    .feature-description {
        font-size: clamp(0.9rem, 2.5vw, 0.95rem);
        line-height: 1.5;
        margin-bottom: 0;
    }
}

@media (max-width: 480px) {
    .about-page {
        padding: 1rem 0.75rem;
    }

    .about-main-title {
        font-size: 2.8rem;
        margin-bottom: 0.5rem;
    }

    .about-clinic-title {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    .about-description {
        font-size: 0.9rem;
    }

    .about-image-section {
        min-height: 250px;
    }

    .dentist-card {
        min-height: 250px;
    }

    .dentist-card-inner {
        min-height: 250px;
    }

    .dentist-card-front,
    .dentist-card-back {
        min-height: 250px;
        padding: 1.5rem 1rem;
    }

    .dentist-icon {
        width: 80px;
        height: 80px;
        margin-bottom: 0.75rem;
    }

    .dentist-icon i {
        font-size: 2.5rem;
    }

    .dentist-name {
        font-size: 1.2rem;
    }

    .dentist-role {
        font-size: 0.9rem;
    }

    .dentist-description {
        font-size: 0.85rem;
    }

    .location-title {
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }

    .location-address {
        font-size: 0.8rem;
        padding: 0 0.5rem;
    }

    .map-container {
        height: 250px;
        min-height: 250px;
    }

    .feature-card {
        padding: 0.875rem;
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
    }

    .feature-icon-wrapper {
        width: calc((0.95rem * 1.2) + 0.375rem + (0.85rem * 1.5));
        height: calc((0.95rem * 1.2) + 0.375rem + (0.85rem * 1.5));
        aspect-ratio: 1;
        margin-bottom: 0;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-icon-wrapper i {
        font-size: 1.3rem;
    }

    .feature-text-content {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .feature-title {
        font-size: 0.95rem;
        margin-bottom: 0.375rem;
        line-height: 1.2;
    }

    .feature-description {
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 0;
    }
}

/* ============================================
   DARK MODE STYLES FOR ABOUT US PAGE
   ============================================ */

/* Main Page Dark Mode */
[data-theme="dark"] .about-page {
    background: var(--dm-bg-primary, #0f172a) !important;
}

[data-theme="dark"] .about-container {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Header Dark Mode */
[data-theme="dark"] .about-main-title {
    background: linear-gradient(135deg, #00EAFF 0%, #00EAFF 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: none !important;
    filter: none !important;
}

[data-theme="dark"] .about-clinic-title {
    color: #3b82f6 !important;
}

/* Content Grid Dark Mode */
[data-theme="dark"] .about-content-grid {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Description Dark Mode */
[data-theme="dark"] .about-description {
    color: var(--dm-text-muted, #94a3b8) !important;
}


/* Dentist card text is already white, which is good */

/* Location Section Dark Mode */
[data-theme="dark"] .location-section {
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .location-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .location-address {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .location-address i {
    color: #60a5fa !important;
}

/* Map Container Dark Mode */
[data-theme="dark"] .map-container {
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
}


/* Features Section Dark Mode */
[data-theme="dark"] .features-section {
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .feature-card {
    background: var(--dm-card-bg, #1e293b) !important;
    border: none !important;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .feature-card:hover {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .feature-icon-wrapper {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .feature-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .feature-description {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* ========================================
   SCROLL REVEAL ANIMATIONS
   ======================================== */
/* Prevent overflow from reveal animations */
html, body {
    overflow-x: hidden;
    width: 100%;
}

.about-page {
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

<div class="about-page">
    <div class="about-container">
        <div class="about-header reveal-element reveal-slide-up">
            <h1 class="about-main-title">ABOUT US</h1>
            <h2 class="about-clinic-title">JVALERA DENTAL CLINIC</h2>
        </div>

        <div class="about-content-grid">
            <div class="about-text-section">
                <p class="about-description">
                    We believe in creating smiles that last a lifetime. Located in the heart of Gen. T. De Leon Valenzuela City, our clinic is a place where your comfort and well-being are our top priorities. Our friendly and skilled team takes the time to understand your individual needs and concerns, offering gentle and effective dental care tailored just for you. We're more than just a dental clinic; we're your partners in achieving optimal oral health and a confident smile.
                </p>

                <!-- Features Highlight Section -->
                <div class="features-section">
                    <div class="features-grid">
                        <div class="feature-card reveal-element reveal-fade">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="feature-text-content">
                                <h3 class="feature-title">Expert Dental Team</h3>
                                <p class="feature-description">Our skilled professionals are dedicated to providing the highest quality dental care.</p>
                            </div>
                        </div>

                        <div class="feature-card reveal-element reveal-fade reveal-delay-1">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-cpu-fill"></i>
                            </div>
                            <div class="feature-text-content">
                                <h3 class="feature-title">Advanced Technology</h3>
                                <p class="feature-description">We utilize the latest dental technology for precise diagnoses and effective treatments.</p>
                            </div>
                        </div>

                        <div class="feature-card reveal-element reveal-fade reveal-delay-2">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-heart-pulse-fill"></i>
                            </div>
                            <div class="feature-text-content">
                                <h3 class="feature-title">Patient-Centered Care</h3>
                                <p class="feature-description">Your comfort and satisfaction are at the heart of everything we do.</p>
                            </div>
                        </div>

                        <div class="feature-card reveal-element reveal-fade reveal-delay-3">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <div class="feature-text-content">
                                <h3 class="feature-title">Sterile Environment</h3>
                                <p class="feature-description">We maintain the highest standards of cleanliness and safety for all our patients.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-image-section">
                <div class="dentist-card">
                    <div class="dentist-card-inner">
                        <div class="dentist-card-front">
                            <div class="dentist-icon">
                                <img src="{{ asset('images/doc.jpg') }}" alt="Dr. JValera">
                            </div>
                            <h3 class="dentist-name">Dr. Justin Valera</h3>
                            <p class="dentist-role">Head Dentist</p>
                        </div>
                        <div class="dentist-card-back">
                            <p class="dentist-description">With years of experience in dental care, Dr. Justin Valera leads our team with expertise and compassion. Dedicated to providing the highest quality dental services and ensuring patient comfort and satisfaction.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
>
        </div>

        <!-- Our Location Section -->
        <div class="location-section">
            <h2 class="location-title">Our Location</h2>
            <div class="location-address">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Policarpio St. Gen. T. de Leon Valenzuela City, Valenzuela, Philippines</span>
            </div>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3857.234!2d120.9831!3d14.7045!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b36e1e1e1e1e%3A0x1e1e1e1e1e1e1e1e!2sPolicarpio%20St%2C%20Valenzuela%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1234567890123!5m2!1sen!2sph"
                    style="border:0; border-radius: 12px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>
</div>

<script>
// ========================================
// DENTIST CARD FLIP FUNCTIONALITY
// ========================================
(function() {
    'use strict';
    
    function initDentistCard() {
        const dentistCard = document.querySelector('.dentist-card');
        if (!dentistCard) return;
        
        // Check if device is touch-enabled
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        const isMobile = window.matchMedia('(hover: none) and (pointer: coarse)').matches;
        
        if (!isTouchDevice && !isMobile) {
            // Desktop - hover only, no click handlers needed
            return;
        }
        
        // Mobile/Tablet - add click handlers
        dentistCard.style.cursor = 'pointer';
        
        const cardClickHandler = function(e) {
            e.preventDefault();
            e.stopPropagation();
            dentistCard.classList.toggle('flipped');
        };
        
        dentistCard.addEventListener('click', cardClickHandler, false);
        
        // Add visual feedback
        dentistCard.addEventListener('touchstart', function(e) {
            dentistCard.style.opacity = '0.9';
        }, { passive: true });
        
        dentistCard.addEventListener('touchend', function(e) {
            setTimeout(function() {
                dentistCard.style.opacity = '1';
            }, 150);
        }, { passive: true });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDentistCard);
    } else {
        initDentistCard();
    }
})();

// Google Maps - Always keep in light mode regardless of theme
(function() {
    function ensureLightModeMap() {
        const mapContainer = document.querySelector('.map-container iframe');
        if (mapContainer) {
            // Always ensure light mode - remove any dark mode filters
            mapContainer.style.filter = 'none';
            mapContainer.style.webkitFilter = 'none';
            mapContainer.style.opacity = '1';
            mapContainer.style.transition = 'none';
        }
    }
    
    function initMapLightMode() {
        const mapContainer = document.querySelector('.map-container iframe');
        if (!mapContainer) {
            setTimeout(initMapLightMode, 500);
            return;
        }
        
        // Ensure light mode on load
        ensureLightModeMap();
        
        // Monitor theme changes and always keep map in light mode
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                    // Always keep map in light mode, regardless of theme
                    ensureLightModeMap();
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
        
        // Also check periodically to ensure filters aren't re-applied
        setInterval(ensureLightModeMap, 1000);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMapLightMode);
    } else {
        initMapLightMode();
    }
    
    // Ensure light mode after a delay to catch any late-loading scripts
    setTimeout(ensureLightModeMap, 1000);
    setTimeout(ensureLightModeMap, 2000);
})();

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

@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
@include('patient.components.chatbot')
@endif

@endsection
