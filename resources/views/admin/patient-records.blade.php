@extends('layout.admin.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/patient-records.css') }}">

<div class="patient-records-container">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-file-medical-fill text-primary me-2"></i>Patient Record Access
                </h2>
                <p class="text-muted mb-0">Search and view patient medical and dental history</p>
            </div>
            <div class="stats-badge">
                <i class="bi bi-people-fill me-2"></i>
                <span id="totalPatients">{{ $totalPatients ?? 0 }}</span> Total Patients
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Search Patient
                    </label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-person-fill text-primary"></i>
                        </span>
                        <input type="text" class="form-control" id="patientSearch"
                               placeholder="Search by name, patient ID, or email..." autofocus>
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="bi bi-search me-1"></i>Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-funnel-fill me-1"></i>Filter by Status
                    </label>
                    <select class="form-select form-select-lg" id="statusFilter">
                        <option value="">All Patients</option>
                        <option value="with_appointments">With Appointments</option>
                        <option value="with_records">With Medical Records</option>
                        <option value="active">Active Patients</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="row">
        <!-- Patient List -->
        <div class="col-md-4">
            <div class="card shadow-sm patient-list-card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>Patients
                        <span class="badge bg-white text-primary float-end" id="resultCount">0</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div id="patientListContainer" class="patient-list">
                        <div class="empty-state text-center py-5">
                            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Search for a patient to view their records</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Details -->
        <div class="col-md-8">
            <div class="card shadow-sm patient-details-card">
                <div class="card-header bg-gradient">
                    <h6 class="mb-0 text-white">
                        <i class="bi bi-person-badge-fill me-2"></i>Patient Details
                    </h6>
                </div>
                <div class="card-body">
                    <div id="patientDetailsContainer">
                        <div class="empty-state text-center py-5">
                            <i class="bi bi-file-earmark-medical text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No Patient Selected</h5>
                            <p class="text-muted">Select a patient from the list to view their medical and dental history</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let patientsData = [];
let selectedPatientId = null;

// Search patients on input
document.getElementById('patientSearch').addEventListener('input', debounce(searchPatients, 300));
document.getElementById('searchBtn').addEventListener('click', searchPatients);
document.getElementById('statusFilter').addEventListener('change', searchPatients);

// Enter key to search
document.getElementById('patientSearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchPatients();
    }
});

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search patients
function searchPatients() {
    const query = document.getElementById('patientSearch').value.trim();
    const status = document.getElementById('statusFilter').value;

    if (query.length < 2 && !status) {
        document.getElementById('patientListContainer').innerHTML = `
            <div class="empty-state text-center py-5">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Enter at least 2 characters to search</p>
            </div>
        `;
        return;
    }

    // Show loading
    document.getElementById('patientListContainer').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-3">Searching patients...</p>
        </div>
    `;

    // Fetch patients
    fetch(`/admin/patient-records/search?query=${encodeURIComponent(query)}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                patientsData = data.patients;
                displayPatientList(data.patients);
                document.getElementById('resultCount').textContent = data.patients.length;
            } else {
                showError('Failed to search patients');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Error searching patients');
        });
}

// Display patient list
function displayPatientList(patients) {
    const container = document.getElementById('patientListContainer');

    if (patients.length === 0) {
        container.innerHTML = `
            <div class="empty-state text-center py-5">
                <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No patients found</p>
            </div>
        `;
        return;
    }

    let html = '<div class="list-group list-group-flush">';
    patients.forEach(patient => {
        const hasRecords = patient.has_records ? '<i class="bi bi-file-earmark-medical-fill text-success ms-2"></i>' : '';
        const appointmentCount = patient.appointments_count || 0;

        html += `
            <a href="#" class="list-group-item list-group-item-action patient-item"
               data-patient-id="${patient.id}" onclick="viewPatient(${patient.id}); return false;">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">${patient.name}${hasRecords}</h6>
                        <small class="text-muted d-block">
                            <i class="bi bi-envelope me-1"></i>${patient.email}
                        </small>
                        <small class="text-muted d-block">
                            <i class="bi bi-calendar-check me-1"></i>${appointmentCount} appointment(s)
                        </small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary">ID: ${patient.id}</span>
                    </div>
                </div>
            </a>
        `;
    });
    html += '</div>';

    container.innerHTML = html;
}

