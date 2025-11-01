@extends('layout.staff.app')
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
                <a href="{{ route('staff-appointment') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-calendar3 me-1"></i>Calendar View
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section (Activity Logs style) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filters-section">
                <h5 class="mb-3 fw-semibold"><i class="bi bi-funnel me-2"></i>Filter Options</h5>
                <form method="GET" action="{{ route('staff-appointment.table') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="status" class="form-label fw-medium"><i class="bi bi-flag me-1"></i>Status</label>
                            <select class="form-select modern-select" id="status" name="status">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All ({{ $statusCounts['all'] ?? 0 }})</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending ({{ $statusCounts['Pending'] ?? 0 }})</option>
                                <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed ({{ $statusCounts['Confirmed'] ?? 0 }})</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed ({{ $statusCounts['Completed'] ?? 0 }})</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled ({{ $statusCounts['Cancelled'] ?? 0 }})</option>
                                <option value="Missed" {{ request('status') == 'Missed' ? 'selected' : '' }}>Missed ({{ $statusCounts['Missed'] ?? 0 }})</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="rescheduled" class="form-label fw-medium"><i class="bi bi-arrow-repeat me-1"></i>Rescheduled</label>
                            <select class="form-select modern-select" id="rescheduled" name="rescheduled">
                                <option value="all" {{ request('rescheduled') == 'all' || !request('rescheduled') ? 'selected' : '' }}>All</option>
                                <option value="yes" {{ request('rescheduled') == 'yes' ? 'selected' : '' }}>Yes ({{ $rescheduledCount ?? 0 }})</option>
                                <option value="no" {{ request('rescheduled') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="emergency" class="form-label fw-medium"><i class="bi bi-exclamation-triangle me-1"></i>Emergency</label>
                            <select class="form-select modern-select" id="emergency" name="emergency">
                                <option value="all" {{ request('emergency') == 'all' || !request('emergency') ? 'selected' : '' }}>All</option>
                                <option value="yes" {{ request('emergency') == 'yes' ? 'selected' : '' }}>Yes ({{ $emergencyCount ?? 0 }})</option>
                                <option value="no" {{ request('emergency') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="month" class="form-label fw-medium"><i class="bi bi-calendar-month me-1"></i>Month</label>
                            <select class="form-select modern-select" id="month" name="month">
                                <option value="all" {{ request('month') == 'all' || !request('month') ? 'selected' : '' }}>All</option>
                                @foreach($availableMonths ?? [] as $monthOption)
                                    <option value="{{ $monthOption['value'] }}" {{ request('month') == $monthOption['value'] ? 'selected' : '' }}>{{ $monthOption['label'] }}</option>
                                @endforeach
                            </select>
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
                                        ID
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
                                        <td>{{ $loop->iteration }}</td>
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
                                                <span class="text-muted">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isEmergency)
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Yes
                                                </span>
                                            @else
                                                <span class="text-muted">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary view-appointment" data-appointment-id="{{ $appointment->id }}" title="View Details">
                                                <i class="bi bi-eye"></i>
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

<!-- Appointment Details Modal (reuse from appointment.blade.php) -->
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        fetch(`/staff/appointment/${appointmentId}`, {
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
});

function toggleSortOrder() {
    const form = document.getElementById('filterForm');
    const currentOrder = form.querySelector('input[name="sort_order"]').value;
    form.querySelector('input[name="sort_order"]').value = currentOrder === 'desc' ? 'asc' : 'desc';
    form.submit();
}

function resetFilters() {
    window.location.href = '{{ route("staff-appointment.table") }}';
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
            window.location.href = '{{ route("staff-appointment.table") }}?' + params.toString();
        });
    });
}
</script>
<style>
/* Filter section with navy blue background */
.filters-section {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    color: white;
}

.filters-section h5 {
    color: white;
}

.form-label.fw-medium {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.modern-select {
    border-radius: 10px;
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    padding: 0.625rem 1rem;
    padding-right: 2.5rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px;
    color: white;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.modern-select option {
    color: #001f3f;
    background: white;
}

.modern-select:focus {
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.15);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px;
    color: white;
}

.btn-modern {
    border-radius: 10px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    transition: all .3s ease;
    border: none;
}

.btn-primary.btn-modern {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-primary.btn-modern:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    color: white;
}

.btn-outline-secondary.btn-modern {
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    background: transparent;
}

.btn-outline-secondary.btn-modern:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    color: white;
}

.active-filters .filter-pill {
    background: rgba(255, 255, 255, 0.9);
    color: #001f3f;
    border-radius: 999px;
    padding: .25rem .6rem;
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

/* Dark Mode Support */
[data-theme="dark"] .filters-section {
    background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .filters-section h5,
[data-theme="dark"] .form-label.fw-medium {
    color: rgba(255, 255, 255, 0.9);
}

[data-theme="dark"] .modern-select {
    background: rgba(255, 255, 255, 0.1) !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px !important;
    border-color: rgba(255, 255, 255, 0.25);
    color: white;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

[data-theme="dark"] .modern-select option {
    background: var(--dm-input-bg, #0f172a) !important;
    color: var(--dm-text-primary, #e5e7eb) !important;
}

[data-theme="dark"] .modern-select option:hover,
[data-theme="dark"] .modern-select option:checked {
    background: var(--dm-bg-tertiary, #334155) !important;
    color: var(--dm-text-primary, #e5e7eb) !important;
}

[data-theme="dark"] .modern-select:focus {
    background: rgba(255, 255, 255, 0.15) !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px !important;
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
}

[data-theme="dark"] .active-filters .filter-pill {
    background: rgba(255, 255, 255, 0.15) !important;
    color: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
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

</style>
@endsection

