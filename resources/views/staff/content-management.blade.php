@extends('layout.staff.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/content-management.css') }}">
<style>
    /* FORCE FIXED LAYOUT - HIGHEST PRIORITY */
    html {
        overflow-y: scroll !important;
        width: 100vw !important;
        overflow-x: hidden !important;
    }

    body, body.admin-body {
        overflow: hidden !important;
        height: 100vh !important;
        max-height: 100vh !important;
        width: 100% !important;
        position: relative !important;
    }

    .navigation-bar-container,
    aside.navigation-bar-container {
        width: 260px !important;
        min-width: 260px !important;
        max-width: 260px !important;
        flex: 0 0 260px !important;
        flex-shrink: 0 !important;
        flex-grow: 0 !important;
        flex-basis: 260px !important;
        position: relative !important;
        z-index: 100 !important;
        font-size: 16px !important;
    }

    .navigation-bar-container *,
    aside.navigation-bar-container * {
        font-size: inherit !important;
    }

    .navigation-bar-container .logo a,
    aside.navigation-bar-container .logo a {
        font-size: 1.1rem !important;
    }

    .navigation-bar-container .logo i,
    aside.navigation-bar-container .logo i {
        font-size: 1.5rem !important;
    }

    .navigation-bar-container .navigation-bar li,
    aside.navigation-bar-container .navigation-bar li {
        font-size: 16px !important;
    }

    main {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        flex: 1 1 auto !important;
        width: calc(100vw - 260px) !important;
        max-width: calc(100vw - 260px) !important;
        position: relative !important;
    }

    .container-fluid {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 text-dark fw-bold">Content Management</h1>
        </div>
    </div>

    <!-- Announcement Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-bold">Update Announcement</h5>
                    <a href="{{ route('staff-announcement-archives') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-archive me-1"></i>View Archives
                    </a>
                </div>
                <div class="card-body">
                    <form id="announcementForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Image Upload -->
                            <div class="col-md-4 mb-3">
                                <div class="announcement-image-container position-relative">
                                    <div class="image-preview" id="imagePreview">
                                        @if($announcement && $announcement->image_path)
                                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="Announcement" class="img-fluid rounded">
                                        @else
                                            <div class="placeholder-image d-flex align-items-center justify-content-center bg-light rounded" style="height: 250px;">
                                                <i class="bi bi-image" style="font-size: 4rem; color: #ddd;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" id="announcementImage" name="image" accept="image/*" class="d-none">
                                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="document.getElementById('announcementImage').click()">
                                        <i class="bi bi-upload me-1"></i>Change Photo
                                    </button>
                                </div>
                            </div>

                            <!-- Announcement Details -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="announcementTitle" class="form-label fw-bold">Title</label>
                                    <input type="text" class="form-control" id="announcementTitle" name="title"
                                           value="{{ $announcement->title ?? '' }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="announcementContent" class="form-label fw-bold">Content</label>
                                    <textarea class="form-control" id="announcementContent" name="content"
                                              rows="6" required>{{ $announcement->content ?? '' }}</textarea>
                                </div>
                                <div class="text-end d-flex gap-2 justify-content-end">
                                    <button type="button" id="btnNewAnnouncement" class="btn btn-success px-4">
                                        <i class="bi bi-plus-circle me-1"></i>NEW ANNOUNCEMENT
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-circle me-1"></i>EDIT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>

<!-- Top Header Ticker Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-primary fw-bold">Top Header Ticker Notification</h5>
            </div>
            <div class="card-body">
                <form id="tickerForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-10">
                            <div class="mb-3">
                                <label for="tickerText" class="form-label fw-bold">Ticker Message</label>
                                <input type="text" class="form-control" id="tickerText" name="ticker_text"
                                       value="{{ $announcement->ticker_text ?? 'The clinic will be closed on April 27, 2025 for regular maintenance. Emergency services will be available.' }}" required>
                                <small class="text-muted">This message will scroll across the top of the patient portal</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="showTicker" class="form-label fw-bold">Display</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="showTicker" name="show_ticker"
                                           {{ ($announcement->show_ticker ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="showTicker">Show Ticker</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Preview:</strong>
                                <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded">
                                    <i class="bi bi-megaphone-fill me-2"></i>
                                    <strong>Announcement:</strong> <span id="tickerPreview">{{ $announcement->ticker_text ?? 'The clinic will be closed on April 27, 2025 for regular maintenance. Emergency services will be available.' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i>UPDATE TICKER
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Services Section (Compact Grid Table - st-*) -->
<div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-bold">Service</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="bi bi-plus-circle me-1"></i>Add Service
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="st-table" role="table" aria-label="Services">
                        <div class="st-row st-head" role="row">
                            <div class="st-cell text-center" style="width:60px" role="columnheader">ID</div>
                            <div class="st-cell text-center" style="width:120px" role="columnheader">Icon</div>
                            <div class="st-cell" role="columnheader">Service</div>
                            <div class="st-cell text-center" style="width:120px" role="columnheader">Duration</div>
                            <div class="st-cell" role="columnheader">Description</div>
                            <div class="st-cell text-center" style="width:170px" role="columnheader">Action</div>
                        </div>
                        @forelse($services as $index => $service)
                        <div class="st-row" role="row">
                            <div class="st-cell text-center" role="cell">{{ $index + 1 }}</div>
                            <div class="st-cell text-center" role="cell">
                                <div class="st-icon-wrap">
                                    <div class="st-icon-box">
                                        @php $ic = $service->icon_class; @endphp
                                        @if($ic && \Illuminate\Support\Str::startsWith($ic,'uploaded:'))
                                            <img src="{{ asset('storage/' . \Illuminate\Support\Str::after($ic,'uploaded:')) }}" alt="icon" class="st-icon-img">
                                        @else
                                            <i class="bi {{ $ic ?: 'bi-gear' }}"></i>
                                        @endif
                                    </div>
                                    <button type="button" class="st-btn-change" onclick="changeIcon({{ $service->id }})">Change</button>
                                </div>
                            </div>
                            <div class="st-cell" role="cell">{{ $service->service_name }}</div>
                            <div class="st-cell text-center" role="cell">{{ $service->default_duration_minutes ?? '—' }} mins.</div>
                            <div class="st-cell" role="cell">{{ $service->description }}</div>
                            <div class="st-cell text-center" role="cell">
                                <div class="d-inline-flex gap-2">
                                    <button type="button" class="btn-edit btn-icon" aria-label="Edit" title="Edit" onclick="editService({{ $service->id }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn-delete btn-icon" aria-label="Delete" title="Delete" onclick="deleteService({{ $service->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="st-row">
                            <div class="st-cell text-center" style="grid-column:1/-1">No services available</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Mail Settings Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary fw-bold">Patient Mail Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tabs -->
                        <div class="col-md-2">
                            <div class="nav flex-column nav-pills" id="mail-tabs" role="tablist">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#send-email"
                                        type="button" role="tab">
                                    <i class="bi bi-send me-1"></i>Send Email
                                </button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#initial-confirmation"
                                        type="button" role="tab">Initial Confirmation</button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#reminders"
                                        type="button" role="tab">Reminders</button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#cancellation"
                                        type="button" role="tab">Cancellation</button>
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#rescheduling"
                                        type="button" role="tab">Rescheduling</button>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="col-md-10">
                            <div class="tab-content">
                                <!-- Send Email Tab -->
                                <div class="tab-pane fade show active" id="send-email" role="tabpanel">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-envelope-fill me-2 text-primary"></i>Send Email to Patient
                                    </h6>

                                    <!-- Bulk Email Mode Toggle -->
                                    <div class="alert alert-light border mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bulkEmailMode" style="cursor: pointer;">
                                            <label class="form-check-label fw-semibold" for="bulkEmailMode" style="cursor: pointer;">
                                                <i class="bi bi-people me-1"></i>Enable Bulk Email Mode
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-1">Toggle to send emails to multiple patients at once</small>
                                    </div>

                                    <p class="text-muted mb-4" id="modeDescription">Select a patient with an appointment and choose the type of email to send.</p>

                                    <!-- Single Email Form -->
                                    <div class="row" id="singleEmailForm">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="selectPatient" class="form-label fw-semibold">Select Patient <span class="text-danger">*</span></label>
                                                <select class="form-select" id="selectPatient" required>
                                                    <option value="">-- Choose a patient --</option>
                                                </select>
                                                <small class="text-muted">Only patients with appointments are shown</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="selectAppointment" class="form-label fw-semibold">Select Appointment <span class="text-danger">*</span></label>
                                                <select class="form-select" id="selectAppointment" required disabled>
                                                    <option value="">-- Select patient first --</option>
                                                </select>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-primary btn-lg" id="btnSendEmail" disabled>
                                                    <i class="bi bi-send-fill me-2"></i>Send Follow Up Email
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card bg-light border">
                                                <div class="card-body">
                                                    <h6 class="card-title fw-bold mb-3">
                                                        <i class="bi bi-info-circle me-2"></i>Email Preview
                                                    </h6>
                                                    <div id="emailPreviewContent">
                                                        <p class="text-muted text-center py-5">
                                                            <i class="bi bi-envelope" style="font-size: 3rem; opacity: 0.3;"></i><br>
                                                            Select a patient to preview
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-info mt-3" role="alert">
                                                <i class="bi bi-lightbulb-fill me-2"></i>
                                                <strong>Tip:</strong> Sending a Follow Up email to patients.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Follow Up Template Editor (Static, always visible) -->
                                    <div class="row mt-4" id="followUpTemplateEditor">
                                        <div class="col-12">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-white border-bottom">
                                                    <h6 class="fw-bold mb-0">
                                                        <i class="bi bi-envelope-check me-2 text-primary"></i>Follow Up Email Template
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Template Content</label>
                                                        <textarea class="form-control" id="follow-ups-template" rows="4"
                                                                  data-type="follow_up">{{ $mailTemplates['follow_up']->content ?? 'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment.' }}</textarea>
                                                        <small class="text-muted">Use %firstname%, %service% for placeholders</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="card bg-light border-0">
                                                            <div class="card-body p-3">
                                                                <small class="text-muted fw-bold d-block mb-2">Preview:</small>
                                                                <p class="mb-0 small" id="follow-ups-preview">
                                                                    Hi <span class="text-primary fw-bold">Angel Cuadernal</span>, we hope you are doing well.
                                                                    Please schedule your follow-up appointment.
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-primary" onclick="saveMailTemplate('follow_up')">
                                                            <i class="bi bi-check-circle me-1"></i>Save Template
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bulk Email Form -->
                                    <div class="row" id="bulkEmailForm" style="display: none;">
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label for="selectSituation" class="form-label fw-semibold">Filter Patients By <span class="text-danger">*</span></label>
                                                <select class="form-select" id="selectSituation" required>
                                                    <option value="">-- Choose a filter --</option>
                                                    <option value="today">Appointments Today</option>
                                                    <option value="tomorrow">Appointments Tomorrow</option>
                                                    <option value="this_week">Appointments This Week</option>
                                                    <option value="rescheduled">Rescheduled Appointments</option>
                                                    <option value="pending">Pending/Unconfirmed Appointments</option>
                                                    <option value="completed">Recently Completed (Last 7 Days)</option>
                                                    <option value="upcoming">All Upcoming Appointments</option>
                                                </select>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-primary btn-lg" id="btnLoadPatients" disabled>
                                                    <i class="bi bi-search me-2"></i>Load Patients
                                                </button>
                                            </div>

                                            <div class="alert alert-info mt-3" role="alert">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>Note:</strong> You can select/deselect individual patients from the list before sending.
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="card border">
                                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0 fw-bold">
                                                        <i class="bi bi-list-check me-2"></i>Patients List
                                                    </h6>
                                                    <span class="badge bg-primary" id="patientCount">0 patients</span>
                                                </div>
                                                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                                    <div id="patientsList">
                                                        <p class="text-muted text-center py-5">
                                                            <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i><br>
                                                            Select a filter and click "Load Patients" to see the list
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-white border-top">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="selectAllPatients">
                                                            <label class="form-check-label" for="selectAllPatients">
                                                                Select All
                                                            </label>
                                                        </div>
                                                        <button type="button" class="btn btn-success" id="btnSendBulkEmail" disabled>
                                                            <i class="bi bi-send-fill me-2"></i>Send Follow Up to Selected
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Initial Confirmation -->
                                <div class="tab-pane fade" id="initial-confirmation" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="initial-confirmation-template" rows="4"
                                                  data-type="initial_confirmation">{{ $mailTemplates['initial_confirmation']->content ?? 'Good Day! %firstname%, you have a schedule appointment on %datetime% with Dr. Justin Valera regarding on your %service% treatment.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('initial_confirmation')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="initial-confirmation-preview">
                                            Good Day! <span class="text-primary fw-bold">Angel Cuadernal</span>, you have a schedule appointment on
                                            <span class="text-primary fw-bold">April 15, 2025 3:00 PM</span> with Dr. Justin Valera regarding on your
                                            <span class="text-primary fw-bold">Flexible Dentures</span> treatment.
                                        </p>
                                    </div>
                                </div>

                                <!-- Reminders -->
                                <div class="tab-pane fade" id="reminders" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="reminders-template" rows="4"
                                                  data-type="reminder">{{ $mailTemplates['reminder']->content ?? 'Reminder: %firstname%, you have an appointment on %datetime% with Dr. Justin Valera for %service%.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('reminder')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="reminders-preview">
                                            Reminder: <span class="text-primary fw-bold">Angel Cuadernal</span>, you have an appointment on
                                            <span class="text-primary fw-bold">April 15, 2025 3:00 PM</span> with Dr. Justin Valera for
                                            <span class="text-primary fw-bold">Flexible Dentures</span>.
                                        </p>
                                    </div>
                                </div>

                                <!-- Cancellation -->
                                <div class="tab-pane fade" id="cancellation" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="cancellation-template" rows="4"
                                                  data-type="cancellation">{{ $mailTemplates['cancellation']->content ?? 'Dear %firstname%, your appointment on %datetime% has been cancelled.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('cancellation')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="cancellation-preview">
                                            Dear <span class="text-primary fw-bold">Angel Cuadernal</span>, your appointment on
                                            <span class="text-primary fw-bold">April 15, 2025 3:00 PM</span> has been cancelled.
                                        </p>
                                    </div>
                                </div>

                                <!-- Rescheduling -->
                                <div class="tab-pane fade" id="rescheduling" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="rescheduling-template" rows="4"
                                                  data-type="rescheduling">{{ $mailTemplates['rescheduling']->content ?? 'Hello %firstname%, your appointment has been rescheduled to %datetime%.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('rescheduling')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="rescheduling-preview">
                                            Hello <span class="text-primary fw-bold">Angel Cuadernal</span>, your appointment has been rescheduled to
                                            <span class="text-primary fw-bold">April 20, 2025 2:00 PM</span>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Add New Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addServiceForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="service_name" class="form-label fw-semibold">
                                <i class="bi bi-card-heading text-primary me-2"></i>Service Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="service_name" name="service_name"
                                   placeholder="e.g., Teeth Cleaning, Root Canal, etc." required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="icon_class" class="form-label fw-semibold">
                                <i class="bi bi-emoji-smile text-primary me-2"></i>Service Icon
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-gear fs-5" id="selected_icon_preview"></i>
                                </span>
                                <input type="text" class="form-control" id="icon_class" name="icon_class"
                                       placeholder="Click 'Choose Icon' button to select" readonly>
                                <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('icon_class', 'selected_icon_preview')">
                                    <i class="bi bi-grid-3x3-gap me-1"></i>Choose Icon
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Select an icon that represents this service
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label fw-semibold">
                                <i class="bi bi-currency-peso text-success me-2"></i>Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light fw-bold">₱</span>
                                <input type="number" class="form-control" id="price" name="price"
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Enter the service price in Philippine Peso
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="default_duration_minutes" class="form-label fw-semibold">
                                <i class="bi bi-clock text-info me-2"></i>Duration (minutes) <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="default_duration_minutes" name="default_duration_minutes" required>
                                <option value="">Select duration</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                                <option value="150">2.5 hours</option>
                                <option value="180">3 hours</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Typical appointment duration
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-file-text text-secondary me-2"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                      placeholder="Describe what this service includes, benefits, and any important details..." required></textarea>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>This will be shown to patients when booking
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Add Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editServiceForm">
                @csrf
                <input type="hidden" id="edit_service_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_service_name" class="form-label fw-semibold">
                                <i class="bi bi-card-heading text-primary me-2"></i>Service Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="edit_service_name" name="service_name"
                                   placeholder="e.g., Teeth Cleaning, Root Canal, etc." required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_icon_class" class="form-label fw-semibold">
                                <i class="bi bi-emoji-smile text-primary me-2"></i>Service Icon
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <img id="edit_selected_icon_img" class="d-none theme-adapt" style="width:24px;height:24px;object-fit:contain;" alt="icon">
                                    <i class="bi bi-gear fs-5" id="edit_selected_icon_preview"></i>
                                </span>
                                <input type="text" class="form-control" id="edit_icon_class" name="icon_class"
                                       placeholder="Click 'Choose Icon' or 'Upload Icon'" readonly>
                                <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('edit_icon_class', 'edit_selected_icon_preview')">
                                    <i class="bi bi-grid-3x3-gap me-1"></i>Choose Icon
                                </button>
                                <button type="button" class="btn btn-outline-secondary ms-2" id="btnUploadIcon">
                                    <i class="bi bi-upload me-1"></i>Upload Icon
                                </button>
                                <input type="file" id="edit_icon_upload" name="icon_upload" class="d-none" accept="image/*,.ico">
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Select an icon that represents this service
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_price" class="form-label fw-semibold">
                                <i class="bi bi-currency-peso text-success me-2"></i>Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light fw-bold">₱</span>
                                <input type="number" class="form-control" id="edit_price" name="price"
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Enter the service price in Philippine Peso
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_default_duration_minutes" class="form-label fw-semibold">
                                <i class="bi bi-clock text-info me-2"></i>Duration (minutes) <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="edit_default_duration_minutes" name="default_duration_minutes" required>
                                <option value="">Select duration</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                                <option value="150">2.5 hours</option>
                                <option value="180">3 hours</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>Typical appointment duration
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_description" class="form-label fw-semibold">
                                <i class="bi bi-file-text text-secondary me-2"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="edit_description" name="description" rows="4"
                                      placeholder="Describe what this service includes, benefits, and any important details..." required></textarea>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>This will be shown to patients when booking
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle me-2"></i>Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- New Announcement Confirmation Modal -->
<div class="modal fade" id="newAnnouncementConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-modal-content">
            <div class="modal-body text-center p-4">
                <div class="delete-icon-wrapper mb-3">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                </div>
                <h5 class="delete-modal-title mb-2">Create New Announcement</h5>
                <p class="delete-modal-message mb-4">Are you sure you want to create a new announcement?<br>The current announcement will be archived.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmNewAnnouncementBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Service Confirmation Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content delete-modal-content">
            <div class="modal-body text-center p-4">
                <div class="delete-icon-wrapper mb-3">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                </div>
                <h5 class="delete-modal-title mb-2">Delete Service</h5>
                <p class="delete-modal-message mb-4">Are you sure you want to delete this service?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-delete" id="confirmDeleteServiceBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose an Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="iconSearchInput" placeholder="Search icons...">
                <div class="icon-picker-grid" id="iconPickerGrid">
                    <!-- Icons will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-picker-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 10px;
    max-height: 400px;
    overflow-y: auto;
}

.icon-picker-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.icon-picker-item:hover {
    border-color: #2196F3;
    background: #e3f2fd;
    transform: scale(1.05);
}

.icon-picker-item.selected {
    border-color: #2196F3;
    background: #2196F3;
    color: white;
}

.icon-picker-item i {
    font-size: 2rem;
    margin-bottom: 5px;
}

.icon-picker-item span {
    font-size: 0.7rem;
    text-align: center;
    word-break: break-word;
}
</style>

<script>
const servicesData = @json($services);

// Variables for new announcement confirmation
let pendingNewAnnouncementData = null;
let pendingNewAnnouncementBtn = null;

// New Announcement Button Handler
document.getElementById('btnNewAnnouncement')?.addEventListener('click', function() {
    const form = document.getElementById('announcementForm');
    const formData = new FormData(form);

    // Validate form
    const title = document.getElementById('announcementTitle').value;
    const content = document.getElementById('announcementContent').value;

    if (!title || !content) {
        showToast('Please fill in title and content', 'error');
        return;
    }

    // Store form data and button reference for later use
    pendingNewAnnouncementData = formData;
    pendingNewAnnouncementBtn = this;

    // Show confirmation modal instead of browser confirm
    const confirmModal = new bootstrap.Modal(document.getElementById('newAnnouncementConfirmModal'));
    confirmModal.show();
});

// Confirm New Announcement Handler
document.getElementById('confirmNewAnnouncementBtn')?.addEventListener('click', function() {
    if (!pendingNewAnnouncementData || !pendingNewAnnouncementBtn) return;

    // Hide modal
    const confirmModal = bootstrap.Modal.getInstance(document.getElementById('newAnnouncementConfirmModal'));
    confirmModal.hide();

    // Disable button and show loading
    const btn = pendingNewAnnouncementBtn;
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

    fetch('/staff/content-management/announcement/new', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: pendingNewAnnouncementData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('New announcement created successfully! Old announcement has been archived.', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('Error creating new announcement: ' + (data.message || 'Unknown error'), 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error creating new announcement', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    })
    .finally(() => {
        // Clear pending data
        pendingNewAnnouncementData = null;
        pendingNewAnnouncementBtn = null;
    });
});

// Announcement Form Handler (Edit only - does not archive)
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/staff/content-management/announcement', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Announcement updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error updating announcement: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating announcement', 'error');
    });
});

// Image preview
document.getElementById('announcementImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML =
                `<img src="${e.target.result}" alt="Preview" class="img-fluid rounded">`;
        };
        reader.readAsDataURL(file);
    }
});

// Ticker Form Handler
document.getElementById('tickerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        ticker_text: formData.get('ticker_text'),
        show_ticker: document.getElementById('showTicker').checked
    };

    fetch('/staff/content-management/ticker', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Ticker notification updated successfully!', 'success');
        } else {
            showToast('Error updating ticker: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating ticker', 'error');
    });
});

// Ticker preview update
document.getElementById('tickerText').addEventListener('input', function(e) {
    document.getElementById('tickerPreview').textContent = e.target.value;
});

// Add Service Form Handler
document.getElementById('addServiceForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch('/staff/content-management/service', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Service added successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('addServiceModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error adding service: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding service', 'error');
    });
});

// Edit Service Function
function editService(id) {
    const service = servicesData.find(s => s.id === id);
    if (!service) return;

    document.getElementById('edit_service_id').value = service.id;
    document.getElementById('edit_service_name').value = service.service_name;
    document.getElementById('edit_icon_class').value = service.icon_class || '';
    document.getElementById('edit_price').value = service.price;
    document.getElementById('edit_default_duration_minutes').value = service.default_duration_minutes;
    document.getElementById('edit_description').value = service.description;

    // Update icon preview
    const iconPreview = document.getElementById('edit_selected_icon_preview');
    const iconImg = document.getElementById('edit_selected_icon_img');
    iconImg.classList.add('d-none');
    iconPreview.classList.remove('d-none');
    if (service.icon_class && service.icon_class.startsWith('uploaded:')) {
        iconImg.src = `/storage/${service.icon_class.replace('uploaded:','')}`;
        iconImg.classList.remove('d-none');
        iconPreview.classList.add('d-none');
    } else if (service.icon_class) {
        iconPreview.className = service.icon_class + ' fs-5';
    } else {
        iconPreview.className = 'bi bi-gear fs-5';
    }

    new bootstrap.Modal(document.getElementById('editServiceModal')).show();
}

// Edit Service Form Handler
document.getElementById('editServiceForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const serviceId = document.getElementById('edit_service_id').value;
    const formData = new FormData(this);

    fetch(`/staff/content-management/service/${serviceId}`, {
        method: 'POST',
        headers: {
            'X-HTTP-Method-Override': 'PUT',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Service updated successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('editServiceModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error updating service: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating service', 'error');
    });
});

// Delete Service Function
let serviceToDelete = null;

function deleteService(id) {
    serviceToDelete = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteServiceModal'));
    deleteModal.show();
}

// Confirm Delete Handler
document.getElementById('confirmDeleteServiceBtn').addEventListener('click', function() {
    if (!serviceToDelete) return;

    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteServiceModal'));
    deleteModal.hide();

    fetch(`/staff/content-management/service/${serviceToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Service deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error deleting service: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error deleting service', 'error');
    })
    .finally(() => {
        serviceToDelete = null;
    });
});

// Toast Notification Function
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `custom-toast custom-toast-${type}`;
    toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;

    document.getElementById('toastContainer').appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Save Mail Template Function
function saveMailTemplate(type) {
    const textarea = document.querySelector(`[data-type="${type}"]`);
    const content = textarea.value;

    fetch(`/staff/content-management/mail-template/${type}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            subject: `Appointment ${type.replace('_', ' ')}`,
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Mail template updated successfully!', 'success');
        } else {
            showToast('Error updating mail template: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating mail template', 'error');
    });
}

// Update template preview in tab (live preview as you type)
function updateTemplatePreview(textarea) {
    const type = textarea.dataset.type;
    const content = textarea.value;

    // Map template types to preview IDs
    const previewMap = {
        'initial_confirmation': 'initial-confirmation-preview',
        'reminder': 'reminders-preview',
        'cancellation': 'cancellation-preview',
        'rescheduling': 'rescheduling-preview',
        'follow_up': 'follow-ups-preview'
    };

    const previewId = previewMap[type];
    if (!previewId) return;

    const previewElement = document.getElementById(previewId);
    if (!previewElement) return;

    // Replace placeholders with sample data
    const sampleContent = content
        .replace(/%firstname%/g, '<span class="text-primary fw-bold">Angel Cuadernal</span>')
        .replace(/%datetime%/g, '<span class="text-primary fw-bold">April 15, 2025 3:00 PM</span>')
        .replace(/%rescheduledtime%/g, '<span class="text-primary fw-bold">April 20, 2025 2:00 PM</span>')
        .replace(/%service%/g, '<span class="text-primary fw-bold">Flexible Dentures</span>');

    previewElement.innerHTML = sampleContent;
}

// Upload Icon handlers (delegated for staff modal)
document.addEventListener('click', function(e) {
    const btn = e.target.closest('#btnUploadIcon');
    if (!btn) return;
    const modal = btn.closest('.modal');
    const input = modal ? modal.querySelector('#edit_icon_upload') : document.getElementById('edit_icon_upload');
    if (input) input.click();
});

document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'edit_icon_upload') {
        const file = e.target.files[0];
        if (!file) return;
        const modal = e.target.closest('.modal');
        const img = modal ? modal.querySelector('#edit_selected_icon_img') : document.getElementById('edit_selected_icon_img');
        const icon = modal ? modal.querySelector('#edit_selected_icon_preview') : document.getElementById('edit_selected_icon_preview');
        const cls = modal ? modal.querySelector('#edit_icon_class') : document.getElementById('edit_icon_class');
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        icon.classList.add('d-none');
        if (cls) cls.value = 'uploaded:pending';
    }
});

