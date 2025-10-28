@extends('layout.patient.app')
@section('content')

<style>
.records-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

.page-header-box {
    background: white;
    border: 3px solid #2196F3;
    border-radius: 8px;
    padding: 1rem 2rem;
    display: inline-block;
    margin-bottom: 2rem;
}

.page-header-box h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #2C3E50;
    margin: 0;
    letter-spacing: 1px;
}

.records-layout {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 2rem;
}

/* Records Table */
.records-table-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.records-table {
    width: 100%;
    border-collapse: collapse;
}

.records-table thead {
    background: #26a69a;
}

.records-table thead th {
    color: white;
    font-weight: 700;
    font-size: 1rem;
    padding: 1.25rem 1.5rem;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.records-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
}

.records-table tbody tr:nth-child(even) {
    background: #f8f9fa;
}

.records-table tbody td {
    padding: 1.25rem 1.5rem;
    color: #2C3E50;
    font-size: 0.95rem;
}

.form-name {
    font-weight: 600;
}

.form-date {
    color: #64748b;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
}

.btn-view, .btn-download {
    padding: 0.5rem 1.25rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-view {
    background: #26a69a;
    color: white;
}

.btn-view:hover {
    background: #1e8e82;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(38, 166, 154, 0.3);
}

.btn-download {
    background: #2C3E50;
    color: white;
}

.btn-download:hover {
    background: #1e2938;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(44, 62, 80, 0.3);
}

/* Form Preview */
.form-preview-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 2rem;
    max-height: 800px;
    overflow-y: auto;
}

.form-preview-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2C3E50;
    margin: 0 0 1.5rem 0;
    padding-bottom: 1rem;
    border-bottom: 2px solid #2196F3;
}

.form-section {
    margin-bottom: 2rem;
}

.section-title {
    background: #eceff1;
    padding: 0.75rem 1rem;
    font-weight: 700;
    color: #2C3E50;
    font-size: 1rem;
    margin: 0 0 1rem 0;
    border-left: 4px solid #2196F3;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.field-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
}

.field-value {
    font-size: 0.95rem;
    color: #2C3E50;
    padding: 0.5rem 0;
}

.field-full-width {
    grid-column: 1 / -1;
}

.checkbox-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-top: 1rem;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #2C3E50;
}

.checkbox-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-item input[type="checkbox"]:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

