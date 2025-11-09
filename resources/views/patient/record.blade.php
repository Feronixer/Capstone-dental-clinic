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
    overflow: visible;
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
    position: relative;
    overflow: visible;
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
    position: relative;
    overflow: visible;
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
    position: relative;
    overflow: visible;
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
    position: relative;
    z-index: 1;
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
    z-index: 1;
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
    top: -2.5rem;
    left: 50%;
    transform: translateX(-50%);
    background: #212529;
    color: #ffffff;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    z-index: 99999;
    pointer-events: none;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    min-width: max-content;
    max-width: none;
    width: auto;
}

.btn-view:hover::before, .btn-download:hover::before {
    content: '';
    position: absolute;
    top: -0.375rem;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: #212529;
    z-index: 100000;
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
    position: relative;
}

/* Preview Mask for Security */
.preview-mask {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    transition: opacity 0.3s ease;
    opacity: 1;
    pointer-events: auto;
}

.preview-mask.hidden {
    opacity: 0;
    pointer-events: none;
    z-index: -1;
}

.mask-content {
    text-align: center;
    padding: 2rem;
}

.mask-content i {
    font-size: 3rem;
    color: #0d6efd;
    margin-bottom: 1rem;
}

.mask-content p {
    font-size: 1rem;
    color: #495057;
    font-weight: 500;
    margin: 0;
}

/* Locked Button Styles */
.btn-view.locked, .btn-download.locked {
    opacity: 0.5;
    cursor: pointer;
    pointer-events: auto;
    position: relative;
}

/* Password Modal Styles */
.password-modal {
    z-index: 10001 !important;
}

.password-modal .modal-backdrop {
    z-index: 10000 !important;
    background-color: rgba(0, 0, 0, 0.5);
}

.password-modal .modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    z-index: 10001 !important;
    position: relative;
}

.password-modal .modal-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    border-radius: 12px 12px 0 0;
    padding: 1.25rem 1.5rem;
}

.password-modal .modal-header .modal-title {
    color: #ffffff;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.password-modal .modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.password-modal .modal-body {
    padding: 2rem;
}

.password-modal .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.password-modal .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.password-modal .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    outline: none;
}

.password-modal .modal-footer {
    border: none;
    padding: 1.25rem 1.5rem;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
}

.password-modal .btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.password-modal .btn-primary:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.password-modal .btn-secondary {
    border: 2px solid #6c757d;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.password-modal .btn-secondary:hover {
    background: #6c757d;
    color: #ffffff;
    transform: translateY(-2px);
}

.password-error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: none;
}

