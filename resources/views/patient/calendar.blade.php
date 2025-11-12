@extends('layout.patient.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/patient-calendar.css') }}">

<style>
/* Loading Overlay Styles - Themed to match system design - Positioned over calendar sections only */
.calendar-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(227, 242, 253, 0.95) 0%, rgba(187, 222, 251, 0.95) 100%);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    will-change: opacity;
    transform: translateZ(0);
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
    pointer-events: auto;
}

[data-theme="dark"] .calendar-loading-overlay {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
}

.calendar-loading-overlay.hidden {
    opacity: 0;
    visibility: hidden;
}

/* Loading Spinner Container - Simple and elegant design */
.loading-spinner {
    position: relative;
    width: 60px;
    height: 60px;
    margin-bottom: 2rem;
    will-change: transform;
    transform: translateZ(0);
    contain: layout style paint;
    isolation: isolate;
}

/* Responsive spinner size */
@media (max-width: 768px) {
    .loading-spinner {
        width: 50px;
        height: 50px;
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 480px) {
    .loading-spinner {
        width: 45px;
        height: 45px;
        margin-bottom: 1.25rem;
    }
}

/* Simple elegant spinner - single smooth circle */
.spinner-circle {
    width: 100%;
    height: 100%;
    border: 4px solid rgba(33, 150, 243, 0.15);
    border-top-color: #2196F3;
    border-radius: 50%;
    will-change: transform;
    transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    animation: spin 1s cubic-bezier(0.5, 0, 0.5, 1) infinite;
}

@media (max-width: 768px) {
    .spinner-circle {
        border-width: 3.5px;
    }
}

@media (max-width: 480px) {
    .spinner-circle {
        border-width: 3px;
    }
}

/* Dark mode spinner */
[data-theme="dark"] .spinner-circle {
    border-color: rgba(96, 165, 250, 0.2);
    border-top-color: #60a5fa;
}

/* Optimized spin animation - using transform3d for GPU acceleration */
@keyframes spin {
    0% {
        transform: rotate3d(0, 0, 1, 0deg);
    }
    100% {
        transform: rotate3d(0, 0, 1, 360deg);
    }
}

/* Loading Text - Themed - Optimized for smooth animation */
.loading-text {
    font-size: clamp(1rem, 2vw, 1.25rem);
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    will-change: opacity;
    transform: translateZ(0);
    animation: pulse 1.5s ease-in-out infinite;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    letter-spacing: 0.5px;
}

.loading-text i {
    font-size: 1.2em;
    color: #2196F3;
    will-change: opacity;
    transform: translateZ(0);
    animation: pulse 1.5s ease-in-out infinite;
    /* Using text-shadow instead of filter for better performance */
    text-shadow: 0 2px 4px rgba(33, 150, 243, 0.3);
}

.loading-text span {
    position: relative;
}

[data-theme="dark"] .loading-text {
    color: #f1f5f9;
}

[data-theme="dark"] .loading-text i {
    color: #60a5fa;
    text-shadow: 0 2px 6px rgba(96, 165, 250, 0.5);
}

/* Responsive text adjustments */
@media (max-width: 768px) {
    .loading-text {
        font-size: 0.95rem;
        gap: 0.6rem;
    }
    
    .loading-text i {
        font-size: 1.1em;
    }
}

@media (max-width: 480px) {
    .loading-text {
        font-size: 0.85rem;
        gap: 0.5rem;
        padding: 0 1rem;
        flex-direction: column;
        text-align: center;
    }
    
    .loading-text i {
        font-size: 1em;
    }
}

/* Optimized pulse animation - using opacity only for GPU acceleration */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* Additional loading indicator - subtle background animation - Optimized */
.calendar-loading-overlay::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(33, 150, 243, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    will-change: transform, opacity;
    transform: translate3d(-50%, -50%, 0) scale(0.8);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    animation: ripple 2s ease-out infinite;
    z-index: -1;
}

[data-theme="dark"] .calendar-loading-overlay::before {
    background: radial-gradient(circle, rgba(96, 165, 250, 0.15) 0%, transparent 70%);
}

@media (max-width: 768px) {
    .calendar-loading-overlay::before {
        width: 150px;
        height: 150px;
    }
}

@media (max-width: 480px) {
    .calendar-loading-overlay::before {
        width: 120px;
        height: 120px;
    }
}

/* Optimized ripple animation using transform3d */
@keyframes ripple {
    0% {
        transform: translate3d(-50%, -50%, 0) scale(0.8);
        opacity: 1;
    }
    100% {
        transform: translate3d(-50%, -50%, 0) scale(1.5);
        opacity: 0;
    }
}

/* Fix z-index for modals to appear above header */
.modal {
    z-index: 1050 !important;
}

.modal-backdrop {
    z-index: 1040 !important;
}

/* Ensure dropdown menus in header are below modals */
.dropdown-menu {
    z-index: 1001 !important;
}

/* Mobile menu should be below modals and behind hamburger button */
.mobile-menu-backdrop {
    z-index: 1037 !important;
}

.mobile-menu-overlay {
    z-index: 1038 !important;
}

.mobile-menu-toggle.fixed-open {
    z-index: 1041 !important;
}
</style>

<style>
.mobile-event-count {
    display: none;
}

/* Mobile overrides: rectangular event boxes that fit within grid cells, vertical stacking */
/* Appointments adjust to fit grid, grid stays fixed */
@media (max-width: 768px) {
    /* 1) Rectangular event boxes that fit within grid cells */
    .day-events {
        display: flex;
        flex-direction: column !important;
        align-items: stretch !important;
        justify-content: flex-start !important;
        gap: 0.25rem !important;
        margin-top: 1.5rem !important;
        padding: 0.2rem 0 !important;
        position: relative !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        align-self: stretch !important;
        min-height: 0 !important;
        overflow: hidden !important;
    }
    
    .day-events .event-item {
        width: 100% !important;
        max-width: 100% !important;
        min-height: 2rem !important;
        padding: 0.35rem 0.4rem !important;
        margin: 0 !important;
        border-radius: 4px !important;
        border-left: 2px solid !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        justify-content: flex-start !important;
        font-size: 0.7rem !important;
        font-weight: normal !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
        position: relative !important;
        transform: none !important;
        box-sizing: border-box !important;
        gap: 0.05rem !important;
        line-height: 1.3 !important;
    }
    
    /* Show all event content - time must be fully visible */
    .day-events .event-item .event-time {
        display: block !important;
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        margin-bottom: 0.05rem !important;
        white-space: nowrap !important;
        overflow: visible !important; /* Allow time to be fully visible */
        text-overflow: clip !important; /* Don't truncate time */
        width: 100% !important; /* Take full width but don't truncate */
        max-width: 100% !important;
        flex-shrink: 0 !important; /* Prevent time from shrinking */
        min-width: 0 !important; /* Allow flexbox to work properly */
        word-break: keep-all !important; /* Keep time together */
    }
    
    .day-events .event-item .event-title {
        display: block !important;
        font-size: 0.65rem !important;
        line-height: 1.2 !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        flex-shrink: 1 !important; /* Allow title to shrink to make room for time */
        min-width: 0 !important; /* Allow title to shrink below its content size */
    }
    
    .day-events .event-item .event-notes {
        display: -webkit-box !important;
        font-size: 0.6rem !important;
        -webkit-line-clamp: 1 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        margin-top: 0.05rem !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .day-events .event-item .event-patient {
        display: none !important;
    }
    
    .day-events .event-item .mobile-event-count {
        display: none !important;
    }
    
    /* Event item colors */
    .day-events .event-item.booked {
        background: #f3e8ff !important;
        border-left-color: #9333ea !important;
        color: #6b21a8 !important;
    }
    .day-events .event-item.pending {
        background: #fef3c7 !important;
        border-left-color: #fbbf24 !important;
        color: #92400e !important;
    }
    .day-events .event-item.confirmed {
        background: #dbeafe !important;
        border-left-color: #3b82f6 !important;
        color: #1e40af !important;
    }
    .day-events .event-item.completed {
        background: #d1fae5 !important;
        border-left-color: #10b981 !important;
        color: #065f46 !important;
    }
    .day-events .event-item.cancelled {
        background: #fef3c7 !important;
        border-left-color: #92400e !important;
        color: #78350f !important;
        opacity: 0.7 !important;
    }
    .day-events .event-item.missed {
        background: #e5e7eb !important;
        border-left-color: #6b7280 !important;
        color: #374151 !important;
    }
    .day-events .event-item.blocked {
        background: #fff1f2 !important;
        border-left-color: rgb(255, 0, 0) !important;
        color: rgb(255, 0, 0) !important;
    }
    
    .calendar-day.fully-booked .day-events {
        margin-top: 1.5rem !important;
        padding-top: 0.2rem !important;
    }

    /* 2) Hide legacy "X more" indicator on mobile */
    .event-more-indicator {
        display: none !important;
    }

    /* 3) Fully booked indicator as a small icon near the top-right corner */
    .fully-booked-indicator {
        position: absolute !important;
        top: 0.25rem !important;
        right: 0.25rem !important;
        left: auto !important;
        width: 20px !important;
        height: 20px !important;
        padding: 0 !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.35) !important;
        color: #fff !important;
        font-size: 0 !important; /* hide any text inside */
        z-index: 35 !important;
    }
    .fully-booked-indicator::after {
        content: '\00D7' !important; /* icon-like marker */
        font-size: 0.75rem !important;
        font-weight: 800 !important;
        line-height: 1 !important;
    }
    .calendar-day.fully-booked .day-number {
        max-width: calc(100% - 2.5rem) !important;
    }

    /* Dark mode adjustments - proper dark mode colors */
    [data-theme="dark"] .calendar-day .day-events .event-item {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
    }
    [data-theme="dark"] .calendar-day .day-events .event-item.pending {
        background: rgba(251, 191, 36, 0.2) !important;
        border-left-color: #fbbf24 !important;
        color: #fef3c7 !important;
    }
    [data-theme="dark"] .calendar-day .day-events .event-item.confirmed {
        background: rgba(59, 130, 246, 0.2) !important;
        border-left-color: #3b82f6 !important;
        color: #dbeafe !important;
    }
    [data-theme="dark"] .calendar-day .day-events .event-item.completed {
        background: rgba(16, 185, 129, 0.2) !important;
        border-left-color: #10b981 !important;
        color: #d1fae5 !important;
    }
    [data-theme="dark"] .calendar-day .day-events .event-item.cancelled {
        background: rgba(146, 64, 14, 0.3) !important;
        border-left-color: #a16207 !important;
        color: #fef3c7 !important;
        opacity: 0.7 !important;
    }
    [data-theme="dark"] .calendar-day .day-events .event-item.blocked {
        background: rgba(239, 68, 68, 0.2) !important;
        border-left-color: #ef4444 !important;
        color: #fee2e2 !important;
    }
    [data-theme="dark"] .calendar-day .day-events .event-item.missed {
        background: rgba(107, 114, 128, 0.2) !important;
        border-left-color: #6b7280 !important;
        color: #e5e7eb !important;
    }
    [data-theme="dark"] .calendar-day .day-events .event-item.booked {
        background: rgba(147, 51, 234, 0.3) !important;
        border-left-color: #9333ea !important;
        color: #e9d5ff !important;
    }
}
</style>

<div class="calendar-container">
    <!-- Main Content -->
    <div class="calendar-layout">
        <!-- Loading Overlay for Calendar Sections -->
        <div id="calendar-loading-overlay" class="calendar-loading-overlay">
            <div class="loading-spinner">
                <div class="spinner-circle"></div>
            </div>
            <p class="loading-text">
                <i class="bi bi-calendar-check"></i>
                <span>Loading Calendar...</span>
            </p>
        </div>
        
        <!-- Sidebar -->
        <aside class="calendar-sidebar">
            <!-- Tabbed Appointment Section -->
            <div class="sidebar-card tabbed-section reveal-element reveal-slide-left">
                <!-- Tab Buttons -->
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="upcoming" onclick="switchTab('upcoming')" data-tooltip="Upcoming Appointment">
                        <i class="bi bi-clock-history"></i>
                        <span>Upcoming</span>
                    </button>
                    <button class="tab-btn" data-tab="pending" onclick="switchTab('pending')" data-tooltip="Pending Request">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Pending Request</span>
                    </button>
                    <button class="tab-btn" data-tab="history" onclick="switchTab('history')" data-tooltip="Appointment History">
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
                        @php
                            $status = strtolower($appointment->status ?? 'pending');
                            $statusClass = '';
                            if ($status === 'confirmed') {
                                $statusClass = 'confirmed';
                            } elseif ($status === 'pending') {
                                $statusClass = 'pending';
                            }
                        @endphp
                        <div class="upcoming-item {{ $statusClass }}">
                            <div class="upcoming-date">
                                <span class="date-day">{{ $appointment->start_datetime->format('d') }}</span>
                                <span class="date-month">{{ $appointment->start_datetime->format('M') }}</span>
                            </div>
                            <div class="upcoming-info">
                                <div class="upcoming-title">{{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }}</div>
                                <div class="upcoming-meta">
                                    <div class="upcoming-time">
                                        <i class="bi bi-clock me-1"></i>{{ $appointment->start_datetime->format('g:i A') }}
                                    </div>
                                    <span class="status-badge {{ strtolower($appointment->status) }}">{{ $appointment->status }}</span>
                                </div>
                            </div>
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
                                <span class="request-type-badge {{ $request->request_type === 'walk-in' ? 'emergency' : ($request->request_type === 'book' ? 'regular' : $request->request_type) }}">
                                    <i class="bi {{ $request->request_type === 'walk-in' ? 'bi-lightning-charge-fill' : ($request->request_type === 'book' ? 'bi-calendar-check' : 'bi-arrow-repeat') }}"></i>
                                    {{ $request->request_type === 'walk-in' ? 'Emergency' : ($request->request_type === 'book' ? 'Regular' : 'Reschedule') }}
                                </span>
                            </div>
                            <div class="pending-request-info">
                                <div class="pending-request-service">
                                    <svg class="tooth-icon me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; display: inline-block;">
                                        <!-- Simple tooth outline: crown with two roots -->
                                        <path d="M12 2C10.5 2 9 2.5 8.5 3.5C8 4.5 8 5.5 8.5 6.5C9 7.5 10 8 11 8C12 8 13 7.5 13.5 6.5C14 5.5 14 4.5 13.5 3.5C13 2.5 11.5 2 12 2ZM9 8L8.5 10L8 13L7.5 16L8 19L9 21L10 22M15 8L15.5 10L16 13L16.5 16L16 19L15 21L14 22M10 22L14 22"/>
                                    </svg>
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
                        @php
                            $status = strtolower($appointment->status);
                            $statusClass = '';
                            if ($status === 'cancelled') {
                                $statusClass = 'cancelled';
                            } elseif ($status === 'missed') {
                                $statusClass = 'missed';
                            } elseif ($status === 'completed') {
                                $statusClass = 'completed';
                            }
                        @endphp
                        <div class="history-item {{ $statusClass }}">
                            <div class="history-date">
                                <span class="history-day">{{ $appointment->start_datetime->format('d') }}</span>
                                <span class="history-month">{{ $appointment->start_datetime->format('M') }}</span>
                                <span class="history-year">{{ $appointment->start_datetime->format('Y') }}</span>
                            </div>
                            <div class="history-info">
                                <div class="history-title {{ in_array($status, ['cancelled', 'missed']) ? 'text-decoration-line-through' : '' }}">{{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }}</div>
                                <div class="history-meta">
                                    <div class="history-time">
                                        <i class="bi bi-clock me-1"></i>{{ $appointment->start_datetime->format('g:i A') }}
                                    </div>
                                    <div class="history-badges">
                                        <span class="status-badge history {{ $status }}">{{ $appointment->status }}</span>
                                        @if(($status === 'cancelled' || $status === 'missed') && $appointment->notes && (stripos($appointment->notes, 'automatic') !== false || stripos($appointment->notes, 'automatically') !== false))
                                            <span class="status-badge automatic-status">Automatic Status</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
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

    <div class="calendar-resizer" id="calendarResizer" role="separator" aria-label="Resize appointment panels" aria-orientation="vertical" tabindex="0"></div>

        <!-- Main Calendar -->
        <main class="calendar-main reveal-element reveal-slide-right">
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
                    <button class="appointment-action-btn book-now-btn" data-bs-toggle="modal" data-bs-target="#appointmentRequestModal" onclick="openAppointmentModal('book')">
                        <i class="bi bi-calendar-plus-fill"></i> Book Now
                    </button>
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
                            <span class="legend-dot missed"></span>
                            <span class="legend-text">Missed</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot blocked"></span>
                            <span class="legend-text">Blocked/Closed</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot booked"></span>
                            <span class="legend-text">Already Booked</span>
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
@php
    $clinicTimeSlots = [];
    $startTime = strtotime('11:00');
    $endTime = strtotime('18:00');
    for ($time = $startTime; $time <= $endTime; $time += 15 * 60) {
        $clinicTimeSlots[] = [
            'value' => date('H:i', $time),
            'label' => date('g:i A', $time),
        ];
    }
@endphp
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
                                    <label class="form-label">
                                        <i class="bi bi-calendar-event me-2"></i>Select Date:
                                    </label>
                                    <div class="custom-date-picker-container">
                                        <div class="custom-calendar-widget" id="emergencyCalendarWidget">
                                            <div class="custom-calendar-header">
                                                <span class="custom-calendar-title">Date</span>
                                                <div class="custom-calendar-nav">
                                                    <button type="button" class="custom-calendar-nav-btn" id="emergencyPrevMonth">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </button>
                                                    <span class="custom-calendar-month-year" id="emergencyMonthYear">November 2025</span>
                                                    <button type="button" class="custom-calendar-nav-btn" id="emergencyNextMonth">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="custom-calendar-days-header">
                                                <span>S</span>
                                                <span>M</span>
                                                <span>T</span>
                                                <span>W</span>
                                                <span>Th</span>
                                                <span>F</span>
                                                <span>S</span>
                                            </div>
                                            <div class="custom-calendar-grid" id="emergencyCalendarGrid"></div>
                                            <div class="custom-calendar-instruction">Click on a date to select it</div>
                                        </div>
                                        <input type="hidden" id="emergencyDate" name="date" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="emergencyTime" class="form-label">
                                        <i class="bi bi-clock-history me-2"></i>Select Time:
                                    </label>
                                    <div class="time-slot-picker">
                                        <div class="time-slot-header">
                                            <span class="time-slot-icon">
                                                <i class="bi bi-clock-history"></i>
                                            </span>
                                            <span class="time-slot-selected" id="emergencyTimeSelected">No time selected</span>
                                        </div>
                                        <input type="hidden" id="emergencyTime" name="time" required>
                                        <div class="time-slot-grid" id="emergencyTimeSlots">
                                            @foreach($clinicTimeSlots as $slot)
                                                @if($slot['value'] !== '18:00')
                                                    <button type="button" class="time-slot-btn" data-value="{{ $slot['value'] }}">{{ $slot['label'] }}</button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <small class="time-slot-help" style="margin-top: 0.5rem;">Clinic hours: 11:00 AM – 6:00 PM</small>
                                    <button type="submit" form="emergencyForm" class="btn-submit-time-slot">
                                        <i class="bi bi-check-circle me-2"></i>Submit Request
                                    </button>
                                </div>
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
                    @php
                        $displayedIds = [];
                    @endphp
                    @foreach($reschedulableAppointments as $appointment)
                        @php
                            $status = $appointment->status ?? 'Pending';
                            $optionLabel = ($appointment->service ? $appointment->service->service_name : ($appointment->reason_for_visit ?? 'Appointment')) .
                                ' - ' . $appointment->start_datetime->format('M d, Y') . ' at ' . $appointment->start_datetime->format('g:i A');
                            if (!in_array($status, ['Pending', 'Confirmed'])) {
                                $optionLabel .= ' [' . $status . ']';
                            }
                        @endphp
                        @if(!in_array($appointment->id, $displayedIds))
                            <option value="{{ $appointment->id }}"
                                    data-service="{{ $appointment->service ? $appointment->service->service_name : $appointment->reason_for_visit }}"
                                    data-date="{{ $appointment->start_datetime->format('Y-m-d') }}"
                                    data-time="{{ $appointment->start_datetime->format('H:i') }}"
                                    data-datetime="{{ $appointment->start_datetime->format('F d, Y \a\t g:i A') }}"
                                    data-service-id="{{ $appointment->service_id ?? '' }}"
                                    data-duration="{{ $appointment->duration_minutes }}"
                                    data-reason-for-visit="{{ $appointment->reason_for_visit ?? '' }}"
                                    data-status="{{ $appointment->status ?? 'Pending' }}">
                                {{ $optionLabel }}
                            </option>
                            @php $displayedIds[] = $appointment->id; @endphp
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
                                    <label class="form-label">
                        <i class="bi bi-calendar-event me-2"></i>Select Date:
                    </label>
                    <div class="custom-date-picker-container">
                        <div class="custom-calendar-widget" id="rescheduleCalendarWidget">
                            <div class="custom-calendar-header">
                                <span class="custom-calendar-title">Date</span>
                                <div class="custom-calendar-nav">
                                    <button type="button" class="custom-calendar-nav-btn" id="reschedulePrevMonth">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <span class="custom-calendar-month-year" id="rescheduleMonthYear">November 2025</span>
                                    <button type="button" class="custom-calendar-nav-btn" id="rescheduleNextMonth">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="custom-calendar-days-header">
                                <span>S</span>
                                <span>M</span>
                                <span>T</span>
                                <span>W</span>
                                <span>Th</span>
                                <span>F</span>
                                <span>S</span>
                            </div>
                            <div class="custom-calendar-grid" id="rescheduleCalendarGrid"></div>
                            <div class="custom-calendar-instruction">Click on a date to select it</div>
                        </div>
                        <input type="hidden" id="rescheduleDate" name="date" required>
                    </div>
                </div>

                <div class="form-group">
                                    <label for="rescheduleTime" class="form-label">
                        <i class="bi bi-clock-history me-2"></i>Select Time:
                    </label>
                    <div class="time-slot-picker">
                                        <div class="time-slot-header">
                                            <span class="time-slot-icon">
                                                <i class="bi bi-clock-history"></i>
                                            </span>
                                            <span class="time-slot-selected" id="rescheduleTimeSelected">No time selected</span>
                                        </div>
                                        <input type="hidden" id="rescheduleTime" name="time" required>
                                        <div class="time-slot-grid" id="rescheduleTimeSlots">
                                            @foreach($clinicTimeSlots as $slot)
                                                @if($slot['value'] !== '18:00')
                                                    <button type="button" class="time-slot-btn" data-value="{{ $slot['value'] }}">{{ $slot['label'] }}</button>
                                                @endif
                                            @endforeach
                                        </div>
                    </div>
                    <small class="time-slot-help" style="margin-top: 0.5rem;">Tap a slot to request a new time</small>
                    <button type="submit" form="rescheduleForm" class="btn-submit-time-slot">
                        <i class="bi bi-check-circle me-2"></i>Submit Request
                    </button>
                </div>
            </div>
        </form>
                    </div>

                    <!-- Book Now Appointment Form -->
                    <div id="bookFormSection" class="appointment-form-section" style="display: none;">
                        <form id="bookForm" class="appointment-form">
                            <!-- Service Selection -->
                            <div class="form-group" id="bookServiceSelectionGroup">
                                <label for="bookServiceSelect" class="form-label">
                    <i class="bi bi-heart-pulse-fill me-2"></i>Select Service:
                </label>
                                <select id="bookServiceSelect" name="service_id" class="form-select" required>
                    <option value="">-- Select a service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" data-duration="{{ $service->default_duration_minutes }}">{{ $service->service_name }}</option>
                    @endforeach
                    <option value="other" data-duration="30">Other</option>
                </select>
                </div>

            <!-- Other Concern Input (only visible when "Other" is selected) -->
                            <div class="form-group" id="bookOtherConcernGroup" style="display: none;">
                                <label for="bookOtherConcern" class="form-label">
                    <i class="bi bi-pencil-square me-2"></i>Service Name:
                </label>
                                <input type="text" id="bookOtherConcern" name="other_concern" class="form-input" placeholder="Enter the dental service you need...">
            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar-event me-2"></i>Preferred Date:
                                </label>
                                <div class="custom-date-picker-container">
                                    <div class="custom-calendar-widget" id="bookCalendarWidget">
                                        <div class="custom-calendar-header">
                                            <span class="custom-calendar-title">Date</span>
                                            <div class="custom-calendar-nav">
                                                <button type="button" class="custom-calendar-nav-btn" id="bookPrevMonth">
                                                    <i class="bi bi-chevron-left"></i>
                                                </button>
                                                <span class="custom-calendar-month-year" id="bookMonthYear">November 2025</span>
                                                <button type="button" class="custom-calendar-nav-btn" id="bookNextMonth">
                                                    <i class="bi bi-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="custom-calendar-days-header">
                                            <span>S</span>
                                            <span>M</span>
                                            <span>T</span>
                                            <span>W</span>
                                            <span>Th</span>
                                            <span>F</span>
                                            <span>S</span>
                                        </div>
                                        <div class="custom-calendar-grid" id="bookCalendarGrid"></div>
                                        <div class="custom-calendar-instruction">Click on a date to select it</div>
                                    </div>
                                    <input type="hidden" id="bookDate" name="date" required>
                                </div>
                                <small class="form-text text-muted mt-2">
                                    <i class="bi bi-info-circle me-1"></i>Admin or staff will assign the appointment time after reviewing your request.
                                </small>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-submit">
                                    <i class="bi bi-check-circle me-2"></i>Book this Date
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; padding: 1.5rem;">
                <h5 class="modal-title text-white" id="successModalLabel" style="font-weight: 700;">
                    <i class="bi bi-check-circle-fill me-2"></i>Success
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem; text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: #10b981;"></i>
                </div>
                <h5 style="color: #1e293b; font-weight: 700; margin-bottom: 1rem;">Request Submitted Successfully!</h5>
                <p id="successModalMessage" style="color: #64748b; margin-bottom: 0; line-height: 1.6;">
                    Your appointment booking request has been submitted. Admin or staff will review your request and assign an appointment time. You will be notified once your appointment is scheduled.
                </p>
            </div>
            <div class="modal-footer" style="border: none; padding: 1.5rem; background: #f8f9fa; justify-content: center;">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                    <i class="bi bi-check-circle me-2"></i>OK
                </button>
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
    position: relative;
    display: flex;
    gap: 0.875rem;
    align-items: stretch;
    --calendar-sidebar-width: 260px;
    --calendar-sidebar-min: 220px;
    --calendar-sidebar-max: 480px;
}

/* Desktop-only: lock the sidebar width and isolate its scrolling/positioning */
@media (min-width: 1025px) {
    .calendar-layout {
        /* Lock a stable desktop width; independent from calendar adjustments */
		--calendar-sidebar-width: 280px;
    }
    .calendar-sidebar {
        /* Keep it pinned within the viewport; independent vertical scroll */
        position: sticky;
        top: 0.75rem;
        max-height: calc(100vh - 1.5rem);
        overflow-y: auto;
    }
	.tabbed-section {
		/* Remove any inner bottom spacing so content can use full height */
		padding-bottom: 0 !important;
	}
    .tabbed-section .tab-content-wrapper {
        /* Ensure inner content uses its own scroll within fixed sidebar */
		max-height: none !important; /* override earlier desktop cap that created a gap */
		height: 100% !important;
		flex: 1 1 auto !important;
        overflow-y: auto;
		padding-bottom: 0 !important;
    }
}

.calendar-layout.is-resizing {
    cursor: col-resize;
    user-select: none;
}

.calendar-sidebar {
    flex: 0 0 var(--calendar-sidebar-width);
    min-width: var(--calendar-sidebar-min);
    max-width: var(--calendar-sidebar-max);
    transition: flex-basis 0.1s ease;
}

.calendar-resizer {
    flex: 0 0 10px;
    position: relative;
    cursor: col-resize;
    border-radius: 999px;
    display: block;
    z-index: 1; /* keep below tooltips */
}

.calendar-resizer::before {
    content: '';
    position: absolute;
    top: 12px;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    border-radius: 999px;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.4), rgba(25, 118, 210, 0.4));
    transition: background 0.2s ease, width 0.2s ease;
    z-index: 1; /* keep below tooltips */
}

.calendar-resizer:hover::before,
.calendar-layout.is-resizing .calendar-resizer::before {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.8), rgba(25, 118, 210, 0.8));
    width: 6px;
}

