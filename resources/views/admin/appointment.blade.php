@extends('layout.admin.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="h2 fw-bold mb-0" style="color: #3498db;">Schedule</h1>
                <a href="{{ route('admin-appointment.table') }}" class="btn btn-outline-primary" title="Table View">
                    <i class="bi bi-archive text-primary me-1"></i>Table View
                </a>
            </div>
            <p class="text-muted mb-0" id="appointment-summary">
                {{ $appointments->count() }} Appointments are set for {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}
            </p>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <!-- Top Row: View Toggles, Date Navigation, Action Buttons -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3" style="min-height: 50px;">
                <!-- View Toggles -->
                <div class="d-flex gap-2 align-items-center" style="flex-shrink: 0;">
                    <button class="btn btn-primary view-toggle active" data-view="month" id="month-btn" style="min-width: 80px;">Month</button>
                    <button class="btn btn-outline-secondary view-toggle" data-view="week" id="week-btn" style="min-width: 80px;">Week</button>
                    <button class="btn btn-outline-secondary view-toggle" data-view="day" id="day-btn" style="min-width: 80px;">Day</button>
                </div>

                <!-- Calendar Navigation -->
                <div class="d-flex align-items-center gap-2" style="flex-shrink: 0;">
                    <button class="btn btn-outline-secondary btn-sm" id="prev-period" style="min-width: 38px;">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h5 class="mb-0 text-dark" id="current-period" style="min-width: 180px; text-align: center; font-size: 1rem;">
                        {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}
                    </h5>
                    <button class="btn btn-outline-secondary btn-sm" id="next-period" style="min-width: 38px;">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm" id="today-button" style="min-width: 80px;">
                        Today
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 align-items-center" style="flex-shrink: 0;">
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#blockTimeModal" style="white-space: nowrap;">
                        <i class="bi bi-x-circle me-1"></i>Block Off Time
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal" style="white-space: nowrap;">
                        <i class="bi bi-plus-circle me-1"></i>Add Appointment
                    </button>
                </div>
            </div>

            <!-- Bottom Row: Status Legend -->
            <div class="d-flex gap-3" style="flex-wrap: nowrap;">
                <div class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
                    <div class="status-dot bg-warning"></div>
                    <small class="text-muted" style="font-size: 0.75rem; white-space: nowrap;">Pending</small>
                </div>
                <div class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
                    <div class="status-dot bg-primary"></div>
                    <small class="text-muted" style="font-size: 0.75rem; white-space: nowrap;">Confirmed</small>
                </div>
                <div class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
                    <div class="status-dot bg-success"></div>
                    <small class="text-muted" style="font-size: 0.75rem; white-space: nowrap;">Completed</small>
                </div>
                <div class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
                    <div class="status-dot bg-brown"></div>
                    <small class="text-muted" style="font-size: 0.75rem; white-space: nowrap;">Cancelled</small>
                </div>
                <div class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
                    <div class="status-dot bg-secondary"></div>
                    <small class="text-muted" style="font-size: 0.75rem; white-space: nowrap;">Missed</small>
                </div>
                <div class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
                    <div class="status-dot bg-danger"></div>
                    <small class="text-muted" style="font-size: 0.75rem; white-space: nowrap;">Blocked</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="row">
        <div class="col-12">
            <div class="calendar-container">
                <!-- Month View -->
                <div class="calendar-grid month-view" id="month-calendar">
                    <!-- Month calendar will be generated by JavaScript -->
                </div>

                <!-- Week View -->
                <div class="calendar-grid week-view d-none" id="week-calendar">
                    <!-- Week calendar will be generated by JavaScript -->
                </div>

                <!-- Day View -->
                <div class="calendar-grid day-view d-none" id="day-calendar">
                    <!-- Day calendar will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Appointment Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalTitle">Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="appointmentForm">
                <div class="modal-body appointment-modal-body">
                    <input type="hidden" id="appointment_id" name="id">

                    <!-- Validation Messages -->
                    <div id="validation-messages" class="mb-2" style="display: none;">
                        <!-- Messages will appear here -->
                    </div>

                    <!-- Patient Search Section -->
                    <div class="mb-3">
                        <label for="patient_search" class="form-label fw-bold appointment-label">Search Patient Name *</label>
                        <div class="input-group input-group-sm">
                            <div class="position-relative flex-grow-1">
                            <input type="text" class="form-control form-control-sm" id="patient_search" name="patient_search" placeholder="Enter patient name or ID" required>
                                <div id="patient-results" class="mt-1" style="display: none;">
                                    <!-- Patient search results will appear here -->
                                </div>
                            </div>
                            <button type="button" class="btn btn-link text-primary btn-sm p-1" id="add-new-patient" style="font-size: 0.75rem; white-space: nowrap;">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Add New Patient
                            </button>
                        </div>
                        <input type="hidden" id="patient_id" name="patient_id">
                    </div>

                        <!-- Service Name -->
                    <div class="mb-3">
                            <label for="service_name" class="form-label fw-bold appointment-label mb-1">Service Name *</label>
                            <select class="form-select form-select-sm" id="service_name" name="service_name" required>
                                <option value="">Select a service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                            data-duration="{{ $service->default_duration_minutes }}"
                                            data-description="{{ $service->description }}">
                                        {{ $service->service_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="service-info" class="mt-1" style="display: none;">
                                <small class="text-muted" id="service-description" style="font-size: 0.75rem;"></small>
                                <br>
                                <small class="text-muted" id="service-duration" style="font-size: 0.75rem;"></small>
                            </div>
                        </div>

                    <!-- Date and Time Selection -->
                    <div class="row g-3 mb-3">
                        <!-- Select Date Section -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-check me-2" style="font-size: 1rem;"></i>
                                <label class="form-label fw-bold appointment-label mb-0">Select Date:</label>
                            </div>
                            <div class="date-picker-container appointment-calendar">
                                <div class="calendar-widget" id="calendar-widget">
                                    <div class="calendar-header d-flex justify-content-between align-items-center mb-2">
                                        <span style="font-size: 0.8rem;">Date</span>
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 me-2" id="prev-month" style="width: 24px; height: 24px; padding: 0 !important;">
                                                <i class="bi bi-chevron-left" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <span id="current-month-year" style="font-size: 0.8rem; font-weight: 500;">October 2025</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 ms-2" id="next-month" style="width: 24px; height: 24px; padding: 0 !important;">
                                                <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="calendar-grid" id="modal-calendar">
                                        <!-- Calendar will be generated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="selected_date" name="selected_date">
                            <div class="form-text" style="font-size: 0.7rem; margin-top: 0.5rem;">Click on a date to select it</div>
                        </div>

                        <!-- Select Time Section -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock me-2" style="font-size: 1rem;"></i>
                                    <label class="form-label fw-bold appointment-label mb-0">Select Time:</label>
                                </div>
                                <span id="time-selected-status" class="text-muted" style="font-size: 0.8rem;">No time selected</span>
                            </div>
                            <div class="time-slots-container" style="max-height: 300px; overflow-y: auto;">
                                <div class="time-slots-grid" id="time-slots-list">
                                    <!-- Time slots will be generated dynamically based on selected service duration and date -->
                                    <div class="text-muted text-center w-100" style="padding: 20px;">
                                        <small>Please select a service and date to view available time slots</small>
                                    </div>
                                </div>
                                <div id="end-time-preview" class="mt-2" style="display: none; font-size: 0.8rem; color: #6c757d;"></div>
                            </div>
                            <div class="mt-2" style="font-size: 0.75rem; color: #6c757d;">
                                <small>Clinic hours: 11:00 AM – 6:00 PM</small>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slot Styles -->
                    <style>
                        .time-slots-grid {
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
                            gap: 8px;
                        }
                        .time-slots-grid > div:only-child {
                            grid-column: 1 / -1;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-height: 150px;
                        }
                        .time-slot-btn {
                            cursor: pointer;
                            border: 1px solid #e2e8f0;
                            border-radius: 8px;
                            padding: 8px 12px;
                            text-align: center;
                            background-color: #fff;
                            transition: all 0.15s ease;
                            font-size: 0.875rem;
                            color: #1e293b;
                        }
                        .time-slot-btn:hover:not(:disabled) {
                            background-color: #f1f5f9;
                            border-color: #cbd5e1;
                        }
                        .time-slot-btn.selected {
                            background-color: #0ea5e9;
                            border-color: #0ea5e9;
                            color: #fff;
                        }
                        .time-slot-btn:disabled,
                        .time-slot-btn.time-slot-disabled,
                        .time-slot-btn.time-slot-blocked {
                            opacity: 0.6;
                            cursor: not-allowed;
                            text-decoration: line-through;
                            border-color: #dc2626;
                            color: #991b1b;
                        }
                        [data-theme="dark"] .time-slot-btn {
                            background-color: #1e293b;
                            border-color: #334155;
                            color: #e2e8f0;
                        }
                        [data-theme="dark"] .time-slot-btn:hover:not(:disabled) {
                            background-color: #1f2937;
                            border-color: #475569;
                        }
                        [data-theme="dark"] .time-slot-btn.selected {
                            background-color: #0ea5e9;
                            border-color: #0ea5e9;
                            color: #fff;
                        }
                        [data-theme="dark"] .time-slot-btn:disabled,
                        [data-theme="dark"] .time-slot-btn.time-slot-disabled,
                        [data-theme="dark"] .time-slot-btn.time-slot-blocked {
                            opacity: 0.6;
                            cursor: not-allowed;
                            text-decoration: line-through;
                            border-color: #dc2626;
                            color: #fca5a5;
                        }
                        #time-slots-list .form-check {
                            display: none;
                        }
                    </style>

                    

                    <!-- Notes -->
                    <div class="mb-2">
                        <label for="notes" class="form-label fw-bold appointment-label mb-1">Notes</label>
                        <textarea class="form-control form-control-sm" id="notes" name="notes" rows="2" placeholder="Add any relevant notes..." style="font-size: 0.875rem;"></textarea>
                    </div>

                    <!-- New Patient Checkbox -->
                    {{-- <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_new_patient" name="is_new_patient" value="1">
                            <label class="form-check-label" for="is_new_patient">
                                New Patient
                            </label>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Create Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="appointment-details-content">
                    <!-- Appointment details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-purple" id="change-status-btn">
                    <i class="bi bi-arrow-repeat me-1"></i>Change Status
                </button>
                <button type="button" class="btn btn-yellow" id="reschedule-appointment-btn">
                    <i class="bi bi-calendar3 me-1"></i>Reschedule
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none;">
                <h5 class="modal-title text-white">
                    <i class="bi bi-arrow-repeat me-2"></i>Change Appointment Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="status_change_appointment_id">
                <input type="hidden" id="status_change_current_status">

                <!-- Appointment Info -->
                <div class="alert alert-info mb-4" role="alert" style="border-left: 4px solid #3b82f6; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px;">
                    <div style="display: flex; align-items: start; gap: 0.75rem;">
                        <i class="bi bi-info-circle-fill" style="color: #1e40af; font-size: 1.25rem; flex-shrink: 0; margin-top: 0.125rem;"></i>
                        <div style="color: #1e40af;">
                            <strong style="display: block; margin-bottom: 0.25rem;" id="status_change_patient_name">Patient Name</strong>
                            <span style="font-size: 0.9rem;" id="status_change_appointment_info">Appointment details</span>
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Current Status:</label>
                    <div>
                        <span class="badge fs-6 px-3 py-2" id="current_status_badge">Pending</span>
                    </div>
                </div>

                <!-- New Status Selection -->
                <div class="mb-3">
                    <label for="new_status" class="form-label fw-bold">
                        <i class="bi bi-check-circle me-1"></i>Change Status To:
                    </label>
                    <select class="form-select" id="new_status" required>
                        <option value="">Select new status...</option>
                    </select>
                    <small class="form-text text-muted mt-1" id="status_transition_help">
                        Select a new status for this appointment
                    </small>
                </div>

                <!-- Optional Notes (only for Cancelled status) -->
                <div class="mb-3" id="status_notes_container" style="display: none;">
                    <label for="status_change_notes" class="form-label fw-bold">
                        <i class="bi bi-pencil me-1"></i>Notes <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" id="status_change_notes" rows="3"
                              placeholder="Please provide a reason for cancelling this appointment..." maxlength="500"></textarea>
                    <small class="form-text text-muted">This note will be added to the appointment notes.</small>
                </div>

                <!-- Validation Message -->
                <div id="status-validation-message" class="alert" style="display: none;" role="alert"></div>
            </div>
            <div class="modal-footer bg-light" style="gap: 0.75rem; padding: 1.25rem 1.5rem;">
                <button type="button" class="btn btn-primary" id="confirm-status-change-btn" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 600; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none;">
                    <i class="bi bi-check-circle me-1"></i>Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Appointment Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalTitle">Reschedule Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rescheduleForm">
                <div class="modal-body">
                    <input type="hidden" id="reschedule_appointment_id" name="id">

                    <!-- Validation Messages -->
                    <div id="reschedule-validation-messages" class="mb-3" style="display: none;">
                        <!-- Messages will appear here -->
                    </div>

                    <!-- Patient Information (Read-Only) -->
                    <div class="mb-4">
                        <div class="form-label fw-bold">Patient Information</div>
                        <div class="bg-light rounded p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Name:</strong> <span id="reschedule_patient_name">Loading...</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Service:</strong> <span id="reschedule_service_name">Loading...</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <strong>Notes:</strong> <span id="reschedule_notes">None</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time Selection -->
                    <div class="row g-3 mb-3">
                        <!-- Select Date Section -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-check me-2" style="font-size: 1rem;"></i>
                                <label class="form-label fw-bold appointment-label mb-0">Select Date:</label>
                            </div>
                            <div class="date-picker-container appointment-calendar">
                                <div class="calendar-widget" id="reschedule-calendar-widget">
                                    <div class="calendar-header d-flex justify-content-between align-items-center mb-2">
                                        <span style="font-size: 0.8rem;">Date</span>
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 me-2" id="reschedule-prev-month" style="width: 24px; height: 24px; padding: 0 !important;">
                                                <i class="bi bi-chevron-left" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <span id="reschedule-current-month-year" style="font-size: 0.8rem; font-weight: 500;">October 2025</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 ms-2" id="reschedule-next-month" style="width: 24px; height: 24px; padding: 0 !important;">
                                                <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="calendar-grid" id="reschedule-modal-calendar">
                                        <!-- Calendar will be generated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="reschedule_selected_date" name="selected_date">
                            <div class="form-text" style="font-size: 0.7rem; margin-top: 0.5rem;">Click on a date to select it</div>
                        </div>

                        <!-- Select Time Section -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock me-2" style="font-size: 1rem;"></i>
                                    <label class="form-label fw-bold appointment-label mb-0">Select Time:</label>
                                </div>
                                <span id="reschedule-time-selected-status" class="text-muted" style="font-size: 0.8rem;">No time selected</span>
                            </div>
                            <div class="time-slots-container" style="max-height: 300px; overflow-y: auto;">
                                <div class="time-slots-grid" id="reschedule-time-slots-list">
                                    <!-- Time slots will be generated dynamically based on appointment duration and date -->
                                    <div class="text-muted text-center w-100" style="padding: 20px;">
                                        <small>Please select a date to view available time slots</small>
                                    </div>
                                </div>
                                <div id="reschedule-end-time-preview" class="mt-2" style="display: none; font-size: 0.8rem; color: #6c757d;"></div>
                            </div>
                            <div class="mt-2" style="font-size: 0.75rem; color: #6c757d;">
                                <small>Clinic hours: 11:00 AM – 6:00 PM</small>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slot Styles -->
                    <style>
                        #rescheduleModal .time-slots-grid {
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
                            gap: 8px;
                        }
                        #rescheduleModal .time-slots-grid > div:only-child {
                            grid-column: 1 / -1;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-height: 150px;
                        }
                        #rescheduleModal .time-slot-btn {
                            cursor: pointer;
                            border: 1px solid #e2e8f0;
                            border-radius: 8px;
                            padding: 8px 12px;
                            text-align: center;
                            background-color: #fff;
                            transition: all 0.15s ease;
                            font-size: 0.875rem;
                            color: #1e293b;
                        }
                        #rescheduleModal .time-slot-btn:hover:not(:disabled) {
                            background-color: #f1f5f9;
                            border-color: #cbd5e1;
                        }
                        #rescheduleModal .time-slot-btn.selected {
                            background-color: #2196F3;
                            border-color: #2196F3;
                            color: #fff;
                        }
                        #rescheduleModal .time-slot-btn:disabled,
                        #rescheduleModal .time-slot-btn.time-slot-disabled,
                        #rescheduleModal .time-slot-btn.time-slot-blocked {
                            opacity: 0.6;
                            cursor: not-allowed;
                            text-decoration: line-through;
                            border-color: #dc2626;
                            color: #991b1b;
                        }
                        [data-theme="dark"] #rescheduleModal .time-slot-btn {
                            background-color: #1e293b;
                            border-color: #334155;
                            color: #e2e8f0;
                        }
                        [data-theme="dark"] #rescheduleModal .time-slot-btn:hover:not(:disabled) {
                            background-color: #1f2937;
                            border-color: #475569;
                        }
                        [data-theme="dark"] #rescheduleModal .time-slot-btn.selected {
                            background-color: #2196F3;
                            border-color: #2196F3;
                            color: #fff;
                        }
                        [data-theme="dark"] #rescheduleModal .time-slot-btn:disabled,
                        [data-theme="dark"] #rescheduleModal .time-slot-btn.time-slot-disabled,
                        [data-theme="dark"] #rescheduleModal .time-slot-btn.time-slot-blocked {
                            opacity: 0.6;
                            cursor: not-allowed;
                            text-decoration: line-through;
                            border-color: #dc2626;
                            color: #fca5a5;
                        }
                        #rescheduleModal .time-slots-list .form-check {
                            display: none;
                        }
                    </style>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-calendar3 me-1"></i>Reschedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $clinicStart = \Carbon\Carbon::createFromTime(11, 0);
    $clinicEnd = \Carbon\Carbon::createFromTime(18, 0);
    $blockTimeSelectOptions = [];
    for ($time = $clinicStart->copy(); $time->lte($clinicEnd); $time->addMinutes(15)) {
        $blockTimeSelectOptions[$time->format('H:i')] = $time->format('g:i A');
    }
@endphp

<!-- Block Time Modal -->
<div class="modal fade" id="blockTimeModal" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="modal-title fw-bold mb-0" id="blockTimeModalTitle">Block OFF Time</h5>
                    <i class="bi bi-info-circle text-info"
                       data-bs-toggle="tooltip"
                       data-bs-placement="right"
                       data-bs-html="true"
                       title="Going on vacation? Taking some time off? Block off time on your calendar to prevent clients from booking appointments (existing appointments will remain on your calendar)."
                       style="cursor: help; font-size: 1.1rem;"></i>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="blockTimeForm">
                <div class="modal-body" style="max-height: 60vh; overflow-y: auto; padding: 1.5rem;">
                    <input type="hidden" id="block_time_id" name="id">

                    <!-- Block Off Time Section -->
                    <div class="mb-3">
                        <!-- Switchable Tabs -->
                        <div class="btn-group w-100 mb-2" role="group" aria-label="Block time type selection">
                            <input type="radio" class="btn-check" name="block_time_type" id="block_off_time_tab" value="block_off_time" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="block_off_time_tab">
                                Specific Time
                            </label>

                            <input type="radio" class="btn-check" name="block_time_type" id="clinic_closed_tab" value="clinic_closed" autocomplete="off">
                            <label class="btn btn-outline-primary" for="clinic_closed_tab">
                                Clinic Closed
                            </label>
                        </div>

                    <!-- Time Selection (Single Day Mode) -->
                        <div class="row mb-2" id="time_selection_section">
                        <div class="col-6">
                                <label for="block_start_time" class="form-label fw-medium">
                                    <i class="bi bi-clock me-1"></i>Start Time
                                </label>
                            <select class="form-select block-time-select" id="block_start_time" name="start_time" required>
                                <option value="" disabled selected>Select start time</option>
                                @foreach($blockTimeSelectOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                                <label for="block_end_time" class="form-label fw-medium">
                                    <i class="bi bi-clock-fill me-1"></i>End Time
                                </label>
                            <select class="form-select block-time-select" id="block_end_time" name="end_time" required>
                                <option value="" disabled selected>Select end time</option>
                                @foreach($blockTimeSelectOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                    </div>
                    </div>

                    <!-- Date Selection (Single Day Mode) -->
                        <div class="mb-2" id="single_date_section">
                            <label for="block_date" class="form-label fw-medium">
                                <i class="bi bi-calendar3 me-1"></i>Date
                            </label>
                        <input type="date" class="form-control" id="block_date" name="date">
                    </div>

                        <!-- Multi-Day Date Selection (Hidden by default) -->
                    <div class="mb-3" id="multi_day_section" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="block_start_date_input" class="form-label fw-medium">
                                        <i class="bi bi-calendar-event me-1"></i>Start Date
                                    </label>
                                    <input type="date" class="form-control" id="block_start_date_input" name="start_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="block_end_date_input" class="form-label fw-medium">
                                        <i class="bi bi-calendar-event-fill me-1"></i>End Date
                                    </label>
                                    <input type="date" class="form-control" id="block_end_date_input" name="end_date">
                                </div>
                            </div>
                            <div class="alert alert-info border-0 bg-light py-2 mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                <small class="text-muted">The clinic will be marked as closed for the entire day(s) in this date range.</small>
                        </div>
                        </div>
                    </div>

                    <!-- Repeat Section -->
                    <div class="mb-3 pb-2 border-bottom">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="repeat-toggle-btn">
                            <i class="bi bi-arrow-repeat me-1"></i>Repeat
                        </button>
                    </div>

                    <!-- Repeat Options Section (Hidden by default) -->
                    <div class="mb-3 border rounded p-2 bg-light" id="repeat-options-section" style="display: none;">
                        <h6 class="fw-bold mb-2" style="font-size: 0.9rem;">
                            <i class="bi bi-arrow-repeat me-2"></i>Repeat Settings
                        </h6>

                        <!-- Repeat Frequency -->
                        <div class="mb-2">
                            <label for="repeat_frequency" class="form-label fw-bold" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Repeat Frequency</label>
                            <select class="form-select form-select-sm" id="repeat_frequency" name="repeat_frequency">
                                <option value="">Select frequency...</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="custom">Custom (Every N days)</option>
                            </select>
                        </div>

                        <!-- Custom Interval (Hidden by default) -->
                        <div class="mb-2" id="custom-interval-section" style="display: none;">
                            <label for="custom_interval" class="form-label fw-bold" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Every (days)</label>
                            <input type="number" class="form-control form-control-sm" id="custom_interval" name="custom_interval" min="1" max="365" placeholder="e.g., 2 for every 2 days">
                            <div class="form-text" style="font-size: 0.75rem;">Enter the number of days between each repetition</div>
                        </div>

                        <!-- Repeat End Options -->
                        <div class="mb-2">
                            <label class="form-label fw-bold" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Repeat Until</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="repeat_end_type" id="repeat_end_date" value="date" checked>
                                <label class="form-check-label" for="repeat_end_date" style="font-size: 0.85rem;">
                                    Specific Date
                                </label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="repeat_end_type" id="repeat_end_count" value="count">
                                <label class="form-check-label" for="repeat_end_count" style="font-size: 0.85rem;">
                                    Number of Occurrences
                                </label>
                            </div>
                        </div>

                        <!-- Repeat End Date Input -->
                        <div class="mb-2" id="repeat-end-date-section">
                            <label for="repeat_end_date_input" class="form-label fw-bold" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Repeat Until Date</label>
                            <input type="date" class="form-control form-control-sm" id="repeat_end_date_input" name="repeat_end_date">
                            <div class="form-text" style="font-size: 0.75rem;">The last date this closure pattern will repeat (not the closure end date)</div>
                        </div>

                        <!-- Occurrence Count Input -->
                        <div class="mb-2" id="repeat-end-count-section" style="display: none;">
                            <label for="repeat_occurrence_count" class="form-label fw-bold" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Number of Occurrences</label>
                            <input type="number" class="form-control form-control-sm" id="repeat_occurrence_count" name="repeat_occurrence_count" min="1" max="365" placeholder="e.g., 10">
                            <div class="form-text" style="font-size: 0.75rem;">Total number of times this will repeat</div>
                        </div>

                        <!-- Preview Section -->
                        <div class="alert alert-info mt-2 mb-0 py-2" id="repeat-preview" style="display: none; font-size: 0.8rem;">
                            <strong><i class="bi bi-info-circle me-1"></i>Preview:</strong>
                            <div id="repeat-preview-content" class="mt-1"></div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="block_description" class="form-label fw-bold" style="font-size: 0.9rem;">Notes (Reason for blocking)</label>
                        <textarea class="form-control" id="block_description" name="description" rows="3"
                                  placeholder="e.g., Vacation, Holiday, Maintenance" style="font-size: 0.875rem;"></textarea>
                    </div>

                    <input type="hidden" id="block_title" name="title" value="Blocked Time">
                    <input type="hidden" name="type" value="blocked_time">
                    <input type="hidden" name="status" value="blocked">
                    <input type="hidden" name="color" value="#DC2626">

                </div>

                <!-- Footer with Quick Actions and Primary Buttons -->
                <div class="modal-footer border-top bg-light flex-row align-items-center justify-content-between gap-3 px-4 py-3">
                    <div class="quick-actions-wrapper flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-info-circle text-muted me-2"></i>
                            <small class="text-muted fw-semibold">Quick Actions:</small>
                        </div>
                        <div class="d-flex flex-row flex-nowrap gap-2">
                            <button type="button" class="btn btn-orange btn-sm px-3" id="clear-block-off-time-btn" title="Clear specific block off time dates (partial time blocks)">
                                <i class="bi bi-calendar-x me-1"></i>Clear Specific Time
                            </button>
                            <button type="button" class="btn btn-danger btn-sm px-3" id="clear-clinic-closed-btn" title="Clear all future clinic closed days (full day closures only)">
                                <i class="bi bi-calendar-x me-1"></i>Clear Clinic Closed
                             </button>
                        </div>
                    </div>
                    <div class="d-flex gap-2 ms-auto">
                        <button type="submit" class="btn btn-success px-4 align-self-start" id="save-block-time-btn" style="margin-top: 30px;">
                            <i class="bi bi-check-circle me-1"></i><span id="save-block-btn-text">Create</span>
                        </button>
                        <button type="button" class="btn btn-danger px-4" id="delete-block-time-btn" style="display: none;">
                            <i class="bi bi-trash me-1"></i>Remove Blocked Time
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Blocked Time Confirmation Modal -->
<div class="modal fade" id="deleteBlockedTimeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-exclamation-triangle text-white" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Remove Blocked Time</h4>
                    <p class="text-muted mb-0">Are you sure you want to remove this blocked time? This action cannot be undone.</p>
                </div>
                <div class="bg-light rounded p-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-clock text-danger me-2"></i>
                        <span class="fw-medium" id="delete-block-info">Blocked time details will be shown here</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-danger" id="confirm-delete-block-btn">
                    <i class="bi bi-trash me-1"></i>Remove Blocked Time
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Block Time Success Modal -->
<div class="modal fade" id="blockTimeSuccessModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-check-circle-fill text-white" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2" id="blockSuccessTitle">Time Blocked Successfully!</h4>
                    <p class="text-muted mb-0" id="blockSuccessMessage">The time has been blocked and no appointments can be scheduled during this period.</p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Block Time Error Modal -->
<div class="modal fade" id="blockTimeErrorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-circle-fill text-white" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Failed to Block Time</h4>
                    <p class="text-muted mb-0" id="blockErrorMessage">An error occurred while blocking the time. Please try again.</p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clear All Future Blocked Times Confirmation Modal -->
<!-- Clear Clinic Closed Modal -->
<div class="modal fade" id="clearClinicClosedModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-calendar-x text-white" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Clear Clinic Closed Days</h4>
                    <p class="text-muted mb-0">Are you sure you want to delete all future clinic closed days (full day closures only)? This action cannot be undone.</p>
                    <p class="text-warning mt-2 mb-0">
                        <i class="bi bi-info-circle me-1"></i><strong>Note:</strong> Past dates will not be affected. This will only clear full-day closures, not partial block off times.
                    </p>
                </div>
                <div class="bg-light rounded p-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-check text-warning me-2"></i>
                        <span class="fw-medium" id="clinic-closed-count">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-danger" id="confirm-clear-clinic-closed-btn">
                    <i class="bi bi-calendar-x me-1"></i>Clear Clinic Closed
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Block Off Time Confirmation Modal -->
<div class="modal fade" id="clearBlockOffTimeConfirmModal" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Clear Block Off Time
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0" id="clearBlockOffTimeConfirmMessage">Are you sure you want to clear the selected dates? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" id="cancel-confirm-clear-block-off-time-btn">Cancel</button>
                <button type="button" class="btn btn-orange" id="final-confirm-clear-block-off-time-btn">
                    <i class="bi bi-check-circle me-1"></i>Yes, Clear Selected Dates
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Block Off Time Modal -->
<div class="modal fade" id="clearBlockOffTimeModal" tabindex="-1" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Clear Specific Block Off Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted mb-3">Select the specific dates with block off time you want to clear. You can select multiple dates.</p>
                
                <!-- List of block off time dates -->
                <div class="mb-3">
                    <div id="clear-specific-dates-loading" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2 text-muted">Loading dates...</span>
                    </div>
                    <div id="clear-specific-dates-list" style="display: none; max-height: 400px; overflow-y: auto;">
                        <!-- Dates list will be generated by JavaScript -->
                    </div>
                    <div id="clear-specific-no-dates" class="text-center py-4 text-muted" style="display: none;">
                        <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No block off time dates found</p>
                    </div>
                </div>

                <div class="alert alert-info border-0 bg-light py-2 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    <small class="text-muted">Only dates with block off time entries are shown. Past dates are disabled.</small>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-orange" id="confirm-clear-block-off-time-btn" disabled>
                    <i class="bi bi-calendar-x me-1"></i>Clear Selected Dates
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Conflict Modal (Blocked/Closed Time) -->
<div class="modal fade" id="appointmentConflictModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
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
                    <h4 class="fw-bold text-dark mb-2" id="conflictTitle">Time Slot is Blocked or Clinic is Closed</h4>
                    <p class="text-muted mb-0" id="conflictMessage">The selected time slot conflicts with a blocked time or the clinic is closed on this date.</p>
                </div>
                <div class="bg-light rounded p-3 mb-3">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <div><i class="bi bi-calendar-event text-warning me-2"></i><span id="conflictDate">-</span></div>
                        <div><i class="bi bi-clock text-warning me-2"></i><span id="conflictTime">-</span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left-circle me-1"></i>Select Different Time
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Same Procedure Warning Modal -->
<div class="modal fade" id="sameProcedureWarningModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Cannot Book Duplicate Procedure
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Duplicate Procedure Detected</h4>
                    <p class="text-muted mb-3" id="sameProcedureWarningMessage">This patient already has the same procedure booked on this day.</p>
                    <div class="alert alert-danger mb-0" style="background: #fee2e2; border: 1px solid #ef4444;">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Not Allowed:</strong> You cannot book the same procedure twice on the same day for a patient.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1"></i>Understood
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Same Procedure Error Modal (for backend validation) -->
<div class="modal fade" id="sameProcedureErrorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Cannot Book Same Procedure
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Same Procedure Already Booked</h4>
                    <p class="text-muted mb-0" id="sameProcedureErrorMessage">You cannot book the same procedure twice on the same day.</p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1"></i>Understood
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Generic Warning Modal -->
<div class="modal fade" id="genericWarningModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Warning
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <p class="text-muted mb-0" id="genericWarningMessage"></p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Dark Mode Styles for Generic Modals */
[data-theme="dark"] #genericWarningModal .modal-content,
[data-theme="dark"] #genericErrorModal .modal-content,
[data-theme="dark"] #genericInfoModal .modal-content,
[data-theme="dark"] #genericConfirmModal .modal-content {
    background-color: #1e293b !important;
    color: #ffffff !important;
}

[data-theme="dark"] #genericWarningModal .modal-body,
[data-theme="dark"] #genericErrorModal .modal-body,
[data-theme="dark"] #genericInfoModal .modal-body,
[data-theme="dark"] #genericConfirmModal .modal-body {
    background-color: #1e293b !important;
    color: #ffffff !important;
}

