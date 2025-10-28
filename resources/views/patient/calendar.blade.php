@extends('layout.patient.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/patient-calendar.css') }}">

<div class="calendar-container">
    <!-- Header Section -->
    <div class="calendar-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">My Appointments</h1>
                <p class="page-subtitle">View and manage your dental appointments</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="calendar-layout">
        <!-- Sidebar -->
        <aside class="calendar-sidebar">
            <!-- Mini Calendar -->
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="bi bi-calendar3 me-2"></i>Quick Calendar
                </h3>
                <div class="mini-calendar-nav">
                    <button id="miniCalPrev" class="nav-btn">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <span id="miniCalMonthYear" class="month-display">April 2025</span>
                    <button id="miniCalNext" class="nav-btn">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <div id="miniCalendarGrid" class="mini-calendar"></div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="bi bi-clock-history me-2"></i>Upcoming
                </h3>
                <div id="upcomingAppointments" class="upcoming-list">
                    <div class="upcoming-item">
                        <div class="upcoming-date">
                            <span class="date-day">15</span>
                            <span class="date-month">Apr</span>
                        </div>
                        <div class="upcoming-info">
                            <div class="upcoming-title">Teeth Cleaning</div>
                            <div class="upcoming-time">
                                <i class="bi bi-clock me-1"></i>10:00 AM
                            </div>
                        </div>
                        <span class="status-badge confirmed">Confirmed</span>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="bi bi-info-circle me-2"></i>Status Legend
                </h3>
                <div class="legend-list">
                    <div class="legend-item">
                        <span class="legend-dot pending"></span>
                        <span class="legend-text">Pending</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot confirmed"></span>
                        <span class="legend-text">Confirmed</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot completed"></span>
                        <span class="legend-text">Completed</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot cancelled"></span>
                        <span class="legend-text">Cancelled</span>
                    </div>
                </div>
        </div>
    </aside>

        <!-- Main Calendar -->
        <main class="calendar-main">
            <div class="calendar-controls">
                <div class="view-controls">
                    <button class="view-btn active" data-view="month">
                        <i class="bi bi-calendar-month"></i> Month
                    </button>
                    <button class="view-btn" data-view="week">
                        <i class="bi bi-calendar-week"></i> Week
                    </button>
                    <button class="view-btn" data-view="day">
                        <i class="bi bi-calendar-day"></i> Day
                    </button>
                </div>

                <div class="calendar-nav">
                    <button id="prevPeriod" class="nav-btn">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button id="todayBtn" class="btn-today">Today</button>
                    <button id="nextPeriod" class="nav-btn">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="calendar-wrapper">
                <h2 id="currentPeriodDisplay" class="period-title">April 2025</h2>
                <div id="calendarContent" class="calendar-content">
                    <!-- Calendar will be rendered here -->
                </div>
            </div>
        </main>
    </div>

    <!-- Appointment Form Section -->
    <div class="appointment-form-section">
        <div class="form-toggle-buttons">
            <button class="toggle-btn active" id="emergencyBtn" onclick="toggleAppointmentType('emergency')">
                Appoint Emergency Walkins?
            </button>
            <button class="toggle-btn" id="rescheduleBtn" onclick="toggleAppointmentType('reschedule')">
                Request for Reschedule?
            </button>
        </div>

        <form id="appointmentForm" class="appointment-form">
            <div class="form-group">
                <label for="reason" class="form-label">State your Reason:</label>
                <textarea id="reason" name="reason" class="form-textarea" rows="3" placeholder="Enter your reason here..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="appointmentDate" class="form-label">Select Date:</label>
                    <div class="input-with-icon">
                        <input type="date" id="appointmentDate" name="date" class="form-input" required>
                        <i class="bi bi-calendar3"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="appointmentTime" class="form-label">Select Time:</label>
                    <div class="input-with-icon">
                        <input type="time" id="appointmentTime" name="time" class="form-input" required>
                        <i class="bi bi-clock"></i>
                    </div>
                </div>

                <div class="form-group submit-group">
                    <button type="submit" class="btn-submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Appointment Form Section */
