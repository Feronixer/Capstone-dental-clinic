@extends('layout.patient.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/patient-calendar.css') }}">

<div class="calendar-container">
    <!-- Main Content -->
    <div class="calendar-layout">
        <!-- Sidebar -->
        <aside class="calendar-sidebar">
            <!-- Tabbed Appointment Section -->
            <div class="sidebar-card tabbed-section">
                <!-- Tab Buttons -->
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="upcoming" onclick="switchTab('upcoming')">
                        <i class="bi bi-clock-history"></i>
                        <span>Upcoming</span>
                    </button>
                    <button class="tab-btn" data-tab="pending" onclick="switchTab('pending')">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Pending Request</span>
                    </button>
                    <button class="tab-btn" data-tab="history" onclick="switchTab('history')">
                        <i class="bi bi-archive"></i>
                        <span>Medical History</span>
                    </button>
            </div>

                <!-- Tab Content -->
                <div class="tab-content-wrapper">
                    <!-- Upcoming Tab -->
                    <div id="tab-upcoming" class="tab-content active">
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
                                <div class="text-center text-muted py-3 empty-state">
                                    <i class="bi bi-calendar-x mb-2"></i>
                            <p class="mb-0">No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>

                    <!-- Pending Tab -->
                    <div id="tab-pending" class="tab-content">
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
                                <div class="text-center text-muted py-3 empty-state">
                                    <i class="bi bi-check-circle mb-2"></i>
                            <p class="mb-0">No pending requests</p>
                        </div>
                    @endforelse
                </div>
            </div>

                    <!-- History Tab -->
                    <div id="tab-history" class="tab-content">
                <div id="appointmentHistory" class="history-list">
                    @forelse($appointmentHistory as $appointment)
                        <div class="history-item {{ strtolower($appointment->status) === 'cancelled' ? 'cancelled' : '' }}">
                            <div class="history-date">
                                <span class="history-day">{{ $appointment->start_datetime->format('d') }}</span>
                                <span class="history-month">{{ $appointment->start_datetime->format('M') }}</span>
                                <span class="history-year">{{ $appointment->start_datetime->format('Y') }}</span>
                            </div>
                            <div class="history-info">
                                <div class="history-title {{ strtolower($appointment->status) === 'cancelled' ? 'text-decoration-line-through' : '' }}">{{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }}</div>
                                <div class="history-time">
                                    <i class="bi bi-clock me-1"></i>{{ $appointment->start_datetime->format('g:i A') }}
                                </div>
                                @if(strtolower($appointment->status) === 'cancelled' && $appointment->notes)
                                    <div class="history-notes text-muted small mt-1">
                                        <i class="bi bi-info-circle me-1"></i>{{ $appointment->notes }}
                                    </div>
                                @endif
                            </div>
                            <span class="status-badge history {{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                        </div>
                    @empty
                                <div class="text-center text-muted py-3 empty-state">
                                    <i class="bi bi-inbox mb-2"></i>
                            <p class="mb-0">No appointment history</p>
                        </div>
                    @endforelse
                        </div>
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
                    <button class="appointment-action-btn emergency-btn" data-bs-toggle="modal" data-bs-target="#appointmentRequestModal" onclick="openAppointmentModal('emergency')">
                        <i class="bi bi-lightning-charge-fill"></i> Emergency
                    </button>
                    <button class="appointment-action-btn reschedule-btn" data-bs-toggle="modal" data-bs-target="#appointmentRequestModal" onclick="openAppointmentModal('reschedule')">
                        <i class="bi bi-arrow-repeat"></i> Reschedule
                    </button>
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

    <!-- Appointment Request Modal -->
    <div class="modal fade" id="appointmentRequestModal" tabindex="-1" aria-labelledby="appointmentRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); border: none;">
                    <h5 class="modal-title text-white" id="appointmentRequestModalLabel">
                        <i class="bi bi-calendar-plus me-2"></i>Appointment Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
                    <!-- Emergency Appointment Form -->
                    <div id="emergencyFormSection" class="appointment-form-section" style="display: none;">
                        <form id="emergencyForm" class="appointment-form">
                            <!-- Service Selection -->
                            <div class="form-group" id="emergencyServiceSelectionGroup">
                                <label for="emergencyServiceSelect" class="form-label">
                    <i class="bi bi-heart-pulse-fill me-2"></i>Service to be Treated:
                </label>
                                <select id="emergencyServiceSelect" name="service_id" class="form-select" required>
                    <option value="">-- Select a service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" data-duration="{{ $service->default_duration_minutes }}">{{ $service->service_name }}</option>
                    @endforeach
                    <option value="other" data-duration="30">Other</option>
                </select>
            </div>

            <!-- Other Concern Input (only visible when "Other" is selected) -->
                            <div class="form-group" id="emergencyOtherConcernGroup" style="display: none;">
                                <label for="emergencyOtherConcern" class="form-label">
                    <i class="bi bi-pencil-square me-2"></i>Service Name:
                </label>
                                <input type="text" id="emergencyOtherConcern" name="other_concern" class="form-input" placeholder="Enter the dental service you need...">
            </div>

                            <div class="form-group">
                                <label for="emergencyReason" class="form-label">
                                    <i class="bi bi-chat-left-text me-2"></i>State your Reason:
                                </label>
                                <textarea id="emergencyReason" name="reason" class="form-textarea" rows="4" placeholder="Please provide a clear reason for your request. For example: 'I have a dental emergency with severe tooth pain'..." required></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="emergencyDate" class="form-label">
                                        <i class="bi bi-calendar-event me-2"></i>Select Date:
                                    </label>
                                    <div class="input-with-icon">
                                        <input type="date" id="emergencyDate" name="date" class="form-input" required>
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="emergencyTime" class="form-label">
                                        <i class="bi bi-clock-history me-2"></i>Select Time:
                                    </label>
                                    <div class="input-with-icon">
                                        <input type="time" id="emergencyTime" name="time" class="form-input" required>
                                        <i class="bi bi-clock"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-submit">
                                    <i class="bi bi-check-circle me-2"></i>Submit Request
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Reschedule Appointment Form -->
                    <div id="rescheduleFormSection" class="appointment-form-section" style="display: none;">
                        <form id="rescheduleForm" class="appointment-form">
                            <!-- Appointment Selection -->
                            <div class="form-group" id="rescheduleAppointmentSelectionGroup">
                                <label for="rescheduleAppointmentSelect" class="form-label">
                    <i class="bi bi-calendar-check me-2"></i>Select Appointment to Reschedule:
                </label>
                                <select id="rescheduleAppointmentSelect" name="appointment_id" class="form-select">
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
                                    data-reason-for-visit="{{ $appointment->reason_for_visit ?? '' }}"
                                    data-status="{{ $appointment->status ?? 'Pending' }}">
                                {{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }} -
                                {{ $appointment->start_datetime->format('M d, Y') }} at
                                {{ $appointment->start_datetime->format('g:i A') }}
                            </option>
                        @endif
                    @endforeach
                </select>
                                <div id="rescheduleSelectedAppointmentInfo" class="appointment-info-box" style="display: none;">
                    <div class="info-header">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Selected Appointment Details</span>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <i class="bi bi-heart-pulse"></i>
                                            <span id="rescheduleInfoService">-</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-calendar-event"></i>
                                            <span id="rescheduleInfoDateTime">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                                <label for="rescheduleReason" class="form-label">
                    <i class="bi bi-chat-left-text me-2"></i>State your Reason:
                </label>
                                <textarea id="rescheduleReason" name="reason" class="form-textarea" rows="4" placeholder="Please provide a clear reason for your request. For example: 'I need to reschedule due to a work commitment...'" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                                    <label for="rescheduleDate" class="form-label">
                        <i class="bi bi-calendar-event me-2"></i>Select Date:
                    </label>
                    <div class="input-with-icon">
                                        <input type="date" id="rescheduleDate" name="date" class="form-input" required>
                        <i class="bi bi-calendar3"></i>
                    </div>
                </div>

                <div class="form-group">
                                    <label for="rescheduleTime" class="form-label">
                        <i class="bi bi-clock-history me-2"></i>Select Time:
                    </label>
                    <div class="input-with-icon">
                                        <input type="time" id="rescheduleTime" name="time" class="form-input" required>
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle me-2"></i>Submit Request
                </button>
            </div>
        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   CALENDAR PAGE UI/UX IMPROVEMENTS
   ============================================ */

/* Container Optimization - Maximize Screen Space */
.calendar-container {
    max-width: 100%;
    margin: 0;
    padding: 0.875rem;
    min-height: calc(100vh - 80px);
}