[data-theme="dark"] #genericWarningModal .modal-footer,
[data-theme="dark"] #genericErrorModal .modal-footer,
[data-theme="dark"] #genericInfoModal .modal-footer,
[data-theme="dark"] #genericConfirmModal .modal-footer {
    background-color: #1e293b !important;
    border-top: 1px solid #334155 !important;
}

[data-theme="dark"] #genericWarningModal .text-muted,
[data-theme="dark"] #genericErrorModal .text-muted,
[data-theme="dark"] #genericInfoModal .text-muted,
[data-theme="dark"] #genericConfirmModal .text-muted {
    color: #cbd5e1 !important;
}

[data-theme="dark"] #genericWarningModal #genericWarningMessage,
[data-theme="dark"] #genericErrorModal #genericErrorMessage,
[data-theme="dark"] #genericInfoModal #genericInfoMessage,
[data-theme="dark"] #genericConfirmModal #genericConfirmMessage {
    color: #cbd5e1 !important;
}
</style>

@php
    $requestedDay = (int) request()->input('day', 1);
    if ($requestedDay < 1) {
        $requestedDay = 1;
    } elseif ($requestedDay > 31) {
        $requestedDay = 31;
    }

    $initialRangeStart = $startDate->copy()->format('Y-m-d');
    $initialRangeEnd = $endDate->copy()->format('Y-m-d');
@endphp

<style>
    .calendar-container.calendar-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.6;
        transition: opacity 0.2s ease-in-out;
    }

    .calendar-container.calendar-loading::after {
        content: 'Loading...';
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.3);
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
</style>

