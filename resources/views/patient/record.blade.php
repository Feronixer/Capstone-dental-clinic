@extends('layout.patient.app')
@section('content')

<style>
/* ============================================
   ENHANCED PATIENT RECORDS STYLES
   Blue Accent Theme: #0d6efd
   ============================================ */

.records-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 1rem 1.5rem;
}

/* Enhanced Page Header */
.page-header-box {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    border-radius: 10px;
    padding: 0.875rem 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 2px 12px rgba(13, 110, 253, 0.2);
}

.page-header-box h1 {
    font-size: clamp(1.25rem, 2.5vw, 1.5rem);
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-header-box h1::before {
    content: '';
    width: 3px;
    height: 1.5rem;
    background: #ffffff;
    border-radius: 2px;
}

.page-header-box h1 i {
    font-size: 1.25rem;
}

.records-layout {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 1.25rem;
    align-items: start;
}

/* Enhanced Records Table Section */
.records-table-section {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.records-table-section:hover {
    box-shadow: 0 6px 24px rgba(13, 110, 253, 0.12);
}

/* Enhanced Sort Control */
.sort-control-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.sort-control-wrapper {
    display: flex;
    align-items: center;
    gap: 0.875rem;
}

.sort-control-wrapper label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    white-space: nowrap;
}

.sort-control-wrapper label i {
    color: #0d6efd;
    font-size: 1rem;
    flex-shrink: 0;
}

.sort-control-wrapper .form-select {
    border: 1.5px solid #0d6efd;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: #495057;
    background: #ffffff;
    transition: all 0.2s ease;
    min-width: 180px;
}

.sort-control-wrapper .form-select:focus {
    border-color: #0a58ca;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    outline: none;
}

/* Enhanced Records Table */
.records-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.records-table thead {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.records-table thead th {
    color: #ffffff;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.75rem 1rem;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.records-table thead th:first-child {
    border-top-left-radius: 0;
}

.records-table tbody tr {
    border-bottom: 1px solid #f1f3f5;
    transition: all 0.2s ease;
}

.records-table tbody tr:hover {
    background-color: #f8f9fa;
    box-shadow: 0 1px 3px rgba(13, 110, 253, 0.1);
}

.records-table tbody tr:nth-child(even) {
    background: #ffffff;
}

.records-table tbody tr:nth-child(odd) {
    background: #f8f9fa;
}

.records-table tbody td {
    padding: 0.75rem 1rem;
    color: #495057;
    font-size: 0.85rem;
    vertical-align: middle;
}

.form-name {
    font-weight: 600;
    color: #212529;
    font-size: 0.875rem;
}

.form-date {
    color: #64748b;
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 0.375rem;
    flex-wrap: nowrap;
}

.btn-view, .btn-download {
    padding: 0.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    width: 36px;
    height: 36px;
    min-width: 36px;
    position: relative;
}

.btn-view {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
}

.btn-view:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.btn-view:active {
    transform: translateY(0);
}

.btn-download {
    background: linear-gradient(135deg, #495057 0%, #343a40 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(73, 80, 87, 0.2);
}

.btn-download:hover {
    background: linear-gradient(135deg, #343a40 0%, #212529 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(73, 80, 87, 0.3);
}

.btn-download:active {
    transform: translateY(0);
}

.btn-view i, .btn-download i {
    font-size: 1rem;
    line-height: 1;
}

.btn-view:hover::after, .btn-download:hover::after {
    content: attr(title);
    position: absolute;
    bottom: -2.5rem;
    left: 50%;
    transform: translateX(-50%);
    background: #212529;
    color: #ffffff;
    padding: 0.375rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    z-index: 1000;
    pointer-events: none;
    font-weight: 500;
}

.btn-view:hover::before, .btn-download:hover::before {
    content: '';
    position: absolute;
    bottom: -0.375rem;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-bottom-color: #212529;
    z-index: 1001;
    pointer-events: none;
}

/* Enhanced Form Preview Section */
.form-preview-section {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1.25rem;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
    border: 1px solid #e9ecef;
    position: sticky;
    top: 1rem;
    transition: all 0.3s ease;
}

.form-preview-section:hover {
    box-shadow: 0 6px 24px rgba(13, 110, 253, 0.12);
}

/* Enhanced Empty State */
.empty-preview-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    text-align: center;
    padding: 2rem 1.5rem;
}

.empty-preview-state i {
    font-size: 3rem;
    color: #adb5bd;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-preview-state p {
    font-size: 0.95rem;
    color: #64748b;
    margin: 0.375rem 0;
    font-weight: 500;
}

.empty-preview-state .small {
    font-size: 0.85rem;
    color: #adb5bd;
}

.form-preview-title {
    font-size: clamp(1.1rem, 2vw, 1.25rem);
    font-weight: 700;
    color: #212529;
    margin: 0 0 1rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #0d6efd;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-preview-title::before {
    content: '';
    width: 3px;
    height: 1.25rem;
    background: #0d6efd;
    border-radius: 2px;
}

.form-section {
    margin-bottom: 1.25rem;
    padding: 0.75rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-section:hover {
    box-shadow: 0 2px 6px rgba(13, 110, 253, 0.1);
    border-color: #dee2e6;
}

.section-title {
    background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
    padding: 0.625rem 1rem;
    font-weight: 600;
    color: #212529;
    font-size: 0.9rem;
    margin: 0 0 0.875rem 0;
    border-left: 3px solid #0d6efd;
    border-radius: 5px;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    padding: 0.5rem;
    background: #ffffff;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.form-field:hover {
    border-color: #0d6efd;
    box-shadow: 0 1px 3px rgba(13, 110, 253, 0.1);
}

.field-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    margin-bottom: 0.125rem;
}

.field-label::before {
    content: '';
    width: 2px;
    height: 0.75rem;
    background: #0d6efd;
    border-radius: 1px;
}

.field-value {
    font-size: 0.85rem;
    color: #212529;
    padding: 0.25rem 0;
    font-weight: 500;
    line-height: 1.5;
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

/* Enhanced Empty State for Records Table */
.empty-records-state {
    padding: 2rem 1.5rem;
    text-align: center;
}

.empty-records-state i {
    font-size: 2.5rem;
    color: #adb5bd;
    margin-bottom: 0.75rem;
    opacity: 0.5;
    display: block;
}

.empty-records-state p {
    color: #64748b;
    font-size: 0.9rem;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .records-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .form-preview-section {
        position: relative;
        top: 0;
        max-height: none;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .checkbox-grid {
        grid-template-columns: 1fr;
    }

    .sort-control-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
    }
    
    .sort-control-wrapper {
        width: 100%;
        gap: 0.75rem;
    }
}

@media (max-width: 768px) {
    .records-container {
        padding: 0.75rem 1rem;
    }

    .page-header-box {
        padding: 0.75rem 1.25rem;
        width: 100%;
        justify-content: center;
    }

    .records-table thead th,
    .records-table tbody td {
        padding: 0.625rem 0.75rem;
        font-size: 0.8rem;
    }

    .action-buttons {
        flex-direction: row;
        width: 100%;
    }

    .btn-view, .btn-download {
        width: 36px;
        height: 36px;
        padding: 0.5rem;
        justify-content: center;
    }

    .form-preview-section {
        padding: 1rem;
    }

    .form-section {
        padding: 0.625rem;
    }

    .form-field {
        padding: 0.5rem;
    }
}

/* ============================================
   DARK MODE STYLES FOR PATIENT RECORDS PAGE
   ============================================ */

/* Page Header Box Dark Mode */
[data-theme="dark"] .page-header-box {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    border: none !important;
}

[data-theme="dark"] .page-header-box h1 {
    color: #ffffff !important;
}

[data-theme="dark"] .page-header-box h1::before {
    background: #ffffff !important;
}

/* Records Table Section Dark Mode */
[data-theme="dark"] .records-table-section {
    background: var(--dm-card-bg, #1e293b) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
}

[data-theme="dark"] .records-table-section:hover {
    box-shadow: 0 6px 24px rgba(37, 99, 235, 0.2) !important;
}

/* Sort Control Dark Mode */
[data-theme="dark"] .sort-control-section {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .sort-control-wrapper label {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .sort-control-wrapper label i {
    color: #60a5fa !important;
}

[data-theme="dark"] .sort-control-wrapper .form-select {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: #60a5fa !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .sort-control-wrapper .form-select:focus {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: #60a5fa !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.25) !important;
}

/* Table Header Dark Mode */
[data-theme="dark"] .records-table thead {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .records-table thead th {
    color: #ffffff !important;
    border-bottom-color: rgba(255, 255, 255, 0.2) !important;
}

/* Table Body Dark Mode */
[data-theme="dark"] .records-table tbody tr {
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .records-table tbody tr:nth-child(even) {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .records-table tbody tr:nth-child(odd) {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .records-table tbody tr:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
    box-shadow: 0 2px 4px rgba(96, 165, 250, 0.15) !important;
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

/* Button Dark Mode */
[data-theme="dark"] .btn-view {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3) !important;
}

[data-theme="dark"] .btn-view:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4) !important;
}

[data-theme="dark"] .btn-download {
    background: linear-gradient(135deg, #495057 0%, #343a40 100%) !important;
}

[data-theme="dark"] .btn-download:hover {
    background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
}

/* Empty State Dark Mode */
[data-theme="dark"] .empty-records-state {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .empty-records-state i {
    color: var(--dm-text-muted, #94a3b8) !important;
    opacity: 0.5 !important;
}

[data-theme="dark"] .empty-records-state p {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .empty-preview-state i {
    color: var(--dm-text-muted, #94a3b8) !important;
    opacity: 0.4 !important;
}

[data-theme="dark"] .empty-preview-state p {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .empty-preview-state .small {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Form Preview Section Dark Mode */
[data-theme="dark"] .form-preview-section {
    background: var(--dm-card-bg, #1e293b) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
}

[data-theme="dark"] .form-preview-section:hover {
    box-shadow: 0 6px 24px rgba(37, 99, 235, 0.2) !important;
}

[data-theme="dark"] .form-preview-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-bottom-color: #60a5fa !important;
}

[data-theme="dark"] .form-preview-title::before {
    background: #60a5fa !important;
}

/* Preview Header Dark Mode */
[data-theme="dark"] .preview-header {
    border-bottom-color: #60a5fa !important;
}

/* Section Title Dark Mode */
[data-theme="dark"] .section-title {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-left-color: #60a5fa !important;
}

/* Form Section Dark Mode */
[data-theme="dark"] .form-section {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .form-section:hover {
    border-color: #60a5fa !important;
    box-shadow: 0 2px 8px rgba(96, 165, 250, 0.15) !important;
}

/* Form Fields Dark Mode */
[data-theme="dark"] .form-field {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .form-field:hover {
    border-color: #60a5fa !important;
    box-shadow: 0 2px 4px rgba(96, 165, 250, 0.1) !important;
}

[data-theme="dark"] .field-label {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .field-label::before {
    background: #60a5fa !important;
}

[data-theme="dark"] .field-value {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Checkbox Items Dark Mode */
[data-theme="dark"] .checkbox-item {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Alert Dark Mode */
[data-theme="dark"] .alert {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .alert-info {
    background: rgba(37, 99, 235, 0.15) !important;
    border-color: #60a5fa !important;
    color: #93c5fd !important;
}

[data-theme="dark"] .alert-danger {
    background: rgba(239, 68, 68, 0.15) !important;
    border-color: #ef4444 !important;
    color: #fca5a5 !important;
}

/* Loading Spinner Dark Mode */
[data-theme="dark"] .spinner-border.text-primary {
    color: #60a5fa !important;
}

[data-theme="dark"] .text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Inline Styles Dark Mode Overrides */
[data-theme="dark"] div[style*="border-bottom: 2px solid #2196F3"],
[data-theme="dark"] div[style*="border-bottom: 2px solid #0d6efd"] {
    border-bottom-color: #60a5fa !important;
}

[data-theme="dark"] h2[style*="color: #2196F3"],
[data-theme="dark"] h2[style*="color: #0d6efd"] {
    color: #60a5fa !important;
}

[data-theme="dark"] span[style*="color: #2196F3"],
[data-theme="dark"] span[style*="color: #0d6efd"] {
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
        <h1>
            <i class="bi bi-file-earmark-medical"></i>
            PATIENT RECORDS
        </h1>
    </div>

    <div class="records-layout">
        <!-- Records Table -->
        <div class="records-table-section">
            <!-- Enhanced Sort Control -->
            <div class="sort-control-section">
                <div class="sort-control-wrapper">
                    <label for="recordSortBy">
                        <i class="bi bi-funnel"></i>Sort by:
                    </label>
                    <select class="form-select form-select-sm" id="recordSortBy">
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
                                <button class="btn-view" onclick="viewRecord({{ $record->id }})" title="View Record">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn-download" onclick="downloadRecord({{ $record->id }})" title="Download Record">
                                    <i class="bi bi-download"></i>
                                </button>
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
                                    <button class="btn-view" onclick="viewHistory({{ $history->id }})" title="View History">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn-download" onclick="downloadHistory({{ $history->id }})" title="Download History">
                                        <i class="bi bi-download"></i>
                                    </button>
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
                                    <button class="btn-view" onclick="viewProgressNote({{ $note->id }})" title="View Progress Note">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn-download" onclick="downloadProgressNote({{ $note->id }})" title="Download Progress Note">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    @empty
                    <tr>
                        <td colspan="3" class="empty-records-state">
                            <i class="bi bi-inbox"></i>
                            <p>No medical records found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
                        </div>

        <!-- Enhanced Form Preview -->
        <div class="form-preview-section" id="recordPreviewPanel">
            <div class="empty-preview-state">
                <i class="bi bi-file-earmark-medical"></i>
                <p>Select a record to view details</p>
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
        <div class="preview-header mb-2" style="border-bottom: 2px solid #0d6efd; padding-bottom: 0.75rem;">
            <h2 class="form-preview-title mb-0">Patient Medical Record</h2>
        </div>
        <div class="record-detail-view">
            <h3 class="section-title">Patient Information</h3>
            <div class="form-grid mb-3">
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
            <div class="form-grid mb-3">
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
            <div class="form-grid mb-3">
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
            <div class="form-grid mb-3">
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
            <div class="form-grid mb-3">
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
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted" style="font-size: 0.875rem;">Loading patient history...</p>
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
        <div class="preview-header mb-2" style="border-bottom: 2px solid #0d6efd; padding-bottom: 0.75rem;">
            <h2 class="form-preview-title mb-0" style="color: #0d6efd; font-weight: 700;">Medical History</h2>
        </div>
        <div>
            <div class="mb-3">
                <span class="field-label">Visit Date:</span>
                <span class="field-value" style="font-size: 1rem; font-weight: 600; color: #0d6efd;">${history.visit_date ? new Date(history.visit_date).toLocaleDateString() : 'N/A'}</span>
            </div>

            ${history.previous_dentist || history.last_dental_visit || history.treatment_done ? `
            <h3 class="section-title">Dental History</h3>
            <div class="form-grid mb-3">
                ${history.previous_dentist ? `<div class="form-field"><span class="field-label">Previous Dentist:</span><span class="field-value">${history.previous_dentist}</span></div>` : ''}
                ${history.last_dental_visit ? `<div class="form-field"><span class="field-label">Last Visit:</span><span class="field-value">${new Date(history.last_dental_visit).toLocaleDateString()}</span></div>` : ''}
                ${history.treatment_done ? `<div class="form-field field-full-width"><span class="field-label">Treatment Done:</span><span class="field-value">${history.treatment_done}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.physician_name || history.physician_specialty || history.physician_office_address || history.physician_contact ? `
            <h3 class="section-title">Physician Information</h3>
            <div class="form-grid mb-3">
                ${history.physician_name ? `<div class="form-field"><span class="field-label">Physician:</span><span class="field-value">${history.physician_name}</span></div>` : ''}
                ${history.physician_specialty ? `<div class="form-field"><span class="field-label">Specialty:</span><span class="field-value">${history.physician_specialty}</span></div>` : ''}
                ${history.physician_office_address ? `<div class="form-field field-full-width"><span class="field-label">Office Address:</span><span class="field-value">${history.physician_office_address}</span></div>` : ''}
                ${history.physician_contact ? `<div class="form-field"><span class="field-label">Contact:</span><span class="field-value">${history.physician_contact}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.good_health || history.under_treatment || history.serious_illness || history.been_hospitalized || history.taking_drugs || history.tobacco_use || history.alcohol_use || history.recreational_drugs ? `
            <h3 class="section-title">Health Questions</h3>
            <div class="form-grid mb-3">
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
            <div class="form-grid mb-3">
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
            <div class="form-grid mb-3">
                ${history.is_pregnant ? `<div class="form-field"><span class="field-label">Pregnant:</span><span class="field-value">${history.is_pregnant}</span></div>` : ''}
                ${history.is_nursing ? `<div class="form-field"><span class="field-label">Nursing:</span><span class="field-value">${history.is_nursing}</span></div>` : ''}
                ${history.birth_control ? `<div class="form-field"><span class="field-label">Taking Birth Control:</span><span class="field-value">${history.birth_control}</span></div>` : ''}
            </div>
            ` : ''}

            ${history.procedure_performed || history.anesthesia_used || history.materials_used || history.complications || history.post_operative_instructions || history.follow_up_notes ? `
            <h3 class="section-title">Procedure Details</h3>
            <div class="form-grid mb-3">
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
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted" style="font-size: 0.875rem;">Loading progress note...</p>
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
        <div class="preview-header mb-2 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #10b981; padding-bottom: 0.75rem;">
            <h2 class="form-preview-title mb-0" style="color: #10b981; font-weight: 700;">Progress Note</h2>
        </div>
        <div>
            <div class="form-grid mb-3">
                <div class="form-field">
                    <span class="field-label">Date:</span>
                    <span class="field-value" style="font-size: 1rem; font-weight: 600; color: #10b981;">${new Date(note.note_date).toLocaleDateString()}</span>
                </div>
            </div>

            ${note.progress_description ? `
            <h3 class="section-title">Progress Description</h3>
            <div class="alert alert-info mb-3" style="padding: 0.75rem; font-size: 0.875rem;">${note.progress_description}</div>
            ` : ''}

            ${note.treatment_response ? `
            <h3 class="section-title">Treatment Response</h3>
            <div class="alert alert-info mb-3" style="padding: 0.75rem; font-size: 0.875rem;">${note.treatment_response}</div>
            ` : ''}

            ${note.next_steps ? `
            <h3 class="section-title">Next Steps</h3>
            <div class="alert alert-info mb-3" style="padding: 0.75rem; font-size: 0.875rem;">${note.next_steps}</div>
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
    if (!tbody) return;
    
    const rows = Array.from(tbody.querySelectorAll('tr'));
    if (rows.length === 0) return;

    // Helper function to parse date in MM/DD/YYYY format
    function parseDate(dateStr) {
        if (!dateStr) return new Date(0);
        const parts = dateStr.trim().split('/');
        if (parts.length !== 3) return new Date(0);
        // Format: MM/DD/YYYY
        const month = parseInt(parts[0], 10) - 1; // Month is 0-indexed
        const day = parseInt(parts[1], 10);
        const year = parseInt(parts[2], 10);
        return new Date(year, month, day);
    }

    rows.sort((a, b) => {
        let comparison = 0;

        switch(sortBy) {
            case 'date_desc':
            case 'date_asc':
                // Get date from the 2nd column (DATE) - index 1
                const aDateStr = a.cells[1]?.textContent.trim() || '';
                const bDateStr = b.cells[1]?.textContent.trim() || '';
                const aDate = parseDate(aDateStr);
                const bDate = parseDate(bDateStr);
                comparison = aDate.getTime() - bDate.getTime();
                return sortBy === 'date_desc' ? -comparison : comparison;

            case 'form_asc':
            case 'form_desc':
                // Get form type from the 1st column (FORM) - index 0
                const aForm = a.cells[0]?.textContent.trim() || '';
                const bForm = b.cells[0]?.textContent.trim() || '';
                comparison = aForm.localeCompare(bForm, undefined, { sensitivity: 'base' });
                return sortBy === 'form_asc' ? comparison : -comparison;

            default:
                return 0;
        }
    });

    // Clear tbody and re-append sorted rows
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
});
</script>

@endsection