.calendar-main {
    flex: 1 1 auto;
    min-width: 0;
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
    overflow-x: visible;
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
    position: relative;
    overflow: visible;
    z-index: 3000;
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
    position: relative;
    overflow: visible;
    z-index: 1;
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
    position: relative;
    overflow: visible;
    z-index: 1000;
    isolation: isolate;
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

/* Tab Button Tooltips */
.tab-btn[data-tooltip] {
    position: relative;
    z-index: 2000;
    /* default positions for fixed tooltip (updated via JS on hover) */
    --tt-left: 0px;
    --tt-top: 0px;
}

.tab-btn[data-tooltip]:hover:not([data-no-tt])::before {
    content: attr(data-tooltip);
    position: fixed; /* escape ancestor overflow */
    top: var(--tt-top);
    left: var(--tt-left);
    transform: translateX(-50%); /* center to button */
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    z-index: 999999 !important; /* above everything */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3), 0 2px 6px rgba(0, 0, 0, 0.2);
    pointer-events: none;
    opacity: 0;
    animation: tooltipFadeInBottom 0.2s ease forwards;
}

.tab-btn[data-tooltip]:hover:not([data-no-tt])::after {
    content: '';
    position: fixed; /* escape ancestor overflow */
    top: calc(var(--tt-top) - 6px);
    left: var(--tt-left);
    transform: translateX(-50%); /* center to button */
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-bottom: 6px solid #1e293b;
    z-index: 100000 !important;
    pointer-events: none;
    opacity: 0;
    animation: tooltipFadeInBottom 0.2s ease forwards;
}

@keyframes tooltipFadeInBottom {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* Dark Mode Tooltips */
[data-theme="dark"] .tab-btn[data-tooltip]:hover::before {
    background: linear-gradient(135deg, #334155 0%, #1e293b 100%) !important;
    color: #f1f5f9 !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5), 0 2px 6px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .tab-btn[data-tooltip]:hover::after {
    border-bottom-color: #334155 !important;
}

.tab-buttons {
    overflow: visible;
    position: relative;
    z-index: 2;
}

/* Body-mounted tooltip (JS-driven) to avoid clipping inside sidebar/card */
.calendar-body-tooltip {
    position: fixed;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: #e2e8f0;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 8px 16px rgba(2, 6, 23, 0.35);
    z-index: 10000;
    pointer-events: none;
}
.calendar-body-tooltip::after {
    content: '';
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-bottom: 6px solid #1e293b;
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
    max-height: 100%;
    flex-direction: column;
    padding-bottom: 0;
    margin-bottom: 0;
}

.tab-content.active {
    display: flex;
    flex-direction: column;
    padding-bottom: 0;
    margin-bottom: 0;
}

.tab-content .upcoming-list,
.tab-content .pending-requests-list,
.tab-content .history-list {
    flex: 1;
    min-height: 0;
    max-height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 0.45rem;
    padding-bottom: 0;
    margin-bottom: 0;
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
    width: 6px;
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
    margin-bottom: 0;
    padding-bottom: 0;
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

/* Upcoming Item Status Colors - Light Mode */
.upcoming-item.pending {
    background: #F8FAFC;
    border-left: 3px solid #fbbf24;
}

.upcoming-item.pending:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(251, 191, 36, 0.3);
}

.upcoming-item.confirmed {
    background: #F8FAFC;
    border-left: 3px solid #3b82f6;
}

.upcoming-item.confirmed:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
}

/* History Item Status Colors */
.history-item.completed {
    background: #F8FAFC;
    border-left: 3px solid #10b981;
}

.history-item.completed:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.history-item.cancelled {
    background: #F8FAFC;
    opacity: 0.9;
    border-left: 3px solid #92400e;
}

.history-item.cancelled:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(146, 64, 14, 0.3);
}

.history-item.missed {
    background: #F8FAFC;
    opacity: 0.9;
    border-left: 3px solid #6b7280;
}

.history-item.missed:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(107, 114, 128, 0.3);
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
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    line-height: 1; /* keeps icon and label vertically centered */
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

/* Ensure icon and text are aligned nicely inside view buttons */
.view-btn i {
    display: inline-block;
    vertical-align: middle;
    font-size: 1em;
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
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.legend-dot.pending {
    background: #fbbf24;
}

.legend-dot.confirmed {
    background: #3b82f6;
}

.legend-dot.completed {
    background: #10b981;
}

.legend-dot.cancelled {
    background: #92400e;
}

.legend-dot.blocked {
    background: #ef4444;
}

.legend-dot.missed {
    background: #6b7280;
}

.legend-dot.booked {
    background: #9333ea;
}

.legend-text {
    font-size: 0.7rem;
    font-weight: 600;
    color: #64748b;
}

.calendar-content {
    min-height: 500px;
    transition: opacity 0.2s ease;
    position: relative;
    opacity: 1;
}

.calendar-content.updating {
    opacity: 0.5;
    pointer-events: none;
}

/* Calendar Grid - More Compact */
.calendar-grid {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.month-grid {
    width: 100%;
}

.calendar-header-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e2e8f0;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
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
    width: 100%;
    box-sizing: border-box;
    overflow-x: hidden;
}

.calendar-week {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    width: 100%;
    box-sizing: border-box;
}

.calendar-day {
    position: relative;
    background: white;
    min-height: 100px;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
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
    font-size: 0.875rem;
    position: absolute;
    top: 0.375rem;
    left: 0.375rem;
    z-index: 3;
    line-height: 1;
    min-width: 20px;
}

.day-events {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    margin-top: 1.75rem;
    padding-top: 0.25rem;
    position: relative;
    z-index: 1;
    min-height: 0;
    width: 100%;
    box-sizing: border-box;
    align-items: stretch;
}

.day-events > * {
    flex-shrink: 0;
}

.event-item {
    background: #e3f2fd;
    border-left: 2px solid #2196F3;
    padding: 0.4rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.2s;
    overflow: hidden; /* Hide overflow for container, but time will be visible */
    text-overflow: ellipsis;
    white-space: normal;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
    line-height: 1.4;
    word-wrap: break-word;
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
    margin-bottom: 0;
    width: 100%;
    box-sizing: border-box;
    min-height: 2.5rem;
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
    background: #fef3c7;
    border-left-color: #92400e;
    color: #78350f;
    opacity: 0.7;
}

/* Hide notes/description for cancelled appointments in the main calendar */
.calendar-grid .event-item.cancelled .event-notes,
.day-events .event-item.cancelled .event-notes {
    display: none !important;
}

/* Compact spacing in day slots (month view) */
.calendar-grid .day-events {
    gap: 0.2rem !important;
    margin-top: 0.25rem !important;
}
.calendar-grid .calendar-day .day-events {
    /* Add a little clearance under the day number so the first item is not overlapped */
    margin-top: 0.6rem !important;
}
.calendar-grid .calendar-day.today .day-events {
    /* Today's circular day badge is taller; give a bit more space */
    margin-top: 0.7rem !important;
}
.calendar-grid .calendar-day.today .day-number {
    /* Pin today's badge to the very top-left corner of the day cell */
    top: -0.1rem !important;
    left: -0.1rem !important;
    z-index: 4 !important;
}
.calendar-grid .calendar-day.fully-booked .day-events {
    /* Ensure the first appointment is not hidden under the day-number or fully booked badge */
    margin-top: 0.6rem !important;
    padding-top: 0.65rem !important;
}
.calendar-grid .calendar-day .event-count-badge + .day-events {
    /* If count badges are visible, add a bit of clearance */
    margin-top: 1rem !important;
}
.calendar-grid .day-events .event-item {
    padding: 0.35rem 0.45rem !important;
    min-height: 2rem !important;
    margin: 0 !important;
}
.calendar-grid .event-more-indicator {
    margin-top: 0.25rem !important;
}

/* Desktop-only compaction to fit screen height without inner scrolling */
@media (min-width: 1200px) {
    /* Tighter overall layout spacing */
    .calendar-layout {
        gap: 0.75rem !important;
    }
    .calendar-container {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    /* Sidebar compaction */
    .calendar-sidebar {
        max-height: calc(100vh - 70px) !important; /* slightly taller usable area */
        gap: 0.5rem !important;
    }
    .sidebar-card.tabbed-section {
        padding: 0.45rem !important;
    }
    .tab-buttons {
        gap: 0.3rem !important;
        padding-bottom: 0.35rem !important;
        margin-bottom: 0.6rem !important;
    }
    .tab-btn {
        min-height: 36px !important;
        padding: 0.45rem 0.55rem !important;
        font-size: 0.8rem !important;
    }
    .tab-btn i {
        font-size: 0.9rem !important;
    }
    .tab-content-wrapper {
        /* Reduce internal chrome so the content fits within viewport height */
        max-height: calc(100vh - 210px) !important;
        padding-right: 2px !important;
    }
    /* Month grid: reduce day cell height and internal padding */
    .calendar-day {
        min-height: 80px !important;
        padding: 0.4rem !important;
    }
    .day-events {
        gap: 0.2rem !important;
        margin-top: 0.25rem !important;
    }
    .day-events .event-item {
        padding: 0.35rem 0.45rem !important;
        min-height: 1.9rem !important;
    }
    .event-time {
        margin-bottom: 0.1rem !important;
        font-size: 0.68rem !important;
    }
    .event-title {
        font-size: 0.68rem !important;
        line-height: 1.15 !important;
    }
    .event-notes {
        font-size: 0.62rem !important;
    }
    .event-more-indicator {
        margin-top: 0.35rem !important;
        padding: 0.35rem 0.45rem !important;
        font-size: 0.68rem !important;
    }
    /* Ensure fully-booked badge and day number don't overlap first event */
    .calendar-day.fully-booked .day-events {
        margin-top: 0.6rem !important;
        padding-top: 0.3rem !important;
    }
}
.event-item.blocked {
    background: #fff1f2 !important;
    border-left-color:rgb(255, 0, 0) !important;
    color:rgb(255, 0, 0) !important;
    cursor: pointer;
}


.event-item.blocked:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
    opacity: 1;
}

.event-item.missed {
    background: #e5e7eb;
    border-left-color: #6b7280;
    color: #374151;
    cursor: not-allowed;
    opacity: 0.7;
}

.event-item.missed .event-time,
.event-item.missed .event-title,
.event-item.missed .event-notes,
.event-item.missed .event-meta,
.event-item.missed .event-service,
.event-item.missed .event-patient {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
}

.week-appointment.missed .appointment-time,
.week-appointment.missed .appointment-title,
.week-appointment.missed .appointment-status,
.week-appointment.missed .appointment-notes,
.week-appointment.missed .appointment-meta {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
}
.week-appointment.missed {
    opacity: 0.7;
}

.day-appointment.missed .appointment-time,
.day-appointment.missed .appointment-title,
.day-appointment-item.missed .appointment-time,
.day-appointment-item.missed .appointment-title,
.day-appointment-item.missed .appointment-status,
.day-appointment-item.missed .appointment-notes,
.day-appointment-item.missed .appointment-meta,
.day-appointment-item.missed .appointment-description {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
}
.day-appointment.missed,
.day-appointment-item.missed {
    opacity: 0.7;
}

.history-item.missed .history-title {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
}

/* Dark mode: ensure the same strikethrough effect for missed items */
[data-theme="dark"] .event-item.missed .event-time,
[data-theme="dark"] .event-item.missed .event-title,
[data-theme="dark"] .event-item.missed .event-notes,
[data-theme="dark"] .event-item.missed .event-meta,
[data-theme="dark"] .event-item.missed .event-service,
[data-theme="dark"] .event-item.missed .event-patient,
[data-theme="dark"] .event-item.missed,
[data-theme="dark"] .week-appointment.missed .appointment-time,
[data-theme="dark"] .week-appointment.missed .appointment-title,
[data-theme="dark"] .week-appointment.missed .appointment-status,
[data-theme="dark"] .week-appointment.missed .appointment-notes,
[data-theme="dark"] .week-appointment.missed .appointment-meta,
[data-theme="dark"] .week-appointment.missed,
[data-theme="dark"] .day-appointment.missed .appointment-time,
[data-theme="dark"] .day-appointment.missed .appointment-title,
[data-theme="dark"] .day-appointment.missed,
[data-theme="dark"] .day-appointment-item.missed .appointment-time,
[data-theme="dark"] .day-appointment-item.missed .appointment-title,
[data-theme="dark"] .day-appointment-item.missed .appointment-status,
[data-theme="dark"] .day-appointment-item.missed .appointment-notes,
[data-theme="dark"] .day-appointment-item.missed .appointment-meta,
[data-theme="dark"] .day-appointment-item.missed .appointment-description,
[data-theme="dark"] .day-appointment-item.missed,
[data-theme="dark"] .history-item.missed .history-title {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
    opacity: 0.7;
}

.event-item.booked {
    background: #f3e8ff;
    border-left: 2px solid #9333ea;
    color: #6b21a8;
    cursor: pointer;
    opacity: 1;
}

.event-item.booked:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(147, 51, 234, 0.3);
    opacity: 1;
}

.event-time {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.7rem;
    margin-bottom: 0.1rem;
    line-height: 1.2;
    white-space: nowrap;
    overflow: visible; /* Allow time to be fully visible */
    text-overflow: clip; /* Don't truncate time */
    display: block;
    width: 100%; /* Take full width but don't truncate */
    max-width: 100%;
    flex-shrink: 0; /* Prevent time from shrinking */
    min-width: 0; /* Allow flexbox to work properly */
    word-break: keep-all; /* Keep time together */
}

.event-title {
    font-weight: 700;
    color: #64748b;
    font-size: 0.65rem;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    word-wrap: normal;
    display: block;
    width: 100%;
    max-width: 100%;
    margin: 0;
    flex-shrink: 1; /* Allow title to shrink to make room for time */
    min-width: 0; /* Allow title to shrink below its content size */
}

.event-notes {
    font-size: 0.65rem;
    color: #64748b;
    margin-top: 0.1rem;
    opacity: 0.9;
    line-height: 1.2;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    width: 100%;
    max-width: 100%;
}

/* Upcoming Info - More Compact */
.upcoming-info {
    flex: 1;
    min-width: 0;
}

.upcoming-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    line-height: 1.25;
    white-space: normal;
    overflow: hidden;
    text-overflow: clip;
    word-break: break-word;
}

.upcoming-time {
    font-size: 0.75rem;
    color: #64748b;
}

/* History Info - More Compact */
.history-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
    line-height: 1.25;
    white-space: normal;
    overflow: hidden;
    text-overflow: clip;
    word-break: break-word;
}

.history-time {
    font-size: 0.7rem;
    color: #64748b;
}

/* Pending Request - More Compact */
.pending-request-item {
    padding: 0.625rem;
    border-radius: 8px;
    transition: all 0.25s ease;
    margin-bottom: 0;
}

.pending-request-header {
    margin-bottom: 0.5rem;
}

