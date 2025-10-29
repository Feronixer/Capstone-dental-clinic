@extends('layout.admin.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/content-management.css') }}">

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
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary fw-bold">Update Announcement</h5>
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
                                <div class="text-end">
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

<!-- Services Section -->
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
                    <div class="table-responsive">
                        <table class="table mb-0 service-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Icon</th>
                                    <th>Service</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="servicesTableBody">
                                @forelse($services as $index => $service)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="icon-wrapper">
                                            <div class="service-icon-box">
                                                <i class="bi {{ $service->icon_class ?? 'bi-gear' }}"></i>
                                            </div>
                                            <button class="btn-change" onclick="changeIcon({{ $service->id }})">Change</button>
                                        </div>
                                    </td>
                                    <td>{{ $service->service_name }}</td>
                                    <td>{{ number_format($service->price, 0, '.', ',') }}{{ strpos(strtolower($service->description), 'tooth') !== false ? ' (per tooth)' : '' }}</td>
                                    <td class="description-cell">{{ $service->description }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-edit" onclick="editService({{ $service->id }})">Edit</button>
                                            <button class="btn-delete" onclick="deleteService({{ $service->id }})">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No services available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#follow-ups"
                                        type="button" role="tab">Follow Ups</button>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="col-md-10">
                            <div class="tab-content">
                                <!-- Send Email Tab -->
                                <div class="tab-pane fade show active" id="send-email" role="tabpanel">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-envelope-fill me-2 text-primary"></i>Send Manual Email to Patient
                                    </h6>
                                    <p class="text-muted mb-4">Select a patient with an appointment and choose the type of email to send.</p>

                                    <div class="row">
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

                                            <div class="mb-3">
                                                <label for="selectEmailType" class="form-label fw-semibold">Email Type <span class="text-danger">*</span></label>
                                                <select class="form-select" id="selectEmailType" required>
                                                    <option value="">-- Choose email type --</option>
                                                    <option value="initial_confirmation">Initial Confirmation</option>
                                                    <option value="reminder">Reminder</option>
                                                    <option value="cancellation">Cancellation Notice</option>
                                                    <option value="rescheduling">Rescheduling Notice</option>
                                                    <option value="follow_up">Follow Up</option>
                                                </select>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-primary btn-lg" id="btnSendEmail" disabled>
                                                    <i class="bi bi-send-fill me-2"></i>Send Email
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
                                                            Select a patient and email type to preview
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-info mt-3" role="alert">
                                                <i class="bi bi-lightbulb-fill me-2"></i>
                                                <strong>Tip:</strong> The email will use the templates configured in the tabs below.
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

                                <!-- Follow Ups -->
                                <div class="tab-pane fade" id="follow-ups" role="tabpanel">
                                    <h6 class="fw-bold mb-3">Mail Structure</h6>
                                    <div class="mail-template-editor p-3 border rounded bg-light mb-3">
                                        <textarea class="form-control" id="follow-ups-template" rows="4"
                                                  data-type="follow_up">{{ $mailTemplates['follow_up']->content ?? 'Hi %firstname%, we hope you are doing well. Please schedule your follow-up appointment.' }}</textarea>
                                    </div>
                                    <div class="text-end mb-3">
                                        <button class="btn btn-primary" onclick="saveMailTemplate('follow_up')">
                                            <i class="bi bi-check-circle me-1"></i>Edit Template
                                        </button>
                                    </div>

                                    <h6 class="fw-bold mb-3">Mail Preview</h6>
                                    <div class="mail-preview p-4 border rounded bg-white">
                                        <h5 class="fw-bold mb-3">JValera Dental Clinic</h5>
                                        <p id="follow-ups-preview">
                                            Hi <span class="text-primary fw-bold">Angel Cuadernal</span>, we hope you are doing well.
                                            Please schedule your follow-up appointment.
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addServiceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Service Name *</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="icon_class" class="form-label">Icon Class (Bootstrap Icons)</label>
                        <input type="text" class="form-control" id="icon_class" name="icon_class" placeholder="bi-tooth">
                        <small class="text-muted">e.g., bi-tooth, bi-gear, bi-heart-pulse</small>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price *</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="default_duration_minutes" class="form-label">Duration (minutes) *</label>
                        <input type="number" class="form-control" id="default_duration_minutes" name="default_duration_minutes" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editServiceForm">
                @csrf
                <input type="hidden" id="edit_service_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_service_name" class="form-label">Service Name *</label>
                        <input type="text" class="form-control" id="edit_service_name" name="service_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_icon_class" class="form-label">Icon Class (Bootstrap Icons)</label>
                        <input type="text" class="form-control" id="edit_icon_class" name="icon_class" placeholder="bi-tooth">
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price *</label>
                        <input type="number" class="form-control" id="edit_price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_default_duration_minutes" class="form-label">Duration (minutes) *</label>
                        <input type="number" class="form-control" id="edit_default_duration_minutes" name="default_duration_minutes" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description *</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Service</button>
                </div>
            </form>
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

<script>
const servicesData = @json($services);

// Announcement Form Handler
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/admin/content-management/announcement', {
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

    fetch('/admin/content-management/ticker', {
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

    fetch('/admin/content-management/service', {
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

    new bootstrap.Modal(document.getElementById('editServiceModal')).show();
}

// Edit Service Form Handler
document.getElementById('editServiceForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const serviceId = document.getElementById('edit_service_id').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch(`/admin/content-management/service/${serviceId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
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

    fetch(`/admin/content-management/service/${serviceToDelete}`, {
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

    fetch(`/admin/content-management/mail-template/${type}`, {
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
});

// Load patients who have appointments
function loadPatientsWithAppointments() {
    fetch('/admin/content-management/patients-with-appointments')
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
    fetch(`/admin/content-management/patient-appointments/${patientId}`)
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
        updateEmailTypeOptions();
        updateEmailPreview();
    } else {
        selectedAppointmentData = null;
        resetEmailTypeOptions();
        resetEmailPreview();
    }
    updateSendButtonState();
});

// Update email type options based on appointment status
function updateEmailTypeOptions() {
    const emailTypeSelect = document.getElementById('selectEmailType');
    const reschedulingOption = emailTypeSelect.querySelector('option[value="rescheduling"]');

    if (reschedulingOption) {
        if (selectedAppointmentData && selectedAppointmentData.rescheduled_at) {
            // Appointment was rescheduled - enable the option
            reschedulingOption.disabled = false;
            reschedulingOption.textContent = 'Rescheduling Notice';
        } else {
            // Appointment was not rescheduled - disable the option
            reschedulingOption.disabled = true;
            reschedulingOption.textContent = 'Rescheduling Notice (Not available - appointment not rescheduled)';

            // If rescheduling was selected, reset to empty
            if (emailTypeSelect.value === 'rescheduling') {
                emailTypeSelect.value = '';
            }
        }
    }
}

// Reset email type options to default
function resetEmailTypeOptions() {
    const emailTypeSelect = document.getElementById('selectEmailType');
    const reschedulingOption = emailTypeSelect.querySelector('option[value="rescheduling"]');

    if (reschedulingOption) {
        reschedulingOption.disabled = true;
        reschedulingOption.textContent = 'Rescheduling Notice (Select appointment first)';
    }

    emailTypeSelect.value = '';
}

// Handle email type selection
document.getElementById('selectEmailType')?.addEventListener('change', function(e) {
    updateEmailPreview();
    updateSendButtonState();
});

// Update email preview
function updateEmailPreview() {
    const emailType = document.getElementById('selectEmailType').value;
    const previewDiv = document.getElementById('emailPreviewContent');

    if (!selectedAppointmentData || !emailType) {
        resetEmailPreview();
        return;
    }

    // Get template for this email type
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
            <strong>Type:</strong> ${getEmailTypeLabel(emailType)}
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
            Select a patient and email type to preview
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
    const emailType = document.getElementById('selectEmailType').value;
    const btn = document.getElementById('btnSendEmail');

    btn.disabled = !(patientId && appointmentId && emailType);
}

// Send email button click handler
document.getElementById('btnSendEmail')?.addEventListener('click', function() {
    const appointmentId = document.getElementById('selectAppointment').value;
    const emailType = document.getElementById('selectEmailType').value;
    const btn = this;

    if (!appointmentId || !emailType) {
        showToast('Please select all required fields', 'error');
        return;
    }

    // Disable button and show loading
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    // Send email
    fetch('/admin/content-management/send-patient-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            appointment_id: appointmentId,
            email_type: emailType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Email sent successfully!', 'success');

            // Show success details
            const patient = selectedAppointmentData.patient;
            const emailTypeLabel = getEmailTypeLabel(emailType);

            showToast(`${emailTypeLabel} email sent to ${patient.email}`, 'success');
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
</script>
@endsection