.password-error.show {
    display: block;
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

/* Preview Mask Dark Mode */
[data-theme="dark"] .preview-mask {
    background: rgba(30, 41, 59, 0.95) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

[data-theme="dark"] .mask-content i {
    color: #60a5fa !important;
}

[data-theme="dark"] .mask-content p {
    color: var(--dm-text-primary, #f1f5f9) !important;
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

/* Progress Notes Table Dark Mode */
[data-theme="dark"] .progress-notes-table-container {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .progress-notes-table {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .progress-notes-thead {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    color: #ffffff !important;
}

[data-theme="dark"] .progress-notes-thead th {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
}

[data-theme="dark"] .progress-notes-tbody {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .progress-notes-row {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .progress-notes-row:nth-child(even) {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .progress-notes-row:nth-child(odd) {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .progress-notes-row:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
    box-shadow: 0 2px 4px rgba(96, 165, 250, 0.15) !important;
}

[data-theme="dark"] .progress-notes-cell {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-color: var(--dm-border-color, #334155) !important;
    background: inherit !important;
}

[data-theme="dark"] .preview-header {
    border-bottom-color: #60a5fa !important;
}

[data-theme="dark"] .preview-header h2 {
    color: #60a5fa !important;
}

[data-theme="dark"] .preview-header .text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .progress-notes-info {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .progress-notes-info .text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .progress-notes-info strong {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Override inline styles for dark mode */
[data-theme="dark"] .progress-notes-table-container table {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .progress-notes-table-container table thead {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .progress-notes-table-container table thead th {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
}

[data-theme="dark"] .progress-notes-table-container table tbody {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .progress-notes-table-container table tbody tr {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .progress-notes-table-container table tbody tr:nth-child(even) {
    background: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .progress-notes-table-container table tbody tr:nth-child(odd) {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .progress-notes-table-container table tbody tr:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .progress-notes-table-container table tbody td {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-color: var(--dm-border-color, #334155) !important;
    background: inherit !important;
}

[data-theme="dark"] .progress-notes-title {
    color: #60a5fa !important;
}

[data-theme="dark"] div[style*="border-bottom: 2px solid #0d6efd"] {
    border-bottom-color: #60a5fa !important;
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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .alert-info {
    background: rgba(37, 99, 235, 0.2) !important;
    border-color: #60a5fa !important;
    color: #93c5fd !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;
}

[data-theme="dark"] .alert-danger {
    background: rgba(239, 68, 68, 0.2) !important;
    border-color: #ef4444 !important;
    color: #fca5a5 !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
}

[data-theme="dark"] .alert-success {
    background: rgba(34, 197, 94, 0.2) !important;
    border-color: #4ade80 !important;
    color: #86efac !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2) !important;
}

[data-theme="dark"] .alert-warning {
    background: rgba(234, 179, 8, 0.2) !important;
    border-color: #fbbf24 !important;
    color: #fde047 !important;
    box-shadow: 0 4px 12px rgba(234, 179, 8, 0.2) !important;
}

/* Alert Close Button Dark Mode */
[data-theme="dark"] .alert .btn-close {
    filter: brightness(0) invert(1) !important;
    opacity: 0.8 !important;
}

[data-theme="dark"] .alert .btn-close:hover {
    opacity: 1 !important;
}

/* Ensure notifications appear above preview mask */
.alert.position-fixed {
    z-index: 10001 !important;
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

/* ========================================
   SCROLL REVEAL ANIMATIONS
   ======================================== */
/* Prevent overflow from reveal animations */
html, body {
    overflow-x: hidden;
    width: 100%;
}

.records-container {
    overflow-x: hidden;
    width: 100%;
}

/* Remove reveal animations - elements visible immediately */
.reveal-element {
    opacity: 1 !important;
    transform: none !important;
    transition: none !important;
    max-width: 100%;
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
        <div class="records-table-section reveal-element reveal-slide-up">
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

                    <!-- Progress Notes for this record (Consolidated) -->
                    @if($record->progressNotes && $record->progressNotes->count() > 0)
                        <tr>
                            <td class="form-name">
                                Progress Notes ({{ $record->progressNotes->count() }} {{ $record->progressNotes->count() == 1 ? 'entry' : 'entries' }})
                            </td>
                            <td class="form-date">{{ \Carbon\Carbon::parse($record->progressNotes->max('created_at'))->format('m/d/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewAllProgressNotes({{ $record->id }})" title="View All Progress Notes">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn-download" onclick="downloadAllProgressNotes({{ $record->id }})" title="Download All Progress Notes">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
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
        <div class="form-preview-section reveal-element reveal-fade" id="recordPreviewPanel">
            <div class="preview-mask hidden" id="previewMask">
                <div class="mask-content">
                    <i class="bi bi-lock-fill"></i>
                    <p>Access is needed to view this record</p>
                </div>
            </div>
            <div class="empty-preview-state">
                <i class="bi bi-file-earmark-medical"></i>
                <p>Select a record to view details</p>
                <p class="small">Click on any VIEW button to preview the record here</p>
            </div>
        </div>
    </div>
</div>

<!-- Password Verification Modal -->
<div class="modal fade password-modal" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">
                    <i class="bi bi-shield-lock"></i>
                    Password Requirements
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3 text-muted">Please enter your patient account password to access this record.</p>
                <form id="passwordForm">
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Password</label>
                        <input type="password" class="form-control" id="passwordInput" placeholder="Enter your password" required autofocus>
                        <div class="password-error" id="passwordError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="verifyPasswordBtn">
                    <i class="bi bi-check-circle me-2"></i>Access Forms
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentRecordId = null;
let isAuthenticated = false;
let authTimer = null;
let pendingAction = null; // Store the action to execute after password verification
const AUTH_DURATION = 15000; // 15 seconds in milliseconds

// Initialize: Lock all buttons on page load
document.addEventListener('DOMContentLoaded', function() {
    lockAllButtons();
    hidePreviewMask(); // Hide mask initially when no record is selected
    
    // Password modal event listeners
    // Verify password button click
    const verifyBtn = document.getElementById('verifyPasswordBtn');
    if (verifyBtn) {
        verifyBtn.addEventListener('click', verifyPassword);
    }
    
    // Enter key in password input
    const passwordInput = document.getElementById('passwordInput');
    if (passwordInput) {
        passwordInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                verifyPassword();
            }
        });
    }
    
    // Clear pending action when modal is closed
    const passwordModal = document.getElementById('passwordModal');
    if (passwordModal) {
        passwordModal.addEventListener('hidden.bs.modal', function() {
            // Only clear if authentication failed (user closed modal without verifying)
            if (!isAuthenticated) {
                pendingAction = null;
            }
            
            // Only show mask if authentication expired and there's content
            if (!isAuthenticated) {
                const previewPanel = document.getElementById('recordPreviewPanel');
                if (previewPanel) {
                    const hasContent = previewPanel.querySelector('.preview-header, .record-detail-view, .progress-notes-table-container, .form-grid, .section-title');
                    if (hasContent) {
                        showPreviewMask();
                    }
                }
            }
        });
        
        // Ensure modal is visible when shown
        passwordModal.addEventListener('shown.bs.modal', function() {
            // Hide preview mask when modal is shown
            hidePreviewMask();
            
            // Focus on password input
            const passwordInput = document.getElementById('passwordInput');
            if (passwordInput) {
                passwordInput.focus();
            }
        });
    }
    
    // Expire authentication when user navigates away from the page
    // Listen for navigation clicks (link clicks that navigate to different page)
    document.addEventListener('click', function(e) {
        const target = e.target.closest('a');
        if (target && target.href) {
            try {
                const currentPath = window.location.pathname;
                const targetUrl = new URL(target.href, window.location.origin);
                const targetPath = targetUrl.pathname;
                
                // If navigating to a different page (not same page, not anchor link), expire authentication
                if (targetPath !== currentPath && 
                    !target.href.includes('#') && 
                    !target.hasAttribute('data-no-expire') &&
                    targetUrl.origin === window.location.origin) {
                    // Expire authentication before navigation
                    expireAuthentication();
                }
            } catch (error) {
                // If URL parsing fails, it might be an external link - expire authentication
                if (!target.hasAttribute('data-no-expire')) {
                    expireAuthentication();
                }
            }
        }
    });
    
    // Listen for page visibility change (tab switch, minimize, etc.)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Page is hidden (user switched tabs or minimized window)
            // Expire authentication for security
            expireAuthentication();
        }
    });
    
    // Listen for page unload (navigation to different page or browser close)
    window.addEventListener('beforeunload', function() {
        // Expire authentication when leaving the page
        expireAuthentication();
    });
});

// Lock all view and download buttons
function lockAllButtons() {
    document.querySelectorAll('.btn-view, .btn-download').forEach(btn => {
        btn.classList.add('locked');
    });
}

// Unlock all view and download buttons
function unlockAllButtons() {
    document.querySelectorAll('.btn-view, .btn-download').forEach(btn => {
        btn.classList.remove('locked');
    });
}

// Expire authentication (reset access)
function expireAuthentication() {
    isAuthenticated = false;
    
    // Clear authentication timer
    if (authTimer) {
        clearTimeout(authTimer);
        authTimer = null;
    }
    
    // Lock all buttons
    lockAllButtons();
    
    // Show preview mask
    showPreviewMask();
    
    // Clear pending action
    pendingAction = null;
}

// Show preview mask
function showPreviewMask() {
    const mask = document.getElementById('previewMask');
    const previewPanel = document.getElementById('recordPreviewPanel');
    
    if (!mask && previewPanel) {
        // Create mask if it doesn't exist
        const maskElement = document.createElement('div');
        maskElement.id = 'previewMask';
        maskElement.className = 'preview-mask';
        maskElement.innerHTML = `
            <div class="mask-content">
                <i class="bi bi-lock-fill"></i>
                <p>Access is needed to view this record</p>
            </div>
        `;
        previewPanel.insertBefore(maskElement, previewPanel.firstChild);
    }
    
    if (mask) {
        mask.classList.remove('hidden');
        mask.style.opacity = '1';
        mask.style.zIndex = '100';
        mask.style.pointerEvents = 'auto';
    }
}

// Hide preview mask
function hidePreviewMask() {
    const mask = document.getElementById('previewMask');
    if (mask) {
        mask.classList.add('hidden');
        mask.style.opacity = '0';
        mask.style.zIndex = '-1';
        mask.style.pointerEvents = 'none';
    }
}

// Show password modal
function showPasswordModal(actionCallback) {
    pendingAction = actionCallback;
    
    // Hide preview mask when showing password modal
    hidePreviewMask();
    
    const modal = new bootstrap.Modal(document.getElementById('passwordModal'), {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    
    document.getElementById('passwordInput').value = '';
    document.getElementById('passwordError').textContent = '';
    document.getElementById('passwordError').classList.remove('show');
    
    // Ensure modal is shown with proper z-index
    modal.show();
    
    // Force modal to be on top after showing
    setTimeout(() => {
        const modalElement = document.getElementById('passwordModal');
        if (modalElement) {
            modalElement.style.zIndex = '10001';
        }
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.zIndex = '10000';
        }
    }, 100);
}

// Verify password
async function verifyPassword() {
    const passwordInput = document.getElementById('passwordInput');
    const passwordError = document.getElementById('passwordError');
    const verifyBtn = document.getElementById('verifyPasswordBtn');
    
    const password = passwordInput.value.trim();
    
    if (!password) {
        passwordError.textContent = 'Please enter your password.';
        passwordError.classList.add('show');
        return;
    }
    
    // Disable button during verification
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
    passwordError.classList.remove('show');
    
    try {
        const response = await fetch('/patient/record/verify-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ password: password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Password verified successfully
            isAuthenticated = true;
            unlockAllButtons();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'));
            modal.hide();
            
            // Execute pending action if any
            if (pendingAction) {
                pendingAction();
                pendingAction = null;
            }
            
            // Start 15-second timer
            startAuthTimer();
            
            // Show success notification
            showNotification('Password verified successfully. Access granted for 15 seconds.', 'success');
        } else {
            // Password incorrect
            passwordError.textContent = data.message || 'Incorrect password. Please try again.';
            passwordError.classList.add('show');
            passwordInput.focus();
        }
    } catch (error) {
        console.error('Error verifying password:', error);
        passwordError.textContent = 'An error occurred. Please try again.';
        passwordError.classList.add('show');
    } finally {
        // Re-enable button
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Access Forms';
    }
}

// Start authentication timer (15 seconds)
function startAuthTimer() {
    // Clear existing timer
    if (authTimer) {
        clearTimeout(authTimer);
    }
    
    // Set new timer
    authTimer = setTimeout(() => {
        isAuthenticated = false;
        lockAllButtons();
        
        // Force show mask to cover all content when authentication expires
        const previewPanel = document.getElementById('recordPreviewPanel');
        if (previewPanel) {
            // Always show mask when authentication expires to protect content
            // Check if there's any content in the preview panel
            const hasContent = previewPanel.querySelector('.preview-header, .record-detail-view, .progress-notes-table-container, .form-grid, .section-title, .progress-notes-info, .progress-notes-table');
            
            // Always show mask if there's content, or if the panel is not empty
            if (hasContent || previewPanel.innerHTML.trim() !== '') {
                // Force show mask to cover all content
                showPreviewMask();
                
                // Double-check mask is visible by setting styles directly
                setTimeout(() => {
                    const mask = document.getElementById('previewMask');
                    if (mask) {
                        mask.classList.remove('hidden');
                        mask.style.opacity = '1';
                        mask.style.zIndex = '100';
                        mask.style.pointerEvents = 'auto';
                        mask.style.display = 'flex';
                    }
                }, 100);
            }
        }
        
        // Always try to show mask as a safety measure
        showPreviewMask();
        
        showNotification('Access expired. Please verify your password again.', 'warning');
    }, AUTH_DURATION);
}

// Check if authenticated before executing action
function requireAuth(actionCallback) {
    if (isAuthenticated) {
        // Already authenticated, execute action immediately
        actionCallback();
    } else {
        // Not authenticated, show password modal
        showPasswordModal(actionCallback);
    }
}

// View record in preview panel
async function viewRecord(recordId) {
    requireAuth(async () => {
        await executeViewRecord(recordId);
    });
}

// Execute view record (called after authentication)
async function executeViewRecord(recordId) {
    currentRecordId = recordId;
    const previewPanel = document.getElementById('recordPreviewPanel');

    // Hide mask since user is authenticated
    hidePreviewMask();

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
            // Hide mask after successful load
            hidePreviewMask();
        } else {
            // If record not found or access denied, show appropriate message
            if (data.message && (data.message.includes('access denied') || data.message.includes('not found'))) {
                previewPanel.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-shield-exclamation me-2"></i>
                        ${data.message || 'This record may not be available yet. Please contact the clinic if you believe this is an error.'}
                    </div>
                `;
                hidePreviewMask();
            } else {
                throw new Error('Invalid response');
            }
        }
    } catch (error) {
        console.error('Error loading record:', error);
        previewPanel.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Failed to load record. Please try again.
            </div>
        `;
        // Don't show mask on error - let user see the error message
        hidePreviewMask();
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
    requireAuth(() => {
    // Open print-friendly view in new window
    const printWindow = window.open(`/patient/record/${recordId}/download`, '_blank');

    if (printWindow) {
        showNotification('Opening print-friendly view. Use your browser\'s print function to save as PDF.', 'info');
    } else {
        showNotification('Please allow pop-ups to view the printable record.', 'warning');
    }
    });
}

// Notification function
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';

    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 10001; min-width: 300px;';
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
    requireAuth(async () => {
        await executeViewHistory(historyId);
    });
}

// Execute view history (called after authentication)
async function executeViewHistory(historyId) {
    const previewPanel = document.getElementById('recordPreviewPanel');

    // Show mask initially (blurred)
    showPreviewMask();

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
            // Hide mask after successful load
            hidePreviewMask();
        } else {
            showNotification('Failed to load patient history', 'error');
            previewPanel.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Failed to load patient history.
                </div>
            `;
            showPreviewMask(); // Keep mask on error
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
        showPreviewMask(); // Keep mask on error
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
                ${history.treatment_condition && history.under_treatment?.toLowerCase() === 'yes' ? `<div class="form-field field-full-width" style="padding-left: 1.5rem; border-left: 3px solid #0d6efd; margin-top: 0.5rem; margin-bottom: 0.5rem;"><span class="field-label" style="font-style: italic; color: #6c757d;">If Under Treatment (Yes), Condition:</span><span class="field-value">${history.treatment_condition}</span></div>` : ''}
                ${history.serious_illness ? `<div class="form-field"><span class="field-label">Serious Illness:</span><span class="field-value">${history.serious_illness}</span></div>` : ''}
                ${history.illness_details && history.serious_illness?.toLowerCase() === 'yes' ? `<div class="form-field field-full-width" style="padding-left: 1.5rem; border-left: 3px solid #0d6efd; margin-top: 0.5rem; margin-bottom: 0.5rem;"><span class="field-label" style="font-style: italic; color: #6c757d;">If Serious Illness (Yes), Illness Details:</span><span class="field-value">${history.illness_details}</span></div>` : ''}
                ${history.been_hospitalized ? `<div class="form-field"><span class="field-label">Been Hospitalized:</span><span class="field-value">${history.been_hospitalized}</span></div>` : ''}
                ${history.hospitalization_reason && history.been_hospitalized?.toLowerCase() === 'yes' ? `<div class="form-field field-full-width" style="padding-left: 1.5rem; border-left: 3px solid #0d6efd; margin-top: 0.5rem; margin-bottom: 0.5rem;"><span class="field-label" style="font-style: italic; color: #6c757d;">If Been Hospitalized (Yes), Reason:</span><span class="field-value">${history.hospitalization_reason}</span></div>` : ''}
                ${history.taking_drugs ? `<div class="form-field"><span class="field-label">Taking Medications:</span><span class="field-value">${history.taking_drugs}</span></div>` : ''}
                ${history.medications && history.taking_drugs?.toLowerCase() === 'yes' ? `<div class="form-field field-full-width" style="padding-left: 1.5rem; border-left: 3px solid #0d6efd; margin-top: 0.5rem; margin-bottom: 0.5rem;"><span class="field-label" style="font-style: italic; color: #6c757d;">If Taking Medications (Yes), Medications:</span><span class="field-value">${history.medications}</span></div>` : ''}
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
    requireAuth(() => {
    const printWindow = window.open(`/patient/history/${historyId}/download`, '_blank');

    if (printWindow) {
        showNotification('Opening print-friendly view. Use your browser\'s print function to save as PDF.', 'info');
    } else {
        showNotification('Please allow pop-ups to view the printable history.', 'warning');
    }
    });
}

function downloadProgressNote(noteId) {
    const printWindow = window.open(`/patient/progress-note/${noteId}/download`, '_blank');

    if (printWindow) {
        showNotification('Opening print-friendly view. Use your browser\'s print function to save as PDF.', 'info');
    } else {
        showNotification('Please allow pop-ups to view the printable progress note.', 'warning');
    }
}

// Download all progress notes for a record
function downloadAllProgressNotes(recordId) {
    requireAuth(() => {
    const printWindow = window.open(`/patient/record/${recordId}/progress-notes/download`, '_blank');

    if (printWindow) {
        showNotification('Opening consolidated progress notes view. Use your browser\'s print function to save as PDF.', 'info');
    } else {
        showNotification('Please allow pop-ups to view the printable progress notes.', 'warning');
    }
    });
}

// View all progress notes for a record
async function viewAllProgressNotes(recordId) {
    requireAuth(async () => {
        await executeViewAllProgressNotes(recordId);
    });
}

// Execute view all progress notes (called after authentication)
async function executeViewAllProgressNotes(recordId) {
    const previewPanel = document.getElementById('recordPreviewPanel');

    // Show mask initially (blurred)
    showPreviewMask();

    // Show loading state
    previewPanel.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted" style="font-size: 0.875rem;">Loading progress notes...</p>
        </div>
    `;

    try {
        const response = await fetch(`/patient/record/${recordId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Progress notes response:', data);
        console.log('Progress notes count:', data.progress_notes_count);
        console.log('Record progressNotes:', data.record?.progressNotes);

        if (data.success && data.record) {
            // Check if progressNotes exists (even if empty array)
            // Handle both array and collection formats
            const progressNotes = data.record.progressNotes || data.record.progress_notes || [];
            const notesArray = Array.isArray(progressNotes) ? progressNotes : (progressNotes.data || []);
            
            console.log('Processed progress notes:', notesArray);
            console.log('Notes array length:', notesArray.length);
            
            if (notesArray && notesArray.length > 0) {
                renderAllProgressNotes(notesArray, data.record);
                // Hide mask after successful load
                hidePreviewMask();
            } else if (data.progress_notes_count > 0) {
                // If count says there are notes but array is empty, try to reload
                console.warn('Progress notes count indicates notes exist but array is empty');
                showNotification('Progress notes found but unable to load. Please try again.', 'warning');
                previewPanel.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Progress notes found (${data.progress_notes_count}) but unable to load. Please try refreshing the page.
                    </div>
                `;
            } else {
                // Progress notes not loaded or doesn't exist
                showNotification('No progress notes found for this record', 'info');
                previewPanel.innerHTML = `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No progress notes found for this record.
                    </div>
                `;
                showPreviewMask(); // Keep mask on error
            }
        } else {
            const errorMsg = data.message || 'Failed to load progress notes';
            console.error('Error response:', data);
            showNotification(errorMsg, 'error');
            previewPanel.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${errorMsg}
                </div>
            `;
            showPreviewMask(); // Keep mask on error
        }
    } catch (error) {
        console.error('Error loading progress notes:', error);
        showNotification('Error loading progress notes: ' + error.message, 'error');
        previewPanel.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error loading progress notes: ${error.message}
            </div>
        `;
        showPreviewMask(); // Keep mask on error
    }
}

// Render all progress notes in preview panel
function renderAllProgressNotes(notes, record) {
    const patientName = record.user && record.user.info 
        ? `${record.user.info.first_name} ${record.user.info.last_name}`
        : record.user?.username || 'Patient';

    // Handle empty array
    if (!notes || notes.length === 0) {
        previewPanel.innerHTML = `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No progress notes found for this record.
            </div>
        `;
        return;
    }

    let tableRows = '';
    notes.forEach((note, index) => {
        const noteDate = note.note_date ? new Date(note.note_date).toLocaleDateString() : 'N/A';
        const amountPaid = note.amount_paid ? parseFloat(note.amount_paid).toFixed(2) : '-';
        const balance = note.balance ? parseFloat(note.balance).toFixed(2) : '-';
        
        tableRows += `
            <tr class="progress-notes-row">
                <td class="progress-notes-cell" style="text-align: center; padding: 0.5rem;">${index + 1}</td>
                <td class="progress-notes-cell" style="padding: 0.5rem;">${noteDate}</td>
                <td class="progress-notes-cell" style="padding: 0.5rem;">${note.progress_description || '-'}</td>
                <td class="progress-notes-cell" style="text-align: right; padding: 0.5rem;">${amountPaid !== '-' ? '₱' + amountPaid : '-'}</td>
                <td class="progress-notes-cell" style="text-align: right; padding: 0.5rem;">${balance !== '-' ? '₱' + balance : '-'}</td>
                <td class="progress-notes-cell" style="padding: 0.5rem;">${note.conforme || '-'}</td>
            </tr>
        `;
    });

    const content = `
        <div class="preview-header mb-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #0d6efd; padding-bottom: 0.75rem;">
            <h2 class="form-preview-title mb-0 progress-notes-title" style="color: #0d6efd; font-weight: 700;">
                <i class="bi bi-journal-text me-2"></i>Progress Notes
            </h2>
        </div>
        <div class="mb-3 progress-notes-info">
            <p class="text-muted mb-0"><strong>Patient:</strong> ${patientName}</p>
            <p class="text-muted mb-0"><strong>Total Entries:</strong> ${notes.length}</p>
        </div>
        <div class="table-responsive progress-notes-table-container" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered table-sm progress-notes-table" style="font-size: 0.875rem;">
                <thead class="progress-notes-thead" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white; position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th style="text-align: center; padding: 0.5rem; width: 5%;">#</th>
                        <th style="padding: 0.5rem; width: 12%;">DATE</th>
                        <th style="padding: 0.5rem; width: 30%;">PROGRESS NOTES</th>
                        <th style="text-align: right; padding: 0.5rem; width: 15%;">AMOUNT PAID</th>
                        <th style="text-align: right; padding: 0.5rem; width: 15%;">BALANCE</th>
                        <th style="padding: 0.5rem; width: 23%;">CONFORME</th>
                    </tr>
                </thead>
                <tbody class="progress-notes-tbody">
                    ${tableRows}
                </tbody>
            </table>
        </div>
    `;

    const previewPanel = document.getElementById('recordPreviewPanel');
    previewPanel.innerHTML = content;

    // Scroll preview panel to top
    previewPanel.scrollTop = 0;
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

@if(!empty($chatbotSetting) && $chatbotSetting->enabled)
@include('patient.components.chatbot')
@endif

<script>
// ========================================
// SCROLL REVEAL FUNCTIONALITY - DISABLED
// ========================================
// Reveal animations removed - all elements visible immediately
(function() {
    document.querySelectorAll('.reveal-element').forEach(el => {
        el.classList.add('revealed');
        el.style.opacity = '1';
        el.style.transform = 'none';
    });
})();
</script>

@endsection