.pending-request-service {
    font-size: 0.9rem;
    line-height: 1.25;
    white-space: normal;
    word-break: break-word;
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
    background: linear-gradient(135deg, #25079C 0%, #1a0569 100%);
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

.history-day {
    font-size: 1.1rem;
    color: white;
    font-weight: 700;
}

.history-month {
    font-size: 0.6rem;
    color: white;
}

.history-year {
    font-size: 0.5rem;
    color: white;
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

.badge.status-badge-brown {
    background: linear-gradient(135deg, #a16207 0%, #854d0e 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(133, 77, 14, 0.35);
}

.status-badge.cancelled {
    background: linear-gradient(135deg, #92400e 0%, #78350f 100%);
    color: white;
}

.status-badge.missed {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.status-badge.blocked {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

/* History Badges Container */
.history-badges {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Automatic Status Badge */
.status-badge.automatic-status {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    padding: 0.2rem 0.5rem;
    font-size: 0.6rem;
    font-weight: 600;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
}

/* Pending Request Items */
.pending-request-item {
    background: #F8FAFC;
    border-left: 3px solid #f59e0b;
    transition: all 0.25s ease;
    margin-bottom: 0;
}

.pending-request-item:hover {
    transform: translateX(3px);
    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.2);
}

.request-type-badge {
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 0.25rem 0.625rem;
    font-size: 0.65rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.request-type-badge.emergency {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
}

.request-type-badge.reschedule {
    background: linear-gradient(135deg, #4DD3E0 0%, #38B3C0 100%);
    color: white;
}

.request-type-badge.regular {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: white;
}

/* History Items */
.history-date {
    border-radius: 8px;
    background: linear-gradient(135deg, #25079C 0%, #1a0569 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

.history-day {
    font-size: 1.2rem;
    color: white;
    font-weight: 700;
}

.history-month {
    color: white;
}

.history-year {
    color: white;
}

/* Responsive Adjustments */
/* Desktop styles - ensure desktop is not affected by mobile */
@media (min-width: 1201px) {
    .event-item {
        padding: 0.4rem 0.5rem !important;
        font-size: 0.7rem !important;
        min-height: 2.5rem !important;
        display: block !important;
        width: 100% !important;
    }
    
    .event-time {
        font-size: 0.7rem !important;
        margin-bottom: 0.2rem !important;
        overflow: visible !important; /* Allow time to be fully visible */
        text-overflow: clip !important; /* Don't truncate time */
        white-space: nowrap !important; /* Keep time on one line */
        word-break: keep-all !important; /* Keep time together */
    }
    
    .event-title {
        font-size: 0.65rem !important;
    }
    
    .calendar-day.fully-booked .event-item.booked {
        padding: 0.4rem 0.5rem !important;
        font-size: 0.7rem !important;
        min-height: 2.5rem !important;
    }
}

@media (max-width: 1200px) {
    .calendar-layout {
        grid-template-columns: 240px 1fr;
        gap: 0.875rem;
    }

    .calendar-day {
        min-height: 90px;
        padding: 0.45rem;
    }
    
    .day-events {
        margin-top: 1.5rem;
        gap: 0.25rem;
    }
    
    .event-item {
        padding: 0.3rem 0.45rem;
        font-size: 0.65rem;
        min-height: 2.2rem;
    }
}

@media (max-width: 1024px) {
    .calendar-layout {
        flex-direction: column;
    }

    .calendar-sidebar {
        position: static;
        max-height: none;
        flex: none;
        width: 100%;
        min-width: 0;
        max-width: none;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 0.75rem;
    }

    .calendar-main {
        order: -1;
        width: 100%;
    }

    .calendar-resizer {
        display: none;
    }
}

@media (max-width: 768px) {
    .calendar-container {
        padding: 0.5rem;
    }

    .calendar-header {
        padding: 0.75rem 0.75rem;
        margin-bottom: 0.75rem;
    }

    .calendar-main,
    .sidebar-card {
        padding: 0.75rem;
    }
    
    /* Calendar Grid - Better mobile spacing - Compact layout */
    .calendar-header-row {
        gap: 1px;
    }
    
    .calendar-week {
        gap: 1px;
    }
    
    .calendar-body {
        gap: 1px;
    }
    
    .calendar-day {
        min-height: 75px;
        padding: 0.35rem;
        position: relative;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    
    .day-number {
        font-size: 0.75rem;
        top: 0.25rem;
        left: 0.25rem;
        font-weight: 700;
        color: #1e293b;
        z-index: 3;
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
    }
    
    .calendar-day.today .day-number {
        color: white;
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        width: 18px;
        height: 18px;
        font-size: 0.65rem;
    }
    
    /* Event count badges - Make them more visible on mobile */
    .event-count-badge {
        width: 20px;
        height: 20px;
        font-size: 0.65rem;
        margin: 0.1rem;
        position: absolute;
        top: 0.3rem;
        right: 0.3rem;
        z-index: 4;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    /* Multiple badges positioning */
    .calendar-day .event-count-badge:nth-of-type(1) {
        top: 0.3rem;
        right: 0.3rem;
    }
    
    .calendar-day .event-count-badge:nth-of-type(2) {
        top: 0.3rem;
        right: 1.6rem;
    }
    
    .calendar-day .event-count-badge:nth-of-type(3) {
        top: 0.3rem;
        right: 2.9rem;
    }
    
    .day-events {
        margin-top: 1.5rem;
        gap: 0.25rem;
        padding-top: 0.2rem;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .event-item {
        padding: 0.35rem 0.4rem;
        font-size: 0.7rem;
        min-height: 2rem;
        line-height: 1.3;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        gap: 0.05rem;
    }
    
    .event-time {
        font-size: 0.65rem;
        margin-bottom: 0.05rem;
        max-width: 100%;
        overflow: visible; /* Allow time to be fully visible */
        text-overflow: clip; /* Don't truncate time */
        width: 100%; /* Take full width but don't truncate */
        flex-shrink: 0; /* Prevent time from shrinking */
        min-width: 0; /* Allow flexbox to work properly */
        word-break: keep-all; /* Keep time together */
        white-space: nowrap; /* Keep time on one line */
    }
    
    .event-title {
        font-size: 0.65rem;
        line-height: 1.2;
        max-width: 100%;
    }
    
    .event-notes {
        font-size: 0.6rem;
        -webkit-line-clamp: 1;
        margin-top: 0.05rem;
        max-width: 100%;
    }
    
    /* Mobile: Booked event items as rectangular boxes that fit within grid */
    .event-item.booked {
        width: 100% !important;
        max-width: 100% !important;
        min-height: 2rem;
        padding: 0.35rem 0.4rem;
        border-radius: 4px;
        border-left: 2px solid #9333ea;
        background: #f3e8ff;
        color: #6b21a8;
        position: relative;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        margin-left: 0;
        margin-bottom: 0.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        opacity: 1;
        box-sizing: border-box;
        gap: 0.05rem;
    }
    
    .event-item.booked:first-of-type {
        margin-left: 0;
    }
    
    /* All booked items use the same style - rectangular boxes */
    .event-item.booked:nth-of-type(1),
    .event-item.booked:nth-of-type(2),
    .event-item.booked:nth-of-type(3) {
        background: #f3e8ff;
        border-left-color: #9333ea;
        color: #6b21a8;
        z-index: 1;
    }
    
    .event-item.booked:nth-of-type(4) {
        background: #3b82f6;
        z-index: 4;
    }
    
    .event-item.booked:nth-of-type(5) {
        background: #9333ea;
        z-index: 5;
    }
    
    /* Hide text content in booked items on mobile */
    .event-item.booked .event-time,
    .event-item.booked .event-title,
    .event-item.booked .event-notes {
        display: none;
    }
    
    /* Container for booked items - overlapping circular layout */
    .day-events:has(.event-item.booked) {
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
        gap: 0;
        margin-top: 1.75rem;
        justify-content: flex-start;
        position: relative;
    }
    
    /* Count number below the circles */
    .day-events:has(.event-item.booked)::after {
        content: attr(data-booked-count);
        position: absolute;
        bottom: -1.25rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.65rem;
        font-weight: 600;
        color: white;
        text-align: center;
        white-space: nowrap;
        line-height: 1;
    }
    
    /* Dark mode: Count number color */
    [data-theme="dark"] .day-events:has(.event-item.booked)::after {
        color: #f1f5f9;
    }
    
    /* Fully-booked days: keep overlapping circles */
    .calendar-day.fully-booked .event-item.booked {
        width: 24px;
        height: 24px;
        min-height: 24px;
        margin-left: -8px;
        border-radius: 50%;
    }
    
    .calendar-day.fully-booked .event-item.booked:first-of-type {
        margin-left: 0;
    }
    
    .calendar-day.fully-booked .day-events:has(.event-item.booked) {
        flex-direction: row;
        flex-wrap: nowrap;
        gap: 0;
    }
    
    /* Non-booked items still use column layout */
    .day-events .event-item:not(.booked) {
        width: 100%;
        margin-left: 0;
    }
    
    /* When day has booked items, keep column layout for non-booked items */
    .day-events:has(.event-item.booked) .event-item:not(.booked) {
        width: 100%;
        margin-left: 0;
        margin-top: 0.25rem;
    }
    
    .event-time {
        font-size: 0.65rem;
        font-weight: 600;
        margin-bottom: 0.15rem;
    }
    
    .event-title {
        font-size: 0.65rem;
        line-height: 1.2;
    }
    
    .fully-booked-indicator {
        top: 0.3rem;
        right: 0.3rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.6rem;
        max-width: calc(100% - 2.5rem);
        z-index: 30;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);
    }
    
    /* Position booked circles below day number */
    .calendar-day .day-events:has(.event-item.booked) {
        margin-top: 1.75rem;
        padding-top: 0.5rem;
        justify-content: flex-start;
        padding-bottom: 1.5rem; /* Space for count number */
    }
    
    .calendar-day.fully-booked .day-events {
        margin-top: 2.2rem;
        padding-right: 0;
    }
    
    /* Ensure fully-booked indicator is on top of event circles */
    .calendar-day.fully-booked .fully-booked-indicator {
        z-index: 35;
    }
    
    /* Position booked circles when fully-booked indicator is present */
    .calendar-day.fully-booked .day-events:has(.event-item.booked) {
        margin-top: 2.2rem;
        padding-top: 0.5rem;
        padding-bottom: 1.5rem; /* Space for count number */
    }
    
    .calendar-day.fully-booked .day-number {
        max-width: calc(100% - 6rem);
        color: #1e293b;
    }
    
    /* Hide event count badges when fully booked indicator is present */
    .calendar-day.fully-booked .event-count-badge {
        display: none;
    }
    
    .event-more-indicator {
        padding: 0.4rem 0.5rem;
        font-size: 0.65rem;
        margin-top: 0.4rem;
        min-height: 36px;
    }
    
    /* Calendar header cells - More compact */
    .calendar-header-cell {
        padding: 0.5rem 0.3rem;
        font-size: 0.65rem;
        font-weight: 700;
    }

    /* Navigation buttons - Better touch targets */
    /* Note: Calendar nav buttons have specific styles below */
    .nav-btn {
        min-width: 44px;
        min-height: 44px;
        font-size: 0.875rem;
    }
    
    /* Override for calendar navigation buttons */
    .calendar-nav .nav-btn {
        width: auto !important;
        height: auto !important;
    }

    .btn-today {
        padding: 0.5rem 1rem;
        min-height: 44px;
        font-size: 0.85rem;
    }

    .calendar-controls {
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .view-controls {
        width: 100%;
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .view-btn {
        flex: 1;
        padding: 0.6rem 0.75rem;
        min-height: 44px;
        font-size: 0.85rem;
    }
    
    .calendar-nav {
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-left: 0;
        align-items: stretch;
    }
    
    /* Action buttons - Stack vertically (Book Now, Emergency, Reschedule) */
    /* Force each action button to take full width, causing them to stack */
    .calendar-nav .appointment-action-btn {
        flex: 0 0 100%;
        width: 100%;
        min-height: 44px;
        padding: 0.6rem 1rem !important;
        font-size: 0.8rem !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .calendar-nav .appointment-action-btn:first-of-type {
        order: 1;
    }
    
    .calendar-nav .appointment-action-btn:nth-of-type(2) {
        order: 2;
    }
    
    .calendar-nav .appointment-action-btn:nth-of-type(3) {
        order: 3;
    }
    
    /* Navigation buttons - Horizontal row (Prev, Today, Next) */
    /* With gap 0.75rem, account for 2 gaps (1.5rem total) */
    /* Target prevPeriod button specifically */
    .calendar-nav #prevPeriod.nav-btn {
        order: 4 !important;
        flex: 0 0 calc((100% - 1.5rem) * 0.25) !important;
        width: calc((100% - 1.5rem) * 0.25) !important;
        min-width: 44px;
        min-height: 44px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
    }
    
    .calendar-nav .btn-today {
        order: 5 !important;
        flex: 0 0 calc((100% - 1.5rem) * 0.5) !important;
        width: calc((100% - 1.5rem) * 0.5) !important;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Target nextPeriod button specifically */
    .calendar-nav #nextPeriod.nav-btn {
        order: 6 !important;
        flex: 0 0 calc((100% - 1.5rem) * 0.25) !important;
        width: calc((100% - 1.5rem) * 0.25) !important;
        min-width: 44px;
        min-height: 44px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
    }
    
    /* General nav-btn styles for mobile */
    .calendar-nav .nav-btn {
        width: auto !important;
        height: auto !important;
        min-width: 44px;
        min-height: 44px;
    }
    
    .calendar-nav .nav-btn i {
        font-size: 1rem;
    }

    .period-header {
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1rem;
        position: sticky;
        top: 0;
        z-index: 99;
        background: white;
        padding: 0.75rem;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    
    .period-title {
        font-size: 1.25rem;
        text-align: center;
    }

    .legend-inline {
        width: 100%;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .legend-item {
        padding: 0.4rem 0.75rem;
        font-size: 0.75rem;
    }

    /* Show only first 3 letters of day names on mobile */
    .calendar-header-cell {
        padding: 0.6rem 0.4rem;
        font-size: 0 !important;
        font-weight: 700;
        overflow: hidden;
        text-overflow: clip;
        white-space: nowrap;
        max-width: 100%;
        position: relative;
        line-height: 0 !important;
        color: transparent !important;
        text-indent: -9999px;
    }
    
    .calendar-header-cell::before {
        font-size: 0.7rem;
        display: block;
        line-height: normal;
        font-weight: 700;
        text-indent: 0;
        color: #64748b;
    }
    
    /* Dark mode color for abbreviated day names */
    [data-theme="dark"] .calendar-header-cell::before {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }
    
    /* Set abbreviated day names (first 3 letters) */
    .calendar-header-cell:nth-child(1)::before { content: 'Sun'; }
    .calendar-header-cell:nth-child(2)::before { content: 'Mon'; }
    .calendar-header-cell:nth-child(3)::before { content: 'Tue'; }
    .calendar-header-cell:nth-child(4)::before { content: 'Wed'; }
    .calendar-header-cell:nth-child(5)::before { content: 'Thu'; }
    .calendar-header-cell:nth-child(6)::before { content: 'Fri'; }
    .calendar-header-cell:nth-child(7)::before { content: 'Sat'; }
    
    .calendar-day.today .day-number {
        width: 18px;
        height: 18px;
        font-size: 0.65rem;
    }
    
    /* Better spacing for calendar grid on mobile */
    .month-grid {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Sidebar improvements for mobile */
    .calendar-sidebar {
        gap: 0.75rem;
    }
    
    .tab-btn {
        min-height: 44px;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }
    
    /* Mobile modals: fit screen, reduce padding/typography, scroll body only */
    .modal-dialog {
        margin: 0.75rem auto;
        max-width: calc(100% - 1.5rem);
        width: calc(100% - 1.5rem);
        box-sizing: border-box;
    }
    #appointmentRequestModal .modal-dialog,
    #appointmentDetailsModal .modal-dialog,
    #successModal .modal-dialog,
    #cancelConfirmationModal .modal-dialog,
    #cancelRescheduleModal .modal-dialog,
    #ratingModal .modal-dialog,
    #patientAppointmentConflictModal .modal-dialog,
    #patientClinicClosedModal .modal-dialog,
    #dayAppointmentsModal .modal-dialog {
        width: calc(100% - 1.5rem);
        max-width: calc(100% - 1.5rem);
        margin: 0.75rem;
        box-sizing: border-box;
    }
    #appointmentRequestModal .modal-content,
    #appointmentDetailsModal .modal-content,
    #successModal .modal-content,
    #cancelConfirmationModal .modal-content,
    #cancelRescheduleModal .modal-content,
    #ratingModal .modal-content,
    #patientAppointmentConflictModal .modal-content,
    #patientClinicClosedModal .modal-content,
    #dayAppointmentsModal .modal-content {
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        border-radius: 12px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        margin: 0;
    }
    #appointmentRequestModal .modal-header,
    #appointmentDetailsModal .modal-header,
    #successModal .modal-header,
    #cancelConfirmationModal .modal-header,
    #cancelRescheduleModal .modal-header,
    #ratingModal .modal-header,
    #patientAppointmentConflictModal .modal-header,
    #patientClinicClosedModal .modal-header,
    #dayAppointmentsModal .modal-header {
        padding: 0.75rem 0.9rem !important;
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
    }
    #appointmentRequestModal .modal-title,
    #appointmentDetailsModal .modal-title,
    #successModal .modal-title,
    #cancelConfirmationModal .modal-title,
    #cancelRescheduleModal .modal-title,
    #ratingModal .modal-title,
    #patientAppointmentConflictModal .modal-title,
    #patientClinicClosedModal .modal-title,
    #dayAppointmentsModal .modal-title {
        font-size: 1rem !important;
        margin: 0;
        padding: 0;
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    #appointmentRequestModal .modal-body,
    #appointmentDetailsModal .modal-body,
    #successModal .modal-body,
    #cancelConfirmationModal .modal-body,
    #cancelRescheduleModal .modal-body,
    #ratingModal .modal-body,
    #patientAppointmentConflictModal .modal-body,
    #patientClinicClosedModal .modal-body,
    #dayAppointmentsModal .modal-body {
        padding: 0.8rem !important;
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1 1 auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    #appointmentRequestModal .modal-footer,
    #appointmentDetailsModal .modal-footer,
    #successModal .modal-footer,
    #cancelConfirmationModal .modal-footer,
    #cancelRescheduleModal .modal-footer,
    #ratingModal .modal-footer,
    #patientAppointmentConflictModal .modal-footer,
    #patientClinicClosedModal .modal-footer,
    #dayAppointmentsModal .modal-footer {
        padding: 0.6rem 0.8rem !important;
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    /* Form elements inside modals */
    #appointmentRequestModal .form-label,
    #appointmentDetailsModal .form-label {
        font-size: 0.85rem !important;
        margin-bottom: 0.25rem !important;
    }
    #appointmentRequestModal .form-select,
    #appointmentRequestModal .form-control,
    #appointmentRequestModal .form-textarea,
    #appointmentDetailsModal .form-select,
    #appointmentDetailsModal .form-control {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.6rem !important;
    }
    #appointmentRequestModal .appointment-action-btn,
    #appointmentRequestModal .btn,
    #appointmentDetailsModal .btn,
    #successModal .btn,
    #cancelConfirmationModal .btn,
    #cancelRescheduleModal .btn,
    #ratingModal .btn,
    #patientAppointmentConflictModal .btn,
    #patientClinicClosedModal .btn,
    #dayAppointmentsModal .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.85rem !important;
        min-height: 44px;
    }
    
    /* Ensure modal buttons stack on mobile */
    #cancelConfirmationModal .modal-footer,
    #cancelRescheduleModal .modal-footer,
    #patientAppointmentConflictModal .modal-footer,
    #patientClinicClosedModal .modal-footer {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    #cancelConfirmationModal .modal-footer .btn,
    #cancelRescheduleModal .modal-footer .btn,
    #patientAppointmentConflictModal .modal-footer .btn,
    #patientClinicClosedModal .modal-footer .btn {
        width: 100%;
        margin: 0 !important;
    }
    
    /* Ensure close button is easily tappable on mobile */
    .modal-header .btn-close {
        width: 44px;
        height: 44px;
        min-width: 44px;
        min-height: 44px;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
    }
    
    /* Prevent horizontal overflow on mobile */
    .modal {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    /* Ensure modal content doesn't overflow */
    .modal-content {
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
        margin: 0;
    }
    
    /* Ensure all modal children respect width */
    .modal-content > * {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* Ensure modal footer respects width */
    .modal-footer {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* Prevent any content inside modals from overflowing */
    .modal-body *,
    .modal-header *,
    .modal-footer * {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* Ensure images and other media don't overflow */
    .modal-body img,
    .modal-body video,
    .modal-body iframe {
        max-width: 100%;
        height: auto;
    }
    
    /* Ensure modal header content doesn't overflow */
    .modal-header {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    
    .modal-header .modal-title {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .modal-header .btn-close {
        flex-shrink: 0;
        margin-left: 0.5rem;
    }
    
    /* Appointment Details Modal - Compact mobile version */
    #appointmentDetailsModal .modal-body {
        padding: 0.6rem !important;
        max-height: calc(92vh - 140px);
    }
    
    #appointmentDetailsModal .appointment-details-grid {
        gap: 0.75rem;
    }
    
    #appointmentDetailsModal .detail-card {
        padding: 0.75rem !important;
        gap: 0.625rem !important;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    #appointmentDetailsModal .detail-icon {
        width: 36px !important;
        height: 36px !important;
        border-radius: 8px;
    }
    
    #appointmentDetailsModal .detail-icon i {
        font-size: 1.1rem !important;
    }
    
    #appointmentDetailsModal .detail-label {
        font-size: 0.7rem !important;
        margin-bottom: 0.25rem !important;
        letter-spacing: 0.3px;
    }
    
    #appointmentDetailsModal .detail-value {
        font-size: 0.875rem !important;
        line-height: 1.3 !important;
    }
    
    #appointmentDetailsModal .modal-footer {
        padding: 0.5rem 0.6rem !important;
        gap: 0.5rem;
    }
    
    #appointmentDetailsModal .modal-footer .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.8rem !important;
        min-height: 40px;
    }
    
    /* Mobile-specific modal content adjustments */
    .modal-body h4,
    .modal-body h5 {
        font-size: 1rem !important;
        line-height: 1.4 !important;
    }
    
    .modal-body p {
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
    }
    
    /* Rating modal stars - make them larger and more touch-friendly */
    #ratingModal .star-rating {
        font-size: 2rem !important;
        gap: 0.5rem;
    }
    
    #ratingModal .star {
        cursor: pointer;
        min-width: 44px;
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Icon wrappers in modals - responsive sizing */
    .modal-body [style*="width: 80px"][style*="height: 80px"] {
        width: 60px !important;
        height: 60px !important;
    }
    
    .modal-body [style*="font-size: 2.5rem"],
    .modal-body [style*="font-size: 3rem"] {
        font-size: 2rem !important;
    }
    
    /* Cancel icon wrapper */
    .cancel-icon-wrapper {
        width: 60px !important;
        height: 60px !important;
    }
    
    .cancel-icon-wrapper i {
        font-size: 2rem !important;
    }
    
    /* Alert boxes in modals */
    .modal-body .alert {
        font-size: 0.85rem !important;
        padding: 0.75rem !important;
    }
    
    /* Cancel details box */
    .cancel-details-box {
        font-size: 0.875rem !important;
    }
    
    /* Time slot grid tighter */
    .time-slot-grid {
        gap: 0.35rem !important;
    }
    .time-slot-btn {
        padding: 0.4rem 0.5rem !important;
        font-size: 0.8rem !important;
        min-width: 66px !important;
    }
    
    /* Improve scrollability on mobile */
    .calendar-day {
        overflow: visible;
    }
    
    .day-events {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: calc(75px - 2rem);
    }
    
    /* Dark mode: Booked items as overlapping circular indicators on mobile */
    [data-theme="dark"] .event-item.booked {
        background: #3b82f6 !important;
        border: none !important;
    }
    
    [data-theme="dark"] .event-item.booked:nth-of-type(1) {
        background: #3b82f6 !important;
    }
    
    [data-theme="dark"] .event-item.booked:nth-of-type(2) {
        background: #9333ea !important;
    }
    
    [data-theme="dark"] .event-item.booked:nth-of-type(3) {
        background: #ef4444 !important;
    }
    
    [data-theme="dark"] .event-item.booked:nth-of-type(4) {
        background: #3b82f6 !important;
    }
    
    [data-theme="dark"] .event-item.booked:nth-of-type(5) {
        background: #9333ea !important;
    }
}

/* Additional mobile optimizations for very small screens */
@media (max-width: 480px) {
    .calendar-container {
        padding: 0.375rem;
    }
    
    .calendar-main,
    .sidebar-card {
        padding: 0.5rem;
    }
    
    .calendar-day {
        min-height: 70px;
        padding: 0.3rem;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    
    .day-number {
        font-size: 0.7rem;
        top: 0.2rem;
        left: 0.2rem;
        color: #1e293b;
        width: 22px;
        height: 22px;
    }
    
    .calendar-day.today .day-number {
        color: white;
        width: 16px;
        height: 16px;
        font-size: 0.6rem;
    }
    
    /* Event count badges - Smaller on very small screens */
    .event-count-badge {
        width: 18px;
        height: 18px;
        font-size: 0.6rem;
        top: 0.25rem;
        right: 0.25rem;
    }
    
    .calendar-day .event-count-badge:nth-of-type(2) {
        right: 1.4rem;
    }
    
    .calendar-day .event-count-badge:nth-of-type(3) {
        right: 2.55rem;
    }
    
    .day-events {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .event-item {
        padding: 0.3rem 0.35rem;
        font-size: 0.65rem;
        min-height: 1.75rem;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        gap: 0.05rem;
    }
    
    .event-time {
        font-size: 0.6rem;
        margin-bottom: 0.05rem;
        max-width: 100%;
    }
    
    .event-title {
        font-size: 0.6rem;
        line-height: 1.15;
        max-width: 100%;
    }
    
    .event-notes {
        font-size: 0.55rem;
        -webkit-line-clamp: 1;
        margin-top: 0.05rem;
        max-width: 100%;
    }
    
    .calendar-header-row,
    .calendar-week {
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        width: 100%;
        min-width: 100%;
    }
    
    .calendar-body {
        gap: 1px;
        width: 100%;
        overflow-x: hidden;
    }
    
    .fully-booked-indicator {
        top: 0.25rem;
        right: 0.25rem;
        padding: 0.2rem 0.4rem;
        font-size: 0.55rem;
    }
    
    .calendar-header-cell {
        padding: 0.4rem 0.2rem;
        font-size: 0.6rem;
    }
    
    .day-events {
        max-height: calc(70px - 1.75rem);
    }
    
    .period-title {
        font-size: 1.1rem;
    }
    
    .view-btn {
        padding: 0.5rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .appointment-action-btn {
        padding: 0.5rem 0.5rem !important;
        font-size: 0.75rem !important;
    }
    
    /* Ensure buttons stay aligned on very small screens */
    .calendar-nav {
        gap: 0.5rem;
    }
    
    /* Action buttons remain full width (stacked vertically) */
    .calendar-nav .appointment-action-btn {
        flex: 0 0 100%;
        width: 100%;
        padding: 0.5rem 0.75rem !important;
    }
    
    /* Navigation buttons horizontal row */
    /* With gap 0.5rem, account for 2 gaps (1rem total) */
    /* Target prevPeriod button specifically */
    .calendar-nav #prevPeriod.nav-btn {
        order: 4 !important;
        flex: 0 0 calc((100% - 1rem) * 0.25) !important;
        width: calc((100% - 1rem) * 0.25) !important;
        min-width: 44px;
        min-height: 44px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
    }
    
    .calendar-nav .btn-today {
        order: 5 !important;
        flex: 0 0 calc((100% - 1rem) * 0.5) !important;
        width: calc((100% - 1rem) * 0.5) !important;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Target nextPeriod button specifically */
    .calendar-nav #nextPeriod.nav-btn {
        order: 6 !important;
        flex: 0 0 calc((100% - 1rem) * 0.25) !important;
        width: calc((100% - 1rem) * 0.25) !important;
        min-width: 44px;
        min-height: 44px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
    }
    
    /* General nav-btn styles for very small screens */
    .calendar-nav .nav-btn {
        width: auto !important;
        height: auto !important;
        min-width: 44px;
        min-height: 44px;
    }
    
    .calendar-nav .nav-btn i {
        font-size: 0.9rem;
    }
    
    .legend-item {
        padding: 0.35rem 0.6rem;
        font-size: 0.7rem;
    }
    
    .legend-dot {
        width: 8px;
        height: 8px;
    }
    
    .calendar-day.today .day-number {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .fully-booked-indicator {
        font-size: 0.6rem;
        padding: 0.3rem 0.5rem;
    }
    
    .day-events {
        max-height: calc(85px - 2.25rem);
    }
}

/* Ultra-small devices (250px - 320px) */
@media (min-width: 250px) and (max-width: 320px) {
    .calendar-container {
        padding: 0.25rem;
    }
    
    .calendar-main,
    .sidebar-card {
        padding: 0.4rem;
    }
    
    .period-header {
        gap: 0.5rem;
        padding: 0.6rem;
    }
    
    .period-title {
        font-size: 1rem;
    }
    
    .calendar-header {
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .calendar-day {
        min-height: 64px;
        padding: 0.25rem;
    }
    
    .day-number {
        font-size: 0.65rem;
        top: 0.2rem;
        left: 0.2rem;
        width: 18px;
        height: 18px;
    }
    
    .calendar-day.today .day-number {
        width: 14px;
        height: 14px;
        font-size: 0.55rem;
    }
    
    .day-events {
        margin-top: 1.3rem;
        padding-bottom: 0.15rem;
    }
    
    .day-events .event-item {
        width: 20px !important;
        height: 20px !important;
        min-height: 20px !important;
        border-width: 1.5px !important;
        font-size: 0.55rem !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.25) !important;
    }
    
    .day-events .event-item:not(:first-child) {
        margin-top: -10px !important;
    }
    
    .mobile-event-count {
        font-size: 0.6rem;
    }
    
    .fully-booked-indicator {
        width: 16px !important;
        height: 16px !important;
        top: 0.2rem !important;
        right: 0.2rem !important;
    }
    
    .calendar-header-cell {
        padding: 0.35rem 0.2rem;
    }
    
    .calendar-header-cell::before {
        font-size: 0.6rem;
    }
    
    .event-count-badge {
        width: 16px;
        height: 16px;
        font-size: 0.55rem;
        top: 0.2rem;
        right: 0.2rem;
    }
    
    .calendar-day .event-count-badge:nth-of-type(2) {
        right: 1.2rem;
    }
    
    .calendar-day .event-count-badge:nth-of-type(3) {
        right: 2.25rem;
    }
    
    .calendar-nav {
        gap: 0.5rem;
    }
    
    .calendar-nav .nav-btn,
    .view-btn {
        min-height: 40px;
        font-size: 0.7rem;
    }
    
    .tab-btn {
        min-height: 40px;
        font-size: 0.75rem;
    }
    
    .legend-item {
        padding: 0.35rem 0.5rem;
        font-size: 0.7rem;
    }
    
    /* Ultra-small: modals tighter */
    #appointmentRequestModal .modal-content,
    #appointmentDetailsModal .modal-content,
    #successModal .modal-content,
    #cancelConfirmationModal .modal-content,
    #cancelRescheduleModal .modal-content,
    #ratingModal .modal-content,
    #patientAppointmentConflictModal .modal-content,
    #patientClinicClosedModal .modal-content,
    #dayAppointmentsModal .modal-content {
        max-height: 94vh;
        border-radius: 10px;
    }
    #appointmentRequestModal .modal-header,
    #appointmentDetailsModal .modal-header,
    #successModal .modal-header,
    #cancelConfirmationModal .modal-header,
    #cancelRescheduleModal .modal-header,
    #ratingModal .modal-header,
    #patientAppointmentConflictModal .modal-header,
    #patientClinicClosedModal .modal-header,
    #dayAppointmentsModal .modal-header {
        padding: 0.6rem 0.7rem !important;
    }
    #appointmentRequestModal .modal-title,
    #appointmentDetailsModal .modal-title,
    #successModal .modal-title,
    #cancelConfirmationModal .modal-title,
    #cancelRescheduleModal .modal-title,
    #ratingModal .modal-title,
    #patientAppointmentConflictModal .modal-title,
    #patientClinicClosedModal .modal-title,
    #dayAppointmentsModal .modal-title {
        font-size: 0.95rem !important;
    }
    #appointmentRequestModal .modal-body,
    #appointmentDetailsModal .modal-body,
    #successModal .modal-body,
    #cancelConfirmationModal .modal-body,
    #cancelRescheduleModal .modal-body,
    #ratingModal .modal-body,
    #patientAppointmentConflictModal .modal-body,
    #patientClinicClosedModal .modal-body,
    #dayAppointmentsModal .modal-body {
        padding: 0.6rem !important;
    }
    
    /* Ultra-small: Appointment Details Modal - Even more compact */
    #appointmentDetailsModal .modal-body {
        padding: 0.5rem !important;
        max-height: calc(94vh - 120px);
    }
    
    #appointmentDetailsModal .detail-card {
        padding: 0.625rem !important;
        gap: 0.5rem !important;
    }
    
    #appointmentDetailsModal .detail-icon {
        width: 32px !important;
        height: 32px !important;
    }
    
    #appointmentDetailsModal .detail-icon i {
        font-size: 1rem !important;
    }
    
    #appointmentDetailsModal .detail-label {
        font-size: 0.65rem !important;
        margin-bottom: 0.2rem !important;
    }
    
    #appointmentDetailsModal .detail-value {
        font-size: 0.8rem !important;
    }
    
    #appointmentDetailsModal .appointment-details-grid {
        gap: 0.5rem;
    }
    
    #appointmentDetailsModal .modal-footer {
        padding: 0.4rem 0.5rem !important;
    }
    
    #appointmentDetailsModal .modal-footer .btn {
        padding: 0.45rem 0.65rem !important;
        font-size: 0.75rem !important;
        min-height: 38px;
    }
    #appointmentRequestModal .form-select,
    #appointmentRequestModal .form-control,
    #appointmentRequestModal .form-textarea,
    #appointmentDetailsModal .form-select,
    #appointmentDetailsModal .form-control {
        font-size: 0.8rem !important;
        padding: 0.45rem 0.5rem !important;
    }
    .time-slot-btn {
        padding: 0.35rem 0.45rem !important;
        font-size: 0.75rem !important;
        min-width: 60px !important;
    }
    
    /* Day appointments modal list - better scrolling */
    #dayAppointmentsModal .day-appointments-list {
        max-height: 50vh !important;
    }
    
    /* Modal fully booked indicator */
    .modal-fully-booked-indicator {
        font-size: 0.75rem !important;
        padding: 0.4rem 0.75rem !important;
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

/* Book Now Button - Green Gradient */
.appointment-action-btn.book-now-btn {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4) !important;
    border: 2px solid #16a34a !important;
}

.appointment-action-btn.book-now-btn:hover {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
    box-shadow: 0 6px 20px rgba(34, 197, 94, 0.6) !important;
    transform: translateY(-2px) scale(1.02) !important;
    border-color: #15803d !important;
}

.appointment-action-btn.book-now-btn i {
    font-size: 1rem !important;
    filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.3));
}

/* Reschedule Button - Light Blue with Darker Hover (Light Mode Only) */
.appointment-action-btn.reschedule-btn {
    background: linear-gradient(135deg, #4DD3E0 0%, #38B3C0 100%) !important; /* use hover color as default */
    color: white !important;
    border: 2px solid #38B3C0 !important;
    box-shadow: none !important;
    text-shadow: none !important;
    animation: none !important;
}

.appointment-action-btn.reschedule-btn:hover {
    background: linear-gradient(135deg, #38B3C0 0%, #2EA3B1 100%) !important; /* slightly darker on hover */
    border-color: #2EA3B1 !important;
    color: white !important;
    box-shadow: none !important;
    transform: translateY(-2px) scale(1.02) !important;
    text-shadow: none !important;
}

.appointment-action-btn.reschedule-btn i {
    color: white !important; /* icon visible on white bg */
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

.input-with-icon select {
    width: 100%;
    padding: 0.75rem 2.75rem 0.75rem 0.875rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    transition: all 0.3s;
    background: #f8f9fa;
    appearance: none;
    cursor: pointer;
}

.input-with-icon input:hover {
    border-color: #cbd5e1;
    background: white;
}

.input-with-icon select:hover {
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

.input-with-icon select:focus {
    outline: none;
    border-color: #2196F3;
    background: white;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.input-with-icon input:focus + i {
    color: #2196F3;
}

.input-with-icon select:focus + i {
    color: #2196F3;
}

.time-slot-picker {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.85rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    position: relative;
}

.time-slot-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.time-slot-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.15) 0%, rgba(25, 118, 210, 0.08) 100%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #2196F3;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.time-slot-selected {
    font-weight: 600;
    color: #64748b;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.time-slot-selected.has-value {
    color: #1e293b;
}

.time-slot-grid {
    display: grid;
    gap: 0.5rem;
    grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
    max-height: 220px;
    overflow-y: auto;
    padding-right: 0.25rem;
}

.time-slot-btn {
    border: 1px solid #dbeafe;
    background: white;
    border-radius: 10px;
    padding: 0.55rem 0.35rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.time-slot-btn:hover {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.12) 0%, rgba(25, 118, 210, 0.05) 100%);
    border-color: #2196F3;
    color: #0f172a;
    transform: translateY(-1px);
}

.time-slot-btn:focus {
    outline: none;
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.25);
}

.time-slot-btn.selected {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-color: transparent;
    color: white;
    box-shadow: 0 6px 18px rgba(33, 150, 243, 0.35);
    transform: translateY(-1px);
}

.time-slot-btn.disabled,
.time-slot-btn.disabled:hover {
    background: #f1f5f9;
    border-color: #e2e8f0;
    color: #94a3b8;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

.time-slot-btn.disabled.selected {
    background: #f1f5f9;
    color: #94a3b8;
    box-shadow: none;
}

.time-slot-grid::-webkit-scrollbar {
    width: 6px;
}

.time-slot-grid::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.time-slot-grid::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 3px;
}

.time-slot-grid::-webkit-scrollbar-thumb:hover {
    background: #1976D2;
}

.time-slot-help {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    margin-bottom: 0.5rem;
    color: #94a3b8;
}

@media (max-width: 768px) {
    .time-slot-grid {
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        max-height: 200px;
    }

    .time-slot-btn {
        font-size: 0.8rem;
        padding: 0.45rem 0.3rem;
    }
}

@media (max-width: 480px) {
    .time-slot-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.4rem;
    }

    .time-slot-btn {
        font-size: 0.8rem;
        padding: 0.45rem 0.25rem;
    }
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

.btn-submit-time-slot {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 16px rgba(33, 150, 243, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    margin-top: 1rem;
    margin-left: 0;
    margin-right: 0;
}

.btn-submit-time-slot:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(33, 150, 243, 0.4);
}

.btn-submit-time-slot:active {
    transform: translateY(-1px);
}

.btn-submit-time-slot i {
    font-size: 1rem;
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

/* Status Badge Colors in Appointment Details Modal */
/* Confirmed = Blue (now using bg-primary) */
#appointmentDetailsModal .status-badge.confirmed,
#appointmentDetailsModal .badge.bg-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
}

/* Completed = Green (now using bg-success) */
#appointmentDetailsModal .status-badge.completed,
#appointmentDetailsModal .badge.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
}

[data-theme="dark"] #appointmentDetailsModal .status-badge.confirmed,
[data-theme="dark"] #appointmentDetailsModal .badge.bg-primary {
    background: #2196f3 !important;
    color: #FFFFFF !important;
}

[data-theme="dark"] #appointmentDetailsModal .status-badge.completed,
[data-theme="dark"] #appointmentDetailsModal .badge.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 12px rgba(16, 185, 129, 0.5),
        0 2px 6px rgba(16, 185, 129, 0.4),
        0 0 8px rgba(16, 185, 129, 0.3) !important;
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
    background: linear-gradient(135deg, #e5e7eb, #d1d5db);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.detail-icon i {
    font-size: 1.5rem;
    color: #6b7280;
}

/* Booked Slot Icon and Text Styles */
.booked-slot-icon {
    background: linear-gradient(135deg, #e5e7eb, #d1d5db) !important;
}

.booked-slot-icon i {
    color: #6b7280 !important;
}

.booked-slot-title {
    color: #1e293b;
}

.booked-slot-description {
    color: #64748b;
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
        gap: 0.75rem;
    }
    
    /* Reduce appointment details modal content sizes on mobile */
    #appointmentDetailsModal .modal-body {
        padding: 0.6rem !important;
        max-height: calc(92vh - 140px);
        overflow-y: auto;
    }
    
    #appointmentDetailsModal .detail-card {
        padding: 0.75rem !important;
        gap: 0.625rem !important;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    #appointmentDetailsModal .detail-icon {
        width: 36px !important;
        height: 36px !important;
        border-radius: 8px;
        flex-shrink: 0;
    }
    
    #appointmentDetailsModal .detail-icon i {
        font-size: 1.1rem !important;
    }
    
    #appointmentDetailsModal .detail-label {
        font-size: 0.7rem !important;
        margin-bottom: 0.25rem !important;
        letter-spacing: 0.3px;
    }
    
    #appointmentDetailsModal .detail-value {
        font-size: 0.875rem !important;
        line-height: 1.3 !important;
    }
    
    #appointmentDetailsModal .detail-content {
        min-width: 0;
        flex: 1;
    }
    
    /* Reduce modal footer padding */
    #appointmentDetailsModal .modal-footer {
        padding: 0.5rem 0.6rem !important;
        gap: 0.5rem;
    }
    
    #appointmentDetailsModal .modal-footer .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.8rem !important;
        min-height: 40px;
    }
    
    #appointmentDetailsModal .modal-footer .btn i {
        font-size: 0.85rem !important;
    }
}

