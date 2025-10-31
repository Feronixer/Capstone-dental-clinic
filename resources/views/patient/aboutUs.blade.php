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
    font-size: 3.5rem;
    font-weight: 900;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
    letter-spacing: -1px;
}

.about-clinic-title {
    font-size: 2rem;
    font-weight: 700;
    color: #17a2b8;
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

.about-image-section {
    background: #17a2b8;
    border-radius: 12px;
    padding: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    position: relative;
    overflow: hidden;
}

.dentist-card {
    text-align: center;
    color: white;
    z-index: 2;
}

.dentist-icon {
    width: 120px;
    height: 120px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    backdrop-filter: blur(10px);
}

.dentist-icon i {
    font-size: 4rem;
    color: white;
}

.dentist-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.dentist-role {
    font-size: 1.2rem;
    opacity: 0.95;
}

.about-image-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 8s ease-in-out infinite;
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
    color: #17a2b8;
}

.map-container {
    width: 100%;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
}

.map-container iframe {
    display: block;
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
        font-size: 2.5rem;
    }

    .about-clinic-title {
        font-size: 1.5rem;
    }

    .about-image-section {
        min-height: 300px;
    }

    .location-title {
        font-size: 2rem;
    }

    .location-address {
        font-size: 1rem;
    }

    .map-container iframe {
        height: 350px;
    }
}

@media (max-width: 768px) {
    .about-page {
        padding: 2rem 1rem;
    }

    .about-main-title {
        font-size: 2rem;
    }

    .about-clinic-title {
        font-size: 1.3rem;
    }

    .about-description {
        font-size: 1rem;
        text-align: left;
    }

    .dentist-name {
        font-size: 1.5rem;
    }

    .dentist-role {
        font-size: 1rem;
    }

    .location-section {
        margin-top: 2rem;
        padding-top: 2rem;
    }

    .location-title {
        font-size: 1.75rem;
    }

    .location-address {
        font-size: 0.95rem;
        flex-direction: column;
        text-align: center;
    }

    .map-container iframe {
        height: 300px;
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
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .about-clinic-title {
    color: #14b8a6 !important;
}

/* Content Grid Dark Mode */
[data-theme="dark"] .about-content-grid {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Description Dark Mode */
[data-theme="dark"] .about-description {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Image Section Dark Mode - Keep teal background but adjust if needed */
[data-theme="dark"] .about-image-section {
    background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%) !important;
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
    color: #14b8a6 !important;
}

/* Map Container Dark Mode */
[data-theme="dark"] .map-container {
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
}
</style>

<div class="about-page">
    <div class="about-container">
        <div class="about-header">
            <h1 class="about-main-title">ABOUT US</h1>
            <h2 class="about-clinic-title">JVALERA DENTAL CLINIC</h2>
        </div>

        <div class="about-content-grid">
            <div class="about-text-section">
                <p class="about-description">
                    We believe in creating smiles that last a lifetime. Located in the heart of Gen. T. De Leon Valenzuela City, our clinic is a place where your comfort and well-being are our top priorities. Our friendly and skilled team takes the time to understand your individual needs and concerns, offering gentle and effective dental care tailored just for you. We're more than just a dental clinic; we're your partners in achieving optimal oral health and a confident smile.
                </p>
            </div>

            <div class="about-image-section">
                <div class="dentist-card">
                    <div class="dentist-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h3 class="dentist-name">Dr. JValera</h3>
                    <p class="dentist-role">Lead Dentist</p>
                </div>
            </div>
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
                    width="100%"
                    height="450"
                    style="border:0; border-radius: 12px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</div>

@endsection