<script>
// Generic Warning Modal Function
function showWarningModal(message) {
    const modal = new bootstrap.Modal(document.getElementById('genericWarningModal'));
    document.getElementById('genericWarningMessage').textContent = message;
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date({{ $currentYear }}, {{ $currentMonth - 1 }}, {{ $requestedDay }});

    // Get view from URL parameter, default to 'month'
    const urlParams = new URLSearchParams(window.location.search);
    let currentView = urlParams.get('view') || 'month';

    let appointments = @json($appointments);
    let blockedTimes = @json($blockedTimes);

    const initialRangeStart = new Date('{{ $initialRangeStart }}T00:00:00');
    const initialRangeEnd = new Date('{{ $initialRangeEnd }}T23:59:59');
    let loadedRangeStart = initialRangeStart;
    let loadedRangeEnd = initialRangeEnd;
    let filteredAppointments = [];
    let allCalendarItems = [];
    let isLoadingData = false;
    
    // Server time synchronization variables - declared early to avoid initialization errors
    let serverTimeData = null;
    let serverTimeOffset = 0; // Offset between server time and client time (in ms)

    const calendarContainer = document.querySelector('.calendar-container');

    const CALENDAR_DATA_ENDPOINT = '/admin/appointment/calendar-data';

    const normalizeBlockedTimes = (items = []) => {
        return items
            .filter(Boolean)
            .map(blockedTime => ({
                ...blockedTime,
                status: 'blocked',
                reason_for_visit: blockedTime.title,
                notes: blockedTime.notes
            }));
    };

    function mergeAppointments(incoming = []) {
        if (!Array.isArray(incoming)) {
            return;
        }

        const map = new Map(appointments.map(apt => [`apt-${apt.id}`, apt]));

        incoming.forEach(apt => {
            if (!apt) {
                return;
            }
            if (!apt.status || apt.status === '') {
                apt.status = 'Pending';
            }
            map.set(`apt-${apt.id}`, apt);
        });

        appointments = Array.from(map.values());
    }

    function mergeBlockedTimes(incoming = []) {
        if (!Array.isArray(incoming)) {
            return;
        }

        const normalized = normalizeBlockedTimes(incoming);
        const map = new Map(blockedTimes.map(bt => [`bt-${bt.id}`, bt]));

        normalized.forEach(bt => {
            map.set(`bt-${bt.id}`, bt);
        });

        blockedTimes = Array.from(map.values());
    }

    function getMonthLabel(date) {
        return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    }

    function getMonthAppointmentCount(date) {
        const month = date.getMonth();
        const year = date.getFullYear();

        return appointments.reduce((count, apt) => {
            if (!apt || !apt.start_datetime) {
                return count;
            }

            const start = parseLocalDateTime(apt.start_datetime);
            if (!start) {
                return count;
            }

            if (start.getMonth() === month && start.getFullYear() === year) {
                const statusLower = (apt.status || 'pending').toLowerCase().trim();
                // Exclude blocked and cancelled appointments from count
                if (statusLower !== 'blocked' && statusLower !== 'cancelled') {
                    return count + 1;
                }
            }

            return count;
        }, 0);
    }

    function updateAppointmentSummary() {
        const summaryEl = document.getElementById('appointment-summary');
        if (!summaryEl) {
            return;
        }

        const total = getMonthAppointmentCount(currentDate);
        const monthLabel = getMonthLabel(currentDate);
        const suffix = total === 1 ? '' : 's';
        summaryEl.textContent = `${total} Appointment${suffix} are set for ${monthLabel}`;
    }

    function rebuildCalendarItems() {
        filteredAppointments = appointments.filter(apt => {
            // Filter out cancelled appointments - they should not appear in calendar
            const status = (apt.status || '').toString().toLowerCase().trim();
            if (status === 'cancelled') {
                return false;
            }

            if (!apt.start_datetime || !apt.end_datetime) return true;

            const aptStart = parseLocalDateTime(apt.start_datetime);
            const aptEnd = parseLocalDateTime(apt.end_datetime);
            if (!aptStart || !aptEnd) return true;

            // Check if appointment overlaps with any blocked time
            const overlaps = blockedTimes.some(bt => {
                if (!bt.start_datetime || !bt.end_datetime) return false;

                const btStart = parseLocalDateTime(bt.start_datetime);
                const btEnd = parseLocalDateTime(bt.end_datetime);
                if (!btStart || !btEnd) return false;

                // Check if it's a full-day closure (starts at 00:00 and ends at 23:59 or later)
                const isFullDayClosure = btStart.getHours() === 0 && btStart.getMinutes() === 0 &&
                                         (btEnd.getHours() === 23 && btEnd.getMinutes() >= 59);

                // If full-day closure, check if appointment is on the same date
                if (isFullDayClosure) {
                    const sameDate = aptStart.toDateString() === btStart.toDateString();
                    if (sameDate) {
                        console.log('Filtering appointment on clinic closed day:', apt.start_datetime, 'Blocked:', bt.start_datetime);
                    }
                    return sameDate;
                }

                // For partial blocks, check for overlap: appointment starts before blocked ends AND appointment ends after blocked starts
                const hasOverlap = aptStart < btEnd && aptEnd > btStart;
                if (hasOverlap) {
                    console.log('Filtering overlapping appointment:', apt.start_datetime, 'Blocked:', bt.start_datetime);
                }
                return hasOverlap;
            });

            return !overlaps;
        });

        console.log('Filtered appointments:', filteredAppointments.length, 'out of', appointments.length);
        
        // Filter out past blocked times
        const now = typeof getServerTime === 'function' ? getServerTime() : new Date();
        const activeBlockedTimes = blockedTimes.filter(bt => {
            if (!bt || !bt.start_datetime || !bt.end_datetime) return false;
            const btEnd = parseLocalDateTime(bt.end_datetime);
            if (!btEnd || isNaN(btEnd.getTime())) return false;
            return btEnd >= now;
        });
        
        allCalendarItems = [...filteredAppointments, ...activeBlockedTimes];
        updateAppointmentSummary();
    }

    function setLoadingState(state) {
        isLoadingData = state;
        if (calendarContainer) {
            calendarContainer.classList.toggle('calendar-loading', state);
        }
    }

    function formatDateParam(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function getRangeForView(date, view) {
        const target = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        let rangeStart;
        let rangeEnd;

        if (view === 'month') {
            rangeStart = new Date(target.getFullYear(), target.getMonth() - 1, 1);
            rangeEnd = new Date(target.getFullYear(), target.getMonth() + 2, 0);
        } else if (view === 'week') {
            rangeStart = new Date(target);
            rangeStart.setDate(rangeStart.getDate() - rangeStart.getDay());
            rangeEnd = new Date(rangeStart);
            rangeEnd.setDate(rangeStart.getDate() + 6);
        } else { // day view
            rangeStart = new Date(target);
            rangeEnd = new Date(target);
        }

        rangeStart.setHours(0, 0, 0, 0);
        rangeEnd.setHours(23, 59, 59, 999);

        return { start: rangeStart, end: rangeEnd };
    }

    async function ensureDataForRange(rangeStart, rangeEnd) {
        const needsEarlierData = rangeStart.getTime() < loadedRangeStart.getTime();
        const needsLaterData = rangeEnd.getTime() > loadedRangeEnd.getTime();

        if (!needsEarlierData && !needsLaterData) {
            return;
        }

        const params = new URLSearchParams({
            start: formatDateParam(rangeStart),
            end: formatDateParam(rangeEnd)
        });

        const response = await fetch(`${CALENDAR_DATA_ENDPOINT}?${params.toString()}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch calendar data.');
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || 'Unable to load calendar data.');
        }

        mergeAppointments(data.appointments || []);
        mergeBlockedTimes(data.blocked_times || []);

        if (needsEarlierData) {
            loadedRangeStart = new Date(rangeStart);
        }

        if (needsLaterData) {
            loadedRangeEnd = new Date(rangeEnd);
        }
    }

    // Real-time update functions
    function refreshAppointments(data) {
        if (data && data.appointments && Array.isArray(data.appointments)) {
            mergeAppointments(data.appointments);
            rebuildCalendarItems();
            generateCalendar();
        } else if (data && data.deleted_ids && Array.isArray(data.deleted_ids)) {
            // Remove deleted appointments
            data.deleted_ids.forEach(id => {
                appointments = appointments.filter(apt => apt.id !== id);
            });
            rebuildCalendarItems();
            generateCalendar();
        } else {
            // Full refresh
            loadDataForCurrentView();
        }
    }

    function updateAppointmentInCalendar(appointmentData) {
        if (!appointmentData || !appointmentData.id) {
            console.warn('[Real-Time] Invalid appointment data:', appointmentData);
            return;
        }
        
        console.log('[Real-Time] Updating appointment in calendar:', appointmentData);
        
        // Ensure status is set
        if (!appointmentData.status || appointmentData.status === '') {
            appointmentData.status = 'Pending';
        }
        
        // Use mergeAppointments to properly merge the data
        mergeAppointments([appointmentData]);
        
        // Rebuild calendar items and regenerate view
        rebuildCalendarItems();
        generateCalendar();
        
        console.log('[Real-Time] Appointment updated successfully. New status:', appointmentData.status);
    }

    function removeAppointmentFromCalendar(appointmentId) {
        appointments = appointments.filter(apt => apt.id !== appointmentId);
        rebuildCalendarItems();
        generateCalendar();
    }

    function loadAppointments() {
        loadDataForCurrentView();
    }

    // Function to refresh blocked times
    function refreshBlockedTimes(blockedTimeData) {
        if (blockedTimeData) {
            // Normalize blocked time data
            const normalized = normalizeBlockedTimes([blockedTimeData]);
            mergeBlockedTimes(normalized);
            rebuildCalendarItems();
            generateCalendar();
        } else {
            // Full refresh
            loadDataForCurrentView();
        }
    }

    // Function to remove blocked time from calendar
    function removeBlockedTimeFromCalendar(blockedTimeId) {
        blockedTimes = blockedTimes.filter(bt => bt.id !== blockedTimeId);
        rebuildCalendarItems();
        generateCalendar();
    }

    // Expose functions to global scope for real-time updates
    window.updateAppointmentInCalendar = updateAppointmentInCalendar;
    window.refreshAppointments = refreshAppointments;
    window.removeAppointmentFromCalendar = removeAppointmentFromCalendar;
    window.loadAppointments = loadAppointments;
    window.refreshBlockedTimes = refreshBlockedTimes;
    window.removeBlockedTimeFromCalendar = removeBlockedTimeFromCalendar;

    async function loadDataForCurrentView() {
        const { start, end } = getRangeForView(currentDate, currentView);
        const needsLoad = start.getTime() < loadedRangeStart.getTime() || end.getTime() > loadedRangeEnd.getTime();

        if (needsLoad) {
            setLoadingState(true);
        }

        try {
            if (needsLoad) {
                await ensureDataForRange(start, end);
            }
            rebuildCalendarItems();
        } catch (error) {
            console.error('Error loading calendar data:', error);
            showValidationMessage(error.message || 'Error loading calendar data. Please try again.', 'error');
        } finally {
            if (needsLoad) {
                setLoadingState(false);
            }
        }
    }

    function updateUrlParams() {
        const url = new URL(window.location.href);
        url.searchParams.set('month', currentDate.getMonth() + 1);
        url.searchParams.set('year', currentDate.getFullYear());
        url.searchParams.set('day', currentDate.getDate());
        url.searchParams.set('view', currentView);
        window.history.replaceState({}, '', url);
    }

    blockedTimes = normalizeBlockedTimes(blockedTimes);
    rebuildCalendarItems();

    // Server time synchronization - CRITICAL for fault tolerance
    // (serverTimeData and serverTimeOffset already declared above)

    // Function to fetch and sync server time
    async function syncServerTime() {
        try {
            const response = await fetch('/admin/appointment/server-time', {
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
                
                // Rebuild calendar items to filter out past blocked times with accurate server time
                rebuildCalendarItems();
            }
        } catch (error) {
            console.error('Error syncing server time:', error);
            // Fall back to client time, but server-side validation will catch errors
            serverTimeOffset = 0;
        }
    }

    // Function to get current server time as Date object
    function getServerTime() {
        // Check if serverTimeData exists and is initialized (safe check for real-time events)
        if (typeof serverTimeData !== 'undefined' && serverTimeData) {
            // Calculate server time: client time + offset
            const serverTimestampMs = (serverTimeData.server_timestamp * 1000) + (Date.now() - (serverTimeData.server_timestamp * 1000) + serverTimeOffset);
            return new Date(serverTimestampMs);
        }
        // Fallback to client time if server time not synced yet
        return new Date();
    }

    // CRITICAL: Validate and fix date manipulation on page load
    async function validateAndFixDate() {
        // Wait for server time to sync first
        await syncServerTime();

        if (!serverTimeData) {
            console.warn('Server time not synced, cannot validate date');
            return;
        }

        // Get server's actual date
        const serverTime = getServerTime();
        const serverYear = serverTime.getFullYear();
        const serverMonth = serverTime.getMonth() + 1; // JavaScript months are 0-indexed
        const serverDay = serverTime.getDate();

        // Get displayed date from URL or currentDate
        const urlParams = new URLSearchParams(window.location.search);
        const displayedMonth = parseInt(urlParams.get('month')) || currentDate.getMonth() + 1;
        const displayedYear = parseInt(urlParams.get('year')) || currentDate.getFullYear();

        // Create server date and displayed date for comparison
        const serverDate = new Date(serverYear, serverMonth - 1, serverDay);
        const displayedDate = new Date(displayedYear, displayedMonth - 1, 1);

        // Calculate difference in days
        const daysDiff = Math.round((displayedDate - serverDate) / (1000 * 60 * 60 * 24));

        const MAX_FUTURE_DAYS = 365 * 2; // Match server-side 2 year future window
        const MAX_PAST_DAYS = 365; // Match server-side 1 year past window

        // Guard against dates outside the supported window
        if (daysDiff > MAX_FUTURE_DAYS) {
            console.warn('Requested date beyond allowed future range - forcing reload to server date', {
                displayed_month: displayedMonth,
                displayed_year: displayedYear,
                server_month: serverMonth,
                server_year: serverYear,
                days_diff: daysDiff,
                max_future_days: MAX_FUTURE_DAYS
            });

            const url = new URL(window.location.href);
            url.searchParams.delete('month');
            url.searchParams.delete('year');
            url.searchParams.delete('day');
            const view = urlParams.get('view');
            if (view) {
                url.searchParams.set('view', view);
            }

            showWarningModal('Selected date is too far in the future. Showing the current month instead.');
            setTimeout(() => {
                window.location.href = url.toString();
            }, 1500);
            return;
        }

        if (daysDiff < -MAX_PAST_DAYS) {
            console.warn('Requested date beyond allowed past range - forcing reload to server date', {
                displayed_month: displayedMonth,
                displayed_year: displayedYear,
                server_month: serverMonth,
                server_year: serverYear,
                days_diff: daysDiff,
                max_past_days: MAX_PAST_DAYS
            });

            const url = new URL(window.location.href);
            url.searchParams.delete('month');
            url.searchParams.delete('year');
            url.searchParams.delete('day');
            const view = urlParams.get('view');
            if (view) {
                url.searchParams.set('view', view);
            }

            showWarningModal('Selected date is too far in the past. Showing the current month instead.');
            setTimeout(() => {
                window.location.href = url.toString();
            }, 1500);
            return;
        }
    }

    // Sync server time on page load and validate date
    validateAndFixDate().then(async () => {
        updateUrlParams();
        await loadDataForCurrentView();
        generateCalendar();
    }).catch(async (error) => {
        console.error('Error validating date:', error);
        await loadDataForCurrentView();
        generateCalendar();
    });

    // Re-sync server time periodically (every 5 minutes) and before critical operations
    setInterval(syncServerTime, 5 * 60 * 1000);
    
    // Periodic cleanup to remove past blocked times
    function cleanupPastBlockedTimes() {
        const now = typeof getServerTime === 'function' ? getServerTime() : new Date();
        const beforeCount = blockedTimes.length;
        blockedTimes = blockedTimes.filter(bt => {
            if (!bt || !bt.start_datetime || !bt.end_datetime) return false;
            const btEnd = parseLocalDateTime(bt.end_datetime);
            if (!btEnd || isNaN(btEnd.getTime())) return false;
            return btEnd >= now;
        });
        if (beforeCount > blockedTimes.length) {
            console.log('[Admin Calendar] Cleaned up', beforeCount - blockedTimes.length, 'past blocked times');
            rebuildCalendarItems();
        }
    }
    
    // Run cleanup every minute
    setInterval(cleanupPastBlockedTimes, 60000);
    // Also run cleanup after initial server time sync
    setTimeout(cleanupPastBlockedTimes, 10000);

    // Debug: Check appointments data
    console.log('Appointments loaded:', appointments);
    console.log('Blocked times loaded:', blockedTimes);

    // Helper function to reload page while preserving the current month and year
    function reloadWithCurrentMonth() {
        const month = currentDate.getMonth() + 1; // JavaScript months are 0-indexed
        const year = currentDate.getFullYear();
        const url = new URL(window.location.href);
        url.searchParams.set('month', month);
        url.searchParams.set('year', year);
        url.searchParams.set('day', currentDate.getDate());
        window.location.href = url.toString();
    }

    // Helper function to parse datetime strings as LOCAL time (not UTC)
    function parseLocalDateTime(datetimeStr) {
        // Handle null, undefined, or non-string values
        if (!datetimeStr || typeof datetimeStr !== 'string') {
            console.warn('Invalid datetime string:', datetimeStr);
            return null;
        }

        try {
            // Split the datetime string (format: YYYY-MM-DD HH:mm:ss)
            const parts = datetimeStr.split(' ');
            if (parts.length !== 2) {
                console.warn('Invalid datetime format:', datetimeStr);
                return null;
            }

            const [datePart, timePart] = parts;
            const [year, month, day] = datePart.split('-').map(Number);
            const [hours, minutes, seconds] = timePart.split(':').map(Number);

            // Validate parsed values
            if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hours) || isNaN(minutes)) {
                console.warn('Invalid datetime values:', datetimeStr);
                return null;
            }

            // Create date object with local timezone (not UTC)
            // Note: month is 0-indexed in JavaScript Date
            return new Date(year, month - 1, day, hours, minutes, seconds || 0);
        } catch (error) {
            console.error('Error parsing datetime:', datetimeStr, error);
            return null;
        }
    }

    // Initialize calendar view button
    setActiveButton(currentView);
    // Calendar generation happens in validateAndFixDate() promise chain above

    // View toggle functionality
    document.querySelectorAll('.view-toggle').forEach(button => {
        button.addEventListener('click', async function() {
            if (this.classList.contains('active') || isLoadingData) {
                return;
            }

            document.querySelectorAll('.view-toggle').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-secondary');
            });

            this.classList.add('active');
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary');

            currentView = this.dataset.view;
            updateUrlParams();

            await loadDataForCurrentView();
            generateCalendar();
        });
    });

    // Ensure Month button is active on page load
    function setActiveButton(view) {
        document.querySelectorAll('.view-toggle').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-secondary');
        });

        const activeBtn = document.getElementById(view + '-btn');
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.classList.remove('btn-outline-secondary');
            activeBtn.classList.add('btn-primary');
        }
    }

    // Navigation buttons
    document.getElementById('prev-period').addEventListener('click', () => navigatePeriod(-1));

    document.getElementById('next-period').addEventListener('click', () => navigatePeriod(1));

    const todayButton = document.getElementById('today-button');
    if (todayButton) {
        todayButton.addEventListener('click', async function() {
            if (isLoadingData) {
                return;
            }
            const serverNow = getServerTime();
            currentDate = new Date(serverNow.getFullYear(), serverNow.getMonth(), serverNow.getDate());

            updateUrlParams();
            await loadDataForCurrentView();
            setActiveButton(currentView);
            generateCalendar();
        });
    }


    // Appointment form handling
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveAppointment();
    });

    document.getElementById('blockTimeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveBlockTime();
    });

    // Repeat functionality
    let isRepeatEnabled = false;

    // Toggle repeat options section
    document.getElementById('repeat-toggle-btn').addEventListener('click', function() {
        const repeatSection = document.getElementById('repeat-options-section');
        isRepeatEnabled = !isRepeatEnabled;

        if (isRepeatEnabled) {
            repeatSection.style.display = 'block';
            this.classList.add('btn-primary');
            this.classList.remove('btn-outline-secondary');
            this.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>Repeat <i class="bi bi-check-circle ms-1"></i>';
        } else {
            repeatSection.style.display = 'none';
            this.classList.remove('btn-primary');
            this.classList.add('btn-outline-secondary');
            this.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>Repeat';
            // Reset repeat fields
            document.getElementById('repeat_frequency').value = '';
            document.getElementById('custom_interval').value = '';
            document.getElementById('repeat_end_date_input').value = '';
            document.getElementById('repeat_occurrence_count').value = '';
            document.getElementById('repeat_end_date').checked = true;
            document.getElementById('repeat-preview').style.display = 'none';
        }
    });

    // Handle repeat frequency change
    document.getElementById('repeat_frequency').addEventListener('change', function() {
        const customIntervalSection = document.getElementById('custom-interval-section');
        if (this.value === 'custom') {
            customIntervalSection.style.display = 'block';
        } else {
            customIntervalSection.style.display = 'none';
            document.getElementById('custom_interval').value = '';
        }
        updateRepeatPreview();
    });

    // Handle custom interval change
    document.getElementById('custom_interval').addEventListener('input', function() {
        updateRepeatPreview();
    });

    // Handle repeat end type change
    document.querySelectorAll('input[name="repeat_end_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const endDateSection = document.getElementById('repeat-end-date-section');
            const endCountSection = document.getElementById('repeat-end-count-section');

            if (this.value === 'date') {
                endDateSection.style.display = 'block';
                endCountSection.style.display = 'none';
            } else {
                endDateSection.style.display = 'none';
                endCountSection.style.display = 'block';
            }
            updateRepeatPreview();
        });
    });

    // Update preview when end date or count changes
    document.getElementById('repeat_end_date_input').addEventListener('change', updateRepeatPreview);
    document.getElementById('repeat_occurrence_count').addEventListener('input', updateRepeatPreview);

    // Update preview when date or time changes
    document.getElementById('block_date').addEventListener('change', function() {
        if (isRepeatEnabled) {
            updateRepeatPreview();
        }
    });
    document.getElementById('block_start_time').addEventListener('change', function() {
        if (isRepeatEnabled) {
            updateRepeatPreview();
        }
    });
    document.getElementById('block_end_time').addEventListener('change', function() {
        if (isRepeatEnabled) {
            updateRepeatPreview();
        }
    });
    document.getElementById('block_start_date_input').addEventListener('change', function() {
        updateRepeatButtonVisibility();
        if (isRepeatEnabled) {
            updateRepeatPreview();
        }
    });
    document.getElementById('block_end_date_input').addEventListener('change', function() {
        updateRepeatButtonVisibility();
        if (isRepeatEnabled) {
            updateRepeatPreview();
        }
    });

    // Helper function to check if clinic closed tab is selected
    function isClinicClosedSelected() {
        const clinicClosedTab = document.getElementById('clinic_closed_tab');
        return clinicClosedTab && clinicClosedTab.checked;
    }

    // Function to update repeat button visibility based on date inputs
    function updateRepeatButtonVisibility() {
        const repeatToggleBtn = document.getElementById('repeat-toggle-btn');
        const repeatToggleContainer = repeatToggleBtn.parentElement;
        const isMultiple = isClinicClosedSelected();

        if (isMultiple) {
            // For Clinic Closed tab, show repeat button only when both dates are filled
            const startDate = document.getElementById('block_start_date_input').value;
            const endDate = document.getElementById('block_end_date_input').value;
            
            if (startDate && endDate) {
                repeatToggleContainer.style.display = 'block';
            } else {
                repeatToggleContainer.style.display = 'none';
                // Also hide repeat options if open
                const repeatOptionsSection = document.getElementById('repeat-options-section');
                if (repeatOptionsSection) {
                    repeatOptionsSection.style.display = 'none';
                    isRepeatEnabled = false;
                    repeatToggleBtn.classList.remove('btn-primary');
                    repeatToggleBtn.classList.add('btn-outline-secondary');
                    repeatToggleBtn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>Repeat';
                }
            }
        } else {
            // For Specific Time tab, always show repeat button
            repeatToggleContainer.style.display = 'block';
        }
    }

    // Function to update repeat preview
    function updateRepeatPreview() {
        const previewDiv = document.getElementById('repeat-preview');
        const previewContent = document.getElementById('repeat-preview-content');
        const isMultipleDays = isClinicClosedSelected();

        const frequency = document.getElementById('repeat_frequency').value;
        const endType = document.querySelector('input[name="repeat_end_type"]:checked').value;
        const repeatEndDate = document.getElementById('repeat_end_date_input').value;
        const occurrenceCount = document.getElementById('repeat_occurrence_count').value;
        const customInterval = document.getElementById('custom_interval').value;

        if (!frequency) {
            previewDiv.style.display = 'none';
            return;
        }

        let closureStartDate, closureEndDate;

        if (isMultipleDays) {
            const startDate = document.getElementById('block_start_date_input').value;
            const endDate = document.getElementById('block_end_date_input').value;

            if (!startDate || !endDate) {
                previewDiv.style.display = 'none';
                return;
            }

            closureStartDate = new Date(startDate + 'T00:00:00');
            closureEndDate = new Date(endDate + 'T23:59:00');
                } else {
            const blockDate = document.getElementById('block_date').value;
            const startTime = document.getElementById('block_start_time').value;
            const endTime = document.getElementById('block_end_time').value;

            if (!blockDate || !startTime || !endTime) {
                previewDiv.style.display = 'none';
                return;
            }

            closureStartDate = new Date(blockDate + 'T' + startTime);
            closureEndDate = new Date(blockDate + 'T' + endTime);
        }

        // Calculate repeat end date/time
        const repeatEndDateTime = endType === 'date' && repeatEndDate ? new Date(repeatEndDate + 'T23:59:59') : null;
        const count = endType === 'count' ? parseInt(occurrenceCount) : null;

        if (endType === 'date' && !repeatEndDate) {
            previewDiv.style.display = 'none';
            return;
        }

        if (endType === 'count' && !occurrenceCount) {
            previewDiv.style.display = 'none';
            return;
        }

        // Generate repeat occurrences
        const repeatOccurrences = generateRepeatDateRanges(
            closureStartDate,
            closureEndDate,
            frequency,
            customInterval,
            repeatEndDateTime,
            count
        );

        if (repeatOccurrences.length === 0) {
            previewDiv.style.display = 'none';
            return;
        }

        // Calculate preview text
        let previewText = '';
        if (endType === 'date') {
            previewText = `Will repeat ${repeatOccurrences.length} occurrence(s) until ${repeatEndDate}.`;
        } else {
            const lastOccurrence = repeatOccurrences[repeatOccurrences.length - 1];
            const lastDateStr = isMultipleDays
                ? formatDate(lastOccurrence.end)
                : formatDate(lastOccurrence.start);
            previewText = `Will repeat ${repeatOccurrences.length} occurrence(s). Last occurrence ends: ${lastDateStr}.`;
        }

        // Show first few occurrences
        const firstFew = repeatOccurrences.slice(0, 5);
        let occurrencesText = firstFew.map(occ => {
            if (isMultipleDays) {
                return `${formatDate(occ.start)} to ${formatDate(occ.end)}`;
            } else {
                return formatDate(occ.start);
        }
        }).join(', ');

        if (repeatOccurrences.length > 5) {
            occurrencesText += ` ... and ${repeatOccurrences.length - 5} more`;
        }

        previewContent.innerHTML = `<div class="fw-bold mb-1">${previewText}</div><div class="small">First few occurrences: ${occurrencesText}</div>`;
        previewDiv.style.display = 'block';
    }

    // Helper function to generate repeat dates
    function generateRepeatDates(startDate, frequency, customInterval, endDate, count) {
        const dates = [];
        let currentDate = new Date(startDate);
        let iteration = 0;
        const maxIterations = count || 1000; // Safety limit

        while (iteration < maxIterations) {
            dates.push(new Date(currentDate));

            if (endDate && currentDate >= endDate) {
                break;
            }

            if (count && dates.length >= count) {
                break;
            }

            // Calculate next date based on frequency
            switch (frequency) {
                case 'daily':
                    currentDate.setDate(currentDate.getDate() + 1);
                    break;
                case 'weekly':
                    currentDate.setDate(currentDate.getDate() + 7);
                    break;
                case 'monthly':
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    break;
                case 'custom':
                    const interval = parseInt(customInterval) || 1;
                    currentDate.setDate(currentDate.getDate() + interval);
                    break;
            }

            iteration++;
        }

        return dates;
    }

    // Helper function to format date
    function formatDate(date) {
        return date.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        }) + ' ' + date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    // Toggle between single day and multi-day mode using tabs
    document.querySelectorAll('input[name="block_time_type"]').forEach(radio => {
        radio.addEventListener('change', function(e) {
            const isMultiple = this.value === 'clinic_closed';
            const timeSection = document.getElementById('time_selection_section');
            const singleSection = document.getElementById('single_date_section');
            const multiSection = document.getElementById('multi_day_section');
            const blockDateField = document.getElementById('block_date');
            const startTimeField = document.getElementById('block_start_time');
            const endTimeField = document.getElementById('block_end_time');
            const repeatToggleBtn = document.getElementById('repeat-toggle-btn');
            const repeatToggleContainer = repeatToggleBtn.parentElement; // Get the parent div

            if (isMultiple) {
                timeSection.style.display = 'none';
                singleSection.style.display = 'none';
                multiSection.style.display = 'block';
                blockDateField.removeAttribute('required');
                startTimeField.removeAttribute('required');
                endTimeField.removeAttribute('required');
                // Show Repeat button when clinic closed is selected (will be toggled based on date inputs)
                updateRepeatButtonVisibility();
                // Also hide repeat options if open (will be shown when repeat is enabled)
                const repeatOptionsSection = document.getElementById('repeat-options-section');
                if (repeatOptionsSection && !isRepeatEnabled) {
                    repeatOptionsSection.style.display = 'none';
                }
            } else {
                timeSection.style.display = 'flex';
                singleSection.style.display = 'block';
                multiSection.style.display = 'none';
                blockDateField.setAttribute('required', 'required');
                startTimeField.setAttribute('required', 'required');
                endTimeField.setAttribute('required', 'required');
                // Show Repeat button when block off time is selected
                repeatToggleContainer.style.display = 'block';
                // Clear multi-day date inputs
                document.getElementById('block_start_date_input').value = '';
                document.getElementById('block_end_date_input').value = '';
            }

            // Update repeat preview if repeat is enabled
            if (isRepeatEnabled) {
                updateRepeatPreview();
    }
        });
    });

    // Patient search functionality
    let selectedPatientIndex = -1;
    const patientSearch = document.getElementById('patient_search');

    patientSearch.addEventListener('input', function() {
        const query = this.value;
        selectedPatientIndex = -1; // Reset selection
        if (query.length >= 0) {
            searchPatients(query);
        }
    });

    // Keyboard navigation for patient search dropdown
    patientSearch.addEventListener('keydown', function(e) {
        const resultsContainer = document.getElementById('patient-results');
        const resultItems = resultsContainer?.querySelectorAll('.patient-result-item');

        if (!resultItems || resultItems.length === 0) {
            // If no results, allow Enter to submit form
            if (e.key === 'Enter' || e.keyCode === 13) {
                const form = document.getElementById('appointmentForm');
                if (form) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            }
            return;
        }

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedPatientIndex = Math.min(selectedPatientIndex + 1, resultItems.length - 1);
            updatePatientSearchSelection(resultItems, selectedPatientIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedPatientIndex = Math.max(selectedPatientIndex - 1, -1);
            updatePatientSearchSelection(resultItems, selectedPatientIndex);
        } else if (e.key === 'Enter' && selectedPatientIndex >= 0) {
            e.preventDefault();
            resultItems[selectedPatientIndex].click();
        } else if (e.key === 'Enter' && this.value.trim().length >= 2) {
            // If Enter pressed with search term but no selection, trigger search
            e.preventDefault();
            const query = this.value.trim();
            searchPatients(query);
        } else if (e.key === 'Escape') {
            e.preventDefault();
            resultsContainer.style.display = 'none';
            selectedPatientIndex = -1;
        }
    });

    // Helper function to update patient search selection highlighting
    function updatePatientSearchSelection(items, index) {
        items.forEach((item, i) => {
            if (i === index) {
                item.style.backgroundColor = '#e7f1ff';
                item.style.cursor = 'pointer';
                item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            } else {
                item.style.backgroundColor = '';
                item.style.cursor = 'default';
            }
        });
    }

    // Show all patients when input is focused or clicked
    patientSearch.addEventListener('focus', function() {
        if (this.value.length === 0) {
            searchPatients('');
        }
    });

    patientSearch.addEventListener('click', function() {
        if (this.value.length === 0) {
            searchPatients('');
        }
    });

    // Close patient search results when clicking outside
    document.addEventListener('click', function(e) {
        const patientSearch = document.getElementById('patient_search');
        const patientResults = document.getElementById('patient-results');

        if (!patientSearch.contains(e.target) && !patientResults.contains(e.target)) {
            patientResults.style.display = 'none';
            selectedPatientIndex = -1;
        }
    });

    // Handle Enter key in appointment form inputs
    $(document).on('keydown', '#appointmentForm input, #appointmentForm select, #appointmentForm textarea', function(e) {
        // Ctrl+Enter or Cmd+Enter in textarea submits form
        if (e.target.tagName === 'TEXTAREA' && (e.ctrlKey || e.metaKey) && (e.key === 'Enter' || e.keyCode === 13)) {
            e.preventDefault();
            const form = document.getElementById('appointmentForm');
            if (form) {
                form.dispatchEvent(new Event('submit'));
            }
            return;
        }

        // Allow normal Enter in textareas (adds new line)
        if (e.target.tagName === 'TEXTAREA') {
            return;
        }

        // If Enter is pressed in patient search with results, let that handler take precedence
        if (e.target.id === 'patient_search') {
            const resultsContainer = document.getElementById('patient-results');
            const resultItems = resultsContainer?.querySelectorAll('.patient-result-item');
            if (resultItems && resultItems.length > 0) {
                return; // Let patient search keyboard navigation handle it
            }
        }

        // For service dropdown - allow Enter to submit if value is selected
        if (e.target.id === 'service_name' && (e.key === 'Enter' || e.keyCode === 13)) {
            const serviceSelect = e.target;
            if (serviceSelect.value) {
                e.preventDefault();
                const form = document.getElementById('appointmentForm');
                if (form) {
                    form.dispatchEvent(new Event('submit'));
                }
                return;
            }
        }

        // For custom time input - allow Enter to submit
        

        // For other inputs and selects, submit form on Enter
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            const form = document.getElementById('appointmentForm');
            if (form) {
                form.dispatchEvent(new Event('submit'));
            }
        }
    });

    // Custom time toggle
    // Custom time removed

    // Service selection handler
    document.getElementById('service_name').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const serviceInfo = document.getElementById('service-info');
        const serviceDescription = document.getElementById('service-description');
        const serviceDuration = document.getElementById('service-duration');

        if (selectedOption.value) {
            const duration = selectedOption.getAttribute('data-duration');
            const description = selectedOption.getAttribute('data-description');

            if (description) {
                serviceDescription.textContent = description;
            }

            if (duration) {
                serviceDuration.textContent = `Duration: ${duration} minutes`;
            }

            serviceInfo.style.display = 'block';

            // Generate time slots based on service duration and selected date
            const selectedDate = document.getElementById('selected_date').value;
            generateTimeSlots(parseInt(duration, 10), selectedDate);
            updateTimeSlotAvailability(selectedDate);
        } else {
            serviceInfo.style.display = 'none';
            // Clear slots
            const timeSlotsList = document.getElementById('time-slots-list');
            if (timeSlotsList) {
                timeSlotsList.innerHTML = '<div class="text-muted text-center w-100" style="padding: 20px; grid-column: 1 / -1; display: flex; justify-content: center; align-items: center; min-height: 150px;"><small>Please select a service and date to view available time slots</small></div>';
            }
        }
    });

    // Time slot selection is now handled in generateTimeSlots function

    // Update end-time preview whenever service or date changes
    document.getElementById('selected_date').addEventListener('change', updateEndTimePreview);

    function updateEndTimePreview() {
        const preview = document.getElementById('end-time-preview');
        if (!preview) return;

        const serviceSelect = document.getElementById('service_name');
        const opt = serviceSelect && serviceSelect.options[serviceSelect.selectedIndex];
        const duration = opt ? parseInt(opt.getAttribute('data-duration') || '0', 10) : 0;

        if (!duration) {
            preview.style.display = 'none';
            return;
        }

        const selectedDate = document.getElementById('selected_date').value;
        const checked = document.querySelector('input[name="time_slot"]:checked');
        let startStr = '';
        if (checked) {
            startStr = checked.value.split('-')[0];
        }

        if (!selectedDate || !startStr) {
            preview.style.display = 'none';
            return;
        }

        const [y, m, d] = selectedDate.split('-').map(Number);
        const [hh, mm] = startStr.split(':').map(Number);
        if (Number.isNaN(hh) || Number.isNaN(mm)) {
            preview.style.display = 'none';
            return;
        }

        const start = new Date(y, m - 1, d, hh, mm, 0);
        const end = new Date(start.getTime() + duration * 60000);
        const endLabel = end.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        preview.textContent = `Ends at ${endLabel}`;
        preview.style.display = 'block';
    }

    // Modal calendar functionality
    let modalCurrentDate = new Date();

    // Initialize modal calendar when modal is shown
    document.getElementById('appointmentModal').addEventListener('shown.bs.modal', function() {
        // Reset modal date to current date
        modalCurrentDate = new Date();
        generateModalCalendar();
        // Clear patient search when modal opens
        document.getElementById('patient_search').value = '';
        document.getElementById('patient_id').value = '';
        document.getElementById('patient-results').style.display = 'none';

        // Clear time slot selection
        document.querySelectorAll('input[name="time_slot"]').forEach(radio => {
            radio.checked = false;
        });
        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        updateTimeSelectedStatus('');

        // Reset form for new appointment
        resetAppointmentForm();
    });

    // Handle "Add New Patient" button click
    document.getElementById('add-new-patient').addEventListener('click', function(e) {
        e.preventDefault();
        // Store reference to appointment modal
        const appointmentModalEl = document.getElementById('appointmentModal');
        const appointmentModal = bootstrap.Modal.getInstance(appointmentModalEl);
        
        // Close appointment modal if open
        if (appointmentModal) {
            appointmentModal.hide();
        }
        
        // Open user management modal
        const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
        addUserModal.show();
    });

    // Listen for successful patient creation from user management modal
    $(document).on('ajaxSuccess', function(event, xhr, settings) {
        // Check if this is the add user form submission for a patient
        if (settings.url && settings.url.includes('/admin/account-management') && settings.method === 'POST') {
            try {
                const response = typeof xhr.responseJSON !== 'undefined' ? xhr.responseJSON : JSON.parse(xhr.responseText);
                
                // Only auto-fill if the created user is a patient (role_id = 3)
                if (response.status === 'success' && response.user && response.is_patient) {
                    const user = response.user;
                    
                    // Auto-fill patient in appointment form
                    const patientSearch = document.getElementById('patient_search');
                    const patientId = document.getElementById('patient_id');
                    
                    if (patientSearch && patientId) {
                        // Get the full name from user info if available
                        const patientName = user.name || user.username;
                        
                        patientSearch.value = patientName;
                        patientId.value = user.id;
                        
                        // Hide patient results if visible
                        const patientResults = document.getElementById('patient-results');
                        if (patientResults) {
                            patientResults.style.display = 'none';
                        }
                        
                        // Reopen appointment modal if it was closed
                        const appointmentModalEl = document.getElementById('appointmentModal');
                        if (appointmentModalEl && !appointmentModalEl.classList.contains('show')) {
                            const appointmentModal = new bootstrap.Modal(appointmentModalEl);
                            appointmentModal.show();
                        }
                    }
                }
            } catch (e) {
                console.error('Error processing patient creation response:', e);
            }
        }
    });

    // Generate calendar on page load
    document.addEventListener('DOMContentLoaded', function() {
        generateModalCalendar();
    });

    // Generate time slots according to selected service duration and clinic hours
    function generateTimeSlots(durationMinutes, selectedDate) {
        const timeSlotsList = document.getElementById('time-slots-list');
        if (!timeSlotsList) return;

        timeSlotsList.innerHTML = '';
        updateTimeSelectedStatus('');

        if (!durationMinutes || !selectedDate) {
            timeSlotsList.innerHTML = '<div class="text-muted text-center w-100" style="padding: 20px; grid-column: 1 / -1; display: flex; justify-content: center; align-items: center; min-height: 150px;"><small>Please select a service and date to view available time slots</small></div>';
            return;
        }

        const [year, month, day] = selectedDate.split('-').map(Number);
        const clinicOpenHour = 11; // 11:00
        const clinicCloseHour = 18; // 18:00

        let slotIndex = 1;
        for (let hour = clinicOpenHour; hour < clinicCloseHour; hour++) {
            for (let minute = 0; minute < 60; minute += 15) { // 15-minute granularity
                const start = new Date(year, month - 1, day, hour, minute, 0);
                const end = new Date(start.getTime() + durationMinutes * 60000);

                // Skip if end exceeds 18:00
                if (end.getHours() > clinicCloseHour || (end.getHours() === clinicCloseHour && end.getMinutes() > 0)) {
                    continue;
                }

                // Build value like HH:MM-HH:MM
                const val = `${String(start.getHours()).padStart(2,'0')}:${String(start.getMinutes()).padStart(2,'0')}-${String(end.getHours()).padStart(2,'0')}:${String(end.getMinutes()).padStart(2,'0')}`;

                // Label (start only)
                const startLabel = start.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

                // Create button instead of radio
                const slotBtn = document.createElement('button');
                slotBtn.type = 'button';
                slotBtn.className = 'time-slot-btn';
                slotBtn.setAttribute('data-time-value', val);
                slotBtn.textContent = startLabel;
                
                // Create hidden radio input for form submission
                const hiddenRadio = document.createElement('input');
                hiddenRadio.type = 'radio';
                hiddenRadio.name = 'time_slot';
                hiddenRadio.id = `slot${slotIndex}`;
                hiddenRadio.value = val;
                hiddenRadio.style.display = 'none';
                
                slotBtn.addEventListener('click', function() {
                    // Remove selected class from all buttons
                    document.querySelectorAll('.time-slot-btn').forEach(btn => {
                        btn.classList.remove('selected');
                    });
                    
                    // Uncheck all radios
                    document.querySelectorAll('input[name="time_slot"]').forEach(radio => {
                        radio.checked = false;
                    });
                    
                    // Select this button and radio
                    this.classList.add('selected');
                    hiddenRadio.checked = true;
                    
                    // Update status
                    updateTimeSelectedStatus(startLabel);
                    
                        if (typeof updateEndTimePreview === 'function') updateEndTimePreview();
                    });
                
                timeSlotsList.appendChild(slotBtn);
                timeSlotsList.appendChild(hiddenRadio);
                slotIndex++;
            }
        }

        if (slotIndex === 1) {
            timeSlotsList.innerHTML = '<div class="text-muted text-center w-100" style="padding: 20px; grid-column: 1 / -1; display: flex; justify-content: center; align-items: center; min-height: 150px;"><small>No available time slots for this date</small></div>';
        }
    }

    // Update time selected status
    function updateTimeSelectedStatus(timeLabel) {
        const statusEl = document.getElementById('time-selected-status');
        if (statusEl) {
            if (timeLabel) {
                statusEl.textContent = timeLabel;
                statusEl.classList.remove('text-muted');
                statusEl.style.color = '#0ea5e9';
            } else {
                statusEl.textContent = 'No time selected';
                statusEl.classList.add('text-muted');
                statusEl.style.color = '';
            }
        }
    }

    // Function to disable blocked time slots
    function updateTimeSlotAvailability(selectedDate) {
        // If we have a selected service, regenerate slots first for this date
        const serviceSelect = document.getElementById('service_name');
        if (serviceSelect && serviceSelect.value) {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const duration = parseInt(selectedOption.getAttribute('data-duration') || '0', 10);
            generateTimeSlots(duration, selectedDate);
        }
        if (!selectedDate) {
            // If no date selected, enable all time slots
            document.querySelectorAll('.time-slot-btn').forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('time-slot-blocked', 'time-slot-disabled');
            });
            document.querySelectorAll('input[name="time_slot"]').forEach(radio => {
                radio.disabled = false;
            });
            return;
        }

        // Parse selected date
        const [year, month, day] = selectedDate.split('-').map(Number);
        const selectedDateObj = new Date(year, month - 1, day);

        // Find blocked times on the selected date
        const blockedTimesOnDate = blockedTimes.filter(blockedTime => {
            const btStart = parseLocalDateTime(blockedTime.start_datetime);
            return btStart && btStart.toDateString() === selectedDateObj.toDateString();
        });

        // Find booked appointments on the selected date (exclude cancelled and blocked)
        const bookedOnDate = appointments.filter(apt => {
            const aptDate = parseLocalDateTime(apt.start_datetime);
            if (!aptDate) return false;
            const statusLower = (apt.status || 'pending').toLowerCase();
            return aptDate.toDateString() === selectedDateObj.toDateString() && statusLower !== 'cancelled';
        });

        // Check each time slot button
        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            const timeValue = btn.getAttribute('data-time-value');
            if (!timeValue || timeValue === 'custom') {
                return; // Don't disable custom time option
            }

            const [startTime, endTime] = timeValue.split('-');
            const [startHours, startMinutes] = startTime.split(':').map(Number);
            const [endHours, endMinutes] = endTime.split(':').map(Number);

            const slotStart = new Date(year, month - 1, day, startHours, startMinutes);
            const slotEnd = new Date(year, month - 1, day, endHours, endMinutes);

            // Check if this time slot conflicts with any blocked time
            const isBlocked = blockedTimesOnDate.some(blockedTime => {
                const blockedStart = parseLocalDateTime(blockedTime.start_datetime);
                const blockedEnd = parseLocalDateTime(blockedTime.end_datetime);

                // Check if slot overlaps with blocked time
                return (slotStart < blockedEnd && slotEnd > blockedStart);
            });

            if (isBlocked) {
                btn.disabled = true;
                btn.classList.add('time-slot-blocked', 'time-slot-disabled');
                btn.title = 'This time slot is blocked';
                // Also disable the associated radio
                const radio = document.querySelector(`input[name="time_slot"][value="${timeValue}"]`);
                if (radio) {
                radio.disabled = true;
                radio.checked = false;
                }
                return;
            }

            // Check overlap with booked appointments
            const isBooked = bookedOnDate.some(apt => {
                const aptStart = parseLocalDateTime(apt.start_datetime);
                const aptEnd = parseLocalDateTime(apt.end_datetime);
                if (!aptStart || !aptEnd) return false;
                return (slotStart < aptEnd && slotEnd > aptStart);
            });

            if (isBooked) {
                btn.disabled = true;
                btn.classList.add('time-slot-blocked', 'time-slot-disabled');
                btn.title = 'This time slot is already booked';
                // Also disable the associated radio
                const radio = document.querySelector(`input[name="time_slot"][value="${timeValue}"]`);
                if (radio) {
                radio.disabled = true;
                radio.checked = false;
                }
                return;
            }

            // If not blocked or booked, ensure it's enabled
            btn.disabled = false;
            btn.classList.remove('time-slot-blocked', 'time-slot-disabled');
            btn.title = '';
            // Also enable the associated radio
            const radio = document.querySelector(`input[name="time_slot"][value="${timeValue}"]`);
            if (radio) {
            radio.disabled = false;
            }
        });
    }

    document.getElementById('prev-month').addEventListener('click', function() {
        try {
        modalCurrentDate.setMonth(modalCurrentDate.getMonth() - 1);
        generateModalCalendar();
        } catch (error) {
            console.error('Error navigating to previous month:', error);
            modalCurrentDate = new Date();
            generateModalCalendar();
        }
    });

    document.getElementById('next-month').addEventListener('click', function() {
        try {
        modalCurrentDate.setMonth(modalCurrentDate.getMonth() + 1);
        generateModalCalendar();
        } catch (error) {
            console.error('Error navigating to next month:', error);
            modalCurrentDate = new Date();
            generateModalCalendar();
        }
    });

    async function navigatePeriod(direction) {
        if (isLoadingData) {
            return;
        }

        console.log('Navigate Period:', direction, 'Current View:', currentView, 'Current Date:', currentDate);

        // Adjust current date based on active view
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() + direction);
            currentDate.setDate(1);
        } else if (currentView === 'week') {
            currentDate.setDate(currentDate.getDate() + (direction * 7));
        } else if (currentView === 'day') {
            currentDate.setDate(currentDate.getDate() + direction);
        }

        updateUrlParams();
        await loadDataForCurrentView();
        generateCalendar();
    }

    function generateCalendar() {
        // Ensure the correct button is active
        setActiveButton(currentView);

        // Get all calendar views
        const monthCalendar = document.getElementById('month-calendar');
        const weekCalendar = document.getElementById('week-calendar');
        const dayCalendar = document.getElementById('day-calendar');

        // IMMEDIATELY hide all views first (synchronously, no delay)
        // This prevents any view from being visible when switching
        if (monthCalendar) {
            monthCalendar.classList.add('d-none');
            monthCalendar.classList.remove('fade-out', 'fade-in');
            monthCalendar.style.setProperty('display', 'none', 'important');
            monthCalendar.style.visibility = 'hidden';
        }
        if (weekCalendar) {
            weekCalendar.classList.add('d-none');
            weekCalendar.classList.remove('fade-out', 'fade-in');
            weekCalendar.style.setProperty('display', 'none', 'important');
            weekCalendar.style.visibility = 'hidden';
        }
        if (dayCalendar) {
            dayCalendar.classList.add('d-none');
            dayCalendar.classList.remove('fade-out', 'fade-in');
            dayCalendar.style.setProperty('display', 'none', 'important');
            dayCalendar.style.visibility = 'hidden';
        }

        // Small delay before showing new view for smooth transition
        setTimeout(() => {
            // Show only the selected view
            if (currentView === 'month') {
                generateMonthView();
            } else if (currentView === 'week') {
                generateWeekView();
            } else if (currentView === 'day') {
                generateDayView();
            }
        }, 50); // Small delay for smooth transition
    }

    function generateMonthView() {
        // Explicitly hide week and day views (double protection)
        const weekCalendar = document.getElementById('week-calendar');
        const dayCalendar = document.getElementById('day-calendar');
        if (weekCalendar) {
            weekCalendar.classList.add('d-none');
            weekCalendar.style.display = 'none';
        }
        if (dayCalendar) {
            dayCalendar.classList.add('d-none');
            dayCalendar.style.display = 'none';
        }

        const calendarGrid = document.getElementById('month-calendar');
        if (!calendarGrid) {
            console.error('Calendar grid element not found!');
            return;
        }

        console.log('Generating month view for:', currentDate);
        // Ensure week and day are still hidden
        if (weekCalendar) {
            weekCalendar.style.setProperty('display', 'none', 'important');
        }
        if (dayCalendar) {
            dayCalendar.style.setProperty('display', 'none', 'important');
        }
        calendarGrid.classList.remove('d-none');
        calendarGrid.style.removeProperty('display');
        calendarGrid.style.visibility = 'visible';
        calendarGrid.classList.add('fade-in');
        calendarGrid.innerHTML = '';
        
        // Remove fade-in class after animation completes
        setTimeout(() => {
            calendarGrid.classList.remove('fade-in');
        }, 300);

        // Add day headers
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        days.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'calendar-header';
            dayHeader.textContent = day;
            calendarGrid.appendChild(dayHeader);
        });


        // Get first day of month and number of days
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        // Generate calendar days
        console.log('Generating 42 calendar days...');
        for (let i = 0; i < 42; i++) {
            const cellDate = new Date(startDate);
            cellDate.setDate(startDate.getDate() + i);

            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';

            if (cellDate.getMonth() !== currentDate.getMonth()) {
                dayElement.classList.add('other-month');
            }

            if (isToday(cellDate)) {
                dayElement.classList.add('today');
            }

            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = cellDate.getDate();
            dayElement.appendChild(dayNumber);

            // Add appointments for this day
            addAppointmentsToDay(dayElement, cellDate);

            calendarGrid.appendChild(dayElement);
        }

        console.log('Calendar days generated. Total children:', calendarGrid.children.length);

        // Update period display
        const periodElement = document.getElementById('current-period');
        if (periodElement) {
            periodElement.textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        }
    }

    function generateWeekView() {
        // Explicitly hide month and day views (double protection)
        const monthCalendar = document.getElementById('month-calendar');
        const dayCalendar = document.getElementById('day-calendar');
        if (monthCalendar) {
            monthCalendar.classList.add('d-none');
            monthCalendar.style.setProperty('display', 'none', 'important');
            monthCalendar.style.visibility = 'hidden';
        }
        if (dayCalendar) {
            dayCalendar.classList.add('d-none');
            dayCalendar.style.setProperty('display', 'none', 'important');
            dayCalendar.style.visibility = 'hidden';
        }
        

        const calendarGrid = document.getElementById('week-calendar');
        if (monthCalendar) {
            monthCalendar.style.setProperty('display', 'none', 'important');
        }
        if (dayCalendar) {
            dayCalendar.style.setProperty('display', 'none', 'important');
        }
        calendarGrid.classList.remove('d-none');
        calendarGrid.style.removeProperty('display');
        calendarGrid.style.visibility = 'visible';
        calendarGrid.classList.add('fade-in');
        calendarGrid.innerHTML = '';
        
        // Remove fade-in class after animation completes
        setTimeout(() => {
            calendarGrid.classList.remove('fade-in');
            // Final check: ensure month and day views are still hidden
            if (monthCalendar) {
                monthCalendar.classList.add('d-none');
                monthCalendar.style.setProperty('display', 'none', 'important');
                monthCalendar.style.visibility = 'hidden';
            }
            if (dayCalendar) {
                dayCalendar.classList.add('d-none');
                dayCalendar.style.setProperty('display', 'none', 'important');
                dayCalendar.style.visibility = 'hidden';
            }
        }, 300);

        // Get start of week (Sunday)
        const startOfWeek = new Date(currentDate);
        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());

        // Add day headers with dates
        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(startOfWeek);
            dayDate.setDate(startOfWeek.getDate() + i);

            const dayHeader = document.createElement('div');
            dayHeader.className = 'week-header';
            dayHeader.innerHTML = `
                <div>${dayDate.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                <div>${dayDate.getDate()}</div>
            `;
            calendarGrid.appendChild(dayHeader);
        }

        // Add day content areas
        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(startOfWeek);
            dayDate.setDate(startOfWeek.getDate() + i);

            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';

            if (isToday(dayDate)) {
                dayElement.classList.add('today');
            }

            addAppointmentsToDay(dayElement, dayDate);

            calendarGrid.appendChild(dayElement);
        }

        // Update period display
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);

        document.getElementById('current-period').textContent =
            `${startOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${endOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
    }

    function generateDayView() {
        // Explicitly hide month and week views (double protection)
        const monthCalendar = document.getElementById('month-calendar');
        const weekCalendar = document.getElementById('week-calendar');
        if (monthCalendar) {
            monthCalendar.classList.add('d-none');
            monthCalendar.style.setProperty('display', 'none', 'important');
            monthCalendar.style.visibility = 'hidden';
        }
        if (weekCalendar) {
            weekCalendar.classList.add('d-none');
            weekCalendar.style.setProperty('display', 'none', 'important');
            weekCalendar.style.visibility = 'hidden';
        }

        const calendarGrid = document.getElementById('day-calendar');
        // Double-check month and week are hidden
        if (monthCalendar) {
            monthCalendar.style.setProperty('display', 'none', 'important');
        }
        if (weekCalendar) {
            weekCalendar.style.setProperty('display', 'none', 'important');
        }
        calendarGrid.classList.remove('d-none');
        calendarGrid.style.removeProperty('display');
        calendarGrid.style.visibility = 'visible';
        calendarGrid.classList.add('fade-in');
        calendarGrid.innerHTML = '';
        
        // Remove fade-in class after animation completes
        setTimeout(() => {
            calendarGrid.classList.remove('fade-in');
            // Final check: ensure month and week views are still hidden
            if (monthCalendar) {
                monthCalendar.classList.add('d-none');
                monthCalendar.style.setProperty('display', 'none', 'important');
                monthCalendar.style.visibility = 'hidden';
            }
            if (weekCalendar) {
                weekCalendar.classList.add('d-none');
                weekCalendar.style.setProperty('display', 'none', 'important');
                weekCalendar.style.visibility = 'hidden';
            }
        }, 300);

        // Add day header
        const dayHeader = document.createElement('div');
        dayHeader.className = 'day-header';
        dayHeader.textContent = currentDate.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        calendarGrid.appendChild(dayHeader);


        // Generate time slots (8 AM to 8 PM)
        for (let hour = 8; hour <= 20; hour++) {
            const timeSlot = document.createElement('div');
            timeSlot.className = 'time-slot';

            const timeLabel = document.createElement('span');
            timeLabel.className = 'time-label';
            timeLabel.textContent = formatHour(hour);

            const timeContent = document.createElement('div');
            timeContent.className = 'time-content';

            // Add appointments for this hour
            const hourAppointments = allCalendarItems.filter(apt => {
                // Filter out cancelled appointments
                const status = (apt.status || '').toString().toLowerCase().trim();
                if (status === 'cancelled') {
                    return false;
                }
                const aptDate = parseLocalDateTime(apt.start_datetime);
                return aptDate.toDateString() === currentDate.toDateString() &&
                       aptDate.getHours() === hour;
            });

            // Sort appointments by start time within the hour
            hourAppointments.sort((a, b) => {
                const timeA = parseLocalDateTime(a.start_datetime);
                const timeB = parseLocalDateTime(b.start_datetime);
                return timeA - timeB;
            });

            hourAppointments.forEach(apt => {
                const aptElement = document.createElement('div');
                const statusLower = (apt.status || 'pending').toLowerCase();
                aptElement.className = `appointment-item ${statusLower}`;

                // Format time
                const aptTime = parseLocalDateTime(apt.start_datetime);
                const timeString = aptTime.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });

                // Format end time
                const endTime = parseLocalDateTime(apt.end_datetime);
                const endTimeString = endTime.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });

            // Check if this is a blocked time
            if (apt.status === 'blocked') {
                const blockTitle = apt.reason_for_visit || 'Blocked Time';
                // Check if it's a full day closure (00:00 to 23:59)
                const isFullDayClosure = aptTime.getHours() === 0 && aptTime.getMinutes() === 0 &&
                                         endTime.getHours() === 23 && endTime.getMinutes() === 59;

                if (isFullDayClosure) {
                    aptElement.textContent = blockTitle === 'Clinic Closed' ? 'Clinic Closed' : blockTitle;
                    aptElement.title = `Clinic Closed: ${apt.notes || 'No appointments available'}`;
                } else {
                    aptElement.textContent = `${timeString}-${endTimeString} ${blockTitle}`;
                    aptElement.title = `Blocked Time: ${apt.notes || 'No reason provided'}`;
                }
                
                // Add click handler for blocked times - show blocked time details
                aptElement.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (apt.id) {
                        editAppointment(apt.id);
                    } else {
                        console.error('Blocked time has no ID:', apt);
                    }
                });
            } else {
                // Get patient name from the loaded relationship
                let patientName = 'Unknown Patient';
                if (apt.patient && apt.patient.info) {
                    const info = apt.patient.info;
                    patientName = `${info.first_name} ${info.last_name}`.trim();
                } else if (apt.patient && apt.patient.name) {
                    patientName = apt.patient.name;
                }

                aptElement.textContent = `${timeString}-${endTimeString} ${patientName}`;
                aptElement.title = `${patientName} - ${apt.service ? apt.service.service_name : 'No Service'} - ${apt.status || 'Pending'}`;

                // Add strikethrough for completed, cancelled, or missed appointments
                const statusLower = (apt.status || 'pending').toLowerCase();
                if (statusLower === 'completed' || statusLower === 'cancelled' || statusLower === 'missed') {
                    aptElement.style.textDecoration = 'line-through';
                    aptElement.style.opacity = '0.7';
                }

                // Ensure cancelled status is properly set
                if (statusLower === 'cancelled') {
                    aptElement.classList.remove('confirmed', 'pending');
                    aptElement.classList.add('cancelled');
                }
                
                // Add click handler for appointments - show appointment details
                aptElement.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (apt.id) {
                        editAppointment(apt.id);
                    } else {
                        console.error('Appointment has no ID:', apt);
                    }
                });
                }
                timeContent.appendChild(aptElement);
            });

            timeSlot.appendChild(timeLabel);
            timeSlot.appendChild(timeContent);
            calendarGrid.appendChild(timeSlot);
        }

        // Update period display
        document.getElementById('current-period').textContent =
            currentDate.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
    }

    function addAppointmentsToDay(dayElement, date) {
        const dayAppointments = allCalendarItems.filter(apt => {
            // Filter out cancelled appointments
            const status = (apt.status || '').toString().toLowerCase().trim();
            if (status === 'cancelled') {
                return false;
            }

            const aptDate = parseLocalDateTime(apt.start_datetime);
            // Skip appointments with invalid dates
            if (!aptDate) return false;
            return aptDate.toDateString() === date.toDateString();
        });

        // Sort appointments by start time
        dayAppointments.sort((a, b) => {
            const timeA = parseLocalDateTime(a.start_datetime);
            const timeB = parseLocalDateTime(b.start_datetime);
            // Handle null dates
            if (!timeA || !timeB) return 0;
            return timeA - timeB;
        });

        // Check if day is fully booked
        const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        const isFullyBooked = checkIfDayIsFullyBooked(dateStr, dayAppointments);
        
        // Store appointments data for modal
        dayElement.dataset.dayAppointments = JSON.stringify(dayAppointments);
        dayElement.dataset.date = dateStr;
        
        // Add fully booked indicator
        if (isFullyBooked) {
            dayElement.classList.add('fully-booked');
            const fullyBookedIndicator = document.createElement('div');
            fullyBookedIndicator.className = 'fully-booked-indicator';
            fullyBookedIndicator.innerHTML = '<i class="bi bi-x-circle"></i> Fully Booked';
            dayElement.appendChild(fullyBookedIndicator);
        }
        
        // Show only first 3 appointments
        const maxVisible = 3;
        const visibleAppointments = dayAppointments.slice(0, maxVisible);
        const hiddenCount = Math.max(0, dayAppointments.length - maxVisible);

        visibleAppointments.forEach(apt => {
            const aptElement = document.createElement('div');
            const statusLower = (apt.status || 'pending').toLowerCase();
            aptElement.className = `appointment-item ${statusLower}`;

            // Format time
            const aptTime = parseLocalDateTime(apt.start_datetime);
            const timeString = aptTime.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Format end time
            const endTime = parseLocalDateTime(apt.end_datetime);
            const endTimeString = endTime.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Check if this is a blocked time
            if (apt.status === 'blocked') {
                const blockTitle = apt.reason_for_visit || 'Blocked Time';
                // Check if it's a full day closure (00:00 to 23:59)
                const isFullDayClosure = aptTime.getHours() === 0 && aptTime.getMinutes() === 0 &&
                                         endTime.getHours() === 23 && endTime.getMinutes() === 59;

                if (isFullDayClosure) {
                    aptElement.textContent = blockTitle === 'Clinic Closed' ? 'Clinic Closed' : blockTitle;
                    aptElement.title = `Clinic Closed: ${apt.notes || 'No appointments available'}`;
                } else {
                    aptElement.textContent = `${timeString}-${endTimeString} ${blockTitle}`;
                    aptElement.title = `Blocked Time: ${apt.notes || 'No reason provided'}`;
                }
                
                // Add click handler for blocked times - show blocked time details
                aptElement.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (apt.id) {
                        editAppointment(apt.id);
                    } else {
                        console.error('Blocked time has no ID:', apt);
                    }
                });
            } else {
            // Get patient name from the loaded relationship
            let patientName = 'Unknown Patient';
            if (apt.patient && apt.patient.info) {
                const info = apt.patient.info;
                patientName = `${info.first_name} ${info.last_name}`.trim();
            } else if (apt.patient && apt.patient.name) {
                patientName = apt.patient.name;
            }

            aptElement.textContent = `${timeString}-${endTimeString} ${patientName}`;
            aptElement.title = `${patientName} - ${apt.service ? apt.service.service_name : 'No Service'} - ${apt.status || 'Pending'}`;

            // Add strikethrough for completed, cancelled, or missed appointments
            const statusLower = (apt.status || 'pending').toLowerCase();
            if (statusLower === 'completed' || statusLower === 'cancelled' || statusLower === 'missed') {
                aptElement.style.textDecoration = 'line-through';
                aptElement.style.opacity = '0.7';
            }

            // Ensure cancelled status is properly set
            if (statusLower === 'cancelled') {
                aptElement.classList.remove('confirmed', 'pending');
                aptElement.classList.add('cancelled');
            }
            
            // Add click handler for appointments - show appointment details
            aptElement.addEventListener('click', (e) => {
                e.stopPropagation();
                if (apt.id) {
                    editAppointment(apt.id);
                } else {
                    console.error('Appointment has no ID:', apt);
                }
            });
            }
            dayElement.appendChild(aptElement);
        });
        
        // Add "X more" indicator if there are more appointments
        if (hiddenCount > 0) {
            const moreIndicator = document.createElement('div');
            moreIndicator.className = 'event-more-indicator';
            moreIndicator.innerHTML = `<span class="more-text">${hiddenCount} more</span>`;
            moreIndicator.dataset.date = dateStr;
            moreIndicator.addEventListener('click', (e) => {
                e.stopPropagation();
                showDayAppointmentsModal(dateStr, dayAppointments);
            });
            dayElement.appendChild(moreIndicator);
        }
        
        // Add click handler for the day to show all appointments
        dayElement.addEventListener('click', function(e) {
            // Don't trigger if clicking on an appointment item or more indicator
            if (e.target.closest('.appointment-item') || e.target.closest('.event-more-indicator') || e.target.closest('.fully-booked-indicator')) {
                return;
            }
            
            if (dayAppointments.length > 0) {
                showDayAppointmentsModal(dateStr, dayAppointments);
            }
        });
    }

    function formatHour(hour) {
        if (hour === 0) return '12 AM';
        if (hour < 12) return `${hour} AM`;
        if (hour === 12) return '12 PM';
        return `${hour - 12} PM`;
    }

    function isToday(date) {
        // CRITICAL: Use server time to determine "today" (fault tolerant)
        const serverNow = getServerTime();
        const serverToday = new Date(serverNow);
        serverToday.setHours(0, 0, 0, 0);
        const dateToCheck = new Date(date);
        dateToCheck.setHours(0, 0, 0, 0);
        return dateToCheck.toDateString() === serverToday.toDateString();
    }
    
    // Function to check if a day is fully booked (11:00 AM - 6:00 PM)
    function checkIfDayIsFullyBooked(dateStr, appointments) {
        // Generate all 15-minute time slots from 11:00 AM to 6:00 PM
        const timeSlots = [];
        for (let hour = 11; hour <= 18; hour++) {
            for (let minute = 0; minute < 60; minute += 15) {
                if (hour === 18 && minute > 0) break; // Stop at 6:00 PM
                timeSlots.push({ hour: hour, minute: minute });
            }
        }
        
        // Check each time slot for availability (assuming 30-minute default duration)
        const [year, month, day] = dateStr.split('-').map(Number);
        const defaultDuration = 30; // minutes
        
        for (let i = 0; i < timeSlots.length; i++) {
            const slot = timeSlots[i];
            const slotStart = new Date(year, month - 1, day, slot.hour, slot.minute);
            const slotEnd = new Date(slotStart.getTime() + defaultDuration * 60000);
            
            // Check if this slot is available
            let isAvailable = true;
            
            // Check against appointments
            for (let j = 0; j < appointments.length; j++) {
                const apt = appointments[j];
                if (!apt.start_datetime || !apt.end_datetime) continue;
                
                const aptStart = parseLocalDateTime(apt.start_datetime);
                const aptEnd = parseLocalDateTime(apt.end_datetime);
                if (!aptStart || !aptEnd) continue;
                
                // Check for overlap (excluding cancelled and blocked appointments)
                const status = (apt.status || '').toLowerCase();
                if (status !== 'cancelled' && status !== 'blocked' && slotStart < aptEnd && slotEnd > aptStart) {
                    isAvailable = false;
                    break;
                }
            }
            
            // If any slot is available, day is not fully booked
            if (isAvailable) {
                return false;
            }
        }
        
        // All slots are booked
        return true;
    }
    
    // Function to show day appointments modal
    function showDayAppointmentsModal(dateStr, appointments) {
        const [year, month, day] = dateStr.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const formattedDate = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Filter out blocked/closed appointments from count (they are break/vacation times, not appointments)
        const actualAppointments = appointments.filter(apt => {
            const status = (apt.status || '').toLowerCase();
            return status !== 'blocked';
        });
        
        // Sort appointments by time
        appointments.sort((a, b) => {
            const timeA = parseLocalDateTime(a.start_datetime);
            const timeB = parseLocalDateTime(b.start_datetime);
            if (!timeA || !timeB) return 0;
            return timeA - timeB;
        });
        
        let modalContent = `
            <div class="day-appointments-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-event me-2"></i>${formattedDate}
                </h5>
                <p class="text-muted mb-0">${actualAppointments.length} appointment${actualAppointments.length !== 1 ? 's' : ''}</p>
            </div>
            <div class="day-appointments-list">
        `;
        
        if (appointments.length === 0) {
            modalContent += '<div class="text-center text-muted py-4">No appointments scheduled for this day.</div>';
        } else {
            appointments.forEach(apt => {
                const aptStart = parseLocalDateTime(apt.start_datetime);
                const aptEnd = parseLocalDateTime(apt.end_datetime);
                
                if (!aptStart) return;
                
                const timeStr = aptStart.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                
                let endTimeStr = '';
                if (aptEnd) {
                    endTimeStr = aptEnd.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                }
                
                let status = (apt.status || 'pending').toLowerCase();
                const isBlocked = status === 'blocked';
                const isFullDayClosure = isBlocked && aptStart && aptEnd &&
                    aptStart.getHours() === 0 && aptStart.getMinutes() === 0 &&
                    aptEnd.getHours() === 23 && aptEnd.getMinutes() === 59;

                if (isBlocked) {
                    const blockLabel = isFullDayClosure
                        ? 'Clinic Closed'
                        : (apt.reason_for_visit || 'Blocked Time');
                    const timeDisplay = isFullDayClosure
                        ? 'All Day'
                        : `${timeStr}${endTimeStr ? ' - ' + endTimeStr : ''}`;
                    modalContent += `
                    <div class="day-appointment-item blocked" data-appointment-id="${apt.id}" style="cursor: pointer;">
                        <div class="appointment-time">
                            <i class="${isFullDayClosure ? 'bi bi-calendar-x' : 'bi bi-clock'}"></i>
                            ${timeDisplay}
                        </div>
                        <div class="appointment-title">${blockLabel}</div>
                        <div class="appointment-status">Status: ${isFullDayClosure ? 'Clinic Closed' : 'Blocked Time'}</div>
                        ${apt.notes ? `<div class="appointment-notes text-muted small">${apt.notes}</div>` : ''}
                    </div>
                    `;
                    return;
                }
                
                let patientName = 'Unknown Patient';
                if (apt.patient && apt.patient.info) {
                    const info = apt.patient.info;
                    patientName = `${info.first_name} ${info.last_name}`.trim();
                } else if (apt.patient && apt.patient.name) {
                    patientName = apt.patient.name;
                }
                
                let serviceName = apt.service ? apt.service.service_name : 'No Service';
                const isCompleted = status === 'completed';
                const isCancelled = status === 'cancelled';
                const isMissed = status === 'missed';
                
                modalContent += `
                    <div class="day-appointment-item ${status}" data-appointment-id="${apt.id}" style="cursor: pointer;">
                        <div class="appointment-time">
                            <i class="bi bi-clock"></i>
                            ${timeStr}${endTimeStr ? ' - ' + endTimeStr : ''}
                        </div>
                        <div class="appointment-title ${isCompleted || isCancelled || isMissed ? 'text-decoration-line-through' : ''}">${patientName} - ${serviceName}</div>
                        <div class="appointment-status">Status: ${apt.status || 'Pending'}</div>
                        ${apt.notes && isCancelled ? `<div class="appointment-notes text-muted small">${apt.notes}</div>` : ''}
                    </div>
                `;
            });
        }
        
        modalContent += '</div>';
        
        // Update modal content
        const modal = document.getElementById('dayAppointmentsModal');
        if (modal) {
            const modalBody = modal.querySelector('.modal-body');
            if (modalBody) {
                modalBody.innerHTML = modalContent;
                
                // Add click event listeners to appointment items after content is inserted
                modalBody.querySelectorAll('.day-appointment-item[data-appointment-id]').forEach(function(item) {
                    const appointmentId = item.dataset.appointmentId;
                    if (appointmentId) {
                        item.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Close the day appointments modal first
                            const bsModal = bootstrap.Modal.getInstance(modal);
                            if (bsModal) {
                                bsModal.hide();
                            }
                            // Then open the appointment details modal
                            editAppointment(parseInt(appointmentId));
                        });
                    }
                });
            }
            
            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    }

    // Flag to bypass duplicate check when user confirms
    let bypassDuplicateCheck = false;

    function saveAppointment() {
        // Clear previous validation messages
        clearValidationMessages();

        const formData = new FormData(document.getElementById('appointmentForm'));
        const appointmentData = Object.fromEntries(formData.entries());

        console.log('Form data:', appointmentData);

        // Validation
        let isValid = true;
        const errors = [];

        // Check patient selection
        if (!appointmentData.patient_id) {
            showFieldError('patient_search', 'Please select a patient');
            isValid = false;
        }

        // Check date selection
        const selectedDate = document.getElementById('selected_date').value;
        if (!selectedDate) {
            showFieldError('selected_date', 'Please select a date');
            isValid = false;
        } else {
            // CRITICAL: Check if selected date is in the past using SERVER time (fault tolerant)
            const serverNow = getServerTime();
            const serverToday = new Date(serverNow);
            serverToday.setHours(0, 0, 0, 0); // Set to start of today in server time

            // Parse selected date as local date
            const [year, month, day] = selectedDate.split('-').map(Number);
            const appointmentDate = new Date(year, month - 1, day); // month is 0-indexed
            appointmentDate.setHours(0, 0, 0, 0); // Set to start of selected date

            console.log('Comparing dates (using server time):', {
                selectedDate: selectedDate,
                serverToday: serverToday.toDateString(),
                appointmentDate: appointmentDate.toDateString(),
                serverTodayTime: serverToday.getTime(),
                appointmentTime: appointmentDate.getTime(),
                isPast: appointmentDate < serverToday
            });

            if (appointmentDate < serverToday) {
                showFieldError('selected_date', 'Cannot schedule appointments in the past');
                isValid = false;
            }
        }

        // Check time slot selection
        const timeSlot = document.querySelector('input[name="time_slot"]:checked');
        if (!timeSlot) {
            showFieldError('time-slots-list', 'Please select a time slot');
            isValid = false;
        } else if (selectedDate) {
            // Get start time
            let startTime;
            const [start, end] = timeSlot.value.split('-');
            startTime = start;

            if (startTime) {
                const [year, month, day] = selectedDate.split('-').map(Number);
                const [hours, minutes] = startTime.split(':').map(Number);
                const newAppointmentStart = new Date(year, month - 1, day, hours, minutes);

                // Get service duration
                const serviceSelect = document.getElementById('service_name');
                const selectedService = serviceSelect.options[serviceSelect.selectedIndex];
                const duration = parseInt(selectedService.getAttribute('data-duration')) || 30;
                const newAppointmentEnd = new Date(newAppointmentStart.getTime() + (duration * 60000));

                // Check if the selected patient already has an overlapping appointment
                const overlappingForPatient = allCalendarItems.find(apt => {
                    // Must match patient
                    if (String(apt.patient_id) !== String(appointmentData.patient_id)) return false;
                    // Ignore cancelled
                    const statusLower = (apt.status || 'pending').toLowerCase();
                    if (statusLower === 'cancelled') return false;
                    const existingStart = parseLocalDateTime(apt.start_datetime);
                    const existingEnd = parseLocalDateTime(apt.end_datetime);
                    if (!existingStart || !existingEnd) return false;
                    return newAppointmentStart < existingEnd && newAppointmentEnd > existingStart;
                });

                if (overlappingForPatient) {
                    showFieldError('patient_search', 'This patient already has an appointment that overlaps this time');
                    isValid = false;
                }

                // Check for time overlaps with existing appointments and blocked times on the same date (global conflicts)
                // Exclude cancelled appointments - they don't block time slots
                const overlappingItem = allCalendarItems.find(apt => {
                    // Skip cancelled appointments
                    const statusLower = (apt.status || 'pending').toLowerCase();
                    if (statusLower === 'cancelled') {
                        return false;
                    }

                    const aptDate = parseLocalDateTime(apt.start_datetime);
                    const selectedDateObj = new Date(selectedDate);
                    if (!aptDate) return false;

                    // Only check items on the same date
                    if (aptDate.toDateString() !== selectedDateObj.toDateString()) {
                        return false;
                    }

                    const existingStart = parseLocalDateTime(apt.start_datetime);
                    const existingEnd = parseLocalDateTime(apt.end_datetime);
                    if (!existingStart || !existingEnd) return false;

                    // Check if the new appointment overlaps with existing item
                    return (newAppointmentStart < existingEnd && newAppointmentEnd > existingStart);
                });

                if (overlappingItem) {
                    const conflictType = overlappingItem.status === 'blocked' ? 'blocked time' : 'appointment';
                    const isBlocked = overlappingItem.status === 'blocked';

                    if (isBlocked) {
                        // Check if it's a full day closure
                        const blockStart = parseLocalDateTime(overlappingItem.start_datetime);
                        const blockEnd = parseLocalDateTime(overlappingItem.end_datetime);
                        const isFullDayClosure = blockStart && blockEnd &&
                            blockStart.getHours() === 0 && blockStart.getMinutes() === 0 &&
                            blockEnd.getHours() === 23 && blockEnd.getMinutes() === 59;

                        // Show conflict modal
                        const conflictDate = new Date(selectedDate).toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        // Get end time from selected slot
                        const endTimeField = document.querySelector('input[name="time_slot"]:checked')?.value?.split('-')[1] || endTime;
                        const conflictTime = `${startTime} - ${endTimeField}`;

                        document.getElementById('conflictTitle').textContent = isFullDayClosure
                            ? 'Clinic is Closed on This Date'
                            : 'Time Slot is Blocked';
                        document.getElementById('conflictMessage').textContent = isFullDayClosure
                            ? 'The clinic is closed on this date. Please select a different date for the appointment.'
                            : 'This time slot is blocked. Please select a different time slot.';
                        document.getElementById('conflictDate').textContent = conflictDate;
                        document.getElementById('conflictTime').textContent = conflictTime;

                        // Close appointment modal temporarily
                        const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentModal'));
                        if (appointmentModal) {
                            appointmentModal.hide();
                        }

                        // Show conflict modal after a short delay
                        setTimeout(() => {
                            new bootstrap.Modal(document.getElementById('appointmentConflictModal')).show();

                            // When conflict modal is closed, reopen appointment modal
                            document.getElementById('appointmentConflictModal').addEventListener('hidden.bs.modal', function onHidden() {
                                if (appointmentModal) {
                                    appointmentModal.show();
                                }
                                document.getElementById('appointmentConflictModal').removeEventListener('hidden.bs.modal', onHidden);
                            }, { once: true });
                        }, 300);
                    } else {
                    showFieldError('time-slots-list', `This time slot conflicts with an existing ${conflictType}`);
                    }
                    isValid = false;
                }

                // CRITICAL: Check if selected time is in the past using SERVER time (fault tolerant)
                const serverNow = getServerTime();
                const serverToday = new Date(serverNow);
                serverToday.setHours(0, 0, 0, 0);
                const appointmentDate = new Date(year, month - 1, day);

                // Only check time if the appointment is for today (server time)
                const serverTodayString = serverToday.toDateString();
                const appointmentDateString = appointmentDate.toDateString();

                if (appointmentDateString === serverTodayString) {
                    const appointmentDateTime = new Date(year, month - 1, day, hours, minutes);

                    if (appointmentDateTime < serverNow) {
                        showFieldError('time-slots-list', 'Cannot schedule appointments in the past');
                        isValid = false;
                    }
                }
            }
        }

        // Check service selection
        const serviceSelect = document.getElementById('service_name');
        if (!serviceSelect || !serviceSelect.value) {
            showFieldError('service_name', 'Please select a service');
            isValid = false;
        }

        // Check for duplicate procedure on the same day (only if not bypassed)
        if (!bypassDuplicateCheck && selectedDate && appointmentData.patient_id && serviceSelect && serviceSelect.value) {
            const selectedServiceId = serviceSelect.value;
            const [year, month, day] = selectedDate.split('-').map(Number);
            const selectedDateStart = new Date(year, month - 1, day, 0, 0, 0);
            const selectedDateEnd = new Date(year, month - 1, day, 23, 59, 59);

            // Check if patient already has the same procedure on this day
            const duplicateProcedure = allCalendarItems.find(apt => {
                // Must match patient
                if (String(apt.patient_id) !== String(appointmentData.patient_id)) return false;
                // Must match service
                if (String(apt.service_id) !== String(selectedServiceId)) return false;
                // Ignore cancelled
                const statusLower = (apt.status || 'pending').toLowerCase();
                if (statusLower === 'cancelled') return false;
                // Check if appointment is on the same day
                const existingStart = parseLocalDateTime(apt.start_datetime);
                if (!existingStart) return false;
                return existingStart >= selectedDateStart && existingStart <= selectedDateEnd;
            });

            if (duplicateProcedure) {
                const serviceName = serviceSelect.options[serviceSelect.selectedIndex].text;
                const formattedDate = new Date(selectedDate).toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                // Show warning modal (admin cannot proceed)
                document.getElementById('sameProcedureWarningMessage').textContent = `This patient already has "${serviceName}" booked on ${formattedDate}.`;
                
                // Show warning modal
                const warningModal = new bootstrap.Modal(document.getElementById('sameProcedureWarningModal'));
                warningModal.show();
                
                isValid = false; // Prevent submission
                return; // Exit early - admin cannot proceed
            }
        }
        
        // Reset bypass flag after check
        bypassDuplicateCheck = false;

        if (!isValid) {
            // Only show generic message if no specific field errors were shown
            const hasFieldErrors = document.querySelectorAll('.is-invalid').length > 0;
            if (!hasFieldErrors) {
                showValidationMessage('Please fill in all required fields', 'error');
            }
            return;
        }


        // Get the selected time slot again for appointment creation
        const timeSlotForCreation = document.querySelector('input[name="time_slot"]:checked');

        // Get start time from selected slot
        let startTime;
        if (timeSlotForCreation) {
            const [start, end] = timeSlotForCreation.value.split('-');
            startTime = start;
        } else {
            showFieldError('time-slots-list', 'Please select a time slot');
            showValidationMessage('Please select a time slot', 'error');
            return;
        }

        // Show loading state
        const appointmentId = document.getElementById('appointment_id').value;
        const isUpdate = appointmentId && appointmentId !== '';
        const loadingMessage = isUpdate ? 'Rescheduling appointment...' : 'Creating appointment...';
        const buttonText = isUpdate ? 'Rescheduling...' : 'Creating...';

        showValidationMessage(loadingMessage, 'info');
        const submitBtn = document.querySelector('#appointmentForm button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = buttonText;
        submitBtn.disabled = true;

        // Add a delay to simulate processing time
        const delay = 500; // 2 seconds delay

        // Create datetime string in local format (YYYY-MM-DD HH:mm:ss) to avoid timezone conversion
        const startDateTimeStr = `${selectedDate} ${startTime}:00`;

        // Get service and duration information
        const selectedService = serviceSelect.options[serviceSelect.selectedIndex];
        const duration = selectedService.getAttribute('data-duration') || 30;
        const isNewPatient = document.getElementById('is_new_patient') ? document.getElementById('is_new_patient').checked : false;

        const appointmentPayload = {
            patient_id: appointmentData.patient_id,
            service_id: appointmentData.service_name,
            start_datetime: startDateTimeStr,
            duration_minutes: parseInt(duration),
            status: 'Pending',
            notes: appointmentData.notes,
            is_new_patient: isNewPatient
        };

        // Add delay before making the request
        setTimeout(() => {
            const url = isUpdate ? `/admin/appointment/${appointmentId}` : '/admin/appointment';
            const method = isUpdate ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(appointmentPayload)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);

            // Parse JSON regardless of status to get error messages
            return response.json().then(data => ({
                ok: response.ok,
                status: response.status,
                data: data
            })).catch(e => {
                // If JSON parsing fails, return text response
                return response.text().then(text => ({
                    ok: response.ok,
                    status: response.status,
                    data: {success: false, message: text || 'Invalid response format'}
                }));
            });
        })
        .then(({ok, status, data}) => {
            console.log('Parsed response data:', data);

            // Check if data exists and has success property
            if (ok && data && typeof data === 'object' && data.success === true) {
                const successMessage = isUpdate ?
                    (data.message || 'Appointment rescheduled successfully!') :
                    (data.message || 'Appointment created successfully!');
                showValidationMessage(successMessage, 'success');
                // Close modal after a short delay
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();

                    // Navigate to the month of the appointment
                    const [year, month, day] = selectedDate.split('-').map(Number);
                    const url = new URL(window.location.href);
                    url.searchParams.set('month', month);
                    url.searchParams.set('year', year);
                    url.searchParams.set('day', day);
                    window.location.href = url.toString();
                }, 1500);
            } else if (!ok || (data && typeof data === 'object' && data.success === false)) {
                // Handle validation errors or other errors
                let errorMessage = 'Unknown error occurred';

                if (data.message) {
                    errorMessage = data.message;
                } else if (data.errors) {
                    // If there are field-specific errors, show the first one
                    const firstError = Object.values(data.errors)[0];
                    errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                }

                console.error('Appointment operation failed:', errorMessage);

                // Check if error is due to same procedure
                if (errorMessage.includes('same procedure') || errorMessage.includes('cannot book the same')) {
                    // Show same procedure error modal
                    document.getElementById('sameProcedureErrorMessage').textContent = errorMessage;

                    // Close appointment modal temporarily
                    const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentModal'));
                    if (appointmentModal) {
                        appointmentModal.hide();
                    }

                    // Show error modal after a short delay
                    setTimeout(() => {
                        const sameProcedureModal = new bootstrap.Modal(document.getElementById('sameProcedureErrorModal'));
                        sameProcedureModal.show();

                        // When error modal is closed, reopen appointment modal
                        document.getElementById('sameProcedureErrorModal').addEventListener('hidden.bs.modal', function onHidden() {
                            if (appointmentModal) {
                                appointmentModal.show();
                            }
                            document.getElementById('sameProcedureErrorModal').removeEventListener('hidden.bs.modal', onHidden);
                        }, { once: true });
                    }, 300);
                }
                // Check if error is due to blocked/closed time
                else if (errorMessage.includes('closed') || errorMessage.includes('blocked')) {
                    // Show conflict modal
                    const selectedDate = document.getElementById('selected_date').value;
                    const timeSlot = document.querySelector('input[name="time_slot"]:checked');
                    let selectedTime = '';

                    if (timeSlot && timeSlot.value !== 'custom') {
                        selectedTime = timeSlot.value.split('-')[0];
                    } else if (timeSlot && timeSlot.value === 'custom') {
                        selectedTime = document.getElementById('custom_start_time').value;
                    }

                    const conflictDate = new Date(selectedDate).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    document.getElementById('conflictTitle').textContent = errorMessage.includes('closed')
                        ? 'Clinic is Closed on This Date'
                        : 'Time Slot is Blocked';
                    document.getElementById('conflictMessage').textContent = errorMessage;
                    document.getElementById('conflictDate').textContent = conflictDate;
                    document.getElementById('conflictTime').textContent = selectedTime || 'Selected time';

                    // Close appointment modal temporarily
                    const appointmentModal = bootstrap.Modal.getInstance(document.getElementById('appointmentModal'));
                    if (appointmentModal) {
                        appointmentModal.hide();
                    }

                    // Show conflict modal after a short delay
                    setTimeout(() => {
                        new bootstrap.Modal(document.getElementById('appointmentConflictModal')).show();

                        // When conflict modal is closed, reopen appointment modal
                        document.getElementById('appointmentConflictModal').addEventListener('hidden.bs.modal', function onHidden() {
                            if (appointmentModal) {
                                appointmentModal.show();
                            }
                            document.getElementById('appointmentConflictModal').removeEventListener('hidden.bs.modal', onHidden);
                        }, { once: true });
                    }, 300);
                } else {
                showValidationMessage(errorMessage, 'error');
                }
            } else {
                // Handle unexpected response format - but if we got here, the request succeeded
                console.warn('Unexpected response format, but request succeeded:', data);

                // Since the appointment was created/updated successfully in the database,
                // we'll treat this as a success
                const successMessage = isUpdate ? 'Appointment rescheduled successfully!' : 'Appointment created successfully!';
                showValidationMessage(successMessage, 'success');
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();

                    // Navigate to the month of the appointment
                    const [year, month, day] = selectedDate.split('-').map(Number);
                    const url = new URL(window.location.href);
                    url.searchParams.set('month', month);
                    url.searchParams.set('year', year);
                    url.searchParams.set('day', day);
                    window.location.href = url.toString();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showValidationMessage('Network error. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
        }, delay); // Close setTimeout with delay
    }

    // Helper function to show block time success modal
    function showBlockTimeSuccessModal(title, message) {
        document.getElementById('blockSuccessTitle').textContent = title;
        document.getElementById('blockSuccessMessage').textContent = message;
        new bootstrap.Modal(document.getElementById('blockTimeSuccessModal')).show();
    }

    // Helper function to show block time error modal
    function showBlockTimeErrorModal(message) {
        document.getElementById('blockErrorMessage').textContent = message || 'An error occurred while blocking time. Please try again.';
        new bootstrap.Modal(document.getElementById('blockTimeErrorModal')).show();
    }

    // Handle repeat block time functionality
    function handleRepeatBlockTime() {
        const isMultipleDays = isClinicClosedSelected();
        const description = document.getElementById('block_description').value;
        const frequency = document.getElementById('repeat_frequency').value;
        const customInterval = document.getElementById('custom_interval').value;
        const endType = document.querySelector('input[name="repeat_end_type"]:checked').value;
        const repeatEndDate = document.getElementById('repeat_end_date_input').value;
        const occurrenceCount = document.getElementById('repeat_occurrence_count').value;

        // Validation
        if (!frequency) {
            showValidationMessage('Please select a repeat frequency', 'error');
            return;
        }

        if (frequency === 'custom' && !customInterval) {
            showValidationMessage('Please enter a custom interval', 'error');
            return;
        }

        if (endType === 'date' && !repeatEndDate) {
            showValidationMessage('Please select a repeat until date', 'error');
            return;
        }

        if (endType === 'count' && !occurrenceCount) {
            showValidationMessage('Please enter the number of occurrences', 'error');
            return;
        }

        let closureStartDate, closureEndDate;

        if (isMultipleDays) {
            // For multiple days closure
            const startDate = document.getElementById('block_start_date_input').value;
            const endDate = document.getElementById('block_end_date_input').value;

            if (!startDate || !endDate) {
                showValidationMessage('Please select both start and end dates for the closure', 'error');
                return;
            }

            // Validate that end date is after start date
            const startDateObj = new Date(startDate);
            const endDateObj = new Date(endDate);

            if (endDateObj < startDateObj) {
                showValidationMessage('End date must be after start date', 'error');
                return;
            }

            closureStartDate = new Date(startDate + 'T00:00:00');
            closureEndDate = new Date(endDate + 'T23:59:00');
        } else {
            // For single day closure
            const blockDate = document.getElementById('block_date').value;
            const startTime = document.getElementById('block_start_time').value;
            const endTime = document.getElementById('block_end_time').value;

            if (!blockDate || !startTime || !endTime) {
                showValidationMessage('Date and time are required', 'error');
                return;
            }

            closureStartDate = new Date(blockDate + 'T' + startTime);
            closureEndDate = new Date(blockDate + 'T' + endTime);
        }

        // Calculate repeat end date/time
        const repeatEndDateTime = endType === 'date' ? new Date(repeatEndDate + 'T23:59:59') : null;
        const count = endType === 'count' ? parseInt(occurrenceCount) : null;

        // Generate repeat occurrences (each occurrence is a date range or single day)
        const repeatOccurrences = generateRepeatDateRanges(
            closureStartDate,
            closureEndDate,
            frequency,
            customInterval,
            repeatEndDateTime,
            count
        );

        if (repeatOccurrences.length === 0) {
            showValidationMessage('No occurrences generated. Please check your repeat settings.', 'error');
            return;
        }

        // Calculate total days to block
        const totalDaysToBlock = repeatOccurrences.reduce((total, occurrence) => {
            if (isMultipleDays) {
                // For date ranges, count all days in the range
                const daysInRange = Math.ceil((occurrence.end - occurrence.start) / (1000 * 60 * 60 * 24)) + 1;
                return total + daysInRange;
            } else {
                return total + 1;
            }
        }, 0);

        // Show loading state
        const submitBtn = document.getElementById('save-block-time-btn');
        const btnText = document.getElementById('save-block-btn-text');
        const originalText = btnText.textContent;
        submitBtn.disabled = true;
        btnText.textContent = `Creating ${totalDaysToBlock} block(s)...`;

        // Create blocked times for each occurrence
        const createPromises = [];

        repeatOccurrences.forEach(occurrence => {
            if (isMultipleDays) {
                // For date ranges, create blocks for each day in the range
                const currentDate = new Date(occurrence.start);
                const endDateObj = new Date(occurrence.end);
                while (currentDate <= endDateObj) {
                    const dateStr = formatLocalDate(currentDate);
                    const startDateTimeStr = `${dateStr} 00:00:00`;
                    const endDateTimeStr = `${dateStr} 23:59:00`;

                    const blockData = {
                        title: 'Clinic Closed',
                        start_time: startDateTimeStr,
                        end_time: endDateTimeStr,
                        description: description || 'Recurring clinic closure'
                    };

                    createPromises.push(
                        fetch('/admin/blocked-time', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(blockData)
                        }).then(response => response.json())
                    );

                    // Move to next day
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            } else {
                // For single day blocks
                const startDateObj = new Date(occurrence.start);
                const endDateObj = new Date(occurrence.end);
                const dateStr = formatLocalDate(startDateObj);
                const startTime = startDateObj.toTimeString().slice(0, 5);
                const endTime = endDateObj.toTimeString().slice(0, 5);

                const startDateTimeStr = `${dateStr} ${startTime}:00`;
                const endDateTimeStr = `${dateStr} ${endTime}:00`;

                const blockData = {
                    title: 'Blocked Time',
                    start_time: startDateTimeStr,
                    end_time: endDateTimeStr,
                    description: description || 'Recurring blocked time'
                };

                createPromises.push(
                    fetch('/admin/blocked-time', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(blockData)
                    }).then(response => response.json())
                );
            }
        });

        // Wait for all blocks to be created
        Promise.all(createPromises)
            .then(results => {
                const successCount = results.filter(r => r && r.success).length;
                const failCount = results.filter(r => !r || !r.success).length;

                if (successCount > 0) {
                    bootstrap.Modal.getInstance(document.getElementById('blockTimeModal')).hide();

                    // Show success modal
                    const successTitle = isMultipleDays
                        ? 'Recurring Clinic Closures Created Successfully!'
                        : 'Recurring Blocks Created Successfully!';
                    const successMessage = isMultipleDays
                        ? `${successCount} day(s) of recurring clinic closure have been created${failCount > 0 ? ` (${failCount} failed)` : ''}. The clinic will be unavailable for appointments during these recurring periods.`
                        : `${successCount} recurring block(s) have been created${failCount > 0 ? ` (${failCount} failed)` : ''}. The clinic will be unavailable for appointments during these recurring periods.`;

                    showBlockTimeSuccessModal(successTitle, successMessage);

                    setTimeout(() => {
                        reloadWithCurrentMonth();
                    }, 2000);
                } else {
                    showBlockTimeErrorModal('Failed to create recurring blocks. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error creating recurring blocks:', error);
                showBlockTimeErrorModal('Error creating recurring blocks. Please try again.');
            })
            .finally(() => {
                btnText.textContent = originalText;
                submitBtn.disabled = false;
            });
    }

    // Helper function to format date as YYYY-MM-DD in local time (not UTC)
    function formatLocalDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Helper function to generate repeat date ranges
    function generateRepeatDateRanges(startDate, endDate, frequency, customInterval, repeatEndDate, count) {
        const occurrences = [];
        let currentStartDate = new Date(startDate);
        let currentEndDate = new Date(endDate);
        const duration = currentEndDate - currentStartDate; // Duration in milliseconds
        let iteration = 0;
        const maxIterations = count || 1000; // Safety limit

        while (iteration < maxIterations) {
            // Add this occurrence
            occurrences.push({
                start: new Date(currentStartDate),
                end: new Date(currentEndDate)
            });

            // Check if we've reached the end condition
            if (repeatEndDate && currentStartDate >= repeatEndDate) {
                break;
            }

            if (count && occurrences.length >= count) {
                break;
            }

            // Calculate next occurrence start date based on frequency
            let nextStartDate = new Date(currentStartDate);

            switch (frequency) {
                case 'daily':
                    nextStartDate.setDate(nextStartDate.getDate() + 1);
                    break;
                case 'weekly':
                    nextStartDate.setDate(nextStartDate.getDate() + 7);
                    break;
                case 'monthly':
                    nextStartDate.setMonth(nextStartDate.getMonth() + 1);
                    break;
                case 'custom':
                    const interval = parseInt(customInterval) || 1;
                    nextStartDate.setDate(nextStartDate.getDate() + interval);
                    break;
            }

            // Calculate next occurrence end date (maintain same duration)
            const nextEndDate = new Date(nextStartDate.getTime() + duration);

            // Check if next occurrence would exceed repeat end date
            if (repeatEndDate && nextStartDate > repeatEndDate) {
                break;
            }

            currentStartDate = nextStartDate;
            currentEndDate = nextEndDate;
            iteration++;
        }

        return occurrences;
    }

    function saveBlockTime() {
        const isMultipleDays = isClinicClosedSelected();
        const blockId = document.getElementById('block_time_id').value;
        const isUpdate = blockId && blockId !== '';

        // Don't allow updates with repeat - must be new block
        if (isUpdate && isRepeatEnabled) {
            showValidationMessage('Cannot update recurring blocked times. Please delete and create a new one.', 'error');
            return;
        }

        const startTime = document.getElementById('block_start_time').value;
        const endTime = document.getElementById('block_end_time').value;
        const description = document.getElementById('block_description').value;

        console.log('Block time save - Multiple days:', isMultipleDays, 'Repeat enabled:', isRepeatEnabled);

        // Handle repeat functionality (works with both single day and multiple days)
        if (isRepeatEnabled) {
            handleRepeatBlockTime();
            return;
        }

        if (isMultipleDays) {
            const startDateRaw = document.getElementById('block_start_date_input').value || document.getElementById('block_date').value;
            const endDateRaw = document.getElementById('block_end_date_input').value || startDateRaw;

            if (!startDateRaw) {
                showValidationMessage('Please select a start date', 'error');
                return;
            }

            const startDateObj = new Date(startDateRaw);
            const endDateObj = new Date(endDateRaw);

            if (endDateObj < startDateObj) {
                showValidationMessage('End date must be after start date', 'error');
                return;
            }

            const startDateStr = startDateRaw;
            const endDateStr = endDateRaw;

            const submitBtn = document.getElementById('save-block-time-btn');
            const btnText = document.getElementById('save-block-btn-text');
            const originalText = btnText.textContent;
            submitBtn.disabled = true;
            btnText.textContent = 'Saving...';

            if (isUpdate) {
                const blockData = {
                    title: 'Clinic Closed',
                    start_time: `${startDateStr} 00:00:00`,
                    end_time: `${endDateStr} 23:59:00`,
                    description: description || 'Clinic closed for the day',
                    _method: 'PUT'
                };

                fetch(`/admin/blocked-time/${blockId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(blockData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('blockTimeModal')).hide();
                        document.getElementById('blockSuccessTitle').textContent = 'Clinic Closed Updated!';
                        document.getElementById('blockSuccessMessage').textContent =
                            'The clinic closure has been updated successfully.';
                        new bootstrap.Modal(document.getElementById('blockTimeSuccessModal')).show();
                        setTimeout(() => {
                            reloadWithCurrentMonth();
                        }, 2000);
                    } else {
                        const message = data.message || 'Failed to update clinic closure. Please try again.';
                        document.getElementById('blockErrorMessage').textContent = message;
                        new bootstrap.Modal(document.getElementById('blockTimeErrorModal')).show();
                    }
                })
                .catch(error => {
                    console.error('Error updating clinic closure:', error);
                    document.getElementById('blockErrorMessage').textContent = 'Error updating clinic closure. Please try again.';
                    new bootstrap.Modal(document.getElementById('blockTimeErrorModal')).show();
                })
                .finally(() => {
                    btnText.textContent = originalText;
                    submitBtn.disabled = false;
                });
            } else {
                const dates = [];
                const currentDate = new Date(startDateObj);
                const end = new Date(endDateObj);
                while (currentDate <= end) {
                    dates.push(new Date(currentDate));
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                const createPromises = dates.map(date => {
                    const dateStr = formatLocalDate(date);
                const blockData = {
                    title: 'Clinic Closed',
                        start_time: `${dateStr} 00:00:00`,
                        end_time: `${dateStr} 23:59:00`,
                    description: description || 'Clinic closed for the day'
                };

                return fetch('/admin/blocked-time', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(blockData)
                }).then(response => response.json());
            });

            Promise.all(createPromises)
                .then(results => {
                    const successCount = results.filter(r => r.success).length;
                    const failCount = results.filter(r => !r.success).length;

                    if (successCount > 0) {
                        bootstrap.Modal.getInstance(document.getElementById('blockTimeModal')).hide();

                        document.getElementById('blockSuccessTitle').textContent = 'Clinic Closed Successfully!';
                        document.getElementById('blockSuccessMessage').textContent =
                            `${successCount} day(s) marked as closed${failCount > 0 ? ` (${failCount} failed)` : ''}. The clinic will be unavailable for appointments during this period.`;
                        new bootstrap.Modal(document.getElementById('blockTimeSuccessModal')).show();

                        setTimeout(() => {
                            reloadWithCurrentMonth();
                        }, 2000);
                    } else {
                        document.getElementById('blockErrorMessage').textContent = 'Failed to block any days. Please try again.';
                        new bootstrap.Modal(document.getElementById('blockTimeErrorModal')).show();
                    }
                })
                .catch(error => {
                    console.error('Error blocking time:', error);
                    document.getElementById('blockErrorMessage').textContent = 'Error blocking time. Please try again.';
                    new bootstrap.Modal(document.getElementById('blockTimeErrorModal')).show();
                })
                .finally(() => {
                    btnText.textContent = originalText;
                    submitBtn.disabled = false;
                });
            }
        } else {
            // Single day blocking (existing logic)
            const formData = new FormData(document.getElementById('blockTimeForm'));
            const blockData = Object.fromEntries(formData.entries());
            blockData.title = 'Blocked Time';

            console.log('Block time data (before combining):', blockData);

            // Get date and time fields
            let date = blockData.date;

            // If updating (editing), date field might be hidden, so get it from the stored value
            if (isUpdate && !date) {
                date = document.getElementById('block_date').value;
            }

            if (!date) {
                showValidationMessage('Date is required', 'error');
                return;
            }

            // Create datetime strings in ISO format to avoid timezone issues
            // Format: YYYY-MM-DDTHH:mm:ss
            const startDateTimeStr = `${date}T${startTime}:00`;
            const endDateTimeStr = `${date}T${endTime}:00`;

            // Create Date objects to validate
            const startDateTime = new Date(startDateTimeStr);
            const endDateTime = new Date(endDateTimeStr);

            // Validate that end time is after start time
            if (endDateTime <= startDateTime) {
                showValidationMessage('End time must be after start time', 'error');
                return;
            }

            // Convert to format that backend expects (without timezone conversion)
            blockData.start_time = startDateTimeStr.replace('T', ' ');
            blockData.end_time = endDateTimeStr.replace('T', ' ');

            // Remove the separate date field as it's no longer needed
            delete blockData.date;

            console.log('Block time data (after combining):', blockData);
            console.log('Start DateTime:', blockData.start_time);
            console.log('End DateTime:', blockData.end_time);
            console.log('Is update:', isUpdate, 'Block ID:', blockId);

            // Determine URL and method for blocked times
            const url = isUpdate ? `/admin/blocked-time/${blockId}` : '/admin/blocked-time';
            const method = isUpdate ? 'PUT' : 'POST';

            // Add _method for Laravel PUT requests
            if (isUpdate) {
                blockData._method = 'PUT';
            }

            // Show loading state
            const submitBtn = document.getElementById('save-block-time-btn');
            const btnText = document.getElementById('save-block-btn-text');
            const originalText = btnText.textContent;
            submitBtn.disabled = true;
            btnText.textContent = 'Saving...';

            fetch(url, {
                method: 'POST', // Always POST, Laravel will handle _method
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(blockData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Block time response:', data);
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('blockTimeModal')).hide();

                    // Show success modal
                    const message = isUpdate ? 'Blocked time updated successfully!' : 'Time blocked successfully!';
                    document.getElementById('blockSuccessTitle').textContent = isUpdate ? 'Time Block Updated!' : 'Time Blocked Successfully!';
                    document.getElementById('blockSuccessMessage').textContent = isUpdate
                        ? 'The blocked time has been updated. No appointments can be scheduled during this period.'
                        : 'The time has been blocked and no appointments can be scheduled during this period.';
                    new bootstrap.Modal(document.getElementById('blockTimeSuccessModal')).show();

                    // Reload page after delay
                    setTimeout(() => {
                        reloadWithCurrentMonth();
                    }, 2000);
                } else {
                    // Show error modal
                    let errorMessage = data.message || 'Error blocking time. Please try again.';
                    if (data.errors) {
                        // Get first error message from validation errors
                        const firstError = Object.values(data.errors)[0];
                        if (firstError && Array.isArray(firstError)) {
                            errorMessage = firstError[0];
                        } else if (firstError) {
                            errorMessage = firstError;
                        }
                    }
                    document.getElementById('blockErrorMessage').textContent = errorMessage;
                    new bootstrap.Modal(document.getElementById('blockTimeErrorModal')).show();
                }
            })
            .catch(error => {
                console.error('Error blocking time:', error);
                // Show error modal
                document.getElementById('blockErrorMessage').textContent = 'Error blocking time. Please try again.';
                new bootstrap.Modal(document.getElementById('blockTimeErrorModal')).show();
            })
            .finally(() => {
                // Reset button state
                btnText.textContent = originalText;
                submitBtn.disabled = false;
            });
        }
    }

    // Delete block time functionality - show custom confirmation modal
    document.getElementById('delete-block-time-btn').addEventListener('click', function() {
        const blockId = this.getAttribute('data-block-id');
        if (!blockId) return;

        showDeleteBlockConfirmation(blockId);
    });

    // Confirm delete blocked time
    document.getElementById('confirm-delete-block-btn').addEventListener('click', function() {
        const blockId = this.getAttribute('data-block-id');
        if (!blockId) return;

        deleteBlockTime(blockId);
    });

    function showDeleteBlockConfirmation(blockId) {
        console.log('Showing delete confirmation for block ID:', blockId);

        // Find the blocked time to show details
        const blockedTime = blockedTimes.find(bt => bt.id == blockId);
        if (!blockedTime) {
            showValidationMessage('Blocked time not found', 'error');
            return;
        }

        // Parse datetime strings as LOCAL time to avoid timezone conversion
        const startDateTime = parseLocalDateTime(blockedTime.start_datetime);
        const endDateTime = parseLocalDateTime(blockedTime.end_datetime);

        // Format date and time for display
        const formattedDate = startDateTime.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });

        const formattedStartTime = startDateTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        const formattedEndTime = endDateTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        // Update the confirmation modal content
        document.getElementById('delete-block-info').textContent =
            `${formattedDate} - ${formattedStartTime} to ${formattedEndTime}`;

        // Store block ID for confirmation
        document.getElementById('confirm-delete-block-btn').setAttribute('data-block-id', blockId);

        // Close the edit modal and show confirmation modal
        bootstrap.Modal.getInstance(document.getElementById('blockTimeModal')).hide();

        // Show confirmation modal after a short delay
        setTimeout(() => {
            new bootstrap.Modal(document.getElementById('deleteBlockedTimeModal')).show();
        }, 300);
    }

    function deleteBlockTime(blockId) {
        // Ensure blockId is a valid number/string
        blockId = String(blockId).trim();
        if (!blockId || blockId === 'undefined' || blockId === 'null') {
            console.error('Invalid block ID:', blockId);
            showValidationMessage('Invalid blocked time ID', 'error');
            return;
        }

        console.log('Deleting block time:', blockId);

        // Show loading state
        const deleteBtn = document.getElementById('confirm-delete-block-btn');
        const originalText = deleteBtn.innerHTML;
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Deleting...';

        const url = `/admin/blocked-time/${encodeURIComponent(blockId)}/delete`;
        console.log('Delete URL:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            })
        })
        .then(response => {
            if (!response.ok) {
                // If response is not ok, try to get error message
                return response.json().then(err => {
                    throw new Error(err.message || `Server error: ${response.status}`);
                }).catch(() => {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete response:', data);
            if (data.success) {
                // Close confirmation modal
                bootstrap.Modal.getInstance(document.getElementById('deleteBlockedTimeModal')).hide();

                // Show success message
                showValidationMessage('Blocked time deleted successfully!', 'success');

                // Remove from calendar and refresh (no page reload needed)
                if (typeof removeBlockedTimeFromCalendar === 'function') {
                    removeBlockedTimeFromCalendar(blockId);
                } else {
                    // Fallback to page reload
                    setTimeout(() => {
                        reloadWithCurrentMonth();
                    }, 1500);
                }
            } else {
                showValidationMessage(data.message || 'Error deleting blocked time', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting blocked time:', error);
            showValidationMessage(error.message || 'Error deleting blocked time. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
        });
    }

    // Flag to track if we're editing a blocked time
    let isEditingBlockedTime = false;

    // Clear Clinic Closed button - use event delegation to handle clicks even if button is inside modal
    function setupClearClinicClosedButton() {
        const clearBtn = document.getElementById('clear-clinic-closed-btn');
        if (!clearBtn) {
            console.warn('Clear Clinic Closed button not found');
            return;
        }

        // Remove any existing listeners by cloning the button
        const newBtn = clearBtn.cloneNode(true);
        clearBtn.parentNode.replaceChild(newBtn, clearBtn);

        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Clear Clinic Closed button clicked');
            
            // Show loading state in modal count
            const countElement = document.getElementById('clinic-closed-count');
            if (countElement) {
                countElement.textContent = 'Loading...';
            }

            // Show confirmation modal first
            const modalElement = document.getElementById('clearClinicClosedModal');
            if (!modalElement) {
                console.error('Clear Clinic Closed modal not found');
                showValidationMessage('Error: Confirmation modal not found.', 'error');
                return;
            }

            // Show the modal immediately
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Fetch count of future clinic closed days
            fetch('/admin/blocked-time/future/clinic-closed/count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const count = data.count || 0;
                const countText = count === 0
                    ? 'No future clinic closed days found'
                    : `${count} future clinic closed day(s) will be deleted`;

                if (countElement) {
                    countElement.textContent = countText;
                }

                // Disable confirm button if count is 0
                const confirmBtn = document.getElementById('confirm-clear-clinic-closed-btn');
                if (confirmBtn) {
                if (count === 0) {
                        confirmBtn.disabled = true;
                        confirmBtn.innerHTML = '<i class="bi bi-calendar-x me-1"></i>Nothing to Clear';
                } else {
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<i class="bi bi-calendar-x me-1"></i>Clear Clinic Closed';
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching future clinic closed count:', error);
                if (countElement) {
                    countElement.textContent = 'Error loading count';
                }
                showValidationMessage('Error loading future clinic closed days count.', 'error');
            });
        });
    }

    // Setup button on page load
    setupClearClinicClosedButton();

    // Fallback: Use event delegation for the clear clinic closed button (in case button is dynamically added or direct handler fails)
    // This ensures the modal always shows when the button is clicked
    document.addEventListener('click', function(e) {
        // Check if the clicked element or its parent is the clear clinic closed button
        const clearBtn = e.target.closest('#clear-clinic-closed-btn');
        if (clearBtn && !clearBtn.dataset.delegationHandled) {
            // Mark to prevent double execution if direct handler also fires
            clearBtn.dataset.delegationHandled = 'true';
            setTimeout(() => {
                clearBtn.dataset.delegationHandled = '';
            }, 100);
            
            e.preventDefault();
            e.stopPropagation();
            
            // Show loading state
            const countElement = document.getElementById('clinic-closed-count');
            if (countElement) {
                countElement.textContent = 'Loading...';
            }

            // Show modal immediately
            const modalElement = document.getElementById('clearClinicClosedModal');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modal.show();
                
                // Fetch count
                fetch('/admin/blocked-time/future/clinic-closed/count', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const count = data.count || 0;
                    const countText = count === 0
                        ? 'No future clinic closed days found'
                        : `${count} future clinic closed day(s) will be deleted`;

                    if (countElement) {
                        countElement.textContent = countText;
                    }

                    const confirmBtn = document.getElementById('confirm-clear-clinic-closed-btn');
                    if (confirmBtn) {
                        if (count === 0) {
                            confirmBtn.disabled = true;
                            confirmBtn.innerHTML = '<i class="bi bi-calendar-x me-1"></i>Nothing to Clear';
                        } else {
                            confirmBtn.disabled = false;
                            confirmBtn.innerHTML = '<i class="bi bi-calendar-x me-1"></i>Clear Clinic Closed';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching future clinic closed count:', error);
                    if (countElement) {
                        countElement.textContent = 'Error loading count';
                    }
                });
            }
        }
    });

    // Confirm clear clinic closed
    document.getElementById('confirm-clear-clinic-closed-btn').addEventListener('click', function() {
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Clearing...';

        fetch('/admin/blocked-time/future/clinic-closed/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || `Server error: ${response.status}`);
                }).catch(() => {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close confirmation modal
                const clearModal = bootstrap.Modal.getInstance(document.getElementById('clearClinicClosedModal'));
                if (clearModal) {
                    clearModal.hide();
                }

                // Close block time modal if open
                const blockTimeModal = bootstrap.Modal.getInstance(document.getElementById('blockTimeModal'));
                if (blockTimeModal) {
                    blockTimeModal.hide();
                }

                // Show success message
                showValidationMessage(data.message || `Successfully cleared ${data.deleted_count || 0} future clinic closed day(s)!`, 'success');

                // Reload page after delay
                setTimeout(() => {
                    reloadWithCurrentMonth();
                }, 1500);
            } else {
                showValidationMessage(data.message || 'Error clearing future clinic closed days.', 'error');
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error clearing future clinic closed days:', error);
            showValidationMessage(error.message || 'Error clearing future clinic closed days. Please try again.', 'error');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
    });

    // Clear Specific Time button - with date selection (for block off time)
    let clearSpecificSelectedDates = [];
    let clearSpecificBlockOffTimeDates = [];
    let clearSpecificCurrentDate = new Date();

    function setupClearBlockOffTimeButton() {
        const clearBtn = document.getElementById('clear-block-off-time-btn');
        if (!clearBtn) {
            console.warn('Clear Block Off Time button not found');
            return;
        }

        // Remove any existing listeners by cloning the button
        const newBtn = clearBtn.cloneNode(true);
        clearBtn.parentNode.replaceChild(newBtn, clearBtn);

        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Clear Specific Time button clicked');
            
            // Reset selection
            clearSpecificSelectedDates = [];
            clearSpecificCurrentDate = new Date();

            // Show confirmation modal first
            const modalElement = document.getElementById('clearBlockOffTimeModal');
            if (!modalElement) {
                console.error('Clear Specific Time modal not found');
                showValidationMessage('Error: Confirmation modal not found.', 'error');
                return;
            }

            // Show the modal immediately
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Show loading state
            const loadingDiv = document.getElementById('clear-specific-dates-loading');
            const listDiv = document.getElementById('clear-specific-dates-list');
            const noDatesDiv = document.getElementById('clear-specific-no-dates');
            
            if (loadingDiv) loadingDiv.style.display = 'block';
            if (listDiv) listDiv.style.display = 'none';
            if (noDatesDiv) noDatesDiv.style.display = 'none';
            
            // Fetch list of future block off time dates
            fetch('/admin/blocked-time/future/block-off-time/dates', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (loadingDiv) loadingDiv.style.display = 'none';
                
                if (data.success && data.dates && data.dates.length > 0) {
                    clearSpecificBlockOffTimeDates = data.dates;
                    generateClearSpecificDatesList();
                    if (listDiv) listDiv.style.display = 'block';
                } else {
                    clearSpecificBlockOffTimeDates = [];
                    if (noDatesDiv) noDatesDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching future block off time dates:', error);
                clearSpecificBlockOffTimeDates = [];
                if (loadingDiv) loadingDiv.style.display = 'none';
                if (noDatesDiv) noDatesDiv.style.display = 'block';
                showValidationMessage('Error loading future block off time dates.', 'error');
            });
        });
    }

    function generateClearSpecificDatesList() {
        const listDiv = document.getElementById('clear-specific-dates-list');
        const confirmBtn = document.getElementById('confirm-clear-block-off-time-btn');
        
        if (!listDiv) return;

        // Clear the list
        listDiv.innerHTML = '';

        // Get today's date for comparison
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Sort dates chronologically
        const sortedDates = [...clearSpecificBlockOffTimeDates].sort((a, b) => {
            return new Date(a.date) - new Date(b.date);
        });

        sortedDates.forEach(dateItem => {
            const date = new Date(dateItem.date);
            const isPast = date < today;
            const isSelected = clearSpecificSelectedDates.includes(dateItem.date);

            const listItem = document.createElement('div');
            listItem.className = `d-flex align-items-center p-3 border-bottom ${isSelected ? 'bg-warning bg-opacity-10' : ''}`;
            listItem.style.cursor = isPast ? 'not-allowed' : 'pointer';
            listItem.style.opacity = isPast ? '0.5' : '1';
            
            if (!isPast) {
                listItem.addEventListener('click', function() {
                    toggleClearSpecificDateSelection(dateItem.date);
                });
            }

            // Checkbox
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input me-3';
            checkbox.checked = isSelected;
            checkbox.disabled = isPast;
            checkbox.style.cursor = isPast ? 'not-allowed' : 'pointer';
            checkbox.addEventListener('change', function(e) {
                e.stopPropagation();
                toggleClearSpecificDateSelection(dateItem.date);
            });
            listItem.appendChild(checkbox);

            // Date info
            const dateInfo = document.createElement('div');
            dateInfo.className = 'flex-grow-1';
            
            const dateText = document.createElement('div');
            dateText.className = 'fw-medium';
            dateText.textContent = dateItem.formatted;
            dateInfo.appendChild(dateText);

            const dayName = document.createElement('div');
            dayName.className = 'text-muted small';
            dayName.textContent = date.toLocaleDateString('en-US', { weekday: 'long' });
            dateInfo.appendChild(dayName);

            listItem.appendChild(dateInfo);

            // Selected indicator
            if (isSelected) {
                const checkIcon = document.createElement('i');
                checkIcon.className = 'bi bi-check-circle-fill text-warning';
                checkIcon.style.fontSize = '1.25rem';
                listItem.appendChild(checkIcon);
            }

            listDiv.appendChild(listItem);
        });

        // Update confirm button state
        if (confirmBtn) {
            confirmBtn.disabled = clearSpecificSelectedDates.length === 0;
        }
    }

    function toggleClearSpecificDateSelection(dateKey) {
        const index = clearSpecificSelectedDates.indexOf(dateKey);
        if (index > -1) {
            clearSpecificSelectedDates.splice(index, 1);
        } else {
            clearSpecificSelectedDates.push(dateKey);
        }
        generateClearSpecificDatesList();
    }

    // Setup button on page load
    setupClearBlockOffTimeButton();

    // Re-setup buttons when modal is shown (in case modal content is reset)
    const blockTimeModalElement = document.getElementById('blockTimeModal');
    if (blockTimeModalElement) {
        blockTimeModalElement.addEventListener('shown.bs.modal', function() {
            setupClearClinicClosedButton();
            setupClearBlockOffTimeButton();
        });
    }

    // Show confirmation modal when clear button is clicked
    document.getElementById('confirm-clear-block-off-time-btn').addEventListener('click', function() {
        if (clearSpecificSelectedDates.length === 0) {
            showValidationMessage('Please select at least one date to clear.', 'error');
            return;
        }

        // Show confirmation modal
        const selectedCount = clearSpecificSelectedDates.length;
        const confirmMessage = document.getElementById('clearBlockOffTimeConfirmMessage');
        if (confirmMessage) {
            confirmMessage.textContent = `Are you sure you want to clear ${selectedCount} selected date(s)? This action cannot be undone.`;
        }
        
        // Hide the date selection modal first
        const dateSelectionModal = bootstrap.Modal.getInstance(document.getElementById('clearBlockOffTimeModal'));
        if (dateSelectionModal) {
            dateSelectionModal.hide();
        }
        
        const confirmModalElement = document.getElementById('clearBlockOffTimeConfirmModal');
        const confirmModal = new bootstrap.Modal(confirmModalElement, {
            backdrop: true, // Show backdrop for confirmation modal since date selection modal is hidden
            keyboard: true
        });
        
        // Ensure confirmation modal appears above
        confirmModalElement.style.zIndex = '1060';
        
        confirmModal.show();
    });

    // Handle cancel button - reopen date selection modal
    document.getElementById('cancel-confirm-clear-block-off-time-btn').addEventListener('click', function() {
        // Close confirmation modal
        const confirmModalInstance = bootstrap.Modal.getInstance(document.getElementById('clearBlockOffTimeConfirmModal'));
        if (confirmModalInstance) {
            confirmModalInstance.hide();
        }
        
        // Reopen the date selection modal
        setTimeout(() => {
            const dateSelectionModal = new bootstrap.Modal(document.getElementById('clearBlockOffTimeModal'));
            dateSelectionModal.show();
        }, 300); // Small delay to ensure confirmation modal is fully closed
    });

    // Final confirmation - actually clear the dates
    document.getElementById('final-confirm-clear-block-off-time-btn').addEventListener('click', function() {
        // Close confirmation modal
        bootstrap.Modal.getInstance(document.getElementById('clearBlockOffTimeConfirmModal')).hide();
        
        // Close the date selection modal
        bootstrap.Modal.getInstance(document.getElementById('clearBlockOffTimeModal')).hide();

        const confirmBtn = document.getElementById('confirm-clear-block-off-time-btn');
        const originalText = confirmBtn ? confirmBtn.innerHTML : '';
        
        // Show loading state on the final confirm button
        const finalConfirmBtn = this;
        const finalOriginalText = finalConfirmBtn.innerHTML;
        finalConfirmBtn.disabled = true;
        finalConfirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Clearing...';

        fetch('/admin/blocked-time/future/block-off-time/clear-specific', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                dates: clearSpecificSelectedDates
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || `Server error: ${response.status}`);
                }).catch(() => {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close block time modal if open
                const blockTimeModal = bootstrap.Modal.getInstance(document.getElementById('blockTimeModal'));
                if (blockTimeModal) {
                    blockTimeModal.hide();
                }

                // Show success message
                const deletedCount = data.deleted_count || 0;
                showValidationMessage(`Successfully cleared ${deletedCount} block off time date(s)!`, 'success');

                // Reload page after delay
                setTimeout(() => {
                    reloadWithCurrentMonth();
                }, 1500);
            } else {
                showValidationMessage(data.message || 'Error clearing block off time dates.', 'error');
                finalConfirmBtn.innerHTML = finalOriginalText;
                finalConfirmBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error clearing block off time dates:', error);
            showValidationMessage(error.message || 'Error clearing block off time dates. Please try again.', 'error');
            finalConfirmBtn.innerHTML = finalOriginalText;
            finalConfirmBtn.disabled = false;
        });
    });

    // Initialize tooltips for the info icon
    const blockTimeModalForTooltip = document.getElementById('blockTimeModal');
    if (blockTimeModalForTooltip) {
        const infoIcon = blockTimeModalForTooltip.querySelector('[data-bs-toggle="tooltip"]');
        if (infoIcon) {
            new bootstrap.Tooltip(infoIcon);
        }
    }

    // Reset block time modal when opening for new block
    document.getElementById('blockTimeModal').addEventListener('show.bs.modal', function(event) {
        // Initialize tooltip when modal is shown
        const infoIcon = this.querySelector('[data-bs-toggle="tooltip"]');
        if (infoIcon && !bootstrap.Tooltip.getInstance(infoIcon)) {
            new bootstrap.Tooltip(infoIcon);
        }

        // Only reset if we're NOT editing an existing blocked time
        if (!isEditingBlockedTime) {
            // Reset form for new block time
            document.getElementById('block_time_id').value = '';
            document.getElementById('block_title').value = 'Blocked Time';
            document.getElementById('block_description').value = '';
            document.getElementById('block_date').value = '';
            document.getElementById('block_start_time').value = '';
            document.getElementById('block_end_time').value = '';
            document.getElementById('blockTimeModalTitle').textContent = 'Block OFF Time';
            document.getElementById('save-block-btn-text').textContent = 'Create';
            document.getElementById('delete-block-time-btn').style.display = 'none';

            // Reset repeat fields
            isRepeatEnabled = false;
            document.getElementById('repeat-options-section').style.display = 'none';
            const repeatBtn = document.getElementById('repeat-toggle-btn');
            const repeatToggleContainer = repeatBtn.parentElement;
            repeatBtn.classList.remove('btn-primary');
            repeatBtn.classList.add('btn-outline-secondary');
            repeatBtn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>Repeat';
            // Show Repeat button container (will be hidden if checkbox is checked)
            repeatToggleContainer.style.display = 'block';
            document.getElementById('repeat_frequency').value = '';
            document.getElementById('custom_interval').value = '';
            document.getElementById('repeat_end_date_input').value = '';
            document.getElementById('repeat_occurrence_count').value = '';
            document.getElementById('repeat_end_date').checked = true;
            document.getElementById('repeat_end_count').checked = false;
            document.getElementById('repeat-end-date-section').style.display = 'block';
            document.getElementById('repeat-end-count-section').style.display = 'none';
            document.getElementById('custom-interval-section').style.display = 'none';
            document.getElementById('repeat-preview').style.display = 'none';

            // Reset multi-day fields
            document.getElementById('block_off_time_tab').checked = true;
            document.getElementById('clinic_closed_tab').checked = false;
            // Trigger change event to update UI
            document.getElementById('block_off_time_tab').dispatchEvent(new Event('change'));
            document.getElementById('block_start_date_input').value = '';
            document.getElementById('block_end_date_input').value = '';

            // Show all fields for new block and remove readonly
            const dateField = document.getElementById('block_date');
            dateField.removeAttribute('readonly');
            dateField.classList.remove('bg-light');
            dateField.closest('.mb-3').style.display = 'block';
            document.querySelector('.d-flex.justify-content-between.align-items-center').style.display = 'flex';
            document.querySelector('.btn.btn-outline-secondary.btn-sm').parentElement.style.display = 'block';
        }
    });

    function getBlockTimeActionRow() {
        return document.querySelector('#blockTimeModal .d-flex.gap-2.ms-auto');
    }

    function applyEditFooterLayout() {
        const actionRow = getBlockTimeActionRow();
        if (!actionRow) return;

        const saveBtn = document.getElementById('save-block-time-btn');
        const deleteBtn = document.getElementById('delete-block-time-btn');
        if (!saveBtn || !deleteBtn) return;

        if (!actionRow.dataset.editLayoutPrepared) {
            actionRow.dataset.editLayoutPrepared = 'true';
            saveBtn.dataset.originalMarginTop = saveBtn.style.marginTop || '';
            saveBtn.dataset.originalAlignSelf = saveBtn.classList.contains('align-self-start')
                ? 'align-self-start'
                : saveBtn.classList.contains('align-self-center')
                    ? 'align-self-center'
                    : '';
        }

        actionRow.dataset.editLayoutActive = 'true';
        actionRow.classList.add('flex-nowrap', 'align-items-center');
        if (deleteBtn.parentNode === actionRow && deleteBtn.nextSibling !== saveBtn) {
            actionRow.insertBefore(deleteBtn, saveBtn);
        }

        saveBtn.classList.remove('align-self-start');
        saveBtn.classList.add('align-self-center');
        saveBtn.style.marginTop = '0';
        deleteBtn.style.display = 'inline-block';
    }

    function restoreEditFooterLayout() {
        const actionRow = getBlockTimeActionRow();
        if (!actionRow) return;

        const saveBtn = document.getElementById('save-block-time-btn');
        const deleteBtn = document.getElementById('delete-block-time-btn');
        if (!saveBtn || !deleteBtn) return;

        actionRow.classList.remove('align-items-center');
        actionRow.classList.remove('flex-nowrap');

        actionRow.appendChild(saveBtn);
        actionRow.appendChild(deleteBtn);

        const originalMargin = saveBtn.dataset.originalMarginTop;
        if (typeof originalMargin !== 'undefined') {
            saveBtn.style.marginTop = originalMargin;
        } else {
            saveBtn.style.marginTop = '';
        }

        const originalAlign = saveBtn.dataset.originalAlignSelf;
        saveBtn.classList.remove('align-self-center', 'align-self-start');
        if (originalAlign) {
            saveBtn.classList.add(originalAlign);
        }

        deleteBtn.style.display = 'none';
        deleteBtn.removeAttribute('data-block-id');

        delete actionRow.dataset.editLayoutActive;

        const dateField = document.getElementById('block_date');
        const startTimeField = document.getElementById('block_start_time');
        const endTimeField = document.getElementById('block_end_time');
        const startDateInput = document.getElementById('block_start_date_input');
        const endDateInput = document.getElementById('block_end_date_input');

        if (dateField) {
            dateField.removeAttribute('readonly');
            dateField.classList.remove('bg-light');
        }
        if (startTimeField) {
            startTimeField.removeAttribute('disabled');
        }
        if (endTimeField) {
            endTimeField.removeAttribute('disabled');
        }
        if (startDateInput) {
            startDateInput.removeAttribute('readonly');
            startDateInput.classList.remove('bg-light');
        }
        if (endDateInput) {
            endDateInput.removeAttribute('readonly');
            endDateInput.classList.remove('bg-light');
        }
    }

    // Reset the editing flag when modal is closed
    document.getElementById('blockTimeModal').addEventListener('hidden.bs.modal', function() {
        isEditingBlockedTime = false;
        restoreEditFooterLayout();

        // Dispose tooltip when modal is hidden to prevent memory leaks
        const infoIcon = this.querySelector('[data-bs-toggle="tooltip"]');
        if (infoIcon) {
            const tooltipInstance = bootstrap.Tooltip.getInstance(infoIcon);
            if (tooltipInstance) {
                tooltipInstance.dispose();
            }
        }
    });

    function editAppointment(id) {
        console.log('Edit appointment called with ID:', id);
        console.log('Available calendar items:', allCalendarItems);
        console.log('Blocked times:', blockedTimes);

        // Find item (appointment or blocked time) in allCalendarItems first
        let item = allCalendarItems.find(apt => apt.id == id);
        
        // If not found, check if it's a blocked time in the original blockedTimes array
        if (!item) {
            const blockedTime = blockedTimes.find(bt => bt.id == id);
            if (blockedTime) {
                // Convert to format compatible with allCalendarItems
                item = {
                    ...blockedTime,
                    status: 'blocked',
                    reason_for_visit: blockedTime.title,
                    notes: blockedTime.notes
                };
            }
        }
        
        console.log('Found item:', item);

        if (item) {
            // Check if this is a blocked time (check both status and if it's in blockedTimes)
            const isBlockedTime = item.status === 'blocked' || blockedTimes.some(bt => bt.id == id);
            
            if (isBlockedTime) {
                console.log('Item is a blocked time, showing blocked time details');
                showBlockTimeDetails(item);
            } else {
                // For regular appointments, fetch fresh data from server to ensure service is loaded
                fetch(`/admin/appointment/${id}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(appointment => {
                    console.log('Fresh appointment data from server:', appointment);
                    showAppointmentDetails(appointment);
                })
                .catch(error => {
                    console.error('Error fetching appointment:', error);
                    // Fall back to cached data
                    showAppointmentDetails(item);
                });
            }
        } else {
            console.error('Calendar item not found with ID:', id);
            showValidationMessage('Calendar item not found', 'error');
        }
    }

    function showBlockTimeDetails(blockTime) {
        console.log('Showing block time details for EDITING:', blockTime);

        // Set flag to prevent modal reset
        isEditingBlockedTime = true;

        // Populate the block time modal for editing
        document.getElementById('block_time_id').value = blockTime.id;
        document.getElementById('block_title').value = blockTime.reason_for_visit || 'Blocked Time';
        document.getElementById('block_description').value = blockTime.notes || '';

        // Parse datetime strings as LOCAL time to avoid timezone conversion
        const startDate = parseLocalDateTime(blockTime.start_datetime);
        const endDate = parseLocalDateTime(blockTime.end_datetime);

        // Format date: YYYY-MM-DD
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        // Format time: HH:mm
        const formatTime = (date) => {
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${hours}:${minutes}`;
        };

        const blockOffTab = document.getElementById('block_off_time_tab');
        const clinicClosedTab = document.getElementById('clinic_closed_tab');
        const dateField = document.getElementById('block_date');
        const startTimeField = document.getElementById('block_start_time');
        const endTimeField = document.getElementById('block_end_time');
        const startDateInput = document.getElementById('block_start_date_input');
        const endDateInput = document.getElementById('block_end_date_input');

        const isFullDayClosure = startDate && endDate &&
            startDate.getHours() === 0 && startDate.getMinutes() === 0 &&
            endDate.getHours() === 23 && endDate.getMinutes() === 59;

        if (isFullDayClosure) {
            clinicClosedTab.checked = true;
            blockOffTab.checked = false;
        } else {
            blockOffTab.checked = true;
            clinicClosedTab.checked = false;
        }

        const changeEvent = new Event('change', { bubbles: true });
        if (isFullDayClosure) {
            clinicClosedTab.dispatchEvent(changeEvent);
            const startDateStr = formatDate(startDate);
            const endDateStr = formatDate(endDate);
            startDateInput.value = startDateStr;
            endDateInput.value = endDateStr;
            dateField.value = startDateStr;
            startDateInput.setAttribute('readonly', true);
            startDateInput.classList.add('bg-light');
            endDateInput.setAttribute('readonly', true);
            endDateInput.classList.add('bg-light');
            dateField.setAttribute('readonly', true);
            dateField.classList.add('bg-light');
            startTimeField.setAttribute('disabled', true);
            endTimeField.setAttribute('disabled', true);
        } else {
            blockOffTab.dispatchEvent(changeEvent);
            const dateStr = formatDate(startDate);
            dateField.value = dateStr;
            startTimeField.value = formatTime(startDate);
            endTimeField.value = formatTime(endDate);
            startDateInput.value = dateStr;
            endDateInput.value = formatDate(endDate);
            dateField.setAttribute('readonly', true);
            dateField.classList.add('bg-light');
            startTimeField.removeAttribute('disabled');
            endTimeField.removeAttribute('disabled');
            startDateInput.setAttribute('readonly', true);
            startDateInput.classList.add('bg-light');
            endDateInput.setAttribute('readonly', true);
            endDateInput.classList.add('bg-light');
        }

        dateField.setAttribute('readonly', true);
        dateField.classList.add('bg-light');
        document.querySelector('.d-flex.justify-content-between.align-items-center').style.display = 'none';
        document.querySelector('.btn.btn-outline-secondary.btn-sm').parentElement.style.display = 'none';

        // Update modal title and buttons
        document.getElementById('blockTimeModalTitle').textContent = 'Edit Blocked Off Time';
        document.getElementById('save-block-btn-text').textContent = 'Save Changes';
        document.getElementById('delete-block-time-btn').style.display = 'inline-block';
        document.getElementById('delete-block-time-btn').setAttribute('data-block-id', blockTime.id);

        // Show the modal
        console.log('Opening modal in EDIT mode for blocked time ID:', blockTime.id);
        new bootstrap.Modal(document.getElementById('blockTimeModal')).show();
        applyEditFooterLayout();
    }

    function showAppointmentDetails(appointment) {
        console.log('Showing appointment details for:', appointment);
        console.log('Appointment ID:', appointment.id);

        // Get patient name
        let patientName = 'Unknown Patient';
        if (appointment.patient && appointment.patient.info) {
            const info = appointment.patient.info;
            patientName = `${info.first_name} ${info.last_name}`.trim();
        } else if (appointment.patient && appointment.patient.name) {
            patientName = appointment.patient.name;
        }

        // Get service name
        let serviceName = 'No Service';
        if (appointment.service && typeof appointment.service === 'object' && appointment.service.service_name) {
            serviceName = appointment.service.service_name;
        } else if (appointment.reason_for_visit) {
            // Use reason_for_visit as fallback
            serviceName = appointment.reason_for_visit;
        } else if (appointment.service_id && (!appointment.service || appointment.service === null)) {
            // Service ID exists but service was deleted or doesn't exist
            serviceName = 'Service Not Found (ID: ' + appointment.service_id + ')';
        }

        // Parse datetime strings as LOCAL time to avoid timezone conversion
        const startDateTime = parseLocalDateTime(appointment.start_datetime);
        const endDateTime = parseLocalDateTime(appointment.end_datetime);

        const formattedDate = startDateTime.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const formattedStartTime = startDateTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        const formattedEndTime = endDateTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        // Get status badge class
        const statusClass = appointment.status.toLowerCase();
        const statusBadgeClass = statusClass === 'pending' ? 'bg-warning' :
                                statusClass === 'confirmed' ? 'bg-primary' :
                                statusClass === 'completed' ? 'bg-success' :
                                statusClass === 'cancelled' ? 'bg-brown' : 'bg-secondary';

        // Create appointment details HTML
        const detailsHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Patient Information</h6>
                    <div class="mb-3">
                        <strong>Name:</strong> ${patientName}
                    </div>
                    <div class="mb-3">
                        <strong>Patient ID:</strong> ${appointment.patient_id}
                    </div>
                    ${appointment.patient && appointment.patient.info && appointment.patient.info.phone ?
                        `<div class="mb-3"><strong>Phone:</strong> ${appointment.patient.info.phone}</div>` : ''}
                    ${appointment.patient && appointment.patient.info && appointment.patient.info.email ?
                        `<div class="mb-3"><strong>Email:</strong> ${appointment.patient.info.email}</div>` : ''}
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Appointment Information</h6>
                    <div class="mb-3">
                        <strong>Date:</strong> ${formattedDate}
                    </div>
                    <div class="mb-3">
                        <strong>Time:</strong> ${formattedStartTime} - ${formattedEndTime}
                    </div>
                    <div class="mb-3">
                        <strong>Duration:</strong> ${appointment.duration_minutes} minutes
                    </div>
                    <div class="mb-3">
                        <strong>Service:</strong> ${serviceName}
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge ${statusBadgeClass}">${appointment.status}</span>
                    </div>
                    ${appointment.is_new_patient ? '<div class="mb-3"><span class="badge bg-info">New Patient</span></div>' : ''}
                </div>
            </div>
            ${appointment.notes ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Notes</h6>
                        <div class="border rounded p-3 bg-light">
                            ${appointment.notes}
                        </div>
                    </div>
                </div>
            ` : ''}
        `;

        // Populate the modal content
        document.getElementById('appointment-details-content').innerHTML = detailsHTML;

        // Store appointment ID for status change, delete, and reschedule actions
        document.getElementById('change-status-btn').setAttribute('data-appointment-id', appointment.id);
        document.getElementById('reschedule-appointment-btn').setAttribute('data-appointment-id', appointment.id);

        // Disable reschedule button if appointment is confirmed, completed, cancelled, or missed
        const rescheduleBtn = document.getElementById('reschedule-appointment-btn');
        const statusLower = (appointment.status || 'pending').toLowerCase();
        if (['confirmed', 'completed', 'cancelled', 'missed'].includes(statusLower)) {
            rescheduleBtn.disabled = true;
            rescheduleBtn.style.opacity = '0.5';
            rescheduleBtn.style.cursor = 'not-allowed';
            rescheduleBtn.title = `Cannot reschedule ${appointment.status.toLowerCase()} appointments`;
        } else {
            rescheduleBtn.disabled = false;
            rescheduleBtn.style.opacity = '1';
            rescheduleBtn.style.cursor = 'pointer';
            rescheduleBtn.title = 'Reschedule this appointment';
        }

        // Disable change status button unless appointment is Pending OR (Confirmed AND date is today)
        const changeStatusBtn = document.getElementById('change-status-btn');
        if (changeStatusBtn) {
            // Check if appointment is today
            const today = new Date();
            const appointmentDate = new Date(startDateTime);
            const isToday = appointmentDate.toDateString() === today.toDateString();
            
            // Enable if status is Pending OR (status is Confirmed AND appointment is today)
            if (statusLower === 'pending' || (statusLower === 'confirmed' && isToday)) {
                changeStatusBtn.disabled = false;
                changeStatusBtn.style.opacity = '1';
                changeStatusBtn.style.cursor = 'pointer';
                changeStatusBtn.title = 'Change appointment status';
            } else {
                changeStatusBtn.disabled = true;
                changeStatusBtn.style.opacity = '0.5';
                changeStatusBtn.style.cursor = 'not-allowed';
                if (statusLower === 'cancelled') {
                    changeStatusBtn.title = 'Cannot change status of cancelled appointments.';
                } else if (statusLower === 'completed') {
                    changeStatusBtn.title = 'Cannot change status of completed appointments.';
                } else if (statusLower === 'missed') {
                    changeStatusBtn.title = 'Cannot change status of missed appointments.';
                } else if (statusLower === 'confirmed' && !isToday) {
                    changeStatusBtn.title = 'Change status is only available for today\'s Confirmed appointments.';
                } else {
                    changeStatusBtn.title = 'Change status is only available for Pending appointments or Confirmed appointments scheduled for today.';
                }
            }
        }

        // Show the modal
        new bootstrap.Modal(document.getElementById('appointmentDetailsModal')).show();
    }

    function searchPatients(query) {
        // Show loading state
        const resultsContainer = document.getElementById('patient-results');
        resultsContainer.innerHTML = '<div class="text-muted p-2">Loading...</div>';
        resultsContainer.style.display = 'block';

        fetch(`/admin/appointment/search/patients?query=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(patients => {
        resultsContainer.innerHTML = '';

            if (patients.length > 0) {
                patients.forEach(patient => {
                const resultItem = document.createElement('div');
                resultItem.className = 'patient-result-item';
                resultItem.innerHTML = `
                    <div class="patient-name">${patient.name}</div>
                        <div class="patient-details">
                            <small class="text-muted">ID: ${patient.id}</small>
                            ${patient.phone ? `<small class="text-muted ms-2">Phone: ${patient.phone}</small>` : ''}
                        </div>
                `;
                resultItem.addEventListener('click', () => {
                    document.getElementById('patient_search').value = patient.name;
                    document.getElementById('patient_id').value = patient.id;
                    resultsContainer.style.display = 'none';
                });
                resultsContainer.appendChild(resultItem);
            });
            resultsContainer.style.display = 'block';
        } else {
                if (query.length === 0) {
                    resultsContainer.innerHTML = '<div class="text-muted p-2">Start typing to search patients...</div>';
                } else {
                    resultsContainer.innerHTML = '<div class="text-muted p-2">No patients found</div>';
                }
                resultsContainer.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error searching patients:', error);
            resultsContainer.innerHTML = '<div class="text-danger p-2">Error searching patients</div>';
            resultsContainer.style.display = 'block';
        });
    }

    function generateModalCalendar() {
        const calendarGrid = document.getElementById('modal-calendar');
        if (!calendarGrid) {
            console.error('Modal calendar grid not found');
            return;
        }

        // Ensure modalCurrentDate is valid
        if (!modalCurrentDate || isNaN(modalCurrentDate.getTime())) {
            modalCurrentDate = new Date();
        }

        calendarGrid.innerHTML = '';

        // Add day headers
        const days = ['S', 'M', 'T', 'W', 'Th', 'F', 'S'];
        days.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'calendar-day';
            dayHeader.style.fontWeight = '600';
            dayHeader.style.background = '#f8f9fa';
            dayHeader.textContent = day;
            calendarGrid.appendChild(dayHeader);
        });

        try {
            // Get first day of month and calculate grid
        const firstDay = new Date(modalCurrentDate.getFullYear(), modalCurrentDate.getMonth(), 1);
            const lastDay = new Date(modalCurrentDate.getFullYear(), modalCurrentDate.getMonth() + 1, 0);
            const daysInMonth = lastDay.getDate();
            const firstDayOfWeek = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.

            // Generate calendar grid (6 weeks × 7 days = 42 cells)
        for (let i = 0; i < 42; i++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';

                // Calculate if this cell should show a date
                const dayNumber = i - firstDayOfWeek + 1;

                if (dayNumber >= 1 && dayNumber <= daysInMonth) {
                    // This is a valid day in the current month
                    const cellDate = new Date(modalCurrentDate.getFullYear(), modalCurrentDate.getMonth(), dayNumber);

                    // Set text content explicitly
                    dayElement.textContent = String(dayNumber);
                    dayElement.setAttribute('data-day', dayNumber);

                    // Ensure element is visible
                    dayElement.style.visibility = 'visible';
                    dayElement.style.opacity = '1';
                    dayElement.style.display = 'flex';

            if (isToday(cellDate)) {
                dayElement.classList.add('today');
            }

                    // CRITICAL: Check if date is in the past using SERVER time (fault tolerant)
                    const serverNow = getServerTime();
                    const serverToday = new Date(serverNow);
                    serverToday.setHours(0, 0, 0, 0);
                    const appointmentDate = new Date(cellDate);
                    appointmentDate.setHours(0, 0, 0, 0);

                    console.log('Calendar date check (using server time):', {
                        cellDate: cellDate.toDateString(),
                        serverToday: serverToday.toDateString(),
                        isPast: appointmentDate < serverToday
                    });

                    if (appointmentDate < serverToday) {
                        dayElement.classList.add('past-date');
                        dayElement.style.opacity = '0.5';
                        dayElement.style.cursor = 'not-allowed';
                    } else {
                        // Add click handler for date selection (only for future dates)
                        dayElement.addEventListener('click', function() {
                // Remove previous selection
                document.querySelectorAll('.calendar-widget .calendar-day.selected').forEach(day => {
                    day.classList.remove('selected');
                });

                // Add selection to clicked day
                this.classList.add('selected');

                // Set selected date
                const selectedDateInput = document.getElementById('selected_date');
                if (selectedDateInput) {
                                // Use local date to avoid timezone issues
                                const year = cellDate.getFullYear();
                                const month = String(cellDate.getMonth() + 1).padStart(2, '0');
                                const day = String(cellDate.getDate()).padStart(2, '0');
                                const dateString = `${year}-${month}-${day}`;

                                selectedDateInput.value = dateString;
                                console.log('Date selected:', {
                                    cellDate: cellDate.toDateString(),
                                    dateString: dateString,
                                    year: year,
                                    month: month,
                                    day: day
                                });

                                // Update time slot availability based on blocked times
                                updateTimeSlotAvailability(dateString);
                            }
                        });
                    }
                } else {
                    // Empty cell for days outside current month
                    dayElement.textContent = '';
                    dayElement.style.visibility = 'hidden'; // Hide empty cells
                    dayElement.style.opacity = '0';
                }

            calendarGrid.appendChild(dayElement);
        }

        // Update month display
        const monthYearElement = document.getElementById('current-month-year');
        if (monthYearElement) {
            monthYearElement.textContent =
                modalCurrentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        }

        console.log('Modal calendar generated successfully');
        } catch (error) {
            console.error('Error generating modal calendar:', error);
            // Reset to current date if there's an error
            modalCurrentDate = new Date();
            generateModalCalendar();
        }
    }

    // Validation helper functions
    function showValidationMessage(message, type) {
        const messageContainer = document.getElementById('validation-messages');
        const alertClass = type === 'error' ? 'alert-danger' :
                          type === 'success' ? 'alert-success' :
                          type === 'info' ? 'alert-info' : 'alert-warning';

        messageContainer.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        messageContainer.style.display = 'block';

        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => {
                const alert = messageContainer.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => {
                        messageContainer.style.display = 'none';
                    }, 150);
                }
            }, 3000);
        }
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (field) {
            // Add error styling
            field.classList.add('is-invalid');

            // Remove existing error message
            const existingError = field.parentNode.querySelector('.invalid-feedback');
            if (existingError) {
                existingError.remove();
            }

            // Add new error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }
    }

    function clearValidationMessages() {
        // Clear validation messages
        const messageContainer = document.getElementById('validation-messages');
        messageContainer.style.display = 'none';
        messageContainer.innerHTML = '';

        // Clear field errors
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });

        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
    }

    // Clear validation when modal is opened
    document.getElementById('appointmentModal').addEventListener('shown.bs.modal', function() {
        clearValidationMessages();
    });

    // Cancel appointment functionality is now integrated into Change Status modal

    // Reschedule appointment functionality
    document.getElementById('reschedule-appointment-btn').addEventListener('click', function() {
        const appointmentId = this.getAttribute('data-appointment-id');
        if (appointmentId) {
            rescheduleAppointment(appointmentId);
        }
    });

    // Reschedule form handling
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveReschedule();
    });


    function rescheduleAppointment(appointmentId) {
        // Fetch fresh appointment data from server
        fetch(`/admin/appointment/${appointmentId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(appointment => {
            console.log('Fresh appointment data for reschedule:', appointment);
            populateRescheduleModal(appointment);
        })
        .catch(error => {
            console.error('Error fetching appointment:', error);
            // Fall back to cached data
            const appointment = appointments.find(apt => apt.id == appointmentId);
            if (!appointment) {
                showValidationMessage('Appointment not found', 'error');
                return;
            }
            populateRescheduleModal(appointment);
        });
    }

    function populateRescheduleModal(appointment) {
        // Close the details modal
        bootstrap.Modal.getInstance(document.getElementById('appointmentDetailsModal')).hide();

        // Populate the reschedule modal with existing data
        document.getElementById('reschedule_appointment_id').value = appointment.id;

        // Set patient information (read-only display)
        let patientName = 'Unknown Patient';
        if (appointment.patient && appointment.patient.info) {
            const info = appointment.patient.info;
            patientName = `${info.first_name} ${info.last_name}`.trim();
        } else if (appointment.patient && appointment.patient.name) {
            patientName = appointment.patient.name;
        }
        document.getElementById('reschedule_patient_name').textContent = patientName;

        // Set service name (read-only display)
        let serviceName = 'No Service';
        if (appointment.service && appointment.service.service_name) {
            serviceName = appointment.service.service_name;
        } else if (appointment.reason_for_visit) {
            // Use reason_for_visit as fallback
            serviceName = appointment.reason_for_visit;
        } else if (appointment.service_id && (!appointment.service || appointment.service === null)) {
            // Service ID exists but service was deleted or doesn't exist
            serviceName = 'Service Not Found (ID: ' + appointment.service_id + ')';
        }
        document.getElementById('reschedule_service_name').textContent = serviceName;

        // Set notes (read-only display)
        document.getElementById('reschedule_notes').textContent = appointment.notes || 'None';

        // Parse appointment date as LOCAL time to avoid timezone conversion
        const appointmentDate = parseLocalDateTime(appointment.start_datetime);
        rescheduleModalCurrentDate = new Date(appointmentDate);
        generateRescheduleModalCalendar();

        // Format date for input
        const year = appointmentDate.getFullYear();
        const month = String(appointmentDate.getMonth() + 1).padStart(2, '0');
        const day = String(appointmentDate.getDate()).padStart(2, '0');
        const selectedDate = `${year}-${month}-${day}`;
        document.getElementById('reschedule_selected_date').value = selectedDate;

        // Get appointment duration (from service or duration_minutes)
        let durationMinutes = appointment.duration_minutes || 30;
        if (appointment.service && appointment.service.default_duration_minutes) {
            durationMinutes = appointment.service.default_duration_minutes;
        }

        // Generate time slots for reschedule
        generateRescheduleTimeSlots(durationMinutes, selectedDate);

        // Set time slot based on start time
        const startTime = appointmentDate.toTimeString().slice(0, 5); // HH:MM format
        const timeSlotBtn = document.querySelector(`#reschedule-time-slots-list .time-slot-btn[data-time-value*="${startTime}"]`);
        if (timeSlotBtn) {
            timeSlotBtn.click(); // Trigger click to select
        }

        // Show the reschedule modal
        new bootstrap.Modal(document.getElementById('rescheduleModal')).show();
    }

    function resetAppointmentForm() {
        // Reset form fields
        document.getElementById('appointment_id').value = '';
        document.getElementById('appointmentModalTitle').textContent = 'Add Appointment';
        document.getElementById('service_name').value = '';
        document.getElementById('notes').value = '';
        document.getElementById('selected_date').value = '';

        // Reset patient search
        document.getElementById('patient_search').value = '';
        document.getElementById('patient_id').value = '';

        // Hide custom time inputs
        

        // Clear validation messages
        clearValidationMessages();
    }

    // Reschedule modal variables
    let rescheduleModalCurrentDate = new Date();

    // Generate reschedule modal calendar
    function generateRescheduleModalCalendar() {
        const calendarGrid = document.getElementById('reschedule-modal-calendar');
        if (!calendarGrid) return;

        const year = rescheduleModalCurrentDate.getFullYear();
        const month = rescheduleModalCurrentDate.getMonth();

        // Update month/year display
        const monthYearElement = document.getElementById('reschedule-current-month-year');
        if (monthYearElement) {
            monthYearElement.textContent = rescheduleModalCurrentDate.toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });
        }

        // Clear existing calendar
        calendarGrid.innerHTML = '';

        // Add day headers
        const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        dayHeaders.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'calendar-day-header';
            dayHeader.textContent = day;
            calendarGrid.appendChild(dayHeader);
        });

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day other-month';
            calendarGrid.appendChild(emptyDay);
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;
            dayElement.dataset.day = day;

            // Check if this is today
            const today = new Date();
            const isToday = year === today.getFullYear() &&
                          month === today.getMonth() &&
                          day === today.getDate();

            // Check if this is a past date (Philippines timezone)
            const phToday = new Date(today.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
            const phNow = new Date(phToday.getFullYear(), phToday.getMonth(), phToday.getDate());
            const currentDay = new Date(year, month, day);
            const isPastDate = currentDay < phNow;

            if (isToday) {
                dayElement.classList.add('today');
            }
            if (isPastDate) {
                dayElement.classList.add('past-date');
                dayElement.style.pointerEvents = 'none';
                dayElement.style.opacity = '0.5';
            }

            // Add click event
            dayElement.addEventListener('click', function() {
                if (!isPastDate) {
                    // Remove previous selection
                    calendarGrid.querySelectorAll('.calendar-day').forEach(day => {
                        day.classList.remove('selected');
                    });

                    // Add selection to clicked day
                    this.classList.add('selected');

                    // Update selected date input
                    const selectedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    document.getElementById('reschedule_selected_date').value = selectedDate;

                    // Update time slot availability based on blocked times
                    updateRescheduleTimeSlotAvailability(selectedDate);
                }
            });

            calendarGrid.appendChild(dayElement);
        }

        // Add empty cells to complete the grid
        const totalCells = calendarGrid.children.length;
        const remainingCells = 42 - totalCells; // 6 rows * 7 days = 42 cells
        for (let i = 0; i < remainingCells; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day other-month';
            calendarGrid.appendChild(emptyDay);
        }
    }

    // Generate time slots for reschedule modal
    function generateRescheduleTimeSlots(durationMinutes, selectedDate) {
        const timeSlotsList = document.getElementById('reschedule-time-slots-list');
        if (!timeSlotsList) return;

        timeSlotsList.innerHTML = '';
        updateRescheduleTimeSelectedStatus('');

        if (!durationMinutes || !selectedDate) {
            timeSlotsList.innerHTML = '<div class="text-muted text-center w-100" style="padding: 20px; grid-column: 1 / -1; display: flex; justify-content: center; align-items: center; min-height: 150px;"><small>Please select a date to view available time slots</small></div>';
            return;
        }

        const [year, month, day] = selectedDate.split('-').map(Number);
        const clinicOpenHour = 11;
        const clinicCloseHour = 18;

        // Get the appointment ID being rescheduled (to exclude it from conflicts)
        const rescheduleAppointmentId = document.getElementById('reschedule_appointment_id')?.value;

        // Find blocked times and appointments on the selected date
        const selectedDateObj = new Date(year, month - 1, day);
        const blockedTimesOnDate = (blockedTimes || []).filter(blocked => {
            const btDate = parseLocalDateTime(blocked.start_datetime);
            return btDate && btDate.toDateString() === selectedDateObj.toDateString();
        });

        const bookedOnDate = (appointments || []).filter(apt => {
            if (!apt.start_datetime) return false;
            const aptDate = parseLocalDateTime(apt.start_datetime);
            if (!aptDate) return false;
            // Exclude the appointment being rescheduled and cancelled/blocked appointments
            const status = (apt.status || '').toLowerCase();
            if (status === 'cancelled' || status === 'blocked') return false;
            if (apt.id == rescheduleAppointmentId) return false; // Exclude current appointment
            return aptDate.toDateString() === selectedDateObj.toDateString();
        });

        let slotIndex = 1;
        for (let hour = clinicOpenHour; hour < clinicCloseHour; hour++) {
            for (let minute = 0; minute < 60; minute += 15) {
                const start = new Date(year, month - 1, day, hour, minute, 0);
                const end = new Date(start.getTime() + durationMinutes * 60000);

                if (end.getHours() > clinicCloseHour || (end.getHours() === clinicCloseHour && end.getMinutes() > 0)) {
                    continue;
                }

                const val = `${String(start.getHours()).padStart(2,'0')}:${String(start.getMinutes()).padStart(2,'0')}-${String(end.getHours()).padStart(2,'0')}:${String(end.getMinutes()).padStart(2,'0')}`;
                const startLabel = start.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

                // Check for conflicts
                let isBlocked = false;
                let isBooked = false;

                // Check blocked times
                for (const blocked of blockedTimesOnDate) {
                    const blockedStart = parseLocalDateTime(blocked.start_datetime);
                    const blockedEnd = parseLocalDateTime(blocked.end_datetime);
                    if (blockedStart && blockedEnd && start < blockedEnd && end > blockedStart) {
                        isBlocked = true;
                        break;
                    }
                }

                // Check booked appointments
                if (!isBlocked) {
                    for (const apt of bookedOnDate) {
                        const aptStart = parseLocalDateTime(apt.start_datetime);
                        const aptEnd = parseLocalDateTime(apt.end_datetime);
                        if (aptStart && aptEnd && start < aptEnd && end > aptStart) {
                            isBooked = true;
                            break;
                        }
                    }
                }

                // Create button
                const slotBtn = document.createElement('button');
                slotBtn.type = 'button';
                slotBtn.className = 'time-slot-btn';
                if (isBlocked || isBooked) {
                    slotBtn.disabled = true;
                    slotBtn.classList.add('time-slot-blocked');
                    slotBtn.title = isBlocked ? 'This time slot is blocked' : 'This time slot is already booked';
                }
                slotBtn.setAttribute('data-time-value', val);
                slotBtn.textContent = startLabel;
                
                // Create hidden radio input for form submission
                const hiddenRadio = document.createElement('input');
                hiddenRadio.type = 'radio';
                hiddenRadio.name = 'reschedule_time_slot';
                hiddenRadio.id = `reschedule_slot${slotIndex}`;
                hiddenRadio.value = val;
                hiddenRadio.style.display = 'none';
                
                slotBtn.addEventListener('click', function() {
                    if (this.disabled) return;
                    
                    // Remove selected class from all buttons
                    document.querySelectorAll('#reschedule-time-slots-list .time-slot-btn').forEach(btn => {
                        btn.classList.remove('selected');
                    });
                    
                    // Uncheck all radios
                    document.querySelectorAll('input[name="reschedule_time_slot"]').forEach(radio => {
                        radio.checked = false;
                    });
                    
                    // Select this button and radio
                    this.classList.add('selected');
                    hiddenRadio.checked = true;
                    
                    // Update status
                    updateRescheduleTimeSelectedStatus(startLabel);
                    
                    // Update end time preview
                    updateRescheduleEndTimePreview(durationMinutes, selectedDate, val);
                });
                
                timeSlotsList.appendChild(slotBtn);
                timeSlotsList.appendChild(hiddenRadio);
                slotIndex++;
            }
        }

        if (slotIndex === 1) {
            timeSlotsList.innerHTML = '<div class="text-muted text-center w-100" style="padding: 20px; grid-column: 1 / -1; display: flex; justify-content: center; align-items: center; min-height: 150px;"><small>No available time slots for this date</small></div>';
        }
    }

    // Update reschedule time selected status
    function updateRescheduleTimeSelectedStatus(timeLabel) {
        const statusEl = document.getElementById('reschedule-time-selected-status');
        if (statusEl) {
            if (timeLabel) {
                statusEl.textContent = timeLabel;
                statusEl.classList.remove('text-muted');
                statusEl.style.color = '#2196F3';
            } else {
                statusEl.textContent = 'No time selected';
                statusEl.classList.add('text-muted');
                statusEl.style.color = '';
            }
        }
    }

    // Update reschedule end time preview
    function updateRescheduleEndTimePreview(durationMinutes, selectedDate, timeValue) {
        const preview = document.getElementById('reschedule-end-time-preview');
        if (!preview || !timeValue) {
            if (preview) preview.style.display = 'none';
            return;
        }

        const [y, m, d] = selectedDate.split('-').map(Number);
        const [hh, mm] = timeValue.split('-')[0].split(':').map(Number);
        if (Number.isNaN(hh) || Number.isNaN(mm)) {
            preview.style.display = 'none';
            return;
        }

        const start = new Date(y, m - 1, d, hh, mm, 0);
        const end = new Date(start.getTime() + durationMinutes * 60000);
        const endLabel = end.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        preview.textContent = `Ends at ${endLabel}`;
        preview.style.display = 'block';
    }

    // Function to update reschedule time slot availability when date changes
    function updateRescheduleTimeSlotAvailability(selectedDate) {
        // Get appointment duration from the appointment being rescheduled
        const appointmentId = document.getElementById('reschedule_appointment_id')?.value;
        if (!appointmentId) return;

        const appointment = appointments.find(apt => apt.id == appointmentId);
        if (!appointment) return;

        let durationMinutes = appointment.duration_minutes || 30;
        if (appointment.service && appointment.service.default_duration_minutes) {
            durationMinutes = appointment.service.default_duration_minutes;
        }

        // Regenerate time slots for the new date
        generateRescheduleTimeSlots(durationMinutes, selectedDate);
    }

    // Reschedule modal navigation
    document.getElementById('reschedule-prev-month').addEventListener('click', function() {
        rescheduleModalCurrentDate.setMonth(rescheduleModalCurrentDate.getMonth() - 1);
        generateRescheduleModalCalendar();
    });

    document.getElementById('reschedule-next-month').addEventListener('click', function() {
        rescheduleModalCurrentDate.setMonth(rescheduleModalCurrentDate.getMonth() + 1);
        generateRescheduleModalCalendar();
    });

    // Reschedule time slot selection is now handled in generateRescheduleTimeSlots function

    // Save reschedule function
    function saveReschedule() {
        const appointmentId = document.getElementById('reschedule_appointment_id').value;
        const selectedDate = document.getElementById('reschedule_selected_date').value;
        const timeSlot = document.querySelector('input[name="reschedule_time_slot"]:checked');

        // Clear previous validation messages
        clearRescheduleValidationMessages();

        // Validation
        if (!selectedDate) {
            showRescheduleValidationMessage('Please select a date', 'error');
            return;
        }

        if (!timeSlot) {
            showRescheduleValidationMessage('Please select a time slot', 'error');
            return;
        }

        // Get start time
        let startTime;
        if (timeSlot.value === 'custom') {
            
            if (!startTime) {
                showRescheduleValidationMessage('Please enter a custom start time', 'error');
                return;
            }
        } else {
            startTime = timeSlot.value.split('-')[0]; // Get start time from range
        }

        // Validate date and time
        const [year, month, day] = selectedDate.split('-').map(Number);
        const [hours, minutes] = startTime.split(':').map(Number);

        const appointmentDateTime = new Date(year, month - 1, day, hours, minutes);
        const now = new Date();

        // Check if appointment is in the past (Philippines timezone)
        const phNow = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
        if (appointmentDateTime <= phNow) {
            showRescheduleValidationMessage('Cannot reschedule to a past date or time', 'error');
            return;
        }

        // Get the appointment data to include patient_id
        const appointment = appointments.find(apt => apt.id == appointmentId);
        if (!appointment) {
            showRescheduleValidationMessage('Appointment not found', 'error');
            return;
        }

        // Create datetime string in local format (YYYY-MM-DD HH:mm:ss) to avoid timezone conversion
        const startDateTimeStr = `${selectedDate} ${startTime}:00`;

        // Prepare data for update
        const updateData = {
            patient_id: appointment.patient_id,
            service_id: appointment.service_id,
            start_datetime: startDateTimeStr,
            duration_minutes: appointment.duration_minutes,
            status: appointment.status,
            notes: appointment.notes,
            is_new_patient: appointment.is_new_patient,
            _method: 'PUT',
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        // Show loading state
        const submitBtn = document.querySelector('#rescheduleForm button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Rescheduling...';

        // Add delay
        setTimeout(() => {
            fetch(`/admin/appointment/${appointmentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updateData)
            })
            .then(response => {
                console.log('Reschedule response status:', response.status);
                console.log('Reschedule response ok:', response.ok);

                // Parse JSON regardless of status to get error messages
                return response.json().then(data => ({
                    ok: response.ok,
                    status: response.status,
                    data: data
                }));
            })
            .then(({ok, status, data}) => {
                console.log('Reschedule response data:', data);

                if (ok && data.success) {
                    // Close the reschedule modal
                    bootstrap.Modal.getInstance(document.getElementById('rescheduleModal')).hide();

                    // Show success message
                    showValidationMessage('Appointment rescheduled successfully!', 'success');

                    // Navigate to the month of the rescheduled appointment
                    setTimeout(() => {
                        const rescheduledDate = new Date(year, month - 1, day);
                        const url = new URL(window.location.href);
                        url.searchParams.set('month', month);
                        url.searchParams.set('year', year);
                        url.searchParams.set('day', day);
                        window.location.href = url.toString();
                    }, 1500);
                } else {
                    // Handle validation errors or other errors
                    let errorMessage = 'Unknown error occurred';

                    if (data.message) {
                        errorMessage = data.message;
                    } else if (data.errors) {
                        // If there are field-specific errors, show the first one
                        const firstError = Object.values(data.errors)[0];
                        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                    }

                    console.log('Error message:', errorMessage);
                    showRescheduleValidationMessage(errorMessage, 'error');
                }
            })
            .catch(error => {
                console.error('Error rescheduling appointment:', error);
                showRescheduleValidationMessage('Network error. Please try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }, 500); // 500ms delay
    }

    // Reschedule validation helper functions
    function showRescheduleValidationMessage(message, type) {
        const messagesDiv = document.getElementById('reschedule-validation-messages');
        messagesDiv.innerHTML = `
            <div class="alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        messagesDiv.style.display = 'block';
    }

    function clearRescheduleValidationMessages() {
        const messagesDiv = document.getElementById('reschedule-validation-messages');
        messagesDiv.innerHTML = '';
        messagesDiv.style.display = 'none';
    }

    // ============ STATUS CHANGE FUNCTIONALITY ============

    // Change Status Button Handler
    const changeStatusBtn = document.getElementById('change-status-btn');
    if (changeStatusBtn) {
        changeStatusBtn.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-appointment-id');
            if (appointmentId) {
                openStatusChangeModal(appointmentId);
            } else {
                showValidationMessage('Error: No appointment selected', 'error');
            }
        });
    }

    function openStatusChangeModal(appointmentId) {
        // Fetch fresh appointment data
        fetch(`/admin/appointment/${appointmentId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(appointment => {
        // Prevent changing status of cancelled appointments
        if (appointment.status && appointment.status.toLowerCase() === 'cancelled') {
            showValidationMessage('Cannot change status of cancelled appointments.', 'error');
            return;
        }

        // Prevent changing status of confirmed appointments (they can only be completed)
        if (appointment.status && appointment.status.toLowerCase() === 'confirmed') {
            // Still allow opening the modal - it will show only "Completed" as option
            // This check is handled by the validTransitions in populateStatusChangeModal
        }
            populateStatusChangeModal(appointment);
        })
        .catch(error => {
            console.error('Error fetching appointment:', error);
            showValidationMessage('Error loading appointment data', 'error');
        });
    }

    function populateStatusChangeModal(appointment) {
        // Close details modal
        const detailsModal = bootstrap.Modal.getInstance(document.getElementById('appointmentDetailsModal'));
        if (detailsModal) {
            detailsModal.hide();
        }

        // Set appointment data
        document.getElementById('status_change_appointment_id').value = appointment.id;
        document.getElementById('status_change_current_status').value = appointment.status;

        // Set patient name
        let patientName = 'Unknown Patient';
        if (appointment.patient && appointment.patient.info) {
            const info = appointment.patient.info;
            patientName = `${info.first_name} ${info.last_name}`.trim();
        } else if (appointment.patient && appointment.patient.name) {
            patientName = appointment.patient.name;
        }
        document.getElementById('status_change_patient_name').textContent = patientName;

        // Set appointment info
        let serviceName = 'No Service';
        if (appointment.service && appointment.service.service_name) {
            serviceName = appointment.service.service_name;
        } else if (appointment.reason_for_visit) {
            // Use reason_for_visit as fallback
            serviceName = appointment.reason_for_visit;
        } else if (appointment.service_id && (!appointment.service || appointment.service === null)) {
            // Service ID exists but service was deleted or doesn't exist
            serviceName = 'Service Not Found (ID: ' + appointment.service_id + ')';
        }

        const startDateTime = parseLocalDateTime(appointment.start_datetime);
        const formattedDate = startDateTime.toLocaleDateString('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
        const formattedTime = startDateTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        document.getElementById('status_change_appointment_info').textContent =
            `${serviceName} on ${formattedDate} at ${formattedTime}`;

        // Set current status badge
        const statusBadge = document.getElementById('current_status_badge');
        statusBadge.textContent = appointment.status;
        statusBadge.className = 'badge fs-6 px-3 py-2 ';

        const statusClass = appointment.status.toLowerCase();
        if (statusClass === 'pending') {
            statusBadge.classList.add('bg-warning', 'text-dark');
        } else if (statusClass === 'confirmed') {
            statusBadge.classList.add('bg-primary');
        } else if (statusClass === 'completed') {
            statusBadge.classList.add('bg-success');
        } else if (statusClass === 'cancelled') {
            statusBadge.classList.add('bg-brown');
        } else if (statusClass === 'missed') {
            statusBadge.classList.add('bg-secondary');
        }

        // Populate available status transitions
        const validTransitions = {
            'Pending': ['Confirmed', 'Cancelled'],
            'Confirmed': ['Completed'],
            'Completed': [],
            'Cancelled': [],
            'Missed': []
        };

        const newStatusSelect = document.getElementById('new_status');
        newStatusSelect.innerHTML = '<option value="">Select new status...</option>';

        let availableStatuses = validTransitions[appointment.status] || [];

        // CRITICAL: Check if appointment date is today using SERVER time (fault tolerant)
        const appointmentDate = parseLocalDateTime(appointment.start_datetime);
        const serverNow = getServerTime();
        const serverToday = new Date(serverNow);
        serverToday.setHours(0, 0, 0, 0);
        const appointmentDateOnly = new Date(appointmentDate);
        appointmentDateOnly.setHours(0, 0, 0, 0);
        const isToday = appointmentDateOnly.toDateString() === serverToday.toDateString();

        // If appointment is not today, remove "Completed" from available statuses
        if (!isToday && availableStatuses.includes('Completed')) {
            availableStatuses = availableStatuses.filter(status => status !== 'Completed');
        }

        if (availableStatuses.length === 0) {
            newStatusSelect.innerHTML = '<option value="">No status changes available</option>';
            newStatusSelect.disabled = true;
            document.getElementById('status_transition_help').textContent =
                `${appointment.status} appointments cannot change status.`;
            document.getElementById('confirm-status-change-btn').disabled = true;
        } else {
            availableStatuses.forEach(status => {
                const option = document.createElement('option');
                option.value = status;
                option.textContent = status;
                // Special styling hint for cancelled status
                if (status === 'Cancelled') {
                    option.style.color = '#dc2626';
                }
                newStatusSelect.appendChild(option);
            });
            newStatusSelect.disabled = false;

            // Update help text based on available statuses
            if (availableStatuses.includes('Cancelled')) {
                document.getElementById('status_transition_help').innerHTML =
                    '<span class="text-danger"><i class="bi bi-info-circle me-1"></i>Select "Cancelled" to cancel this appointment. A note is required when cancelling.</span>';
            } else {
                document.getElementById('status_transition_help').textContent =
                    'Select a new status for this appointment';
            }
            document.getElementById('confirm-status-change-btn').disabled = false;
        }

        // Show/hide notes field based on selected status
        const notesContainer = document.getElementById('status_notes_container');
        const notesField = document.getElementById('status_change_notes');
        newStatusSelect.addEventListener('change', function() {
            if (this.value === 'Cancelled') {
                notesContainer.style.display = 'block';
                notesField.required = true;
            } else {
                notesContainer.style.display = 'none';
                notesField.required = false;
                notesField.value = ''; // Clear notes if not cancelling
            }
        });

        // Clear previous notes and validation
        document.getElementById('status_change_notes').value = '';
        document.getElementById('status-validation-message').style.display = 'none';

        // Show the modal after a short delay
        setTimeout(() => {
            new bootstrap.Modal(document.getElementById('changeStatusModal')).show();
        }, 300);
    }

    // Confirm status change
    document.getElementById('confirm-status-change-btn').addEventListener('click', function() {
        updateAppointmentStatus();
    });

    function updateAppointmentStatus() {
        const appointmentId = document.getElementById('status_change_appointment_id').value;
        const newStatus = document.getElementById('new_status').value;
        const notesField = document.getElementById('status_change_notes');
        const notes = notesField.value.trim();
        const currentStatus = document.getElementById('status_change_current_status').value;

        // Validation
        if (!newStatus) {
            showStatusValidationMessage('Please select a new status', 'danger');
            return;
        }

        if (newStatus === currentStatus) {
            showStatusValidationMessage('Please select a different status', 'warning');
            return;
        }

        // Require notes only when cancelling
        if (newStatus === 'Cancelled' && !notes) {
            showStatusValidationMessage('Please provide a reason for cancelling this appointment', 'danger');
            notesField.focus();
            return;
        }

        // Show loading state
        const confirmBtn = document.getElementById('confirm-status-change-btn');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Updating...';

        // Prepare request data - only include notes if status is Cancelled
        const requestData = {
            status: newStatus
        };
        if (newStatus === 'Cancelled' && notes) {
            requestData.notes = notes;
        }

        // Send request
        fetch(`/admin/appointment/${appointmentId}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close the status change modal
                bootstrap.Modal.getInstance(document.getElementById('changeStatusModal')).hide();

                // Show success message
                showValidationMessage(data.message || 'Status updated successfully!', 'success');

                // Reload page to show updated status
                setTimeout(() => {
                    reloadWithCurrentMonth();
                }, 1500);
            } else {
                showStatusValidationMessage(data.message || 'Error updating status', 'danger');
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            showStatusValidationMessage('Network error. Please try again.', 'danger');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
    }

    function showStatusValidationMessage(message, type) {
        const messageDiv = document.getElementById('status-validation-message');
        messageDiv.className = `alert alert-${type}`;
        messageDiv.textContent = message;
        messageDiv.style.display = 'block';

        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }

});
</script>

<style>
/* Dark Mode Fix for Cancel Button in Block Time Modal */
[data-theme="dark"] #blockTimeModal .btn-light {
    background-color: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #blockTimeModal .btn-light:hover {
    background-color: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #blockTimeModal .btn-light i {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Time Select styling */
#blockTimeModal .block-time-select {
    font-size: 0.9rem;
    padding: 0.45rem 0.75rem;
    border-radius: 8px;
}

#blockTimeModal .block-time-select option {
    font-size: 0.9rem;
}