/* Week View Styles */
.week-view {
    width: 100%;
    max-height: calc(100vh - 280px);
    overflow-y: auto;
    overflow-x: hidden; /* Prevent horizontal scroll - all days should fit */
    border-radius: 12px;
    -webkit-overflow-scrolling: touch;
}

.week-view-container {
    width: 100%;
    overflow-x: hidden; /* Prevent horizontal scroll */
    -webkit-overflow-scrolling: touch;
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
    width: 100%;
    min-width: 100%;
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
    width: 100%;
    min-width: 100%;
}

.week-row {
    display: grid;
    grid-template-columns: 75px repeat(7, 1fr);
    gap: 1px;
    width: 100%;
    min-width: 100%;
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
    overflow: hidden; /* Prevent content from overflowing */
    word-wrap: break-word;
    overflow-wrap: break-word;
    width: 100%;
    box-sizing: border-box;
}

.week-appointment {
    background: #e3f2fd;
    border-left: 2px solid #2196F3;
    padding: 0.3rem 0.375rem;
    border-radius: 4px;
    margin-bottom: 0.2rem;
    cursor: pointer;
    transition: all 0.2s;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    overflow: hidden;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
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
    background: #e3f2fd;
    border-left-color: #2196F3;
}

.week-appointment.completed {
    background: #e8f5e9;
    border-left-color: #4caf50;
}

.week-appointment.cancelled {
    background: #fef3c7;
    border-left-color: #92400e;
    color: #78350f;
    opacity: 0.7;
}

.week-appointment.blocked,
.week-view .week-appointment.blocked,
.week-body .week-appointment.blocked {
    background: #fff1f2 !important;
    border-left-color: #ef4444 !important;
    color: #991b1b !important;
    cursor: pointer;
}

.week-appointment.blocked.full-day-closure,
.week-view .week-appointment.blocked.full-day-closure {
    background: rgba(239, 68, 68, 0.2) !important;
    border-left-color: #ef4444 !important;
    color: #991b1b !important;
}

.week-appointment.missed {
    background: #e5e7eb;
    border-left-color: #6b7280;
    color: #374151;
    cursor: not-allowed;
}

.week-appointment.blocked:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    opacity: 1;
}

.week-appointment.missed:hover {
    transform: none;
    box-shadow: none;
}

.week-appointment.booked {
    background: #f3e8ff;
    border-left: 2px solid #9333ea;
    color: #6b21a8;
    cursor: pointer;
    opacity: 1;
}

.week-appointment.booked:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(147, 51, 234, 0.3);
    opacity: 1;
}

.week-apt-time {
    font-size: 0.65rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
    max-width: 100%;
    line-height: 1.2;
}

.week-apt-title {
    font-size: 0.7rem;
    color: #64748b;
    line-height: 1.2;
    word-wrap: break-word;
    overflow-wrap: break-word;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
    max-width: 100%;
    margin: 0;
}

.week-apt-notes {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.15rem;
    opacity: 0.85;
    line-height: 1.2;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    width: 100%;
    max-width: 100%;
}

/* Day View Styles - Compact to Fit Screen */
.day-view {
    width: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    max-height: calc(100vh - 300px);
    height: 100%;
}

.day-view-header {
    text-align: center;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 10px;
    margin-bottom: 1rem;
    color: white;
    flex-shrink: 0;
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
    overflow-y: auto;
    overflow-x: hidden;
    flex: 1;
    min-height: 0;
    -webkit-overflow-scrolling: touch;
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
    grid-template-columns: minmax(90px, auto) 1fr;
    gap: 1px;
    background: #e2e8f0;
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
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
}

.day-appointment {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-left: 3px solid #2196F3;
    padding: 0.625rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    word-wrap: break-word;
    overflow-wrap: break-word;
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
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left-color: #2196F3;
}

.day-appointment.completed {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-left-color: #4caf50;
}

.day-appointment.cancelled {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left-color: #92400e;
    color: #78350f;
    opacity: 0.8;
}

.day-appointment.blocked,
.day-view .day-appointment.blocked,
.day-view-body .day-appointment.blocked {
    background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%) !important;
    border-left-color: #ef4444 !important;
    color: #991b1b !important;
    cursor: pointer;
}

.day-appointment.blocked.full-day-closure,
.day-view .day-appointment.blocked.full-day-closure {
    background: #fff1f2 !important;
    border-left-color: #ef4444 !important;
    color: #991b1b !important;
}

.day-appointment.missed {
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    border-left-color: #6b7280;
    color: #374151;
    cursor: not-allowed;
}

.day-appointment.blocked:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.day-appointment.missed:hover {
    transform: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.day-appointment.booked {
    background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    border-left: 3px solid #9333ea;
    color: #6b21a8;
    cursor: pointer;
    opacity: 1;
}

.day-appointment.booked:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(147, 51, 234, 0.2);
    opacity: 1;
}

.day-apt-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.4rem;
    gap: 0.5rem;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}

.day-apt-time {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
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
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
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
    background: #d1fae5;
    color: #065f46;
}

.day-apt-badge.cancelled {
    background: #fef3c7;
    color: #78350f;
}

.day-apt-badge.blocked {
    background: #fff1f2;
    color: #991b1b;
}

.day-apt-badge.missed {
    background: #e5e7eb;
    color: #374151;
}

.day-apt-badge.booked {
    background: #f3e8ff;
    color: #6b21a8;
}

.day-apt-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.3rem;
    line-height: 1.3;
    word-wrap: break-word;
    overflow-wrap: break-word;
    width: 100%;
    max-width: 100%;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.day-apt-notes {
    font-size: 0.75rem;
    color: #64748b;
    display: -webkit-box;
    align-items: center;
    padding-top: 0.375rem;
    border-top: 1px solid rgba(0,0,0,0.05);
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    word-wrap: break-word;
    overflow-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
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
/* Appointments adjust to fit grid, grid stays fixed */
@media (max-width: 992px) {
    .week-header, .week-row {
        grid-template-columns: 60px repeat(7, 1fr);
        gap: 1px;
        width: 100%;
        min-width: 100%;
    }
    
    .time-column-header {
        padding: 0.4rem 0.3rem;
        font-size: 0.65rem;
    }
    
    .week-day-header {
        padding: 0.45rem 0.3rem;
    }
    
    .day-name {
        font-size: 0.65rem;
    }
    
    .day-date {
        font-size: 0.85rem;
    }
    
    .time-slot {
        padding: 0.3rem 0.2rem;
        font-size: 0.65rem;
        min-height: 35px;
    }
    
    .week-cell {
        min-height: 40px;
        padding: 0.3rem 0.25rem;
        overflow: hidden;
    }
    
    .week-appointment {
        padding: 0.25rem 0.3rem;
        font-size: 0.65rem;
        margin-bottom: 0.2rem;
        max-width: 100%;
    }
    
    .week-apt-time {
        font-size: 0.6rem;
        margin-bottom: 0.1rem;
    }
    
    .week-apt-title {
        font-size: 0.65rem;
        -webkit-line-clamp: 2;
        line-height: 1.2;
    }
    
    .week-apt-notes {
        font-size: 0.6rem;
        -webkit-line-clamp: 1;
        margin-top: 0.1rem;
    }
    
    .day-time-row {
        grid-template-columns: 70px 1fr;
    }
    
    .day-time-label {
        padding: 0.5rem 0.4rem;
        font-size: 0.7rem;
    }
    
    .day-time-content {
        padding: 0.45rem;
    }
    
    .day-appointment {
        padding: 0.55rem;
    }
    
    .day-apt-title {
        font-size: 0.8rem;
    }
}

@media (max-width: 768px) {
    .week-header, .week-row {
        /* All 7 days fit on one screen */
        grid-template-columns: 50px repeat(7, 1fr);
        gap: 1px;
        width: 100%;
        min-width: 100%;
    }
    
    /* Make the actual week view scrollable on mobile */
    .week-view {
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }
    
    .time-column-header {
        padding: 0.35rem 0.2rem;
        font-size: 0.6rem;
    }
    
    .week-day-header {
        padding: 0.4rem 0.25rem;
    }
    
    .day-name {
        font-size: 0.6rem;
        margin-bottom: 0.1rem;
    }
    
    .day-date {
        font-size: 0.75rem;
    }
    
    .time-slot {
        padding: 0.25rem 0.15rem;
        font-size: 0.6rem;
        min-height: 35px;
    }
    
    /* Improve individual week cells on mobile - appointments adjust to fit */
    .week-cell {
        min-height: 45px;
        padding: 0.3rem 0.2rem;
        overflow: hidden; /* Prevent overflow */
        width: 100%;
        box-sizing: border-box;
    }
    
    .week-appointment {
        margin-bottom: 0.25rem;
        padding: 0.25rem 0.2rem;
        font-size: 0.6rem;
        line-height: 1.2;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
        gap: 0.05rem;
    }
    
    .week-apt-time {
        font-size: 0.55rem;
        font-weight: 700;
        margin-bottom: 0.1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        max-width: 100%;
    }
    
    .week-apt-title {
        font-size: 0.6rem;
        line-height: 1.2;
        word-wrap: break-word;
        overflow-wrap: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        max-width: 100%;
        margin: 0;
    }
    
    .week-apt-notes {
        font-size: 0.55rem;
        -webkit-line-clamp: 1;
        display: none; /* Hide notes on mobile to save space */
        width: 100%;
        max-width: 100%;
    }

    .day-time-row {
        grid-template-columns: 65px 1fr;
        gap: 2px;
    }

    .day-view-title {
        font-size: clamp(1rem, 3vw, 1.15rem);
        text-align: center;
    }

    .day-view-date {
        font-size: clamp(0.8rem, 2vw, 0.9rem);
    }

    .day-view-header {
        padding: 0.75rem 0.75rem;
        margin-bottom: 0.75rem;
        position: sticky;
        top: 0;
        z-index: 100;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    /* Ensure day view header stays above period header */
    .day-view .period-header {
        z-index: 98;
    }

    .day-time-label {
        padding: 0.5rem 0.35rem;
        font-size: 0.7rem;
        font-weight: 600;
        min-width: 65px;
    }

    .day-time-content {
        min-height: 50px;
        padding: 0.5rem;
        width: 100%;
        box-sizing: border-box;
    }

    .day-appointment {
        padding: 0.6rem;
        min-height: 60px;
        margin-bottom: 0.5rem;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }

    .day-apt-title {
        font-size: clamp(0.75rem, 2vw, 0.8rem);
        line-height: 1.3;
        max-width: 100%;
    }

    .day-apt-time {
        font-size: 0.7rem;
        max-width: 100%;
    }
    
    .day-apt-badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
        max-width: 100%;
    }
    
    .day-apt-header {
        max-width: 100%;
        gap: 0.4rem;
    }
    
    .day-apt-notes {
        font-size: 0.7rem;
        -webkit-line-clamp: 2;
    }
    
    /* Week view container - no horizontal scroll, all days fit */
    .week-view-container {
        overflow-x: hidden; /* Prevent horizontal scroll */
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .week-header, .week-row {
        /* All 7 days fit on one screen - even smaller */
        grid-template-columns: 45px repeat(7, 1fr);
        gap: 1px;
        width: 100%;
        min-width: 100%;
    }
    
    .time-column-header {
        padding: 0.3rem 0.15rem;
        font-size: 0.55rem;
    }
    
    .week-day-header {
        padding: 0.35rem 0.2rem;
    }
    
    .day-name {
        font-size: 0.55rem;
        margin-bottom: 0.05rem;
    }
    
    .day-date {
        font-size: 0.7rem;
    }
    
    .time-slot {
        padding: 0.2rem 0.1rem;
        font-size: 0.55rem;
        min-height: 32px;
    }
    
    .week-cell {
        min-height: 40px;
        padding: 0.25rem 0.15rem;
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    
    .week-appointment {
        padding: 0.2rem 0.15rem;
        font-size: 0.55rem;
        margin-bottom: 0.2rem;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
        gap: 0.05rem;
    }
    
    .week-apt-time {
        font-size: 0.5rem;
        margin-bottom: 0.05rem;
        width: 100%;
        max-width: 100%;
    }
    
    .week-apt-title {
        font-size: 0.55rem;
        -webkit-line-clamp: 2;
        line-height: 1.15;
        width: 100%;
        max-width: 100%;
        margin: 0;
    }
    
    .week-apt-notes {
        display: none; /* Hide notes on very small screens */
    }

    .day-time-row {
        grid-template-columns: 60px 1fr;
        gap: 1px;
    }

    .day-view-title {
        font-size: 0.95rem;
    }

    .day-view-date {
        font-size: 0.75rem;
    }

    .day-view-header {
        padding: 0.625rem 0.625rem;
        margin-bottom: 0.625rem;
    }

    .day-time-label {
        padding: 0.4rem 0.3rem;
        font-size: 0.65rem;
        min-width: 60px;
    }

    .day-time-content {
        min-height: 45px;
        padding: 0.4rem;
        width: 100%;
        box-sizing: border-box;
    }

    .day-appointment {
        padding: 0.5rem;
        min-height: 55px;
        margin-bottom: 0.4rem;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }

    .day-apt-title {
        font-size: 0.75rem;
        -webkit-line-clamp: 2;
        max-width: 100%;
    }

    .day-apt-time {
        font-size: 0.65rem;
        max-width: 100%;
    }
    
    .day-apt-badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
        max-width: 100%;
    }
    
    .day-apt-header {
        max-width: 100%;
        gap: 0.3rem;
        margin-bottom: 0.3rem;
    }
    
    .day-apt-notes {
        font-size: 0.65rem;
        -webkit-line-clamp: 1;
    }
}

@media (max-width: 360px) {
    .week-header, .week-row {
        /* All 7 days fit on one screen - smallest phones */
        grid-template-columns: 40px repeat(7, 1fr);
        gap: 1px;
        width: 100%;
        min-width: 100%;
    }
    
    .time-column-header {
        font-size: 0.5rem;
        padding: 0.25rem 0.1rem;
    }
    
    .week-day-header {
        padding: 0.3rem 0.15rem;
    }
    
    .day-name {
        font-size: 0.5rem;
        margin-bottom: 0.05rem;
    }
    
    .day-date {
        font-size: 0.65rem;
    }
    
    .time-slot {
        font-size: 0.5rem;
        min-height: 30px;
        padding: 0.2rem 0.1rem;
    }
    
    .week-cell {
        min-height: 35px;
        padding: 0.2rem 0.15rem;
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    
    .week-appointment {
        padding: 0.15rem 0.1rem;
        font-size: 0.5rem;
        margin-bottom: 0.15rem;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
        gap: 0.05rem;
    }
    
    .week-apt-time {
        font-size: 0.45rem;
        margin-bottom: 0.05rem;
        width: 100%;
        max-width: 100%;
    }
    
    .week-apt-title {
        font-size: 0.5rem;
        -webkit-line-clamp: 2;
        line-height: 1.1;
        width: 100%;
        max-width: 100%;
        margin: 0;
    }

    .day-time-row {
        grid-template-columns: 55px 1fr;
    }

    .day-time-label {
        min-width: 55px;
        font-size: 0.6rem;
        padding: 0.35rem 0.25rem;
    }

    .day-time-content {
        padding: 0.35rem;
        width: 100%;
        box-sizing: border-box;
    }

    .day-appointment {
        padding: 0.45rem;
        min-height: 50px;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }

    .day-apt-title {
        font-size: 0.7rem;
        -webkit-line-clamp: 2;
        max-width: 100%;
    }
    
    .day-apt-time {
        font-size: 0.6rem;
        max-width: 100%;
    }
    
    .day-apt-badge {
        font-size: 0.55rem;
        padding: 0.15rem 0.35rem;
        max-width: 100%;
    }
    
    .day-apt-header {
        max-width: 100%;
        gap: 0.25rem;
        margin-bottom: 0.25rem;
    }
    
    .day-apt-notes {
        font-size: 0.6rem;
        -webkit-line-clamp: 1;
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

/* Compact width specifically for Book Appointment modal */
#appointmentRequestModal .modal-dialog.compact-book {
    max-width: 520px;
    width: min(520px, calc(100vw - 24px));
}

#appointmentRequestModal .modal-dialog.compact-book .modal-body {
    padding: 1rem 1.25rem;
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

/* Period Header Dark Mode */
[data-theme="dark"] .period-header {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
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

/* History Item Status Colors - Dark Mode */
[data-theme="dark"] .history-item.completed {
    background: rgba(16, 185, 129, 0.15) !important;
    border-left: 3px solid #10b981 !important;
}

[data-theme="dark"] .history-item.completed:hover {
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.4) !important;
}

[data-theme="dark"] .history-item.cancelled {
    background: rgba(146, 64, 14, 0.2) !important;
    border-left: 3px solid #d97706 !important;
    opacity: 0.95 !important;
}

[data-theme="dark"] .history-item.cancelled:hover {
    box-shadow: 0 3px 10px rgba(146, 64, 14, 0.4) !important;
}

[data-theme="dark"] .history-item.missed {
    background: rgba(107, 114, 128, 0.2) !important;
    border-left: 3px solid #9ca3af !important;
    opacity: 0.95 !important;
}

[data-theme="dark"] .history-item.missed:hover {
    box-shadow: 0 3px 10px rgba(107, 114, 128, 0.4) !important;
}

/* Upcoming Date Container - Status-Specific Shadows - Dark Mode */
[data-theme="dark"] .upcoming-date {
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
}

/* Pending Status - Yellow Shadow */
[data-theme="dark"] .upcoming-item.pending .upcoming-date {
    box-shadow: 
        0 4px 12px rgba(251, 191, 36, 0.5),
        0 2px 6px rgba(251, 191, 36, 0.4),
        0 0 15px rgba(251, 191, 36, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1),
        inset 0 -1px 0 rgba(0, 0, 0, 0.5) !important;
}

/* Confirmed Status - Blue Shadow */
[data-theme="dark"] .upcoming-item.confirmed .upcoming-date {
    box-shadow: 
        0 4px 12px rgba(59, 130, 246, 0.5),
        0 2px 6px rgba(59, 130, 246, 0.4),
        0 0 15px rgba(59, 130, 246, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1),
        inset 0 -1px 0 rgba(0, 0, 0, 0.5) !important;
}


/* History Date Dark Mode - Status-Specific Shadows */
[data-theme="dark"] .history-date {
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
}

/* Completed Status - Green Shadow */
[data-theme="dark"] .history-item.completed .history-date {
    box-shadow: 
        0 4px 12px rgba(16, 185, 129, 0.5),
        0 2px 6px rgba(16, 185, 129, 0.4),
        0 0 15px rgba(16, 185, 129, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1),
        inset 0 -1px 0 rgba(0, 0, 0, 0.5) !important;
}

/* Cancelled Status - Brown/Orange Shadow */
[data-theme="dark"] .history-item.cancelled .history-date {
    box-shadow: 
        0 4px 12px rgba(217, 119, 6, 0.5),
        0 2px 6px rgba(217, 119, 6, 0.4),
        0 0 15px rgba(217, 119, 6, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1),
        inset 0 -1px 0 rgba(0, 0, 0, 0.5) !important;
}

/* Missed Status - Gray Shadow */
[data-theme="dark"] .history-item.missed .history-date {
    box-shadow: 
        0 4px 12px rgba(107, 114, 128, 0.5),
        0 2px 6px rgba(107, 114, 128, 0.4),
        0 0 15px rgba(107, 114, 128, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1),
        inset 0 -1px 0 rgba(0, 0, 0, 0.5) !important;
}

[data-theme="dark"] .history-day,
[data-theme="dark"] .history-month,
[data-theme="dark"] .history-year {
    color: white !important;
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
    position: absolute !important;
    top: 0.375rem !important;
    left: 0.375rem !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    align-self: auto !important;
}

[data-theme="dark"] .day-number {
    color: var(--dm-text-primary, #f1f5f9) !important;
    position: absolute !important;
    top: 0.375rem !important;
    left: 0.375rem !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    align-self: auto !important;
    text-align: left !important;
}

[data-theme="dark"] .calendar-grid .calendar-day.today .day-number {
    position: absolute !important;
    top: -0.1rem !important;
    left: -0.1rem !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    align-self: auto !important;
}

[data-theme="dark"] .event-item {
    background: rgba(59, 130, 246, 0.15) !important;
    border-left-color: #3b82f6 !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .event-item.pending {
    background: rgba(251, 191, 36, 0.2) !important;
    border-left-color: #fbbf24 !important;
    color: #fef3c7 !important;
}

[data-theme="dark"] .event-item.confirmed {
    background: rgba(59, 130, 246, 0.2) !important;
    border-left-color: #3b82f6 !important;
    color: #dbeafe !important;
}

[data-theme="dark"] .event-item.completed {
    background: rgba(16, 185, 129, 0.2) !important;
    border-left-color: #10b981 !important;
    color: #d1fae5 !important;
}

[data-theme="dark"] .event-item.cancelled {
    background: rgba(146, 64, 14, 0.3) !important;
    border-left-color: #a16207 !important;
    color: #fef3c7 !important;
    opacity: 0.7 !important;
}

[data-theme="dark"] .event-item.blocked {
    background: rgba(239, 68, 68, 0.2) !important;
    border-left-color: #ef4444 !important;
    color: #fee2e2 !important;
}

[data-theme="dark"] .event-item.missed {
    background: rgba(107, 114, 128, 0.2) !important;
    border-left-color: #6b7280 !important;
    color: #e5e7eb !important;
}

[data-theme="dark"] .event-item.booked {
    background: rgba(147, 51, 234, 0.3) !important;
    border-left-color: #9333ea !important;
    color: #e9d5ff !important;
    cursor: pointer !important;
}

[data-theme="dark"] .event-item.booked:hover {
    background: rgba(147, 51, 234, 0.6) !important;
    transform: translateX(2px) !important;
    box-shadow: 0 2px 8px rgba(147, 51, 234, 0.4) !important;
}

[data-theme="dark"] .event-time {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .event-title {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .event-notes {
    color: var(--dm-text-muted, #94a3b8) !important;
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

[data-theme="dark"] .time-slot-picker {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-bg-primary, #0f172a) 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.4) !important;
}

[data-theme="dark"] .time-slot-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .time-slot-selected {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .time-slot-selected.has-value {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .time-slot-btn {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.35) !important;
}

[data-theme="dark"] .time-slot-btn:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.18) 0%, rgba(37, 99, 235, 0.12) 100%) !important;
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .time-slot-btn.selected {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    box-shadow: 0 8px 22px rgba(59, 130, 246, 0.45) !important;
}

[data-theme="dark"] .time-slot-btn.disabled,
[data-theme="dark"] .time-slot-btn.disabled:hover {
    background: rgba(15, 23, 42, 0.6) !important;
    border-color: rgba(148, 163, 184, 0.3) !important;
    color: rgba(148, 163, 184, 0.6) !important;
    box-shadow: none !important;
}

[data-theme="dark"] .time-slot-grid::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .time-slot-grid::-webkit-scrollbar-thumb {
    background: #3b82f6 !important;
}

[data-theme="dark"] .time-slot-grid::-webkit-scrollbar-thumb:hover {
    background: #2563eb !important;
}

[data-theme="dark"] .time-slot-help {
    color: var(--dm-text-muted, #94a3b8) !important;
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

/* Already Booked Slot Modal Dark Mode */
[data-theme="dark"] .booked-slot-icon {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.3), rgba(75, 85, 99, 0.3)) !important;
}

[data-theme="dark"] .booked-slot-icon i {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .booked-slot-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .booked-slot-description {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] #appointmentDetailsModal .modal-body .detail-icon {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(75, 85, 99, 0.2)) !important;
}

[data-theme="dark"] #appointmentDetailsModal .modal-body .detail-icon i {
    color: var(--dm-text-muted, #94a3b8) !important;
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
    background: rgba(33, 150, 243, 0.2) !important;
    border-left-color: #2196F3 !important;
}

[data-theme="dark"] .week-appointment.completed {
    background: rgba(34, 197, 94, 0.2) !important;
    border-left-color: #22c55e !important;
}

[data-theme="dark"] .week-appointment.cancelled {
    background: rgba(146, 64, 14, 0.3) !important;
    border-left-color: #a16207 !important;
    color: #fef3c7 !important;
    opacity: 0.7 !important;
}

[data-theme="dark"] .week-appointment.blocked {
    background: rgba(239, 68, 68, 0.2) !important;
    border-left-color: #ef4444 !important;
    color: #fee2e2 !important;
    cursor: pointer !important;
}

[data-theme="dark"] .week-appointment.missed {
    background: rgba(107, 114, 128, 0.2) !important;
    border-left-color: #6b7280 !important;
    color: #e5e7eb !important;
    cursor: not-allowed !important;
}

[data-theme="dark"] .week-appointment.blocked:hover {
    background: rgba(239, 68, 68, 0.3) !important;
    transform: translateX(2px) !important;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3) !important;
}

[data-theme="dark"] .week-appointment.missed:hover {
    transform: none !important;
    box-shadow: none !important;
}

[data-theme="dark"] .week-appointment.booked {
    background: rgba(147, 51, 234, 0.5) !important;
    border-left-color: #9333ea !important;
    color: #e9d5ff !important;
    cursor: default !important;
}

[data-theme="dark"] .week-appointment.booked:hover {
    background: rgba(147, 51, 234, 0.6) !important;
    transform: none !important;
    box-shadow: none !important;
}

[data-theme="dark"] .week-apt-time,
[data-theme="dark"] .week-apt-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .week-appointment.blocked .week-apt-time,
[data-theme="dark"] .week-appointment.blocked .week-apt-title,
[data-theme="dark"] .week-appointment.blocked .week-apt-notes {
    color: #fee2e2 !important;
}

/* Day View Dark Mode */
[data-theme="dark"] .day-view {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .day-view-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

[data-theme="dark"] .day-view-body {
    background: #1A202C !important;
}

[data-theme="dark"] .day-time-row {
    background: #1A202C !important;
}

[data-theme="dark"] .day-time-label {
    background: #2D3748 !important;
    color: #FFFFFF !important;
}

[data-theme="dark"] .day-time-content {
    background: #1A202C !important;
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
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.2) 0%, rgba(33, 150, 243, 0.15) 100%) !important;
    border-left-color: #2196F3 !important;
}

[data-theme="dark"] .day-appointment.completed {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(34, 197, 94, 0.15) 100%) !important;
    border-left-color: #22c55e !important;
}

[data-theme="dark"] .day-appointment.cancelled {
    background: linear-gradient(135deg, rgba(146, 64, 14, 0.3) 0%, rgba(146, 64, 14, 0.2) 100%) !important;
    border-left-color: #a16207 !important;
    color: #fef3c7 !important;
    opacity: 0.8 !important;
}

[data-theme="dark"] .day-appointment.blocked {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.15) 100%) !important;
    border-left-color: #ef4444 !important;
    color: #fee2e2 !important;
    cursor: pointer !important;
}

[data-theme="dark"] .day-appointment.blocked.full-day-closure,
[data-theme="dark"] .day-view .day-appointment.blocked.full-day-closure {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.25) 0%, rgba(220, 38, 38, 0.2) 100%) !important;
    border-left-color: #ef4444 !important;
    color: #fee2e2 !important;
}

[data-theme="dark"] .day-appointment.blocked.full-day-closure .day-apt-time,
[data-theme="dark"] .day-appointment.blocked.full-day-closure .day-apt-title,
[data-theme="dark"] .day-appointment.blocked.full-day-closure .day-apt-notes,
[data-theme="dark"] .day-appointment.blocked.full-day-closure .day-apt-badge {
    color: #fee2e2 !important;
}

[data-theme="dark"] .day-appointment.missed {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.3) 0%, rgba(107, 114, 128, 0.2) 100%) !important;
    border-left-color: #6b7280 !important;
    color: #e5e7eb !important;
    cursor: not-allowed !important;
}

[data-theme="dark"] .day-appointment.blocked:hover {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.3) 0%, rgba(220, 38, 38, 0.2) 100%) !important;
    transform: translateX(4px) !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3) !important;
}

[data-theme="dark"] .day-appointment.missed:hover {
    transform: none !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08) !important;
}

[data-theme="dark"] .day-appointment.booked {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.5) 0%, rgba(147, 51, 234, 0.4) 100%) !important;
    border-left-color: #9333ea !important;
    color: #e9d5ff !important;
    cursor: pointer !important;
}