// Change Icon Function
function changeIcon(id) {
    const service = servicesData.find(s => s.id === id);
    if (!service) return;

    // Open the edit modal and focus on icon field
    editService(id);
    setTimeout(() => {
        const iconInput = document.getElementById('edit_icon_class');
        if (iconInput) {
            iconInput.focus();
            iconInput.select();
        }
    }, 300);
}

// ============================================
// SEND EMAIL TAB FUNCTIONALITY
// ============================================

let patientsData = [];
let selectedAppointmentData = null;

// Load patients with appointments on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPatientsWithAppointments();

    // Handle bulk email mode toggle
    document.getElementById('bulkEmailMode')?.addEventListener('change', function(e) {
        const isBulkMode = e.target.checked;
        const singleForm = document.getElementById('singleEmailForm');
        const bulkForm = document.getElementById('bulkEmailForm');
        const modeDescription = document.getElementById('modeDescription');

        if (isBulkMode) {
            singleForm.style.display = 'none';
            bulkForm.style.display = 'flex';
            modeDescription.textContent = 'Select a filter to find multiple patients with matching appointments and send Follow Up emails.';
        } else {
            singleForm.style.display = 'flex';
            bulkForm.style.display = 'none';
            modeDescription.textContent = 'Select a patient with an appointment to send a Follow Up email.';
        }

        // Clear form fields when switching modes
        if (isBulkMode) {
            document.getElementById('selectSituation').value = '';
            document.getElementById('btnLoadPatients').disabled = true;
            document.getElementById('patientsList').innerHTML = '<p class="text-muted text-center py-5"><i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i><br>Select a filter and click "Load Patients" to see the list</p>';
            document.getElementById('patientCount').textContent = '0 patients';
        } else {
            document.getElementById('selectPatient').value = '';
            document.getElementById('selectAppointment').value = '';
            document.getElementById('selectAppointment').disabled = true;
            document.getElementById('btnSendEmail').disabled = true;
            document.getElementById('emailPreviewContent').innerHTML = '<p class="text-muted text-center py-5"><i class="bi bi-envelope" style="font-size: 3rem; opacity: 0.3;"></i><br>Select a patient to preview</p>';
        }
    });

    // Live preview for mail template edits
    document.querySelectorAll('[data-type]').forEach(textarea => {
        textarea.addEventListener('input', function() {
            updateTemplatePreview(this);
        });
    });
});