/* Dark Mode Fix for Clock and Calendar Icons in Block Time Modal */
[data-theme="dark"] #blockTimeModal .form-label i,
[data-theme="dark"] #blockTimeModal .form-label .bi-clock,
[data-theme="dark"] #blockTimeModal .form-label .bi-clock-fill,
[data-theme="dark"] #blockTimeModal .form-label .bi-calendar3,
[data-theme="dark"] #blockTimeModal .form-label .bi-calendar-event,
[data-theme="dark"] #blockTimeModal .form-label .bi-calendar-event-fill {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Dark Mode Fix for Native Date Input Icons */
[data-theme="dark"] #blockTimeModal input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2) !important;
    cursor: pointer;
    opacity: 0.8;
}

[data-theme="dark"] #blockTimeModal input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
}

/* Dark mode select styling */
[data-theme="dark"] #blockTimeModal .block-time-select {
    background-color: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #blockTimeModal .block-time-select:focus {
    border-color: #00EAFF !important;
    box-shadow: 0 0 0 0.25rem rgba(0, 234, 255, 0.25) !important;
}

[data-theme="dark"] #blockTimeModal .block-time-select option {
    background-color: var(--dm-bg-secondary, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Firefox dark mode fix for date inputs */
[data-theme="dark"] #blockTimeModal input[type="date"] {
    color-scheme: dark;
}