[data-theme="dark"] .day-appointment.booked:hover {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.6) 0%, rgba(147, 51, 234, 0.5) 100%) !important;
    transform: translateX(4px) !important;
    box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3) !important;
}

[data-theme="dark"] .day-apt-badge.pending {
    background: rgba(251, 191, 36, 0.3) !important;
    color: #fef3c7 !important;
}

[data-theme="dark"] .day-apt-badge.cancelled {
    background: rgba(146, 64, 14, 0.3) !important;
    color: #fef3c7 !important;
}

[data-theme="dark"] .day-apt-badge.blocked {
    background: rgba(239, 68, 68, 0.3) !important;
    color: #fee2e2 !important;
}

[data-theme="dark"] .day-apt-badge.missed {
    background: rgba(107, 114, 128, 0.3) !important;
    color: #e5e7eb !important;
}

[data-theme="dark"] .day-apt-badge.confirmed {
    background: rgba(59, 130, 246, 0.3) !important;
    color: #dbeafe !important;
}

[data-theme="dark"] .day-apt-badge.completed {
    background: rgba(34, 197, 94, 0.3) !important;
    color: #d1fae5 !important;
}

[data-theme="dark"] .day-apt-badge.booked {
    background: rgba(147, 51, 234, 0.5) !important;
    color: #e9d5ff !important;
}

[data-theme="dark"] .day-apt-time,
[data-theme="dark"] .day-apt-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .day-appointment.blocked .day-apt-time,
[data-theme="dark"] .day-appointment.blocked .day-apt-title,
[data-theme="dark"] .day-appointment.blocked .day-apt-notes {
    color: #fee2e2 !important;
}

[data-theme="dark"] .day-apt-notes {
    color: var(--dm-text-muted, #94a3b8) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .day-empty-slot {
    color: var(--dm-text-muted, #64748b) !important;
}

/* Status Badge Dark Mode */
[data-theme="dark"] .status-badge.confirmed {
    background: #2196f3 !important;
    color: #FFFFFF !important;
}

[data-theme="dark"] .status-badge.completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 12px rgba(16, 185, 129, 0.5),
        0 2px 6px rgba(16, 185, 129, 0.4),
        0 0 8px rgba(16, 185, 129, 0.3) !important;
}

[data-theme="dark"] .badge.status-badge-brown {
    background: linear-gradient(135deg, #d97706 0%, #a16207 100%) !important;
    color: #fff8e7 !important;
    box-shadow:
        0 4px 12px rgba(217, 119, 6, 0.45),
        0 2px 6px rgba(217, 119, 6, 0.35),
        0 0 8px rgba(217, 119, 6, 0.25) !important;
}

[data-theme="dark"] .status-badge.cancelled {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 12px rgba(217, 119, 6, 0.5),
        0 2px 6px rgba(217, 119, 6, 0.4),
        0 0 8px rgba(217, 119, 6, 0.3) !important;
}

[data-theme="dark"] .status-badge.missed {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 12px rgba(107, 114, 128, 0.5),
        0 2px 6px rgba(107, 114, 128, 0.4),
        0 0 8px rgba(107, 114, 128, 0.3) !important;
}

[data-theme="dark"] .status-badge.automatic-status {
    background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%) !important;
    color: white !important;
    box-shadow: 
        0 2px 6px rgba(100, 116, 139, 0.4),
        0 1px 3px rgba(100, 116, 139, 0.3) !important;
}

/* Request Type Badge Dark Mode - Same Colors as Light Mode with Status-Specific Shadows */
[data-theme="dark"] .request-type-badge.emergency {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 12px rgba(33, 150, 243, 0.5),
        0 2px 6px rgba(33, 150, 243, 0.4),
        0 0 8px rgba(33, 150, 243, 0.3) !important;
}

[data-theme="dark"] .request-type-badge.reschedule {
    background: linear-gradient(135deg, #4DD3E0 0%, #38B3C0 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 12px rgba(77, 211, 224, 0.5),
        0 2px 6px rgba(77, 211, 224, 0.4),
        0 0 8px rgba(77, 211, 224, 0.3) !important;
}

[data-theme="dark"] .request-type-badge.regular {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 12px rgba(34, 197, 94, 0.5),
        0 2px 6px rgba(34, 197, 94, 0.4),
        0 0 8px rgba(34, 197, 94, 0.3) !important;
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
    box-shadow: 
        0 4px 15px rgba(59, 130, 246, 0.6),
        0 0 20px rgba(59, 130, 246, 0.4),
        0 0 30px rgba(59, 130, 246, 0.3) !important;
    border-color: #2563eb !important;
}

[data-theme="dark"] .appointment-action-btn.emergency-btn:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    box-shadow: 
        0 6px 20px rgba(59, 130, 246, 0.7),
        0 0 25px rgba(59, 130, 246, 0.5),
        0 0 40px rgba(59, 130, 246, 0.4) !important;
    transform: translateY(-2px) scale(1.02) !important;
    border-color: #1d4ed8 !important;
}

/* Book Now Button Dark Mode - Green Glow */
[data-theme="dark"] .appointment-action-btn.book-now-btn {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%) !important;
    color: white !important;
    box-shadow: 
        0 4px 15px rgba(34, 197, 94, 0.6),
        0 0 20px rgba(34, 197, 94, 0.4),
        0 0 30px rgba(34, 197, 94, 0.3) !important;
    border-color: #16a34a !important;
}

[data-theme="dark"] .appointment-action-btn.book-now-btn:hover {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
    box-shadow: 
        0 6px 20px rgba(34, 197, 94, 0.7),
        0 0 25px rgba(34, 197, 94, 0.5),
        0 0 40px rgba(34, 197, 94, 0.4) !important;
    transform: translateY(-2px) scale(1.02) !important;
    border-color: #15803d !important;
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

/* Upcoming Appointments Dark Mode - Status-Based Colors */
[data-theme="dark"] .upcoming-item.pending {
    background: rgba(251, 191, 36, 0.15) !important;
    border-left: 3px solid #fbbf24 !important;
}

[data-theme="dark"] .upcoming-item.pending:hover {
    box-shadow: 0 3px 10px rgba(251, 191, 36, 0.4) !important;
}

[data-theme="dark"] .upcoming-item.confirmed {
    background: rgba(59, 130, 246, 0.15) !important;
    border-left: 3px solid #3b82f6 !important;
}

[data-theme="dark"] .upcoming-item.confirmed:hover {
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.4) !important;
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
    color: #fee2e2 !important;
}

/* Success Modal Dark Mode */
[data-theme="dark"] #successModal .modal-content {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] #successModal .modal-body h5 {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] #successModal .modal-body p {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] #successModal .modal-footer {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
}

/* Time Availability Messages */
[data-theme="dark"] #timeAvailabilityMessage {
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #emergencyTimeSuggestionMessage,
[data-theme="dark"] #rescheduleTimeSuggestionMessage,
[data-theme="dark"] #timeSuggestionMessage {
    border-color: var(--dm-border-color, #334155) !important;
}

/* ============================================
   CUSTOM DATE PICKER CALENDAR WIDGET
   ============================================ */

.custom-date-picker-container {
    width: 100%;
    margin-bottom: 0.5rem;
}

/* Light Mode (Default - White Background) */
.custom-calendar-widget {
    background: #ffffff;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    width: 100%;
    max-width: 350px;
}

.custom-calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.custom-calendar-title {
    font-weight: 700;
    font-size: 1rem;
    color: #1e293b;
    font-family: sans-serif;
}

.custom-calendar-nav {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.custom-calendar-month-year {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1e293b;
    font-family: sans-serif;
    min-width: 120px;
    text-align: center;
}

.custom-calendar-nav-btn {
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    border-radius: 4px;
}

.custom-calendar-nav-btn:hover {
    background: #f1f5f9;
    color: #2196F3;
    transform: scale(1.1);
}

.custom-calendar-nav-btn i {
    font-size: 0.9rem;
}

.custom-calendar-days-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.custom-calendar-days-header span {
    text-align: center;
    font-weight: 700;
    font-size: 0.75rem;
    color: #64748b;
    font-family: sans-serif;
    padding: 0.25rem 0;
}

#bookFormSection .form-select {
    max-width: 380px;
    margin: 0 auto;
}

.custom-calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    margin-bottom: 0.75rem;
}

.custom-calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.85rem;
    font-family: sans-serif;
    transition: all 0.2s ease;
    position: relative;
    border: 1px solid transparent;
    overflow: hidden;
}

.custom-calendar-day .date-number {
    font-weight: 600;
    display: block;
}

.custom-calendar-day.inactive {
    color: #cbd5e1;
    cursor: not-allowed;
    opacity: 0.6;
}

.custom-calendar-day.active {
    color: #1e293b;
}

.custom-calendar-day.active:hover {
    background: #f1f5f9;
    border-color: rgba(33, 150, 243, 0.3);
    color: #2196F3;
}

.custom-calendar-day.selected {
    background: #2196F3;
    border: 2px solid #1976D2;
    color: #ffffff;
    font-weight: 700;
}

.custom-calendar-day.today {
    border: 1px solid #2196F3;
    background: rgba(33, 150, 243, 0.1);
}


.custom-calendar-day.disabled {
    color: #000000;
    cursor: not-allowed;
    opacity: 0.5;
    background: #f8fafc;
    pointer-events: none;
}

.custom-calendar-day.disabled:hover {
    background: #f8fafc;
}

.custom-calendar-day.disabled .date-number {
    opacity: 0.7;
}

.custom-calendar-day .day-status-label {
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.55rem;
    font-weight: 700;
    padding: 0.15rem 0.45rem;
    border-radius: 999px;
    letter-spacing: 0.02em;
    pointer-events: none;
}

.custom-calendar-day.closed-day {
    position: relative;
    cursor: not-allowed;
    pointer-events: none;
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.35);
    color: #b91c1c;
}

/* Full-width strike-through bar for closed days */
.custom-calendar-day.closed-day::after {
    content: '';
    position: absolute;
    left: 10%;
    right: 10%;
    top: 50%;
    height: 3px;
    transform: translateY(-50%);
    background: #ef4444; /* red-500 */
    border-radius: 2px;
}

.custom-calendar-day.closed-day .day-status-label {
    background: rgba(239, 68, 68, 0.2);
    color: #b91c1c;
}

.custom-calendar-day.fully-booked-day {
    background: rgba(59, 130, 246, 0.12);
    border: 1px solid rgba(59, 130, 246, 0.35);
    color: #1d4ed8;
}

.custom-calendar-day.fully-booked-day .day-status-label {
    background: rgba(59, 130, 246, 0.2);
    color: #1d4ed8;
}

[data-theme="dark"] .custom-calendar-day.closed-day::after {
    background: #f87171; /* red-400 */
}

[data-theme="dark"] .custom-calendar-day.closed-day {
    background: rgba(239, 68, 68, 0.18);
    border-color: rgba(239, 68, 68, 0.4);
    color: #ef4444;
}

[data-theme="dark"] .custom-calendar-day.closed-day .day-status-label {
    background: rgba(239, 68, 68, 0.25);
    color: #f87171;
}

[data-theme="dark"] .custom-calendar-day.fully-booked-day {
    background: rgba(59, 130, 246, 0.18);
    border-color: rgba(59, 130, 246, 0.35);
    color: #bfdbfe;
}

[data-theme="dark"] .custom-calendar-day.fully-booked-day .day-status-label {
    background: rgba(59, 130, 246, 0.25);
    color: #bfdbfe;
}

.custom-calendar-instruction {
    text-align: center;
    color: #64748b;
    font-size: 0.75rem;
    font-family: sans-serif;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e2e8f0;
}

/* Dark Mode Support */
[data-theme="dark"] .custom-calendar-widget {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .custom-calendar-header {
    border-bottom-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .custom-calendar-title {
    color: #f1f5f9;
}

[data-theme="dark"] .custom-calendar-month-year {
    color: #f1f5f9;
}

[data-theme="dark"] .custom-calendar-nav-btn {
    color: #94a3b8;
}

[data-theme="dark"] .custom-calendar-nav-btn:hover {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
}

[data-theme="dark"] .custom-calendar-days-header {
    border-bottom-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .custom-calendar-days-header span {
    color: #94a3b8;
}


[data-theme="dark"] .custom-calendar-day.inactive {
    color: #64748b;
    opacity: 0.5;
}

[data-theme="dark"] .custom-calendar-day.active {
    color: #f1f5f9;
}

[data-theme="dark"] .custom-calendar-day.active:hover {
    background: rgba(59, 130, 246, 0.2);
    border-color: rgba(96, 165, 250, 0.5);
    color: #60a5fa;
}

[data-theme="dark"] .custom-calendar-day.selected {
    background: #3b82f6;
    border-color: #60a5fa;
    color: #ffffff;
}

[data-theme="dark"] .custom-calendar-day.today {
    border-color: #60a5fa;
    background: rgba(59, 130, 246, 0.15);
}

[data-theme="dark"] .custom-calendar-day.disabled {
    color: #475569;
    background: rgba(15, 23, 42, 0.5);
    opacity: 0.5;
}

[data-theme="dark"] .custom-calendar-day.disabled:hover {
    background: rgba(15, 23, 42, 0.5);
}

[data-theme="dark"] .custom-calendar-instruction {
    color: #94a3b8;
    border-top-color: rgba(255, 255, 255, 0.1);
}

@media (max-width: 768px) {
    .custom-calendar-widget {
        max-width: 100%;
    }
    
    .custom-calendar-day {
        font-size: 0.75rem;
    }
    
    .custom-calendar-month-year {
        font-size: 0.8rem;
        min-width: 100px;
    }
}

/* ==============================
   Book Appointment modal spacing and alignment
   ============================== */
#bookFormSection .appointment-form {
    padding: 0.85rem;
    max-width: 420px;
    margin: 0 auto;
}
#bookFormSection .form-group { margin-bottom: 0.75rem; }
#bookFormSection .form-label { margin-bottom: 0.35rem; }
#bookFormSection .custom-date-picker-container { display: flex; justify-content: center; }
#bookFormSection .custom-calendar-widget { width: 100%; max-width: 380px; margin: 0 auto; }
#bookFormSection .form-actions { justify-content: center; }
#bookFormSection .btn-submit {
    min-width: 200px;
    padding: 0.7rem 1.6rem;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: none;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    letter-spacing: 0.2px;
    transition: all 0.25s ease;
}
#bookFormSection .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(37, 99, 235, 0.35);
}
#bookFormSection .btn-submit:active {
    transform: translateY(0);
}
#bookFormSection .btn-submit i {
    font-size: 1rem;
}

/* ========================================
   SCROLL REVEAL ANIMATIONS - REMOVED
   ======================================== */
/* Prevent overflow */
html, body {
    overflow-x: hidden;
    width: 100%;
}

.calendar-container {
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

<script>
// Open Appointment Modal with Type
function openAppointmentModal(type) {
    // Set appointment type
    appointmentType = type;

    // Hide all forms
    const emergencyFormSection = document.getElementById('emergencyFormSection');
    const rescheduleFormSection = document.getElementById('rescheduleFormSection');
    const bookFormSection = document.getElementById('bookFormSection');
    const modalDialog = document.querySelector('#appointmentRequestModal .modal-dialog');

    if (modalDialog) {
        modalDialog.classList.remove('compact-book');
    }

        if (type === 'emergency') {
        // Show emergency form, hide others
        if (emergencyFormSection) emergencyFormSection.style.display = 'block';
        if (rescheduleFormSection) rescheduleFormSection.style.display = 'none';
        if (bookFormSection) bookFormSection.style.display = 'none';


        // Update modal title
        const modalTitle = document.getElementById('appointmentRequestModalLabel');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-lightning-charge-fill me-2"></i>Emergency Appointment';
        }

        refreshTimeSlotAvailability('emergency');
        updateSelectedTimeLabel('emergencyTime', 'emergencyTimeSelected');
    } else if (type === 'reschedule') {
        // Show reschedule form, hide others
        if (emergencyFormSection) emergencyFormSection.style.display = 'none';
        if (rescheduleFormSection) rescheduleFormSection.style.display = 'block';
        if (bookFormSection) bookFormSection.style.display = 'none';

        // Update modal title
        const modalTitle = document.getElementById('appointmentRequestModalLabel');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Request Reschedule';
        }

        refreshTimeSlotAvailability('reschedule');
        updateSelectedTimeLabel('rescheduleTime', 'rescheduleTimeSelected');
    } else if (type === 'book') {
        // Show book form, hide others
        if (emergencyFormSection) emergencyFormSection.style.display = 'none';
        if (rescheduleFormSection) rescheduleFormSection.style.display = 'none';
        if (bookFormSection) bookFormSection.style.display = 'block';


        if (modalDialog) {
            modalDialog.classList.add('compact-book');
        }

        // Update modal title
        const modalTitle = document.getElementById('appointmentRequestModalLabel');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-calendar-plus me-2"></i>Book Appointment';
        }
    }

    updateTimeAvailability();
}

// Handle Book Service Selection (show/hide "Other" field)
document.addEventListener('DOMContentLoaded', function() {
    const bookServiceSelect = document.getElementById('bookServiceSelect');
    const bookOtherConcernGroup = document.getElementById('bookOtherConcernGroup');
    
    if (bookServiceSelect && bookOtherConcernGroup) {
        bookServiceSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                bookOtherConcernGroup.style.display = 'block';
                document.getElementById('bookOtherConcern').required = true;
            } else {
                bookOtherConcernGroup.style.display = 'none';
                document.getElementById('bookOtherConcern').required = false;
                document.getElementById('bookOtherConcern').value = '';
            }
        });
    }

    // Handle Book Form Submission
    const bookForm = document.getElementById('bookForm');
    if (bookForm) {
        bookForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('.btn-submit');
            const originalText = submitButton.innerHTML;

            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Booking...';

            // Duration-aware guard: ensure selected date can fit the chosen service duration
            try {
                appointmentType = 'book';
                const selectedDateVal = document.getElementById('bookDate').value;
                const durationNeeded = getRequestDuration();
                if (selectedDateVal && durationNeeded > 0) {
                    const dateObj = new Date(selectedDateVal + 'T00:00:00');
                    if (!canDayFitDuration(dateObj, durationNeeded)) {
                        e.preventDefault();
                        const h = Math.floor(durationNeeded / 60);
                        const m = durationNeeded % 60;
                        const needText = `${h > 0 ? h + ' hr' + (h > 1 ? 's' : '' ) : ''}${h > 0 && m > 0 ? ' ' : ''}${m > 0 ? m + ' min' : ''}`.trim() || 'selected duration';
                        alert(`This date has no continuous window available for ${needText}. Please choose a different date.`);
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        return;
                    }
                }
            } catch (_) {
                // If guard fails silently, proceed to server-side validation
            }

            e.preventDefault();

            const formData = {
                type: 'book', // Use 'book' type for regular appointment booking
                reason: 'Regular appointment booking',
                date: document.getElementById('bookDate').value,
                _token: '{{ csrf_token() }}'
            };

            // Include service information
            const serviceSelect = document.getElementById('bookServiceSelect');
            const otherConcern = document.getElementById('bookOtherConcern');
            
            if (serviceSelect && serviceSelect.value) {
                if (serviceSelect.value === 'other') {
                    if (otherConcern && otherConcern.value.trim()) {
                        formData.other_concern = otherConcern.value.trim();
                    } else {
                        alert('Please enter the service name.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        return;
                    }
                } else {
                    formData.service_id = serviceSelect.value;
                }
            }

            // Submit the form
            fetch('/patient/calendar/submit-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': formData._token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close appointment request modal
                    const requestModal = bootstrap.Modal.getInstance(document.getElementById('appointmentRequestModal'));
                    if (requestModal) requestModal.hide();
                    
                    // Reset form
                    bookForm.reset();
                    if (bookOtherConcernGroup) bookOtherConcernGroup.style.display = 'none';
                    
                    // Show success modal
                    const successMessage = data.message || 'Appointment request submitted successfully!';
                    document.getElementById('successModalMessage').textContent = successMessage;
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    
                    // Reload page when success modal is closed
                    document.getElementById('successModal').addEventListener('hidden.bs.modal', function() {
                        window.location.reload();
                    }, { once: true });
                } else {
                    alert(data.message || 'Failed to submit appointment request. Please try again.');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }
});

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
    // If book mode, read selected service duration
    else if (typeof appointmentType !== 'undefined' && appointmentType === 'book') {
        const bookServiceSelect = document.getElementById('bookServiceSelect');
        if (bookServiceSelect && bookServiceSelect.value && bookServiceSelect.value !== 'other') {
            const selectedOption = bookServiceSelect.options[bookServiceSelect.selectedIndex];
            const serviceDuration = selectedOption.getAttribute('data-duration');
            if (serviceDuration) {
                duration = parseInt(serviceDuration);
            }
        }
    }

    return duration;
}

function formatTimeLabel(value) {
    if (!value || typeof value !== 'string') return '';
    const [hourStr, minuteStr] = value.split(':');
    const hours = Number(hourStr);
    const minutes = Number(minuteStr);
    if (Number.isNaN(hours) || Number.isNaN(minutes)) return value;
    const date = new Date();
    date.setHours(hours, minutes, 0, 0);
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
}

function updateSelectedTimeLabel(inputId, displayId) {
    const input = document.getElementById(inputId);
    const display = document.getElementById(displayId);
    if (!input || !display) return;

    if (input.value) {
        display.textContent = formatTimeLabel(input.value);
        display.classList.add('has-value');
    } else {
        display.textContent = 'No time selected';
        display.classList.remove('has-value');
    }
}

function refreshTimeSlotAvailability(pickerType) {
    let dateInput, timeInput, grid;

    if (pickerType === 'emergency') {
        dateInput = document.getElementById('emergencyDate');
        timeInput = document.getElementById('emergencyTime');
        grid = document.getElementById('emergencyTimeSlots');
    } else if (pickerType === 'reschedule') {
        dateInput = document.getElementById('rescheduleDate');
        timeInput = document.getElementById('rescheduleTime');
        grid = document.getElementById('rescheduleTimeSlots');
    }

    if (!grid) return;

    const buttons = Array.from(grid.querySelectorAll('.time-slot-btn'));
    const selectedValue = timeInput ? timeInput.value : '';
    let clearedSelection = false;

    if (!dateInput || !dateInput.value) {
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.classList.remove('selected');
        });
        if (timeInput && timeInput.value) {
            timeInput.value = '';
            clearedSelection = true;
        }
    } else {
        const dateObj = new Date(dateInput.value + 'T00:00:00');
        buttons.forEach(btn => {
            const slotValue = btn.dataset.value;
            const available = isTimeSlotAvailable(dateObj, slotValue);
            btn.disabled = !available;
            btn.classList.toggle('disabled', !available);
            if (!available) {
                btn.classList.remove('selected');
                if (selectedValue === slotValue) {
                    clearedSelection = true;
                }
            } else if (selectedValue === slotValue) {
                btn.classList.add('selected');
            }
        });
        if (clearedSelection && timeInput) {
            timeInput.value = '';
        }
    }

    if (clearedSelection && timeInput) {
        timeInput.dispatchEvent(new Event('change', { bubbles: true }));
    } else if (timeInput) {
        updateSelectedTimeLabel(timeInput.id, pickerType === 'emergency' ? 'emergencyTimeSelected' : 'rescheduleTimeSelected');
    }
}

// GLOBAL: Check if a day is fully booked
function checkIfDayIsFullyBooked(dateStr, appointments, blockedTimes) {
    // Check if there's a full day closure
    const hasFullDayClosure = blockedTimes && blockedTimes.length > 0 && blockedTimes.some(function(blocked) {
        if (!blocked || !blocked.start_datetime || !blocked.end_datetime) return false;
        const startTime = parseLocalDateTime(blocked.start_datetime);
        const endTime = parseLocalDateTime(blocked.end_datetime);
        if (!startTime || !endTime) return false;
        return startTime.getHours() === 0 && startTime.getMinutes() === 0 &&
               endTime.getHours() === 23 && endTime.getMinutes() === 59;
    });
    
    if (hasFullDayClosure) {
        return true;
    }
    
    // Generate all 15-minute time slots from 11:00 AM to 6:00 PM
    const timeSlots = [];
    for (let hour = 11; hour <= 18; hour++) {
        for (let minute = 0; minute < 60; minute += 15) {
            if (hour === 18 && minute > 0) break; // Stop at 6:00 PM
            timeSlots.push({ hour: hour, minute: minute });
        }
    }
    
    // Check each time slot for availability
    const [year, month, day] = dateStr.split('-').map(Number);
    const minServiceDuration = 15; // Minimum service duration in minutes
    const defaultDuration = 30; // Default appointment duration in minutes
    
    // Check slots with minimum duration first (15 minutes), then default duration (30 minutes)
    const durationsToCheck = [minServiceDuration, defaultDuration];
    
    for (let d = 0; d < durationsToCheck.length; d++) {
        const duration = durationsToCheck[d];
        
        for (let i = 0; i < timeSlots.length; i++) {
            const slot = timeSlots[i];
            const slotStart = new Date(year, month - 1, day, slot.hour, slot.minute);
            const slotEnd = new Date(slotStart.getTime() + duration * 60000);
            
            // Make sure slot doesn't go past clinic closing time (6:00 PM)
            if (slotEnd.getHours() > 18 || (slotEnd.getHours() === 18 && slotEnd.getMinutes() > 0)) {
                continue; // Skip slots that extend past closing time
            }
            
            // Check if this slot is available
            let isAvailable = true;
            
            // Check against appointments
            if (appointments && appointments.length > 0) {
                for (let j = 0; j < appointments.length; j++) {
                    const apt = appointments[j];
                    if (!apt || !apt.start_datetime || !apt.end_datetime) continue;
                    
                    const aptStart = parseLocalDateTime(apt.start_datetime);
                    const aptEnd = parseLocalDateTime(apt.end_datetime);
                    if (!aptStart || !aptEnd) continue;
                    
                    // Check for overlap (excluding cancelled appointments)
                    const status = (apt.status || '').toLowerCase();
                    if (status !== 'cancelled' && slotStart < aptEnd && slotEnd > aptStart) {
                        isAvailable = false;
                        break;
                    }
                }
            }
            
            // Check against blocked times
            if (isAvailable && blockedTimes && blockedTimes.length > 0) {
                for (let j = 0; j < blockedTimes.length; j++) {
                    const blocked = blockedTimes[j];
                    if (!blocked || !blocked.start_datetime || !blocked.end_datetime) continue;
                    
                    const blockStart = parseLocalDateTime(blocked.start_datetime);
                    const blockEnd = parseLocalDateTime(blocked.end_datetime);
                    if (!blockStart || !blockEnd) continue;
                    
                    // Check for overlap
                    if (slotStart < blockEnd && slotEnd > blockStart) {
                        isAvailable = false;
                        break;
                    }
                }
            }
            
            // If any slot is available, day is not fully booked
            if (isAvailable) {
                return false;
            }
        }
    }
    
    // All slots are booked
    return true;
}

// GLOBAL: Determine if a time slot is available given current selections
function isTimeSlotAvailable(selectedDate, selectedTime) {
    if (!selectedDate || !selectedTime) return true;

    // Format date as YYYY-MM-DD for checking fully booked status
    const dateStr = selectedDate.getFullYear() + '-' + 
                   String(selectedDate.getMonth() + 1).padStart(2, '0') + '-' + 
                   String(selectedDate.getDate()).padStart(2, '0');
    
    // Get appointments and blocked times for this date
    const dayAppointments = (window.allAppointments || []).filter(function(apt) {
        if (!apt || !apt.start_datetime) return false;
        const aptStart = parseLocalDateTime(apt.start_datetime);
        if (!aptStart) return false;
        return aptStart.toDateString() === selectedDate.toDateString();
    });
    
    const dayBlockedTimes = (window.blockedTimes || []).filter(function(blocked) {
        if (!blocked || !blocked.start_datetime) return false;
        const blockStart = parseLocalDateTime(blocked.start_datetime);
        if (!blockStart) return false;
        return blockStart.toDateString() === selectedDate.toDateString();
    });
    
    // Check if the day is fully booked - if so, no slots are available
    if (checkIfDayIsFullyBooked(dateStr, dayAppointments, dayBlockedTimes)) {
        return false;
    }

    const [hours, minutes] = selectedTime.split(':').map(Number);

    // Enforce clinic hours (11:00 AM - 6:00 PM) with 15-minute increments
    if (
        Number.isNaN(hours) ||
        Number.isNaN(minutes) ||
        hours < 11 ||
        hours > 18 ||
        (hours === 18 && minutes > 0) ||
        minutes % 15 !== 0
    ) {
        return false;
    }

    // Use server time (fault tolerant) to block past selections
    const requestedStart = new Date(selectedDate);
    requestedStart.setHours(hours, minutes, 0);

    const serverNow = getServerTime();
    if (requestedStart < serverNow) {
        return false;
    }

    // Compute end by selected service/appointment duration
    const duration = getRequestDuration();
    const requestedEnd = new Date(requestedStart.getTime() + duration * 60000);

    // Check against all appointments
    for (const appointment of (window.allAppointments || [])) {
        const aptStart = parseLocalDateTime(appointment.start_datetime);
        const aptEnd = parseLocalDateTime(appointment.end_datetime);
        if (!aptStart || !aptEnd) continue;
        if (aptStart.toDateString() !== requestedStart.toDateString()) continue;
        if (requestedStart < aptEnd && requestedEnd > aptStart) {
            return false;
        }
    }

    // Check against blocked times
    for (const blockedTime of (window.blockedTimes || [])) {
        const blockStart = parseLocalDateTime(blockedTime.start_datetime);
        const blockEnd = parseLocalDateTime(blockedTime.end_datetime);
        if (!blockStart || !blockEnd) continue;
        if (blockStart.toDateString() !== requestedStart.toDateString()) continue;
        if (requestedStart < blockEnd && requestedEnd > blockStart) {
            return false;
        }
    }

    return true;
}