/* Enhanced Header Section with Blue Accent */
.calendar-header {
    margin-bottom: 1rem;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.05) 0%, rgba(33, 150, 243, 0.02) 100%);
    border-radius: 12px;
    border-left: 3px solid #2196F3;
}

.page-title {
    font-size: clamp(1.25rem, 2.5vw, 1.75rem);
    font-weight: 800;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-title i {
    color: #2196F3;
    font-size: 1em;
    margin-right: 0.375rem;
}

.page-title::before {
    content: '';
    width: 3px;
    height: 1.75rem;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 2px;
}

[data-theme="dark"] .page-title i {
    color: #60a5fa !important;
}

.page-subtitle {
    color: #64748b;
    margin: 0.375rem 0 0 1.75rem;
    font-size: clamp(0.8rem, 1.25vw, 0.9rem);
}

/* Optimized Layout - Better Space Usage */
.calendar-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 0.875rem;
    align-items: stretch;
}

/* Enhanced Sidebar Cards - More Compact */
.calendar-sidebar {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    position: sticky;
    top: 0.75rem;
    max-height: calc(100vh - 80px);
    overflow-y: auto;
}

.sidebar-card {
    background: white;
    border-radius: 10px;
    padding: 0.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    border: 1px solid rgba(33, 150, 243, 0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    min-height: 0;
    min-width: 0;
}


.sidebar-card:hover {
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.12);
    border-color: rgba(33, 150, 243, 0.25);
    transform: translateY(-1px);
}

.sidebar-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.625rem 0;
    display: flex;
    align-items: center;
    padding-bottom: 0.4rem;
    border-bottom: 2px solid rgba(33, 150, 243, 0.15);
}

.sidebar-title i {
    color: #2196F3;
    font-size: 0.9rem;
    margin-right: 0.3rem;
}

/* Main Calendar Navigation Buttons */
.nav-btn {
    width: 24px;
    height: 24px;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 4px;
    color: #64748b;
    cursor: pointer;
}

.nav-btn:hover {
    background: #2196F3;
    color: white;
    border-color: #2196F3;
    transform: scale(1.05);
}

.nav-btn i {
    font-size: 0.75rem;
}

/* Tabbed Section Styles */
.tabbed-section {
    padding: 0.625rem !important;
    display: flex;
    flex-direction: column;
    min-height: 0;
    height: 100%;
}

.tabbed-section .tab-content-wrapper {
    flex: 1;
    min-height: 0;
}

.tab-buttons {
    display: flex;
    gap: 0.4rem;
    margin-bottom: 0.875rem;
    border-bottom: 2px solid rgba(33, 150, 243, 0.1);
    padding-bottom: 0.5rem;
}

.tab-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.5rem 0.625rem;
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    min-width: 44px;
}

.tab-btn i {
    font-size: 0.95rem;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.tab-btn span {
    display: none;
}

.tab-btn:hover {
    background: rgba(33, 150, 243, 0.05);
    color: #2196F3;
}

.tab-btn.active {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.15) 0%, rgba(33, 150, 243, 0.08) 100%);
    color: #2196F3;
    font-weight: 700;
    justify-content: center;
}

.tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: -0.625rem;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 3px;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 2px 2px 0 0;
    z-index: 1;
}

.tab-btn.active i {
    color: #2196F3;
}

.tab-content-wrapper {
    position: relative;
    min-height: 0;
    max-height: none;
    overflow-y: auto;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.tab-content {
    display: none;
    animation: fadeIn 0.3s ease;
    flex: 1;
    min-height: 0;
    flex-direction: column;
}

.tab-content.active {
    display: flex;
    flex-direction: column;
}

.tab-content .upcoming-list,
.tab-content .pending-requests-list,
.tab-content .history-list {
    flex: 1;
    min-height: 0;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom Scrollbar for Tab Content */
.tab-content-wrapper::-webkit-scrollbar {
    width: 5px;
}

.tab-content-wrapper::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.tab-content-wrapper::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 3px;
}

.tab-content-wrapper::-webkit-scrollbar-thumb:hover {
    background: #1976D2;
}

.empty-state {
    padding: 2rem 1rem !important;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.empty-state i {
    font-size: 4rem !important;
    color: #94a3b8;
    margin-bottom: 1rem;
}

.empty-state p {
    font-size: 1rem;
    color: #64748b;
    font-weight: 500;
}

/* Upcoming Items - More Compact */
.upcoming-list,
.pending-requests-list,
.history-list {
    gap: 0.625rem;
    display: flex;
    flex-direction: column;
}

.upcoming-item,
.history-item {
    padding: 0.625rem;
    border-radius: 8px;
    transition: all 0.25s ease;
}

.upcoming-item:hover,
.history-item:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(33, 150, 243, 0.2);
}

.history-item.cancelled {
    background: #fff5f5;
    opacity: 0.8;
    border-left: 3px solid #ef4444;
}

.history-item.cancelled:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(239, 68, 68, 0.2);
}

.history-notes {
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
    margin-top: 0.25rem;
    padding-left: 1.25rem;
}

.upcoming-date {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(33, 150, 243, 0.3);
    flex-shrink: 0;
}

.date-day {
    font-size: 1.15rem;
    font-weight: 700;
}

.date-month {
    font-size: 0.65rem;
}