.appointment-form-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-top: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.form-toggle-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.toggle-btn {
    padding: 0.875rem 2rem;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    color: #64748b;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    bottom: -2px;
}

.toggle-btn:hover {
    color: #2196F3;
}

.toggle-btn.active {
    color: #2196F3;
    border-bottom-color: #2196F3;
}

.appointment-form {
    padding: 1rem 0;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 500;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-textarea {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    font-family: inherit;
    resize: vertical;
    transition: all 0.3s;
}

.form-textarea:focus {
    outline: none;
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 1.5rem;
    align-items: end;
}

.input-with-icon {
    position: relative;
}

.input-with-icon input {
    width: 100%;
    padding: 0.875rem 3rem 0.875rem 0.875rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.input-with-icon i {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 1.25rem;
    pointer-events: none;
}

.input-with-icon input:focus {
    outline: none;
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.input-with-icon input:focus + i {
    color: #2196F3;
}

.submit-group {
    margin-bottom: 0;
}

.btn-submit {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    border: none;
    padding: 0.875rem 2.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
		cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
}

.btn-submit:active {
    transform: translateY(0);
}

@media (max-width: 992px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .form-toggle-buttons {
        flex-direction: column;
        gap: 0;
    }

    .toggle-btn {
        border-bottom: none;
        border-left: 3px solid transparent;
        text-align: left;
        padding: 1rem 1.5rem;
        bottom: 0;
    }

    .toggle-btn.active {
        border-bottom: none;
        border-left-color: #2196F3;
        background: rgba(33, 150, 243, 0.05);
    }
}
</style>

<script>
let appointmentType = 'emergency';

function toggleAppointmentType(type) {
    appointmentType = type;

    // Update button states
    document.getElementById('emergencyBtn').classList.toggle('active', type === 'emergency');
    document.getElementById('rescheduleBtn').classList.toggle('active', type === 'reschedule');
}

// Form submission handler
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        type: appointmentType,
        reason: document.getElementById('reason').value,
        date: document.getElementById('appointmentDate').value,
        time: document.getElementById('appointmentTime').value
    };

    console.log('Appointment Request:', formData);

    // TODO: Send to backend API
    alert(`${appointmentType === 'emergency' ? 'Emergency Walk-in' : 'Reschedule'} request submitted!\n\nDate: ${formData.date}\nTime: ${formData.time}\nReason: ${formData.reason}`);

    // Reset form
    this.reset();
});
</script>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header gradient-header">
                <h5 class="modal-title text-white">
                    <i class="bi bi-calendar-check me-2"></i>Appointment Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="appointmentDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
                    </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
                <button type="button" class="btn btn-warning" id="rescheduleBtn">
                    <i class="bi bi-calendar3 me-1"></i>Reschedule
                </button>
                <button type="button" class="btn btn-danger" id="cancelBtn">
                    <i class="bi bi-x-octagon me-1"></i>Cancel Appointment
                </button>
            </div>
        </div>
</div>
</div>

<!-- Rate Service Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header gradient-header">
                <h5 class="modal-title text-white">
                    <i class="bi bi-star me-2"></i>Rate Our Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-5">
                <p class="mb-4">How was your experience?</p>
                <div class="star-rating mb-4">
                    <i class="bi bi-star star" data-rating="1"></i>
                    <i class="bi bi-star star" data-rating="2"></i>
                    <i class="bi bi-star star" data-rating="3"></i>
                    <i class="bi bi-star star" data-rating="4"></i>
                    <i class="bi bi-star star" data-rating="5"></i>
                </div>
                <input type="hidden" id="selectedRating" value="0">
                <button type="button" class="btn btn-primary btn-lg" id="submitRatingBtn" disabled>
                    <i class="bi bi-send me-2"></i>Submit Rating
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/patient-calendar.js') }}"></script>
@endsection