// GLOBAL: Show availability message and enable/disable submit
function updateTimeAvailability() {
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

    const { dateInput, timeInput } = getActiveFormInputs();
    if (!dateInput || !timeInput) return;

    const selectedDate = dateInput.value;
    const selectedTime = timeInput.value;
    const messageId = `${timeInput.id}AvailabilityMessage`;
    let messageDiv = document.getElementById(messageId);
    if (messageDiv) messageDiv.remove();

    const form = timeInput.form || timeInput.closest('form');
    const submitBtn = form ? form.querySelector('.btn-submit') : null;

    const disableSubmit = () => {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        }
    };
    const enableSubmit = () => {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    };

    if (!selectedDate || !selectedTime) {
        disableSubmit();
        return;
    }

    const dateObj = new Date(selectedDate + 'T00:00:00');
    const available = isTimeSlotAvailable(dateObj, selectedTime);
    const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

    messageDiv = document.createElement('div');
    messageDiv.id = messageId;
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
        enableSubmit();
    } else {
        // Provide a specific warning if the chosen duration exceeds the available window
        const [hh, mm] = selectedTime.split(':').map(Number);
        const startAt = new Date(dateObj);
        startAt.setHours(hh || 0, mm || 0, 0, 0);
        const maxFree = getMaxContinuousFreeMinutes(startAt);
        const need = typeof getRequestDuration === 'function' ? getRequestDuration() : 0;

        const formatMinutes = (mins) => {
            const h = Math.floor(mins / 60);
            const m = mins % 60;
            const parts = [];
            if (h > 0) parts.push(`${h} hr${h > 1 ? 's' : ''}`);
            if (m > 0) parts.push(`${m} min`);
            return parts.length ? parts.join(' ') : '0 min';
        };

        if (maxFree > 0 && need > maxFree) {
            if (isDarkMode) {
                messageDiv.style.background = 'linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%)';
                messageDiv.style.color = '#fbbf24';
                messageDiv.style.border = '2px solid #f59e0b';
            } else {
                messageDiv.style.background = 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)';
                messageDiv.style.color = '#92400e';
                messageDiv.style.border = '2px solid #f59e0b';
            }
            messageDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Only ${formatMinutes(maxFree)} is available from this start time, but the selected service needs ${formatMinutes(need)}. Please pick an earlier time or a different date.`;
        } else {
            if (isDarkMode) {
                messageDiv.style.background = 'linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%)';
                messageDiv.style.color = '#fee2e2';
                messageDiv.style.border = '2px solid #ef4444';
            } else {
                messageDiv.style.background = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)';
                messageDiv.style.color = '#991b1b';
                messageDiv.style.border = '2px solid #ef4444';
            }
            messageDiv.innerHTML = '<i class="bi bi-x-circle-fill"></i> This time slot is not available. Please select a different time.';
        }
        disableSubmit();
    }

    const timeFormGroup = timeInput.closest('.form-group');
    if (timeFormGroup) timeFormGroup.appendChild(messageDiv);
}

function initializeTimeSlotPicker(gridId, inputId, displayId, pickerType) {
    const grid = document.getElementById(gridId);
    const hiddenInput = document.getElementById(inputId);
    if (!grid || !hiddenInput) return;

    grid.addEventListener('click', function(event) {
        const button = event.target.closest('.time-slot-btn');
        if (!button || button.disabled || button.classList.contains('disabled')) return;

        grid.querySelectorAll('.time-slot-btn').forEach(btn => btn.classList.remove('selected'));
        button.classList.add('selected');
        hiddenInput.value = button.dataset.value || '';
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        updateSelectedTimeLabel(inputId, displayId);
    });

    hiddenInput.addEventListener('change', function() {
        if (!hiddenInput.value) {
            grid.querySelectorAll('.time-slot-btn').forEach(btn => btn.classList.remove('selected'));
        } else {
            grid.querySelectorAll('.time-slot-btn').forEach(btn => {
                btn.classList.toggle('selected', btn.dataset.value === hiddenInput.value);
            });
        }
        updateSelectedTimeLabel(inputId, displayId);
    });

    updateSelectedTimeLabel(inputId, displayId);
    refreshTimeSlotAvailability(pickerType);
}

function clearTimeSlotSelection(gridId, inputId, displayId) {
    const grid = document.getElementById(gridId);
    const hiddenInput = document.getElementById(inputId);
    const display = document.getElementById(displayId);

    if (grid) {
        grid.querySelectorAll('.time-slot-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
    }

    if (hiddenInput) {
        hiddenInput.value = '';
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    if (display) {
        display.textContent = 'No time selected';
        display.classList.remove('has-value');
    }
}


// Form submission handler
// Handle Emergency Form Submission
document.getElementById('emergencyForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitButton = document.querySelector('#emergencyFormSection .btn-submit-time-slot') || this.querySelector('.btn-submit') || this.querySelector('.btn-submit-time-slot');
    if (!submitButton) {
        console.error('Submit button not found');
        return;
    }
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
            // Close appointment request modal
            const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentRequestModal'));
            if (appointmentModal) {
                appointmentModal.hide();
            }

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

            clearTimeSlotSelection('emergencyTimeSlots', 'emergencyTime', 'emergencyTimeSelected');
            refreshTimeSlotAvailability('emergency');
            updateTimeAvailability();

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

            // Show success modal
            const successMessage = data.message || 'Your request has been submitted successfully!';
            document.getElementById('successModalMessage').textContent = successMessage;
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Reload page when success modal is closed
            document.getElementById('successModal').addEventListener('hidden.bs.modal', function() {
                window.location.reload();
            }, { once: true });
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

    const submitButton = document.querySelector('#rescheduleFormSection .btn-submit-time-slot') || this.querySelector('.btn-submit') || this.querySelector('.btn-submit-time-slot');
    if (!submitButton) {
        console.error('Submit button not found');
        return;
    }
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
            // Close appointment request modal
            const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentRequestModal'));
            if (appointmentModal) {
                appointmentModal.hide();
            }

            // Reset form
            this.reset();

            // Reset reschedule form fields
            const rescheduleAppointmentSelect = document.getElementById('rescheduleAppointmentSelect');
            const rescheduleSelectedAppointmentInfo = document.getElementById('rescheduleSelectedAppointmentInfo');
            if (rescheduleAppointmentSelect) rescheduleAppointmentSelect.value = '';
            if (rescheduleSelectedAppointmentInfo) rescheduleSelectedAppointmentInfo.style.display = 'none';

            clearTimeSlotSelection('rescheduleTimeSlots', 'rescheduleTime', 'rescheduleTimeSelected');
            refreshTimeSlotAvailability('reschedule');
            updateTimeAvailability();

            // Show success modal
            const successMessage = data.message || 'Your reschedule request has been submitted successfully!';
            document.getElementById('successModalMessage').textContent = successMessage;
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Reload page when success modal is closed
            document.getElementById('successModal').addEventListener('hidden.bs.modal', function() {
                window.location.reload();
            }, { once: true });
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

// Set current patient ID for filtering
window.currentPatientId = {{ auth()->id() }};

// Debug: Log raw data from server BEFORE processing
console.log('=== BLADE TEMPLATE: Raw Data from Server ===');
console.log('Raw appointmentsData type:', typeof appointmentsData, 'length:', Array.isArray(appointmentsData) ? appointmentsData.length : Object.keys(appointmentsData || {}).length);
console.log('Raw allAppointmentsData type:', typeof allAppointmentsData, 'length:', Array.isArray(allAppointmentsData) ? allAppointmentsData.length : Object.keys(allAppointmentsData || {}).length);
console.log('Server-side count - appointments:', {{ count($appointments ?? []) }}, 'allAppointments:', {{ count($allAppointments ?? []) }});

// Ensure arrays are properly formatted (convert objects to arrays if needed)
window.patientAppointments = Array.isArray(appointmentsData) ? appointmentsData : Object.values(appointmentsData || []);
window.allAppointments = Array.isArray(allAppointmentsData) ? allAppointmentsData : Object.values(allAppointmentsData || []);
window.blockedTimes = Array.isArray(blockedTimesData) ? blockedTimesData : Object.values(blockedTimesData || []);

// Debug: Verify data is loaded AFTER processing
console.log('=== BLADE TEMPLATE: Processed Data ===');
console.log('window.patientAppointments:', window.patientAppointments ? window.patientAppointments.length : 0, 'appointments');
console.log('window.allAppointments:', window.allAppointments ? window.allAppointments.length : 0, 'appointments');
console.log('window.blockedTimes:', window.blockedTimes ? window.blockedTimes.length : 0, 'blocked times');

if (window.patientAppointments && window.patientAppointments.length > 0) {
    console.log('First 3 patient appointments:', window.patientAppointments.slice(0, 3));
}
if (window.allAppointments && window.allAppointments.length > 0) {
    console.log('First 5 all appointments:', window.allAppointments.slice(0, 5).map(function(a) {
        return {
            id: a.id,
            patient_id: a.patient_id,
            is_own_appointment: a.is_own_appointment,
            patient_name: a.patient_name,
            service: a.service ? a.service.service_name : null,
            start_datetime: a.start_datetime
        };
    }));
    console.log('All appointment dates from allAppointments:', window.allAppointments.map(function(a) {
        return a.start_datetime ? a.start_datetime.split(' ')[0] : 'no date';
    }));
} else {
    console.error('⚠️ CRITICAL: window.allAppointments is empty or undefined!');
    console.error('This means other patients appointments will NOT be displayed!');
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
                // Ensure an option exists for this appointment; if missing (e.g., Cancelled), create it on the fly
                let opt = rescheduleAppointmentSelect.querySelector(`option[value="${appointmentId}"]`);
                if (!opt) {
                    const apt = (window.patientAppointments || []).find(function(a){ return String(a.id) === String(appointmentId); });
                    if (apt) {
                        const start = new Date(apt.start_datetime);
                        const dateStr = start.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                        const timeStr = start.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                        const serviceName = (apt.service && apt.service.service_name) ? apt.service.service_name : (apt.reason_for_visit || 'Appointment');
                        opt = document.createElement('option');
                        opt.value = appointmentId;
                        opt.textContent = `${serviceName} - ${dateStr} at ${timeStr}`;
                        opt.setAttribute('data-service', serviceName);
                        opt.setAttribute('data-date', start.toISOString().split('T')[0]);
                        opt.setAttribute('data-time', String(start.getHours()).padStart(2,'0') + ':' + String(start.getMinutes()).padStart(2,'0'));
                        opt.setAttribute('data-datetime', start.toLocaleString('en-US', { month: 'long', day: '2-digit', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }));
                        if (apt.service_id) opt.setAttribute('data-service-id', apt.service_id);
                        if (apt.duration_minutes) opt.setAttribute('data-duration', apt.duration_minutes);
                        if (apt.reason_for_visit) opt.setAttribute('data-reason-for-visit', apt.reason_for_visit);
                        opt.setAttribute('data-status', apt.status || 'Cancelled');
                        // Prepend so user can see it at top
                        rescheduleAppointmentSelect.insertBefore(opt, rescheduleAppointmentSelect.firstChild);
                    }
                }
                if (opt) {
                    rescheduleAppointmentSelect.value = appointmentId;
                    // Trigger change event to show appointment info
                    rescheduleAppointmentSelect.dispatchEvent(new Event('change'));
                }
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

        // isTimeSlotAvailable moved to global scope above

        // Function to show available/unavailable message
        // updateTimeAvailability moved to global scope above

        // Add event listeners for emergency form
        const emergencyDateInput = document.getElementById('emergencyDate');
        const emergencyTimeInput = document.getElementById('emergencyTime');
        if (emergencyTimeInput) {
            emergencyTimeInput.addEventListener('change', updateTimeAvailability);
        }

        // Add event listeners for reschedule form
        const rescheduleDateInput = document.getElementById('rescheduleDate');
        const rescheduleTimeInput = document.getElementById('rescheduleTime');
        if (rescheduleTimeInput) {
            rescheduleTimeInput.addEventListener('change', updateTimeAvailability);
        }

        // Add listener for emergency service selection changes to re-check availability
        const emergencyServiceSelect = document.getElementById('emergencyServiceSelect');
        if (emergencyServiceSelect) {
            emergencyServiceSelect.addEventListener('change', function() {
                refreshTimeSlotAvailability('emergency');
                updateTimeAvailability();
            });
        }

        // Add listener for reschedule appointment selection changes to re-check availability
        const rescheduleAppointmentSelect = document.getElementById('rescheduleAppointmentSelect');
        if (rescheduleAppointmentSelect) {
            rescheduleAppointmentSelect.addEventListener('change', function() {
                refreshTimeSlotAvailability('reschedule');
                updateTimeAvailability();
            });
        }

        // Show suggested available times when date is selected (emergency)
        if (emergencyDateInput) {
            emergencyDateInput.addEventListener('change', function() {
                refreshTimeSlotAvailability('emergency');
                updateTimeAvailability();

                const selectedDate = this.value;
                const suggestionId = 'emergencyTimeSuggestionMessage';
                let suggestionDiv = document.getElementById(suggestionId);
                if (suggestionDiv) {
                    suggestionDiv.remove();
                }

                if (!selectedDate) {
                    return;
                }

                const dateObj = new Date(selectedDate + 'T00:00:00');
                const availableSlots = [];
                const businessHours = [
                    '11:00', '11:15', '11:30', '11:45',
                    '12:00', '12:15', '12:30', '12:45',
                    '13:00', '13:15', '13:30', '13:45',
                    '14:00', '14:15', '14:30', '14:45',
                    '15:00', '15:15', '15:30', '15:45',
                    '16:00', '16:15', '16:30', '16:45',
                    '17:00', '17:15', '17:30', '17:45',
                    '18:00'
                ];

                for (const time of businessHours) {
                    if (isTimeSlotAvailable(dateObj, time)) {
                        availableSlots.push(time);
                    }
                }

                const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
                suggestionDiv = document.createElement('div');
                suggestionDiv.id = suggestionId;
                suggestionDiv.style.marginTop = '0.5rem';
                suggestionDiv.style.padding = '0.75rem';
                suggestionDiv.style.borderRadius = '8px';
                suggestionDiv.style.fontSize = '0.85rem';

                if (availableSlots.length > 0) {
                    if (isDarkMode) {
                        suggestionDiv.style.background = 'linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.1) 100%)';
                        suggestionDiv.style.color = '#93c5fd';
                        suggestionDiv.style.border = '2px solid #3b82f6';
                    } else {
                        suggestionDiv.style.background = 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)';
                        suggestionDiv.style.color = '#1e40af';
                        suggestionDiv.style.border = '2px solid #3b82f6';
                    }
                    suggestionDiv.innerHTML = `<i class="bi bi-info-circle-fill"></i> <strong>${availableSlots.length}</strong> time slots available. Tap a slot to select.`;
                } else {
                    if (isDarkMode) {
                        suggestionDiv.style.background = 'linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%)';
                        suggestionDiv.style.color = '#fde68a';
                        suggestionDiv.style.border = '2px solid #f59e0b';
                    } else {
                        suggestionDiv.style.background = 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)';
                        suggestionDiv.style.color = '#92400e';
                        suggestionDiv.style.border = '2px solid #f59e0b';
                    }
                    suggestionDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> No available time slots on this date. Please choose a different date.';
                }

                const dateFormGroup = this.closest('.form-group');
                if (dateFormGroup) {
                    dateFormGroup.appendChild(suggestionDiv);
                }
            });
        }

        // Show suggested available times when date is selected (reschedule)
        if (rescheduleDateInput) {
            rescheduleDateInput.addEventListener('change', function() {
                refreshTimeSlotAvailability('reschedule');
                updateTimeAvailability();

                const selectedDate = this.value;
                const suggestionId = 'rescheduleTimeSuggestionMessage';
                let suggestionDiv = document.getElementById(suggestionId);
                if (suggestionDiv) {
                    suggestionDiv.remove();
                }

                if (!selectedDate) {
                    return;
                }

                const dateObj = new Date(selectedDate + 'T00:00:00');
                const availableSlots = [];
                const businessHours = [
                    '11:00', '11:15', '11:30', '11:45',
                    '12:00', '12:15', '12:30', '12:45',
                    '13:00', '13:15', '13:30', '13:45',
                    '14:00', '14:15', '14:30', '14:45',
                    '15:00', '15:15', '15:30', '15:45',
                    '16:00', '16:15', '16:30', '16:45',
                    '17:00', '17:15', '17:30', '17:45',
                    '18:00'
                ];

                for (const time of businessHours) {
                    if (isTimeSlotAvailable(dateObj, time)) {
                        availableSlots.push(time);
                    }
                }

                const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
                suggestionDiv = document.createElement('div');
                suggestionDiv.id = suggestionId;
                suggestionDiv.style.marginTop = '0.5rem';
                suggestionDiv.style.padding = '0.75rem';
                suggestionDiv.style.borderRadius = '8px';
                suggestionDiv.style.fontSize = '0.85rem';

                if (availableSlots.length > 0) {
                    if (isDarkMode) {
                        suggestionDiv.style.background = 'linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.1) 100%)';
                        suggestionDiv.style.color = '#93c5fd';
                        suggestionDiv.style.border = '2px solid #3b82f6';
                    } else {
                        suggestionDiv.style.background = 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)';
                        suggestionDiv.style.color = '#1e40af';
                        suggestionDiv.style.border = '2px solid #3b82f6';
                    }
                    suggestionDiv.innerHTML = `<i class="bi bi-info-circle-fill"></i> <strong>${availableSlots.length}</strong> time slots available. Choose one to request.`;
                } else {
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
                }

                const dateFormGroup = this.closest('.form-group');
                if (dateFormGroup) {
                    dateFormGroup.appendChild(suggestionDiv);
                }
            });
        }

        initializeTimeSlotPicker('emergencyTimeSlots', 'emergencyTime', 'emergencyTimeSelected', 'emergency');
        initializeTimeSlotPicker('rescheduleTimeSlots', 'rescheduleTime', 'rescheduleTimeSelected', 'reschedule');
        updateTimeAvailability();
});

// ============================================
// CUSTOM DATE PICKER CALENDAR WIDGET
// ============================================

// Helper: check if a given Date has a full-day clinic closure (00:00–23:59) in window.blockedTimes
function isFullDayClosed(dateObj) {
    try {
        const y = dateObj.getFullYear();
        const m = String(dateObj.getMonth() + 1).padStart(2, '0');
        const d = String(dateObj.getDate()).padStart(2, '0');
        const dateStr = `${y}-${m}-${d}`;
        const list = Array.isArray(window.blockedTimes) ? window.blockedTimes : [];
        return list.some(function(bt) {
            if (!bt || !bt.start_datetime || !bt.end_datetime) return false;
            // Expect 'Y-m-d H:i:s' strings
            return bt.start_datetime.startsWith(dateStr) &&
                   bt.end_datetime.startsWith(dateStr) &&
                   bt.start_datetime.slice(11, 16) === '00:00' &&
                   bt.end_datetime.slice(11, 16) === '23:59';
        });
    } catch (_) {
        return false;
    }
}

// Helper: determine if an entire day is effectively fully booked (no available start time)
// for the currently selected service duration within clinic hours, considering appointments
// (excluding cancelled) and blocked times.
function isDayFullyBookedForDuration(dateObj, durationMinutes) {
    try {
        if (!durationMinutes || durationMinutes <= 0) return false;
        const year = dateObj.getFullYear();
        const month = dateObj.getMonth();
        const day = dateObj.getDate();
        const clinicOpenHour = 11;
        const clinicCloseHour = 18;

        // Build merged list of busy intervals for this day:
        const busyIntervals = [];

        // Appointments (exclude cancelled)
        const appointments = Array.isArray(window.allAppointments) ? window.allAppointments : [];
        for (const apt of appointments) {
            if (!apt || !apt.start_datetime || !apt.end_datetime) continue;
            const statusLower = (apt.status || '').toString().toLowerCase();
            if (statusLower === 'cancelled') continue;
            const s = parseLocalDateTime(apt.start_datetime);
            const e = parseLocalDateTime(apt.end_datetime);
            if (!s || !e) continue;
            if (s.getFullYear() === year && s.getMonth() === month && s.getDate() === day) {
                busyIntervals.push([s, e]);
            }
        }

        // Blocked times
        const blocked = Array.isArray(window.blockedTimes) ? window.blockedTimes : [];
        for (const bt of blocked) {
            if (!bt || !bt.start_datetime || !bt.end_datetime) continue;
            const s = parseLocalDateTime(bt.start_datetime);
            const e = parseLocalDateTime(bt.end_datetime);
            if (!s || !e) continue;
            if (s.getFullYear() === year && s.getMonth() === month && s.getDate() === day) {
                busyIntervals.push([s, e]);
            }
        }

        // If no busy intervals, day is not fully booked
        if (busyIntervals.length === 0) return false;

        // Try each potential start time in 15-min increments
        for (let hour = clinicOpenHour; hour < clinicCloseHour; hour++) {
            for (let minute = 0; minute < 60; minute += 15) {
                const start = new Date(year, month, day, hour, minute, 0);
                const end = new Date(start.getTime() + durationMinutes * 60000);
                // End cannot go past closing
                if (end.getHours() > clinicCloseHour || (end.getHours() === clinicCloseHour && end.getMinutes() > 0)) {
                    continue;
                }
                // Inclusive overlap: treat boundary touching as conflict
                const overlaps = busyIntervals.some(([bs, be]) => start <= be && end >= bs);
                if (!overlaps) {
                    // Found at least one free slot
                    return false;
                }
            }
        }
        // No free slots found
        return true;
    } catch (_) {
        return false;
    }
}

// Helper: can at least one start time on the day fit the given duration?
function canDayFitDuration(dateObj, durationMinutes) {
    return !isDayFullyBookedForDuration(dateObj, durationMinutes);
}

// Helper: compute maximum continuous free minutes starting at a given date/time
function getMaxContinuousFreeMinutes(startDateTime) {
    try {
        const clinicOpenHour = 11;
        const clinicCloseHour = 18;

        const year = startDateTime.getFullYear();
        const month = startDateTime.getMonth();
        const day = startDateTime.getDate();

        // Closing boundary at 18:00 of same day
        const closingBoundary = new Date(year, month, day, clinicCloseHour, 0, 0);
        if (startDateTime >= closingBoundary) return 0;

        // Collect busy intervals for the same day (appointments excl. cancelled + blocked)
        const busyIntervals = [];
        const appointments = Array.isArray(window.allAppointments) ? window.allAppointments : [];
        for (const apt of appointments) {
            if (!apt || !apt.start_datetime || !apt.end_datetime) continue;
            const statusLower = (apt.status || '').toString().toLowerCase();
            if (statusLower === 'cancelled') continue;
            const s = parseLocalDateTime(apt.start_datetime);
            const e = parseLocalDateTime(apt.end_datetime);
            if (!s || !e) continue;
            if (s.getFullYear() === year && s.getMonth() === month && s.getDate() === day) {
                busyIntervals.push([s, e]);
            }
        }
        const blocked = Array.isArray(window.blockedTimes) ? window.blockedTimes : [];
        for (const bt of blocked) {
            if (!bt || !bt.start_datetime || !bt.end_datetime) continue;
            const s = parseLocalDateTime(bt.start_datetime);
            const e = parseLocalDateTime(bt.end_datetime);
            if (!s || !e) continue;
            if (s.getFullYear() === year && s.getMonth() === month && s.getDate() === day) {
                busyIntervals.push([s, e]);
            }
        }

        // Find the earliest busy interval that overlaps or starts after the requested start
        let earliestConflictStart = closingBoundary;
        for (const [bs, be] of busyIntervals) {
            if (startDateTime < be && be > startDateTime) {
                // If busy starts at/after startDateTime and earlier than current earliest
                if (bs >= startDateTime && bs < earliestConflictStart) {
                    earliestConflictStart = bs;
                }
                // If currently inside a busy interval, there's zero availability
                if (bs <= startDateTime && be > startDateTime) {
                    return 0;
                }
            } else if (bs > startDateTime && bs < earliestConflictStart) {
                earliestConflictStart = bs;
            }
        }

        const freeMs = Math.max(0, earliestConflictStart.getTime() - startDateTime.getTime());
        return Math.floor(freeMs / 60000);
    } catch (_) {
        return 0;
    }
}

// Calendar widget class
class CustomDatePicker {
    constructor(containerId, inputId, monthYearId, prevBtnId, nextBtnId, minDate = null) {
        this.container = document.getElementById(containerId);
        this.input = document.getElementById(inputId);
        this.monthYearElement = document.getElementById(monthYearId);
        this.prevBtn = document.getElementById(prevBtnId);
        this.nextBtn = document.getElementById(nextBtnId);
        this.minDate = minDate;
        this.currentDate = new Date();
        this.selectedDate = null;
        
        if (this.minDate) {
            const min = new Date(this.minDate);
            if (min > this.currentDate) {
                this.currentDate = new Date(min);
            }
        }
        
        this.init();
    }
    
    init() {
        this.render();
        this.attachEvents();
    }
    
    attachEvents() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.navigateMonth(-1));
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.navigateMonth(1));
        }
    }
    
    navigateMonth(direction) {
        this.currentDate.setMonth(this.currentDate.getMonth() + direction);
        this.render();
    }
    
    formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    isDateDisabled(date) {
        if (this.minDate) {
            const min = new Date(this.minDate);
            min.setHours(0, 0, 0, 0);
            const checkDate = new Date(date);
            checkDate.setHours(0, 0, 0, 0);
            if (checkDate < min) {
                return true;
            }
        }
        
        // Check if date is in the past
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const checkDate = new Date(date);
        checkDate.setHours(0, 0, 0, 0);
        if (checkDate < today) {
            return true;
        }
        
        return false;
    }
    
    selectDate(date) {
        if (this.isDateDisabled(date)) {
            return;
        }
        
        this.selectedDate = new Date(date);
        this.input.value = this.formatDate(this.selectedDate);
        this.render();
        
        // Trigger change event for form validation
        this.input.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    render() {
        if (!this.container) return;
        
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        // Update month/year display
        if (this.monthYearElement) {
            this.monthYearElement.textContent = new Date(year, month).toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });
        }
        
        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        
        // Clear grid
        this.container.innerHTML = '';
        
        const todayMidnight = new Date();
        todayMidnight.setHours(0, 0, 0, 0);

        // Add days from previous month
        for (let i = firstDay - 1; i >= 0; i--) {
            const day = daysInPrevMonth - i;
            const date = new Date(year, month - 1, day);
            const isPast = date < todayMidnight;
            const dayElement = this.createDayElement(date, true, true, false, false, false, false, isPast);
            this.container.appendChild(dayElement);
        }
        
        // Add days from current month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateMidnight = new Date(date);
            dateMidnight.setHours(0, 0, 0, 0);
            const isPastDate = dateMidnight < todayMidnight;
            const isClosed = isFullDayClosed(date);
            const baseDisabled = this.isDateDisabled(date);
            let isFullyBooked = false;
            let isDisabled = baseDisabled || isClosed;

            if (!isPastDate && !baseDisabled && !isClosed) {
                const durationForCheck = typeof getRequestDuration === 'function' ? getRequestDuration() : 0;
                if (durationForCheck > 0) {
                    isFullyBooked = isDayFullyBookedForDuration(date, durationForCheck);
                    if (isFullyBooked) {
                        isDisabled = true;
                    }
                }
            }

            const isSelected = this.selectedDate && 
                date.getDate() === this.selectedDate.getDate() &&
                date.getMonth() === this.selectedDate.getMonth() &&
                date.getFullYear() === this.selectedDate.getFullYear();
            const isToday = date.toDateString() === new Date().toDateString();
            
            const dayElement = this.createDayElement(
                date,
                false,
                isDisabled,
                isSelected,
                isToday,
                isClosed,
                isFullyBooked,
                isPastDate
            );
            this.container.appendChild(dayElement);
        }
        
        // Fill remaining cells to complete the grid (next month days)
        const totalCells = this.container.children.length;
        const remainingCells = 42 - totalCells; // 6 rows * 7 days
        for (let day = 1; day <= remainingCells; day++) {
            const date = new Date(year, month + 1, day);
            const isPast = date < todayMidnight;
            const dayElement = this.createDayElement(date, true, true, false, false, false, false, isPast);
            this.container.appendChild(dayElement);
        }
    }
    
    createDayElement(
        date,
        isInactive,
        isDisabled,
        isSelected = false,
        isToday = false,
        isClosed = false,
        isFullyBooked = false,
        isPastDate = false
    ) {
        const dayElement = document.createElement('div');
        dayElement.className = 'custom-calendar-day';
        dayElement.innerHTML = `<span class="date-number">${date.getDate()}</span>`;
        
        if (isInactive) {
            dayElement.classList.add('inactive');
            dayElement.classList.add('disabled');
        } else if (isDisabled) {
            dayElement.classList.add('disabled');
        } else {
            dayElement.classList.add('active');
            dayElement.addEventListener('click', () => this.selectDate(date));
        }
        
        // Visual indicators for closed/fully booked days
        if (!isInactive) {
            if (isClosed) {
                dayElement.classList.add('closed-day');
                dayElement.classList.add('disabled');
                dayElement.classList.remove('active');
                dayElement.style.pointerEvents = 'none';
                dayElement.setAttribute('title', 'Clinic is closed for the entire day');

                const label = document.createElement('span');
                label.className = 'day-status-label closed';
                label.textContent = 'Closed';
                dayElement.appendChild(label);
            } else if (isFullyBooked) {
                dayElement.classList.add('fully-booked-day');
                dayElement.classList.add('disabled');
                dayElement.classList.remove('active');
                dayElement.style.pointerEvents = 'none';
                dayElement.setAttribute('title', 'Fully booked for the selected service duration');

                const label = document.createElement('span');
                label.className = 'day-status-label full';
                label.textContent = 'Full';
                dayElement.appendChild(label);
            } else if (isPastDate) {
                dayElement.classList.add('disabled');
                dayElement.classList.remove('active');
                dayElement.style.pointerEvents = 'none';
            }
        }
        
        if (isSelected) {
            dayElement.classList.add('selected');
        }
        
        if (isToday && !isInactive) {
            dayElement.classList.add('today');
        }
        
        return dayElement;
    }
}

// Store calendar instances globally
let emergencyCalendar = null;
let rescheduleCalendar = null;
let bookCalendar = null;

// Initialize calendars when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    
    // Initialize Emergency Calendar
    if (document.getElementById('emergencyCalendarGrid')) {
        emergencyCalendar = new CustomDatePicker(
            'emergencyCalendarGrid',
            'emergencyDate',
            'emergencyMonthYear',
            'emergencyPrevMonth',
            'emergencyNextMonth',
            today
        );
    }
    
    // Initialize Reschedule Calendar
    if (document.getElementById('rescheduleCalendarGrid')) {
        rescheduleCalendar = new CustomDatePicker(
            'rescheduleCalendarGrid',
            'rescheduleDate',
            'rescheduleMonthYear',
            'reschedulePrevMonth',
            'rescheduleNextMonth',
            today
        );
    }
    
    // Initialize Book Calendar
    if (document.getElementById('bookCalendarGrid')) {
        bookCalendar = new CustomDatePicker(
            'bookCalendarGrid',
            'bookDate',
            'bookMonthYear',
            'bookPrevMonth',
            'bookNextMonth',
            today
        );
    }
    
    // Re-render calendars when modal is shown
    const appointmentModal = document.getElementById('appointmentRequestModal');
    if (appointmentModal) {
        appointmentModal.addEventListener('shown.bs.modal', function() {
            if (emergencyCalendar) emergencyCalendar.render();
            if (rescheduleCalendar) rescheduleCalendar.render();
            if (bookCalendar) bookCalendar.render();
        });
    }
    
    // Trigger date change events for existing validation
    const emergencyDateInput = document.getElementById('emergencyDate');
    const rescheduleDateInput = document.getElementById('rescheduleDate');
    const bookDateInput = document.getElementById('bookDate');
    
    if (emergencyDateInput) {
        emergencyDateInput.addEventListener('change', function() {
            // Trigger existing validation if available
            if (typeof updateTimeAvailability === 'function') {
                updateTimeAvailability();
            }
        });
    }
    
    if (rescheduleDateInput) {
        rescheduleDateInput.addEventListener('change', function() {
            // Trigger existing validation if available
            if (typeof updateTimeAvailability === 'function') {
                updateTimeAvailability();
            }
        });
    }
    
    if (bookDateInput) {
        bookDateInput.addEventListener('change', function() {
            // Book form doesn't need time validation
        });
    }

    // Re-render calendar days when service selection changes (duration-dependent availability)
    const bookServiceSelectEl = document.getElementById('bookServiceSelect');
    if (bookServiceSelectEl) {
        bookServiceSelectEl.addEventListener('change', function() {
            if (bookCalendar) bookCalendar.render();
        });
    }

    // Prevent booking a date that cannot fit the selected duration at all
    const bookForm = document.getElementById('bookForm');
    if (bookForm) {
        bookForm.addEventListener('submit', function(e) {
            const dateInput = document.getElementById('bookDate');
            const selectedDate = dateInput ? dateInput.value : '';
            const duration = typeof getRequestDuration === 'function' ? getRequestDuration() : 0;
            if (selectedDate && duration > 0) {
                const dateObj = new Date(selectedDate + 'T00:00:00');
                if (!canDayFitDuration(dateObj, duration)) {
                    e.preventDefault();
                    const hours = Math.floor(duration / 60);
                    const mins = duration % 60;
                    const needText = `${hours > 0 ? hours + ' hr' + (hours > 1 ? 's' : '') : ''}${hours > 0 && mins > 0 ? ' ' : ''}${mins > 0 ? mins + ' min' : ''}`.trim() || 'selected duration';
                    alert(`No continuous window available on this date for ${needText}. Please choose a different date.`);
                    return false;
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const layout = document.querySelector('.calendar-layout');
    const sidebar = document.querySelector('.calendar-sidebar');
    const resizer = document.getElementById('calendarResizer');

    if (!layout || !sidebar || !resizer) return;

    const storageKey = 'patientCalendarSidebarWidth';
    const minWidth = 220;
    const getMaxWidth = () => Math.min(480, window.innerWidth - 360);

    const clampWidth = (width) => Math.max(minWidth, Math.min(getMaxWidth(), width));
    const applyWidth = (width) => {
        layout.style.setProperty('--calendar-sidebar-width', `${width}px`);
    };

    const restoreWidth = () => {
        const saved = Number(localStorage.getItem(storageKey));
        if (saved && window.innerWidth > 1024) {
            applyWidth(clampWidth(saved));
        } else if (window.innerWidth <= 1024) {
            layout.style.removeProperty('--calendar-sidebar-width');
        }
    };

    restoreWidth();

    let pointerId = null;
    let startX = 0;
    let startWidth = 0;
    let resizing = false;

    const handlePointerMove = (event) => {
        if (!resizing) return;
        const delta = event.clientX - startX;
        const newWidth = clampWidth(startWidth + delta);
        applyWidth(newWidth);
        localStorage.setItem(storageKey, String(newWidth));
    };

    const stopResize = (event) => {
        if (!resizing) return;
        resizing = false;
        layout.classList.remove('is-resizing');
        document.body.style.cursor = '';
        if (pointerId !== null) {
            try {
                resizer.releasePointerCapture(pointerId);
            } catch (e) {}
        }
        resizer.removeEventListener('pointermove', handlePointerMove);
        resizer.removeEventListener('pointerup', stopResize);
        resizer.removeEventListener('pointercancel', stopResize);
        pointerId = null;
    };

    resizer.addEventListener('pointerdown', function(event) {
        if (window.innerWidth <= 1024) return;
        event.preventDefault();
        pointerId = event.pointerId;
        startX = event.clientX;
        startWidth = sidebar.getBoundingClientRect().width;
        resizing = true;
        layout.classList.add('is-resizing');
        document.body.style.cursor = 'col-resize';
        try {
            resizer.setPointerCapture(pointerId);
        } catch (e) {}
        resizer.addEventListener('pointermove', handlePointerMove);
        resizer.addEventListener('pointerup', stopResize);
        resizer.addEventListener('pointercancel', stopResize);
    });

    resizer.addEventListener('dblclick', function() {
        layout.style.removeProperty('--calendar-sidebar-width');
        localStorage.removeItem(storageKey);
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth <= 1024) {
            layout.style.removeProperty('--calendar-sidebar-width');
        } else {
            restoreWidth();
        }
    });
});
</script>
<script src="{{ asset('js/patient-calendar.js') }}?v={{ time() }}"></script>
<!-- Day Appointments Modal -->
<div class="modal fade" id="dayAppointmentsModal" tabindex="-1" aria-labelledby="dayAppointmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); border: none;">
                <h5 class="modal-title text-white" id="dayAppointmentsModalLabel">
                    <i class="bi bi-calendar-event me-2"></i>Day Appointments
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be dynamically inserted here -->
            </div>
        </div>
    </div>
</div>

<style>
/* Day Appointments Modal Styles */
.day-appointments-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.day-appointments-header .modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

/* Modal Fully Booked Indicator */
.modal-fully-booked-indicator {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.9;
    }
}

.modal-fully-booked-indicator i {
    font-size: 1rem;
}

.day-appointments-list {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

/* Desktop Styles (default - 1025px and above) */
@media (min-width: 1025px) {
    #dayAppointmentsModal .modal-dialog {
        max-width: 800px;
    }
    
    .day-appointments-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
    }
    
    .day-appointments-header .modal-title {
        font-size: 1.5rem;
    }
    
    .day-appointments-list {
        max-height: 500px;
    }
    
    .day-appointment-item {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .appointment-time {
        font-size: 0.9rem;
    }
    
    .appointment-title {
        font-size: 1.05rem;
    }
    
    .appointment-notes {
        font-size: 0.85rem;
    }
}

/* Tablet Styles (768px - 1024px) */
@media (min-width: 768px) and (max-width: 1024px) {
    #dayAppointmentsModal .modal-dialog {
        max-width: 90%;
    }
    
    .day-appointments-header {
        margin-bottom: 1.25rem;
        padding-bottom: 0.875rem;
    }
    
    .day-appointments-header .modal-title {
        font-size: 1.35rem;
    }
    
    .day-appointments-list {
        max-height: 450px;
    }
    
    .day-appointment-item {
        padding: 0.875rem;
        margin-bottom: 0.875rem;
        min-height: 60px;
    }
    
    .appointment-time {
        font-size: 0.875rem;
    }
    
    .appointment-title {
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .appointment-notes {
        font-size: 0.825rem;
        line-height: 1.5;
    }
}

/* Blocked Time Details Modal Styles */
.blocked-time-modal {
    border-radius: 16px;
    overflow: hidden;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 8px 24px rgba(0, 0, 0, 0.1);
}

.blocked-time-modal-header {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border: none;
    padding: 1.25rem 1.5rem;
}

.blocked-time-modal-title {
    color: white;
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
}

.blocked-time-modal-close {
    filter: brightness(0) invert(1);
    opacity: 0.9;
}

.blocked-time-modal-close:hover {
    opacity: 1;
}

.blocked-time-modal-body {
    padding: 2rem 1.5rem;
    background: white;
}

.blocked-time-modal-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
}

.blocked-time-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
}

.blocked-time-icon-circle {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.2);
    border: 3px solid #ef4444;
}

.blocked-time-icon {
    font-size: 3rem;
    color: #ef4444;
}

.blocked-time-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    text-align: center;
}

.blocked-time-details {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 0.5rem;
}

.blocked-time-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.blocked-time-detail-item:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    transform: translateX(2px);
}

.blocked-time-detail-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
}

.blocked-time-detail-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 0;
}

.blocked-time-detail-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.blocked-time-detail-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
    word-wrap: break-word;
    overflow-wrap: break-word;
    line-height: 1.5;
}

/* Dark Mode Styles for Blocked Time Details Modal */
[data-theme="dark"] .blocked-time-modal {
    background: var(--dm-card-bg, #1e293b) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 8px 24px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .blocked-time-modal-header {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
}

[data-theme="dark"] .blocked-time-modal-body {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .blocked-time-icon-circle {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.15) 100%) !important;
    border-color: #ef4444 !important;
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3) !important;
}

[data-theme="dark"] .blocked-time-icon {
    color: white !important;
}

[data-theme="dark"] .blocked-time-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .blocked-time-detail-item {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] .blocked-time-detail-item:hover {
    background: var(--dm-bg-primary, #0f172a) !important;
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .blocked-time-detail-icon {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3) !important;
}

[data-theme="dark"] .blocked-time-detail-label {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .blocked-time-detail-value {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Responsive Styles for Blocked Time Details Modal */
@media (max-width: 768px) {
    /* Blocked time modal dialog - mobile sizing */
    .blocked-time-modal {
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }
    
    .blocked-time-modal .modal-dialog {
        width: calc(100% - 1.5rem);
        max-width: calc(100% - 1.5rem);
        margin: 0.75rem;
        box-sizing: border-box;
        max-height: 90vh;
    }
    
    .blocked-time-modal-header {
        padding: 0.6rem 0.75rem !important;
        flex-shrink: 0;
    }
    
    .blocked-time-modal-title {
        font-size: 0.9rem !important;
    }
    
    .blocked-time-modal-body {
        padding: 0.5rem 0.5rem !important;
        max-height: calc(90vh - 100px);
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1 1 auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .blocked-time-modal-content {
        gap: 0.5rem;
    }
    
    .blocked-time-icon-wrapper {
        margin-bottom: 0.15rem;
    }
    
    .blocked-time-icon-circle {
        width: 50px !important;
        height: 50px !important;
        border-width: 2px;
    }
    
    .blocked-time-icon {
        font-size: 1.75rem !important;
    }
    
    .blocked-time-title {
        font-size: 0.9rem !important;
        margin-bottom: 0.35rem;
        line-height: 1.2;
    }
    
    .blocked-time-details {
        gap: 0.4rem;
        margin-top: 0.15rem;
    }
    
    .blocked-time-detail-item {
        padding: 0.5rem !important;
        gap: 0.5rem !important;
        border-radius: 6px;
        margin-bottom: 0.25rem;
    }
    
    .blocked-time-detail-icon {
        width: 28px !important;
        height: 28px !important;
        font-size: 0.8rem !important;
        border-radius: 6px;
        flex-shrink: 0;
    }
    
    .blocked-time-detail-label {
        font-size: 0.6rem !important;
        letter-spacing: 0.2px;
        margin-bottom: 0.15rem;
    }
    
    .blocked-time-detail-value {
        font-size: 0.75rem !important;
        line-height: 1.3;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
}

@media (max-width: 480px) {
    .blocked-time-modal {
        max-height: 88vh;
    }
    
    .blocked-time-modal .modal-dialog {
        width: calc(100% - 1rem);
        max-width: calc(100% - 1rem);
        margin: 0.5rem;
        max-height: 88vh;
    }
    
    .blocked-time-modal-header {
        padding: 0.5rem 0.625rem !important;
    }
    
    .blocked-time-modal-title {
        font-size: 0.85rem !important;
    }
    
    .blocked-time-modal-body {
        padding: 0.4rem 0.4rem !important;
        max-height: calc(88vh - 90px);
    }
    
    .blocked-time-modal-content {
        gap: 0.4rem;
    }
    
    .blocked-time-icon-wrapper {
        margin-bottom: 0.1rem;
    }
    
    .blocked-time-icon-circle {
        width: 45px !important;
        height: 45px !important;
        border-width: 2px;
    }
    
    .blocked-time-icon {
        font-size: 1.5rem !important;
    }
    
    .blocked-time-title {
        font-size: 0.85rem !important;
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }
    
    .blocked-time-details {
        gap: 0.35rem;
        margin-top: 0.1rem;
    }
    
    .blocked-time-detail-item {
        padding: 0.4rem !important;
        gap: 0.4rem !important;
        margin-bottom: 0.2rem;
    }
    
    .blocked-time-detail-icon {
        width: 26px !important;
        height: 26px !important;
        font-size: 0.75rem !important;
    }
    
    .blocked-time-detail-label {
        font-size: 0.55rem !important;
        margin-bottom: 0.1rem;
    }
    
    .blocked-time-detail-value {
        font-size: 0.7rem !important;
        line-height: 1.25;
    }
}

/* Ultra-small screens for blocked time modal */
@media (max-width: 320px) {
    .blocked-time-modal {
        max-height: 86vh;
    }
    
    .blocked-time-modal .modal-dialog {
        width: calc(100% - 0.75rem);
        max-width: calc(100% - 0.75rem);
        margin: 0.375rem;
        max-height: 86vh;
    }
    
    .blocked-time-modal-header {
        padding: 0.45rem 0.5rem !important;
    }
    
    .blocked-time-modal-title {
        font-size: 0.8rem !important;
    }
    
    .blocked-time-modal-body {
        padding: 0.35rem 0.3rem !important;
        max-height: calc(86vh - 80px);
    }
    
    .blocked-time-modal-content {
        gap: 0.3rem;
    }
    
    .blocked-time-icon-circle {
        width: 40px !important;
        height: 40px !important;
        border-width: 1.5px;
    }
    
    .blocked-time-icon {
        font-size: 1.25rem !important;
    }
    
    .blocked-time-title {
        font-size: 0.8rem !important;
        margin-bottom: 0.2rem;
    }
    
    .blocked-time-details {
        gap: 0.3rem;
        margin-top: 0.05rem;
    }
    
    .blocked-time-detail-item {
        padding: 0.35rem !important;
        gap: 0.35rem !important;
        margin-bottom: 0.15rem;
    }
    
    .blocked-time-detail-icon {
        width: 24px !important;
        height: 24px !important;
        font-size: 0.7rem !important;
    }
    
    .blocked-time-detail-label {
        font-size: 0.5rem !important;
        margin-bottom: 0.08rem;
    }
    
    .blocked-time-detail-value {
        font-size: 0.65rem !important;
        line-height: 1.2;
    }
}

.day-appointment-item {
    padding: 0.875rem;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    border-left: 3px solid;
    transition: all 0.2s ease;
    cursor: pointer;
    width: 100%;
    box-sizing: border-box;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.day-appointment-item:hover {
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.day-appointment-item.clickable-appointment:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
    background: rgba(33, 150, 243, 0.05);
}

.day-appointment-item.clickable-appointment {
    transition: all 0.2s ease;
}

.day-appointment-item.clickable-blocked {
    transition: all 0.2s ease;
    cursor: pointer;
}

.day-appointment-item.clickable-blocked:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    background: #fee2e2;
    opacity: 1;
}

.day-appointment-item.pending {
    background: #fef3c7;
    border-left-color: #fbbf24;
}

.day-appointment-item.confirmed {
    background: #dbeafe;
    border-left-color: #3b82f6;
}

.day-appointment-item.completed {
    background: #d1fae5;
    border-left-color: #10b981;
}

.day-appointment-item.cancelled {
    background: #fef3c7;
    border-left-color: #92400e;
    opacity: 0.8;
}

.day-appointment-item.blocked {
    background: #fee2e2;
    border-left-color: #ef4444;
}

.day-appointment-item.missed {
    background: #e5e7eb;
    border-left-color: #6b7280;
}

.day-appointment-item.booked {
    background: #f3e8ff;
    border-left-color: #9333ea;
}

.appointment-time {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.appointment-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.appointment-notes {
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 0.375rem;
    padding-top: 0.375rem;
    border-top: 1px solid rgba(0,0,0,0.1);
}

/* Fully Booked Indicator */
.fully-booked-indicator {
    position: absolute;
    top: 0.375rem;
    right: 0.375rem;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 25;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
    max-width: calc(100% - 3.5rem);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    pointer-events: auto;
    line-height: 1;
}

.calendar-day.fully-booked {
    position: relative;
}

.calendar-day.fully-booked::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(239, 68, 68, 0.05);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 4px;
    pointer-events: none;
    z-index: 0;
}

/* Ensure day number and fully booked indicator don't overlap */
.calendar-day.fully-booked .day-number {
    max-width: calc(100% - 8rem);
    z-index: 3;
    position: absolute;
    top: 0.375rem;
    left: 0.375rem;
}

/* Ensure event items don't overlap with fully booked indicator */
.calendar-day.fully-booked .day-events {
    padding-top: 0;
    margin-top: 2.5rem;
    overflow-y: auto;
    overflow-x: hidden;
}

/* Ensure first event item has proper spacing when fully booked indicator is present */
.calendar-day.fully-booked .day-events .event-item:first-child {
    margin-top: 0;
}

/* Ensure event items on fully-booked days look the same as other days */
.calendar-day.fully-booked .event-item {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    display: block;
    margin-bottom: 0;
    white-space: normal;
    word-wrap: break-word;
    overflow: hidden;
}

.calendar-day.fully-booked .event-item.booked {
    background: #f3e8ff;
    border-left: 2px solid #9333ea;
    color: #6b21a8;
    padding: 0.4rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    cursor: pointer;
    opacity: 1;
    min-height: 2.5rem;
    display: block;
    width: 100%;
}

/* Ensure "X more" indicator is visible on fully booked days */
.calendar-day.fully-booked .event-more-indicator {
    margin-top: 0.5rem;
    position: relative;
    z-index: 2;
    flex-shrink: 0;
}

/* Event Count Badges */
.event-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    font-size: 0.7rem;
    font-weight: 700;
    color: white;
    margin: 0.125rem;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    z-index: 1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    flex-shrink: 0;
}

.event-count-badge:hover {
    transform: scale(1.1);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
}

.event-count-badge.booked {
    background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);
}

.event-count-badge.pending {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}

.event-count-badge.confirmed {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.event-count-badge.completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.event-count-badge.cancelled {
    background: linear-gradient(135deg, #92400e 0%, #78350f 100%);
}

.event-count-badge.blocked {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.event-count-badge.missed {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

.badge-number {
    font-size: 0.7rem;
    font-weight: 700;
    line-height: 1;
}

/* Ensure event count badges don't overlap with fully booked indicator */
.calendar-day.fully-booked .event-count-badge {
    margin-top: 0.5rem;
    margin-right: 0.25rem;
    position: relative;
    z-index: 1;
}

/* Position event count badges below day number, avoiding fully booked indicator - consolidated with above rule */

/* "X more" Indicator */
.event-more-indicator {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(25, 118, 210, 0.05) 100%);
    border: 1px dashed #2196F3;
    border-radius: 6px;
    padding: 0.4rem 0.5rem;
    margin-top: 0.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
    font-size: 0.7rem;
}

.event-more-indicator:hover {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.15) 0%, rgba(25, 118, 210, 0.1) 100%);
    border-color: #1976D2;
    transform: translateY(-1px);
}

.event-more-indicator .more-text {
    font-size: 0.7rem;
    font-weight: 600;
    color: #2196F3;
}

/* Calendar Day Clickable */
.calendar-day {
    transition: all 0.2s ease;
}

.calendar-day[data-day-appointments]:not([data-day-appointments="[]"]):hover,
.calendar-day[data-day-blocked]:not([data-day-blocked="[]"]):hover {
    background: rgba(33, 150, 243, 0.03);
    cursor: pointer;
}

/* Dark Mode Styles */
[data-theme="dark"] .day-appointments-header {
    border-bottom-color: #334155;
}

[data-theme="dark"] .day-appointments-header .modal-title {
    color: #f1f5f9;
}

[data-theme="dark"] .day-appointment-item.pending {
    background: rgba(251, 191, 36, 0.15);
    border-left-color: #fbbf24;
}

[data-theme="dark"] .day-appointment-item.confirmed {
    background: rgba(59, 130, 246, 0.15);
    border-left-color: #3b82f6;
}

[data-theme="dark"] .day-appointment-item.completed {
    background: rgba(16, 185, 129, 0.15);
    border-left-color: #10b981;
}

[data-theme="dark"] .day-appointment-item.cancelled {
    background: rgba(146, 64, 14, 0.15);
    border-left-color: #a16207;
}

[data-theme="dark"] .day-appointment-item.blocked {
    background: rgba(239, 68, 68, 0.15);
    border-left-color: #ef4444;
}

[data-theme="dark"] .day-appointment-item.clickable-blocked:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    background: rgba(239, 68, 68, 0.25);
    opacity: 1;
}

[data-theme="dark"] .day-appointment-item.missed {
    background: rgba(107, 114, 128, 0.15);
    border-left-color: #6b7280;
    opacity: 0.7;
}

[data-theme="dark"] .day-appointment-item.booked {
    background: rgba(147, 51, 234, 0.5);
    border-left-color: #9333ea;
}

[data-theme="dark"] .appointment-time {
    color: #94a3b8;
}

[data-theme="dark"] .appointment-title {
    color: #f1f5f9;
}

[data-theme="dark"] .appointment-notes {
    color: #94a3b8;
    border-top-color: #334155;
}

[data-theme="dark"] .event-more-indicator {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
    border-color: #3b82f6;
}

[data-theme="dark"] .event-more-indicator:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.1) 100%);
    border-color: #2563eb;
}

[data-theme="dark"] .event-more-indicator .more-text {
    color: #60a5fa;
}

[data-theme="dark"] .calendar-day.fully-booked::after {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
}

[data-theme="dark"] .modal-fully-booked-indicator {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.4);
}

/* Mobile Styles (max-width: 768px) */
@media (max-width: 768px) {
    #dayAppointmentsModal .modal-dialog {
        max-width: calc(100% - 1.5rem);
        width: calc(100% - 1.5rem);
        margin: 0.75rem;
        box-sizing: border-box;
    }
    
    #dayAppointmentsModal .modal-content {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        margin: 0;
    }
    
    #dayAppointmentsModal .modal-header {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    
    #dayAppointmentsModal .modal-body {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .day-appointments-header {
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
    }
    
    .day-appointments-header .modal-title {
        font-size: 1.1rem;
        margin-bottom: 0.375rem;
    }
    
    .day-appointments-list {
        max-height: calc(92vh - 200px);
        padding-right: 0.25rem;
    }
    
    .day-appointment-item {
        padding: 0.75rem;
        margin-bottom: 0.625rem;
        border-left-width: 3px;
        min-height: 50px;
    }
    
    .appointment-time {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
        gap: 0.375rem;
    }
    
    .appointment-title {
        font-size: 0.9rem;
        margin-bottom: 0.2rem;
        line-height: 1.4;
    }
    
    .appointment-notes {
        font-size: 0.75rem;
        margin-top: 0.25rem;
        padding-top: 0.25rem;
    }
    
    .modal-fully-booked-indicator {
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .modal-fully-booked-indicator i {
        font-size: 0.9rem;
    }
}

/* Small Mobile Styles (max-width: 480px) */
@media (max-width: 480px) {
    #dayAppointmentsModal .modal-dialog {
        max-width: calc(100% - 1rem);
        width: calc(100% - 1rem);
        margin: 0.5rem;
        box-sizing: border-box;
    }
    
    #dayAppointmentsModal .modal-content {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        margin: 0;
    }
    
    #dayAppointmentsModal .modal-header {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    
    #dayAppointmentsModal .modal-body {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .day-appointments-header {
        margin-bottom: 0.875rem;
        padding-bottom: 0.625rem;
    }
    
    .day-appointments-header .modal-title {
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }
    
    .day-appointments-list {
        max-height: calc(94vh - 180px);
        padding-right: 0.125rem;
    }
    
    .day-appointment-item {
        padding: 0.625rem;
        margin-bottom: 0.5rem;
        border-left-width: 2.5px;
        min-height: 45px;
    }
    
    .appointment-time {
        font-size: 0.75rem;
        margin-bottom: 0.2rem;
        gap: 0.25rem;
    }
    
    .appointment-title {
        font-size: 0.85rem;
        margin-bottom: 0.15rem;
        line-height: 1.35;
    }
    
    .appointment-notes {
        font-size: 0.7rem;
        margin-top: 0.2rem;
        padding-top: 0.2rem;
    }
    
    .modal-fully-booked-indicator {
        padding: 0.35rem 0.625rem;
        font-size: 0.75rem;
        gap: 0.375rem;
    }
    
    .modal-fully-booked-indicator i {
        font-size: 0.85rem;
    }
}

/* Ultra-small Mobile Styles (max-width: 320px) */
@media (max-width: 320px) {
    #dayAppointmentsModal .modal-dialog {
        max-width: calc(100% - 0.75rem);
        width: calc(100% - 0.75rem);
        margin: 0.375rem;
        box-sizing: border-box;
    }
    
    #dayAppointmentsModal .modal-content {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        margin: 0;
    }
    
    #dayAppointmentsModal .modal-header {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    
    #dayAppointmentsModal .modal-body {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .day-appointments-header {
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
    }
    
    .day-appointments-header .modal-title {
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
    }
    
    .day-appointments-list {
        max-height: calc(96vh - 160px);
    }
    
    .day-appointment-item {
        padding: 0.5rem;
        margin-bottom: 0.4rem;
        border-left-width: 2px;
        min-height: 40px;
    }
    
    .appointment-time {
        font-size: 0.7rem;
        margin-bottom: 0.15rem;
    }
    
    .appointment-title {
        font-size: 0.8rem;
        margin-bottom: 0.1rem;
        line-height: 1.3;
    }
    
    .appointment-notes {
        font-size: 0.65rem;
        margin-top: 0.15rem;
        padding-top: 0.15rem;
    }
    
    .modal-fully-booked-indicator {
        padding: 0.3rem 0.5rem;
        font-size: 0.7rem;
        gap: 0.25rem;
    }
    
    .modal-fully-booked-indicator i {
        font-size: 0.8rem;
    }
}
</style>

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

<script>
// Style adjustments for Blocked Time display in the MAIN patient calendar
// - "Clinic Closed" → ALL CAPS, bold, maroon
// - "Blocked Off Time - Specific Time" → make the time maroon
// Scoped to the main calendar grid only (not the day-of appointments or mini pickers)
document.addEventListener('DOMContentLoaded', function() {
    // Position tab tooltips using viewport coordinates so they aren't clipped by sidebar overflow
    document.querySelectorAll('.tab-btn[data-tooltip]').forEach(function(btn) {
        let bodyTip = null;
        const updateBodyTip = () => {
            if (!bodyTip) return;
            const rect = btn.getBoundingClientRect();
            const left = rect.left + rect.width / 2;
            const top = rect.bottom + 8;
            bodyTip.style.left = (left) + 'px';
            bodyTip.style.top = (top) + 'px';
            bodyTip.style.transform = 'translateX(-50%)';
        };
        btn.addEventListener('mouseenter', function() {
            // prefer body-mounted tooltip to avoid any clipping/z-index issues
            btn.setAttribute('data-no-tt', '1'); // disable pseudo tooltip
            bodyTip = document.createElement('div');
            bodyTip.className = 'calendar-body-tooltip';
            bodyTip.textContent = btn.getAttribute('data-tooltip') || '';
            document.body.appendChild(bodyTip);
            updateBodyTip();
        });
        btn.addEventListener('mousemove', updateBodyTip);
        btn.addEventListener('mouseleave', function() {
            btn.removeAttribute('data-no-tt');
            if (bodyTip && bodyTip.parentNode) {
                bodyTip.parentNode.removeChild(bodyTip);
            }
            bodyTip = null;
        });
    });

    function getAllGrids() {
        return Array.from(document.querySelectorAll('.calendar-layout .calendar-grid'));
    }

    function styleBlockedInMainCalendar() {
        const grids = getAllGrids();
        if (!grids.length) return;

        const blockedItems = grids.flatMap(grid => Array.from(grid.querySelectorAll('.event-item.blocked')));
        if (!blockedItems.length) return; // Early return if no blocked items
        
        // Count items that need processing
        const itemsToProcess = blockedItems.filter(item => item.dataset.blockStyled !== '1');
        if (!itemsToProcess.length) return; // All items already styled, no work needed
        
        itemsToProcess.forEach(function(item) {
            if (item.dataset.blockStyled === '1') return; // avoid rework loops
            const titleEl = item.querySelector('.event-title');
            const timeEl = item.querySelector('.event-time');
            const dayEl = item.closest('.calendar-day');

            if (titleEl) {
                const titleText = titleEl.textContent.trim().toLowerCase();
                // Treat as clinic closed if title OR notes mention it
                const notesEl = item.querySelector('.event-notes');
                const isClinicClosed = titleText.includes('clinic closed') || (notesEl && notesEl.textContent.toLowerCase().includes('clinic closed'));

                if (isClinicClosed) {
                    // Title: all caps, bold, maroon
                    titleEl.textContent = titleEl.textContent.toUpperCase();
                    titleEl.style.fontWeight = '800';
                    titleEl.style.color = '#800000';
                    // If time is present for any reason, also make it maroon
                    if (timeEl) timeEl.style.color = '#800000';
                    item.dataset.blockStyled = '1';
                } else {
                    // Specific-time block: make the time maroon
                    if (timeEl) timeEl.style.color = '#800000';
                    
                    // Also display end time like "Already Booked" (e.g., 12:00 PM - 12:30 PM)
                    try {
                        if (!timeEl || !dayEl) return;

                        // 1) Parse period header to get month/year currently displayed
                        const periodEl = document.getElementById('currentPeriodDisplay');
                        const periodText = periodEl ? periodEl.textContent.trim() : '';
                        // Expected like "November 2025" (fallbacks handled)
                        const monthMap = {
                            january: 0, february: 1, march: 2, april: 3, may: 4, june: 5,
                            july: 6, august: 7, september: 8, october: 9, november: 10, december: 11
                        };
                        let currentMonth = null;
                        let currentYear = null;
                        if (periodText) {
                            const m = periodText.match(/^([A-Za-z]+)\s+(\d{4})$/);
                            if (m) {
                                const monthName = m[1].toLowerCase();
                                if (monthMap.hasOwnProperty(monthName)) {
                                    currentMonth = monthMap[monthName];
                                    currentYear = parseInt(m[2], 10);
                                }
                            }
                        }

                        // 2) Determine the day number from the cell
                        const dayNumEl = dayEl.querySelector('.day-number');
                        const dayNum = dayNumEl ? parseInt(dayNumEl.textContent.trim(), 10) : NaN;
                        if (Number.isNaN(dayNum)) return;

                        // 3) Build a Date for the displayed start time on that day
                        const displayed = (timeEl.textContent || '').trim();
                        if (!displayed || / - /.test(displayed)) return; // already expanded or empty

                        // "12:00 PM" -> minutes from midnight
                        const parseDisplayTimeToMinutes = (str) => {
                            const m = str.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
                            if (!m) return null;
                            let h = parseInt(m[1], 10);
                            const min = parseInt(m[2], 10);
                            const ap = m[3].toUpperCase();
                            if (ap === 'PM' && h !== 12) h += 12;
                            if (ap === 'AM' && h === 12) h = 0;
                            return h * 60 + min;
                        };
                        const startMinutes = parseDisplayTimeToMinutes(displayed);
                        if (startMinutes === null) return;

                        // 4) Find the matching blocked time from window.blockedTimes
                        const blockedList = Array.isArray(window.blockedTimes) ? window.blockedTimes : [];
                        if (!blockedList.length || currentMonth === null || currentYear === null) return;

                        const pad2 = (n) => String(n).padStart(2, '0');
                        // Build yyyy-mm-dd string for quick date equality checks in local time
                        const candidateDateStr = `${currentYear}-${pad2(currentMonth + 1)}-${pad2(dayNum)}`;

                        // Robust local parser to avoid timezone issues
                        const toLocal = (str) => {
                            if (!str) return new Date(NaN);
                            // Accept "YYYY-MM-DD HH:MM:SS" or "YYYY-MM-DDTHH:MM:SS"
                            const m = String(str).match(/^(\d{4})-(\d{2})-(\d{2})[T\s](\d{2}):(\d{2})(?::(\d{2}))?/);
                            if (!m) return new Date(str); // fallback to native
                            const y = parseInt(m[1], 10);
                            const mo = parseInt(m[2], 10) - 1;
                            const d = parseInt(m[3], 10);
                            const h = parseInt(m[4], 10);
                            const mi = parseInt(m[5], 10);
                            const s = m[6] ? parseInt(m[6], 10) : 0;
                            return new Date(y, mo, d, h, mi, s);
                        };
                        const sameYMD = (d, y, m, day) => d.getFullYear() === y && d.getMonth() === m && d.getDate() === day;

                        // Prefer exact start-minute match; otherwise match if displayed time lies within the block range
                        const candidates = blockedList.filter((bt) => {
                            if (!bt || !bt.start_datetime || !bt.end_datetime) return false;
                            const s = toLocal(bt.start_datetime);
                            const e = toLocal(bt.end_datetime);
                            if (!(s instanceof Date) || isNaN(s) || !(e instanceof Date) || isNaN(e)) return false;
                            return sameYMD(s, currentYear, currentMonth, dayNum);
                        });
                        let match = null;
                        if (candidates.length) {
                            match = candidates.find((bt) => {
                                const s = toLocal(bt.start_datetime);
                                const sMin = s.getHours() * 60 + s.getMinutes();
                                return sMin === startMinutes;
                            }) || candidates.find((bt) => {
                                const s = toLocal(bt.start_datetime);
                                const e = toLocal(bt.end_datetime);
                                const sMin = s.getHours() * 60 + s.getMinutes();
                                const eMin = e.getHours() * 60 + e.getMinutes();
                                return startMinutes >= sMin && startMinutes < eMin;
                            }) || null;
                        }

                        if (match) {
                            const s = toLocal(match.start_datetime);
                            const e = toLocal(match.end_datetime);
                            if (s instanceof Date && !isNaN(s) && e instanceof Date && !isNaN(e)) {
                                const fmt = (d) => {
                                    let h = d.getHours();
                                    const m = d.getMinutes();
                                    const ap = h >= 12 ? 'PM' : 'AM';
                                    h = h % 12;
                                    h = h ? h : 12;
                                    return `${h}:${pad2(m)} ${ap}`;
                                };
                                timeEl.textContent = `${fmt(s)} - ${fmt(e)}`;
                            }
                        } else {
                            // Fallback: default to a 60-minute window to mirror common block durations
                            const addMinutes = (minutes) => {
                                const base = new Date(currentYear, currentMonth, dayNum, Math.floor(startMinutes / 60), startMinutes % 60, 0);
                                base.setMinutes(base.getMinutes() + minutes);
                                return base;
                            };
                            const fmt = (d) => {
                                let h = d.getHours();
                                const m = d.getMinutes();
                                const ap = h >= 12 ? 'PM' : 'AM';
                                h = h % 12;
                                h = h ? h : 12;
                                return `${h}:${pad2(m)} ${ap}`;
                            };
                            const startDate = addMinutes(0);
                            const endDate = addMinutes(60);
                            if (!isNaN(startDate) && !isNaN(endDate)) {
                                timeEl.textContent = `${fmt(startDate)} - ${fmt(endDate)}`;
                            }
                        }
                    } catch (_) {
                        // no-op on failure
                    }
                }
            } else if (timeEl) {
                // Fallback: if only time exists, make it maroon
                timeEl.style.color = '#800000';
            }
            item.dataset.blockStyled = '1';
        });
    }

    // Initial styling will be triggered by the global debounced formatter below

    // NEW: Ensure ALL appointments show a time range (Pending, Confirmed, Completed, Cancelled, Missed)
    function ensureAllEventRanges() {
        const grids = getAllGrids();
        if (!grids.length) return;

        const periodEl = document.getElementById('currentPeriodDisplay');
        const periodText = periodEl ? periodEl.textContent.trim() : '';
        const monthMap = {
            january: 0, february: 1, march: 2, april: 3, may: 4, june: 5,
            july: 6, august: 7, september: 8, october: 9, november: 10, december: 11
        };
        function parsePeriodToRange(text) {
            if (!text) return null;
            // Case 1: "April 2025"
            let m = text.match(/^([A-Za-z]+)\s+(\d{4})$/);
            if (m) {
                const monthName = m[1].toLowerCase();
                if (monthMap.hasOwnProperty(monthName)) {
                    const y = parseInt(m[2], 10);
                    const mo = monthMap[monthName];
                    const start = new Date(y, mo, 1, 0, 0, 0);
                    const end = new Date(y, mo + 1, 0, 23, 59, 59);
                    return { start, end, year: y, month: mo };
                }
            }
            // Case 2: "Nov 9 - Nov 15, 2025" or "November 9 - 15, 2025"
            m = text.match(/^([A-Za-z]+)\s+(\d{1,2})\s*-\s*(?:([A-Za-z]+)\s*)?(\d{1,2}),\s*(\d{4})$/);
            if (m) {
                const m1Name = m[1].toLowerCase();
                const m2Name = (m[3] ? m[3] : m[1]).toLowerCase();
                if (monthMap.hasOwnProperty(m1Name) && monthMap.hasOwnProperty(m2Name)) {
                    const y = parseInt(m[5], 10);
                    const mo1 = monthMap[m1Name];
                    const mo2 = monthMap[m2Name];
                    const d1 = parseInt(m[2], 10);
                    const d2 = parseInt(m[4], 10);
                    const start = new Date(y, mo1, d1, 0, 0, 0);
                    const end = new Date(y, mo2, d2, 23, 59, 59);
                    return { start, end, year: y, month: mo1, range: true };
                }
            }
            // Case 3: "Tuesday, November 11, 2025" (Day view)
            m = text.match(/^(?:[A-Za-z]+,\s*)?([A-Za-z]+)\s+(\d{1,2}),\s*(\d{4})$/);
            if (m) {
                const monthName = m[1].toLowerCase();
                if (monthMap.hasOwnProperty(monthName)) {
                    const y = parseInt(m[3], 10);
                    const mo = monthMap[monthName];
                    const d = parseInt(m[2], 10);
                    const start = new Date(y, mo, d, 0, 0, 0);
                    const end = new Date(y, mo, d, 23, 59, 59);
                    return { start, end, year: y, month: mo, day: d };
                }
            }
            return null;
        }
        const periodInfo = parsePeriodToRange(periodText);
        if (!periodInfo) return;
        const currentYear = periodInfo.year;
        const currentMonth = periodInfo.month;

        const toLocal = (str) => {
            if (!str) return new Date(NaN);
            const m = String(str).match(/^(\d{4})-(\d{2})-(\d{2})[T\s](\d{2}):(\d{2})(?::(\d{2}))?/);
            if (!m) return new Date(str);
            const y = parseInt(m[1], 10);
            const mo = parseInt(m[2], 10) - 1;
            const d = parseInt(m[3], 10);
            const h = parseInt(m[4], 10);
            const mi = parseInt(m[5], 10);
            const s = m[6] ? parseInt(m[6], 10) : 0;
            return new Date(y, mo, d, h, mi, s);
        };
        const pad2 = (n) => String(n).padStart(2, '0');
        const sameYMD = (d, y, m, day) => d.getFullYear() === y && d.getMonth() === m && d.getDate() === day;
        const parseDisplayTimeToMinutes = (str) => {
            const m = String(str || '').trim().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
            if (!m) return null;
            let h = parseInt(m[1], 10);
            const min = parseInt(m[2], 10);
            const ap = m[3].toUpperCase();
            if (ap === 'PM' && h !== 12) h += 12;
            if (ap === 'AM' && h === 12) h = 0;
            return h * 60 + min;
        };
        const fmt = (d) => {
            let h = d.getHours();
            const m = d.getMinutes();
            const ap = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12;
            return `${h}:${pad2(m)} ${ap}`;
        };

        const allAppointments = Array.isArray(window.allAppointments) ? window.allAppointments : [];
        if (!allAppointments.length) return; // Early return if no appointments data

        const allItems = grids.flatMap(grid => Array.from(grid.querySelectorAll('.event-item')));
        if (!allItems.length) return; // Early return if no event items
        
        // Filter items that need processing (exclude blocked, items without time, or already formatted)
        const itemsToProcess = allItems.filter(item => {
            if (item.classList.contains('blocked')) return false;
            const timeEl = item.querySelector('.event-time') || item.querySelector('.appointment-time');
            if (!timeEl) return false;
            const current = (timeEl.textContent || '').trim();
            if (!current || / - /.test(current)) return false; // already a range or empty
            return true;
        });
        
        if (!itemsToProcess.length) return; // All items already formatted, no work needed
        
        // 1) Month/Week grid items
        itemsToProcess.forEach(function(item) {
            const timeEl = item.querySelector('.event-time') || item.querySelector('.appointment-time');
            if (!timeEl) return;
            const current = (timeEl.textContent || '').trim();
            if (!current || / - /.test(current)) return; // already a range or empty

            const dayEl = item.closest('.calendar-day');
            const dayNumEl = dayEl ? dayEl.querySelector('.day-number') : null;
            const dayNum = dayNumEl ? parseInt(dayNumEl.textContent.trim(), 10) : NaN;

            const startMinutes = parseDisplayTimeToMinutes(current);
            if (startMinutes === null) return;

            // Find matching appointment
            const candidates = allAppointments.filter((apt) => {
                const s = toLocal(apt.start_datetime);
                if (!(s instanceof Date) || isNaN(s)) return false;
                if (!Number.isNaN(dayNum)) {
                    return sameYMD(s, currentYear, currentMonth, dayNum);
                }
                // Week/Day view: use period range containment
                return periodInfo.start <= s && s <= periodInfo.end;
            });
            let match = candidates.find((apt) => {
                const s = toLocal(apt.start_datetime);
                const sMin = s.getHours() * 60 + s.getMinutes();
                return sMin === startMinutes;
            }) || candidates.find((apt) => {
                const s = toLocal(apt.start_datetime);
                const e = toLocal(apt.end_datetime);
                if (!(s instanceof Date) || isNaN(s) || !(e instanceof Date) || isNaN(e)) return false;
                const sMin = s.getHours() * 60 + s.getMinutes();
                const eMin = e.getHours() * 60 + e.getMinutes();
                return startMinutes >= sMin && startMinutes < eMin;
            }) || null;

            if (match) {
                const s = toLocal(match.start_datetime);
                const e = toLocal(match.end_datetime);
                if (s instanceof Date && !isNaN(s) && e instanceof Date && !isNaN(e)) {
                    timeEl.textContent = `${fmt(s)} - ${fmt(e)}`;
                    return;
                }
                // If end not parsable, try duration
                const dur = parseInt(match.duration_minutes, 10);
                if (!isNaN(dur) && dur > 0) {
                    const base = !Number.isNaN(dayNum)
                        ? new Date(currentYear, currentMonth, dayNum, Math.floor(startMinutes / 60), startMinutes % 60, 0)
                        : new Date(s.getFullYear(), s.getMonth(), s.getDate(), Math.floor(startMinutes / 60), startMinutes % 60, 0);
                    const sDate = base;
                    const eDate = new Date(base);
                    eDate.setMinutes(eDate.getMinutes() + dur);
                    timeEl.textContent = `${fmt(sDate)} - ${fmt(eDate)}`;
                    return;
                }
            }

            // Fallback 60 minutes if no data match
            const sDate = !Number.isNaN(dayNum)
                ? new Date(currentYear, currentMonth, dayNum, Math.floor(startMinutes / 60), startMinutes % 60, 0)
                : (function() {
                    // Use start of period for date base when day number is unavailable
                    const base = periodInfo.start;
                    return new Date(base.getFullYear(), base.getMonth(), base.getDate(), Math.floor(startMinutes / 60), startMinutes % 60, 0);
                })();
            const eDate = new Date(sDate);
            eDate.setMinutes(eDate.getMinutes() + 60);
            timeEl.textContent = `${fmt(sDate)} - ${fmt(eDate)}`;
        });

        // 2) Day view cards (outside grid) - ensure their times show ranges too
        const dayCards = Array.from(document.querySelectorAll('.day-appointment-item'));
        dayCards.forEach(function(card) {
            const timeEl = card.querySelector('.appointment-time');
            if (!timeEl) return;
            const current = (timeEl.textContent || '').trim();
            if (!current || / - /.test(current)) return;

            const startMinutes = parseDisplayTimeToMinutes(current);
            if (startMinutes === null) return;

            const candidates = allAppointments.filter((apt) => {
                const s = toLocal(apt.start_datetime);
                return s instanceof Date && !isNaN(s) && periodInfo.start <= s && s <= periodInfo.end;
            });

            let match = candidates.find((apt) => {
                const s = toLocal(apt.start_datetime);
                const sMin = s.getHours() * 60 + s.getMinutes();
                return sMin === startMinutes;
            }) || candidates.find((apt) => {
                const s = toLocal(apt.start_datetime);
                const e = toLocal(apt.end_datetime);
                if (!(s instanceof Date) || isNaN(s) || !(e instanceof Date) || isNaN(e)) return false;
                const sMin = s.getHours() * 60 + s.getMinutes();
                const eMin = e.getHours() * 60 + e.getMinutes();
                return startMinutes >= sMin && startMinutes < eMin;
            }) || null;

            const baseDate = periodInfo.start;
            if (match) {
                const s = toLocal(match.start_datetime);
                const e = toLocal(match.end_datetime);
                if (s instanceof Date && !isNaN(s) && e instanceof Date && !isNaN(e)) {
                    timeEl.textContent = `${fmt(s)} - ${fmt(e)}`;
                    return;
                }
                const dur = parseInt(match.duration_minutes, 10);
                if (!isNaN(dur) && dur > 0) {
                    const sDate = new Date(baseDate.getFullYear(), baseDate.getMonth(), baseDate.getDate(), Math.floor(startMinutes / 60), startMinutes % 60, 0);
                    const eDate = new Date(sDate);
                    eDate.setMinutes(eDate.getMinutes() + dur);
                    timeEl.textContent = `${fmt(sDate)} - ${fmt(eDate)}`;
                    return;
                }
            }
            const sDate = new Date(baseDate.getFullYear(), baseDate.getMonth(), baseDate.getDate(), Math.floor(startMinutes / 60), startMinutes % 60, 0);
            const eDate = new Date(sDate);
            eDate.setMinutes(eDate.getMinutes() + 60);
            timeEl.textContent = `${fmt(sDate)} - ${fmt(eDate)}`;
        });
    }

    // Debounced formatter to avoid heavy loops
    function debounce(fn, delay) {
        let t = null;
        return function() {
            clearTimeout(t);
            t = setTimeout(fn, delay);
        };
    }
    // Track if formatters are currently running to prevent overlapping calls
    let formattersRunning = false;
    let lastFormatterRun = 0;
    const MIN_FORMATTER_INTERVAL = 500; // Minimum 500ms between runs

    const applyAllFormatters = debounce(function() {
        // Prevent overlapping runs
        const now = Date.now();
        if (formattersRunning || (now - lastFormatterRun < MIN_FORMATTER_INTERVAL)) {
            return;
        }
        
        formattersRunning = true;
        lastFormatterRun = now;
        
        try {
            styleBlockedInMainCalendar();
            ensureAllEventRanges();
        } finally {
            formattersRunning = false;
        }
    }, 200);

    // Initial passes - defer to improve initial load performance
    // Use requestIdleCallback if available, otherwise setTimeout
    if (window.requestIdleCallback) {
        requestIdleCallback(() => {
            applyAllFormatters();
        }, { timeout: 500 });
    } else {
        setTimeout(applyAllFormatters, 100);
    }
    
    // Also run on window load, but with debounce
    window.addEventListener('load', function() {
        if (window.requestIdleCallback) {
            requestIdleCallback(() => {
                applyAllFormatters();
            }, { timeout: 500 });
        } else {
            setTimeout(applyAllFormatters, 200);
        }
    }, { once: true });

    // Observe all current grids and rebind when switching views
    const observedGrids = new WeakSet();
    let observerInstances = new WeakMap();
    
    function bindGridObservers() {
        getAllGrids().forEach(grid => {
            if (observedGrids.has(grid)) return;
            
            // Disconnect any existing observer for this grid
            const existingObs = observerInstances.get(grid);
            if (existingObs) {
                existingObs.disconnect();
            }
            
            const obs = new MutationObserver((mutations) => {
                // Only trigger if there are actual meaningful changes
                const hasSignificantChanges = mutations.some(mutation => {
                    // Ignore attribute changes that don't affect rendering
                    if (mutation.type === 'attributes') {
                        const attr = mutation.attributeName;
                        // Ignore data attributes used for tracking
                        if (attr && (attr.startsWith('data-') && attr !== 'data-block-styled')) {
                            return false;
                        }
                    }
                    // Only care about actual DOM structure changes
                    return mutation.type === 'childList' || 
                           (mutation.type === 'characterData' && mutation.target.textContent.trim());
                });
                
                if (hasSignificantChanges) {
                    applyAllFormatters();
                }
            });
            
            obs.observe(grid, { 
                childList: true, 
                subtree: true, 
                characterData: true,
                attributes: true,
                attributeFilter: ['class', 'style'] // Only watch class/style changes
            });
            
            observedGrids.add(grid);
            observerInstances.set(grid, obs);
        });
    }
    bindGridObservers();

    // Watch the layout for grid additions/removals (view change)
    const layout = document.querySelector('.calendar-layout') || document.body;
    if (layout) {
        const layoutObs = new MutationObserver((mutations) => {
            // Only trigger on actual grid additions/removals
            const hasGridChanges = mutations.some(mutation => {
                if (mutation.type !== 'childList') return false;
                return Array.from(mutation.addedNodes).some(node => 
                    node.nodeType === 1 && (node.classList?.contains('calendar-grid') || node.querySelector?.('.calendar-grid'))
                ) || Array.from(mutation.removedNodes).some(node => 
                    node.nodeType === 1 && (node.classList?.contains('calendar-grid') || node.querySelector?.('.calendar-grid'))
                );
            });
            
            if (hasGridChanges) {
                bindGridObservers();
                applyAllFormatters();
            }
        });
        layoutObs.observe(layout, { childList: true, subtree: true });
    }

    // Removed setInterval - MutationObserver handles all updates efficiently
    // No periodic polling needed - only update when DOM actually changes
});
</script>

<script>
// Optimized loading overlay - hide when calendar sections are ready
(function() {
    const loadingOverlay = document.getElementById('calendar-loading-overlay');
    
    if (!loadingOverlay) return;
    
    function hideLoadingOverlay() {
        if (loadingOverlay && !loadingOverlay.classList.contains('hidden')) {
            loadingOverlay.classList.add('hidden');
            // Remove from DOM after animation completes
            setTimeout(() => {
                if (loadingOverlay.parentNode) {
                    loadingOverlay.parentNode.removeChild(loadingOverlay);
                }
            }, 300);
        }
    }
    
    // Check if calendar sections are ready
    function checkCalendarReady() {
        const calendarMain = document.querySelector('.calendar-main');
        const tabSection = document.querySelector('.tabbed-section');
        
        // If both sections exist and have content, hide overlay
        if (calendarMain && tabSection) {
            // Use requestAnimationFrame for smooth performance
            requestAnimationFrame(() => {
                hideLoadingOverlay();
            });
            return true;
        }
        return false;
    }
    
    // Try to hide immediately if DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Give a small delay for initial render
            setTimeout(() => {
                if (!checkCalendarReady()) {
                    // Fallback: hide after short delay even if sections not detected
                    setTimeout(hideLoadingOverlay, 200);
                }
            }, 50);
        });
    } else {
        // DOM already loaded
        setTimeout(() => {
            if (!checkCalendarReady()) {
                setTimeout(hideLoadingOverlay, 200);
            }
        }, 50);
    }
    
    // Also listen for window load as backup
    window.addEventListener('load', function() {
        setTimeout(() => {
            if (!loadingOverlay.classList.contains('hidden')) {
                hideLoadingOverlay();
            }
        }, 100);
    }, { once: true });
    
    // Fallback: Hide after maximum wait time (reduced from 10s to 3s for faster UX)
    setTimeout(hideLoadingOverlay, 3000);
})();
</script>

@endsection


