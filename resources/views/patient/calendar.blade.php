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
                    @forelse($upcomingAppointments as $appointment)
                        <div class="upcoming-item">
                            <div class="upcoming-date">
                                <span class="date-day">{{ $appointment->start_datetime->format('d') }}</span>
                                <span class="date-month">{{ $appointment->start_datetime->format('M') }}</span>
                            </div>
                            <div class="upcoming-info">
                                <div class="upcoming-title">{{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }}</div>
                                <div class="upcoming-time">
                                    <i class="bi bi-clock me-1"></i>{{ $appointment->start_datetime->format('g:i A') }}
                                </div>
                            </div>
                            <span class="status-badge {{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-calendar-x mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="bi bi-hourglass-split me-2"></i>Pending Requests
                </h3>
                <div id="pendingRequestsList" class="pending-requests-list">
                    @forelse($pendingRequests as $request)
                        <div class="pending-request-item">
                            <div class="pending-request-header">
                                <span class="request-type-badge {{ $request->request_type === 'walk-in' ? 'emergency' : $request->request_type }}">
                                    <i class="bi {{ $request->request_type === 'walk-in' ? 'bi-lightning-charge-fill' : 'bi-arrow-repeat' }}"></i>
                                    {{ $request->request_type === 'walk-in' ? 'Emergency' : 'Reschedule' }}
                                </span>
                            </div>
                            <div class="pending-request-info">
                                <div class="pending-request-service">
                                    <i class="bi bi-heart-pulse me-1"></i>
                                    {{ $request->service ? $request->service->service_name : $request->other_concern }}
                                </div>
                                <div class="pending-request-datetime">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $request->requested_datetime->format('M d, Y') }}
                                    <i class="bi bi-clock ms-2 me-1"></i>
                                    {{ $request->requested_datetime->format('g:i A') }}
                                </div>
                                <div class="pending-request-status">
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    {{ $request->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-check-circle mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">No pending requests</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Appointment History -->
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="bi bi-archive me-2"></i>History
                </h3>
                <div id="appointmentHistory" class="history-list">
                    @forelse($appointmentHistory as $appointment)
                        <div class="history-item">
                            <div class="history-date">
                                <span class="history-day">{{ $appointment->start_datetime->format('d') }}</span>
                                <span class="history-month">{{ $appointment->start_datetime->format('M') }}</span>
                                <span class="history-year">{{ $appointment->start_datetime->format('Y') }}</span>
                            </div>
                            <div class="history-info">
                                <div class="history-title">{{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }}</div>
                                <div class="history-time">
                                    <i class="bi bi-clock me-1"></i>{{ $appointment->start_datetime->format('g:i A') }}
                                </div>
                            </div>
                            <span class="status-badge history {{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-inbox mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">No appointment history</p>
                        </div>
                    @endforelse
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
                <div class="period-header">
                    <h2 id="currentPeriodDisplay" class="period-title">April 2025</h2>

                    <!-- Status Legend -->
                    <div class="legend-inline">
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
                        <div class="legend-item">
                            <span class="legend-dot blocked"></span>
                            <span class="legend-text">Missed</span>
                        </div>
                    </div>
                </div>
                <div id="calendarContent" class="calendar-content">
                    <!-- Calendar will be rendered here -->
                </div>
            </div>
        </main>
    </div>

    <!-- Appointment Form Section -->
    <div class="appointment-form-section">
        <div class="form-header">


        </div>

        <div class="form-toggle-buttons">
            <button class="toggle-btn active" id="emergencyBtn" onclick="toggleAppointmentType('emergency')">
                <div class="toggle-icon">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <div class="toggle-content">
                    <span class="toggle-title">Emergency Appointment</span>
                    <span class="toggle-desc">Urgent dental care needed </span>
                </div>
            </button>
            <button class="toggle-btn" id="rescheduleBtn" onclick="toggleAppointmentType('reschedule')">
                <div class="toggle-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="toggle-content">
                    <span class="toggle-title">Request Reschedule</span>
                    <span class="toggle-desc">Change existing appointment</span>
                </div>
            </button>
        </div>

        <form id="appointmentForm" class="appointment-form">
            <!-- Service Selection (only visible in emergency mode) -->
            <div class="form-group" id="serviceSelectionGroup">
                <label for="serviceSelect" class="form-label">
                    <i class="bi bi-heart-pulse-fill me-2"></i>Service to be Treated:
                </label>
                <select id="serviceSelect" name="service_id" class="form-select" required>
                    <option value="">-- Select a service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" data-duration="{{ $service->default_duration_minutes }}">{{ $service->service_name }}</option>
                    @endforeach
                    <option value="other" data-duration="30">Other</option>
                </select>
            </div>

            <!-- Other Concern Input (only visible when "Other" is selected) -->
            <div class="form-group" id="otherConcernGroup" style="display: none;">
                <label for="otherConcern" class="form-label">
                    <i class="bi bi-pencil-square me-2"></i>Service Name:
                </label>
                <input type="text" id="otherConcern" name="other_concern" class="form-input" placeholder="Enter the dental service you need...">
            </div>

            <!-- Appointment Selection (only visible in reschedule mode) -->
            <div class="form-group" id="appointmentSelectionGroup" style="display: none;">
                <label for="appointmentSelect" class="form-label">
                    <i class="bi bi-calendar-check me-2"></i>Select Appointment to Reschedule:
                </label>
                <select id="appointmentSelect" name="appointment_id" class="form-select">
                    <option value="">-- Select an appointment --</option>
                    @foreach($upcomingAppointments as $appointment)
                        @if(in_array($appointment->status, ['Pending', 'Confirmed']))
                            <option value="{{ $appointment->id }}"
                                    data-service="{{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }}"
                                    data-date="{{ $appointment->start_datetime->format('Y-m-d') }}"
                                    data-time="{{ $appointment->start_datetime->format('H:i') }}"
                                    data-datetime="{{ $appointment->start_datetime->format('F d, Y \a\t g:i A') }}"
                                    data-service-id="{{ $appointment->service_id ?? '' }}"
                                    data-duration="{{ $appointment->duration_minutes }}"
                                    data-reason-for-visit="{{ $appointment->reason_for_visit ?? '' }}">
                                {{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }} -
                                {{ $appointment->start_datetime->format('M d, Y') }} at
                                {{ $appointment->start_datetime->format('g:i A') }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <div id="selectedAppointmentInfo" class="appointment-info-box" style="display: none;">
                    <div class="info-header">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Selected Appointment Details</span>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <i class="bi bi-heart-pulse"></i>
                            <span id="infoService">-</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-calendar-event"></i>
                            <span id="infoDateTime">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="reason" class="form-label">
                    <i class="bi bi-chat-left-text me-2"></i>State your Reason:
                </label>
                <textarea id="reason" name="reason" class="form-textarea" rows="4" placeholder="Please provide a clear reason for your request. For example: 'I have a dental emergency with severe tooth pain' or 'I need to reschedule due to a work commitment...'" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="appointmentDate" class="form-label">
                        <i class="bi bi-calendar-event me-2"></i>Select Date:
                    </label>
                    <div class="input-with-icon">
                        <input type="date" id="appointmentDate" name="date" class="form-input" required>
                        <i class="bi bi-calendar3"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="appointmentTime" class="form-label">
                        <i class="bi bi-clock-history me-2"></i>Select Time:
                    </label>
                    <div class="input-with-icon">
                        <input type="time" id="appointmentTime" name="time" class="form-input" required>
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" id="cancelFormBtn" style="display: none;">
                    <i class="bi bi-x-circle me-2"></i>Cancel Request
                </button>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle me-2"></i>Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Appointment Form Section */
.appointment-form-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 20px;
    padding: 2.5rem;
    margin-top: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    border: 1px solid rgba(33, 150, 243, 0.1);
}

.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 3px solid #2196F3;
}

.form-main-title {
    font-size: 2rem;
    font-weight: 800;
    color: #2C3E50;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.form-main-title i {
    color: #2196F3;
    font-size: 2.2rem;
}

.form-subtitle {
    color: #64748b;
    font-size: 1rem;
    margin: 0;
}

.form-toggle-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.toggle-btn {
    background: white;
    border: 3px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 1rem;
		text-align: left;
		}

.toggle-btn:hover {
    border-color: #2196F3;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(33, 150, 243, 0.15);
}

.toggle-btn.active {
    border-color: #2196F3;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(25, 118, 210, 0.05) 100%);
    box-shadow: 0 8px 24px rgba(33, 150, 243, 0.2);
}

.toggle-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.toggle-btn.active .toggle-icon {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
}

.toggle-icon i {
    font-size: 1.5rem;
    color: #2196F3;
}

.toggle-btn.active .toggle-icon i {
    color: white;
}

.toggle-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.toggle-title {
    font-weight: 700;
    font-size: 1.05rem;
    color: #2C3E50;
}

.toggle-desc {
    font-size: 0.85rem;
    color: #64748b;
}

.appointment-form {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}

.form-group {
    margin-bottom: 1.75rem;
}

.form-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #2C3E50;
    margin-bottom: 0.75rem;
    font-size: 1rem;
}