// View patient details
function viewPatient(patientId) {
    selectedPatientId = patientId;

    // Highlight selected patient
    document.querySelectorAll('.patient-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`[data-patient-id="${patientId}"]`).classList.add('active');

    // Show loading
    document.getElementById('patientDetailsContainer').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-3">Loading patient details...</p>
        </div>
    `;

    // Fetch patient details
    fetch(`/admin/patient-records/${patientId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPatientDetails(data.patient, data.record, data.history, data.notes, data.appointments);
            } else {
                showError('Failed to load patient details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Error loading patient details');
        });
}

// Display patient details
function displayPatientDetails(patient, record, history, notes, appointments) {
    const container = document.getElementById('patientDetailsContainer');

    const info = patient.info || {};
    const age = info.age || 'N/A';
    const gender = info.gender || 'N/A';
    const phone = info.phone || 'N/A';
    const address = info.address || 'N/A';

    let html = `
        <!-- Patient Information -->
        <div class="patient-info-header mb-4">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="bi bi-person-circle me-2"></i>${patient.name}
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <i class="bi bi-envelope-fill text-muted me-2"></i>
                                <strong>Email:</strong> ${patient.email}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <i class="bi bi-telephone-fill text-muted me-2"></i>
                                <strong>Phone:</strong> ${phone}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <i class="bi bi-calendar-fill text-muted me-2"></i>
                                <strong>Age:</strong> ${age} years
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <i class="bi bi-gender-ambiguous text-muted me-2"></i>
                                <strong>Gender:</strong> ${gender}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <i class="bi bi-geo-alt-fill text-muted me-2"></i>
                                <strong>Address:</strong> ${address}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="patient-stats">
                        <div class="stat-box bg-primary bg-opacity-10 p-3 rounded mb-2">
                            <h3 class="mb-0 text-primary">${appointments.length}</h3>
                            <small class="text-muted">Total Appointments</small>
                        </div>
                        <div class="stat-box bg-success bg-opacity-10 p-3 rounded">
                            <h3 class="mb-0 text-success">${history.length}</h3>
                            <small class="text-muted">Visit History</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Tabs for Medical Record, History, Progress Notes -->
        <ul class="nav nav-tabs mb-3" id="patientTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="medical-record-tab" data-bs-toggle="tab"
                        data-bs-target="#medical-record" type="button" role="tab">
                    <i class="bi bi-file-earmark-medical me-1"></i>Medical Record
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab"
                        data-bs-target="#history" type="button" role="tab">
                    <i class="bi bi-clock-history me-1"></i>Visit History (${history.length})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notes-tab" data-bs-toggle="tab"
                        data-bs-target="#notes" type="button" role="tab">
                    <i class="bi bi-journal-text me-1"></i>Progress Notes (${notes.length})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="appointments-tab" data-bs-toggle="tab"
                        data-bs-target="#appointments" type="button" role="tab">
                    <i class="bi bi-calendar-check me-1"></i>Appointments (${appointments.length})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="patientTabsContent">
            <!-- Medical Record Tab -->
            <div class="tab-pane fade show active" id="medical-record" role="tabpanel">
                ${renderMedicalRecord(record)}
            </div>

            <!-- Visit History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                ${renderVisitHistory(history)}
            </div>

            <!-- Progress Notes Tab -->
            <div class="tab-pane fade" id="notes" role="tabpanel">
                ${renderProgressNotes(notes)}
            </div>

            <!-- Appointments Tab -->
            <div class="tab-pane fade" id="appointments" role="tabpanel">
                ${renderAppointments(appointments)}
            </div>
        </div>
    `;

    container.innerHTML = html;
}

// Render medical record
function renderMedicalRecord(record) {
    if (!record) {
        return `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No medical record found for this patient.
            </div>
        `;
    }

    return `
        <div class="medical-record-view">
            <div class="row g-3">
                <div class="col-md-12">
                    <h6 class="fw-bold text-primary border-bottom pb-2">
                        <i class="bi bi-person-vcard me-2"></i>Patient Information
                    </h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <strong>Home Address:</strong><br>
                            ${record.home_address || 'N/A'}
                        </div>
                        <div class="col-md-4">
                            <strong>Date of Birth:</strong><br>
                            ${record.date_of_birth || 'N/A'}
                        </div>
                        <div class="col-md-4">
                            <strong>Occupation:</strong><br>
                            ${record.occupation || 'N/A'}
                        </div>
                    </div>
                </div>

                ${record.parent_guardian_name ? `
                <div class="col-md-12">
                    <h6 class="fw-bold text-primary border-bottom pb-2">
                        <i class="bi bi-people me-2"></i>Guardian Information
                    </h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <strong>Guardian Name:</strong><br>
                            ${record.parent_guardian_name}
                        </div>
                        <div class="col-md-6">
                            <strong>Guardian Occupation:</strong><br>
                            ${record.parent_guardian_occupation || 'N/A'}
                        </div>
                    </div>
                </div>
                ` : ''}

                <div class="col-md-12">
                    <h6 class="fw-bold text-primary border-bottom pb-2">
                        <i class="bi bi-heart-pulse me-2"></i>Medical History
                    </h6>
                    <div class="alert alert-light">
                        ${record.medical_history || 'No medical history recorded.'}
                    </div>
                </div>

                ${record.other_notes ? `
                <div class="col-md-12">
                    <h6 class="fw-bold text-primary border-bottom pb-2">
                        <i class="bi bi-sticky me-2"></i>Additional Notes
                    </h6>
                    <div class="alert alert-light">
                        ${record.other_notes}
                    </div>
                </div>
                ` : ''}
            </div>
        </div>
    `;
}

