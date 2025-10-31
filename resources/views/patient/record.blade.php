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

/* ============================================
   DARK MODE STYLES FOR PATIENT RECORDS PAGE
   ============================================ */

/* Page Header Box Dark Mode */
[data-theme="dark"] .page-header-box {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .page-header-box h1 {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Records Table Section Dark Mode */
[data-theme="dark"] .records-table-section {
    background: var(--dm-card-bg, #1e293b) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
}

/* Sort Control Dark Mode */
[data-theme="dark"] div[style*="background: #f8fafc"] {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] label[style*="color: #475569"] {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-select {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: #14b8a6 !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-select:focus {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: #14b8a6 !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Table Header Dark Mode */
[data-theme="dark"] .records-table thead {
    background: #14b8a6 !important;
}

[data-theme="dark"] .records-table thead th {
    color: white !important;
}

/* Table Body Dark Mode */
[data-theme="dark"] .records-table tbody tr {
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .records-table tbody tr:nth-child(even) {
    background: var(--dm-bg-primary, #0f172a) !important;
}

[data-theme="dark"] .records-table tbody tr:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .records-table tbody td {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-name {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-date {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Empty State Dark Mode */
[data-theme="dark"] .text-center[style*="color: #64748b"] {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .text-center i[style*="font-size: 3rem"] {
    opacity: 0.5 !important;
    color: var(--dm-text-muted, #64748b) !important;
}

/* Form Preview Section Dark Mode */
[data-theme="dark"] .form-preview-section {
    background: var(--dm-card-bg, #1e293b) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .form-preview-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-bottom-color: #3b82f6 !important;
}

/* Preview Header Dark Mode */
[data-theme="dark"] .preview-header {
    border-bottom-color: #3b82f6 !important;
}

/* Section Title Dark Mode */
[data-theme="dark"] .section-title {
    background: var(--dm-bg-secondary, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-left-color: #3b82f6 !important;
}

/* Form Fields Dark Mode */
[data-theme="dark"] .field-label {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .field-value {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Checkbox Items Dark Mode */
[data-theme="dark"] .checkbox-item {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Empty Preview Panel Dark Mode */
[data-theme="dark"] .form-preview-section .text-center.text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .form-preview-section .text-center i {
    opacity: 0.3 !important;
    color: var(--dm-text-muted, #64748b) !important;
}

/* Alert Dark Mode */
[data-theme="dark"] .alert {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .alert-info {
    background: rgba(59, 130, 246, 0.15) !important;
    border-color: #3b82f6 !important;
    color: #93c5fd !important;
}

[data-theme="dark"] .alert-danger {
    background: rgba(239, 68, 68, 0.15) !important;
    border-color: #ef4444 !important;
    color: #fca5a5 !important;
}

/* Inline Styles Dark Mode Overrides */
[data-theme="dark"] div[style*="border-bottom: 2px solid #2196F3"] {
    border-bottom-color: #3b82f6 !important;
}

[data-theme="dark"] h2[style*="color: #2196F3"] {
    color: #60a5fa !important;
}

[data-theme="dark"] span[style*="color: #2196F3"] {
    color: #60a5fa !important;
}

[data-theme="dark"] div[style*="border-bottom: 2px solid #10b981"] {
    border-bottom-color: #14b8a6 !important;
}

[data-theme="dark"] h2[style*="color: #10b981"] {
    color: #14b8a6 !important;
}

[data-theme="dark"] span[style*="color: #10b981"] {
    color: #14b8a6 !important;
}
</style>

<div class="records-container">
    <div class="page-header-box">
        <h1>PATIENT RECORDS</h1>
    </div>

    <div class="records-layout">
        <!-- Records Table -->
        <div class="records-table-section">
            <!-- Sort Control -->
            <div style="padding: 1rem 1.5rem; border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
                <div class="d-flex align-items-center gap-2">
                    <label for="recordSortBy" class="mb-0 fw-semibold" style="color: #475569; font-size: 0.9rem;">
                        <i class="bi bi-sort-down me-1"></i>Sort by:
                    </label>
                    <select class="form-select form-select-sm" id="recordSortBy" style="width: auto; border: 2px solid #26a69a;">
                        <option value="date_desc" selected>Newest First</option>
                        <option value="date_asc">Oldest First</option>
                        <option value="form_asc">Form Type (A-Z)</option>
                        <option value="form_desc">Form Type (Z-A)</option>
                    </select>
                </div>
            </div>

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
                        <tr>
                            <td class="form-name">
                                Medical History
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
                        <tr>
                            <td class="form-name">
                                Progress Note - {{ \Carbon\Carbon::parse($note->note_date)->format('M d, Y') }}
                            </td>
                            <td class="form-date">{{ \Carbon\Carbon::parse($note->created_at)->format('m/d/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewProgressNote({{ $note->id }})">VIEW</button>
                                    <button class="btn-download" onclick="downloadProgressNote({{ $note->id }})">DOWNLOAD</button>
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
        <div class="preview-header mb-3" style="border-bottom: 2px solid #2196F3; padding-bottom: 1rem;">
            <h2 class="form-preview-title mb-0">Patient Medical Record</h2>
        </div>
        <div class="record-detail-view">
            <h3 class="section-title">Patient Information</h3>
            <div class="form-grid mb-4">
                <div class="form-field">
                    <span class="field-label">Patient Name:</span>
                    <span class="field-value">${record.user?.info ? (record.user.info.first_name + ' ' + record.user.info.last_name) : 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Sex:</span>
                    <span class="field-value">${record.sex || 'N/A'}</span>
                </div>
                <div class="form-field">
                    <span class="field-label">Date of Birth:</span>
                    <span class="field-value">${record.date_of_birth ? new Date(record.date_of_birth).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'}) : 'N/A'}</span>
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
        <div class="preview-header mb-3" style="border-bottom: 2px solid #2196F3; padding-bottom: 1rem;">
            <h2 class="form-preview-title mb-0" style="color: #2196F3; font-weight: 700;">Medical History</h2>
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

            ${history.physician_name || history.physician_specialty || history.physician_office_address || history.physician_contact ? `
            <h3 class="section-title">Physician Information</h3>
            <div class="form-grid mb-4">
                ${history.physician_name ? `<div class="form-field"><span class="field-label">Physician:</span><span class="field-value">${history.physician_name}</span></div>` : ''}
                ${history.physician_specialty ? `<div class="form-field"><span class="field-label">Specialty:</span><span class="field-value">${history.physician_specialty}</span></div>` : ''}
                ${history.physician_office_address ? `<div class="form-field field-full-width"><span class="field-label">Office Address:</span><span class="field-value">${history.physician_office_address}</span></div>` : ''}
                ${history.physician_contact ? `<div class="form-field"><span class="field-label">Contact:</span><span class="field-value">${history.physician_contact}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.good_health || history.under_treatment || history.serious_illness || history.been_hospitalized || history.taking_drugs || history.tobacco_use || history.alcohol_use || history.recreational_drugs ? `
            <h3 class="section-title">Health Questions</h3>
            <div class="form-grid mb-4">
                ${history.good_health ? `<div class="form-field"><span class="field-label">Good Health:</span><span class="field-value">${history.good_health}</span></div>` : ''}
                ${history.under_treatment ? `<div class="form-field"><span class="field-label">Under Treatment:</span><span class="field-value">${history.under_treatment}</span></div>` : ''}
                ${history.treatment_condition ? `<div class="form-field field-full-width"><span class="field-label">Condition:</span><span class="field-value">${history.treatment_condition}</span></div>` : ''}
                ${history.serious_illness ? `<div class="form-field"><span class="field-label">Serious Illness:</span><span class="field-value">${history.serious_illness}</span></div>` : ''}
                ${history.illness_details ? `<div class="form-field field-full-width"><span class="field-label">Illness Details:</span><span class="field-value">${history.illness_details}</span></div>` : ''}
                ${history.been_hospitalized ? `<div class="form-field"><span class="field-label">Been Hospitalized:</span><span class="field-value">${history.been_hospitalized}</span></div>` : ''}
                ${history.hospitalization_reason ? `<div class="form-field field-full-width"><span class="field-label">Reason:</span><span class="field-value">${history.hospitalization_reason}</span></div>` : ''}
                ${history.taking_drugs ? `<div class="form-field"><span class="field-label">Taking Medications:</span><span class="field-value">${history.taking_drugs}</span></div>` : ''}
                ${history.medications ? `<div class="form-field field-full-width"><span class="field-label">Medications:</span><span class="field-value">${history.medications}</span></div>` : ''}
                ${history.tobacco_use ? `<div class="form-field"><span class="field-label">Tobacco Use:</span><span class="field-value">${history.tobacco_use}</span></div>` : ''}
                ${history.alcohol_use ? `<div class="form-field"><span class="field-label">Alcohol Use:</span><span class="field-value">${history.alcohol_use}</span></div>` : ''}
                ${history.recreational_drugs ? `<div class="form-field"><span class="field-label">Recreational Drugs:</span><span class="field-value">${history.recreational_drugs}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.allergy_anesthesia || history.allergy_sulfa || history.allergy_antibiotics || history.allergy_aspirin || history.allergy_analgesics || history.allergy_latex || history.food_allergy_details || history.other_allergy_details ? `
            <h3 class="section-title">Allergies</h3>
            <div class="form-grid mb-4">
                ${history.allergy_anesthesia ? `<div class="form-field"><span class="field-label">Local Anesthesia:</span><span class="field-value">Yes</span></div>` : ''}
                ${history.allergy_sulfa ? `<div class="form-field"><span class="field-label">Sulfa Drugs:</span><span class="field-value">Yes</span></div>` : ''}
                ${history.allergy_antibiotics ? `<div class="form-field"><span class="field-label">Antibiotics:</span><span class="field-value">Yes</span></div>` : ''}
                ${history.allergy_aspirin ? `<div class="form-field"><span class="field-label">Aspirin:</span><span class="field-value">Yes</span></div>` : ''}
                ${history.allergy_analgesics ? `<div class="form-field"><span class="field-label">Analgesics:</span><span class="field-value">Yes</span></div>` : ''}
                ${history.allergy_latex ? `<div class="form-field"><span class="field-label">Latex:</span><span class="field-value">Yes</span></div>` : ''}
                ${history.food_allergy_details ? `<div class="form-field field-full-width"><span class="field-label">Food Allergies:</span><span class="field-value">${history.food_allergy_details}</span></div>` : ''}
                ${history.other_allergy_details ? `<div class="form-field field-full-width"><span class="field-label">Other Allergies:</span><span class="field-value">${history.other_allergy_details}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.is_pregnant || history.is_nursing || history.birth_control ? `
            <h3 class="section-title">For Women</h3>
            <div class="form-grid mb-4">
                ${history.is_pregnant ? `<div class="form-field"><span class="field-label">Pregnant:</span><span class="field-value">${history.is_pregnant}</span></div>` : ''}
                ${history.is_nursing ? `<div class="form-field"><span class="field-label">Nursing:</span><span class="field-value">${history.is_nursing}</span></div>` : ''}
                ${history.birth_control ? `<div class="form-field"><span class="field-label">Taking Birth Control:</span><span class="field-value">${history.birth_control}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.procedure_performed || history.anesthesia_used || history.materials_used || history.complications || history.post_operative_instructions || history.follow_up_notes ? `
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

function downloadProgressNote(noteId) {
    const printWindow = window.open(`/patient/progress-note/${noteId}/download`, '_blank');

    if (printWindow) {
        showNotification('Opening print-friendly view. Use your browser\'s print function to save as PDF.', 'info');
    } else {
        showNotification('Please allow pop-ups to view the printable progress note.', 'warning');
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

// Sort functionality
document.getElementById('recordSortBy')?.addEventListener('change', function() {
    const sortBy = this.value;
    const tbody = document.getElementById('recordsTableBody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        let aValue, bValue;

        switch(sortBy) {
            case 'date_desc':
            case 'date_asc':
                // Get date from the 2nd column (DATE)
                const aDate = a.cells[1]?.textContent.trim() || '';
                const bDate = b.cells[1]?.textContent.trim() || '';
                const comparison = new Date(aDate) - new Date(bDate);
                return sortBy === 'date_desc' ? -comparison : comparison;

            case 'form_asc':
            case 'form_desc':
                // Get form type from the 1st column (FORM)
                aValue = a.cells[0]?.textContent.trim() || '';
                bValue = b.cells[0]?.textContent.trim() || '';
                const formComp = aValue.localeCompare(bValue);
                return sortBy === 'form_asc' ? formComp : -formComp;

            default:
                return 0;
        }
    });

    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
});
</script>

@endsection