// Load patients who have appointments
function loadPatientsWithAppointments() {
    fetch('/staff/content-management/patients-with-appointments')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                patientsData = data.patients;
                populatePatientDropdown();
            }
        })
        .catch(error => {
            console.error('Error loading patients:', error);
        });
}

// Populate patient dropdown
function populatePatientDropdown() {
    const select = document.getElementById('selectPatient');
    select.innerHTML = '<option value="">-- Choose a patient --</option>';

    patientsData.forEach(patient => {
        const option = document.createElement('option');
        option.value = patient.id;
        option.textContent = `${patient.name} (${patient.email}) - ${patient.appointments_count} appointment(s)`;
        option.dataset.email = patient.email;
        select.appendChild(option);
    });
}

// Handle patient selection
document.getElementById('selectPatient')?.addEventListener('change', function(e) {
    const patientId = e.target.value;
    const appointmentSelect = document.getElementById('selectAppointment');

    if (!patientId) {
        appointmentSelect.innerHTML = '<option value="">-- Select patient first --</option>';
        appointmentSelect.disabled = true;
        updateSendButtonState();
        return;
    }

    // Load appointments for selected patient
    fetch(`/staff/content-management/patient-appointments/${patientId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateAppointmentDropdown(data.appointments);
                appointmentSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error loading appointments:', error);
            showToast('Error loading appointments', 'error');
        });
});

// Populate appointment dropdown
function populateAppointmentDropdown(appointments) {
    const select = document.getElementById('selectAppointment');
    select.innerHTML = '<option value="">-- Choose an appointment --</option>';

    appointments.forEach(apt => {
        const option = document.createElement('option');
        option.value = apt.id;
        const date = new Date(apt.start_datetime.replace(' ', 'T'));
        const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const timeStr = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

        // Add "(Rescheduled)" badge if appointment was rescheduled
        const rescheduledBadge = apt.rescheduled_at ? ' (Rescheduled)' : '';
        option.textContent = `${dateStr} ${timeStr} - ${apt.service?.service_name || 'No service'}${rescheduledBadge}`;
        option.dataset.appointment = JSON.stringify(apt);
        select.appendChild(option);
    });
}

// Handle appointment selection
document.getElementById('selectAppointment')?.addEventListener('change', function(e) {
    if (e.target.value) {
        const option = e.target.options[e.target.selectedIndex];
        selectedAppointmentData = JSON.parse(option.dataset.appointment);
        updateEmailPreview();
    } else {
        selectedAppointmentData = null;
        resetEmailPreview();
    }
    updateSendButtonState();
});

// Update email preview
function updateEmailPreview() {
    const previewDiv = document.getElementById('emailPreviewContent');

    if (!selectedAppointmentData) {
        resetEmailPreview();
        return;
    }

    // Always use follow_up template
    const emailType = 'follow_up';
    const textarea = document.querySelector(`[data-type="${emailType}"]`);
    let template = textarea ? textarea.value : '';

    if (!template) {
        template = getDefaultTemplate(emailType);
    }

    // Replace placeholders
    const patient = selectedAppointmentData.patient;
    const firstName = patient.info?.first_name || patient.name.split(' ')[0];
    const date = new Date(selectedAppointmentData.start_datetime.replace(' ', 'T'));
    const dateStr = date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    const timeStr = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    const datetime = `${dateStr} ${timeStr}`;
    const service = selectedAppointmentData.service?.service_name || 'your appointment';

    let preview = template.replace(/%firstname%/g, firstName)
                         .replace(/%datetime%/g, datetime)
                         .replace(/%rescheduledtime%/g, datetime)
                         .replace(/%service%/g, service);

    previewDiv.innerHTML = `
        <div class="mb-3">
            <strong>From:</strong> JValera Dental Clinic<br>
            <strong>To:</strong> ${patient.email}<br>
            <strong>Type:</strong> Follow Up
        </div>
        <hr>
        <div style="padding: 15px; background: white; border-radius: 5px;">
            <h6 class="text-primary fw-bold">JValera Dental Clinic</h6>
            <p class="mb-0">${preview}</p>
        </div>
    `;
}

// Reset email preview
function resetEmailPreview() {
    document.getElementById('emailPreviewContent').innerHTML = `
        <p class="text-muted text-center py-5">
            <i class="bi bi-envelope" style="font-size: 3rem; opacity: 0.3;"></i><br>
            Select a patient to preview
        </p>
    `;
}

// Get default template
function getDefaultTemplate(type) {
    const defaults = {
        'initial_confirmation': 'Good Day! %firstname%, you have a schedule appointment on %datetime% with Dr. Justin Valera regarding on your %service% treatment.',
        'reminder': 'Reminder: %firstname%, you have an appointment on %datetime% with Dr. Justin Valera for %service%.',
        'cancellation': 'Dear %firstname%, your appointment on %datetime% has been cancelled.',
        'rescheduling': 'Hello %firstname%, your appointment has been rescheduled to %datetime%.',
        'follow_up': 'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment for %service%.',
    };
    return defaults[type] || '';
}

// Get email type label
function getEmailTypeLabel(type) {
    const labels = {
        'initial_confirmation': 'Initial Confirmation',
        'reminder': 'Reminder',
        'cancellation': 'Cancellation Notice',
        'rescheduling': 'Rescheduling Notice',
        'follow_up': 'Follow Up'
    };
    return labels[type] || type;
}

// Update send button state
function updateSendButtonState() {
    const patientId = document.getElementById('selectPatient').value;
    const appointmentId = document.getElementById('selectAppointment').value;
    const btn = document.getElementById('btnSendEmail');

    btn.disabled = !(patientId && appointmentId);
}

// Send email button click handler
document.getElementById('btnSendEmail')?.addEventListener('click', function() {
    const appointmentId = document.getElementById('selectAppointment').value;
    const btn = this;

    if (!appointmentId) {
        showToast('Please select all required fields', 'error');
        return;
    }

    // Disable button and show loading
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    // Send email (always follow_up)
    fetch('/staff/content-management/send-patient-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            appointment_id: appointmentId,
            email_type: 'follow_up'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success details
            const patient = selectedAppointmentData.patient;
            showToast(`Follow Up email sent to ${patient.email}`, 'success');
        } else {
            showToast('Error sending email: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error sending email', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        updateSendButtonState();
    });
});

// ============================================
// BULK EMAIL FUNCTIONALITY
// ============================================

// Variables for bulk email
let loadedPatients = [];

// Handle situation select change
document.getElementById('selectSituation')?.addEventListener('change', function() {
    updateLoadPatientsButtonState();
    // Clear patients list when filter changes
    document.getElementById('patientsList').innerHTML = `
        <p class="text-muted text-center py-5">
            <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i><br>
            Click "Load Patients" to see patients matching this filter
        </p>
    `;
    document.getElementById('patientCount').textContent = '0 patients';
    document.getElementById('btnSendBulkEmail').disabled = true;
});

function updateLoadPatientsButtonState() {
    const situation = document.getElementById('selectSituation').value;
    const btn = document.getElementById('btnLoadPatients');
    btn.disabled = !situation;
}

// Load patients by situation
document.getElementById('btnLoadPatients')?.addEventListener('click', function() {
    const situation = document.getElementById('selectSituation').value;
    const btn = this;

    if (!situation) {
        showToast('Please select a filter', 'error');
        return;
    }

    // Show loading state
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

    // Fetch patients by situation
    fetch('/staff/content-management/patients-by-situation?situation=' + situation)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadedPatients = data.patients;
                displayPatientsList(data.patients);
                showToast(`Found ${data.count} patient(s) with matching appointments`, 'success');
            } else {
                showToast('Error loading patients: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error loading patients', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
});

// Display patients list with checkboxes
function displayPatientsList(patients) {
    const container = document.getElementById('patientsList');
    const countBadge = document.getElementById('patientCount');

    if (patients.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle me-2"></i>
                No patients found matching this filter.
            </div>
        `;
        countBadge.textContent = '0 patients';
        document.getElementById('btnSendBulkEmail').disabled = true;
        return;
    }

    countBadge.textContent = `${patients.length} patient${patients.length !== 1 ? 's' : ''}`;

    let html = '';
    patients.forEach((patient, index) => {
        html += `
            <div class="card mb-2 patient-item">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        <div class="form-check">
                            <input class="form-check-input patient-checkbox" type="checkbox"
                                   id="patient-${patient.patient_id}"
                                   value="${patient.patient_id}"
                                   checked>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-1 fw-bold">${patient.patient_name}</h6>
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-envelope me-1"></i>${patient.patient_email}
                            </small>
                            <div class="appointments-list">
                                ${patient.appointments.map(apt => {
                                    const date = new Date(apt.start_datetime.replace(' ', 'T'));
                                    const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                                    const timeStr = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                                    const badge = apt.rescheduled ? '<span class="badge bg-warning text-dark ms-2">Rescheduled</span>' : '';

                                    return `
                                        <div class="d-flex align-items-center mb-1" data-appointment-id="${apt.id}">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>${dateStr} ${timeStr}
                                                <span class="ms-2">${apt.service}</span>
                                                ${badge}
                                            </small>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;

    // Add change listeners to checkboxes
    document.querySelectorAll('.patient-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSendBulkEmailButtonState);
    });

    updateSendBulkEmailButtonState();
}

// Handle select all checkbox
document.getElementById('selectAllPatients')?.addEventListener('change', function(e) {
    const checkboxes = document.querySelectorAll('.patient-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = e.target.checked;
    });
    updateSendBulkEmailButtonState();
});

// Update send bulk email button state
function updateSendBulkEmailButtonState() {
    const selectedCheckboxes = document.querySelectorAll('.patient-checkbox:checked');
    const btn = document.getElementById('btnSendBulkEmail');
    const selectAllCheckbox = document.getElementById('selectAllPatients');

    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.patient-checkbox');
    if (allCheckboxes.length > 0) {
        selectAllCheckbox.checked = selectedCheckboxes.length === allCheckboxes.length;
        selectAllCheckbox.indeterminate = selectedCheckboxes.length > 0 && selectedCheckboxes.length < allCheckboxes.length;
    }

    btn.disabled = selectedCheckboxes.length === 0;
}

// Send bulk email button click handler
document.getElementById('btnSendBulkEmail')?.addEventListener('click', function() {
    const emailType = 'follow_up'; // Always follow_up
    const selectedCheckboxes = document.querySelectorAll('.patient-checkbox:checked');
    const btn = this;

    if (selectedCheckboxes.length === 0) {
        showToast('Please select at least one patient', 'error');
        return;
    }

    // Collect all appointment IDs for selected patients
    const appointmentIds = [];
    selectedCheckboxes.forEach(checkbox => {
        const patientId = parseInt(checkbox.value);
        const patient = loadedPatients.find(p => p.patient_id === patientId);
        if (patient) {
            patient.appointments.forEach(apt => {
                appointmentIds.push(apt.id);
            });
        }
    });

    if (appointmentIds.length === 0) {
        showToast('No appointments found for selected patients', 'error');
        return;
    }

    // Confirm action
    const confirmMessage = `Are you sure you want to send Follow Up email to ${selectedCheckboxes.length} patient(s)?\n\nTotal appointments: ${appointmentIds.length}`;
    if (!confirm(confirmMessage)) {
        return;
    }

    // Disable button and show loading
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    // Send bulk email (always follow_up)
    fetch('/staff/content-management/send-bulk-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            appointment_ids: appointmentIds,
            email_type: 'follow_up'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const successMsg = `Successfully sent ${data.data.success_count} email(s)`;
            let fullMsg = successMsg;

            if (data.data.failed_count > 0) {
                fullMsg += `\n${data.data.failed_count} failed`;
                if (data.data.errors && data.data.errors.length > 0) {
                    fullMsg += ':\n' + data.data.errors.join('\n');
                }
            }

            showToast(successMsg, 'success');

            if (data.data.failed_count > 0) {
                console.error('Failed emails:', data.data.errors);
                showToast(`${data.data.failed_count} email(s) failed to send. Check console for details.`, 'warning');
            }

            // Optionally clear the list or reload
            // document.getElementById('selectSituation').value = '';
            // displayPatientsList([]);
        } else {
            showToast('Error sending bulk emails: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error sending bulk emails', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        updateSendBulkEmailButtonState();
    });
});

// ============================================
// ICON PICKER FUNCTIONALITY
// ============================================

// Dental & Medical Icons for Dental Clinic Services
const availableIcons = [
    // === MEDICAL & HEALTH ICONS ===
    'bi-heart-pulse-fill', 'bi-heart-pulse', 'bi-heart-fill', 'bi-heart', 'bi-heart-half',
    'bi-bandaid-fill', 'bi-bandaid', 'bi-capsule-pill', 'bi-capsule',
    'bi-activity', 'bi-clipboard2-pulse-fill', 'bi-clipboard2-pulse',
    'bi-hospital-fill', 'bi-hospital', 'bi-prescription2', 'bi-prescription',
    'bi-clipboard2-check-fill', 'bi-clipboard2-check', 'bi-clipboard2-heart',
    'bi-clipboard2-fill', 'bi-clipboard2', 'bi-clipboard-check', 'bi-clipboard-plus',
    'bi-journal-medical', 'bi-life-preserver',

    // === DENTAL TOOLS & PROCEDURES ===
    'bi-scissors', 'bi-eyedropper', 'bi-thermometer-half', 'bi-thermometer',
    'bi-tools', 'bi-wrench-adjustable-circle-fill', 'bi-gear-fill', 'bi-gear',

    // === QUALITY & CARE SYMBOLS ===
    'bi-star-fill', 'bi-star', 'bi-star-half',
    'bi-award-fill', 'bi-award',
    'bi-trophy-fill', 'bi-trophy',
    'bi-gem', 'bi-diamond-fill', 'bi-diamond',
    'bi-shield-fill-check', 'bi-shield-check', 'bi-shield-fill', 'bi-shield',
    'bi-patch-check-fill', 'bi-patch-check',
    'bi-check-circle-fill', 'bi-check-circle', 'bi-check2-circle',

    // === APPOINTMENTS & SCHEDULING ===
    'bi-calendar-check-fill', 'bi-calendar-check', 'bi-calendar-event-fill', 'bi-calendar-event',
    'bi-calendar-plus-fill', 'bi-calendar-plus', 'bi-calendar-fill', 'bi-calendar',
    'bi-clock-fill', 'bi-clock', 'bi-clock-history', 'bi-alarm-fill', 'bi-alarm',
    'bi-stopwatch-fill', 'bi-stopwatch',

    // === COMMUNICATION & CONTACT ===
    'bi-telephone-fill', 'bi-telephone', 'bi-phone-fill', 'bi-phone',
    'bi-envelope-fill', 'bi-envelope', 'bi-envelope-heart-fill', 'bi-envelope-heart',
    'bi-chat-dots-fill', 'bi-chat-dots', 'bi-chat-fill', 'bi-chat',
    'bi-megaphone-fill', 'bi-megaphone', 'bi-bell-fill', 'bi-bell',

    // === PEOPLE & PATIENTS ===
    'bi-person-fill', 'bi-person', 'bi-person-check-fill', 'bi-person-check',
    'bi-person-hearts', 'bi-person-heart', 'bi-people-fill', 'bi-people',
    'bi-person-badge-fill', 'bi-person-badge',

    // === SMILE & DENTAL AESTHETICS ===
    'bi-emoji-smile-fill', 'bi-emoji-smile', 'bi-emoji-laughing-fill', 'bi-emoji-laughing',
    'bi-brightness-high-fill', 'bi-brightness-high', 'bi-sun-fill', 'bi-sun',
    'bi-magic', 'bi-stars', 'bi-sparkles',

    // === TREATMENT & CARE ===
    'bi-hand-thumbs-up-fill', 'bi-hand-thumbs-up',
    'bi-lightning-charge-fill', 'bi-lightning-charge',
    'bi-droplet-fill', 'bi-droplet', 'bi-water',
    'bi-peace-fill', 'bi-peace',
    'bi-flower1', 'bi-flower2', 'bi-flower3',

    // === PREMIUM & SPECIAL SERVICES ===
    'bi-rocket-takeoff-fill', 'bi-rocket-takeoff', 'bi-rocket-fill', 'bi-rocket',
    'bi-speedometer2', 'bi-speedometer',
    'bi-gift-fill', 'bi-gift',
    'bi-balloon-heart-fill', 'bi-balloon-heart',

    // === DENTAL SPECIALTIES ===
    'bi-grid-3x3-gap-fill', 'bi-grid-3x3-gap', 'bi-grid-fill', 'bi-grid',
    'bi-collection-fill', 'bi-collection',
    'bi-box-seam-fill', 'bi-box-seam',

    // === INFORMATION & NAVIGATION ===
    'bi-info-circle-fill', 'bi-info-circle', 'bi-info-square-fill',
    'bi-question-circle-fill', 'bi-question-circle',
    'bi-exclamation-circle-fill', 'bi-exclamation-circle',
    'bi-arrow-right-circle-fill', 'bi-arrow-right-circle',
    'bi-arrow-clockwise', 'bi-arrow-repeat',

    // === SHAPES FOR DESIGN ===
    'bi-circle-fill', 'bi-circle', 'bi-square-fill', 'bi-square',
    'bi-hexagon-fill', 'bi-hexagon', 'bi-octagon-fill', 'bi-octagon',

    // === USEFUL GENERAL ICONS ===
    'bi-plus-circle-fill', 'bi-plus-circle', 'bi-plus-lg',
    'bi-dash-circle-fill', 'bi-dash-circle',
    'bi-x-circle-fill', 'bi-x-circle',
    'bi-bookmark-fill', 'bi-bookmark', 'bi-bookmark-heart-fill',
    'bi-eye-fill', 'bi-eye',
    'bi-house-heart-fill', 'bi-house-heart', 'bi-house-fill', 'bi-house',
    'bi-shop', 'bi-building-fill', 'bi-building'
];

let currentIconInputId = '';
let currentIconPreviewId = '';
let iconPickerModal = null;

// Initialize icon picker modal
document.addEventListener('DOMContentLoaded', function() {
    iconPickerModal = new bootstrap.Modal(document.getElementById('iconPickerModal'));
    populateIconGrid();

    // Search functionality
    document.getElementById('iconSearchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterIcons(searchTerm);
    });
});