/* Main Calendar Area - Optimized */
.calendar-main {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid rgba(33, 150, 243, 0.1);
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.calendar-controls {
    margin-bottom: 1rem;
    padding-bottom: 0.875rem;
    border-bottom: 2px solid rgba(33, 150, 243, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.view-btn {
    padding: 0.4rem 0.75rem;
    font-size: 0.8rem;
    transition: all 0.25s ease;
}

.view-btn.active {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
    transform: translateY(-1px);
}

.view-btn:hover:not(.active) {
    background: rgba(33, 150, 243, 0.05);
    border-color: #2196F3;
    color: #2196F3;
}

.btn-today {
    padding: 0.4rem 1rem;
    font-weight: 700;
    font-size: 0.8rem;
    transition: all 0.25s ease;
}

.btn-today:hover {
    background: #2196F3;
    color: white;
    border-color: #2196F3;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.period-header {
    margin-bottom: 1rem;
    gap: 1.25rem;
}

.period-title {
    font-size: clamp(1.15rem, 2vw, 1.5rem);
    font-weight: 800;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Legend Improvements */
.legend-inline {
    gap: 0.875rem;
}

.legend-item {
    gap: 0.375rem;
    padding: 0.25rem 0.625rem;
    border-radius: 6px;
    background: rgba(33, 150, 243, 0.05);
    transition: all 0.2s ease;
}

.legend-item:hover {
    background: rgba(33, 150, 243, 0.1);
    transform: scale(1.05);
}

.legend-dot {
    width: 10px;
    height: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.legend-text {
    font-size: 0.7rem;
    font-weight: 600;
    color: #64748b;
}

.calendar-content {
    min-height: 400px;
}

/* Calendar Grid - More Compact */
.calendar-grid {
    width: 100%;
}

.calendar-header-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e2e8f0;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
}

.calendar-header-cell {
    background: #f8fafc;
    padding: 0.625rem 0.5rem;
    text-align: center;
    font-weight: 600;
    color: #64748b;
    font-size: 0.8rem;
}

.calendar-body {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: #e2e8f0;
}

.calendar-week {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
}

.calendar-day {
    background: white;
    min-height: 80px;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
}

.calendar-day:hover {
    background: #f8fafc;
}

.calendar-day.today {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(33, 150, 243, 0.05) 100%);
    border: 2px solid #2196F3;
}

.calendar-day.today .day-number {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.day-number {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.375rem;
    font-size: 0.875rem;
}

.day-events {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    flex: 1;
    overflow: hidden;
}

.event-item {
    background: #e3f2fd;
    border-left: 2px solid #2196F3;
    padding: 0.25rem 0.375rem;
    border-radius: 4px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.2s;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.event-item:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 6px rgba(33, 150, 243, 0.3);
}

.event-item.pending {
    background: #fef3c7;
    border-left-color: #fbbf24;
    color: #92400e;
}

.event-item.confirmed {
    background: #dbeafe;
    border-left-color: #3b82f6;
    color: #1e40af;
}

.event-item.completed {
    background: #d1fae5;
    border-left-color: #10b981;
    color: #065f46;
}

.event-item.cancelled {
    background: #fee2e2;
    border-left-color: #ef4444;
    color: #991b1b;
    opacity: 0.7;
}

.event-item.blocked,
.event-item.missed {
    background: #e5e7eb;
    border-left-color: #6b7280;
    color: #1f2937;
    cursor: not-allowed;
}

.event-time {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.65rem;
    margin-bottom: 0.1rem;
}

.event-title {
    color: #64748b;
    font-size: 0.65rem;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.event-notes {
    font-size: 0.65rem;
    color: #64748b;
    margin-top: 0.125rem;
    opacity: 0.9;
    line-height: 1.2;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* Upcoming Info - More Compact */
.upcoming-info {
    flex: 1;
    min-width: 0;
}

.upcoming-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.2rem;
    font-size: 0.85rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.upcoming-time {
    font-size: 0.75rem;
    color: #64748b;
}

/* History Info - More Compact */
.history-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.2rem;
    font-size: 0.8rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.history-time {
    font-size: 0.7rem;
    color: #64748b;
}

/* Pending Request - More Compact */
.pending-request-item {
    padding: 0.625rem;
}

.pending-request-header {
    margin-bottom: 0.5rem;
}

.pending-request-service {
    font-size: 0.85rem;
}

.pending-request-datetime {
    font-size: 0.75rem;
}

.pending-request-status {
    font-size: 0.7rem;
}

/* Status Badges - Smaller */
.status-badge {
    padding: 0.25rem 0.625rem;
    font-size: 0.65rem;
}

/* History Date - Smaller */
.history-date {
    width: 44px;
    padding: 0.375rem;
}

.history-day {
    font-size: 1.1rem;
}

.history-month {
    font-size: 0.6rem;
}

.history-year {
    font-size: 0.5rem;
}

/* Responsive Calendar Header */
@media (max-width: 768px) {
    .calendar-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .page-title i {
        font-size: 1em;
    }
}

/* Status Badges - Enhanced */
.status-badge {
    padding: 0.3rem 0.8rem;
    font-size: 0.7rem;
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.status-badge.confirmed {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.status-badge.pending {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.status-badge.completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.status-badge.cancelled {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.status-badge.missed,
.status-badge.blocked {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

/* Pending Request Items */
.pending-request-item {
    border-left: 3px solid #f59e0b;
    transition: all 0.25s ease;
}

.pending-request-item:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.2);
}

.request-type-badge {
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* History Items */
.history-date {
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(100, 116, 139, 0.2);
}

.history-day {
    font-size: 1.2rem;
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .calendar-layout {
        grid-template-columns: 240px 1fr;
        gap: 0.875rem;
    }

    .calendar-day {
        min-height: 75px;
        padding: 0.4rem;
    }
}

@media (max-width: 1024px) {
    .calendar-layout {
        grid-template-columns: 1fr;
    }

    .calendar-sidebar {
        position: static;
        max-height: none;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0.75rem;
    }

    .calendar-main {
        order: -1;
    }
}

@media (max-width: 768px) {
    .calendar-container {
        padding: 0.75rem;
    }

    .calendar-header {
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
    }

    .calendar-main,
    .sidebar-card {
        padding: 0.5rem;
    }

    .nav-btn {
        width: 22px;
        height: 22px;
        font-size: 0.7rem;
    }

    .calendar-controls {
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .period-header {
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .legend-inline {
        width: 100%;
        flex-wrap: wrap;
        gap: 0.625rem;
    }

    .calendar-day {
        min-height: 70px;
        padding: 0.375rem;
    }

    .calendar-header-cell {
        padding: 0.5rem 0.375rem;
        font-size: 0.75rem;
    }

    .day-number {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }

    .event-item {
        padding: 0.2rem 0.3rem;
        font-size: 0.65rem;
    }
}

/* Scrollbar Styling */
.calendar-sidebar::-webkit-scrollbar {
    width: 6px;
}

.calendar-sidebar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.calendar-sidebar::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 3px;
}

.calendar-sidebar::-webkit-scrollbar-thumb:hover {
    background: #1976D2;
}

/* Appointment Form Section */
/* Appointment Action Buttons - Trendy Blue Accent */
.appointment-action-btn {
    padding: 0.5rem 1rem !important;
    font-size: 0.85rem !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    border: 2px solid #2196F3 !important;
    position: relative !important;
    overflow: hidden !important;
}

/* Emergency Button - Blue Gradient with Lightning Effect */
.appointment-action-btn.emergency-btn {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(33, 150, 243, 0.4) !important;
    border: 2px solid #1976D2 !important;
}

.appointment-action-btn.emergency-btn:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%) !important;
    box-shadow: 0 6px 20px rgba(33, 150, 243, 0.6) !important;
    transform: translateY(-2px) scale(1.02) !important;
    border-color: #1565C0 !important;
}

.appointment-action-btn.emergency-btn i {
    font-size: 1rem !important;
    filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.3));
    animation: pulse 2s infinite;
}

/* Reschedule Button - Light Blue with Darker Hover (Light Mode Only) */
.appointment-action-btn.reschedule-btn {
    background: linear-gradient(135deg, #E9FAFC 0%, #D0F4F8 100%) !important;
    color: #4DD3E0 !important;
    border: 2px solid #4DD3E0 !important;
    box-shadow: none !important;
    text-shadow: none !important;
    animation: none !important;
}

.appointment-action-btn.reschedule-btn:hover {
    background: linear-gradient(135deg, #4DD3E0 0%, #38B3C0 100%) !important;
    border-color: #38B3C0 !important;
    color: white !important;
    box-shadow: none !important;
    transform: translateY(-2px) scale(1.02) !important;
    text-shadow: none !important;
}

.appointment-action-btn.reschedule-btn i {
    color: #4DD3E0 !important;
    filter: none !important;
    transition: all 0.3s ease !important;
}

.appointment-action-btn.reschedule-btn:hover i {
    color: white !important;
    filter: none !important;
    animation: iconSpin 1.5s linear infinite !important;
}

.appointment-action-btn i {
    margin-right: 0 !important;
    font-size: 0.9rem !important;
    transition: all 0.3s ease !important;
}

/* Pulse animation for emergency button icon */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.1);
    }
}

/* Neon pulse animation for reschedule button */
@keyframes neonPulse {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(40, 150, 160, 0.6),
                    0 0 30px rgba(40, 150, 160, 0.4),
                    0 0 40px rgba(40, 150, 160, 0.3),
                    inset 0 0 15px rgba(255, 255, 255, 0.1);
    }
    50% {
        box-shadow: 0 4px 25px rgba(40, 150, 160, 0.8),
                    0 0 40px rgba(40, 150, 160, 0.6),
                    0 0 50px rgba(40, 150, 160, 0.5),
                    inset 0 0 18px rgba(255, 255, 255, 0.15);
    }
}

/* Icon spin animation for reschedule button */
@keyframes iconSpin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Calendar Navigation Layout */
.calendar-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: auto;
}

.appointment-form-section {
    background: transparent;
    border-radius: 0;
    padding: 0;
    margin-top: 0;
    box-shadow: none;
    border: none;
}

.form-header {
    text-align: center;
    margin-bottom: 0.875rem;
    padding-bottom: 0.625rem;
    border-bottom: 2px solid #2196F3;
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
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.toggle-btn {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.625rem;
		text-align: left;
		}

.toggle-btn:hover {
    border-color: #2196F3;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(33, 150, 243, 0.15);
}

.toggle-btn.active {
    border-color: #2196F3;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(25, 118, 210, 0.05) 100%);
    box-shadow: 0 4px 16px rgba(33, 150, 243, 0.2);
}

.toggle-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.toggle-btn.active .toggle-icon {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
}

.toggle-icon i {
    font-size: 1rem;
    color: #2196F3;
}

.toggle-btn.active .toggle-icon i {
    color: white;
}

.toggle-content {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.toggle-title {
    font-weight: 700;
    font-size: 0.85rem;
    color: #2C3E50;
    line-height: 1.2;
}

.toggle-desc {
    font-size: 0.75rem;
    color: #64748b;
    line-height: 1.2;
}

.appointment-form {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #2C3E50;
    margin-bottom: 0.4rem;
    font-size: 0.85rem;
}

.form-label i {
    color: #2196F3;
    font-size: 0.9rem;
}

.form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    font-family: inherit;
    resize: vertical;
    min-height: 90px;
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
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.input-with-icon {
    position: relative;
}

.form-select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    transition: all 0.3s;
    background: #f8f9fa;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.5rem;
}

.form-select:hover {
    border-color: #cbd5e1;
    background-color: white;
}

.form-select:focus {
    outline: none;
    border-color: #2196F3;
    background-color: white;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
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
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.appointment-info-box {
    margin-top: 0.75rem;
    padding: 0.875rem;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 10px;
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
    gap: 0.4rem;
    font-weight: 700;
    color: #1565C0;
    margin-bottom: 0.625rem;
    font-size: 0.85rem;
}

.info-header i {
    font-size: 0.95rem;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.4rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 6px;
    font-size: 0.8rem;
    color: #1e293b;
}

.info-item i {
    color: #2196F3;
    font-size: 0.9rem;
    width: 18px;
    text-align: center;
}

.info-item span {
    font-weight: 600;
}

.input-with-icon input {
    width: 100%;
    padding: 0.75rem 2.75rem 0.75rem 0.875rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    transition: all 0.3s;
    background: #f8f9fa;
}

.input-with-icon input:hover {
    border-color: #cbd5e1;
    background: white;
}

.input-with-icon i {
    position: absolute;
    right: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 1rem;
    pointer-events: none;
    transition: all 0.3s;
}

.input-with-icon input:focus {
    outline: none;
    border-color: #2196F3;
    background: white;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.input-with-icon input:focus + i {
    color: #2196F3;
}

.form-actions {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1.25rem;
}

.btn-submit {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
		cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 16px rgba(33, 150, 243, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(33, 150, 243, 0.4);
}

.btn-submit:active {
    transform: translateY(-1px);
}

.btn-submit i {
    font-size: 0.95rem;
}

.btn-cancel {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 16px rgba(100, 116, 139, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.btn-cancel:hover {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(100, 116, 139, 0.35);
}

.btn-cancel:active {
    transform: translateY(-1px);
}

.btn-cancel i {
    font-size: 0.95rem;
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
    max-height: calc(100vh - 280px);
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 12px;
}

/* Custom Scrollbar for Week View */
.week-view::-webkit-scrollbar {
    width: 6px;
}

.week-view::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.week-view::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 3px;
}

.week-view::-webkit-scrollbar-thumb:hover {
    background: #1976D2;
}

.week-header {
    display: grid;
    grid-template-columns: 75px repeat(7, 1fr);
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
    padding: 0.5rem 0.375rem;
    font-weight: 700;
    color: #64748b;
    text-align: center;
    font-size: 0.75rem;
}

.week-day-header {
    background: #f8fafc;
    padding: 0.5rem 0.375rem;
    text-align: center;
    transition: all 0.3s;
}

.week-day-header.today {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
}

.day-name {
    font-weight: 700;
    font-size: 0.75rem;
    margin-bottom: 0.125rem;
}

.week-day-header.today .day-name {
    color: white;
}

.day-date {
    font-size: 1rem;
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
    grid-template-columns: 75px repeat(7, 1fr);
    gap: 1px;
}

.time-slot {
    background: #f8fafc;
    padding: 0.375rem 0.25rem;
    font-weight: 600;
    color: #64748b;
    font-size: 0.7rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
}

.week-cell {
    background: white;
    padding: 0.375rem;
    min-height: 38px;
    position: relative;
}

.week-appointment {
    background: #e3f2fd;
    border-left: 2px solid #2196F3;
    padding: 0.3rem 0.375rem;
    border-radius: 4px;
    margin-bottom: 0.2rem;
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

.week-appointment.blocked,
.week-appointment.missed {
    background: #e5e7eb;
    border-left-color: #6b7280;
    color: #1f2937;
    cursor: not-allowed;
}

.week-appointment.blocked:hover,
.week-appointment.missed:hover {
    transform: none;
    box-shadow: none;
}

.week-apt-time {
    font-size: 0.65rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.15rem;
}

.week-apt-title {
    font-size: 0.7rem;
    color: #64748b;
    line-height: 1.2;
}

.week-apt-notes {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.25rem;
    opacity: 0.85;
    line-height: 1.2;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* Day View Styles - Compact to Fit Screen */
.day-view {
    width: 100%;
    max-height: calc(100vh - 300px);
    overflow-y: auto;
}

.day-view-header {
    text-align: center;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 10px;
    margin-bottom: 1rem;
    color: white;
}

.day-view-title {
    font-size: clamp(1.15rem, 2vw, 1.5rem);
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.day-view-date {
    font-size: 0.9rem;
    margin: 0;
    opacity: 0.9;
}

.day-view-body {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    max-height: calc(100vh - 360px);
    overflow-y: auto;
}

/* Custom Scrollbar for Day View */
.day-view-body::-webkit-scrollbar {
    width: 6px;
}

.day-view-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.day-view-body::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 3px;
}

.day-view-body::-webkit-scrollbar-thumb:hover {
    background: #1976D2;
}

[data-theme="dark"] .day-view-body::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .day-view-body::-webkit-scrollbar-thumb {
    background: #3b82f6 !important;
}

[data-theme="dark"] .day-view-body::-webkit-scrollbar-thumb:hover {
    background: #2563eb !important;
}

.day-time-row {
    display: grid;
    grid-template-columns: 90px 1fr;
    gap: 1px;
    background: #e2e8f0;
    min-height: 0;
}

.day-time-label {
    background: #f8fafc;
    padding: 0.625rem 0.5rem;
    font-weight: 700;
    color: #64748b;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.day-time-content {
    background: white;
    padding: 0.5rem;
    min-height: 38px;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    justify-content: center;
}

.day-appointment {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-left: 3px solid #2196F3;
    padding: 0.625rem;
    border-radius: 6px;
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

.day-appointment.blocked,
.day-appointment.missed {
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    border-left-color: #6b7280;
    color: #1f2937;
    cursor: not-allowed;
}

.day-appointment.blocked:hover,
.day-appointment.missed:hover {
    transform: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.day-apt-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.4rem;
    gap: 0.5rem;
}

.day-apt-time {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.day-apt-time i {
    font-size: 0.75rem;
}

.day-apt-badge {
    padding: 0.2rem 0.625rem;
    border-radius: 16px;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
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
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.3rem;
    line-height: 1.3;
}

.day-apt-notes {
    font-size: 0.75rem;
    color: #64748b;
    display: flex;
    align-items: center;
    padding-top: 0.375rem;
    border-top: 1px solid rgba(0,0,0,0.05);
}

.day-apt-notes i {
    font-size: 0.7rem;
}

.day-empty-slot {
    color: #94a3b8;
    font-style: italic;
    text-align: center;
    padding: 0.25rem 0.375rem;
    font-size: 0.75rem;
    min-height: 0;
    line-height: 1.2;
    margin: 0;
}

/* Make empty time slots even more compact */
.day-time-content:has(.day-empty-slot) {
    min-height: 28px;
    padding: 0.25rem 0.375rem;
    justify-content: center;
}

/* Reduce min-height for rows with appointments but make them flexible */
.day-time-content:has(.day-appointment) {
    min-height: auto;
}

/* Responsive adjustments for week and day views */
@media (max-width: 768px) {
    .week-header, .week-row {
        grid-template-columns: 70px repeat(7, minmax(90px, 1fr));
    }

    .day-time-row {
        grid-template-columns: 80px 1fr;
    }

    .day-view-title {
        font-size: clamp(1rem, 1.75vw, 1.25rem);
    }

    .day-view-header {
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
    }

    .day-time-label {
        padding: 0.5rem 0.375rem;
        font-size: 0.75rem;
    }

    .day-time-content {
        min-height: 40px;
        padding: 0.4rem;
    }

    .day-appointment {
        padding: 0.5rem;
    }

    .day-apt-title {
        font-size: 0.85rem;
    }

    .day-apt-time {
        font-size: 0.75rem;
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

/* Container and Header Dark Mode */
[data-theme="dark"] .calendar-container {
    background: var(--dm-bg-primary, #0f172a) !important;
}

[data-theme="dark"] .calendar-header {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%) !important;
    border-left-color: #3b82f6 !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .page-title::before {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

[data-theme="dark"] .page-subtitle {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Sidebar Dark Mode Enhancements */
[data-theme="dark"] .sidebar-card:hover {
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3) !important;
    border-color: rgba(59, 130, 246, 0.5) !important;
}

[data-theme="dark"] .sidebar-title {
    border-bottom-color: rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .sidebar-title i {
    color: #60a5fa !important;
}

/* Calendar Controls Dark Mode */
[data-theme="dark"] .calendar-controls {
    border-bottom-color: rgba(59, 130, 246, 0.2) !important;
}

[data-theme="dark"] .period-title {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
}

/* Legend Dark Mode */
[data-theme="dark"] .legend-item {
    background: rgba(59, 130, 246, 0.1) !important;
}

[data-theme="dark"] .legend-item:hover {
    background: rgba(59, 130, 246, 0.2) !important;
}

[data-theme="dark"] .legend-text {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Sidebar Scrollbar Dark Mode */
[data-theme="dark"] .calendar-sidebar::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .calendar-sidebar::-webkit-scrollbar-thumb {
    background: #3b82f6 !important;
}

[data-theme="dark"] .calendar-sidebar::-webkit-scrollbar-thumb:hover {
    background: #2563eb !important;
}

/* Tabbed Section Dark Mode */
[data-theme="dark"] .tab-buttons {
    border-bottom-color: rgba(59, 130, 246, 0.2) !important;
}

[data-theme="dark"] .tab-btn {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .tab-btn:hover {
    background: rgba(59, 130, 246, 0.1) !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .tab-btn.active {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.1) 100%) !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .tab-btn.active::after {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

[data-theme="dark"] .tab-btn.active i {
    color: #60a5fa !important;
}

[data-theme="dark"] .tab-btn i {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .tab-content-wrapper::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .tab-content-wrapper::-webkit-scrollbar-thumb {
    background: #3b82f6 !important;
}

[data-theme="dark"] .tab-content-wrapper::-webkit-scrollbar-thumb:hover {
    background: #2563eb !important;
}

[data-theme="dark"] .empty-state i {
    color: #00EAFF !important;
    filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.6))
            drop-shadow(0 0 12px rgba(0, 234, 255, 0.4)) !important;
}

[data-theme="dark"] .empty-state p {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Upcoming Items Dark Mode */
[data-theme="dark"] .upcoming-item:hover,
[data-theme="dark"] .history-item:hover {
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .upcoming-date {
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4) !important;
}


/* History Date Dark Mode */
[data-theme="dark"] .history-date {
    box-shadow: 0 2px 6px rgba(100, 116, 139, 0.3) !important;
}

/* Calendar Grid Dark Mode */
[data-theme="dark"] .calendar-header-row {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .calendar-header-cell {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-right: 1px solid var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .calendar-header-cell:last-child {
    border-right: none !important;
}

[data-theme="dark"] .calendar-body {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .calendar-week {
    background: transparent !important;
}

[data-theme="dark"] .calendar-day {
    background: var(--dm-card-bg, #1e293b) !important;
    border-right: 1px solid var(--dm-border-color, #334155) !important;
    border-bottom: 1px solid var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .calendar-day:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .calendar-day.today {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .calendar-day.today .day-number {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

[data-theme="dark"] .day-number {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .event-item {
    color: white !important;
}

[data-theme="dark"] .event-item.pending {
    background: #fbbf24 !important;
    border-left-color: #f59e0b !important;
}

[data-theme="dark"] .event-item.confirmed {
    background: #3b82f6 !important;
    border-left-color: #2563eb !important;
}

[data-theme="dark"] .event-item.completed {
    background: #10b981 !important;
    border-left-color: #059669 !important;
}

[data-theme="dark"] .event-item.cancelled {
    background: #ef4444 !important;
    border-left-color: #dc2626 !important;
}

[data-theme="dark"] .event-item.blocked {
    background: #6b7280 !important;
    border-left-color: #4b5563 !important;
}

[data-theme="dark"] .event-time,
[data-theme="dark"] .event-title {
    color: white !important;
}

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
[data-theme="dark"] .day-view {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .day-view-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

[data-theme="dark"] .day-view-body {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .day-time-row {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .day-time-label {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .day-time-content {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .day-empty-slot {
    color: var(--dm-text-muted, #64748b) !important;
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

/* Dark Mode Appointment Action Buttons */
[data-theme="dark"] .appointment-action-btn.emergency-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5) !important;
    border-color: #2563eb !important;
}

[data-theme="dark"] .appointment-action-btn.emergency-btn:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.7) !important;
    transform: translateY(-2px) scale(1.02) !important;
    border-color: #1d4ed8 !important;
}

[data-theme="dark"] .appointment-action-btn.reschedule-btn {
    background: linear-gradient(135deg, #00EAFF 0%, #0099CC 100%) !important;
    color: white !important;
    border: 2px solid #00EAFF !important;
    box-shadow: 0 4px 25px rgba(0, 234, 255, 0.6),
                0 0 35px rgba(0, 234, 255, 0.4),
                0 0 50px rgba(0, 234, 255, 0.3),
                inset 0 0 20px rgba(255, 255, 255, 0.15) !important;
    text-shadow: 0 0 12px rgba(255, 255, 255, 0.6),
                 0 0 25px rgba(0, 234, 255, 0.6) !important;
    animation: neonPulse 2s ease-in-out infinite !important;
}

[data-theme="dark"] .appointment-action-btn.reschedule-btn:hover {
    background: linear-gradient(135deg, #00FFFF 0%, #00B8D4 100%) !important;
    border-color: #00FFFF !important;
    box-shadow: 0 6px 35px rgba(0, 234, 255, 0.8),
                0 0 50px rgba(0, 234, 255, 0.6),
                0 0 70px rgba(0, 234, 255, 0.4),
                inset 0 0 25px rgba(255, 255, 255, 0.25) !important;
    transform: translateY(-3px) scale(1.05) !important;
    text-shadow: 0 0 18px rgba(255, 255, 255, 0.9),
                 0 0 35px rgba(0, 234, 255, 0.9),
                 0 0 50px rgba(0, 234, 255, 0.7) !important;
}

[data-theme="dark"] .appointment-action-btn.reschedule-btn:hover i {
    color: white !important;
    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8))
            drop-shadow(0 0 12px rgba(0, 234, 255, 0.8)) !important;
    animation: iconSpin 1.5s linear infinite !important;
}

[data-theme="dark"] .appointment-action-btn.reschedule-btn i {
    color: white !important;
    filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.6))
            drop-shadow(0 0 10px rgba(0, 234, 255, 0.6)) !important;
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
// Open Appointment Modal with Type
function openAppointmentModal(type) {
    // Set appointment type
    appointmentType = type;

    // Hide both forms
    const emergencyFormSection = document.getElementById('emergencyFormSection');
    const rescheduleFormSection = document.getElementById('rescheduleFormSection');

        if (type === 'emergency') {
        // Show emergency form, hide reschedule form
        if (emergencyFormSection) emergencyFormSection.style.display = 'block';
        if (rescheduleFormSection) rescheduleFormSection.style.display = 'none';

        // Update modal title
        const modalTitle = document.getElementById('appointmentRequestModalLabel');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-lightning-charge-fill me-2"></i>Emergency Appointment';
        }
    } else if (type === 'reschedule') {
        // Show reschedule form, hide emergency form
        if (emergencyFormSection) emergencyFormSection.style.display = 'none';
        if (rescheduleFormSection) rescheduleFormSection.style.display = 'block';

        // Update modal title
        const modalTitle = document.getElementById('appointmentRequestModalLabel');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Request Reschedule';
        }
    }
}

// Tab Switching Function
function switchTab(tabName) {
    // Remove active class from all tabs and buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });

    // Add active class to selected tab button
    const activeBtn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }

    // Show selected tab content
    const activeContent = document.getElementById(`tab-${tabName}`);
    if (activeContent) {
        activeContent.classList.add('active');
    }
}

var appointmentType = 'emergency';

// Removed updateCancelButtonVisibility function - cancel button no longer needed

// Handle emergency service selection dropdown
document.addEventListener('DOMContentLoaded', function() {
    const emergencyServiceSelect = document.getElementById('emergencyServiceSelect');
    const emergencyOtherConcernGroup = document.getElementById('emergencyOtherConcernGroup');
    const emergencyOtherConcernInput = document.getElementById('emergencyOtherConcern');

    if (emergencyServiceSelect && emergencyOtherConcernGroup && emergencyOtherConcernInput) {
        emergencyServiceSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                emergencyOtherConcernGroup.style.display = 'block';
                emergencyOtherConcernInput.required = true;
            } else {
                emergencyOtherConcernGroup.style.display = 'none';
                emergencyOtherConcernInput.required = false;
                emergencyOtherConcernInput.value = '';
            }
        });
    }
});

// Handle reschedule appointment selection dropdown
document.addEventListener('DOMContentLoaded', function() {
    const rescheduleAppointmentSelect = document.getElementById('rescheduleAppointmentSelect');
    const rescheduleSelectedAppointmentInfo = document.getElementById('rescheduleSelectedAppointmentInfo');
    const rescheduleInfoService = document.getElementById('rescheduleInfoService');
    const rescheduleInfoDateTime = document.getElementById('rescheduleInfoDateTime');
    const rescheduleForm = document.getElementById('rescheduleForm');

    if (rescheduleAppointmentSelect && rescheduleSelectedAppointmentInfo) {
        rescheduleAppointmentSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const service = selectedOption.dataset.service;
                const datetime = selectedOption.dataset.datetime;
                const serviceId = selectedOption.dataset.serviceId;
                const duration = selectedOption.dataset.duration;
                const reasonForVisit = selectedOption.dataset.reasonForVisit;

                // Show info box
                rescheduleSelectedAppointmentInfo.style.display = 'block';
                rescheduleInfoService.textContent = service;
                rescheduleInfoDateTime.textContent = datetime;

                // Store appointment info in form dataset
                if (rescheduleForm) {
                    rescheduleForm.dataset.originalAppointmentId = this.value;
                    rescheduleForm.dataset.serviceId = serviceId;
                    rescheduleForm.dataset.duration = duration;
                    rescheduleForm.dataset.reasonForVisit = reasonForVisit;
                }

                // Optionally pre-fill the reason field
                const rescheduleReasonField = document.getElementById('rescheduleReason');
                if (rescheduleReasonField && !rescheduleReasonField.value) {
                    rescheduleReasonField.value = `I would like to reschedule my ${service} appointment that was originally scheduled for ${datetime}.`;
                }
            } else {
                // Hide info box if no selection
                rescheduleSelectedAppointmentInfo.style.display = 'none';
                if (rescheduleForm) {
                    rescheduleForm.dataset.originalAppointmentId = '';
                    rescheduleForm.dataset.serviceId = '';
                    rescheduleForm.dataset.duration = '';
                    rescheduleForm.dataset.reasonForVisit = '';
            }
            }
        });
    }
});

// Removed cancel button functionality - no longer needed

// Global function to get request duration (used by form handlers)
function getRequestDuration() {
    let duration = 30; // Default 30 minutes for "Other" services

    // If emergency mode and service is selected
    if (typeof appointmentType !== 'undefined' && appointmentType === 'emergency') {
        const emergencyServiceSelect = document.getElementById('emergencyServiceSelect');
        if (emergencyServiceSelect && emergencyServiceSelect.value && emergencyServiceSelect.value !== 'other') {
            const selectedOption = emergencyServiceSelect.options[emergencyServiceSelect.selectedIndex];
            const serviceDuration = selectedOption.getAttribute('data-duration');
            if (serviceDuration) {
                duration = parseInt(serviceDuration);
            }
        }
    }
    // If reschedule mode, get duration from the selected appointment
    else if (typeof appointmentType !== 'undefined' && appointmentType === 'reschedule') {
        const rescheduleAppointmentSelect = document.getElementById('rescheduleAppointmentSelect');
        if (rescheduleAppointmentSelect && rescheduleAppointmentSelect.value) {
            const selectedOption = rescheduleAppointmentSelect.options[rescheduleAppointmentSelect.selectedIndex];
            const aptDuration = selectedOption.getAttribute('data-duration');
            if (aptDuration) {
                duration = parseInt(aptDuration);
            }
        }
    }

    return duration;
}

// Form submission handler
// Handle Emergency Form Submission
document.getElementById('emergencyForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitButton = this.querySelector('.btn-submit');
    const originalText = submitButton.innerHTML;

    // Disable button and show loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';

    const formData = {
        type: 'emergency',
        reason: document.getElementById('emergencyReason').value,
        date: document.getElementById('emergencyDate').value,
        time: document.getElementById('emergencyTime').value,
        _token: '{{ csrf_token() }}'
    };

    // Include service information for emergency
    const serviceSelect = document.getElementById('emergencyServiceSelect');
    const otherConcern = document.getElementById('emergencyOtherConcern');

    if (serviceSelect && serviceSelect.value) {
        if (serviceSelect.value === 'other') {
            formData.service_id = null;
            formData.other_concern = otherConcern ? otherConcern.value : '';
        } else {
            formData.service_id = serviceSelect.value;
            formData.other_concern = null;
        }
    }

    appointmentType = 'emergency';

    // Check for blocked/closed times before submitting
    const selectedDate = formData.date;
    const selectedTime = formData.time;

    if (selectedDate && selectedTime) {
        const [hours, minutes] = selectedTime.split(':').map(Number);
        const requestedStart = new Date(selectedDate);
        requestedStart.setHours(hours, minutes, 0);

        const duration = getRequestDuration();
        const requestedEnd = new Date(requestedStart.getTime() + duration * 60000);

        // Check blocked times
        const isBlocked = window.blockedTimes.some(blockedTime => {
            const blockStart = parseLocalDateTime(blockedTime.start_datetime);
            const blockEnd = parseLocalDateTime(blockedTime.end_datetime);
            if (!blockStart || !blockEnd) return false;

            // Check if on the same date
            if (blockStart.toDateString() !== requestedStart.toDateString()) return false;

            // Check for overlap
            return (requestedStart < blockEnd && requestedEnd > blockStart);
        });

        if (isBlocked) {
            // Find the blocking time for details
            const blockingTime = window.blockedTimes.find(blockedTime => {
                const blockStart = parseLocalDateTime(blockedTime.start_datetime);
                const blockEnd = parseLocalDateTime(blockedTime.end_datetime);
                if (!blockStart || !blockEnd) return false;

                if (blockStart.toDateString() !== requestedStart.toDateString()) return false;
                return (requestedStart < blockEnd && requestedEnd > blockStart);
            });

                if (blockingTime) {
                const blockStart = parseLocalDateTime(blockingTime.start_datetime);
                const blockEnd = parseLocalDateTime(blockingTime.end_datetime);
                const isFullDayClosure = blockStart && blockEnd &&
                    blockStart.getHours() === 0 && blockStart.getMinutes() === 0 &&
                    blockEnd.getHours() === 23 && blockEnd.getMinutes() === 59;

                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;

                if (isFullDayClosure) {
                    // Show clinic closed modal (red) - separate feedback for clinic closure
                    const conflictDate = new Date(selectedDate).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    document.getElementById('clinicClosedMessage').textContent = 'The clinic is closed on this date. Please select a different date for your appointment request.';
                    document.getElementById('clinicClosedDate').textContent = conflictDate;

                    // Show clinic closed modal
                    new bootstrap.Modal(document.getElementById('patientClinicClosedModal')).show();
                } else {
                    // Show blocked time modal (yellow/warning) - for partial time blocks
                    const conflictDate = new Date(selectedDate).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    const conflictTime = `${selectedTime}`;

                    document.getElementById('patientConflictMessage').textContent = 'This time slot is blocked. Please select a different time slot for your appointment request.';
                    document.getElementById('patientConflictDate').textContent = conflictDate;
                    document.getElementById('patientConflictTime').textContent = conflictTime;

                    // Show conflict modal
                    new bootstrap.Modal(document.getElementById('patientAppointmentConflictModal')).show();
                }
                return; // Stop submission
            }
        }
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
            // Close the modal
            const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentRequestModal'));
            if (appointmentModal) {
                appointmentModal.hide();
            }

            // Show success message at the top of the page
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.style.position = 'fixed';
            alert.style.top = '80px';
            alert.style.left = '50%';
            alert.style.transform = 'translateX(-50%)';
            alert.style.zIndex = '9999';
            alert.style.minWidth = '300px';
            alert.style.maxWidth = '600px';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                <strong>Success!</strong> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);

            // Scroll to top to see the message
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Reset form
            this.reset();

            // Reset emergency form fields
            const emergencyServiceSelect = document.getElementById('emergencyServiceSelect');
            const emergencyOtherConcernGroup = document.getElementById('emergencyOtherConcernGroup');
            const emergencyOtherConcern = document.getElementById('emergencyOtherConcern');
            if (emergencyServiceSelect) emergencyServiceSelect.value = '';
            if (emergencyOtherConcernGroup) emergencyOtherConcernGroup.style.display = 'none';
            if (emergencyOtherConcern) {
                emergencyOtherConcern.value = '';
                emergencyOtherConcern.required = false;
            }

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
            // Check if error is due to blocked/closed time
            if (data.message && (data.message.includes('closed') || data.message.includes('blocked'))) {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;

                if (data.message.includes('closed')) {
                    // Show clinic closed modal (red) - separate feedback for clinic closure
                    const conflictDate = new Date(formData.date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    document.getElementById('clinicClosedMessage').textContent = data.message;
                    document.getElementById('clinicClosedDate').textContent = conflictDate;

                    // Show clinic closed modal
                    new bootstrap.Modal(document.getElementById('patientClinicClosedModal')).show();
                } else {
                    // Show blocked time modal (yellow/warning) - for partial time blocks
                    const conflictDate = new Date(formData.date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    const conflictTime = formData.time;

                    document.getElementById('patientConflictMessage').textContent = data.message;
                    document.getElementById('patientConflictDate').textContent = conflictDate;
                    document.getElementById('patientConflictTime').textContent = conflictTime;

                    // Show conflict modal
                    new bootstrap.Modal(document.getElementById('patientAppointmentConflictModal')).show();
                }
        } else {
            throw new Error(data.message || 'Failed to submit request');
            }
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

        // Re-enable button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    })
    .finally(() => {
        // Re-enable button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

// Handle Reschedule Form Submission
document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitButton = this.querySelector('.btn-submit');
    const originalText = submitButton.innerHTML;

    // Disable button and show loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';

    const formData = {
        type: 'reschedule',
        reason: document.getElementById('rescheduleReason').value,
        date: document.getElementById('rescheduleDate').value,
        time: document.getElementById('rescheduleTime').value,
        _token: '{{ csrf_token() }}'
    };

    // Include appointment ID for reschedule
    const appointmentSelect = document.getElementById('rescheduleAppointmentSelect');
    if (appointmentSelect && appointmentSelect.value) {
        const selectedOption = appointmentSelect.options[appointmentSelect.selectedIndex];
        const appointmentStatus = selectedOption.dataset.status;

        // Prevent rescheduling missed appointments
        if (appointmentStatus && appointmentStatus.toLowerCase() === 'missed') {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            alert('Cannot reschedule missed appointments. Please book a new appointment instead.');
            return;
        }

        formData.existing_appointment_id = appointmentSelect.value;
    } else {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
        alert('Please select an appointment to reschedule.');
        return;
    }

    appointmentType = 'reschedule';

    // Check for blocked/closed times before submitting
    const selectedDate = formData.date;
    const selectedTime = formData.time;

    if (selectedDate && selectedTime) {
        const [hours, minutes] = selectedTime.split(':').map(Number);
        const requestedStart = new Date(selectedDate);
        requestedStart.setHours(hours, minutes, 0);

        const duration = getRequestDuration();
        const requestedEnd = new Date(requestedStart.getTime() + duration * 60000);

        // Check blocked times
        const isBlocked = window.blockedTimes.some(blockedTime => {
            const blockStart = parseLocalDateTime(blockedTime.start_datetime);
            const blockEnd = parseLocalDateTime(blockedTime.end_datetime);
            if (!blockStart || !blockEnd) return false;

            // Check if on the same date
            if (blockStart.toDateString() !== requestedStart.toDateString()) return false;

            // Check for overlap
            return (requestedStart < blockEnd && requestedEnd > blockStart);
        });

        if (isBlocked) {
            // Find the blocking time for details
            const blockingTime = window.blockedTimes.find(blockedTime => {
                const blockStart = parseLocalDateTime(blockedTime.start_datetime);
                const blockEnd = parseLocalDateTime(blockedTime.end_datetime);
                if (!blockStart || !blockEnd) return false;

                if (blockStart.toDateString() !== requestedStart.toDateString()) return false;
                return (requestedStart < blockEnd && requestedEnd > blockStart);
            });

                if (blockingTime) {
                const blockStart = parseLocalDateTime(blockingTime.start_datetime);
                const blockEnd = parseLocalDateTime(blockingTime.end_datetime);
                const isFullDayClosure = blockStart && blockEnd &&
                    blockStart.getHours() === 0 && blockStart.getMinutes() === 0 &&
                    blockEnd.getHours() === 23 && blockEnd.getMinutes() === 59;

                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;

                if (isFullDayClosure) {
                    // Show clinic closed modal (red) - separate feedback for clinic closure
                    const conflictDate = new Date(selectedDate).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    document.getElementById('clinicClosedMessage').textContent = 'The clinic is closed on this date. Please select a different date for your appointment request.';
                    document.getElementById('clinicClosedDate').textContent = conflictDate;

                    // Show clinic closed modal
                    new bootstrap.Modal(document.getElementById('patientClinicClosedModal')).show();
            } else {
                    // Show blocked time modal (yellow/warning) - for partial time blocks
                    const conflictDate = new Date(selectedDate).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    const conflictTime = selectedTime;

                    document.getElementById('patientConflictMessage').textContent = 'This time slot is unavailable. Please select a different time.';
                    document.getElementById('patientConflictDate').textContent = conflictDate;
                    document.getElementById('patientConflictTime').textContent = conflictTime;

                    // Show conflict modal
                    new bootstrap.Modal(document.getElementById('patientAppointmentConflictModal')).show();
                }

                return; // Don't submit if blocked
            }
        }
    }

    // Submit the form
    fetch('{{ route('patient-calendar.submit-request') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close the modal
            const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentRequestModal'));
            if (appointmentModal) {
                appointmentModal.hide();
            }

            // Show success message at the top of the page
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.style.position = 'fixed';
            alert.style.top = '80px';
            alert.style.left = '50%';
            alert.style.transform = 'translateX(-50%)';
            alert.style.zIndex = '9999';
            alert.style.minWidth = '300px';
            alert.style.maxWidth = '600px';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                <strong>Success!</strong> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);

            // Scroll to top to see the message
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Reset form
            this.reset();

            // Reset reschedule form fields
            const rescheduleAppointmentSelect = document.getElementById('rescheduleAppointmentSelect');
            const rescheduleSelectedAppointmentInfo = document.getElementById('rescheduleSelectedAppointmentInfo');
            if (rescheduleAppointmentSelect) rescheduleAppointmentSelect.value = '';
            if (rescheduleSelectedAppointmentInfo) rescheduleSelectedAppointmentInfo.style.display = 'none';

            // Auto-dismiss after 5 seconds
            setTimeout(() => alert.remove(), 5000);
        } else {
            // Check if error is due to blocked/closed time
            if (data.message && (data.message.includes('closed') || data.message.includes('blocked'))) {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;

                if (data.message.includes('closed')) {
                    // Show clinic closed modal (red) - separate feedback for clinic closure
                    const conflictDate = new Date(formData.date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    document.getElementById('clinicClosedMessage').textContent = data.message;
                    document.getElementById('clinicClosedDate').textContent = conflictDate;

                    // Show clinic closed modal
                    new bootstrap.Modal(document.getElementById('patientClinicClosedModal')).show();
                } else {
                    // Show blocked time modal (yellow/warning) - for partial time blocks
                    const conflictDate = new Date(formData.date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    const conflictTime = formData.time;

                    document.getElementById('patientConflictMessage').textContent = data.message;
                    document.getElementById('patientConflictDate').textContent = conflictDate;
                    document.getElementById('patientConflictTime').textContent = conflictTime;

                    // Show conflict modal
                    new bootstrap.Modal(document.getElementById('patientAppointmentConflictModal')).show();
                }
        } else {
            throw new Error(data.message || 'Failed to submit request');
            }
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

        // Re-enable button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
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

<!-- Patient Appointment Conflict Modal (Blocked Time Slot) -->
<div class="modal fade" id="patientAppointmentConflictModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Time Slot Not Available
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-octagon-fill text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Time Slot is Blocked</h4>
                    <p class="text-muted mb-0" id="patientConflictMessage">This time slot is blocked. Please select a different time slot for your appointment request.</p>
                </div>
                <div class="bg-light rounded p-3 mb-3">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <div><i class="bi bi-calendar-event text-warning me-2"></i><span id="patientConflictDate">-</span></div>
                        <div><i class="bi bi-clock text-warning me-2"></i><span id="patientConflictTime">-</span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 bg-light">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-1"></i>Select Different Time
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Patient Clinic Closed Modal (Full Day Closure) -->
<div class="modal fade" id="patientClinicClosedModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-x-circle-fill me-2"></i>Clinic Closed
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #dc2626, #b91c1c); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-circle-fill text-white" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Clinic is Closed on This Date</h4>
                    <p class="text-muted mb-0" id="clinicClosedMessage">The clinic is closed on this date. Please select a different date for your appointment request.</p>
                </div>
                <div class="bg-light rounded p-3 mb-3" style="border-left: 4px solid #dc2626;">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <div><i class="bi bi-calendar-x text-danger me-2"></i><span id="clinicClosedDate" class="fw-bold">-</span></div>
                        <div class="mt-2">
                            <i class="bi bi-info-circle text-danger me-1"></i>
                            <small class="text-muted">No appointments available on this date</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 bg-light">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="bi bi-calendar-event me-1"></i>Select Different Date
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to parse datetime string as LOCAL time (must be defined before use)
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

// Pass appointments data to JavaScript
var appointmentsData = @json($appointments ?? []);
var allAppointmentsData = @json($allAppointments ?? []);
var blockedTimesData = @json($blockedTimes ?? []);

// Ensure arrays are properly formatted (convert objects to arrays if needed)
window.patientAppointments = Array.isArray(appointmentsData) ? appointmentsData : Object.values(appointmentsData || []);
window.allAppointments = Array.isArray(allAppointmentsData) ? allAppointmentsData : Object.values(allAppointmentsData || []);
window.blockedTimes = Array.isArray(blockedTimesData) ? blockedTimesData : Object.values(blockedTimesData || []);

// Debug: Verify data is loaded
console.log('Blade template: Appointments data set', {
    patientAppointments: window.patientAppointments ? window.patientAppointments.length : 0,
    allAppointments: window.allAppointments ? window.allAppointments.length : 0,
    blockedTimes: window.blockedTimes ? window.blockedTimes.length : 0,
    rawAppointmentsCount: {{ count($appointments ?? []) }}
});
if (window.patientAppointments && window.patientAppointments.length > 0) {
    console.log('First 3 appointments:', window.patientAppointments.slice(0, 3));
}

// Server time synchronization - CRITICAL for fault tolerance
let serverTimeData = null;
let serverTimeOffset = 0; // Offset between server time and client time (in ms)

// Function to fetch and sync server time
async function syncServerTime() {
    try {
        const response = await fetch('/patient/calendar/server-time', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        if (data.success) {
            serverTimeData = data;
            // Calculate offset: server timestamp - client timestamp
            const clientNow = Date.now();
            // Server timestamp is in seconds, convert to milliseconds
            const serverTimestampMs = data.server_timestamp * 1000;
            serverTimeOffset = serverTimestampMs - clientNow;

            console.log('Server time synced:', {
                server_time: data.server_time,
                client_time: new Date(clientNow).toISOString(),
                offset_ms: serverTimeOffset,
                offset_seconds: Math.round(serverTimeOffset / 1000)
            });

            // Warn if time skew is too large (> 5 minutes)
            const skewSeconds = Math.abs(serverTimeOffset / 1000);
            if (skewSeconds > 300) { // 5 minutes
                console.warn('WARNING: Significant time skew detected:', {
                    skew_seconds: skewSeconds,
                    skew_minutes: Math.round(skewSeconds / 60)
                });
            }
        }
    } catch (error) {
        console.error('Error syncing server time:', error);
        // Fall back to client time, but server-side validation will catch errors
        serverTimeOffset = 0;
    }
}

// Function to get current server time as Date object
function getServerTime() {
    if (serverTimeData) {
        // Calculate server time: client time + offset
        const serverTimestampMs = (serverTimeData.server_timestamp * 1000) + (Date.now() - (serverTimeData.server_timestamp * 1000) + serverTimeOffset);
        return new Date(serverTimestampMs);
    }
    // Fallback to client time if server time not synced yet
    return new Date();
}

// Sync server time on page load
syncServerTime();

// Re-sync server time periodically (every 5 minutes) and before critical operations
setInterval(syncServerTime, 5 * 60 * 1000);

// Modal button event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Handle reschedule request from modal
    const modalRescheduleBtn = document.getElementById('modalRescheduleBtn');
    if (modalRescheduleBtn) {
        modalRescheduleBtn.addEventListener('click', function() {
            const modal = document.getElementById('appointmentDetailsModal');
            const appointmentId = modal.dataset.appointmentId;

            // Check if appointment is missed - prevent rescheduling
            const appointment = window.patientAppointments ? window.patientAppointments.find(function(apt) {
                return apt.id == appointmentId;
            }) : null;

            if (appointment && appointment.status && appointment.status.toLowerCase() === 'missed') {
                alert('Cannot reschedule missed appointments. Please book a new appointment instead.');
                return;
            }

            // Close the details modal
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();

            // Scroll to the reschedule form section
            // Open the appointment modal
            const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentRequestModal'));
            appointmentModal.show();

            // Activate reschedule mode
            openAppointmentModal('reschedule');

            // Select the appointment in the dropdown
            const rescheduleAppointmentSelect = document.getElementById('rescheduleAppointmentSelect');
            if (rescheduleAppointmentSelect && appointmentId) {
                rescheduleAppointmentSelect.value = appointmentId;
                // Trigger change event to show appointment info
                rescheduleAppointmentSelect.dispatchEvent(new Event('change'));
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
    // Get date and time inputs dynamically based on active form
    function getActiveFormInputs() {
        const emergencyFormSection = document.getElementById('emergencyFormSection');
        const rescheduleFormSection = document.getElementById('rescheduleFormSection');

        let dateInput, timeInput;
        if (emergencyFormSection && emergencyFormSection.style.display !== 'none') {
            dateInput = document.getElementById('emergencyDate');
            timeInput = document.getElementById('emergencyTime');
        } else if (rescheduleFormSection && rescheduleFormSection.style.display !== 'none') {
            dateInput = document.getElementById('rescheduleDate');
            timeInput = document.getElementById('rescheduleTime');
        }
        return { dateInput, timeInput };
    }

        // getRequestDuration is now defined globally above
        // parseLocalDateTime is now defined globally above

        // Function to check if a time conflicts with existing appointments or blocked times
        function isTimeSlotAvailable(selectedDate, selectedTime) {
            if (!selectedDate || !selectedTime) return true;

            // CRITICAL: Use server time to validate past dates (fault tolerant)
            const [hours, minutes] = selectedTime.split(':').map(Number);
            const requestedStart = new Date(selectedDate);
            requestedStart.setHours(hours, minutes, 0);

            // Check if the requested time is in the past (using server time)
            const serverNow = getServerTime();
            if (requestedStart < serverNow) {
                return false; // Time is in the past
            }

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
            const { dateInput, timeInput } = getActiveFormInputs();
            if (!dateInput || !timeInput) return;

            const selectedDate = dateInput.value;
            const selectedTime = timeInput.value;

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
                const timeFormGroup = timeInput.closest('.form-group');
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

        // Add event listeners for emergency form
        const emergencyDateInput = document.getElementById('emergencyDate');
        const emergencyTimeInput = document.getElementById('emergencyTime');
        if (emergencyDateInput && emergencyTimeInput) {
            emergencyDateInput.addEventListener('change', updateTimeAvailability);
            emergencyTimeInput.addEventListener('change', updateTimeAvailability);
            emergencyTimeInput.addEventListener('input', updateTimeAvailability);
        }

        // Add event listeners for reschedule form
        const rescheduleDateInput = document.getElementById('rescheduleDate');
        const rescheduleTimeInput = document.getElementById('rescheduleTime');
        if (rescheduleDateInput && rescheduleTimeInput) {
            rescheduleDateInput.addEventListener('change', updateTimeAvailability);
            rescheduleTimeInput.addEventListener('change', updateTimeAvailability);
            rescheduleTimeInput.addEventListener('input', updateTimeAvailability);
        }

        // Add listener for emergency service selection changes to re-check availability
        const emergencyServiceSelect = document.getElementById('emergencyServiceSelect');
        if (emergencyServiceSelect) {
            emergencyServiceSelect.addEventListener('change', updateTimeAvailability);
        }

        // Add listener for reschedule appointment selection changes to re-check availability
        const rescheduleAppointmentSelect = document.getElementById('rescheduleAppointmentSelect');
        if (rescheduleAppointmentSelect) {
            rescheduleAppointmentSelect.addEventListener('change', updateTimeAvailability);
        }

        // Show suggested available times when date is selected (emergency)
        if (emergencyDateInput) {
            emergencyDateInput.addEventListener('change', function() {
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

                    const dateFormGroup = this.closest('.form-group');
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

                    const dateFormGroup = this.closest('.form-group');
                if (dateFormGroup) {
                    dateFormGroup.appendChild(suggestionDiv);
                }
            }
        });
    }

        // Show suggested available times when date is selected (reschedule)
        if (rescheduleDateInput) {
            rescheduleDateInput.addEventListener('change', function() {
                const { dateInput } = getActiveFormInputs();
                if (!dateInput || dateInput !== this) return;

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

                    const dateFormGroup = this.closest('.form-group');
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

                    const dateFormGroup = this.closest('.form-group');
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
