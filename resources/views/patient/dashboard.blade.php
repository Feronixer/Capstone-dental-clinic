@extends('layout.patient.app')
@section('content')

<style>
    .main-wrapper {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        min-height: 80vh;
        padding: 0;
    }

    /* Hero Section */
    .hero-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 3rem 3rem;
        gap: 3rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .hero-content {
        flex: 1;
        max-width: 600px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(33, 150, 243, 0.1);
        color: #2196F3;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: #263238;
        line-height: 1.2;
        margin-bottom: 1.5rem;
    }

    .hero-title .highlight {
        color: #2196F3;
    }

    .hero-description {
        font-size: 1.1rem;
        color: #546e7a;
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    /* Hero Card */
    .hero-card {
        flex: 1;
        max-width: 550px;
        position: relative;
    }

    .main-card {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border-radius: 30px;
        padding: 3rem;
        min-height: 450px;
        box-shadow: 0 20px 60px rgba(33, 150, 243, 0.3);
        position: relative;
        overflow: hidden;
    }

    .main-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .card-title {
        color: white;
        font-size: 1.4rem;
        font-weight: 700;
        text-align: center;
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
    }

    .feature-card {
        position: absolute;
        background: white;
        border-radius: 20px;
        padding: 1.3rem 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        max-width: 250px;
        z-index: 10;
    }

    .feature-card.top {
        top: 2rem;
        right: -2rem;
    }

    .feature-card.bottom {
        bottom: 8rem;
        right: -2rem;
    }

    .feature-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2196F3;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .feature-text h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #263238;
        margin-bottom: 0.3rem;
    }

    .feature-text p {
        font-size: 0.85rem;
        color: #78909c;
        line-height: 1.4;
    }

    /* Services Section */
    .services-section {
        padding: 5rem 3rem;
        background: white;
    }

    .services-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .services-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #263238;
        text-align: center;
        margin-bottom: 1rem;
        letter-spacing: 1px;
    }

    .services-description {
        text-align: center;
        color: #546e7a;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 3rem;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .service-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(33, 150, 243, 0.2);
    }

    .service-icon-box {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        padding: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 140px;
    }

    .service-icon-box i {
        font-size: 3rem;
        color: white;
    }

    .service-content {
        padding: 1.5rem;
        background: white;
    }

    .service-content h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #263238;
        margin-bottom: 0.8rem;
    }

    .service-content p {
        font-size: 0.9rem;
        color: #78909c;
        line-height: 1.6;
    }

    @media (max-width: 1024px) {
        .hero-section {
            flex-direction: column;
            padding: 2rem;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .feature-card {
            position: relative;
            right: auto;
            top: auto;
            bottom: auto;
            margin-bottom: 1rem;
        }

        .services-section {
            padding: 4rem 2rem;
        }

        .services-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .services-section {
            padding: 3rem 1rem;
        }

        .services-title {
            font-size: 2rem;
        }

        .services-description {
            font-size: 0.9rem;
        }

        .services-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .service-icon-box {
            padding: 2rem;
            height: 120px;
        }

        .service-icon-box i {
            font-size: 2.5rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <!-- Left Content -->
    <div class="hero-content">
        <div class="badge">
            <i class="bi bi-stars"></i>
            Welcome Back, {{ auth()->user()->name ?? 'Patient' }}!
        </div>

        <h1 class="hero-title">
            Have confidence<br>in your <span class="highlight">SMILE</span> in<br>no time!
        </h1>

        <p class="hero-description">
            Experience world-class dental care with cutting-edge technology and a compassionate team dedicated to your oral health and beautiful smile.
        </p>
    </div>

    <!-- Right Card -->
    <div class="hero-card">
        <div class="main-card">
            <div class="feature-card top">
                <div class="feature-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="feature-text">
                    <h4>Pain-Free</h4>
                    <p>Advanced anesthesia</p>
                </div>
            </div>

            <div class="feature-card bottom">
                <div class="feature-icon">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
                <div class="feature-text">
                    <h4>Sterile</h4>
                    <p>Highest safety standards</p>
                </div>
            </div>

            <h2 class="card-title">Advanced Dental Clinic Environment</h2>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <div class="services-container">
        <h2 class="services-title">OUR SERVICES</h2>
        <p class="services-description">
            We offer a comprehensive range of premium dental services using the latest<br>
            technology and techniques to ensure optimal oral health and beautiful smiles.
        </p>

        <div class="services-grid">
            <!-- Service Card 1 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="service-content">
                    <h3>Cosmetic Dentistry</h3>
                    <p>Teeth whitening, veneers, bonding, and smile makeovers to transform your smile and confidence.</p>
                </div>
            </div>

            <!-- Service Card 2 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <div class="service-content">
                    <h3>Laser Dentistry</h3>
                    <p>Minimally invasive laser treatments for precise care and faster recovery.</p>
                </div>
            </div>

            <!-- Service Card 3 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-bandaid-fill"></i>
                </div>
                <div class="service-content">
                    <h3>Oral Surgery</h3>
                    <p>Expert surgical care including wisdom teeth removal and dental implants.</p>
                </div>
            </div>

            <!-- Service Card 4 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                </div>
                <div class="service-content">
                    <h3>Periodontics</h3>
                    <p>Specialized care for gums and supporting structures for optimal oral health.</p>
                </div>
            </div>

            <!-- Service Card 5 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-clipboard2-check-fill"></i>
                </div>
                <div class="service-content">
                    <h3>Restoration & Filling</h3>
                    <p>High quality dental fillings and restorations to repair damaged teeth.</p>
                </div>
            </div>

            <!-- Service Card 6 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-capsule-pill"></i>
                </div>
                <div class="service-content">
                    <h3>Tooth Extraction</h3>
                    <p>Safe and comfortable tooth removal procedures.</p>
                </div>
            </div>

            <!-- Service Card 7 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="service-content">
                    <h3>Root Canal Treatment</h3>
                    <p>Advanced root canal therapy to save infected teeth.</p>
                </div>
            </div>

            <!-- Service Card 8 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-gem"></i>
                </div>
                <div class="service-content">
                    <h3>Dental Crowns</h3>
                    <p>Various crown options including porcelain, Emax, and zirconia for durable restoration.</p>
                </div>
            </div>

            <!-- Service Card 9 -->
            <div class="service-card">
                <div class="service-icon-box">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>
                <div class="service-content">
                    <h3>Dentures</h3>
                    <p>Custom-fit flexible and traditional dentures for natural looking results.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feedback Section -->
<section class="feedback-section" id="feedback">
    <div class="feedback-container">
        <h2 class="feedback-title">
            Rate Your Experience
            <span class="title-badge" id="titleFeedbackBadge" style="display: none;">
                <i class="bi bi-exclamation-circle"></i> You have pending reviews
            </span>
        </h2>
        <p class="feedback-description">
            Help us improve our services by sharing your feedback
        </p>

        <div class="feedback-cards">
            <!-- Give Feedback Card -->
            <div class="feedback-action-card" id="giveFeedbackCard">
                <div class="feedback-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h3>Give Feedback</h3>
                <p>Rate your recent appointment</p>
                <button class="feedback-btn" onclick="openFeedbackModal()">
                    <i class="bi bi-star me-2"></i>Rate Now
                </button>
                <span class="pending-count" id="pendingFeedbackCount" style="display: none;">0</span>
            </div>

            <!-- View History Card -->
            <div class="feedback-action-card" id="viewHistoryCard">
                <div class="feedback-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3>Feedback History</h3>
                <p>View your past ratings</p>
                <button class="feedback-btn feedback-btn-secondary" onclick="openHistoryModal()">
                    <i class="bi bi-list-ul me-2"></i>View History
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rate Your Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="feedbackForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Appointment</label>
                        <select class="form-select" id="appointmentSelect">
                            <option value="">Loading appointments...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Rating</label>
                        <div class="star-rating" id="starRating">
                            <i class="bi bi-star" data-rating="1"></i>
                            <i class="bi bi-star" data-rating="2"></i>
                            <i class="bi bi-star" data-rating="3"></i>
                            <i class="bi bi-star" data-rating="4"></i>
                            <i class="bi bi-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" id="ratingValue" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Comments (Optional)</label>
                        <textarea class="form-control" id="feedbackComment" rows="3"
                            placeholder="Share your experience with us..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" onclick="submitFeedback()">
                            <i class="bi bi-send me-2"></i>Submit Feedback
                        </button>
                    </div>
                </div>

                <div id="noAppointmentsMessage" style="display: none;" class="text-center py-4">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="text-muted mt-3">No completed appointments to rate</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Feedback History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="feedbackHistory">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden;">
            <div class="modal-body p-0">
                <div style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); padding: 2.5rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: white; border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        <i class="bi bi-check-circle-fill" style="color: #4caf50; font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Success!</h4>
                    <p class="text-white mb-4" id="successMessage">Thank you for your feedback!</p>
                    <button type="button" class="btn btn-light fw-bold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 25px;">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden;">
            <div class="modal-body p-0">
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 2.5rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: white; border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        <i class="bi bi-exclamation-circle-fill" style="color: #ef4444; font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Oops!</h4>
                    <p class="text-white mb-4" id="errorMessage">Something went wrong</p>
                    <button type="button" class="btn btn-light fw-bold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 25px;">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Feedback Section */
    .feedback-section {
        padding: 4rem 3rem;
        background: #f8f9fa;
    }

    .feedback-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .feedback-title {
        font-size: 2rem;
        font-weight: 800;
        color: #263238;
        text-align: center;
        margin-bottom: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }

    .title-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        padding: 0.5rem 1.25rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
        animation: gentle-pulse 2s ease-in-out infinite;
    }

    @keyframes gentle-pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.03);
        }
    }

    .feedback-description {
        text-align: center;
        color: #546e7a;
        font-size: 1rem;
        margin-bottom: 3rem;
    }

    .feedback-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .feedback-action-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
    }

    .feedback-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(33, 150, 243, 0.15);
    }

    .feedback-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .feedback-action-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #263238;
        margin-bottom: 0.5rem;
    }

    .feedback-action-card p {
        color: #78909c;
        margin-bottom: 1.5rem;
    }

    .feedback-btn {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .feedback-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .feedback-btn-secondary {
        background: linear-gradient(135deg, #546e7a 0%, #37474f 100%);
    }

    .pending-count {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        animation: pulse-badge 2s ease-in-out infinite;
        z-index: 10;
    }

    @keyframes pulse-badge {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.6);
        }
    }

    /* Star Rating */
    .star-rating {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        font-size: 2.5rem;
        margin: 1rem 0;
    }

    .star-rating i {
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star-rating i:hover,
    .star-rating i.active {
        color: #ffd700;
        transform: scale(1.1);
    }

    /* History Cards */
    .history-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .history-stars {
        color: #ffd700;
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .feedback-section {
            padding: 3rem 1rem;
        }

        .feedback-title {
            font-size: 1.75rem;
        }

        .feedback-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
let feedbackModal, historyModal, successModal, errorModal;
let completedAppointments = [];

document.addEventListener('DOMContentLoaded', function() {
    feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
    historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
    successModal = new bootstrap.Modal(document.getElementById('successModal'));
    errorModal = new bootstrap.Modal(document.getElementById('errorModal'));

    loadPendingFeedbackCount();
});

function openFeedbackModal() {
    fetch('/patient/feedback/appointments')
        .then(response => response.json())
        .then(data => {
            completedAppointments = data;
            const select = document.getElementById('appointmentSelect');

            if (data.length === 0) {
                document.getElementById('feedbackForm').style.display = 'none';
                document.getElementById('noAppointmentsMessage').style.display = 'block';
            } else {
                document.getElementById('feedbackForm').style.display = 'block';
                document.getElementById('noAppointmentsMessage').style.display = 'none';

                select.innerHTML = '<option value="">Select an appointment</option>';
                data.forEach(apt => {
                    select.innerHTML += `<option value="${apt.id}">${apt.service_name} - ${apt.date} at ${apt.time}</option>`;
                });
            }

            resetForm();
            feedbackModal.show();
        })
        .catch(error => {
            console.error('Error loading appointments:', error);
            showErrorModal('Failed to load appointments');
        });
}

function openHistoryModal() {
    historyModal.show();
    const historyDiv = document.getElementById('feedbackHistory');
    historyDiv.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';

    fetch('/patient/feedback/history')
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                historyDiv.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No feedback history yet</p>
                    </div>
                `;
            } else {
                historyDiv.innerHTML = '';
                data.forEach(feedback => {
                    const stars = '★'.repeat(feedback.rating) + '☆'.repeat(5 - feedback.rating);
                    historyDiv.innerHTML += `
                        <div class="history-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">${feedback.service_name}</h6>
                                    <small class="text-muted">Appointment: ${feedback.appointment_date}</small>
                                </div>
                                <div class="history-stars">${stars}</div>
                            </div>
                            ${feedback.comment ? `<p class="text-muted mb-0 mt-2">"${feedback.comment}"</p>` : ''}
                            <small class="text-muted d-block mt-2">Submitted: ${feedback.submitted_at}</small>
                        </div>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading history:', error);
            historyDiv.innerHTML = '<div class="alert alert-danger">Failed to load feedback history</div>';
        });
}