/* Orange Button Styles */
.btn-orange {
    background-color: #ff6b35;
    border-color: #ff6b35;
    color: white;
}

.btn-orange:hover {
    background-color: #e55a2b;
    border-color: #e55a2b;
    color: white;
}

.btn-orange:focus {
    background-color: #e55a2b;
    border-color: #e55a2b;
    color: white;
    box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.5);
}

/* Dark Mode Orange Button */
[data-theme="dark"] .btn-orange {
    background-color: #ff6b35;
    border-color: #ff6b35;
    color: white;
}

[data-theme="dark"] .btn-orange:hover {
    background-color: #ff8555;
    border-color: #ff8555;
    color: white;
}

/* Add Appointment Modal - Compact Design */
#appointmentModal .modal-dialog {
    max-width: 375px;
    margin: 1rem auto;
    
}

#appointmentModal .modal-content {
    max-height: calc(100vh - 2rem);
    display: flex;
    flex-direction: column;
    
   
}

#appointmentModal .modal-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
    flex-shrink: 0;
}

#appointmentModal .modal-header .modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2196F3;
}

#appointmentModal .appointment-modal-body {
    padding: 1rem;
    overflow-y: auto;
    flex: 1;
    max-height: calc(100vh - 140px);
}

#appointmentModal .appointment-label {
    font-size: 0.85rem;
    margin-bottom: 0.375rem;
    color: #495057;
}

