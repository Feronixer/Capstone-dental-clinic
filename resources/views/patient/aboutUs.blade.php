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

.about-image-section {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
    color: #3b82f6;
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

/* Development Team Section */
.team-section {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 2px solid #e0e0e0;
    background: linear-gradient(to bottom, #f0f9ff 0%, #e0f2fe 100%);
    padding: 4rem 2rem;
    border-radius: 16px;
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
}

.team-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-top: 4px solid #3b82f6;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.team-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
}

.team-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2);
}

.team-image-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
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
}

.team-role {
    font-size: 1rem;
    font-weight: 600;
    color: #3b82f6;
    margin-bottom: 1rem;
    text-align: center;
}

.team-separator {
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
    margin: 1rem 0;
}

.team-description {
    font-size: 0.95rem;
    line-height: 1.7;
    color: #64748b;
    text-align: center;
    flex-grow: 1;
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

    .features-grid {
        grid-template-columns: 1fr;
        gap: 0.625rem;
    }

    .feature-card {
        padding: 0.625rem;
    }

    .feature-icon-wrapper {
        width: 32px;
        height: 32px;
        margin-bottom: 0.5rem;
    }

    .feature-icon-wrapper i {
        font-size: 1rem;
    }

    .feature-title {
        font-size: 0.8rem;
    }

    .feature-description {
        font-size: 0.7rem;
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

    .team-section {
        padding: 3rem 1.5rem;
    }
}

@media (max-width: 768px) {
    .team-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
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
    background: linear-gradient(135deg, #60a5fa 0%, #2dd4bf 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
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

/* Image Section Dark Mode - Blue background */
[data-theme="dark"] .about-image-section {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;
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

/* Development Team Section Dark Mode */
[data-theme="dark"] .team-section {
    background: linear-gradient(to bottom, #1e293b 0%, #0f172a 100%) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
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

[data-theme="dark"] .team-card {
    background: var(--dm-card-bg, #1e293b) !important;
    border-top-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .team-card::before {
    background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%) !important;
}

[data-theme="dark"] .team-card:hover {
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

[data-theme="dark"] .team-separator {
    background: linear-gradient(90deg, transparent 0%, #334155 50%, transparent 100%) !important;
}

[data-theme="dark"] .team-description {
    color: var(--dm-text-muted, #94a3b8) !important;
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

                <!-- Features Highlight Section -->
                <div class="features-section">
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3 class="feature-title">Expert Dental Team</h3>
                            <p class="feature-description">Our skilled professionals are dedicated to providing the highest quality dental care.</p>
                        </div>

                        <div class="feature-card">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-cpu-fill"></i>
                            </div>
                            <h3 class="feature-title">Advanced Technology</h3>
                            <p class="feature-description">We utilize the latest dental technology for precise diagnoses and effective treatments.</p>
                        </div>

                        <div class="feature-card">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-heart-pulse-fill"></i>
                            </div>
                            <h3 class="feature-title">Patient-Centered Care</h3>
                            <p class="feature-description">Your comfort and satisfaction are at the heart of everything we do.</p>
                        </div>

                        <div class="feature-card">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <h3 class="feature-title">Sterile Environment</h3>
                            <p class="feature-description">We maintain the highest standards of cleanliness and safety for all our patients.</p>
                        </div>
                    </div>
                </div>
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

        <!-- Meet Our Development Team Section -->
        <div class="team-section">
            <div class="team-badge">
                <i class="bi bi-people-fill"></i>
                <span>Development Team</span>
            </div>
            <h2 class="team-title">Meet Our Development Team</h2>
            <p class="team-subtitle">The talented individuals behind the development of this dental clinic management system.</p>

            <div class="team-grid">
                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="{{ asset('images/dev1.png') }}" alt="Josh Andrei D. Castillo" class="team-image">
                    </div>
                    <h3 class="team-name">Josh Andrei D. Castillo</h3>
                    <p class="team-role">Project Manager/Coordinator</p>
                    <div class="team-separator"></div>
                    <p class="team-description">Responsible for the overall implementation, execution, and coordination of the group. Ensures that the project is on track and that the team is working towards the same goals.</p>
                </div>

                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="{{ asset('images/dev2.png') }}" alt="Angel Cuadernal" class="team-image">
                    </div>
                    <h3 class="team-name">Angel Cuadernal</h3>
                    <p class="team-role">Technical Lead</p>
                    <div class="team-separator"></div>
                    <p class="team-description">Responsible in managing group in the aspect of software development, data analysis, and meeting other technical deliverables. Ensures that the system is developed in a way that is efficient and effective.</p>
                </div>

                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="{{ asset('images/dev3.png') }}" alt="John Roy D. Lalantacon" class="team-image">
                    </div>
                    <h3 class="team-name">John Roy D. Lalantacon</h3>
                    <p class="team-role">Design Lead</p>
                    <div class="team-separator"></div>
                    <p class="team-description">Responsible in front-end development and the visual or creative aspect of the project. Ensures that projects visual design is visually appealing, easy to use, and aligns with the target audience.</p>
                </div>

                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="{{ asset('images/dev4.png') }}" alt="Aleck Joy G. Carpio" class="team-image">
                    </div>
                    <h3 class="team-name">Aleck Joy G. Carpio</h3>
                    <p class="team-role">Communication and Documentation Lead</p>
                    <div class="team-separator"></div>
                    <p class="team-description">Responsible in maintaining project documentation and other important records. Coordinating with technical and research advisers. Ensure Manuscript is aligned with the actual output to be developed.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