// Star rating functionality
document.getElementById('starRating').addEventListener('click', function(e) {
    if (e.target.classList.contains('bi-star') || e.target.classList.contains('bi-star-fill')) {
        const rating = parseInt(e.target.dataset.rating);
        document.getElementById('ratingValue').value = rating;

        const stars = document.querySelectorAll('#starRating i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill', 'active');
            } else {
                star.classList.remove('bi-star-fill', 'active');
                star.classList.add('bi-star');
            }
        });
    }
});

function submitFeedback() {
    const appointmentId = document.getElementById('appointmentSelect').value;
    const rating = document.getElementById('ratingValue').value;
    const comment = document.getElementById('feedbackComment').value;

    if (!appointmentId) {
        showErrorModal('Please select an appointment');
        return;
    }

    if (rating === '0') {
        showErrorModal('Please select a rating');
        return;
    }

    fetch('/patient/feedback/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            appointment_id: appointmentId,
            rating: parseInt(rating),
            feedback_comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            feedbackModal.hide();
            showSuccessModal(data.message);
            loadPendingFeedbackCount();
        } else {
            showErrorModal(data.message || 'Failed to submit feedback');
        }
    })
    .catch(error => {
        console.error('Error submitting feedback:', error);
        showErrorModal('Failed to submit feedback');
    });
}

function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    successModal.show();
}

function showErrorModal(message) {
    document.getElementById('errorMessage').textContent = message;
    errorModal.show();
}

function resetForm() {
    document.getElementById('appointmentSelect').value = '';
    document.getElementById('ratingValue').value = '0';
    document.getElementById('feedbackComment').value = '';

    const stars = document.querySelectorAll('#starRating i');
    stars.forEach(star => {
        star.classList.remove('bi-star-fill', 'active');
        star.classList.add('bi-star');
    });
}

function loadPendingFeedbackCount() {
    fetch('/patient/feedback/appointments')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Pending feedback appointments:', data);
            const count = data.length;
            const badge = document.getElementById('pendingFeedbackCount');
            const titleBadge = document.getElementById('titleFeedbackBadge');

            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                    console.log(`Showing badge with count: ${count}`);
                } else {
                    badge.style.display = 'none';
                    console.log('No pending feedback - hiding badge');
                }
            } else {
                console.error('Badge element not found');
            }

            // Show/hide title badge
            if (titleBadge) {
                if (count > 0) {
                    titleBadge.style.display = 'inline-flex';
                } else {
                    titleBadge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error loading pending feedback count:', error);
        });
}
</script>

@endsection
