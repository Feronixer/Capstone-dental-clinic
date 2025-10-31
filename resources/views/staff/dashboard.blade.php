@extends('layout.staff.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    .card {
        transition: all 0.3s ease;
    }
    .btn {
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Success Message -->
    @if(session('success'))
        <x-toast-message type="success" :message="session('success')" />
    @endif

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Good {{ now()->format('A') === 'AM' ? 'morning' : (now()->format('A') === 'PM' && now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0">You have {{ $todayAppointments }} appointments to manage today from {{ $totalPatients }} active patients.</p>
                    </div>
                    <div class="text-white" style="font-size: 4rem; opacity: 0.3;">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-4">
        <!-- Total Patient Load -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 65px; height: 65px; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                                <i class="bi bi-people-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Patient Load</p>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem; color: #0d6efd;">{{ $totalPatients }}</h2>
                            <small class="text-muted">Active patients</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 65px; height: 65px; background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);">
                                <i class="bi bi-calendar-check-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Today's Schedule</p>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem; color: #0dcaf0;">{{ $todayAppointments }}</h2>
                            <small class="text-muted">Appointments to manage</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Appointments -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 65px; height: 65px; background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
                                <i class="bi bi-calendar3 text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Appointments</p>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem; color: #198754;">{{ $totalAppointments }}</h2>
                            <small class="text-muted">All time</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Actions -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 65px; height: 65px; background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);">
                                <i class="bi bi-clock-history text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Pending</p>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem; color: #6c757d;">{{ $pendingAppointments }}</h2>
                            <small class="text-muted">Need confirmation</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row mb-4 g-4">
        <!-- Left Column - Today's Appointments & Upcoming -->
        <div class="col-lg-6">
            <!-- Today's Appointments -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 me-2 text-primary"></i>Today's Appointments
                        </h5>
                        <a href="{{ route('staff-appointment') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($todayAppointmentsList->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-0">No appointments scheduled for today</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($todayAppointmentsList as $appointment)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1">
                                                @if($appointment->patient && $appointment->patient->info)
                                                    {{ $appointment->patient->info->first_name }} {{ $appointment->patient->info->last_name }}
                                                @else
                                                    Unknown Patient
                                                @endif
                                            </h6>
                                            <div class="text-muted small">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($appointment->start_datetime)->format('g:i A') }}
                                                @if($appointment->service)
                                                    | <i class="bi bi-scissors me-1"></i>{{ $appointment->service->service_name }}
                                                @endif
                                            </div>
                                            @if($appointment->patient && $appointment->patient->info && $appointment->patient->info->phone)
                                                <div class="text-muted small mt-1">
                                                    <i class="bi bi-telephone me-1"></i>{{ $appointment->patient->info->phone }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge
                                                @if($appointment->status === 'Pending') bg-warning
                                                @elseif($appointment->status === 'Confirmed') bg-primary
                                                @elseif($appointment->status === 'Completed') bg-success
                                                @else bg-secondary
                                                @endif">
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

            <!-- Upcoming Appointments -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-event me-2 text-success"></i>Upcoming Appointments
                        </h5>
                        <a href="{{ route('staff-appointment') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($upcomingAppointments->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-0">No upcoming appointments</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($upcomingAppointments as $appointment)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-person-fill text-success"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1">
                                                @if($appointment->patient && $appointment->patient->info)
                                                    {{ $appointment->patient->info->first_name }} {{ $appointment->patient->info->last_name }}
                                                @else
                                                    Unknown Patient
                                                @endif
                                            </h6>
                                            <div class="text-muted small">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $appointment->start_datetime->format('M j, Y') }} -
                                                <i class="bi bi-clock ms-1 me-1"></i>
                                                {{ $appointment->start_datetime->format('g:i A') }}
                                                @if($appointment->service)
                                                    | <i class="bi bi-scissors me-1"></i>{{ $appointment->service->service_name }}
                                                @endif
                                            </div>
                                            @if($appointment->patient && $appointment->patient->info && $appointment->patient->info->phone)
                                                <div class="text-muted small mt-1">
                                                    <i class="bi bi-telephone me-1"></i>{{ $appointment->patient->info->phone }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge
                                                @if($appointment->status === 'Pending') bg-warning
                                                @elseif($appointment->status === 'Confirmed') bg-success
                                                @elseif($appointment->status === 'Completed') bg-success
                                                @else bg-secondary
                                                @endif">
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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-people me-2 text-primary"></i>Recent Patients
                        </h5>
                        <a href="{{ route('staff-patient-records') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentPatients->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-person-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-0">No patients registered yet</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentPatients as $patient)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1">
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

        <!-- Right Column - Calendar -->
        <div class="col-lg-6">
            <!-- Mini Calendar -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 me-2 text-primary"></i>Appointment Calendar
                        </h5>
                        <div class="calendar-nav d-flex align-items-center gap-2">
                            <button id="prevMonth" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button id="todayBtn" class="btn btn-sm btn-outline-primary" title="Go to current month">
                                <i class="bi bi-calendar-day me-1"></i>Today
                            </button>
                            <span id="currentMonthYear" class="fw-bold px-3" style="min-width: 150px; text-align: center;">
                                {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
                            </span>
                            <button id="nextMonth" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mini-calendar" id="dashboard-calendar">
                        <!-- Calendar will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patient Feedback Section -->
    <div class="card border-0 shadow-sm mb-4 feedback-section-card" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);">
        <div class="card-header border-0 pt-4 pb-2" style="background: transparent;">
            <div class="d-flex align-items-center mb-2">
                <div class="rounded-circle d-flex align-items-center justify-center me-3"
                     style="width: 50px; height: 50px; background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%); box-shadow: 0 4px 12px rgba(0, 188, 212, 0.3);">
                    <i class="bi bi-chat-dots-fill text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold feedback-section-title" style="color: #00695c; font-size: 1.5rem;">Recent Patient Feedback</h5>
                    <small class="text-muted">Latest Review from patients</small>
                </div>
            </div>
        </div>
        <div class="card-body pt-2">
            @if($recentFeedback->isEmpty())
                <div class="text-center py-5 feedback-empty-state" style="background: white; border-radius: 12px;">
                    <i class="bi bi-chat-left-dots" style="font-size: 3rem; opacity: 0.3; color: #00bcd4;"></i>
                    <p class="mt-3 mb-0 text-muted">No patient feedback yet</p>
                </div>
            @else
                <div class="feedback-table-wrapper">
                    <table class="feedback-table">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Patient</th>
                                <th style="width: 15%;">Service</th>
                                <th style="width: 10%;">Rating</th>
                                <th style="width: 40%;">Comments</th>
                                <th style="width: 20%;">Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFeedback as $index => $feedback)
                                <tr class="feedback-row">
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="patient-avatar me-3">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <strong style="color: #00695c;">{{ $feedback['patient_name'] }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="service-name">{{ $feedback['service_name'] }}</span>
                                    </td>
                                    <td>
                                        <div class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $feedback['rating'])
                                                    <i class="bi bi-star-fill" style="color: #00bcd4;"></i>
                                                @else
                                                    <i class="bi bi-star" style="color: #ccc;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($feedback['comment'])
                                            <div class="feedback-comment">
                                                <span style="color: #00bcd4; font-size: 1.5rem; font-weight: bold; margin-right: 0.3rem;">"</span>
                                                <em style="color: #00695c; font-weight: 500;">{{ $feedback['comment'] }}</em>
                                                <span style="color: #00bcd4; font-size: 1.5rem; font-weight: bold; margin-left: 0.3rem;">"</span>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic" style="font-size: 0.9rem;">No comment</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="submission-info">
                                            <small style="color: #00695c;">
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

        .feedback-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .feedback-table thead {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
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
            border-bottom: 1px solid #e0f7fa;
            transition: all 0.3s ease;
        }

        .feedback-row:last-child {
            border-bottom: none;
        }

        .feedback-row:hover {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 50%);
            transform: translateX(5px);
        }

        .feedback-row td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            text-align: center;
        }

        .patient-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            box-shadow: 0 2px 8px rgba(0, 188, 212, 0.3);
        }

        .service-name {
            color: #00695c;
            font-weight: 500;
        }

        .rating-stars {
            font-size: 1.1rem;
            display: flex;
            gap: 2px;
            justify-content: center;
        }

        .feedback-comment {
            color: #00695c;
            line-height: 1.6;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        .submission-info {
            line-height: 1.6;
        }

        @media (max-width: 1200px) {
            .feedback-table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Dark Mode Styles for Feedback Section */
        [data-theme="dark"] .feedback-section-card {
            background: linear-gradient(135deg, var(--dm-bg-primary, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        }

        [data-theme="dark"] .card-header[style*="background: transparent"] {
            background: transparent !important;
        }

        [data-theme="dark"] .feedback-section-title {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        [data-theme="dark"] .card-header .text-muted {
            color: var(--dm-text-muted, #94a3b8) !important;
        }

        [data-theme="dark"] .feedback-empty-state {
            background: var(--dm-card-bg, #1e293b) !important;
            border: 1px solid var(--dm-border-color, #334155) !important;
        }

        [data-theme="dark"] .feedback-empty-state .text-muted {
            color: var(--dm-text-muted, #94a3b8) !important;
        }

        [data-theme="dark"] .feedback-empty-state i[style*="color: #00bcd4"] {
            color: #17a2b8 !important;
            opacity: 0.5 !important;
        }

        [data-theme="dark"] .feedback-table-wrapper {
            background: var(--dm-card-bg, #1e293b) !important;
            border: 1px solid var(--dm-border-color, #334155) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
        }

        [data-theme="dark"] .feedback-table thead {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
            color: white !important;
        }

        [data-theme="dark"] .feedback-table thead th {
            color: white !important;
        }

        [data-theme="dark"] .feedback-row {
            border-bottom-color: var(--dm-border-color, #334155) !important;
        }

        [data-theme="dark"] .feedback-row:hover {
            background: var(--dm-bg-tertiary, #334155) !important;
            transform: translateX(5px);
        }

        [data-theme="dark"] .feedback-row td {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        [data-theme="dark"] .feedback-row strong[style*="color: #00695c"] {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        [data-theme="dark"] .service-name {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        [data-theme="dark"] .feedback-comment {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        [data-theme="dark"] .feedback-comment em[style*="color: #00695c"] {
            color: var(--dm-text-primary, #f1f5f9) !important;
        }

        [data-theme="dark"] .feedback-comment span[style*="color: #00bcd4"] {
            color: #17a2b8 !important;
        }

        [data-theme="dark"] .submission-info small[style*="color: #00695c"] {
            color: var(--dm-text-muted, #94a3b8) !important;
        }

        [data-theme="dark"] .rating-stars i[style*="color: #00bcd4"] {
            color: #17a2b8 !important;
        }

        [data-theme="dark"] .rating-stars i[style*="color: #ccc"] {
            color: var(--dm-border-color, #475569) !important;
        }
    </style>

    <!-- Analytics Row -->
    <div class="row g-3 mb-4">
        <!-- User Demographics -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people-fill me-2" style="color: #0d6efd;"></i>User Demographics
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
                        <i class="bi bi-star-fill me-2" style="color: #0d6efd;"></i>Service Feedback
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
                        <i class="bi bi-heart-pulse-fill me-2" style="color: #0d6efd;"></i>Most Performed Services
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
    gap: 6px;
}

.mini-calendar-header {
    text-align: center;
    font-weight: 600;
    padding: 6px;
    color: #6c757d;
    font-size: 0.8rem;
}

.mini-calendar-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6px;
    border-radius: 8px;
    font-size: 0.85rem;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.mini-calendar-day:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.mini-calendar-day.has-appointments {
    background: #cfe2ff;
    border: 2px solid #0d6efd;
}

.mini-calendar-day.has-completed-appointments {
    background: #e2e3e5;
    border: 2px solid #6c757d;
}

.mini-calendar-day.today {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    font-weight: bold;
}

.mini-calendar-day.other-month {
    opacity: 0.3;
}

.appointment-count {
    font-size: 0.65rem;
    color: #0d6efd;
    margin-top: 2px;
}

.appointment-count-completed {
    font-size: 0.65rem;
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

/* Dark Mode Styles for Mini Calendar */
[data-theme="dark"] .mini-calendar {
    background: transparent !important;
}

[data-theme="dark"] .mini-calendar-header {
    color: var(--dm-text-primary, #f1f5f9) !important;
    font-weight: 600 !important;
}

[data-theme="dark"] .mini-calendar-day {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .mini-calendar-day:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
    transform: translateY(-2px);
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .mini-calendar-day.has-appointments {
    background: rgba(59, 130, 246, 0.2) !important;
    border: 2px solid #3b82f6 !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .mini-calendar-day.has-appointments:hover {
    background: rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .mini-calendar-day.has-completed-appointments {
    background: var(--dm-bg-secondary, #0f172a) !important;
    border: 2px solid var(--dm-border-color, #475569) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .mini-calendar-day.has-completed-appointments:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .mini-calendar-day.today {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    font-weight: bold !important;
    border: 2px solid #3b82f6 !important;
}

[data-theme="dark"] .mini-calendar-day.other-month {
    background: var(--dm-bg-secondary, #0f172a) !important;
    opacity: 0.4 !important;
    color: var(--dm-text-muted, #64748b) !important;
}

[data-theme="dark"] .appointment-count {
    font-size: 0.65rem;
    color: #93c5fd !important;
    margin-top: 2px;
    font-weight: 600 !important;
}

[data-theme="dark"] .appointment-count-completed {
    font-size: 0.65rem;
    color: var(--dm-text-muted, #94a3b8) !important;
    margin-top: 2px;
    font-style: italic;
}

[data-theme="dark"] .mini-calendar-day.today .appointment-count {
    color: white !important;
}

[data-theme="dark"] .mini-calendar-day.today .appointment-count-completed {
    color: white !important;
    opacity: 0.9 !important;
}

[data-theme="dark"] .mini-calendar-day.has-appointments .appointment-count {
    color: #93c5fd !important;
}

/* Dark mode for calendar card */
[data-theme="dark"] .card.border-0.shadow-sm {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .card-header.bg-white {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .card-header h5 {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .card-header #currentMonthYear {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .card-body {
    background: var(--dm-card-bg, #1e293b) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointments = @json($appointments);
    const initialMonth = {{ $currentMonth }};
    const initialYear = {{ $currentYear }};
    let currentMonth = {{ $currentMonth }};
    let currentYear = {{ $currentYear }};

    // Helper function to parse datetime strings as LOCAL time
    const parseLocalDateTime = (datetimeStr) => {
        if (!datetimeStr || typeof datetimeStr !== 'string') {
            return null;
        }

        try {
            const [datePart, timePart] = datetimeStr.split(' ');
            const [year, month, day] = datePart.split('-').map(Number);
            const [hours, minutes, seconds] = timePart.split(':').map(Number);

            if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hours) || isNaN(minutes)) {
                return null;
            }

            return new Date(year, month - 1, day, hours, minutes, seconds || 0);
        } catch (error) {
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
                window.location.href = '{{ route("staff-appointment") }}?month=' + currentMonth + '&year=' + currentYear;
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
        primary: '#0d6efd'
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
                    backgroundColor: '#0d6efd',
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
            '#0d6efd',
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