/* Compact Calendar Widget */
#appointmentModal .appointment-calendar {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: visible;
    background: #fff;
    min-height: auto;
    max-height: none;
    height: auto;
}

#appointmentModal .calendar-widget {
    background: #fff;
    width: 100%;
    min-height: auto;
    max-height: none;
    height: auto;
    display: flex;
    flex-direction: column;
    overflow: visible;
}

#appointmentModal .calendar-widget .calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    flex-shrink: 0;
    min-height: 28px;
    height: 28px;
}

#appointmentModal .calendar-widget .calendar-header span {
    font-weight: 600;
    color: #495057;
    font-size: 0.75rem;
}

#appointmentModal .calendar-widget .calendar-header button {
    width: 20px;
    height: 20px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
}

#appointmentModal .calendar-widget .calendar-grid {
    grid-template-columns: repeat(7, 1fr) !important;
    grid-auto-rows: 20px !important;
    gap: 1px;
    background: #e9ecef;
    padding: 2px;
    min-height: 0;
    height: auto;
    max-height: none;
    overflow: visible;
    border: none;
    border-radius: 0;
    flex: 1;
    display: grid;
}

#appointmentModal .calendar-widget .calendar-day {
    background: #fff !important;
    padding: 0;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.2s;
    min-height: 20px !important;
    height: 20px !important;
    max-height: 20px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.7rem !important;
    border: 1px solid #e9ecef;
    font-weight: 500;
    color: #000 !important;
    overflow: visible !important;
    line-height: 1 !important;
    white-space: nowrap;
    position: relative;
    z-index: 1;
}