@media (max-width: 1024px) {
    .records-layout {
        grid-template-columns: 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .checkbox-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .records-container {
        padding: 1rem;
    }

    .records-table thead th,
    .records-table tbody td {
        padding: 0.75rem;
        font-size: 0.85rem;
    }

    .action-buttons {
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn-view, .btn-download {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
}
</style>

<div class="records-container">
    <div class="page-header-box">
        <h1>PATIENT RECORDS</h1>
    </div>

    <div class="records-layout">
        <!-- Records Table -->
        <div class="records-table-section">
            <table class="records-table">
                    <thead>
                        <tr>
                            <th>FORM</th>
                            <th>DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                <tbody id="recordsTableBody">
                    @forelse($records as $record)
                    <!-- Patient Record -->
                    <tr>
                        <td class="form-name">
                            @if($record->chief_complaint)
                                Patient Record & Chart
                            @elseif($record->diagnosis)
                                Treatment Notes
                            @elseif($record->treatment_done)
                                Post-Treatment Record
                            @else
                                Medical Record
                            @endif
                        </td>
                        <td class="form-date">{{ \Carbon\Carbon::parse($record->created_at)->format('m/d/Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewRecord({{ $record->id }})">VIEW</button>
                                <button class="btn-download" onclick="downloadRecord({{ $record->id }})">DOWNLOAD</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Patient Histories for this record -->
                    @if($record->patientHistories && $record->patientHistories->count() > 0)
                        @foreach($record->patientHistories as $history)
                        <tr style="background: #f0f7ff;">
                            <td class="form-name" style="padding-left: 2.5rem;">
                                <i class="bi bi-arrow-return-right me-2"></i>
                                Medical History - Visit {{ $loop->iteration }}
                            </td>
                            <td class="form-date">{{ \Carbon\Carbon::parse($history->visit_date ?? $history->created_at)->format('m/d/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewHistory({{ $history->id }})">VIEW</button>
                                    <button class="btn-download" onclick="downloadHistory({{ $history->id }})">DOWNLOAD</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif

                    <!-- Progress Notes for this record -->
                    @if($record->progressNotes && $record->progressNotes->count() > 0)
                        @foreach($record->progressNotes as $note)
                        <tr style="background: #f0fff4;">
                            <td class="form-name" style="padding-left: 2.5rem;">
                                <i class="bi bi-arrow-return-right me-2"></i>
                                Progress Note - {{ \Carbon\Carbon::parse($note->note_date)->format('M d, Y') }}
                            </td>
                            <td class="form-date">{{ \Carbon\Carbon::parse($note->created_at)->format('m/d/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewProgressNote({{ $note->id }})">VIEW</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 2rem; color: #64748b;">
                            <i class="bi bi-inbox" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                            No medical records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
                        </div>

        <!-- Form Preview -->
        <div class="form-preview-section" id="recordPreviewPanel">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-file-earmark-medical" style="font-size: 4rem; opacity: 0.3;"></i>
                <p class="mt-3" style="font-size: 1.1rem;">Select a record to view details</p>
                <p class="small">Click on any VIEW button to preview the record here</p>
                </div>
        </div>
    </div>
</div>

<script>
let currentRecordId = null;

// View record in preview panel
async function viewRecord(recordId) {
    currentRecordId = recordId;
    const previewPanel = document.getElementById('recordPreviewPanel');

    // Show loading state
    previewPanel.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading record details...</p>
        </div>
    `;

    try {
        const response = await fetch(`/patient/record/${recordId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load record');
        }

        const data = await response.json();

        if (data.success) {
            displayRecordInPreview(data.record);
        } else {
            throw new Error('Invalid response');
        }
    } catch (error) {
        console.error('Error loading record:', error);
        previewPanel.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Failed to load record. Please try again.
            </div>
        `;
    }
}

// Display record details in preview panel
function displayRecordInPreview(record) {
    const content = `
        <div class="preview-header mb-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #2196F3; padding-bottom: 1rem;">
            <h2 class="form-preview-title mb-0">Patient Medical Record</h2>
            <button class="btn btn-sm" onclick="downloadRecord(${record.id})" style="background: #2C3E50; color: white; padding: 0.5rem 1.5rem; border-radius: 6px; font-weight: 600;">
                <i class="bi bi-download me-1"></i>DOWNLOAD
            </button>
        </div>
        <div class="record-detail-view">
            <h3 class="section-title">Patient Information</h3>
            <div class="form-grid mb-4">
                <div class="form-field">
                    <span class="field-label">Patient Number:</span>
                    <span class="field-value">${record.patient_number || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Sex:</span>
                    <span class="field-value">${record.sex || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Date of Birth:</span>
                    <span class="field-value">${record.date_of_birth || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Age:</span>
                    <span class="field-value">${record.age || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Contact:</span>
                    <span class="field-value">${record.contact || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Nickname:</span>
                    <span class="field-value">${record.nickname || 'N/A'}</span>
                </div>
                <div class="form-field field-full-width">
                    <span class="field-label">Home Address:</span>
                    <span class="field-value">${record.home_address || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Religion:</span>
                    <span class="field-value">${record.religion || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Occupation:</span>
                    <span class="field-value">${record.occupation || 'N/A'}</span>
                </div>
            </div>

            ${record.guardian_name ? `
            <h3 class="section-title">Guardian Information</h3>
            <div class="form-grid mb-4">
                <div class="form-field">
                    <span class="field-label">Guardian Name:</span>
                    <span class="field-value">${record.guardian_name}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Guardian Contact:</span>
                    <span class="field-value">${record.guardian_contact || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Guardian Occupation:</span>
                    <span class="field-value">${record.guardian_occupation || 'N/A'}</span>
                </div>
            </div>
            ` : ''}

            ${record.physician_name ? `
            <h3 class="section-title">Physician Information</h3>
            <div class="form-grid mb-4">
                <div class="form-field">
                    <span class="field-label">Physician Name:</span>
                    <span class="field-value">${record.physician_name}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Specialty:</span>
                    <span class="field-value">${record.physician_specialty || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Contact:</span>
                    <span class="field-value">${record.physician_contact || 'N/A'}</span>
                </div>
                <div class="form-field field-full-width">
                    <span class="field-label">Office Address:</span>
                    <span class="field-value">${record.physician_office_address || 'N/A'}</span>
                </div>
            </div>
            ` : ''}

            ${record.medical_history || record.allergies || record.current_medications ? `
            <h3 class="section-title">Medical Information</h3>
            <div class="form-grid mb-4">
                ${record.medical_history ? `
                <div class="form-field field-full-width">
                    <span class="field-label">Medical History:</span>
                    <span class="field-value">${record.medical_history}</span>
                </div>
                ` : ''}
                ${record.allergies ? `
                <div class="form-field field-full-width">
                    <span class="field-label">Allergies:</span>
                    <span class="field-value">${record.allergies}</span>
                </div>
                ` : ''}
                ${record.current_medications ? `
                <div class="form-field field-full-width">
                    <span class="field-label">Current Medications:</span>
                    <span class="field-value">${record.current_medications}</span>
                </div>
                ` : ''}
            </div>
            ` : ''}

            ${record.chief_complaint || record.diagnosis || record.treatment_plan ? `
            <h3 class="section-title">Treatment Information</h3>
            <div class="form-grid mb-4">
                ${record.chief_complaint ? `
                <div class="form-field field-full-width">
                    <span class="field-label">Chief Complaint:</span>
                    <span class="field-value">${record.chief_complaint}</span>
                </div>
                ` : ''}
                ${record.diagnosis ? `
                <div class="form-field field-full-width">
                    <span class="field-label">Diagnosis:</span>
                    <span class="field-value">${record.diagnosis}</span>
                </div>
                ` : ''}
                ${record.treatment_plan ? `
                <div class="form-field field-full-width">
                    <span class="field-label">Treatment Plan:</span>
                    <span class="field-value">${record.treatment_plan}</span>
                </div>
                ` : ''}
            </div>
            ` : ''}

            ${record.other_notes ? `
            <h3 class="section-title">Additional Notes</h3>
            <div class="alert alert-info">
                ${record.other_notes}
            </div>
            ` : ''}
        </div>
    `;

    const previewPanel = document.getElementById('recordPreviewPanel');
    previewPanel.innerHTML = content;

    // Scroll preview panel to top
    previewPanel.scrollTop = 0;
}

// Download record as PDF
function downloadRecord(recordId) {
    // Open print-friendly view in new window
    const printWindow = window.open(`/patient/record/${recordId}/download`, '_blank');

    if (printWindow) {
        showNotification('Opening print-friendly view. Use your browser\'s print function to save as PDF.', 'info');
    } else {
        showNotification('Please allow pop-ups to view the printable record.', 'warning');
    }
}

// Notification function
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';

    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="bi bi-${icon} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// View patient history in preview panel
async function viewHistory(historyId) {
    const previewPanel = document.getElementById('recordPreviewPanel');

    // Show loading state
    previewPanel.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading patient history...</p>
        </div>
    `;

    try {
        const response = await fetch(`/patient/history/${historyId}`);
        const data = await response.json();

        if (data.success) {
            renderPatientHistory(data.history);
        } else {
            showNotification('Failed to load patient history', 'error');
            previewPanel.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Failed to load patient history.
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading history:', error);
        showNotification('Error loading patient history', 'error');
        previewPanel.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error loading patient history.
            </div>
        `;
    }
}

// Render patient history in preview panel
function renderPatientHistory(history) {
    const content = `
        <div class="preview-header mb-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #2196F3; padding-bottom: 1rem;">
            <h2 class="form-preview-title mb-0" style="color: #2196F3; font-weight: 700;">Medical History</h2>
            <button class="btn btn-sm" onclick="downloadHistory(${history.id})" style="background: #2C3E50; color: white; padding: 0.5rem 1.5rem; border-radius: 6px; font-weight: 600;">
                <i class="bi bi-download me-1"></i>DOWNLOAD
            </button>
        </div>
        <div>
            <div class="mb-4">
                <span class="field-label">Visit Date:</span>
                <span class="field-value" style="font-size: 1.1rem; font-weight: 600; color: #2196F3;">${history.visit_date ? new Date(history.visit_date).toLocaleDateString() : 'N/A'}</span>
            </div>

            ${history.previous_dentist || history.last_dental_visit || history.treatment_done ? `
            <h3 class="section-title">Dental History</h3>
            <div class="form-grid mb-4">
                ${history.previous_dentist ? `<div class="form-field"><span class="field-label">Previous Dentist:</span><span class="field-value">${history.previous_dentist}</span></div>` : ''}
                ${history.last_dental_visit ? `<div class="form-field"><span class="field-label">Last Visit:</span><span class="field-value">${new Date(history.last_dental_visit).toLocaleDateString()}</span></div>` : ''}
                ${history.treatment_done ? `<div class="form-field field-full-width"><span class="field-label">Treatment Done:</span><span class="field-value">${history.treatment_done}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.physician_name || history.physician_specialty ? `
            <h3 class="section-title">Medical History</h3>
            <div class="form-grid mb-4">
                ${history.physician_name ? `<div class="form-field"><span class="field-label">Physician:</span><span class="field-value">${history.physician_name}</span></div>` : ''}
                ${history.physician_specialty ? `<div class="form-field"><span class="field-label">Specialty:</span><span class="field-value">${history.physician_specialty}</span></div>` : ''}
                ${history.physician_office_address ? `<div class="form-field field-full-width"><span class="field-label">Office Address:</span><span class="field-value">${history.physician_office_address}</span></div>` : ''}
                ${history.physician_contact ? `<div class="form-field"><span class="field-label">Contact:</span><span class="field-value">${history.physician_contact}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.procedure_performed || history.anesthesia_used ? `
            <h3 class="section-title">Procedure Details</h3>
            <div class="form-grid mb-4">
                ${history.anesthesia_used ? `<div class="form-field"><span class="field-label">Anesthesia:</span><span class="field-value">${history.anesthesia_used}</span></div>` : ''}
                ${history.procedure_performed ? `<div class="form-field field-full-width"><span class="field-label">Procedure:</span><span class="field-value">${history.procedure_performed}</span></div>` : ''}
                ${history.materials_used ? `<div class="form-field field-full-width"><span class="field-label">Materials Used:</span><span class="field-value">${history.materials_used}</span></div>` : ''}
                ${history.complications ? `<div class="form-field field-full-width"><span class="field-label">Complications:</span><span class="field-value">${history.complications}</span></div>` : ''}
                ${history.post_operative_instructions ? `<div class="form-field field-full-width"><span class="field-label">Post-Op Instructions:</span><span class="field-value">${history.post_operative_instructions}</span></div>` : ''}
                ${history.follow_up_notes ? `<div class="form-field field-full-width"><span class="field-label">Follow-up Notes:</span><span class="field-value">${history.follow_up_notes}</span></div>` : ''}
            </div>
            ` : ''}
        </div>
    `;

    const previewPanel = document.getElementById('recordPreviewPanel');
    previewPanel.innerHTML = content;

    // Scroll preview panel to top
    previewPanel.scrollTop = 0;
}

// Download patient history
function downloadHistory(historyId) {
    const printWindow = window.open(`/patient/history/${historyId}/download`, '_blank');

    if (printWindow) {
        showNotification('Opening print-friendly view. Use your browser\'s print function to save as PDF.', 'info');
    } else {
        showNotification('Please allow pop-ups to view the printable history.', 'warning');
    }
}

// View progress note in preview panel
async function viewProgressNote(noteId) {
    const previewPanel = document.getElementById('recordPreviewPanel');

    // Show loading state
    previewPanel.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading progress note...</p>
        </div>
    `;

    try {
        const response = await fetch(`/patient/progress-note/${noteId}`);
        const data = await response.json();

        if (data.success) {
            renderProgressNote(data.note);
        } else {
            showNotification('Failed to load progress note', 'error');
            previewPanel.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Failed to load progress note.
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading note:', error);
        showNotification('Error loading progress note', 'error');
        previewPanel.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error loading progress note.
            </div>
        `;
    }
}

// Render progress note in preview panel
function renderProgressNote(note) {
    const content = `
        <div class="preview-header mb-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #10b981; padding-bottom: 1rem;">
            <h2 class="form-preview-title mb-0" style="color: #10b981; font-weight: 700;">Progress Note</h2>
        </div>
        <div>
            <div class="form-grid mb-4">
                <div class="form-field">
                    <span class="field-label">Date:</span>
                    <span class="field-value" style="font-size: 1.1rem; font-weight: 600; color: #10b981;">${new Date(note.note_date).toLocaleDateString()}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Status:</span>
                    <span class="field-value badge ${note.status === 'completed' ? 'bg-success' : note.status === 'ongoing' ? 'bg-primary' : 'bg-warning'}">${note.status}</span>
    </div>
</div>

            ${note.progress_description ? `
            <h3 class="section-title">Progress Description</h3>
            <div class="alert alert-info mb-4">${note.progress_description}</div>
            ` : ''}

            ${note.treatment_response ? `
            <h3 class="section-title">Treatment Response</h3>
            <div class="alert alert-info mb-4">${note.treatment_response}</div>
            ` : ''}

            ${note.next_steps ? `
            <h3 class="section-title">Next Steps</h3>
            <div class="alert alert-info mb-4">${note.next_steps}</div>
            ` : ''}
        </div>
    `;

    const previewPanel = document.getElementById('recordPreviewPanel');
    previewPanel.innerHTML = content;

    // Scroll preview panel to top
    previewPanel.scrollTop = 0;
}

// Add CSRF token to head if not present
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '{{ csrf_token() }}';
    document.head.appendChild(meta);
}
</script>

@endsection
