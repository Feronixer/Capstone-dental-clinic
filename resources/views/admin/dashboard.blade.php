@extends('layout.admin.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
    /* Enhanced Dashboard Styles */
    .dashboard-container {
        padding: 1.5rem;
        max-width: 100%;
    }

    /* Welcome Section Enhancements */
    .welcome-card {
        background: linear-gradient(135deg, #16a085 0%, #0e7862 100%);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(22, 160, 133, 0.25);
        overflow: hidden;
        position: relative;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Statistics Cards Enhancements */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15) !important;
    }

    .stat-card:hover::before {
        opacity: 0.6;
    }

    .stat-icon-wrapper {
        position: relative;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    /* List Item Enhancements */
    .appointment-item {
        transition: all 0.2s ease;
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid transparent;
        background-color: white !important;
    }

    /* Ensure proper contrast in light mode */
    .appointment-item h6 {
        color: #212529 !important;
    }
    .appointment-item small,
    .appointment-item .text-muted {
        color: #6c757d !important;
    }
    .appointment-item .rounded-circle.bg-primary.bg-opacity-10 i {
        color: #0d6efd !important; /* bootstrap primary */
    }

    .appointment-item:hover {
        background-color: #f8f9fa !important;
        border-left-color: #16a085;
        transform: translateX(4px);
    }

    /* Patient List Item Styles */
    .patient-list-item {
        background-color: white !important;
        color: #212529 !important;
    }

    .patient-avatar-circle {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .patient-avatar-icon {
        color: #212529 !important;
    }

    .patient-name {
        color: #212529 !important;
    }

    /* Card Header Improvements */
    .enhanced-card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }

    /* Empty State Enhancements */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        opacity: 0.2;
        margin-bottom: 1rem;
    }

    /* Responsive Improvements */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .stat-card {
            margin-bottom: 1rem;
        }

        .welcome-card .card-body {
            flex-direction: column;
            text-align: center;
        }

        .welcome-card .text-white[style*="font-size: 4rem"] {
            font-size: 3rem !important;
            margin-top: 1rem;
        }

        .calendar-nav {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .calendar-nav .btn {
            flex: 1;
            min-width: auto;
        }
    }

    @media (max-width: 576px) {
        .stat-card .card-body {
            padding: 0.75rem !important;
        }

        .stat-icon-wrapper {
            width: 45px !important;
            height: 45px !important;
        }

        .stat-icon-wrapper i {
            font-size: 1.25rem !important;
        }
    }

    /* Smooth Transitions */
    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn {
        transition: all 0.2s ease;
        border-radius: 8px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Scrollbar Styling */
    .card-body::-webkit-scrollbar {
        width: 8px;
    }

    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .card-body::-webkit-scrollbar-thumb {
        background: #16a085;
        border-radius: 4px;
    }

    .card-body::-webkit-scrollbar-thumb:hover {
        background: #0e7862;
    }

    /* Feedback Icon Centering */
    .feedback-icon-wrapper {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }

    .feedback-icon {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 auto !important;
        padding: 0 !important;
        vertical-align: middle !important;
        text-align: center !important;
        width: auto !important;
        height: auto !important;
    }

    .feedback-icon::before {
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
        margin: 0;
        padding: 0;
    }

    /* Feedback Section Spacing */
    .feedback-icon-wrapper {
        flex-shrink: 0;
        margin-right: 1rem !important;
    }

    .feedback-text-container {
        flex: 1;
        min-width: 0;
    }

    @media (max-width: 576px) {
        .feedback-icon-wrapper {
            margin-right: 0.75rem !important;
        }
    }

    /* Dark Mode Styles for Recent Patients and White Elements */
    [data-theme="dark"] .appointment-item {
        background-color: var(--dm-card-bg, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .appointment-item:hover {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        border-left-color: #16a085 !important;
    }

    [data-theme="dark"] .card.bg-white {
        background-color: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .card-header.bg-white,
    [data-theme="dark"] .enhanced-card-header.bg-white {
        background: var(--dm-card-bg, #1e293b) !important;
        border-bottom-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .card-body.bg-white {
        background-color: var(--dm-card-bg, #1e293b) !important;
    }

    [data-theme="dark"] .card-body.bg-white .appointment-item {
        background-color: var(--dm-card-bg, #1e293b) !important;
    }

    [data-theme="dark"] .list-group-item.bg-white,
    [data-theme="dark"] .appointment-item.bg-white {
        background-color: var(--dm-card-bg, #1e293b) !important;
    }

    [data-theme="dark"] .text-dark {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .text-dark i {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] h5.text-dark,
    [data-theme="dark"] h6.text-dark {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .rounded-circle.bg-light,
    [data-theme="dark"] .rounded-circle[style*="background-color: #f8f9fa"] {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        border-color: var(--dm-border-color, #475569) !important;
    }

    [data-theme="dark"] .rounded-circle .text-dark {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .empty-state-icon.text-dark {
        color: var(--dm-text-muted, #94a3b8) !important;
        opacity: 0.5 !important;
    }

    [data-theme="dark"] .empty-state-icon.text-primary {
        color: var(--dm-text-muted, #94a3b8) !important;
        opacity: 0.5 !important;
    }

    /* Dark Mode for Enhanced Card Header */
    [data-theme="dark"] .enhanced-card-header {
        background: var(--dm-card-bg, #1e293b) !important;
        border-bottom-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .enhanced-card-header h5,
    [data-theme="dark"] .enhanced-card-header .fw-bold {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .enhanced-card-header .text-dark {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* Dark Mode for List Group Items */
    [data-theme="dark"] .list-group-item {
        background-color: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .list-group-item .text-muted {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    /* Dark Mode - Patient List Item Styles */
    [data-theme="dark"] .patient-list-item {
        background-color: var(--dm-card-bg, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .patient-list-item:hover {
        background-color: var(--dm-bg-tertiary, #334155) !important;
    }

    [data-theme="dark"] .patient-avatar-circle {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        border-color: var(--dm-border-color, #475569) !important;
    }

    [data-theme="dark"] .patient-avatar-icon {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .patient-name {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* Dark Mode - Override inline styles for Recent Patients */
    [data-theme="dark"] .list-group-item.appointment-item.bg-white[style*="background-color: white"],
    [data-theme="dark"] .appointment-item.bg-white[style*="background-color: white"] {
        background-color: var(--dm-card-bg, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .rounded-circle[style*="background-color: #f8f9fa"],
    [data-theme="dark"] .rounded-circle.bg-light[style*="background-color: #f8f9fa"] {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        border-color: var(--dm-border-color, #475569) !important;
    }

    [data-theme="dark"] .rounded-circle .text-dark,
    [data-theme="dark"] .rounded-circle .bi-person-fill.text-dark {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* Dark Mode - Ensure all white backgrounds are overridden */
    [data-theme="dark"] [style*="background-color: white"],
    [data-theme="dark"] [style*="background-color: white !important"] {
        background-color: var(--dm-card-bg, #1e293b) !important;
    }

    /* Dark Mode - Override light backgrounds */
    [data-theme="dark"] [style*="background-color: #f8f9fa"],
    [data-theme="dark"] [style*="background-color: #f8f9fa !important"] {
        background-color: var(--dm-bg-tertiary, #334155) !important;
    }

    /* Dark Mode - Card container backgrounds */
    [data-theme="dark"] .card.bg-white[style*="background"],
    [data-theme="dark"] .card-header.bg-white[style*="background"],
    [data-theme="dark"] .card-body.bg-white[style*="background"] {
        background-color: var(--dm-card-bg, #1e293b) !important;
    }
</style>

<div class="container-fluid dashboard-container">
    <!-- Success Message -->
    @if(session('success'))
        <x-toast-message type="success" :message="session('success')" />
    @endif

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm welcome-card">
                <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-white mb-3 mb-md-0">
                        <h2 class="fw-bold mb-2 mb-md-3" style="font-size: clamp(1.5rem, 4vw, 2rem);">Good {{ now()->format('A') === 'AM' ? 'morning' : (now()->format('A') === 'PM' && now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0 fs-5" style="opacity: 0.95;">You have <strong>{{ $todayAppointments }}</strong> appointment{{ $todayAppointments !== 1 ? 's' : '' }} scheduled for today and <strong>{{ $totalPatients }}</strong> total patient{{ $totalPatients !== 1 ? 's' : '' }}.</p>
                    </div>
                    <div class="text-white" style="font-size: clamp(3rem, 8vw, 4rem); opacity: 0.3; flex-shrink: 0;">
                        <i class="bi bi-tooth"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <!-- Total Patients -->
        <div class="col-xl col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-top: 3px solid #16a085;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center stat-icon-wrapper"
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #16a085 0%, #0e7862 100%); box-shadow: 0 3px 10px rgba(22, 160, 133, 0.25);">
                                <i class="bi bi-people-fill text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Total Patients</p>
                            <h3 class="fw-bold mb-0" style="font-size: clamp(1.25rem, 2.5vw, 1.75rem); color: #16a085; line-height: 1.2;">{{ $totalPatients }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-xl col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-top: 3px solid #3498db;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center stat-icon-wrapper"
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #3498db 0%, #2574b8 100%); box-shadow: 0 3px 10px rgba(52, 152, 219, 0.25);">
                                <i class="bi bi-calendar-check-fill text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Today's Appointments</p>
                            <h3 class="fw-bold mb-0" style="font-size: clamp(1.25rem, 2.5vw, 1.75rem); color: #3498db; line-height: 1.2;">{{ $todayAppointments }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Appointments -->
        <div class="col-xl col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-top: 3px solid #e74c3c;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center stat-icon-wrapper"
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); box-shadow: 0 3px 10px rgba(231, 76, 60, 0.25);">
                                <i class="bi bi-calendar3 text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Total Appointments</p>
                            <h3 class="fw-bold mb-0" style="font-size: clamp(1.25rem, 2.5vw, 1.75rem); color: #e74c3c; line-height: 1.2;">{{ $totalAppointments }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Appointments -->
        <div class="col-xl col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-top: 3px solid #f39c12;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center stat-icon-wrapper"
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); box-shadow: 0 3px 10px rgba(243, 156, 18, 0.25);">
                                <i class="bi bi-clock-history text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Pending</p>
                            <h3 class="fw-bold mb-0" style="font-size: clamp(1.25rem, 2.5vw, 1.75rem); color: #f39c12; line-height: 1.2;">{{ $pendingAppointments }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Members -->
        <div class="col-xl col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-top: 3px solid #9b59b6;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center stat-icon-wrapper"
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #9b59b6 0%, #7e3d95 100%); box-shadow: 0 3px 10px rgba(155, 89, 182, 0.25);">
                                <i class="bi bi-person-badge-fill text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Staff Members</p>
                            <h3 class="fw-bold mb-0" style="font-size: clamp(1.25rem, 2.5vw, 1.75rem); color: #9b59b6; line-height: 1.2;">{{ $staffMembers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row mb-4 g-3 align-items-stretch">
        <!-- Left Column -->
        <div class="col-lg-6 d-flex flex-column">
            <!-- Today's Appointments Section -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header enhanced-card-header border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 me-2 text-primary"></i>Today's Appointments
                        </h5>
                        <a href="{{ route('admin-appointment') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($todayAppointmentsList->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-calendar-x empty-state-icon text-primary"></i>
                            <p class="mt-3 mb-0 text-muted">No appointments scheduled for today</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($todayAppointmentsList as $appointment)
                                <div class="list-group-item border-0 px-0 appointment-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px; transition: all 0.2s ease;">
                                                <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1 fw-semibold">
                                                @if($appointment->patient && $appointment->patient->info)
                                                    {{ $appointment->patient->info->first_name }} {{ $appointment->patient->info->last_name }}
                                                @else
                                                    Unknown Patient
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($appointment->start_datetime)->format('g:i A') }}
                                                @if($appointment->service)
                                                    - {{ $appointment->service->service_name }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge
                                                @if($appointment->status === 'Pending') bg-warning
                                                @elseif($appointment->status === 'Confirmed') bg-primary
                                                @elseif($appointment->status === 'Completed') bg-success
                                                @else bg-secondary
                                                @endif" style="font-size: 0.75rem; padding: 0.4rem 0.75rem;">
                                                {{ $appointment->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Appointments Section -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header enhanced-card-header border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-event me-2 text-success"></i>Upcoming Appointments
                        </h5>
                        <a href="{{ route('admin-appointment') }}" class="btn btn-sm btn-outline-success">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($upcomingAppointments->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-calendar-check empty-state-icon text-success"></i>
                            <p class="mt-3 mb-0 text-muted">No upcoming appointments in the next 7 days</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($upcomingAppointments as $appointment)
                                <div class="list-group-item border-0 px-0 appointment-item" style="border-left-color: #16a085;">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px; transition: all 0.2s ease;">
                                                <i class="bi bi-person-fill text-success"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1 fw-semibold">
                                                @if($appointment->patient && $appointment->patient->info)
                                                    {{ $appointment->patient->info->first_name }} {{ $appointment->patient->info->last_name }}
                                                @else
                                                    Unknown Patient
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($appointment->start_datetime)->format('M j, Y') }}
                                                <i class="bi bi-clock ms-2 me-1"></i>
                                                {{ \Carbon\Carbon::parse($appointment->start_datetime)->format('g:i A') }}
                                                @if($appointment->service)
                                                    <i class="bi bi-heart-pulse ms-2 me-1"></i>{{ $appointment->service->service_name }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge
                                                @if($appointment->status === 'Pending') bg-warning
                                                @elseif($appointment->status === 'Confirmed') bg-success
                                                @elseif($appointment->status === 'Completed') bg-info
                                                @else bg-secondary
                                                @endif" style="font-size: 0.75rem; padding: 0.4rem 0.75rem;">
                                                {{ $appointment->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Patients Section -->
            <div class="card border-0 shadow-sm bg-white flex-grow-1 d-flex flex-column">
                <div class="card-header enhanced-card-header border-0 bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-people me-2 text-dark"></i>Recent Patients
                        </h5>
                        <a href="{{ route('admin-account-management') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body bg-white flex-grow-1" style="max-height: 400px; overflow-y: auto;">
                    @if($recentPatients->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-person-x empty-state-icon text-dark"></i>
                            <p class="mt-3 mb-0 text-muted">No patients registered yet</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentPatients as $patient)
                                <div class="list-group-item border-0 px-0 appointment-item patient-list-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle patient-avatar-circle d-flex align-items-center justify-content-center border"
                                                 style="width: 40px; height: 40px; transition: all 0.2s ease;">
                                                <i class="bi bi-person-fill patient-avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1 fw-semibold patient-name">
                                                @if($patient->info)
                                                    {{ $patient->info->first_name }} {{ $patient->info->last_name }}
                                                @else
                                                    {{ $patient->name }}
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-envelope me-1"></i>{{ $patient->email }}
                                                @if($patient->info && $patient->info->phone)
                                                    <span class="ms-2"><i class="bi bi-telephone me-1"></i>{{ $patient->info->phone }}</span>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Appointment Calendar -->
        <div class="col-lg-6 d-flex flex-column">
            <div class="card border-0 shadow-sm mb-3 calendar-card flex-grow-1 d-flex flex-column">
                <div class="card-header enhanced-card-header border-0">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 me-2" style="color: #16a085;"></i>Appointment Calendar
                        </h5>
                        <div class="calendar-nav d-flex align-items-center gap-2 flex-wrap">
                            <button id="prevMonth" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button id="todayBtn" class="btn btn-sm btn-outline-primary" title="Go to current month">
                                <i class="bi bi-calendar-day me-1"></i>Today
                            </button>
                            <span id="currentMonthYear" class="fw-bold px-3" style="min-width: 120px; text-align: center; font-size: 0.9rem;">
                                {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
                            </span>
                            <button id="nextMonth" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4 flex-grow-1 d-flex align-items-center justify-content-center">
                    <div class="mini-calendar w-100" id="dashboard-calendar">
                        <!-- Calendar will be generated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm services-list-card flex-grow-1 d-flex flex-column">
                <div class="card-header enhanced-card-header border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-clipboard-data me-2" style="color: #16a085;"></i>Current Services of the Clinic
                        </h5>
                        <a href="{{ route('admin-services') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body flex-grow-1 p-3">
                    @if($clinicServices->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-inbox empty-state-icon text-primary"></i>
                            <p class="mt-3 mb-0 text-muted">No services have been configured yet</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($clinicServices as $service)
                                <div class="list-group-item border-0 px-3 py-2 service-list-item mb-2 rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center service-avatar-circle border"
                                                 style="width: 42px; height: 42px; transition: all 0.3s ease;">
                                                <i class="bi bi-clipboard-data-fill service-avatar-icon" style="font-size: 1.1rem;"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-0 fw-semibold service-name" style="font-size: 0.95rem;">
                                                {{ $service->service_name }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patient Feedback Section -->
    <div class="card border-0 shadow-sm mb-4 feedback-section-card" style="background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);">
        <div class="card-header border-0 pt-4 pb-2" style="background: transparent;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center feedback-icon-wrapper"
                         style="width: 50px; height: 50px; background: linear-gradient(135deg, #16a085 0%, #0e7862 100%); box-shadow: 0 4px 12px rgba(22, 160, 133, 0.3); margin-right: 1rem;">
                        <i class="bi bi-chat-dots-fill text-white feedback-icon" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="feedback-text-container">
                        <h5 class="mb-0 fw-bold feedback-section-title" style="color: #16a085; font-size: 1.5rem;">Recent Patient Feedback</h5>
                        <small class="text-muted">Latest Review from patients</small>
                    </div>
                </div>
                <a href="{{ route('admin-feedback') }}" class="btn btn-sm btn-outline-primary" style="border-color: #16a085; color: #16a085;">
                    See All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body pt-2">
            @if($recentFeedback->isEmpty())
                <div class="text-center py-5 feedback-empty-state" style="background: white; border-radius: 12px;">
                    <i class="bi bi-chat-left-dots" style="font-size: 3rem; opacity: 0.3; color: #16a085;"></i>
                    <p class="mt-3 mb-0 text-muted">No patient feedback yet</p>
                </div>
            @else
                <div class="feedback-table-wrapper">
                    <table class="feedback-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Patient</th>
                                <th style="width: 18%;">Service</th>
                                <th style="width: 12%;">Rating</th>
                                <th style="width: 30%;">Comments</th>
                                <th style="width: 20%;">Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFeedback as $index => $feedback)
                                <tr class="feedback-row">
                                    <td>
                                        <strong class="feedback-patient-name" style="color: #16a085;">{{ $feedback['patient_name'] }}</strong>
                                    </td>
                                    <td>
                                        <span class="service-name">{{ $feedback['service_name'] }}</span>
                                    </td>
                                    <td>
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $feedback['rating'])
                                                    <i class="bi bi-star-fill" style="color: #16a085;"></i>
                                                @else
                                                    <i class="bi bi-star" style="color: #ccc;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($feedback['comment'])
                                            <div class="feedback-comment">
                                                <em class="feedback-comment-text" style="color: #16a085; font-weight: 500;">{{ $feedback['comment'] }}</em>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic" style="font-size: 0.9rem;">No comment</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="submission-info">
                                            <small class="feedback-submission-date" style="color: #16a085;">
                                                <i class="bi bi-calendar-check me-1"></i>{{ $feedback['rated_at'] }}
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <style>
        .feedback-table-wrapper {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .feedback-table-wrapper {
            background: var(--dm-card-bg) !important;
        }

        [data-theme="dark"] .feedback-empty-state {
            background: var(--dm-card-bg) !important;
        }

        /* Dark Mode Feedback Section */
        [data-theme="dark"] .feedback-section-card {
            background: linear-gradient(135deg, var(--dm-bg-primary) 0%, var(--dm-bg-secondary) 100%) !important;
            border-color: var(--dm-border-color) !important;
        }

        [data-theme="dark"] .feedback-section-title {
            color: var(--dm-text-primary) !important;
        }

        [data-theme="dark"] .feedback-row {
            border-bottom-color: var(--dm-border-color) !important;
        }

        [data-theme="dark"] .feedback-row td {
            color: var(--dm-text-primary) !important;
        }

        [data-theme="dark"] .feedback-patient-name {
            color: var(--dm-text-primary) !important;
        }

        [data-theme="dark"] .service-name {
            color: var(--dm-text-primary) !important;
        }

        [data-theme="dark"] .feedback-comment-text {
            color: var(--dm-text-primary) !important;
        }

        [data-theme="dark"] .feedback-submission-date {
            color: var(--dm-text-secondary) !important;
        }

        [data-theme="dark"] .feedback-table tbody tr {
            background: transparent !important;
        }

        [data-theme="dark"] .feedback-table tbody tr:hover {
            background: var(--dm-bg-tertiary) !important;
        }

        /* Dark Mode List Items */
        [data-theme="dark"] .list-group-item {
            background-color: var(--dm-card-bg) !important;
            border-color: var(--dm-border-color) !important;
            color: var(--dm-text-primary) !important;
        }

        [data-theme="dark"] .list-group-item h6 {
            color: var(--dm-text-primary) !important;
        }

        [data-theme="dark"] .list-group-item .text-muted {
            color: var(--dm-text-muted) !important;
        }

        [data-theme="dark"] .list-group-item:hover {
            background-color: var(--dm-bg-tertiary) !important;
        }

        .feedback-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .feedback-table thead {
            background: linear-gradient(135deg, #16a085 0%, #0e7862 100%);
            color: white;
        }

        .feedback-table thead th {
            padding: 1rem;
            font-weight: 700;
            font-size: 1rem;
            text-align: center;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .feedback-table thead th:first-child {
            border-top-left-radius: 12px;
        }

        .feedback-table thead th:last-child {
            border-top-right-radius: 12px;
        }

        .feedback-row {
            border-bottom: 1px solid #b2dfdb;
            transition: all 0.3s ease;
        }

        .feedback-row:last-child {
            border-bottom: none;
        }

        .feedback-row:hover {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 50%);
            transform: translateX(5px);
        }

        [data-theme="dark"] .feedback-row:hover {
            background: linear-gradient(135deg, var(--dm-bg-tertiary) 0%, var(--dm-bg-secondary) 50%) !important;
        }

        .feedback-row td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            text-align: center;
        }

        .service-name {
            color: #16a085;
            font-weight: 500;
        }

        .rating-stars {
            font-size: 1.1rem;
            display: flex;
            gap: 2px;
            justify-content: center;
        }

        /* Services List Card */
        .services-list-card .card-body {
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
            max-height: 420px;
            flex: 1 1 auto;
        }

        .services-list-card .card-body::-webkit-scrollbar {
            width: 8px;
        }

        .services-list-card .card-body::-webkit-scrollbar-thumb {
            background: rgba(22, 160, 133, 0.25);
            border-radius: 4px;
        }

        .services-list-card .card-body::-webkit-scrollbar-thumb:hover {
            background: rgba(22, 160, 133, 0.45);
        }

        [data-theme="dark"] .services-list-card {
            background-color: var(--dm-card-bg, #1e293b) !important;
            border-color: var(--dm-border-color, #334155) !important;
        }

        [data-theme="dark"] .services-list-card .card-header {
            background-color: var(--dm-card-bg, #1e293b) !important;
            border-bottom-color: var(--dm-border-color, #334155) !important;
        }

        [data-theme="dark"] .services-list-card .card-body {
            background-color: var(--dm-card-bg, #1e293b) !important;
        }

        [data-theme="dark"] .services-list-card .card-body::-webkit-scrollbar-thumb {
            background: rgba(22, 160, 133, 0.4) !important;
        }

        [data-theme="dark"] .services-list-card .card-body::-webkit-scrollbar-thumb:hover {
            background: rgba(22, 160, 133, 0.6) !important;
        }

        .service-list-item {
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .service-list-item:hover {
            background-color: #ffffff !important;
            border-color: #16a085 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 160, 133, 0.15);
        }

        .service-list-item:hover .service-avatar-circle {
            background: linear-gradient(135deg, #16a085 0%, #0e7862 100%) !important;
            border-color: #16a085 !important;
            transform: scale(1.05);
        }

        .service-list-item:hover .service-avatar-icon {
            color: #ffffff !important;
        }

        .service-avatar-circle {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            border-color: #dee2e6 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .service-avatar-icon {
            color: #16a085 !important;
            transition: all 0.3s ease;
        }

        .service-name {
            color: #212529 !important;
            transition: color 0.3s ease;
        }

        .service-list-item:hover .service-name {
            color: #16a085 !important;
        }

        [data-theme="dark"] .service-list-item {
            background-color: var(--dm-bg-tertiary, #334155) !important;
            border-color: var(--dm-border-color, #475569) !important;
        }

        [data-theme="dark"] .service-list-item:hover {
            background-color: var(--dm-bg-secondary, #475569) !important;
            border-color: #16a085 !important;
        }

        [data-theme="dark"] .service-list-item:hover .service-avatar-circle {
            background: linear-gradient(135deg, #16a085 0%, #0e7862 100%) !important;
            border-color: #16a085 !important;
        }

        [data-theme="dark"] .service-list-item:hover .service-avatar-icon {
            color: #ffffff !important;
        }

        [data-theme="dark"] .service-list-item:hover .service-name {
            color: #16a085 !important;
        }

        [data-theme="dark"] .service-avatar-circle {
            background: linear-gradient(135deg, var(--dm-bg-tertiary, #334155) 0%, var(--dm-bg-secondary, #475569) 100%) !important;
            border-color: var(--dm-border-color, #475569) !important;
        }

        [data-theme="dark"] .service-avatar-icon {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        [data-theme="dark"] .service-name {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        .feedback-comment {
            color: #16a085;
            line-height: 1.6;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            overflow-wrap: anywhere;
            font-family: 'Poppins', sans-serif;
        }

        .submission-info {
            line-height: 1.6;
            white-space: nowrap;
        }

        .submission-info small {
            white-space: nowrap;
            display: inline-block;
        }

        @media (max-width: 1200px) {
            .feedback-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>

    <!-- Analytics Row -->
    <div class="row g-3">
        <!-- User Demographics -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people-fill me-2" style="color: #16a085;"></i>User Demographics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sex Chart -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-center text-muted mb-3">Sex</h6>
                            <div style="height: 200px; position: relative;">
                                <canvas id="sexChart"></canvas>
                            </div>
                            <div class="mt-3 d-flex justify-content-center gap-3">
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #67B7DC; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Male</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #6794DC; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Female</small>
                                </div>
                            </div>
                        </div>
                        <!-- Age Chart -->
                        <div class="col-md-6">
                            <h6 class="text-center text-muted mb-3">Age</h6>
                            <div style="height: 200px; position: relative;">
                                <canvas id="ageChart"></canvas>
                            </div>
                            <div class="mt-3 d-flex justify-content-center gap-3">
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #6AD4DD; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Pediatric</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #50B4C8; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Adult</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Feedback -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-star-fill me-2" style="color: #16a085;"></i>Service Feedback
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 315px;">
                        <canvas id="feedbackChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Performed Services -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-heart-pulse-fill me-2" style="color: #16a085;"></i>Most Performed Services
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 315px;">
                        <canvas id="servicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mini-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.mini-calendar-header {
    text-align: center;
    font-weight: 600;
    padding: 8px;
    color: #6c757d;
    font-size: 0.85rem;
}

.mini-calendar-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px;
    border-radius: 8px;
    font-size: 0.9rem;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.mini-calendar-day:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.mini-calendar-day.has-appointments {
    background: #d1e7dd;
    border: 2px solid #16a085;
}

.mini-calendar-day.has-completed-appointments {
    background: #e2e3e5;
    border: 2px solid #6c757d;
}

.mini-calendar-day.today {
    background: #16a085;
    color: white;
    font-weight: bold;
}

.mini-calendar-day.other-month {
    opacity: 0.3;
}

.appointment-count {
    font-size: 0.7rem;
    color: #16a085;
    margin-top: 2px;
}

.appointment-count-completed {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: 2px;
    font-style: italic;
}

.mini-calendar-day.today .appointment-count {
    color: white;
}

.mini-calendar-day.today .appointment-count-completed {
    color: white;
    opacity: 0.8;
}

/* Dark Mode Calendar Styles */
[data-theme="dark"] .mini-calendar-header {
    color: var(--dm-text-secondary) !important;
}

[data-theme="dark"] .mini-calendar-day {
    background: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
    border: 1px solid var(--dm-border-color) !important;
}

[data-theme="dark"] .mini-calendar-day:hover {
    background: var(--dm-bg-tertiary) !important;
}

[data-theme="dark"] .mini-calendar-day.other-month {
    background: var(--dm-bg-secondary) !important;
    color: var(--dm-text-muted) !important;
    opacity: 0.5 !important;
}

[data-theme="dark"] .mini-calendar-day.has-appointments {
    background: rgba(22, 160, 133, 0.2) !important;
    border: 2px solid #16a085 !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .mini-calendar-day.has-completed-appointments {
    background: var(--dm-bg-tertiary) !important;
    border: 2px solid var(--dm-text-muted) !important;
    color: var(--dm-text-secondary) !important;
}

[data-theme="dark"] .mini-calendar-day.today {
    background: #16a085 !important;
    color: white !important;
}

[data-theme="dark"] .appointment-count {
    color: #16a085 !important;
}

[data-theme="dark"] .mini-calendar-day.has-appointments .appointment-count {
    color: #16a085 !important;
}

[data-theme="dark"] .appointment-count-completed {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .mini-calendar-day.today .appointment-count {
    color: white !important;
}

[data-theme="dark"] .mini-calendar-day.today .appointment-count-completed {
    color: white !important;
    opacity: 0.8 !important;
}

/* Dark Mode List Items */
[data-theme="dark"] .list-group-item {
    background-color: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .list-group-item h6 {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .list-group-item .text-muted {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .list-group-item:hover {
    background-color: var(--dm-bg-tertiary) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointments = @json($appointments);
    const blockedTimes = @json($blockedTimes);
    const initialMonth = {{ $currentMonth }};
    const initialYear = {{ $currentYear }};
    let currentMonth = {{ $currentMonth }};
    let currentYear = {{ $currentYear }};

    // Helper function to parse datetime strings as LOCAL time
    const parseLocalDateTime = (datetimeStr) => {
        if (!datetimeStr || typeof datetimeStr !== 'string') {
            console.warn('Invalid datetime string:', datetimeStr);
            return null;
        }

        try {
            const [datePart, timePart] = datetimeStr.split(' ');
            const [year, month, day] = datePart.split('-').map(Number);
            const [hours, minutes, seconds] = timePart.split(':').map(Number);

            if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hours) || isNaN(minutes)) {
                console.warn('Invalid datetime values:', datetimeStr);
                return null;
            }

            return new Date(year, month - 1, day, hours, minutes, seconds || 0);
        } catch (error) {
            console.error('Error parsing datetime:', datetimeStr, error);
            return null;
        }
    };

    // Update month/year display
    function updateMonthYearDisplay() {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        document.getElementById('currentMonthYear').textContent = monthNames[currentMonth - 1] + ' ' + currentYear;
    }

    // Generate mini calendar
    function generateMiniCalendar() {
        const calendarDiv = document.getElementById('dashboard-calendar');
        if (!calendarDiv) return;

        calendarDiv.innerHTML = '';

        // Add day headers
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        days.forEach(day => {
            const header = document.createElement('div');
            header.className = 'mini-calendar-header';
            header.textContent = day;
            calendarDiv.appendChild(header);
        });

        // Generate calendar days
        const firstDay = new Date(currentYear, currentMonth - 1, 1);
        const lastDay = new Date(currentYear, currentMonth, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'mini-calendar-day other-month';
            calendarDiv.appendChild(emptyDay);
        }

        // Add days of the month
        const today = new Date();
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'mini-calendar-day';

            const cellDate = new Date(currentYear, currentMonth - 1, day);

            // Check if today
            if (cellDate.toDateString() === today.toDateString()) {
                dayElement.classList.add('today');
            }

            // Count appointments for this day
            const dayAppointments = appointments.filter(apt => {
                const aptDate = parseLocalDateTime(apt.start_datetime);
                return aptDate && aptDate.toDateString() === cellDate.toDateString();
            });

            // Separate completed from active appointments
            const activeAppointments = dayAppointments.filter(apt => apt.status !== 'Completed');
            const completedAppointments = dayAppointments.filter(apt => apt.status === 'Completed');

            if (activeAppointments.length > 0) {
                dayElement.classList.add('has-appointments');
            } else if (completedAppointments.length > 0) {
                dayElement.classList.add('has-completed-appointments');
            }

            dayElement.innerHTML = `
                <div>${day}</div>
                ${activeAppointments.length > 0 ? `<div class="appointment-count">${activeAppointments.length} apt${activeAppointments.length > 1 ? 's' : ''}</div>` : ''}
                ${completedAppointments.length > 0 && activeAppointments.length === 0 ? `<div class="appointment-count-completed">${completedAppointments.length} done</div>` : ''}
            `;

            // Click to go to appointment page
            dayElement.addEventListener('click', function() {
                window.location.href = '{{ route("admin-appointment") }}?month=' + currentMonth + '&year=' + currentYear;
            });

            calendarDiv.appendChild(dayElement);
        }
    }

    // Navigation event listeners
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        updateMonthYearDisplay();
        generateMiniCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        updateMonthYearDisplay();
        generateMiniCalendar();
    });

    // Today button - reset to current month
    document.getElementById('todayBtn').addEventListener('click', function() {
        currentMonth = initialMonth;
        currentYear = initialYear;
        updateMonthYearDisplay();
        generateMiniCalendar();
    });

    generateMiniCalendar();

    // Initialize Charts
    const chartColors = {
        teal: ['#67B7DC', '#6794DC'],
        cyan: ['#6AD4DD', '#50B4C8'],
        primary: '#16a085'
    };

    // Sex Distribution Chart
    const sexCtx = document.getElementById('sexChart');
    if (sexCtx) {
        new Chart(sexCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [{{ $maleCount }}, {{ $femaleCount }}],
                    backgroundColor: chartColors.teal,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $maleCount + $femaleCount }};
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Age Distribution Chart
    const ageCtx = document.getElementById('ageChart');
    if (ageCtx) {
        new Chart(ageCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pediatric', 'Adult'],
                datasets: [{
                    data: [{{ $pediatricCount }}, {{ $adultCount }}],
                    backgroundColor: chartColors.cyan,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $pediatricCount + $adultCount }};
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Service Feedback Chart
    const feedbackCtx = document.getElementById('feedbackChart');
    if (feedbackCtx) {
        const feedbackData = @json($feedbackData);
        new Chart(feedbackCtx, {
            type: 'bar',
            data: {
                labels: ['⭐⭐⭐⭐⭐', '⭐⭐⭐⭐', '⭐⭐⭐', '⭐⭐', '⭐'],
                datasets: [{
                    data: [
                        feedbackData[5] || 0,
                        feedbackData[4] || 0,
                        feedbackData[3] || 0,
                        feedbackData[2] || 0,
                        feedbackData[1] || 0
                    ],
                    backgroundColor: '#16a085',
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const starCount = 5 - context[0].dataIndex;
                                return starCount + ' Star' + (starCount !== 1 ? 's' : '');
                            },
                            label: function(context) {
                                return 'Ratings: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    }

    // Most Performed Services Chart
    const servicesCtx = document.getElementById('servicesChart');
    if (servicesCtx) {
        const topServices = @json($topServices);

        // Extract service names and counts
        const serviceNames = topServices.map(service => service.name);
        const serviceCounts = topServices.map(service => service.count);

        // Generate gradient colors for each bar
        const gradientColors = [
            '#16a085',
            '#3498db',
            '#9b59b6',
            '#e74c3c',
            '#f39c12'
        ];

        new Chart(servicesCtx, {
            type: 'bar',
            data: {
                labels: serviceNames,
                datasets: [{
                    label: 'Appointments',
                    data: serviceCounts,
                    backgroundColor: gradientColors.slice(0, serviceCounts.length),
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Appointments: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