#appointmentModal .calendar-widget .calendar-day:hover {
    background: #e9ecef !important;
}

#appointmentModal .calendar-widget .calendar-day.selected {
    background: #2196F3 !important;
    color: white !important;
    border-color: #2196F3 !important;
    font-weight: 600;
}

#appointmentModal .calendar-widget .calendar-day.today {
    background: #e3f2fd !important;
    font-weight: 600;
}

#appointmentModal .calendar-widget .calendar-day.today.selected {
    background: #2196F3 !important;
    color: white !important;
}

#appointmentModal .calendar-widget .calendar-day.past-date {
    background: #f8f9fa !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    opacity: 0.5 !important;
}

/* Compact Time Slots - Fit All Without Scrolling */
#appointmentModal .time-slots-container {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.5rem;
    min-height: auto;
    max-height: none;
    height: auto;
    overflow: visible;
}

#appointmentModal .time-slots-list {
    max-height: none;
    height: auto;
    overflow: visible;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.2rem;
}

#appointmentModal .time-slots-list .form-check {
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 0.25rem 0.375rem;
    margin: 0;
    transition: all 0.2s ease;
    cursor: pointer;
    background: #fff;
    display: flex;
    align-items: center;
    min-height: 28px;
}

#appointmentModal .time-slots-list .form-check:hover {
    background-color: #f8f9fa;
    border-color: #2196F3;
}

