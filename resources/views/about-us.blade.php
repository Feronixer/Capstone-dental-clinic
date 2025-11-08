@extends('layout.guest.app')
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

/* Chatbot */
    .chatbot-toggle-btn {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #2196F3;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(33,150,243,0.4);
        cursor: pointer;
        z-index: 1000;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        padding: 8px;
    }

    .chatbot-toggle-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 14px 36px rgba(33,150,243,0.45);
        background: #1976D2;
    }

    .chatbot-toggle-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .chatbot-widget {
        position: fixed;
        right: 24px;
        bottom: 92px;
        width: 340px;
        max-width: calc(100vw - 32px);
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        overflow: hidden;
        display: none;
        flex-direction: column;
        z-index: 1000;
    }

    .chatbot-widget.open { display: flex; }

    .chatbot-header {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: #fff;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chatbot-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
    }

    .chatbot-title .badge-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #A5D6A7;
        box-shadow: 0 0 0 4px rgba(165,214,167,0.25);
    }

    .chatbot-body {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 12px;
    }

    .chatbot-messages {
        height: 280px;
        overflow-y: auto;
        padding-right: 4px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        border-bottom: 1px solid #eef2f5;
    }

    .message {
        max-width: 82%;
        padding: 10px 12px;
        border-radius: 14px;
        font-size: 0.92rem;
        line-height: 1.6rem;
        word-wrap: break-word;
        word-break: break-word;
        white-space: pre-wrap;
    }

    .message.bot {
        background: #f5f9ff;
        color: #263238;
        border: 1px solid #e3f2fd;
        align-self: flex-start;
        text-align: left;
    }

    .message.bot .bullet-item {
        display: block;
        padding-left: 1.2em;
        text-indent: -1.2em;
        margin: 0.3em 0;
    }

    .message.bot .section-header {
        font-weight: 600;
        margin-top: 0.8em;
        margin-bottom: 0.3em;
        display: block;
    }

    .message.bot .section-header:first-child {
        margin-top: 0;
    }

    .message.user {
        background: #2196F3;
        color: #fff;
        align-self: flex-end;
    }

    .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chip {
        background: #e3f2fd;
        color: #1976D2;
        border: 1px solid #bbdefb;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.1s ease;
    }

    .chip:hover { background: #d2e9fb; transform: translateY(-1px); }

    .chatbot-input {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 0 0;
    }

    .chatbot-input input[type="text"] {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #dfe7ef;
        border-radius: 10px;
        outline: none;
        transition: border 0.2s ease, box-shadow 0.2s ease;
    }

    .chatbot-input input[type="text"]:focus {
        border: 1px solid #90caf9;
        box-shadow: 0 0 0 3px rgba(144,202,249,0.25);
    }

    .send-btn {
        background: #2196F3;
        color: #fff;
        border: none;
        padding: 10px 12px;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .send-btn:hover { background: #1976D2; }

    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 10px 14px;
        background: #E3F2FD;
        border-radius: 16px;
        margin-bottom: 8px;
        max-width: fit-content;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2196F3;
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.5;
        }
        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }

    .chatbot-tabs {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #eef2f5;
    }

    .chatbot-tab {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #dfe7ef;
        border-radius: 8px;
        background: #f8f9fa;
        color: #64748b;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .chatbot-tab:hover {
        background: #e9ecef;
        border-color: #90caf9;
    }

    .chatbot-tab.active {
        background: #2196F3;
        color: #fff;
        border-color: #2196F3;
    }

    .chatbot-tab.active:hover {
        background: #1976D2;
        border-color: #1976D2;
    }

    @media (max-width: 480px) {
        .chatbot-widget { right: 16px; left: 16px; width: auto; }
        .chatbot-messages { height: 240px; }
    }

@media (max-width: 768px) {
    .team-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
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

                <div class="team-card">
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

                <div class="team-card">
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

                <div class="team-card">
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
<!-- Chatbot Toggle Button -->
<div id="chatbot-toggle" class="chatbot-toggle-btn" aria-label="Open chat" title="Chat with us">
    <img src="{{ asset('images/chatbot-logo_3.png') }}" alt="ToothTalk Assistant">
</div>

<!-- Chatbot Widget -->
<div id="chatbot" class="chatbot-widget" role="dialog" aria-modal="false" aria-labelledby="chatbotTitle">
    <div class="chatbot-header">
        <div class="chatbot-title">
            <span class="badge-dot"></span>
            <span id="chatbotTitle">ToothTalk Assistant</span>
        </div>
        <button id="chatbot-close" class="send-btn" aria-label="Close chat" title="Close" style="background:#ffffff22;border:1px solid #ffffff33;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="chatbot-body">
        <div id="chatbot-messages" class="chatbot-messages" aria-live="polite"></div>
        <div class="chips" id="chatbot-chips"></div>
        <div class="chatbot-input" style="display:none;">
            <input id="chatbot-input" type="text" placeholder="Ask about services, hours, pricing..." autocomplete="off" />
            <button id="chatbot-send" class="send-btn" aria-label="Send message">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
        <div class="chatbot-tabs">
            <button id="tab-live-chat" class="chatbot-tab" data-tab="live-chat">
                <i class="bi bi-chat-dots me-1"></i> Live Chat
            </button>
            <button id="tab-faqs" class="chatbot-tab active" data-tab="faqs">
                <i class="bi bi-question-circle me-1"></i> FAQs
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const widget = document.getElementById('chatbot');
        const closeBtn = document.getElementById('chatbot-close');
        const messagesEl = document.getElementById('chatbot-messages');
        const inputEl = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send');
        const chipsEl = document.getElementById('chatbot-chips');
        const tabLiveChat = document.getElementById('tab-live-chat');
        const tabFaqs = document.getElementById('tab-faqs');
        const titleEl = document.getElementById('chatbotTitle');

        let currentMode = 'faqs';
        let conversationId = null;
        let pollingInterval = null;
        let lastMessageId = null;
        let faqInitialized = false;

        const quickIntents = {!! json_encode($chatbotSetting->quick_intents ?? []) !!};
        const faqRaw = @json($chatbotFaqs ?? []);
        const faqPairs = (faqRaw || []).map(function(f){
            return { q: (f.question || ''), a: (f.answer || '') };
        });

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function addMessage(text, sender) {
            const div = document.createElement('div');
            div.className = 'message ' + (sender === 'user' ? 'user' : 'bot');

            if (sender === 'bot') {
                let lines = text.split('\n');
                let formattedHTML = '';
                for (let i = 0; i < lines.length; i++) {
                    let line = lines[i].trim();
                    if (!line) continue;
                    if (line.endsWith(':')) {
                        formattedHTML += `<span class="section-header">${line}</span>`;
                    } else if (line.startsWith('•')) {
                        formattedHTML += `<span class="bullet-item">${line}</span>`;
                    } else {
                        formattedHTML += line;
                        if (i < lines.length - 1) {
                            formattedHTML += '<br>';
                        }
                    }
                }
                div.innerHTML = formattedHTML;
            } else {
                div.textContent = text;
            }

            messagesEl.appendChild(div);
            scrollToBottom();
        }

        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'typing-indicator';
            typingDiv.id = 'typing-indicator';
            typingDiv.innerHTML = '<span></span><span></span><span></span>';
            messagesEl.appendChild(typingDiv);
            scrollToBottom();
        }

        function hideTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        }

        function normalize(s) {
            return String(s)
                .toLowerCase()
                .replace(/&nbsp;/g, ' ')
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        const stopWords = new Set(['the','a','an','is','are','do','i','you','we','how','what','where','when','why','to','for','of','and','or','in','on','at','with','get','does','it','this','that','about']);

        function tokenize(text) {
            return normalize(text).split(' ').filter(w => w && !stopWords.has(w));
        }

        function overlapScore(aTokens, bTokens) {
            const a = new Set(aTokens);
            const b = new Set(bTokens);
            let inter = 0;
            a.forEach(t => { if (b.has(t)) inter++; });
            const union = a.size + b.size - inter || 1;
            return { inter, jaccard: inter / union };
        }

        const faqIndexed = (faqPairs || []).map(p => ({ q: p.q, a: p.a, tokens: tokenize(p.q || '') }));

        function getBotReply(query) {
            const q = normalize(query);
            const qLower = q.toLowerCase();
            const qTokens = tokenize(q);

            const helpPatterns = ['help', 'assist', 'support', 'can you', 'could you', 'need help', 'i need', 'i want', 'how can', 'what can'];
            const greetingPatterns = ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'greetings'];
            const servicePatterns = ['service', 'treatment', 'procedure', 'what do you', 'what services', 'offer', 'available'];
            const hoursPatterns = ['hours', 'open', 'close', 'time', 'when', 'what time', 'schedule', 'availability'];
            const pricePatterns = ['price', 'cost', 'fee', 'payment', 'how much', 'expensive', 'charge'];
            const appointmentPatterns = ['appointment', 'book', 'schedule', 'reserve', 'visit', 'see dentist'];

            if (helpPatterns.some(pattern => qLower.includes(pattern))) {
                return 'Of course! I\'m here to help you. You can ask me about:\n\n• Our clinic hours and availability\n• Services and treatments we offer\n• Appointment scheduling\n• Pricing information\n• General questions about dental care\n\nWhat would you like to know more about?';
            }

            if (greetingPatterns.some(pattern => qLower.includes(pattern))) {
                return 'Hello! Welcome to our dental clinic. How can I assist you today? You can ask about our services, hours, pricing, or schedule an appointment.';
            }

            if (servicePatterns.some(pattern => qLower.includes(pattern))) {
                return 'We offer a comprehensive range of dental services including:\n\n• General dentistry (cleanings, check-ups)\n• Cosmetic dentistry (whitening, veneers)\n• Orthodontics (braces, aligners)\n• Root canals and fillings\n• Crowns and bridges\n• Implants\n• Emergency dental care\n\nWould you like to know more about a specific service?';
            }

            if (hoursPatterns.some(pattern => qLower.includes(pattern))) {
                return 'Our clinic hours are:\n\n• Tuesday to Saturday: 11:00 AM to 6:00 PM\n• Sunday and Monday: Closed\n\nWe recommend scheduling an appointment in advance. Would you like to book one?';
            }

            if (pricePatterns.some(pattern => qLower.includes(pattern))) {
                return 'Pricing varies depending on the service and treatment needed. For specific pricing information, please contact our office or schedule a consultation. We\'d be happy to provide a detailed quote based on your needs.';
            }

            if (appointmentPatterns.some(pattern => qLower.includes(pattern))) {
                return 'You can schedule an appointment by:\n\n• Logging into your patient portal and using the calendar\n• Contacting us directly at (63)915 622 9695\n• Visiting our clinic at Policarpio St. Gen. T. de Leon Valenzuela City\n\nWould you like help with anything else?';
            }

            let best = { score: 0, inter: 0, a: null };
            for (const item of faqIndexed) {
                if (!item.tokens.length) continue;
                const { inter, jaccard } = overlapScore(qTokens, item.tokens);
                const score = inter >= 1 ? jaccard + 0.15 : jaccard;
                if (score > best.score) best = { score, inter, a: item.a };
            }
            if (best.a && (best.score >= 0.15 || best.inter >= 1)) return best.a;

            return 'I\'m here to help! You can ask me about:\n\n• Clinic hours and availability\n• Our dental services\n• Appointment scheduling\n• Pricing information\n• General questions\n\nOr feel free to browse our FAQs for more detailed information. What would you like to know?';
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        async function sendUserMessage(text) {
            if (!text.trim()) return;
            
            if (currentMode === 'live-chat' && conversationId) {
                await sendLiveMessage(text.trim());
                return;
            }
            
            if (currentMode === 'faqs') {
                addMessage(text.trim(), 'user');
                showTypingIndicator();
                const typingDelay = 1000 + Math.random() * 1000;
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(getBotReply(text), 'bot');
                }, typingDelay);
            } else {
                addMessage('Please wait for the chat to initialize...', 'bot');
            }
        }

        function renderChips() {
            chipsEl.innerHTML = '';
            quickIntents.forEach(intent => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'chip';
                btn.textContent = intent.label;
                btn.addEventListener('click', () => {
                    if (currentMode === 'faqs') {
                        sendFaqMessage(intent.value);
                    } else {
                        sendUserMessage(intent.value);
                    }
                });
                chipsEl.appendChild(btn);
            });
        }

        function sendFaqMessage(text) {
            if (!text.trim()) return;
            addMessage(text.trim(), 'user');
            showTypingIndicator();
            const typingDelay = 1000 + Math.random() * 1000;
            setTimeout(() => {
                hideTypingIndicator();
                addMessage(getBotReply(text), 'bot');
            }, typingDelay);
        }

        async function checkAuth() {
            try {
                const response = await fetch('{{ route("chat.check-auth") }}');
                const data = await response.json();
                return data.authenticated;
            } catch (error) {
                return false;
            }
        }

        async function openChat() {
            widget.classList.add('open');
            widget.setAttribute('aria-hidden', 'false');
            
            if (!messagesEl.dataset.checked) {
                if (currentMode === 'faqs') {
                    chipsEl.style.display = 'flex';
                    chipsEl.innerHTML = '';
                    messagesEl.innerHTML = '';
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                        renderChips();
                        faqInitialized = true;
                    }, 800);
                } else if (currentMode === 'live-chat') {
                    chipsEl.style.display = 'none';
                    chipsEl.innerHTML = '';
                    const isAuth = await checkAuth();
                    if (isAuth) {
                        inputEl.placeholder = 'Type your message to staff...';
                        await initializeLiveChat();
                    } else {
                        messagesEl.innerHTML = '';
                        addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
                        const loginBtn = document.createElement('button');
                        loginBtn.className = 'chip';
                        loginBtn.textContent = 'Login to Chat with Staff';
                        loginBtn.style.background = '#0d6efd';
                        loginBtn.style.color = 'white';
                        loginBtn.style.marginTop = '10px';
                        loginBtn.style.width = '100%';
                        loginBtn.addEventListener('click', () => {
                            window.location.href = '{{ route("login") }}';
                        });
                        const loginContainer = document.createElement('div');
                        loginContainer.style.marginTop = '10px';
                        loginContainer.appendChild(loginBtn);
                        messagesEl.appendChild(loginContainer);
                        inputEl.disabled = true;
                        sendBtn.disabled = true;
                    }
                }
                messagesEl.dataset.checked = '1';
            }
            inputEl.focus();
        }

        async function initializeLiveChat() {
            try {
                const response = await fetch('{{ route("patient-chat.conversation") }}');
                const data = await response.json();
                conversationId = data.conversation_id;
                await loadMessages();
                startPolling();
            } catch (error) {
                console.error('Error initializing chat:', error);
            }
        }

        async function loadMessages() {
            if (!conversationId) return;
            try {
                const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                const data = await response.json();
                data.messages.forEach(msg => {
                    const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                    addMessage(msg.message, sender);
                    if (!lastMessageId || msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }
                });
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        function startPolling() {
            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(async () => {
                if (!conversationId) return;
                try {
                    const response = await fetch(`{{ route("patient-chat.messages") }}?conversation_id=${conversationId}`);
                    const data = await response.json();
                    data.messages.forEach(msg => {
                        if (msg.id > lastMessageId) {
                            const sender = msg.sender_type === 'patient' ? 'user' : msg.sender_type;
                            addMessage(msg.message, sender);
                            lastMessageId = msg.id;
                        }
                    });
                } catch (error) {
                    console.error('Error polling:', error);
                }
            }, 3000);
        }

        async function sendLiveMessage(text) {
            if (!conversationId) return;
            addMessage(text, 'user');
            inputEl.value = '';
            
            try {
                const response = await fetch('{{ route("patient-chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        conversation_id: conversationId,
                        message: text
                    })
                });
                const data = await response.json();
                if (data.success) {
                    lastMessageId = data.message.id;
                }
            } catch (error) {
                addMessage('Error sending message. Please try again.', 'bot');
            }
        }

        function closeChat() {
            widget.classList.remove('open');
            widget.setAttribute('aria-hidden', 'true');
            stopPolling();
        }

        function switchTab(mode) {
            currentMode = mode;
            tabLiveChat.classList.toggle('active', mode === 'live-chat');
            tabFaqs.classList.toggle('active', mode === 'faqs');
            
            const inputContainer = document.querySelector('.chatbot-input');
            
            if (mode === 'live-chat') {
                titleEl.textContent = 'Live Chat - Staff';
                inputEl.placeholder = 'Type your message to staff...';
                inputContainer.style.display = 'flex';
                chipsEl.style.display = 'none';
                chipsEl.innerHTML = '';
                stopPolling();
                checkAuth().then(isAuth => {
                    if (isAuth) {
                        messagesEl.innerHTML = '';
                        if (!conversationId) {
                            initializeLiveChat();
                        } else {
                            loadMessages();
                            startPolling();
                        }
                    } else {
                        messagesEl.innerHTML = '';
                        addMessage('To chat with our staff, please log in to your account. You can use the FAQ chatbot for general questions.', 'bot');
                        const loginBtn = document.createElement('button');
                        loginBtn.className = 'chip';
                        loginBtn.textContent = 'Login to Chat with Staff';
                        loginBtn.style.background = '#0d6efd';
                        loginBtn.style.color = 'white';
                        loginBtn.style.marginTop = '10px';
                        loginBtn.style.width = '100%';
                        loginBtn.addEventListener('click', () => {
                            window.location.href = '{{ route("login") }}';
                        });
                        const loginContainer = document.createElement('div');
                        loginContainer.style.marginTop = '10px';
                        loginContainer.appendChild(loginBtn);
                        messagesEl.appendChild(loginContainer);
                        inputEl.disabled = true;
                        sendBtn.disabled = true;
                    }
                });
            } else {
                titleEl.textContent = 'ToothTalk Assistant';
                inputEl.placeholder = 'Ask about services, hours, pricing...';
                inputContainer.style.display = 'none';
                chipsEl.style.display = 'flex';
                chipsEl.innerHTML = '';
                stopPolling();
                inputEl.disabled = false;
                sendBtn.disabled = false;
                messagesEl.innerHTML = '';
                showTypingIndicator();
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(@json($chatbotSetting->welcome_message ?: 'Welcome! How can I help today?'), 'bot');
                    renderChips();
                    faqInitialized = true;
                }, 800);
            }
        }

        toggleBtn.addEventListener('click', () => {
            if (widget.classList.contains('open')) closeChat(); else openChat();
        });
        closeBtn.addEventListener('click', closeChat);
        tabLiveChat.addEventListener('click', () => switchTab('live-chat'));
        tabFaqs.addEventListener('click', () => switchTab('faqs'));
        sendBtn.addEventListener('click', () => {
            const v = inputEl.value; 
            inputEl.value = ''; 
            sendUserMessage(v);
        });
        inputEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { 
                const v = inputEl.value; 
                inputEl.value = ''; 
                sendUserMessage(v); 
            }
        });
    })();
</script>
@endif

@endsection