// Open icon picker
function openIconPicker(inputId, previewId) {
    currentIconInputId = inputId;
    currentIconPreviewId = previewId;

    // Clear search
    document.getElementById('iconSearchInput').value = '';
    filterIcons('');

    // Highlight currently selected icon
    const currentIcon = document.getElementById(inputId).value;
    highlightSelectedIcon(currentIcon);

    iconPickerModal.show();
}

// Populate icon grid
function populateIconGrid() {
    const grid = document.getElementById('iconPickerGrid');
    grid.innerHTML = '';

    availableIcons.forEach(iconClass => {
        const iconName = iconClass.replace('bi-', '').replace(/-/g, ' ');
        const item = document.createElement('div');
        item.className = 'icon-picker-item';
        item.dataset.icon = iconClass;
        item.innerHTML = `
            <i class="bi ${iconClass}"></i>
            <span>${iconName}</span>
        `;
        item.addEventListener('click', () => selectIcon(iconClass));
        grid.appendChild(item);
    });
}

// Filter icons
function filterIcons(searchTerm) {
    const items = document.querySelectorAll('.icon-picker-item');
    items.forEach(item => {
        const iconName = item.dataset.icon.toLowerCase();
        if (iconName.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Highlight selected icon
function highlightSelectedIcon(iconClass) {
    document.querySelectorAll('.icon-picker-item').forEach(item => {
        item.classList.remove('selected');
        if (item.dataset.icon === iconClass) {
            item.classList.add('selected');
        }
    });
}

// Select icon
function selectIcon(iconClass) {
    // Update input field
    document.getElementById(currentIconInputId).value = iconClass;

    // Update preview
    const preview = document.getElementById(currentIconPreviewId);
    preview.className = `bi ${iconClass}`;

    // Highlight selected
    highlightSelectedIcon(iconClass);

    // Close modal after short delay
    setTimeout(() => {
        iconPickerModal.hide();
    }, 300);
}

// Update edit service function to update preview
const originalEditService = editService;
editService = function(id) {
    originalEditService(id);

    // Update icon preview in edit modal
    const service = servicesData.find(s => s.id === id);
    if (service && service.icon_class) {
        const preview = document.getElementById('edit_selected_icon_preview');
        if (preview) {
            preview.className = `bi ${service.icon_class}`;
        }
    }
};
</script>
@endsection