// Render visit history
function renderVisitHistory(history) {
    if (history.length === 0) {
        return `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No visit history found for this patient.
            </div>
        `;
    }

    let html = '<div class="visit-history-list">';
    history.forEach((visit, index) => {
        html += `
            <div class="visit-card card mb-3 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="bi bi-calendar-event me-2"></i>Visit #${index + 1}
                        </h6>
                        <span class="badge bg-primary">${new Date(visit.visit_date).toLocaleDateString()}</span>
                    </div>
                    <div class="visit-details">
                        <p><strong>Procedure:</strong> ${visit.procedure_done || 'N/A'}</p>
                        <p><strong>Materials Used:</strong> ${visit.materials_used || 'N/A'}</p>
                        ${visit.anesthesia ? `<p><strong>Anesthesia:</strong> ${visit.anesthesia}</p>` : ''}
                        ${visit.complications ? `<p><strong>Complications:</strong> ${visit.complications}</p>` : ''}
                        ${visit.post_op_instructions ? `<p><strong>Post-Op Instructions:</strong> ${visit.post_op_instructions}</p>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    return html;
}

// Render progress notes
function renderProgressNotes(notes) {
    if (notes.length === 0) {
        return `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No progress notes found for this patient.
            </div>
        `;
    }

    let html = '<div class="progress-notes-list">';
    notes.forEach((note, index) => {
        const statusClass = note.status === 'completed' ? 'success' : note.status === 'ongoing' ? 'warning' : 'info';
        html += `
            <div class="note-card card mb-3 border-start border-${statusClass} border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-journal-text me-2"></i>Note #${index + 1}
                        </h6>
                        <span class="badge bg-${statusClass}">${note.status || 'N/A'}</span>
                    </div>
                    <small class="text-muted d-block mb-2">
                        <i class="bi bi-calendar me-1"></i>${new Date(note.note_date).toLocaleDateString()}
                    </small>
                    <div class="note-details">
                        <p><strong>Progress:</strong> ${note.progress_description || 'N/A'}</p>
                        ${note.treatment_response ? `<p><strong>Treatment Response:</strong> ${note.treatment_response}</p>` : ''}
                        ${note.next_steps ? `<p><strong>Next Steps:</strong> ${note.next_steps}</p>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    return html;
}

// Render appointments
function renderAppointments(appointments) {
    if (appointments.length === 0) {
        return `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No appointments found for this patient.
            </div>
        `;
    }

    let html = '<div class="appointments-list">';
    appointments.forEach((apt) => {
        const statusClass = apt.status === 'completed' ? 'success' : apt.status === 'confirmed' ? 'primary' : apt.status === 'cancelled' ? 'danger' : 'warning';
        const date = new Date(apt.start_datetime);
        html += `
            <div class="appointment-card card mb-3 border-start border-${statusClass} border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1">${apt.service?.service_name || 'No Service'}</h6>
                            <p class="mb-1">
                                <i class="bi bi-calendar me-2"></i>
                                ${date.toLocaleDateString()} at ${date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                            </p>
                            ${apt.notes ? `<p class="text-muted mb-0"><i class="bi bi-sticky me-2"></i>${apt.notes}</p>` : ''}
                        </div>
                        <span class="badge bg-${statusClass}">${apt.status}</span>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    return html;
}

// Show error message
function showError(message) {
    const container = document.getElementById('patientDetailsContainer');
    container.innerHTML = `
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>${message}
        </div>
    `;
}

// Print patient record
function printPatientRecord() {
    window.print();
}
</script>

<style>
@media print {
    .sidebar-nav, .page-header, .search-section, .patient-list-card {
        display: none !important;
    }
    .col-md-8 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

@endsection

