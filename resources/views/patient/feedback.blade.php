@extends('layout.patient.app')
@section('content')

<style>
/* ========================================
   SCROLL REVEAL ANIMATIONS
   ======================================== */
/* Prevent overflow from reveal animations */
html, body {
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

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5 reveal-element reveal-slide-up">
                <h1 class="fw-bold mb-3">Service Feedback</h1>
                <p class="text-muted">Help us improve our dental services by sharing your experience</p>
            </div>

            <!-- Debug Info Card -->
            <div class="card border-0 shadow-sm mb-4 reveal-element reveal-fade" id="debugCard">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-info-circle me-2 text-info"></i>Appointment Status
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="loadDebugInfo()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                    <div id="debugInfo">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <p class="text-muted mt-2 mb-0">Loading appointment information...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Cards -->
            <div class="row g-4">
                <!-- Give Feedback Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 reveal-element reveal-fade">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-star-fill text-primary" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h3 class="h5 fw-bold mb-3">Rate Your Experience</h3>
                            <p class="text-muted mb-4">Share your thoughts about your recent appointment</p>
                            <a href="{{ route('patient-dashboard') }}#feedback" class="btn btn-primary">
                                <i class="bi bi-star me-2"></i>Give Feedback
                            </a>
                        </div>
                    </div>
                </div>

                <!-- View History Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 reveal-element reveal-fade reveal-delay-1">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-clock-history text-success" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h3 class="h5 fw-bold mb-3">Feedback History</h3>
                            <p class="text-muted mb-4">View all your previous ratings and comments</p>
                            <a href="{{ route('patient-dashboard') }}#feedback" class="btn btn-success">
                                <i class="bi bi-list-ul me-2"></i>View History
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="card border-0 bg-light mt-4 reveal-element reveal-slide-up">
                <div class="card-body p-4">
                    <h4 class="h6 fw-bold mb-3">
                        <i class="bi bi-info-circle me-2"></i>About Feedback Indicators
                    </h4>
                    <ul class="mb-0 text-muted">
                        <li><strong>Red Badge:</strong> Shows the number of completed appointments you haven't rated yet</li>
                        <li><strong>Yellow Banner:</strong> Appears when you have pending reviews</li>
                        <li>Indicators only appear when you have <strong>completed</strong> appointments without ratings</li>
                        <li>Ratings are on a scale of 1 to 5 stars</li>
                        <li>Your feedback helps us provide better dental care</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDebugInfo();
});

function loadDebugInfo() {
    const debugDiv = document.getElementById('debugInfo');
    debugDiv.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <p class="text-muted mt-2 mb-0">Loading...</p>
        </div>
    `;

    fetch('/patient/feedback/debug')
        .then(response => response.json())
        .then(data => {
            console.log('Debug data:', data);

            let html = '<div class="row g-3 mb-3">';
            html += `
                <div class="col-md-4">
                    <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                        <h3 class="fw-bold mb-0 text-primary">${data.total_appointments}</h3>
                        <small class="text-muted">Total Appointments</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                        <h3 class="fw-bold mb-0 text-success">${data.completed_count}</h3>
                        <small class="text-muted">Completed</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                        <h3 class="fw-bold mb-0 text-warning">${data.unrated_count}</h3>
                        <small class="text-muted">Need Feedback</small>
                    </div>
                </div>
            </div>`;

            if (data.unrated_count > 0) {
                html += `
                    <div class="alert alert-success border-0">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Great!</strong> You should see ${data.unrated_count} feedback indicator(s) on your dashboard.
                    </div>
                `;
            } else if (data.completed_count > 0) {
                html += `
                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        You've already rated all your completed appointments. Thank you!
                    </div>
                `;
            } else if (data.total_appointments > 0) {
                html += `
                    <div class="alert alert-warning border-0">
                        <i class="bi bi-clock me-2"></i>
                        You have appointments, but none are marked as "Completed" yet.
                        Indicators will appear once your appointments are completed.
                    </div>
                `;
            } else {
                html += `
                    <div class="alert alert-secondary border-0">
                        <i class="bi bi-calendar-x me-2"></i>
                        You don't have any appointments yet. Book an appointment to get started!
                    </div>
                `;
            }

            if (data.appointments.length > 0) {
                html += '<hr class="my-3"><h6 class="fw-bold mb-3">Your Appointments:</h6>';
                html += '<div class="table-responsive"><table class="table table-sm">';
                html += '<thead><tr><th>Date</th><th>Service</th><th>Status</th><th>Feedback</th></tr></thead><tbody>';

                data.appointments.forEach(apt => {
                    const statusBadge = {
                        'Pending': 'bg-warning',
                        'Confirmed': 'bg-info',
                        'Completed': 'bg-success',
                        'Cancelled': 'bg-danger'
                    }[apt.status] || 'bg-secondary';

                    const feedbackIcon = apt.has_feedback
                        ? '<i class="bi bi-star-fill text-warning"></i>'
                        : '<i class="bi bi-star text-muted"></i>';

                    html += `
                        <tr>
                            <td><small>${apt.date}</small></td>
                            <td><small>${apt.service_name}</small></td>
                            <td><span class="badge ${statusBadge}">${apt.status}</span></td>
                            <td>${feedbackIcon}</td>
                        </tr>
                    `;
                });

                html += '</tbody></table></div>';
            }

            debugDiv.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            debugDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Error loading appointment information. Please check the console.
                </div>
            `;
        });
}
</script>

@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
@include('patient.components.chatbot')
@endif

<script>
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

@endsection