.form-label i {
    color: #2196F3;
}

.form-textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    font-family: inherit;
    resize: vertical;
    transition: all 0.3s;
    background: #f8f9fa;
}

.form-textarea:hover {
    border-color: #cbd5e1;
    background: white;
}

.form-textarea:focus {
    outline: none;
    border-color: #2196F3;
    background: white;
    box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.input-with-icon {
    position: relative;
}

.form-select {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s;
    background: #f8f9fa;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 3rem;
}

.form-select:hover {
    border-color: #cbd5e1;
    background-color: white;
}

.form-select:focus {
    outline: none;
    border-color: #2196F3;
    background-color: white;
    box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
}

.form-input {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s;
    background: #f8f9fa;
}

.form-input:hover {
    border-color: #cbd5e1;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #2196F3;
    background: white;
    box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
}

.appointment-info-box {
    margin-top: 1rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 12px;
    border: 2px solid #2196F3;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.info-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #1565C0;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.info-header i {
    font-size: 1.1rem;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 8px;
    font-size: 0.9rem;
    color: #1e293b;
}

.info-item i {
    color: #2196F3;
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.info-item span {
    font-weight: 600;
}

.input-with-icon input {
    width: 100%;
    padding: 1rem 3rem 1rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s;
    background: #f8f9fa;
}

.input-with-icon input:hover {
    border-color: #cbd5e1;
    background: white;
}

.input-with-icon i {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 1.25rem;
    pointer-events: none;
    transition: all 0.3s;
}

.input-with-icon input:focus {
    outline: none;
    border-color: #2196F3;
    background: white;
    box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
}

.input-with-icon input:focus + i {
    color: #2196F3;
}

.form-actions {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-submit {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    border: none;
    padding: 1rem 3rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.05rem;
		cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(33, 150, 243, 0.35);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(33, 150, 243, 0.45);
}

.btn-submit:active {
    transform: translateY(-1px);
}

.btn-cancel {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.05rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(100, 116, 139, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cancel:hover {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(100, 116, 139, 0.4);
}

.btn-cancel:active {
    transform: translateY(-1px);
}

@media (max-width: 992px) {
    .form-toggle-buttons {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .appointment-form-section {
        padding: 1.5rem;
    }

    .appointment-form {
        padding: 1.5rem;
    }

    .form-main-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .toggle-btn {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }

    .form-actions {
        flex-direction: column;
        width: 100%;
    }

    .btn-submit,
    .btn-cancel {
        width: 100%;
        justify-content: center;
    }
}

/* Appointment Details Modal */
.appointment-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.detail-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.detail-card:hover {
    border-color: #2196F3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.1);
}

.detail-card.full-width {
    grid-column: 1 / -1;
}

.detail-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.detail-icon i {
    font-size: 1.5rem;
    color: white;
}

.detail-content {
    flex: 1;
}

.detail-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.4;
}

@media (max-width: 768px) {
    .appointment-details-grid {
        grid-template-columns: 1fr;
    }
}

/* Week View Styles */
.week-view {
    width: 100%;
    overflow-x: auto;
}

.week-header {
    display: grid;
    grid-template-columns: 100px repeat(7, 1fr);
    gap: 1px;
    background: #e2e8f0;
    border-radius: 12px 12px 0 0;
    overflow: hidden;
    position: sticky;
    top: 0;
    z-index: 10;
}

.time-column-header {
    background: #f8fafc;
    padding: 1rem;
    font-weight: 700;
    color: #64748b;
    text-align: center;
}

.week-day-header {
    background: #f8fafc;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s;
}

.week-day-header.today {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
}

.day-name {
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.week-day-header.today .day-name {
    color: white;
}

.day-date {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2196F3;
}

.week-day-header.today .day-date {
    color: white;
}

.week-body {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: #e2e8f0;
}

.week-row {
    display: grid;
    grid-template-columns: 100px repeat(7, 1fr);
    gap: 1px;
}

.time-slot {
    background: #f8fafc;
    padding: 1rem;
    font-weight: 600;
    color: #64748b;
    font-size: 0.85rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.week-cell {
    background: white;
    padding: 0.5rem;
    min-height: 60px;
    position: relative;
}

.week-appointment {
    background: #e3f2fd;
    border-left: 3px solid #2196F3;
    padding: 0.5rem;
    border-radius: 6px;
    margin-bottom: 0.25rem;
    cursor: pointer;
    transition: all 0.2s;
}

.week-appointment:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
}

.week-appointment.pending {
    background: #fff3e0;
    border-left-color: #ff9800;
}

.week-appointment.confirmed {
    background: #e8f5e9;
    border-left-color: #4caf50;
}

.week-appointment.completed {
    background: #f3e5f5;
    border-left-color: #9c27b0;
}

.week-appointment.cancelled {
    background: #ffebee;
    border-left-color: #f44336;
    opacity: 0.7;
}

.week-appointment.blocked {
    background: #f3f4f6;
    border-left-color: #6b7280;
    cursor: not-allowed;
}

.week-appointment.blocked:hover {
    transform: none;
    box-shadow: none;
}

.week-apt-time {
    font-size: 0.75rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.week-apt-title {
    font-size: 0.85rem;
    color: #64748b;
    line-height: 1.3;
}

/* Day View Styles */
.day-view {
    width: 100%;
}

.day-view-header {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 12px;
    margin-bottom: 1.5rem;
    color: white;
}

.day-view-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.day-view-date {
    font-size: 1.1rem;
    margin: 0;
    opacity: 0.9;
}

.day-view-body {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.day-time-row {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 1px;
    background: #e2e8f0;
}

.day-time-label {
    background: #f8fafc;
    padding: 1.5rem 1rem;
    font-weight: 700;
    color: #64748b;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.day-time-content {
    background: white;
    padding: 1rem;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.day-appointment {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-left: 4px solid #2196F3;
    padding: 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.day-appointment:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
}

.day-appointment.pending {
    background: linear-gradient(135deg, #fffbf0 0%, #fff3e0 100%);
    border-left-color: #ff9800;
}

.day-appointment.confirmed {
    background: linear-gradient(135deg, #f1f8f4 0%, #e8f5e9 100%);
    border-left-color: #4caf50;
}

.day-appointment.completed {
    background: linear-gradient(135deg, #faf5fc 0%, #f3e5f5 100%);
    border-left-color: #9c27b0;
}

.day-appointment.cancelled {
    background: linear-gradient(135deg, #fff5f5 0%, #ffebee 100%);
    border-left-color: #f44336;
    opacity: 0.8;
}

.day-appointment.blocked {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-left-color: #6b7280;
    cursor: not-allowed;
}

.day-appointment.blocked:hover {
    transform: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.day-apt-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.day-apt-time {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

.day-apt-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.day-apt-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.day-apt-badge.confirmed {
    background: #dbeafe;
    color: #1e40af;
}

.day-apt-badge.completed {
    background: #e9d5ff;
    color: #6b21a8;
}

.day-apt-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.day-apt-badge.blocked {
    background: #e5e7eb;
    color: #374151;
}

.day-apt-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.day-apt-notes {
    font-size: 0.9rem;
    color: #64748b;
    display: flex;
    align-items: center;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(0,0,0,0.05);
}

.day-empty-slot {
    color: #94a3b8;
    font-style: italic;
    text-align: center;
    padding: 1.5rem;
}

/* Responsive adjustments for week and day views */
@media (max-width: 768px) {
    .week-header, .week-row {
        grid-template-columns: 80px repeat(7, minmax(100px, 1fr));
    }

    .day-time-row {
        grid-template-columns: 100px 1fr;
    }

    .day-view-title {
        font-size: 1.5rem;
    }
}

/* Cancellation Modal Styles */
.cancel-icon-wrapper {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.cancel-icon-wrapper i {
    font-size: 2.5rem;
    color: #dc2626;
}

.cancel-details-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
}

.cancel-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.cancel-detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.cancel-detail-row:first-child {
    padding-top: 0;
}

.cancel-detail-label {
    font-weight: 600;
    color: #64748b;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

.cancel-detail-label i {
    color: #ef4444;
}

.cancel-detail-value {
    font-weight: 700;
    color: #1e293b;
    font-size: 1rem;
    text-align: right;
}

/* Modern Modal Styles */
.modern-modal {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    border: none;
}

.modern-modal .modal-header {
    padding: 1.5rem 2rem;
    border: none;
}

.modern-modal .modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.modern-modal .modal-body {
    background: #ffffff;
}

.modern-modal .modal-footer {
    border-top: 2px solid #e2e8f0;
    padding: 1.25rem 1.5rem;
}

.modern-modal .modal-footer .btn {
    padding: 0.65rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.modern-modal .modal-footer .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

.modern-modal .modal-footer .btn:active {
    transform: translateY(0);
}

/* Modal Animation */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    transform: translateY(-50px);
    opacity: 0;
}

.modal.show .modal-dialog {
    transform: translateY(0);
    opacity: 1;
}

/* ============================================
   DARK MODE STYLES FOR PATIENT CALENDAR
   ============================================ */

/* Appointment Form Section Dark Mode */
[data-theme="dark"] .appointment-form-section {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-bg-primary, #0f172a) 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5) !important;
}

[data-theme="dark"] .form-header {
    border-bottom-color: #3b82f6 !important;
}

[data-theme="dark"] .form-main-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-subtitle {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Toggle Buttons Dark Mode */
[data-theme="dark"] .toggle-btn {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .toggle-btn:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2) !important;
}

[data-theme="dark"] .toggle-btn.active {
    border-color: #3b82f6 !important;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
}

[data-theme="dark"] .toggle-icon {
    background: linear-gradient(135deg, var(--dm-bg-tertiary, #334155) 0%, var(--dm-bg-secondary, #1e293b) 100%) !important;
}

[data-theme="dark"] .toggle-btn.active .toggle-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

[data-theme="dark"] .toggle-icon i {
    color: #60a5fa !important;
}

[data-theme="dark"] .toggle-btn.active .toggle-icon i {
    color: white !important;
}

[data-theme="dark"] .toggle-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .toggle-desc {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Appointment Form Dark Mode */
[data-theme="dark"] .appointment-form {
    background: var(--dm-card-bg, #1e293b) !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .form-label {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-label i {
    color: #60a5fa !important;
}

[data-theme="dark"] .form-textarea,
[data-theme="dark"] .form-select,
[data-theme="dark"] .form-input {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-textarea:hover,
[data-theme="dark"] .form-select:hover,
[data-theme="dark"] .form-input:hover {
    border-color: #475569 !important;
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .form-textarea:focus,
[data-theme="dark"] .form-select:focus,
[data-theme="dark"] .form-input:focus {
    border-color: #3b82f6 !important;
    background: var(--dm-bg-tertiary, #334155) !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
}

[data-theme="dark"] .form-textarea::placeholder,
[data-theme="dark"] .form-input::placeholder {
    color: var(--dm-text-muted, #64748b) !important;
    opacity: 0.7 !important;
}

[data-theme="dark"] .input-with-icon i {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .input-with-icon input:focus + i {
    color: #3b82f6 !important;
}

/* Appointment Info Box Dark Mode */
[data-theme="dark"] .appointment-info-box {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .info-header {
    color: #93c5fd !important;
}

[data-theme="dark"] .info-item {
    background: rgba(15, 23, 42, 0.5) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .info-item i {
    color: #60a5fa !important;
}

[data-theme="dark"] .info-item span {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Form Actions Dark Mode */
[data-theme="dark"] .btn-cancel {
    background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
}

[data-theme="dark"] .btn-cancel:hover {
    background: linear-gradient(135deg, #334155 0%, #1e293b 100%) !important;
}

/* Detail Cards Dark Mode */
[data-theme="dark"] .detail-card {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .detail-card:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2) !important;
}

[data-theme="dark"] .detail-label {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .detail-value {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Week View Dark Mode */
[data-theme="dark"] .week-header,
[data-theme="dark"] .week-body {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .time-column-header,
[data-theme="dark"] .week-day-header {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .week-day-header.today {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
}

[data-theme="dark"] .week-day-header.today .day-date {
    color: white !important;
}

[data-theme="dark"] .day-date {
    color: #60a5fa !important;
}

[data-theme="dark"] .time-slot {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .week-cell {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .week-appointment {
    background: rgba(59, 130, 246, 0.15) !important;
    border-left-color: #3b82f6 !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .week-appointment.pending {
    background: rgba(251, 191, 36, 0.2) !important;
    border-left-color: #fbbf24 !important;
}

[data-theme="dark"] .week-appointment.confirmed {
    background: rgba(34, 197, 94, 0.2) !important;
    border-left-color: #22c55e !important;
}

[data-theme="dark"] .week-appointment.completed {
    background: rgba(168, 85, 247, 0.2) !important;
    border-left-color: #a855f7 !important;
}

[data-theme="dark"] .week-appointment.cancelled {
    background: rgba(239, 68, 68, 0.2) !important;
    border-left-color: #ef4444 !important;
    opacity: 0.7 !important;
}

[data-theme="dark"] .week-appointment.blocked {
    background: rgba(107, 114, 128, 0.2) !important;
    border-left-color: #9ca3af !important;
    cursor: not-allowed !important;
}

[data-theme="dark"] .week-appointment.blocked:hover {
    transform: none !important;
    box-shadow: none !important;
}

[data-theme="dark"] .week-apt-time,
[data-theme="dark"] .week-apt-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Day View Dark Mode */
[data-theme="dark"] .day-view-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

[data-theme="dark"] .day-view-body {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .day-time-label {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .day-time-content {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .day-appointment {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-left-color: #3b82f6 !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .day-appointment.pending {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.15) 0%, rgba(251, 191, 36, 0.1) 100%) !important;
    border-left-color: #fbbf24 !important;
}

[data-theme="dark"] .day-appointment.confirmed {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.1) 100%) !important;
    border-left-color: #22c55e !important;
}

[data-theme="dark"] .day-appointment.completed {
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.15) 0%, rgba(168, 85, 247, 0.1) 100%) !important;
    border-left-color: #a855f7 !important;
}

[data-theme="dark"] .day-appointment.cancelled {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.1) 100%) !important;
    border-left-color: #ef4444 !important;
    opacity: 0.8 !important;
}

[data-theme="dark"] .day-appointment.blocked {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.2) 0%, rgba(107, 114, 128, 0.15) 100%) !important;
    border-left-color: #9ca3af !important;
    cursor: not-allowed !important;
}

[data-theme="dark"] .day-appointment.blocked:hover {
    transform: none !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08) !important;
}

[data-theme="dark"] .day-apt-badge.blocked {
    background: rgba(107, 114, 128, 0.3) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .day-apt-time,
[data-theme="dark"] .day-apt-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .day-apt-notes {
    color: var(--dm-text-muted, #94a3b8) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .day-empty-slot {
    color: var(--dm-text-muted, #64748b) !important;
}

/* Cancellation Modal Dark Mode */
[data-theme="dark"] .cancel-icon-wrapper {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%) !important;
}

[data-theme="dark"] .cancel-details-box {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .cancel-detail-row {
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .cancel-detail-label {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .cancel-detail-value {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Modern Modal Dark Mode */
[data-theme="dark"] .modern-modal .modal-body {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .modern-modal .modal-footer {
    border-top-color: var(--dm-border-color, #334155) !important;
    background: var(--dm-bg-tertiary, #334155) !important;
}

/* Calendar Controls Dark Mode */
[data-theme="dark"] .calendar-controls {
    background: transparent !important;
}

[data-theme="dark"] .view-btn {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .view-btn:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .view-btn.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    border-color: #3b82f6 !important;
    color: white !important;
}

[data-theme="dark"] .btn-today {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .btn-today:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .period-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Upcoming Appointments Dark Mode */
[data-theme="dark"] .upcoming-item {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .upcoming-item:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .upcoming-date {
    background: var(--dm-bg-primary, #0f172a) !important;
}

[data-theme="dark"] .upcoming-title,
[data-theme="dark"] .upcoming-time {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Legend Dark Mode */
[data-theme="dark"] .legend-item {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .legend-text {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Mini Calendar Dark Mode (if needed) */
[data-theme="dark"] .mini-calendar {
    background: transparent !important;
}

[data-theme="dark"] .mini-calendar-day {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .mini-calendar-day:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .mini-calendar-day.has-appointments {
    background: rgba(59, 130, 246, 0.2) !important;
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .mini-calendar-day.today {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
}

[data-theme="dark"] .month-display {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Empty States Dark Mode */
[data-theme="dark"] .text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .text-center.text-muted i {
    opacity: 0.4 !important;
    color: var(--dm-text-muted, #64748b) !important;
}

/* Alert Messages Dark Mode */
[data-theme="dark"] .alert {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .alert-warning {
    background: rgba(251, 191, 36, 0.15) !important;
    border-color: #fbbf24 !important;
    color: #fbbf24 !important;
}

[data-theme="dark"] .alert-info {
    background: rgba(59, 130, 246, 0.15) !important;
    border-color: #3b82f6 !important;
    color: #93c5fd !important;
}

[data-theme="dark"] .alert-success {
    background: rgba(34, 197, 94, 0.15) !important;
    border-color: #22c55e !important;
    color: #86efac !important;
}

[data-theme="dark"] .alert-danger {
    background: rgba(239, 68, 68, 0.15) !important;
    border-color: #ef4444 !important;
    color: #fca5a5 !important;
}

/* Time Availability Messages */
[data-theme="dark"] #timeAvailabilityMessage {
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #timeSuggestionMessage {
    border-color: var(--dm-border-color, #334155) !important;
}
</style>

<script>
let appointmentType = 'emergency';

function toggleAppointmentType(type) {
    appointmentType = type;

    // Update button states
    document.getElementById('emergencyBtn').classList.toggle('active', type === 'emergency');
    document.getElementById('rescheduleBtn').classList.toggle('active', type === 'reschedule');

    // Show/hide service selection (only show in emergency mode)
    const serviceSelectionGroup = document.getElementById('serviceSelectionGroup');
    const serviceSelect = document.getElementById('serviceSelect');
    if (serviceSelectionGroup && serviceSelect) {
        if (type === 'emergency') {
            serviceSelectionGroup.style.display = 'block';
            serviceSelect.required = true;
        } else {
            serviceSelectionGroup.style.display = 'none';
            serviceSelect.required = false;
            serviceSelect.value = '';
            // Also hide the "Other" input if visible
            document.getElementById('otherConcernGroup').style.display = 'none';
            document.getElementById('otherConcern').value = '';
        }
    }

    // Show/hide appointment selection (only show in reschedule mode)
    const appointmentSelectionGroup = document.getElementById('appointmentSelectionGroup');
    const appointmentSelect = document.getElementById('appointmentSelect');
    const selectedAppointmentInfo = document.getElementById('selectedAppointmentInfo');
    if (appointmentSelectionGroup && appointmentSelect) {
        if (type === 'reschedule') {
            appointmentSelectionGroup.style.display = 'block';
            appointmentSelect.required = true;
        } else {
            appointmentSelectionGroup.style.display = 'none';
            appointmentSelect.required = false;
            appointmentSelect.value = '';
            if (selectedAppointmentInfo) {
                selectedAppointmentInfo.style.display = 'none';
            }
        }
    }

    // Update cancel button visibility based on form state
    if (typeof updateCancelButtonVisibility === 'function') {
        updateCancelButtonVisibility();
    }
}

// Function to check if form has any values and show/hide cancel button
function updateCancelButtonVisibility() {
    const cancelBtn = document.getElementById('cancelFormBtn');
    const reasonField = document.getElementById('reason');
    const dateField = document.getElementById('appointmentDate');
    const timeField = document.getElementById('appointmentTime');
    const serviceSelect = document.getElementById('serviceSelect');
    const otherConcern = document.getElementById('otherConcern');
    const appointmentSelect = document.getElementById('appointmentSelect');

    if (!cancelBtn) return;

    // Show cancel button if form has any values (for both emergency and reschedule modes)
    const hasValues = reasonField.value.trim() !== '' ||
                      dateField.value !== '' ||
                      timeField.value !== '' ||
                      (serviceSelect && serviceSelect.value !== '') ||
                      (otherConcern && otherConcern.value.trim() !== '') ||
                      (appointmentSelect && appointmentSelect.value !== '');

    if (hasValues) {
        cancelBtn.style.display = 'inline-flex';
    } else {
        cancelBtn.style.display = 'none';
    }
}

// Handle service selection dropdown
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('serviceSelect');
    const otherConcernGroup = document.getElementById('otherConcernGroup');
    const otherConcernInput = document.getElementById('otherConcern');

    if (serviceSelect && otherConcernGroup && otherConcernInput) {
        serviceSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                otherConcernGroup.style.display = 'block';
                otherConcernInput.required = true;
            } else {
                otherConcernGroup.style.display = 'none';
                otherConcernInput.required = false;
                otherConcernInput.value = '';
            }
            updateCancelButtonVisibility();
        });
    }
});

// Handle appointment selection dropdown for reschedule
document.addEventListener('DOMContentLoaded', function() {
    const appointmentSelect = document.getElementById('appointmentSelect');
    const selectedAppointmentInfo = document.getElementById('selectedAppointmentInfo');
    const infoService = document.getElementById('infoService');
    const infoDateTime = document.getElementById('infoDateTime');
    const appointmentForm = document.getElementById('appointmentForm');

    if (appointmentSelect && selectedAppointmentInfo) {
        appointmentSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const service = selectedOption.dataset.service;
                const datetime = selectedOption.dataset.datetime;
                const serviceId = selectedOption.dataset.serviceId;
                const duration = selectedOption.dataset.duration;
                const reasonForVisit = selectedOption.dataset.reasonForVisit;

                // Show info box
                selectedAppointmentInfo.style.display = 'block';
                infoService.textContent = service;
                infoDateTime.textContent = datetime;

                // Store appointment info in form dataset
                appointmentForm.dataset.originalAppointmentId = this.value;
                appointmentForm.dataset.serviceId = serviceId;
                appointmentForm.dataset.duration = duration;
                appointmentForm.dataset.reasonForVisit = reasonForVisit;

                // Optionally pre-fill the reason field
                const reasonField = document.getElementById('reason');
                if (reasonField && !reasonField.value) {
                    reasonField.value = `I would like to reschedule my ${service} appointment that was originally scheduled for ${datetime}.`;
                }
            } else {
                // Hide info box if no selection
                selectedAppointmentInfo.style.display = 'none';
                appointmentForm.dataset.originalAppointmentId = '';
                appointmentForm.dataset.serviceId = '';
                appointmentForm.dataset.duration = '';
                appointmentForm.dataset.reasonForVisit = '';
            }
            updateCancelButtonVisibility();
        });
    }
});

// Cancel form button functionality
document.addEventListener('DOMContentLoaded', function() {
    const cancelFormBtn = document.getElementById('cancelFormBtn');
    const appointmentForm = document.getElementById('appointmentForm');

    // Add event listeners to form fields to check when user fills them
    const reasonField = document.getElementById('reason');
    const dateField = document.getElementById('appointmentDate');
    const timeField = document.getElementById('appointmentTime');
    const serviceSelect = document.getElementById('serviceSelect');
    const otherConcern = document.getElementById('otherConcern');
    const appointmentSelect = document.getElementById('appointmentSelect');

    if (reasonField) reasonField.addEventListener('input', updateCancelButtonVisibility);
    if (dateField) dateField.addEventListener('change', updateCancelButtonVisibility);
    if (timeField) timeField.addEventListener('change', updateCancelButtonVisibility);
    if (serviceSelect) serviceSelect.addEventListener('change', updateCancelButtonVisibility);
    if (otherConcern) otherConcern.addEventListener('input', updateCancelButtonVisibility);
    if (appointmentSelect) appointmentSelect.addEventListener('change', updateCancelButtonVisibility);

    if (cancelFormBtn && appointmentForm) {
        cancelFormBtn.addEventListener('click', function() {
            // Show the modal instead of confirm
            const cancelRescheduleModal = new bootstrap.Modal(document.getElementById('cancelRescheduleModal'));
            cancelRescheduleModal.show();
        });
    }

    // Handle confirmation from modal
    const confirmCancelRescheduleBtn = document.getElementById('confirmCancelRescheduleBtn');
    if (confirmCancelRescheduleBtn) {
        confirmCancelRescheduleBtn.addEventListener('click', function() {
            const appointmentForm = document.getElementById('appointmentForm');

            // Close the modal
            const cancelRescheduleModal = bootstrap.Modal.getInstance(document.getElementById('cancelRescheduleModal'));
            cancelRescheduleModal.hide();

            // Reset form
            appointmentForm.reset();

            // Clear any dataset values
            appointmentForm.dataset.originalAppointmentId = '';
            appointmentForm.dataset.serviceId = '';
            appointmentForm.dataset.duration = '';
            appointmentForm.dataset.reasonForVisit = '';

            // Reset service selection
            const serviceSelect = document.getElementById('serviceSelect');
            const otherConcernGroup = document.getElementById('otherConcernGroup');
            const otherConcern = document.getElementById('otherConcern');
            if (serviceSelect) serviceSelect.value = '';
            if (otherConcernGroup) otherConcernGroup.style.display = 'none';
            if (otherConcern) {
                otherConcern.value = '';
                otherConcern.required = false;
            }

            // Reset appointment selection
            const appointmentSelect = document.getElementById('appointmentSelect');
            const selectedAppointmentInfo = document.getElementById('selectedAppointmentInfo');
            if (appointmentSelect) appointmentSelect.value = '';
            if (selectedAppointmentInfo) selectedAppointmentInfo.style.display = 'none';

            // Don't change the appointment type - keep current mode (emergency or reschedule)
            // Just update the visibility of fields
            updateCancelButtonVisibility();

            // Clear any availability messages
            const timeAvailabilityMsg = document.getElementById('timeAvailabilityMessage');
            const timeSuggestionMsg = document.getElementById('timeSuggestionMessage');
            if (timeAvailabilityMsg) timeAvailabilityMsg.remove();
            if (timeSuggestionMsg) timeSuggestionMsg.remove();

            // Scroll to top of form
            document.querySelector('.appointment-form-section').scrollIntoView({ behavior: 'smooth' });

            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.style.marginBottom = '1rem';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                <strong>Form Cleared.</strong> All fields have been reset. You can now fill out a new request.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            appointmentForm.parentElement.insertBefore(alert, appointmentForm);

            // Auto-dismiss after 3 seconds
            setTimeout(() => alert.remove(), 3000);
        });
    }
});

// Form submission handler
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitButton = this.querySelector('.btn-submit');
    const originalText = submitButton.innerHTML;

    // Disable button and show loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';

    const formData = {
        type: appointmentType,
        reason: document.getElementById('reason').value,
        date: document.getElementById('appointmentDate').value,
        time: document.getElementById('appointmentTime').value,
        _token: '{{ csrf_token() }}'
    };

    // If emergency walk-in, include service information
    if (appointmentType === 'emergency') {
        const serviceSelect = document.getElementById('serviceSelect');
        const otherConcern = document.getElementById('otherConcern');

        if (serviceSelect && serviceSelect.value) {
            if (serviceSelect.value === 'other') {
                formData.service_id = null;
                formData.other_concern = otherConcern ? otherConcern.value : '';
            } else {
                formData.service_id = serviceSelect.value;
                formData.other_concern = null;
            }
        }
    }

    // If rescheduling, include the original appointment ID only
    // The backend will automatically get service_id, duration, and other_concern from the existing appointment
    const form = this;
    if (appointmentType === 'reschedule') {
        if (form.dataset.originalAppointmentId) {
            formData.existing_appointment_id = form.dataset.originalAppointmentId;
        }
        // Don't send service_id or other_concern for reschedule - backend will use original appointment's data
    }

    // Send to backend API
    fetch('{{ route("patient-calendar.submit-request") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                <strong>Success!</strong> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            this.parentElement.insertBefore(alert, this.parentElement.firstChild);

            // Scroll to top to see the message
            this.parentElement.scrollIntoView({ behavior: 'smooth' });

            // Reset form
            this.reset();

            // Reset service selection and hide "Other" input
            const serviceSelect = document.getElementById('serviceSelect');
            const otherConcernGroup = document.getElementById('otherConcernGroup');
            const otherConcern = document.getElementById('otherConcern');
            if (serviceSelect) serviceSelect.value = '';
            if (otherConcernGroup) otherConcernGroup.style.display = 'none';
            if (otherConcern) {
                otherConcern.value = '';
                otherConcern.required = false;
            }

            // Reset appointment selection
            const appointmentSelect = document.getElementById('appointmentSelect');
            const selectedAppointmentInfo = document.getElementById('selectedAppointmentInfo');
            if (appointmentSelect) appointmentSelect.value = '';
            if (selectedAppointmentInfo) selectedAppointmentInfo.style.display = 'none';

            // Clear dataset values
            this.dataset.originalAppointmentId = '';
            this.dataset.serviceId = '';
            this.dataset.duration = '';
            this.dataset.reasonForVisit = '';

            // Hide cancel button
            const cancelBtn = document.getElementById('cancelFormBtn');
            if (cancelBtn) {
                cancelBtn.style.display = 'none';
            }

            // Auto-dismiss after 5 seconds
            setTimeout(() => alert.remove(), 5000);
        } else {
            throw new Error(data.message || 'Failed to submit request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            <i class="bi bi-x-circle me-2"></i>
            <strong>Error!</strong> ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        this.parentElement.insertBefore(alert, this.parentElement.firstChild);
        this.parentElement.scrollIntoView({ behavior: 'smooth' });
    })
    .finally(() => {
        // Re-enable button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
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
                <button type="button" class="btn btn-warning" id="modalRescheduleBtn">
                    <i class="bi bi-calendar3 me-1"></i>Request Reschedule
                </button>
            </div>
        </div>
</div>
</div>

<!-- Cancellation Confirmation Modal -->
<div class="modal fade" id="cancelConfirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;">
                <h5 class="modal-title text-white">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Request Cancellation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="cancel-icon-wrapper">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h5 class="mt-3 mb-2" style="color: #1e293b; font-weight: 700;">Are you sure you want to request cancellation for this appointment?</h5>
                </div>

                <div class="cancel-details-box">
                    <div class="cancel-detail-row">
                        <div class="cancel-detail-label">
                            <i class="bi bi-heart-pulse me-2"></i>Service:
                        </div>
                        <div class="cancel-detail-value" id="cancelServiceName">-</div>
                    </div>
                    <div class="cancel-detail-row">
                        <div class="cancel-detail-label">
                            <i class="bi bi-calendar-event me-2"></i>Date:
                        </div>
                        <div class="cancel-detail-value" id="cancelDate">-</div>
                    </div>
                    <div class="cancel-detail-row">
                        <div class="cancel-detail-label">
                            <i class="bi bi-clock me-2"></i>Time:
                        </div>
                        <div class="cancel-detail-value" id="cancelTime">-</div>
                    </div>
                </div>

                <div class="alert alert-warning mt-3 mb-0" role="alert" style="border-left: 4px solid #f59e0b;">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> This will send a cancellation request to the clinic. The clinic will contact you shortly to confirm.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>No, Keep Appointment
                </button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="bi bi-check-circle me-1"></i>Yes, Request Cancellation
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Form Modal -->
<div class="modal fade" id="cancelRescheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%); border: none;">
                <h5 class="modal-title text-white">
                    <i class="bi bi-question-circle-fill me-2"></i>Clear Form?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="bi bi-arrow-counterclockwise" style="font-size: 2.5rem; color: #475569;"></i>
                    </div>
                    <h5 class="mt-3 mb-2" style="color: #1e293b; font-weight: 700;">Clear all form fields?</h5>
                    <p style="color: #64748b; margin: 0;">All your input will be cleared and you can start over.</p>
                </div>

                <div class="alert alert-info mb-0" role="alert" style="border-left: 4px solid #3b82f6; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px;">
                    <div style="display: flex; align-items: start; gap: 0.75rem;">
                        <i class="bi bi-info-circle-fill" style="color: #1e40af; font-size: 1.25rem; flex-shrink: 0; margin-top: 0.125rem;"></i>
                        <div style="color: #1e40af;">
                            <strong style="display: block; margin-bottom: 0.25rem;">What happens next?</strong>
                            <span style="font-size: 0.9rem;">All form fields will be reset to empty. You'll remain in the current mode (Emergency Walk-in or Request Reschedule).</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light" style="gap: 0.75rem; padding: 1.25rem 1.5rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 600; transition: all 0.3s;">
                    <i class="bi bi-arrow-left me-1"></i>No, Go Back
                </button>
                <button type="button" class="btn btn-primary" id="confirmCancelRescheduleBtn" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 600; transition: all 0.3s; background: linear-gradient(135deg, #64748b 0%, #475569 100%); border: none;">
                    <i class="bi bi-check-circle me-1"></i>Yes, Clear Form
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

<script>
// Pass appointments data to JavaScript
window.patientAppointments = @json($appointments);
window.allAppointments = @json($allAppointments);
window.blockedTimes = @json($blockedTimes);

// Modal button event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Handle reschedule request from modal
    const modalRescheduleBtn = document.getElementById('modalRescheduleBtn');
    if (modalRescheduleBtn) {
        modalRescheduleBtn.addEventListener('click', function() {
            const modal = document.getElementById('appointmentDetailsModal');
            const appointmentId = modal.dataset.appointmentId;

            // Close the details modal
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();

            // Scroll to the reschedule form section
            const formSection = document.querySelector('.appointment-form-section');
            formSection.scrollIntoView({ behavior: 'smooth' });

            // Activate reschedule mode
            toggleAppointmentType('reschedule');

            // Select the appointment in the dropdown
            const appointmentSelect = document.getElementById('appointmentSelect');
            if (appointmentSelect && appointmentId) {
                appointmentSelect.value = appointmentId;
                // Trigger change event to show appointment info
                appointmentSelect.dispatchEvent(new Event('change'));
            }
        });
    }

    // Handle cancellation request from modal
    const modalCancelBtn = document.getElementById('modalCancelBtn');
    if (modalCancelBtn) {
        modalCancelBtn.addEventListener('click', function() {
            const modal = document.getElementById('appointmentDetailsModal');
            const appointmentId = modal.dataset.appointmentId;

            const appointment = window.patientAppointments.find(apt => apt.id == appointmentId);
            if (!appointment) return;

            const startDate = new Date(appointment.start_datetime);
            const dateFormatted = startDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const timeFormatted = startDate.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            const serviceName = appointment.service ? appointment.service.service_name : appointment.reason_for_visit;

            // Populate cancellation modal with appointment details
            document.getElementById('cancelServiceName').textContent = serviceName;
            document.getElementById('cancelDate').textContent = dateFormatted;
            document.getElementById('cancelTime').textContent = timeFormatted;

            // Close details modal
            const detailsModalInstance = bootstrap.Modal.getInstance(modal);
            detailsModalInstance.hide();

            // Show cancellation confirmation modal
            const cancelModal = new bootstrap.Modal(document.getElementById('cancelConfirmationModal'));
            cancelModal.show();

            // Store appointment ID for confirmation
            document.getElementById('cancelConfirmationModal').dataset.appointmentId = appointmentId;
        });
    }

    // Handle final cancellation confirmation
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', function() {
            const cancelModal = document.getElementById('cancelConfirmationModal');
            const appointmentId = cancelModal.dataset.appointmentId;

            // TODO: Send cancellation request to backend
            // For now, just show a success message

            // Close the cancellation modal
            const modalInstance = bootstrap.Modal.getInstance(cancelModal);
            modalInstance.hide();

            // Show success alert (you can replace this with a toast notification)
            setTimeout(() => {
                alert('Cancellation request submitted successfully!\n\nThe clinic will contact you shortly to confirm.');
            }, 300);
        });
    }

    // Add conflict checking for appointment date/time selection
    const appointmentDateInput = document.getElementById('appointmentDate');
    const appointmentTimeInput = document.getElementById('appointmentTime');

    if (appointmentDateInput && appointmentTimeInput) {
        // Function to parse datetime string as LOCAL time
        function parseLocalDateTime(datetimeStr) {
            if (!datetimeStr || typeof datetimeStr !== 'string') return null;
            const parts = datetimeStr.split(' ');
            if (parts.length !== 2) return null;
            const [datePart, timePart] = parts;
            const [year, month, day] = datePart.split('-').map(Number);
            const [hours, minutes, seconds] = timePart.split(':').map(Number);
            if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hours) || isNaN(minutes)) return null;
            return new Date(year, month - 1, day, hours, minutes, seconds || 0);
        }

        // Function to get the duration for the current request
        function getRequestDuration() {
            let duration = 30; // Default 30 minutes for "Other" services

            // If emergency walk-in mode and service is selected
            if (appointmentType === 'emergency') {
                const serviceSelect = document.getElementById('serviceSelect');
                if (serviceSelect && serviceSelect.value && serviceSelect.value !== 'other') {
                    // Find the service in the services array
                    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                    const serviceDuration = selectedOption.getAttribute('data-duration');
                    if (serviceDuration) {
                        duration = parseInt(serviceDuration);
                    }
                }
            }
            // If reschedule mode, get duration from the selected appointment
            else if (appointmentType === 'reschedule') {
                const appointmentSelect = document.getElementById('appointmentSelect');
                if (appointmentSelect && appointmentSelect.value) {
                    const selectedOption = appointmentSelect.options[appointmentSelect.selectedIndex];
                    const aptDuration = selectedOption.getAttribute('data-duration');
                    if (aptDuration) {
                        duration = parseInt(aptDuration);
                    }
                }
            }

            return duration;
        }

        // Function to check if a time conflicts with existing appointments or blocked times
        function isTimeSlotAvailable(selectedDate, selectedTime) {
            if (!selectedDate || !selectedTime) return true;

            const [hours, minutes] = selectedTime.split(':').map(Number);
            const requestedStart = new Date(selectedDate);
            requestedStart.setHours(hours, minutes, 0);

            // Get the duration based on the selected service or appointment
            const duration = getRequestDuration();
            const requestedEnd = new Date(requestedStart.getTime() + duration * 60000);

            // Check all appointments
            for (const appointment of window.allAppointments) {
                const aptStart = parseLocalDateTime(appointment.start_datetime);
                const aptEnd = parseLocalDateTime(appointment.end_datetime);
                if (!aptStart || !aptEnd) continue;

                // Check if on the same date
                if (aptStart.toDateString() !== requestedStart.toDateString()) continue;

                // Check for overlap
                if (requestedStart < aptEnd && requestedEnd > aptStart) {
                    return false; // Conflict found
                }
            }

            // Check blocked times
            for (const blockedTime of window.blockedTimes) {
                const blockStart = parseLocalDateTime(blockedTime.start_datetime);
                const blockEnd = parseLocalDateTime(blockedTime.end_datetime);
                if (!blockStart || !blockEnd) continue;

                // Check if on the same date
                if (blockStart.toDateString() !== requestedStart.toDateString()) continue;

                // Check for overlap
                if (requestedStart < blockEnd && requestedEnd > blockStart) {
                    return false; // Conflict found
                }
            }

            return true; // No conflicts
        }

        // Function to show available/unavailable message
        function updateTimeAvailability() {
            const selectedDate = appointmentDateInput.value;
            const selectedTime = appointmentTimeInput.value;

            // Remove any existing availability message
            let messageDiv = document.getElementById('timeAvailabilityMessage');
            if (messageDiv) {
                messageDiv.remove();
            }

            if (selectedDate && selectedTime) {
                const dateObj = new Date(selectedDate + 'T00:00:00');
                const available = isTimeSlotAvailable(dateObj, selectedTime);

                // Check if dark mode is active
                const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

                // Create message div
                messageDiv = document.createElement('div');
                messageDiv.id = 'timeAvailabilityMessage';
                messageDiv.style.marginTop = '0.5rem';
                messageDiv.style.padding = '0.75rem';
                messageDiv.style.borderRadius = '8px';
                messageDiv.style.fontSize = '0.9rem';
                messageDiv.style.fontWeight = '600';
                messageDiv.style.display = 'flex';
                messageDiv.style.alignItems = 'center';
                messageDiv.style.gap = '0.5rem';

                if (available) {
                    if (isDarkMode) {
                        messageDiv.style.background = 'linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.1) 100%)';
                        messageDiv.style.color = '#86efac';
                        messageDiv.style.border = '2px solid #10b981';
                    } else {
                        messageDiv.style.background = 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)';
                        messageDiv.style.color = '#065f46';
                        messageDiv.style.border = '2px solid #10b981';
                    }
                    messageDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> This time slot is available!';
                } else {
                    if (isDarkMode) {
                        messageDiv.style.background = 'linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%)';
                        messageDiv.style.color = '#fca5a5';
                        messageDiv.style.border = '2px solid #ef4444';
                    } else {
                        messageDiv.style.background = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)';
                        messageDiv.style.color = '#991b1b';
                        messageDiv.style.border = '2px solid #ef4444';
                    }
                    messageDiv.innerHTML = '<i class="bi bi-x-circle-fill"></i> This time slot is not available. Please select a different time.';

                    // Disable the submit button
                    const submitBtn = document.querySelector('.btn-submit');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.5';
                        submitBtn.style.cursor = 'not-allowed';
                    }
                }

                // Insert message after the time input group
                const timeFormGroup = appointmentTimeInput.closest('.form-group');
                if (timeFormGroup) {
                    timeFormGroup.appendChild(messageDiv);
                }

                // Enable submit button if available
                if (available) {
                    const submitBtn = document.querySelector('.btn-submit');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';
                    }
                }
            }
        }

        // Add event listeners
        appointmentDateInput.addEventListener('change', updateTimeAvailability);
        appointmentTimeInput.addEventListener('change', updateTimeAvailability);
        appointmentTimeInput.addEventListener('input', updateTimeAvailability);

        // Add listener for service selection changes to re-check availability
        const serviceSelect = document.getElementById('serviceSelect');
        if (serviceSelect) {
            serviceSelect.addEventListener('change', updateTimeAvailability);
        }

        // Add listener for appointment selection changes to re-check availability
        const appointmentSelect = document.getElementById('appointmentSelect');
        if (appointmentSelect) {
            appointmentSelect.addEventListener('change', updateTimeAvailability);
        }

        // Show suggested available times when date is selected
        appointmentDateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (!selectedDate) return;

            // Find available time slots for this date
            const dateObj = new Date(selectedDate + 'T00:00:00');
            const availableSlots = [];
            const businessHours = [
                '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'
            ];

            for (const time of businessHours) {
                if (isTimeSlotAvailable(dateObj, time)) {
                    availableSlots.push(time);
                }
            }

            // Show suggestion message
            let suggestionDiv = document.getElementById('timeSuggestionMessage');
            if (suggestionDiv) {
                suggestionDiv.remove();
            }

            // Check if dark mode is active
            const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

            if (availableSlots.length > 0) {
                suggestionDiv = document.createElement('div');
                suggestionDiv.id = 'timeSuggestionMessage';
                suggestionDiv.style.marginTop = '0.5rem';
                suggestionDiv.style.padding = '0.75rem';
                suggestionDiv.style.borderRadius = '8px';
                suggestionDiv.style.fontSize = '0.85rem';

                if (isDarkMode) {
                    suggestionDiv.style.background = 'linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.1) 100%)';
                    suggestionDiv.style.color = '#93c5fd';
                    suggestionDiv.style.border = '2px solid #3b82f6';
                } else {
                    suggestionDiv.style.background = 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)';
                    suggestionDiv.style.color = '#1e40af';
                    suggestionDiv.style.border = '2px solid #3b82f6';
                }

                suggestionDiv.innerHTML = `<i class="bi bi-info-circle-fill"></i> <strong>${availableSlots.length}</strong> time slots available on this date. Select a time to check availability.`;

                const dateFormGroup = appointmentDateInput.closest('.form-group');
                if (dateFormGroup) {
                    dateFormGroup.appendChild(suggestionDiv);
                }
            } else {
                suggestionDiv = document.createElement('div');
                suggestionDiv.id = 'timeSuggestionMessage';
                suggestionDiv.style.marginTop = '0.5rem';
                suggestionDiv.style.padding = '0.75rem';
                suggestionDiv.style.borderRadius = '8px';
                suggestionDiv.style.fontSize = '0.85rem';

                if (isDarkMode) {
                    suggestionDiv.style.background = 'linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%)';
                    suggestionDiv.style.color = '#fde68a';
                    suggestionDiv.style.border = '2px solid #f59e0b';
                } else {
                    suggestionDiv.style.background = 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)';
                    suggestionDiv.style.color = '#92400e';
                    suggestionDiv.style.border = '2px solid #f59e0b';
                }

                suggestionDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> No available time slots on this date. Please select a different date.';

                const dateFormGroup = appointmentDateInput.closest('.form-group');
                if (dateFormGroup) {
                    dateFormGroup.appendChild(suggestionDiv);
                }
            }
        });
    }
});
</script>
<script src="{{ asset('js/patient-calendar.js') }}"></script>
@endsection
