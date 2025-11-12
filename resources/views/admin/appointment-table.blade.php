@extends('layout.admin.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
<link rel="stylesheet" href="{{ asset('css/appointment-table.css') }}">
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h2 text-dark fw-bold mb-2">Appointments Table</h1>
                    <p class="text-muted mb-0">
                        {{ $appointments->count() }} appointment(s) found
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin-appointment') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-calendar3 me-1"></i>Calendar View
                    </a>
                    <a href="{{ route('admin-appointment.export-excel', request()->all()) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i>Export to Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section (Activity Logs style) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 filters-section">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-funnel me-2"></i>Filter Options</h5>
                </div>
                <div class="card-body">
                <form method="GET" action="{{ route('admin-appointment.table') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="status" class="form-label fw-medium"><i class="bi bi-flag me-1"></i>Status</label>
                            <div class="select-wrapper">
                            <select class="form-select modern-select" id="status" name="status">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All ({{ $statusCounts['all'] ?? 0 }})</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending ({{ $statusCounts['Pending'] ?? 0 }})</option>
                                <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed ({{ $statusCounts['Confirmed'] ?? 0 }})</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed ({{ $statusCounts['Completed'] ?? 0 }})</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled ({{ $statusCounts['Cancelled'] ?? 0 }})</option>
                                <option value="Missed" {{ request('status') == 'Missed' ? 'selected' : '' }}>Missed ({{ $statusCounts['Missed'] ?? 0 }})</option>
                            </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="rescheduled" class="form-label fw-medium"><i class="bi bi-arrow-repeat me-1"></i>Rescheduled</label>
                            <div class="select-wrapper">
                            <select class="form-select modern-select" id="rescheduled" name="rescheduled">
                                <option value="all" {{ request('rescheduled') == 'all' || !request('rescheduled') ? 'selected' : '' }}>All</option>
                                <option value="yes" {{ request('rescheduled') == 'yes' ? 'selected' : '' }}>Yes ({{ $rescheduledCount ?? 0 }})</option>
                                <option value="no" {{ request('rescheduled') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="emergency" class="form-label fw-medium"><i class="bi bi-exclamation-triangle me-1"></i>Emergency</label>
                            <div class="select-wrapper">
                            <select class="form-select modern-select" id="emergency" name="emergency">
                                <option value="all" {{ request('emergency') == 'all' || !request('emergency') ? 'selected' : '' }}>All</option>
                                <option value="yes" {{ request('emergency') == 'yes' ? 'selected' : '' }}>Yes ({{ $emergencyCount ?? 0 }})</option>
                                <option value="no" {{ request('emergency') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="month" class="form-label fw-medium"><i class="bi bi-calendar-month me-1"></i>Month</label>
                            <div class="select-wrapper">
                            <select class="form-select modern-select" id="month" name="month">
                                <option value="all" {{ request('month') == 'all' || !request('month') ? 'selected' : '' }}>All</option>
                                @foreach($availableMonths ?? [] as $monthOption)
                                    <option value="{{ $monthOption['value'] }}" {{ request('month') == $monthOption['value'] ? 'selected' : '' }}>{{ $monthOption['label'] }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-8">
                            <div class="active-filters" id="activeFilters"></div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-outline-secondary btn-modern me-2" onclick="resetFilters()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset
                            </button>
                            <button type="button" class="btn btn-primary btn-modern" onclick="document.getElementById('filterForm').submit();">
                                <i class="bi bi-funnel-fill me-2"></i>Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Table Controls (Show Entry & Search) -->
                    <div class="table-controls mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label for="per_page" class="form-label fw-medium mb-0">
                                    <i class="bi bi-list-ul me-1"></i>Show:
                                </label>
                                <select class="form-select table-control-select" id="per_page" name="per_page" onchange="updatePerPage(this.value)">
                                    <option value="5" {{ ($perPage ?? 10) == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="ms-2">entries</span>
                            </div>
                            <div class="col-md-8 text-end">
                                <label for="search" class="form-label fw-medium mb-0">
                                    <i class="bi bi-search me-1"></i>Search:
                                </label>
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control table-search-input" id="search" name="search"
                                           placeholder="Search by patient name or service..."
                                           value="{{ $search ?? '' }}"
                                           onkeypress="if(event.key === 'Enter') { performSearch(); }">
                                    <i class="bi bi-search search-icon clickable-search-icon" onclick="performSearch()" title="Click to search"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover appointment-table" id="appointmentsTable">
                            <thead>
                                <tr>
                                    <th class="sortable-header" data-sort="id">
                                        No.
                                        <i class="bi bi-arrow-down-up sort-icon"></i>
                                    </th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th class="sortable-header" data-sort="start_datetime">
                                        Date & Time
                                        <i class="bi bi-arrow-down-up sort-icon"></i>
                                    </th>
                                    <th class="sortable-header" data-sort="duration_minutes">
                                        Duration
                                        <i class="bi bi-arrow-down-up sort-icon"></i>
                                    </th>
                                    <th class="sortable-header" data-sort="status">
                                        Status
                                        <i class="bi bi-arrow-down-up sort-icon"></i>
                                    </th>
                                    <th class="sortable-header" data-sort="rescheduled_at">
                                        Rescheduled
                                        <i class="bi bi-arrow-down-up sort-icon"></i>
                                    </th>
                                    <th>Emergency</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    @php
                                        $patientName = 'Unknown Patient';
                                        if ($appointment->patient && $appointment->patient->info) {
                                            $patientName = trim($appointment->patient->info->first_name . ' ' . $appointment->patient->info->last_name);
                                        } elseif ($appointment->patient && $appointment->patient->name) {
                                            $patientName = $appointment->patient->name;
                                        }

                                        $serviceName = 'No Service';
                                        if ($appointment->service && $appointment->service->service_name) {
                                            $serviceName = $appointment->service->service_name;
                                        } elseif ($appointment->reason_for_visit) {
                                            $serviceName = $appointment->reason_for_visit;
                                        }

                                        $isRescheduled = !is_null($appointment->rescheduled_at);
                                        $isEmergency = false;
                                        if ($appointment->notes && (stripos($appointment->notes, 'emergency') !== false)) {
                                            $isEmergency = true;
                                        } elseif ($appointment->reason_for_visit && (stripos($appointment->reason_for_visit, 'emergency') !== false)) {
                                            $isEmergency = true;
                                        }

                                        $startDate = \Carbon\Carbon::parse($appointment->start_datetime);
                                        $endDate = \Carbon\Carbon::parse($appointment->end_datetime);
                                    @endphp
                                    <tr data-appointment-id="{{ $appointment->id }}">
                                        <td>{{ ($appointments->currentPage() - 1) * $appointments->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <div class="patient-cell">
                                                <strong>{{ $patientName }}</strong>
                                                @if($appointment->patient && $appointment->patient->info && $appointment->patient->info->phone)
                                                    <br><small class="text-muted">{{ $appointment->patient->info->phone }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $serviceName }}</td>
                                        <td>
                                            <div class="datetime-cell">
                                                <strong>{{ $startDate->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $startDate->format('h:i A') }} - {{ $endDate->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $appointment->duration_minutes }} min</td>
                                        <td>
                                            @php
                                                $statusClass = match(strtolower($appointment->status)) {
                                                    'pending' => 'warning',
                                                    'confirmed' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                    'missed' => 'secondary',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $appointment->status }}</span>
                                        </td>
                                         <td>
                                             @if($isRescheduled)
                                                 <span class="badge bg-info">
                                                     <i class="bi bi-arrow-repeat me-1"></i>Yes
                                                 </span>
                                             @else
                                                 <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                     <i class="bi bi-x-circle me-1"></i>No
                                                 </span>
                                             @endif
                                         </td>
                                         <td>
                                             @if($isEmergency)
                                                 <span class="badge bg-danger">
                                                     <i class="bi bi-exclamation-triangle me-1"></i>Yes
                                                 </span>
                                             @else
                                                 <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                     <i class="bi bi-x-circle me-1"></i>No
                                                 </span>
                                             @endif
                                         </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary view-appointment" data-appointment-id="{{ $appointment->id }}" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-appointment" data-appointment-id="{{ $appointment->id }}" title="Delete Appointment">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No appointments found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="table-pagination mt-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-0 text-muted pagination-info">
                                    Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} entries
                                </p>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Appointment pagination">
                                    {{ $appointments->links('pagination::bootstrap-4') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. The appointment will be permanently deleted.
                </div>
                <p class="mb-3">To confirm deletion, please enter your password:</p>
                <div class="mb-3">
                    <label for="deletePassword" class="form-label fw-medium">
                        <i class="bi bi-key me-1"></i>Password
                    </label>
                    <input type="password" class="form-control" id="deletePassword" placeholder="Enter your password" autocomplete="current-password">
                    <div class="invalid-feedback" id="passwordError"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeleteAppointmentBtn">
                    <i class="bi bi-trash me-1"></i>Delete Appointment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="deleteSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Success</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-2">Appointment Deleted Successfully</h5>
                <p class="text-muted mb-0">The appointment has been permanently deleted from the system.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="window.location.reload();">
                    <i class="bi bi-check me-1"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    renderActiveFilters();
    initializeSorting();
    // View appointment details
    document.querySelectorAll('.view-appointment').forEach(btn => {
        btn.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-appointment-id');
            fetchAppointmentDetails(appointmentId);
        });
    });

    function fetchAppointmentDetails(appointmentId) {
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
            showAppointmentDetails(appointment);
        })
        .catch(error => {
            console.error('Error fetching appointment:', error);
            alert('Error loading appointment details');
        });
    }

    function showAppointmentDetails(appointment) {
        let patientName = 'Unknown Patient';
        if (appointment.patient && appointment.patient.info) {
            const info = appointment.patient.info;
            patientName = `${info.first_name} ${info.last_name}`.trim();
        } else if (appointment.patient && appointment.patient.name) {
            patientName = appointment.patient.name;
        }

        let serviceName = 'No Service';
        if (appointment.service && appointment.service.service_name) {
            serviceName = appointment.service.service_name;
        } else if (appointment.reason_for_visit) {
            serviceName = appointment.reason_for_visit;
        }

        const startDateTime = new Date(appointment.start_datetime);
        const endDateTime = new Date(appointment.end_datetime);

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

        const statusClass = appointment.status.toLowerCase();
        const statusBadgeClass = statusClass === 'pending' ? 'bg-warning' :
                                statusClass === 'confirmed' ? 'bg-primary' :
                                statusClass === 'completed' ? 'bg-success' :
                                statusClass === 'cancelled' ? 'bg-danger' : 'bg-secondary';

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

        document.getElementById('appointment-details-content').innerHTML = detailsHTML;

        new bootstrap.Modal(document.getElementById('appointmentDetailsModal')).show();
    }
    // Delete appointment
    let selectedAppointmentId = null;

    const deleteModalEl = document.getElementById('deleteAppointmentModal');
    const successModalEl = document.getElementById('deleteSuccessModal');

    const deleteModal = bootstrap.Modal.getOrCreateInstance(deleteModalEl);
    const successModal = bootstrap.Modal.getOrCreateInstance(successModalEl);

    document.querySelectorAll('.delete-appointment').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedAppointmentId = this.getAttribute('data-appointment-id');
            // Reset password field and error
            document.getElementById('deletePassword').value = '';
            document.getElementById('deletePassword').classList.remove('is-invalid');
            document.getElementById('passwordError').textContent = '';
            deleteModal.show();
        });
    });

    // Handle password input Enter key
    document.getElementById('deletePassword').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('confirmDeleteAppointmentBtn').click();
        }
    });

    document.getElementById('confirmDeleteAppointmentBtn').addEventListener('click', function() {
        if (!selectedAppointmentId) return;
        
        const password = document.getElementById('deletePassword').value.trim();
        const passwordInput = document.getElementById('deletePassword');
        const passwordError = document.getElementById('passwordError');
        
        // Validate password is provided
        if (!password) {
            passwordInput.classList.add('is-invalid');
            passwordError.textContent = 'Please enter your password to confirm deletion.';
            return;
        }
        
        // Remove any previous error
        passwordInput.classList.remove('is-invalid');
        passwordError.textContent = '';
        
        // Disable button and show loading state on button
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deleting...';

        // Send delete request with password
        fetch(`/admin/appointment/${selectedAppointmentId}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                force: true,
                password: password
            })
        })
        .then(async (res) => {
            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                const error = new Error(data.message || 'Failed to delete appointment. Please try again.');
                error.status = res.status;
                throw error;
            }

            return data;
        })
        .then(() => {
            // Restore button state
            const confirmBtn = document.getElementById('confirmDeleteAppointmentBtn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Delete Appointment';

            // Close delete modal and show success modal
            deleteModal.hide();
            successModal.show();
            successModalEl.addEventListener('hidden.bs.modal', function handler() {
                successModalEl.removeEventListener('hidden.bs.modal', handler);
                window.location.reload();
            }, { once: true });
        })
        .catch(err => {
            console.error('Error deleting appointment:', err);
            const message = err.status === 403 ? 'Incorrect password.' : (err.message || 'Failed to delete appointment. Please try again.');

            const confirmBtn = document.getElementById('confirmDeleteAppointmentBtn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Delete Appointment';

            passwordInput.classList.add('is-invalid');
            passwordError.textContent = message;

            deleteModal.show();
        });
    });
});

function toggleSortOrder() {
    const form = document.getElementById('filterForm');
    const currentOrder = form.querySelector('input[name="sort_order"]').value;
    form.querySelector('input[name="sort_order"]').value = currentOrder === 'desc' ? 'asc' : 'desc';
    form.submit();
}

function resetFilters() {
    window.location.href = '{{ route("admin-appointment.table") }}';
}

function updatePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', '1'); // Reset to first page
    window.location.href = url.toString();
}

function performSearch() {
    const searchInput = document.getElementById('search');
    const value = searchInput.value.trim();
    const url = new URL(window.location.href);
    if (value === '') {
        url.searchParams.delete('search');
    } else {
        url.searchParams.set('search', value);
    }
    url.searchParams.set('page', '1'); // Reset to first page
    window.location.href = url.toString();
}

function renderActiveFilters() {
    const container = document.getElementById('activeFilters');
    if (!container) return;
    container.innerHTML = '';
    const params = new URLSearchParams(window.location.search);
    const entries = [
        ['status', 'Status'],
        ['rescheduled', 'Rescheduled'],
        ['emergency', 'Emergency'],
        ['month', 'Month']
    ];
    entries.forEach(([key, label]) => {
        const val = params.get(key);
        if (val && val !== 'all') {
            const pill = document.createElement('span');
            pill.className = 'filter-pill';
            // Format month display
            let displayValue = val;
            if (key === 'month' && val.includes('-')) {
                const [month, year] = val.split('-');
                const date = new Date(year, month - 1, 1);
                displayValue = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            }
            pill.textContent = `${label}: ${displayValue}`;
            container.appendChild(pill);
        }
    });
}

function initializeSorting() {
    const params = new URLSearchParams(window.location.search);
    const currentSort = params.get('sort_by');
    const currentOrder = params.get('sort_order') || 'desc';

    // Update sort icons based on current sort
    document.querySelectorAll('.sortable-header').forEach(header => {
        const sortField = header.getAttribute('data-sort');
        const icon = header.querySelector('.sort-icon');

        if (currentSort === sortField) {
            header.classList.add('sort-active');
            if (currentOrder === 'asc') {
                header.classList.add('sort-asc');
                icon.className = 'bi bi-arrow-up sort-icon';
            } else {
                header.classList.add('sort-desc');
                icon.className = 'bi bi-arrow-down sort-icon';
            }
        } else {
            icon.className = 'bi bi-arrow-down-up sort-icon';
        }
    });

    // Add click handlers to sortable headers
    document.querySelectorAll('.sortable-header').forEach(header => {
        header.addEventListener('click', function() {
            const sortField = this.getAttribute('data-sort');
            const currentSortField = params.get('sort_by');
            const currentOrder = params.get('sort_order') || 'desc';

            // Determine new sort order
            let newOrder = 'asc';
            if (currentSortField === sortField && currentOrder === 'asc') {
                newOrder = 'desc';
            }

            // Update URL parameters
            params.set('sort_by', sortField);
            params.set('sort_order', newOrder);

            // Reload page with new sort parameters
            window.location.href = '{{ route("admin-appointment.table") }}?' + params.toString();
        });
    });
}

</script>
<style>
/* Filter section - Clean white card style */
.filters-section {
    background: white;
    border-radius: 12px;
    margin-bottom: 2rem;
    overflow: hidden;
}

.filters-section .card-header {
    background: white;
    border-bottom: 1px solid #e0e0e0;
    padding: 1rem 1.5rem;
}

.filters-section .card-body {
    padding: 1.5rem;
}

.filters-section h5 {
    color: #3b82f6;
    font-size: 1.1rem;
    margin: 0;
}

.form-label.fw-medium {
    color: #1e293b;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-label.fw-medium i {
    color: #64748b;
}

/* Select Wrapper - Complete arrow removal */
.select-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.select-wrapper::after {
    content: '';
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    pointer-events: none;
    z-index: 2;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23334155' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-size: contain;
}

.modern-select {
    border-radius: 8px;
    border: 1.5px solid #e0e0e0;
    padding: 0.625rem 1rem;
    padding-right: 2.75rem;
    transition: all 0.3s ease;
    background: white;
    background-image: none !important;
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px;
    color: #1e293b;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    font-size: 0.9rem;
    cursor: pointer;
    width: 100%;
    position: relative;
    z-index: 1;
}

.modern-select::-ms-expand {
    display: none !important;
}

.modern-select::-webkit-select {
    appearance: none !important;
}

.modern-select::-moz-select {
    appearance: none !important;
}

.modern-select:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.modern-select option {
    color: #1e293b;
    background: white;
    padding: 0.5rem;
}

.modern-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
    background-image: none !important;
}

.select-wrapper:has(.modern-select:focus)::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%233b82f6' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
}

.btn-modern {
    border-radius: 8px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    transition: all .3s ease;
    font-size: 0.9rem;
}

.btn-primary.btn-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}

.btn-primary.btn-modern:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
    color: white;
}

.btn-outline-secondary.btn-modern {
    border: 1.5px solid #e0e0e0;
    color: #475569;
    background: white;
}

.btn-outline-secondary.btn-modern:hover {
    background: #f8f9fa;
    border-color: #cbd5e1;
    transform: translateY(-2px);
    color: #1e293b;
}

.active-filters .filter-pill {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #1e40af;
    border: 1px solid #bfdbfe;
    border-radius: 999px;
    padding: .375rem .75rem;
    font-weight: 600;
    font-size: .8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    display: inline-block;
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
    min-height: 2rem;
}

/* Table background matching filter section */
.appointment-table {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
    border-radius: 15px;
    overflow: hidden;
}

.appointment-table thead {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
}

.appointment-table thead th {
    color: #000;
    border-bottom: 1px solid rgba(0, 0, 0, 0.2);
    font-weight: 600;
    background: rgba(255, 255, 255, 0.95);
}

.appointment-table tbody {
    background: rgba(255, 255, 255, 0.98);
}

.appointment-table tbody tr {
    background: rgba(255, 255, 255, 0.98);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.appointment-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.9);
}

.appointment-table tbody td {
    color: #000;
}

/* Sortable table headers */
.sortable-header {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 2.5rem !important;
}

.sortable-header .sort-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.7;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    color: #000;
}

.sortable-header:hover .sort-icon {
    opacity: 1;
}

.sortable-header.sort-active .sort-icon {
    opacity: 1;
    color: #001f3f;
}

/* Dark Mode Support - Dark Blue Navy Theme */
[data-theme="dark"] .filters-section {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

[data-theme="dark"] .filters-section .card-header {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%) !important;
    border-bottom-color: rgba(255, 255, 255, 0.15) !important;
}

[data-theme="dark"] .filters-section .card-body {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%) !important;
}

[data-theme="dark"] .filters-section h5 {
    color: white !important;
}

[data-theme="dark"] .form-label.fw-medium {
    color: white !important;
}

[data-theme="dark"] .form-label.fw-medium i {
    color: white !important;
}

[data-theme="dark"] .select-wrapper::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
}

[data-theme="dark"] .select-wrapper:has(.modern-select:focus)::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
}

[data-theme="dark"] .modern-select {
    background: rgba(255, 255, 255, 0.1) !important;
    background-image: none !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px !important;
    border: 1.5px solid rgba(255, 255, 255, 0.25) !important;
    color: white !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
    cursor: pointer !important;
}

[data-theme="dark"] .modern-select::-ms-expand {
    display: none !important;
}

[data-theme="dark"] .modern-select::-webkit-select {
    appearance: none !important;
}

[data-theme="dark"] .modern-select::-moz-select {
    appearance: none !important;
}

[data-theme="dark"] .modern-select:hover {
    border-color: rgba(255, 255, 255, 0.4) !important;
    background: rgba(255, 255, 255, 0.15) !important;
}

[data-theme="dark"] .modern-select option {
    background: #0f172a !important;
    color: white !important;
}

[data-theme="dark"] .modern-select option:checked,
[data-theme="dark"] .modern-select option:hover {
    background: #3b82f6 !important;
    color: white !important;
}

[data-theme="dark"] .modern-select:focus {
    background: rgba(255, 255, 255, 0.15) !important;
    background-image: none !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15) !important;
    color: white !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    -ms-appearance: none !important;
}

[data-theme="dark"] .btn-outline-secondary.btn-modern {
    border: 1.5px solid rgba(255, 255, 255, 0.3) !important;
    color: white !important;
    background: transparent !important;
}

[data-theme="dark"] .btn-outline-secondary.btn-modern:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
    color: white !important;
    transform: translateY(-2px) !important;
}

[data-theme="dark"] .btn-primary.btn-modern {
    background: #3b82f6 !important;
    color: white !important;
    border: none !important;
}

[data-theme="dark"] .btn-primary.btn-modern:hover {
    background: #2563eb !important;
    color: white !important;
}

[data-theme="dark"] .active-filters .filter-pill {
    background: rgba(255, 255, 255, 0.15) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
}

/* Card styling to allow table background to show */
.card {
    background: transparent;
    border: none;
}

.card-body {
    background: transparent;
    padding: 1rem;
}

.table-responsive {
    border-radius: 15px;
    overflow: hidden;
}

[data-theme="dark"] .card,
[data-theme="dark"] .card-body {
    background: transparent;
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .appointment-table {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
}

[data-theme="dark"] .appointment-table thead {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
}

[data-theme="dark"] .appointment-table thead th {
    color: white;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
}

[data-theme="dark"] .appointment-table tbody {
    background: var(--dm-card-bg, #1a1a1a);
}

[data-theme="dark"] .sortable-header {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
}

[data-theme="dark"] .sortable-header .sort-icon {
    color: white;
}

[data-theme="dark"] .sortable-header.sort-active .sort-icon {
    color: #fff;
}

[data-theme="dark"] .appointment-table tbody tr {
    background: var(--dm-card-bg, #1a1a1a);
    border-bottom-color: var(--dm-border-color, #333);
}

[data-theme="dark"] .appointment-table tbody tr:hover {
    background: var(--dm-bg-secondary, #2a2a2a);
}

[data-theme="dark"] .appointment-table tbody td {
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .appointment-table .patient-cell strong,
[data-theme="dark"] .appointment-table .datetime-cell strong {
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .appointment-table .patient-cell small,
[data-theme="dark"] .appointment-table .datetime-cell small {
    color: var(--dm-text-muted, #9ca3af);
}

/* Table Controls Styles */
.table-controls {
    padding: 1rem;
    background: rgba(248, 249, 250, 0.5);
    border-radius: 10px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.table-controls .form-label {
    display: inline-flex;
    align-items: center;
    margin-bottom: 0.5rem;
    color: #000;
    font-weight: 600;
}

.table-controls .form-label i {
    color: #000;
}

.table-control-select {
    display: inline-block;
    width: auto;
    min-width: 80px;
    margin-left: 0.5rem;
    padding: 0.5rem 2rem 0.5rem 0.75rem;
    border-radius: 8px;
    border: 1.5px solid #dee2e6;
    background: white;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23000000' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    background-size: 14px;
    color: #000;
    font-size: 0.9rem;
    font-weight: 500;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.table-control-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23000000' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
}

.table-controls span {
    color: #000;
    font-weight: 500;
    margin-left: 0.5rem;
}

.search-input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
    max-width: 400px;
    margin-left: 0.5rem;
}

.table-search-input {
    padding: 0.5rem 2.5rem 0.5rem 0.75rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    background: white;
    font-size: 0.9rem;
    width: 100%;
    color: #000;
}

.table-search-input::placeholder {
    color: #6c757d;
}

.search-input-wrapper .search-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
}

.search-input-wrapper .clickable-search-icon {
    cursor: pointer;
    pointer-events: all;
    transition: color 0.2s ease;
}

.search-input-wrapper .clickable-search-icon:hover {
    color: #3b82f6;
}

.table-search-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.table-pagination {
    padding: 1rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.pagination-info {
    font-size: 0.9rem;
}

/* Bootstrap Pagination Override */
.pagination {
    margin-bottom: 0;
    justify-content: flex-end;
}

.pagination .page-link {
    color: #495057;
    border-color: #dee2e6;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    margin: 0 0.25rem;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
    border-color: #001f3f;
    color: white;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #001f3f;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}

/* Dark Mode for Table Controls */
[data-theme="dark"] .table-controls {
    background: var(--dm-card-bg, #1e293b);
    border-color: var(--dm-border-color, #334155);
    border-width: 1.5px;
}

[data-theme="dark"] .table-controls .form-label {
    color: var(--dm-text-primary, #e5e7eb);
    font-weight: 600;
}

[data-theme="dark"] .table-controls .form-label i {
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .table-controls span {
    color: var(--dm-text-primary, #e5e7eb);
    font-weight: 500;
}

[data-theme="dark"] .table-control-select,
[data-theme="dark"] .table-search-input {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-border-color, #475569) !important;
    border-width: 1.5px;
    color: var(--dm-text-primary, #e5e7eb) !important;
    font-weight: 500;
}

[data-theme="dark"] .table-search-input::placeholder {
    color: var(--dm-text-muted, #94a3b8) !important;
    opacity: 1;
}

[data-theme="dark"] .table-control-select {
    background: var(--dm-input-bg, #0f172a) !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23e5e7eb' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.5rem center !important;
    background-size: 14px !important;
}

[data-theme="dark"] .table-control-select option {
    background: var(--dm-input-bg, #0f172a);
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .table-control-select:focus,
[data-theme="dark"] .table-search-input:focus {
    border-color: #3b82f6 !important;
    background: var(--dm-bg-tertiary, #334155) !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23e5e7eb' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.5rem center !important;
    background-size: 14px !important;
    color: var(--dm-text-primary, #e5e7eb) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    outline: none;
}

[data-theme="dark"] .search-input-wrapper .search-icon {
    color: var(--dm-text-muted, #94a3b8);
}

[data-theme="dark"] .search-input-wrapper .clickable-search-icon {
    color: var(--dm-text-muted, #94a3b8);
}

[data-theme="dark"] .search-input-wrapper .clickable-search-icon:hover {
    color: #60a5fa;
}

[data-theme="dark"] .table-pagination {
    border-top-color: var(--dm-border-color, #334155);
}

[data-theme="dark"] .pagination-info {
    color: var(--dm-text-muted, #94a3b8);
}

[data-theme="dark"] .pagination .page-link {
    color: var(--dm-text-primary, #e5e7eb);
    background: var(--dm-card-bg, #1e293b);
    border-color: var(--dm-border-color, #334155);
}

[data-theme="dark"] .pagination .page-link:hover {
    background: var(--dm-bg-tertiary, #334155);
    border-color: var(--dm-border-color, #334155);
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
    border-color: #001f3f;
    color: white;
}

[data-theme="dark"] .pagination .page-item.disabled .page-link {
    color: var(--dm-text-muted, #64748b);
    background: var(--dm-card-bg, #1e293b);
    border-color: var(--dm-border-color, #334155);
}

/* Delete Modal Styles */
#deleteAppointmentModal .modal-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border: none;
}

#deleteAppointmentModal .modal-header .modal-title {
    color: white;
    font-weight: 700;
}

#deleteAppointmentModal .alert-warning {
    border-left: 4px solid #f59e0b;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
}

#deleteAppointmentModal .form-label {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

#deleteAppointmentModal .form-control {
    border-radius: 8px;
    border: 1.5px solid #e0e0e0;
    padding: 0.625rem 0.875rem;
    transition: all 0.3s ease;
}

#deleteAppointmentModal .form-control:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    outline: none;
}

#deleteAppointmentModal .form-control.is-invalid {
    border-color: #dc2626;
}

#deleteAppointmentModal .invalid-feedback {
    display: block;
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

#deleteAppointmentModal .btn-danger {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border: none;
    font-weight: 600;
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

#deleteAppointmentModal .btn-danger:hover:not(:disabled) {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

#deleteAppointmentModal .btn-danger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Success Modal Styles */
#deleteSuccessModal .modal-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

#deleteSuccessModal .modal-header .modal-title {
    color: white;
    font-weight: 700;
}

#deleteSuccessModal .btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    font-weight: 600;
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

#deleteSuccessModal .btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Dark Mode for Modals */
[data-theme="dark"] #deleteAppointmentModal .modal-content {
    background: var(--dm-card-bg, #1e293b);
    border-color: var(--dm-border-color, #334155);
}

[data-theme="dark"] #deleteAppointmentModal .alert-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%);
    border-left-color: #f59e0b;
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] #deleteAppointmentModal .form-label {
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] #deleteAppointmentModal .form-control {
    background: var(--dm-input-bg, #0f172a);
    border-color: var(--dm-border-color, #475569);
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] #deleteAppointmentModal .form-control:focus {
    background: var(--dm-bg-tertiary, #334155);
    border-color: #dc2626;
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] #deleteAppointmentModal .form-control::placeholder {
    color: var(--dm-text-muted, #64748b);
}

[data-theme="dark"] #deleteSuccessModal .modal-content {
    background: var(--dm-card-bg, #1e293b);
    border-color: var(--dm-border-color, #334155);
}

[data-theme="dark"] #deleteSuccessModal .modal-body h5 {
    color: var(--dm-text-primary, #f1f5f9);
}

[data-theme="dark"] #deleteSuccessModal .modal-body p {
    color: var(--dm-text-muted, #94a3b8);
}

</style>
@endsection