#appointmentModal .time-slots-list .form-check-label {
    font-size: 0.75rem;
    color: #495057;
    cursor: pointer;
    flex: 1;
    margin: 0;
    padding-left: 0.2rem;
    white-space: nowrap;
}

#appointmentModal .time-slots-list .form-check.selected {
    background-color: #2196F3 !important;
    border-color: #2196F3 !important;
    color: white !important;
}

#appointmentModal .time-slots-list .form-check.selected .form-check-label {
    color: white !important;
    font-weight: 600;
}

#appointmentModal .time-slots-list .form-check.disabled,
#appointmentModal .time-slots-list .form-check.time-slot-blocked {
    background-color: #f8d7da !important;
    border-color: #f5c6cb !important;
    cursor: not-allowed !important;
    opacity: 0.6;
    pointer-events: none;
}

#appointmentModal .time-slots-list .form-check.disabled .form-check-label,
#appointmentModal .time-slots-list .form-check.time-slot-blocked .form-check-label {
    color: #721c24 !important;
    text-decoration: line-through;
}

/* Dark Mode - Add Appointment Modal */
[data-theme="dark"] #appointmentModal .modal-content {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #appointmentModal .modal-header {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #appointmentModal .modal-header .modal-title {
    color: #00EAFF !important;
}

[data-theme="dark"] #appointmentModal .btn-close {
    filter: invert(1);
}

[data-theme="dark"] #appointmentModal .appointment-label {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .form-control,
[data-theme="dark"] #appointmentModal .form-select {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .form-control:focus,
[data-theme="dark"] #appointmentModal .form-select:focus {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: #00EAFF !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 0 0 0.25rem rgba(0, 234, 255, 0.25) !important;
}

[data-theme="dark"] #appointmentModal .btn-link {
    color: #00EAFF !important;
}

[data-theme="dark"] #appointmentModal .btn-link:hover {
    color: #5CECFF !important;
    text-shadow: 0 0 8px rgba(0, 234, 255, 0.5);
}

[data-theme="dark"] #appointmentModal .appointment-calendar {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-header {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-bottom-color: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-header span {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-header button {
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-header button:hover {
    background: var(--dm-bg-tertiary, #475569) !important;
    border-color: #00EAFF !important;
    color: #00EAFF !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-grid {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-day {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-day:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: #00EAFF !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-day.selected {
    background: #00EAFF !important;
    border-color: #00EAFF !important;
    color: #000 !important;
    box-shadow: 0 0 12px rgba(0, 234, 255, 0.6) !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-day.today {
    background: rgba(0, 234, 255, 0.1) !important;
    border-color: #00EAFF !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-day.today.selected {
    background: #00EAFF !important;
    color: #000 !important;
}

[data-theme="dark"] #appointmentModal .calendar-widget .calendar-day.past-date {
    background: var(--dm-bg-tertiary, #334155) !important;
    color: #64748b !important;
    opacity: 0.5 !important;
}

[data-theme="dark"] #appointmentModal .time-slots-container {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #appointmentModal .time-slots-list .form-check {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #appointmentModal .time-slots-list .form-check:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: #00EAFF !important;
}

[data-theme="dark"] #appointmentModal .time-slots-list .form-check-label {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .time-slots-list .form-check.selected {
    background: #00EAFF !important;
    border-color: #00EAFF !important;
    box-shadow: 0 0 12px rgba(0, 234, 255, 0.5) !important;
}

[data-theme="dark"] #appointmentModal .time-slots-list .form-check.selected .form-check-label {
    color: #000 !important;
    font-weight: 700;
}

[data-theme="dark"] #appointmentModal .time-slots-list .form-check.disabled,
[data-theme="dark"] #appointmentModal .time-slots-list .form-check.time-slot-blocked {
    background: rgba(248, 215, 218, 0.2) !important;
    border-color: #dc2626 !important;
}

[data-theme="dark"] #appointmentModal .form-text {
    color: var(--dm-text-secondary, #94a3b8) !important;
}

[data-theme="dark"] #appointmentModal .text-muted {
    color: var(--dm-text-secondary, #94a3b8) !important;
}

/* Patient Search Results Dropdown - Dark Mode */
#appointmentModal #patient-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    margin-top: 0.25rem;
}

#appointmentModal .patient-result-item {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

#appointmentModal .patient-result-item:last-child {
    border-bottom: none;
}

#appointmentModal .patient-result-item:hover {
    background-color: #f8f9fa;
}

#appointmentModal .patient-result-item .patient-name {
    font-weight: 600;
    color: #212529;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

#appointmentModal .patient-result-item .patient-details {
    font-size: 0.75rem;
    color: #6c757d;
}

[data-theme="dark"] #appointmentModal #patient-results {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] #appointmentModal .patient-result-item {
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #appointmentModal .patient-result-item:hover {
    background-color: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] #appointmentModal .patient-result-item .patient-name {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .patient-result-item .patient-details {
    color: var(--dm-text-secondary, #94a3b8) !important;
}

[data-theme="dark"] #appointmentModal .patient-result-item .patient-details .text-muted {
    color: var(--dm-text-secondary, #94a3b8) !important;
}

/* Custom Scrollbar for Patient Results */
#appointmentModal #patient-results::-webkit-scrollbar {
    width: 8px;
}

#appointmentModal #patient-results::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#appointmentModal #patient-results::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-radius: 4px;
}

#appointmentModal #patient-results::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
}

[data-theme="dark"] #appointmentModal #patient-results::-webkit-scrollbar-track {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] #appointmentModal #patient-results::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #00EAFF 0%, #0099CC 100%) !important;
}

[data-theme="dark"] #appointmentModal #patient-results::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5CECFF 0%, #00EAFF 100%) !important;
}

/* Firefox Scrollbar */
#appointmentModal #patient-results {
    scrollbar-width: thin;
    scrollbar-color: #2196F3 #f1f1f1;
}

[data-theme="dark"] #appointmentModal #patient-results {
    scrollbar-color: #00EAFF var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] #appointmentModal .modal-footer {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #appointmentModal .btn-secondary {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #appointmentModal .btn-secondary:hover {
    background: var(--dm-bg-quaternary, #475569) !important;
}

[data-theme="dark"] #appointmentModal .btn-primary {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
    border: none !important;
}

[data-theme="dark"] #appointmentModal .btn-primary:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%) !important;
    box-shadow: 0 0 12px rgba(33, 150, 243, 0.5) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #appointmentModal .time-slots-list {
        grid-template-columns: repeat(2, 1fr);
    }

    #appointmentModal .time-slots-list .form-check {
        padding: 0.2rem 0.3rem;
        min-height: 26px;
    }

    #appointmentModal .time-slots-list .form-check-label {
        font-size: 0.7rem;
    }

    #appointmentModal .calendar-widget .calendar-grid {
        grid-auto-rows: 18px !important;
        max-height: none !important;
        height: auto !important;
    }

    #appointmentModal .calendar-widget .calendar-day {
        min-height: 18px !important;
        max-height: 18px !important;
        height: 18px !important;
        font-size: 0.65rem !important;
        padding: 0 !important;
    }

    #appointmentModal .calendar-widget .calendar-header {
        min-height: 24px !important;
        height: 24px !important;
        padding: 0.2rem 0.4rem !important;
    }
}

/* Calendar View - Fit Screen Without Scrolling */
.calendar-container {
    max-height: calc(100vh - 250px) !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
}

.calendar-grid {
    max-height: calc(100vh - 250px) !important;
    overflow-y: auto !important;
    flex: 1 !important;
    min-height: 0 !important;
}

/* Ensure d-none properly hides calendar grids */
.calendar-grid.d-none {
    display: none !important;
    visibility: hidden !important;
}

/* Force hide month view when week or day view is visible */
.calendar-grid.week-view:not(.d-none) ~ .calendar-grid.month-view,
.calendar-grid.day-view:not(.d-none) ~ .calendar-grid.month-view {
    display: none !important;
    visibility: hidden !important;
}

/* Force hide week view when month or day view is visible */
.calendar-grid.month-view:not(.d-none) ~ .calendar-grid.week-view,
.calendar-grid.day-view:not(.d-none) ~ .calendar-grid.week-view {
    display: none !important;
    visibility: hidden !important;
}

/* Force hide day view when month or week view is visible */
.calendar-grid.month-view:not(.d-none) ~ .calendar-grid.day-view,
.calendar-grid.week-view:not(.d-none) ~ .calendar-grid.day-view {
    display: none !important;
    visibility: hidden !important;
}

.calendar-grid.month-view {
    grid-auto-rows: minmax(80px, auto) !important;
    max-height: calc(100vh - 250px) !important;
}

.calendar-grid.week-view {
    grid-auto-rows: minmax(120px, auto) !important;
    max-height: calc(100vh - 250px) !important;
}

.calendar-grid.day-view {
    max-height: calc(100vh - 250px) !important;
    overflow-y: auto !important;
}

/* Mobile Optimizations - Ultra Compact Calendar Like Reference */
@media (max-width: 768px) {
    .container-fluid.px-4.py-4 {
        padding: 0.15rem 0.3rem !important;
    }

    .row.mb-4 {
        margin-bottom: 0.3rem !important;
    }

    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .col-12 {
        padding-left: 0.1rem !important;
        padding-right: 0.1rem !important;
    }

    .d-flex.flex-wrap.justify-content-between.align-items-center.gap-3.mb-3 {
        gap: 0.15rem !important;
        margin-bottom: 0.3rem !important;
        justify-content: center !important;
    }

    /* Center control panel elements on mobile */
    .d-flex.flex-wrap.justify-content-between.align-items-center.gap-3.mb-3 > div {
        justify-content: center !important;
        width: 100% !important;
        margin-bottom: 0.5rem !important;
    }

    .d-flex.flex-wrap.justify-content-between.align-items-center.gap-3.mb-3 > div:last-child {
        margin-bottom: 0 !important;
    }

    /* Center calendar navigation on mobile */
    .d-flex.align-items-center.gap-2[style*="flex-shrink: 0"] {
        justify-content: center !important;
    }

    /* Center status legend on mobile */
    .d-flex.gap-3[style*="flex-wrap: nowrap"] {
        justify-content: center !important;
        flex-wrap: wrap !important;
    }

    /* Calendar Container - Maximize Space */
    .calendar-container {
        max-height: calc(100vh - 120px) !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .calendar-grid {
        max-height: calc(100vh - 120px) !important;
        margin: 0 !important;
        padding: 0 !important;
        gap: 0 !important;
    }

    .calendar-grid.month-view {
        max-height: calc(100vh - 120px) !important;
        grid-template-rows: auto 1fr !important;
    }

    .calendar-grid.week-view {
        max-height: calc(100vh - 120px) !important;
    }

    .calendar-grid.day-view {
        max-height: calc(100vh - 120px) !important;
    }

    /* Calendar Header Row - Ultra Compact */
    .calendar-grid .calendar-header-row,
    .calendar-grid > div:first-child {
        padding: 0.2rem 0 !important;
        margin: 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .calendar-grid .calendar-header-row > div,
    .calendar-grid > div:first-child > div {
        padding: 0.15rem 0.1rem !important;
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        text-align: center !important;
    }

    /* Reduce header spacing */
    h1.h2.fw-bold {
        font-size: 1rem !important;
        margin-bottom: 0.1rem !important;
        margin-top: 0 !important;
        line-height: 1.2 !important;
    }

    p.text-muted#appointment-summary {
        font-size: 0.65rem !important;
        margin-bottom: 0 !important;
        margin-top: 0 !important;
        line-height: 1.1 !important;
    }

    /* Compact control panel */
    .d-flex.flex-wrap.justify-content-between.align-items-center.gap-3.mb-3 {
        min-height: auto !important;
        margin-top: 0.15rem !important;
    }

    /* Smaller buttons on mobile */
    .btn {
        padding: 0.2rem 0.4rem !important;
        font-size: 0.65rem !important;
        margin: 0 !important;
    }

    .btn-sm {
        padding: 0.15rem 0.3rem !important;
        font-size: 0.65rem !important;
    }

    /* Reduce status legend spacing */
    .d-flex.gap-3 {
        gap: 0.15rem !important;
        flex-wrap: wrap !important;
        margin-top: 0.15rem !important;
        margin-bottom: 0.15rem !important;
        padding: 0.1rem 0 !important;
    }

    .status-dot {
        width: 6px !important;
        height: 6px !important;
    }

    .d-flex.gap-3 small {
        font-size: 0.6rem !important;
        line-height: 1 !important;
    }

    /* Ultra Compact calendar day cells - Match Reference Design */
    .calendar-day {
        min-height: 45px !important;
        padding: 2px 3px !important;
        margin: 0 !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        position: relative !important;
    }

    .day-number {
        font-size: 0.7rem !important;
        margin-bottom: 0 !important;
        margin-top: 0 !important;
        line-height: 1 !important;
        font-weight: 500 !important;
        position: absolute !important;
        top: 2px !important;
        left: 3px !important;
    }

    /* Appointment items - Very compact like reference */
    .appointment-item {
        font-size: 0.55rem !important;
        padding: 1px 3px !important;
        margin-bottom: 1px !important;
        line-height: 1.1 !important;
        border-radius: 2px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        margin-top: 0.5px !important;
        min-height: 12px !important;
        display: block !important;
    }

    /* Calendar grid spacing - No gaps */
    .calendar-grid.month-view {
        gap: 0 !important;
        border: none !important;
    }

    .calendar-grid.month-view > div {
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    /* Reduce header row spacing */
    .row.mb-4:first-of-type {
        margin-bottom: 0.2rem !important;
        margin-top: 0 !important;
    }

    /* Compact control buttons */
    .view-toggle {
        min-width: 50px !important;
        padding: 0.15rem 0.3rem !important;
        font-size: 0.65rem !important;
    }

    h5#current-period {
        min-width: 100px !important;
        font-size: 0.75rem !important;
        margin: 0 !important;
        padding: 0 0.2rem !important;
    }

    /* Reduce action button spacing */
    .btn-dark, .btn-primary[data-bs-toggle="modal"] {
        padding: 0.2rem 0.35rem !important;
        font-size: 0.65rem !important;
    }

    /* Remove all unnecessary margins */
    .mb-2, .mb-3, .mb-4 {
        margin-bottom: 0.15rem !important;
    }

    .mt-2, .mt-3, .mt-4 {
        margin-top: 0.15rem !important;
    }

    /* Compact navigation controls */
    .d-flex.align-items-center.gap-2 {
        gap: 0.15rem !important;
    }

    /* Ensure calendar takes full available height */
    .calendar-grid.month-view {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        grid-auto-rows: minmax(45px, 1fr) !important;
        height: 100% !important;
    }
}

.calendar-day {
    min-height: 80px !important;
    padding: 4px 6px !important;
}

.day-number {
    font-size: 0.85rem !important;
    margin-bottom: 2px !important;
}

.appointment-item {
    font-size: 0.7rem !important;
    padding: 2px 4px !important;
    margin-bottom: 1px !important;
}

.appointment-item.pending {
    background: #F59E0B !important;
    color: white !important;
    font-weight: 500 !important;
}

.appointment-item.confirmed {
    background: #3B82F6 !important;
    color: white !important;
    font-weight: 500 !important;
}

.appointment-item.completed {
    background: #047857 !important;
    color: white !important;
    font-weight: 500 !important;
}

[data-theme="dark"] .appointment-item.completed {
    background: #047857 !important;
    color: white !important;
    font-weight: 500 !important;
}

.appointment-item.blocked {
    background: #EF4444 !important;
    color: white !important;
    font-weight: 500 !important;
}

.appointment-item.missed {
    background: #6B7280 !important;
    color: white !important;
    font-weight: 500 !important;
    opacity: 0.7;
}

.appointment-item.missed .appointment-time,
.appointment-item.missed .appointment-title,
.appointment-item.missed .appointment-status,
.appointment-item.missed .appointment-notes,
.appointment-item.missed .appointment-meta,
.appointment-item.missed .appointment-details {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
}

.appointment-item.cancelled {
    background: #8b6f47 !important;
    color: white !important;
    font-weight: 500 !important;
}

[data-theme="dark"] .appointment-item.pending {
    background: #F59E0B !important;
    color: white !important;
    font-weight: 500 !important;
}

[data-theme="dark"] .appointment-item.confirmed {
    background: #3B82F6 !important;
    color: white !important;
    font-weight: 500 !important;
}

[data-theme="dark"] .appointment-item.blocked {
    background: #EF4444 !important;
    color: white !important;
    font-weight: 500 !important;
}

[data-theme="dark"] .appointment-item.missed {
    background: #6B7280 !important;
    color: white !important;
    font-weight: 500 !important;
}

[data-theme="dark"] .appointment-item.cancelled {
    background: #6d4c41 !important;
    color: white !important;
    font-weight: 500 !important;
}

.time-slot {
    padding: 6px 10px !important;
    min-height: 35px !important;
}

.day-header,
.week-header {
    padding: 8px 12px !important;
    font-size: 0.9rem !important;
    flex-shrink: 0 !important;
}

/* Dark Mode Calendar Container */
[data-theme="dark"] .calendar-container {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .calendar-grid {
    background: var(--dm-card-bg, #1e293b) !important;
}
</style>
<!-- Day Appointments Modal -->
<div class="modal fade" id="dayAppointmentsModal" tabindex="-1" aria-labelledby="dayAppointmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); border: none;">
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
/* Brown Badge for Cancelled Appointments */
.bg-brown {
    background-color: #d4a574 !important;
    color: #5d4037 !important;
}

/* Appointment Details Modal Button Styles */
.btn-purple {
    background-color: #6b21a8 !important;
    border-color: #6b21a8 !important;
    color: white !important;
}

.btn-purple:hover {
    background-color: #581c87 !important;
    border-color: #581c87 !important;
    color: white !important;
}

.btn-purple i {
    color: white !important;
}

.btn-yellow {
    background-color: #ca8a04 !important;
    border-color: #ca8a04 !important;
    color: white !important;
}

.btn-yellow:hover {
    background-color: #a16207 !important;
    border-color: #a16207 !important;
    color: white !important;
}

.btn-yellow i {
    color: white !important;
}

/* Dark Mode Button Styles */
[data-theme="dark"] .btn-purple {
    background-color: #7c3aed !important;
    border-color: #7c3aed !important;
    color: white !important;
}

[data-theme="dark"] .btn-purple:hover {
    background-color: #6d28d9 !important;
    border-color: #6d28d9 !important;
    color: white !important;
}

[data-theme="dark"] .btn-purple i {
    color: white !important;
}

[data-theme="dark"] .btn-yellow {
    background-color: #d97706 !important;
    border-color: #d97706 !important;
    color: white !important;
}

[data-theme="dark"] .btn-yellow:hover {
    background-color: #b45309 !important;
    border-color: #b45309 !important;
    color: white !important;
}

[data-theme="dark"] .btn-yellow i {
    color: white !important;
}

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

.day-appointments-list {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.day-appointment-item {
    padding: 0.875rem;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    border-left: 3px solid;
    transition: all 0.2s ease;
}

.day-appointment-item:hover {
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
    background: #f5e6d3;
    border-left-color: #d4a574;
    opacity: 1;
}

.day-appointment-item.cancelled .appointment-time {
    color: #8b6f47 !important;
}

.day-appointment-item.cancelled .appointment-title {
    color: #5d4037 !important;
}

.day-appointment-item.cancelled .appointment-status {
    color: #8b6f47 !important;
}

.day-appointment-item.cancelled .appointment-notes {
    color: #6d4c41 !important;
    border-top-color: rgba(139, 111, 71, 0.2) !important;
}

.day-appointment-item.blocked {
    background: #fee2e2;
    border-left-color: #ef4444;
}

.day-appointment-item.blocked .appointment-time {
    color: #dc2626 !important;
}

.day-appointment-item.blocked .appointment-title {
    color: #991b1b !important;
}

.day-appointment-item.blocked .appointment-status {
    color: #dc2626 !important;
}

.day-appointment-item.blocked .appointment-notes {
    color: #b91c1c !important;
    border-top-color: rgba(239, 68, 68, 0.2) !important;
}

.day-appointment-item.missed {
    background: #e5e7eb;
    border-left-color: #6b7280;
    opacity: 0.7;
}

.day-appointment-item.missed .appointment-time,
.day-appointment-item.missed .appointment-title,
.day-appointment-item.missed .appointment-status,
.day-appointment-item.missed .appointment-notes,
.day-appointment-item.missed .appointment-meta,
.day-appointment-item.missed .appointment-description {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
}

/* Dark mode parity for missed items */
[data-theme="dark"] .appointment-item.missed .appointment-time,
[data-theme="dark"] .appointment-item.missed .appointment-title,
[data-theme="dark"] .appointment-item.missed .appointment-status,
[data-theme="dark"] .appointment-item.missed .appointment-notes,
[data-theme="dark"] .appointment-item.missed .appointment-meta,
[data-theme="dark"] .appointment-item.missed .appointment-details,
[data-theme="dark"] .day-appointment-item.missed .appointment-time,
[data-theme="dark"] .day-appointment-item.missed .appointment-title,
[data-theme="dark"] .day-appointment-item.missed .appointment-status,
[data-theme="dark"] .day-appointment-item.missed .appointment-notes,
[data-theme="dark"] .day-appointment-item.missed .appointment-meta,
[data-theme="dark"] .day-appointment-item.missed .appointment-description {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
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

.appointment-status {
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 0.25rem;
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
    top: 0.25rem;
    right: 0.25rem;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
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
}

/* "X more" Indicator */
.event-more-indicator {
    background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(41, 128, 185, 0.05) 100%);
    border: 1px dashed #3498db;
    border-radius: 6px;
    padding: 0.375rem 0.5rem;
    margin-top: 0.25rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.event-more-indicator:hover {
    background: linear-gradient(135deg, rgba(52, 152, 219, 0.15) 0%, rgba(41, 128, 185, 0.1) 100%);
    border-color: #2980b9;
    transform: translateY(-1px);
}

.event-more-indicator .more-text {
    font-size: 0.7rem;
    font-weight: 600;
    color: #3498db;
}

/* Calendar Day Clickable */
.calendar-day {
    transition: all 0.2s ease;
    position: relative;
}

.calendar-day[data-day-appointments]:not([data-day-appointments="[]"]):hover {
    background: rgba(52, 152, 219, 0.03);
    cursor: pointer;
}

/* Dark Mode Styles */
[data-theme="dark"] #dayAppointmentsModal .modal-content {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #dayAppointmentsModal .modal-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #1a2e4a 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #dayAppointmentsModal .modal-footer {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #dayAppointmentsModal .btn-secondary {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #dayAppointmentsModal .btn-secondary:hover {
    background: var(--dm-bg-quaternary, #475569) !important;
}

[data-theme="dark"] .day-appointments-header {
    border-bottom-color: #334155;
}

[data-theme="dark"] .day-appointments-header .modal-title {
    color: #f1f5f9;
}

[data-theme="dark"] .day-appointment-item.pending {
    background: #4a3a1f !important;
    border-left-color: #fbbf24 !important;
    opacity: 1;
}

[data-theme="dark"] .day-appointment-item.pending .appointment-time {
    color: #fde68a !important;
}

[data-theme="dark"] .day-appointment-item.pending .appointment-title {
    color: #fef3c7 !important;
}

[data-theme="dark"] .day-appointment-item.pending .appointment-status {
    color: #fcd34d !important;
}

[data-theme="dark"] .day-appointment-item.pending .appointment-notes {
    color: #fde68a !important;
    border-top-color: rgba(251, 191, 36, 0.3) !important;
}

[data-theme="dark"] .day-appointment-item.confirmed {
    background: #2F4F4F !important;
    border-left-color: #4299E1 !important;
    color: #BFDBFE !important;
}

[data-theme="dark"] .day-appointment-item.completed {
    background: #1F3F2F !important;
    border-left-color: #10b981 !important;
    color: #A7F3D0 !important;
}

[data-theme="dark"] .day-appointment-item.cancelled {
    background: #6d4c41 !important;
    border-left-color: #d4a574 !important;
    opacity: 1;
}

[data-theme="dark"] .day-appointment-item.cancelled .appointment-time {
    color: #f5deb3 !important;
}

[data-theme="dark"] .day-appointment-item.cancelled .appointment-title {
    color: #f5deb3 !important;
}

[data-theme="dark"] .day-appointment-item.cancelled .appointment-status {
    color: #d4a574 !important;
}

[data-theme="dark"] .day-appointment-item.cancelled .appointment-notes {
    color: #d4a574 !important;
    border-top-color: rgba(212, 165, 116, 0.3) !important;
}

[data-theme="dark"] .day-appointment-item.blocked {
    background: #7f1d1d !important;
    border-left-color: #ef4444 !important;
    opacity: 1;
}

[data-theme="dark"] .day-appointment-item.blocked .appointment-time {
    color: #fca5a5 !important;
}

[data-theme="dark"] .day-appointment-item.blocked .appointment-title {
    color: #fee2e2 !important;
}

[data-theme="dark"] .day-appointment-item.blocked .appointment-status {
    color: #fca5a5 !important;
}

[data-theme="dark"] .day-appointment-item.blocked .appointment-notes {
    color: #fca5a5 !important;
    border-top-color: rgba(239, 68, 68, 0.3) !important;
}

[data-theme="dark"] .day-appointment-item.missed {
    background: #3a3a3a !important;
    border-left-color: #9ca3af !important;
    opacity: 0.7;
}

[data-theme="dark"] .day-appointment-item.missed .appointment-time {
    color: #e5e7eb !important;
}

[data-theme="dark"] .day-appointment-item.missed .appointment-title {
    color: #f3f4f6 !important;
}

[data-theme="dark"] .day-appointment-item.missed .appointment-status {
    color: #d1d5db !important;
}

[data-theme="dark"] .day-appointment-item.missed .appointment-notes {
    color: #d1d5db !important;
    border-top-color: rgba(156, 163, 175, 0.3) !important;
}

[data-theme="dark"] .day-appointment-item:not(.missed):not(.pending) .appointment-time {
    color: inherit !important;
}

[data-theme="dark"] .day-appointment-item:not(.missed):not(.pending) .appointment-title {
    color: inherit !important;
}

[data-theme="dark"] .day-appointment-item:not(.missed):not(.pending) .appointment-status {
    color: inherit !important;
}

[data-theme="dark"] .day-appointment-item .appointment-notes {
    color: inherit !important;
    border-top-color: rgba(255, 255, 255, 0.1) !important;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .day-appointments-list {
        max-height: 300px;
    }
    
    .day-appointment-item {
        padding: 0.75rem;
    }
    
    .fully-booked-indicator {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }
}
</style>

<!-- Include User Management Modal -->
@include('admin.account-management.modal-add-user')

@endsection
