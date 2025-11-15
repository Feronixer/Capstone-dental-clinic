@extends('layout.admin.app')
@section('content')
<style>
.notifications-container {
    padding: 1rem;
    background: #f8f9fa;
    min-height: calc(100vh - 80px);
    max-height: calc(100vh - 80px);
    max-width: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.notifications-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.25rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.25rem;
    color: white;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
}

.notifications-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.notifications-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 0.95rem;
}

.requests-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.requests-grid::-webkit-scrollbar {
    width: 8px;
}

.requests-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.requests-grid::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.requests-grid::-webkit-scrollbar-thumb:hover {
    background: #5568d3;
}

.request-groups-wrapper {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding-right: 0.5rem;
}

.request-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.request-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border-left: 4px solid #667eea;
}

.request-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f5f9;
    gap: 0.75rem;
}

.request-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    gap: 0.5rem;
}

.request-type-badge.walk-in {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.request-type-badge.reschedule {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.request-type-badge.book {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
}

.request-time {
    font-size: 0.85rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.patient-info {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    margin-bottom: 1rem;
}

.patient-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    font-weight: 700;
    flex-shrink: 0;
}

.patient-details {
    flex: 1;
}

.patient-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.patient-id {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0;
}

.request-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.detail-box {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 0.75rem;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
}

.detail-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
}

.request-reason {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.request-reason-label {
    font-weight: 600;
    color: #92400e;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.request-reason-text {
    color: #78350f;
    line-height: 1.6;
    margin: 0;
}

.request-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-approve {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

.btn-deny {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-deny:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.empty-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #64748b;
    font-size: 1.05rem;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 2px solid #f1f5f9;
    gap: 1rem;
    flex-wrap: wrap;
}

.pagination-info {
    color: #64748b;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-info strong {
    color: #1e293b;
}

.pagination-links {
    display: flex;
    justify-content: flex-end;
    flex: 1;
}

.modern-pagination {
    list-style: none;
    display: flex;
    gap: 0.5rem;
    padding: 0;
    margin: 0;
    align-items: center;
}

.modern-pagination .page-item {
    display: flex;
}

.modern-pagination .page-link {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #1f2937;
    background: #ffffff;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);
}

.modern-pagination .page-link i {
    font-size: 1rem;
}

.modern-pagination .page-link:hover {
    border-color: #667eea;
    color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    transform: translateY(-1px);
}

.modern-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.35);
}

.modern-pagination .page-item.disabled .page-link {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
    box-shadow: none;
}

.modern-pagination .page-link.dots {
    width: auto;
    padding: 0 0.8rem;
    cursor: default;
    border-style: dashed;
    color: #9ca3af;
    box-shadow: none;
}

/* Deny Reason Modal Styles */
.modal-content.modern-modal {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-header.gradient-header {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-radius: 0;
    border: none;
    padding: 2rem;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
}

.deny-modal-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem auto;
}

.deny-modal-icon i {
    font-size: 2.5rem;
    color: #dc2626;
}

.deny-modal-message {
    text-align: center;
    font-size: 1.1rem;
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.deny-reason-label {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.75rem;
    font-size: 1rem;
    display: block;
}

.deny-reason-input {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
    transition: all 0.3s;
}

.deny-reason-input:hover {
    border-color: #cbd5e1;
}

.deny-reason-input:focus {
    outline: none;
    border-color: #ef4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

.modal-footer {
    padding: 1.5rem 2rem;
    gap: 1rem;
}

.modal-footer .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
}

.modal-footer .btn-secondary {
    background: #64748b;
    border: none;
}

.modal-footer .btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
}

.modal-footer .btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.modal-footer .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.deny-warning-box {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-left: 4px solid #f59e0b;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
}

.deny-warning-box p {
    margin: 0;
    color: #92400e;
    font-size: 0.9rem;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.deny-warning-box i {
    color: #f59e0b;
    flex-shrink: 0;
    margin-top: 2px;
}


@media (max-width: 768px) {
    .notifications-container {
        padding: 0.75rem;
    }
    
    .notifications-header {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 10px;
    }
    
    .notifications-header h1 {
        font-size: 1.25rem;
    }
    
    .notifications-header p {
        font-size: 0.85rem;
    }
    
    .requests-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .request-card {
        padding: 1rem;
        border-radius: 10px;
    }
    
    .request-header {
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 0.875rem;
        padding-bottom: 0.625rem;
    }

    .patient-info {
        flex-direction: row;
        text-align: left;
        gap: 0.75rem;
        margin-bottom: 0.875rem;
    }
    
    .patient-avatar {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .patient-name {
        font-size: 1rem;
    }
    
    .patient-id {
        font-size: 0.85rem;
    }

    .request-details-grid {
        grid-template-columns: 1fr;
        gap: 0.625rem;
        margin-bottom: 0.875rem;
    }
    
    .detail-box {
        padding: 0.625rem;
    }
    
    .detail-label {
        font-size: 0.75rem;
        margin-bottom: 0.375rem;
    }
    
    .detail-value {
        font-size: 0.875rem;
    }

    .request-reason {
        padding: 0.625rem;
        margin-bottom: 0.875rem;
    }
    
    .request-reason-label {
        font-size: 0.85rem;
        margin-bottom: 0.375rem;
    }
    
    .request-reason-text {
        font-size: 0.8rem;
    }

    .request-actions {
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn-approve, .btn-deny {
        width: 100%;
        justify-content: center;
        padding: 0.625rem 1.25rem;
    }
}

@media (max-width: 480px) {
    .notifications-container {
        padding: 0.5rem;
    }
    
    .notifications-header {
        padding: 0.875rem;
        margin-bottom: 0.875rem;
    }
    
    .notifications-header h1 {
        font-size: 1.1rem;
    }
    
    .notifications-header p {
        font-size: 0.8rem;
    }
    
    .requests-grid {
        gap: 0.625rem;
    }
    
    .request-card {
        padding: 0.875rem;
    }
    
    .request-header {
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
    }
    
    .request-type-badge {
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .request-time {
        font-size: 0.75rem;
    }
    
    .patient-info {
        margin-bottom: 0.75rem;
    }
    
    .patient-avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .patient-name {
        font-size: 0.95rem;
    }
    
    .patient-id {
        font-size: 0.8rem;
    }
    
    .request-details-grid {
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .detail-box {
        padding: 0.5rem;
    }
    
    .detail-label {
        font-size: 0.7rem;
        margin-bottom: 0.3rem;
    }
    
    .detail-value {
        font-size: 0.8rem;
    }
    
    .request-reason {
        padding: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .request-reason-label {
        font-size: 0.8rem;
        margin-bottom: 0.3rem;
    }
    
    .request-reason-text {
        font-size: 0.75rem;
    }
    
    .request-actions {
        gap: 0.625rem;
    }
    
    .btn-approve, .btn-deny {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .pagination-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .pagination-links {
        justify-content: center;
        width: 100%;
    }
}

/* ============================================
   DARK MODE STYLES FOR APPOINTMENT REQUESTS
   ============================================ */

[data-theme="dark"] .notifications-container {
    background: var(--dm-bg-primary) !important;
}

[data-theme="dark"] .notifications-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

[data-theme="dark"] .notifications-header h1,
[data-theme="dark"] .notifications-header p {
    color: white !important;
}

/* Requests Grid Dark Mode */
[data-theme="dark"] .requests-grid {
    background: transparent !important;
}

/* Request Card Dark Mode */
[data-theme="dark"] .request-card {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .request-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5) !important;
    background: var(--dm-bg-secondary) !important;
}

/* Request Type Badge Dark Mode */
[data-theme="dark"] .request-type-badge.walk-in {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%) !important;
    color: #fbbf24 !important;
    border: 1px solid rgba(251, 191, 36, 0.3) !important;
}

[data-theme="dark"] .request-type-badge.reschedule {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.2) 100%) !important;
    color: #60a5fa !important;
    border: 1px solid rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .request-type-badge.book {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(22, 163, 74, 0.2) 100%) !important;
    color: #4ade80 !important;
    border: 1px solid rgba(34, 197, 94, 0.3) !important;
}

[data-theme="dark"] .request-type-badge i {
    color: inherit !important;
}

[data-theme="dark"] .request-header {
    border-bottom-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .request-time {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .request-time i {
    color: var(--dm-text-muted) !important;
}

/* Patient Info Dark Mode */
[data-theme="dark"] .patient-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

[data-theme="dark"] .patient-name {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .patient-id {
    color: var(--dm-text-muted) !important;
}

/* Detail Box Dark Mode */
[data-theme="dark"] .detail-box {
    background: linear-gradient(135deg, var(--dm-bg-secondary) 0%, var(--dm-bg-tertiary) 100%) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .detail-label {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .detail-label i {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .detail-value {
    color: var(--dm-text-primary) !important;
}

/* Request Reason Dark Mode */
[data-theme="dark"] .request-reason {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%) !important;
    border-left-color: #f59e0b !important;
}

[data-theme="dark"] .request-reason-label {
    color: #fbbf24 !important;
}

[data-theme="dark"] .request-reason-label i {
    color: #fbbf24 !important;
}

[data-theme="dark"] .request-reason-text {
    color: var(--dm-text-primary) !important;
}

/* Empty State Dark Mode */
[data-theme="dark"] .empty-state {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .empty-icon {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .empty-title {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .empty-text {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .pagination-container {
    border-top-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .pagination-info {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination-info strong {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination-links {
    justify-content: flex-end !important;
}

[data-theme="dark"] .modern-pagination .page-link {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.25) !important;
}

[data-theme="dark"] .modern-pagination .page-link:hover {
    border-color: #667eea !important;
    color: #a5b4fc !important;
    box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4) !important;
}

[data-theme="dark"] .modern-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.45) !important;
}

[data-theme="dark"] .modern-pagination .page-item.disabled .page-link {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-muted) !important;
    box-shadow: none !important;
}

[data-theme="dark"] .modern-pagination .page-link.dots {
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .modern-pagination .page-link i {
    color: inherit !important;
}

/* Modal Dark Mode */
[data-theme="dark"] .modal-content.modern-modal {
    background-color: var(--dm-card-bg) !important;
}

[data-theme="dark"] .modal-header.gradient-header {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

[data-theme="dark"] #approveModal .modal-header.gradient-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

[data-theme="dark"] .modal-body {
    background-color: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body p,
[data-theme="dark"] .modal-body h5 {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .text-muted {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .modal-footer.bg-light {
    background: var(--dm-bg-tertiary) !important;
}

[data-theme="dark"] .modal-footer .btn-secondary {
    background: var(--dm-bg-secondary) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modal-footer .btn-secondary:hover {
    background: var(--dm-bg-tertiary) !important;
}

/* Deny Modal Dark Mode */
[data-theme="dark"] .deny-modal-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%) !important;
}

[data-theme="dark"] .deny-modal-message {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .deny-reason-label {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .deny-reason-label i {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .deny-reason-input {
    background-color: var(--dm-input-bg) !important;
    border-color: var(--dm-input-border) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .deny-reason-input::placeholder {
    color: var(--dm-text-muted) !important;
    opacity: 0.7;
}

[data-theme="dark"] .deny-reason-input:focus {
    border-color: #ef4444 !important;
    background-color: var(--dm-input-bg) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .deny-warning-box {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%) !important;
    border-left-color: #f59e0b !important;
}

[data-theme="dark"] .deny-warning-box p {
    color: #fbbf24 !important;
}

[data-theme="dark"] .deny-warning-box i {
    color: #f59e0b !important;
}

/* Approve Modal Dark Mode - inline styles override */
[data-theme="dark"] #approveModal .modal-body div[style*="background: linear-gradient(135deg, #d1fae5"] {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%) !important;
}

[data-theme="dark"] #approveModal .modal-body h5[style*="color: #1e293b"] {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body div[style*="background: linear-gradient(135deg, #d1fae5"] {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%) !important;
}

[data-theme="dark"] .modal-body h5[style*="color: #1e293b"],
[data-theme="dark"] .modal-body p[style*="color: #1e293b"] {
    color: var(--dm-text-primary) !important;
}

/* Form Controls in Modal Dark Mode */
[data-theme="dark"] .modal-body .form-control {
    background-color: var(--dm-input-bg) !important;
    border-color: var(--dm-input-border) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .form-control:focus {
    background-color: var(--dm-input-bg) !important;
    border-color: var(--primary-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .form-label {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .alert-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%) !important;
    border-color: #3b82f6 !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .alert-info strong {
    color: var(--dm-text-primary) !important;
}

/* Close Button Dark Mode */
[data-theme="dark"] .btn-close-white {
    filter: brightness(0) invert(1) !important;
    opacity: 0.8;
}

[data-theme="dark"] .btn-close-white:hover {
    opacity: 1 !important;
}

/* Time Slot Picker Styles */
.time-slots-picker {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 0.5rem;
    padding: 0.75rem;
}

.time-slot-btn {
    padding: 0.5rem 0.75rem;
    border: 2px solid #dee2e6;
    border-radius: 0.375rem;
    background: white;
    color: #495057;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.time-slot-btn:hover {
    border-color: #667eea;
    background: #f0f4ff;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.time-slot-btn.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.time-slot-btn:disabled,
.time-slot-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

.time-slot-btn.blocked {
    text-decoration: line-through;
}

.time-slot-btn:disabled:hover,
.time-slot-btn.disabled:hover {
    transform: none;
    box-shadow: none;
    border-color: #dee2e6;
    background: #e9ecef;
    color: #6c757d;
}

[data-theme="dark"] .time-slots-picker {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .time-slot-btn {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .time-slot-btn:hover {
    border-color: #667eea !important;
    background: rgba(102, 126, 234, 0.1) !important;
    color: #667eea !important;
}

[data-theme="dark"] .time-slot-btn.selected {
    border-color: #667eea !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

[data-theme="dark"] .time-slot-btn:disabled,
[data-theme="dark"] .time-slot-btn.disabled {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-muted) !important;
}

.bg-primary-subtle {
    background-color: #6b6ed3 !important;
}

/* Filter Buttons */
.filter-buttons {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.75rem 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: white;
    color: #64748b;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.filter-btn:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.filter-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.filter-btn i {
    font-size: 1rem;
}

.request-group[data-filter-hidden="true"] {
    display: none;
}

[data-theme="dark"] .filter-btn {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .filter-btn:hover {
    border-color: #667eea !important;
    color: #667eea !important;
}

[data-theme="dark"] .filter-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

</style>

<div class="notifications-container">
    <div class="notifications-header">
        <h1><i class="bi bi-bell-fill me-2"></i>Appointment Requests</h1>
        <p>Review and manage patient appointment requests</p>
    </div>

    @if($pendingRequests->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h3 class="empty-title">No Pending Requests</h3>
            <p class="empty-text">You're all caught up! There are no appointment requests waiting for review.</p>
        </div>
    @else
        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <a href="{{ request()->url() }}?type=all" class="filter-btn {{ ($filterType ?? 'all') === 'all' ? 'active' : '' }}" data-filter="all">
                <i class="bi bi-list-ul"></i>
                <span>All Requests ({{ $allCount ?? $pendingRequests->total() }})</span>
            </a>
            <a href="{{ request()->url() }}?type=book" class="filter-btn {{ ($filterType ?? '') === 'book' ? 'active' : '' }}" data-filter="book">
                <i class="bi bi-calendar-plus-fill"></i>
                <span>Regular Booking ({{ $bookCount ?? 0 }})</span>
            </a>
            <a href="{{ request()->url() }}?type=reschedule" class="filter-btn {{ ($filterType ?? '') === 'reschedule' ? 'active' : '' }}" data-filter="reschedule">
                <i class="bi bi-arrow-repeat"></i>
                <span>Reschedule ({{ $rescheduleCount ?? 0 }})</span>
            </a>
            <a href="{{ request()->url() }}?type=walk-in" class="filter-btn {{ ($filterType ?? '') === 'walk-in' ? 'active' : '' }}" data-filter="walk-in">
                <i class="bi bi-lightning-charge-fill"></i>
                <span>Emergency Walk-in ({{ $walkInCount ?? 0 }})</span>
            </a>
        </div>

        <div class="request-groups-wrapper">
            <div class="requests-grid">
            @foreach($pendingRequests as $request)
                <div class="request-card" data-request-id="{{ $request->id }}" data-request-type="{{ $request->request_type ?? 'other' }}">
                    <div class="request-header">
                        <span class="request-type-badge {{ $request->request_type }}">
                            <i class="bi {{ $request->isBooking() ? 'bi-calendar-plus-fill' : ($request->isWalkIn() ? 'bi-lightning-charge-fill' : 'bi-arrow-repeat') }}"></i>
                            {{ $request->isBooking() ? 'Regular Booking' : ($request->isWalkIn() ? 'Emergency Walk-in' : 'Reschedule Request') }}
                        </span>
                        <div class="request-time">
                            <i class="bi bi-clock"></i>
                            {{ $request->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="patient-info">
                        <div class="patient-avatar">
                            {{ strtoupper(substr($request->patient->info->first_name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="patient-details">
                            <h3 class="patient-name">
                                {{ $request->patient->info ? trim($request->patient->info->first_name . ' ' . $request->patient->info->last_name) : $request->patient->name }}
                            </h3>
                            <p class="patient-id">Patient ID: #{{ $request->patient->id }}</p>
                        </div>
                    </div>

                    <div class="request-details-grid">
                        <div class="detail-box">
                            <div class="detail-label">
                                <i class="bi bi-calendar-event"></i> Requested Date
                            </div>
                            <div class="detail-value">
                                {{ $request->requested_datetime->format('F j, Y') }}
                            </div>
                        </div>
                        @if(!$request->isBooking())
                        <div class="detail-box">
                            <div class="detail-label">
                                <i class="bi bi-clock"></i> Requested Time
                            </div>
                            <div class="detail-value">
                                {{ $request->requested_datetime->format('g:i A') }}
                            </div>
                        </div>
                        @endif
                        @if($request->service || $request->other_concern)
                        <div class="detail-box">
                            <div class="detail-label">
                                <i class="bi bi-heart-pulse"></i> Service
                            </div>
                            <div class="detail-value">
                                @if($request->service)
                                    {{ $request->service->service_name }}
                                @else
                                    {{ $request->other_concern }}
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(!$request->isBooking())
                    <div class="request-reason">
                        <div class="request-reason-label">
                            <i class="bi bi-chat-left-text"></i> Reason for Request
                        </div>
                        <p class="request-reason-text">{{ $request->reason }}</p>
                    </div>
                    @endif

                    <div class="request-actions">
                        <button class="btn-deny" onclick="showDenyModal({{ $request->id }})">
                            <i class="bi bi-x-circle"></i> Deny
                        </button>
                        <button class="btn-approve" onclick="showApproveModal({{ $request->id }}, '{{ $request->service ? $request->service->service_name : $request->other_concern }}', {{ $request->duration_minutes }}, {{ $request->service_id ? 'true' : 'false' }}, '{{ $request->request_type }}', '{{ $request->requested_datetime ? $request->requested_datetime->format('Y-m-d') : '' }}')">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </div>
                    </div>
            @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($pendingRequests->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                <i class="bi bi-info-circle"></i>
                <span>Showing <strong>{{ $pendingRequests->firstItem() }}</strong> to <strong>{{ $pendingRequests->lastItem() }}</strong> of <strong>{{ $pendingRequests->total() }}</strong> requests</span>
            </div>
            <div class="pagination-links">
                <ul class="modern-pagination">
                    {{-- Previous Page --}}
                    <li class="page-item {{ $pendingRequests->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $pendingRequests->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    {{-- Page Numbers --}}
                    @php
                        $start = max($pendingRequests->currentPage() - 2, 1);
                        $end = min($pendingRequests->currentPage() + 2, $pendingRequests->lastPage());
                    @endphp

                    {{-- First page + dots --}}
                    @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $pendingRequests->appends(request()->query())->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled"><span class="page-link dots">...</span></li>
                        @endif
                    @endif

                    {{-- Page range --}}
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $pendingRequests->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $pendingRequests->appends(request()->query())->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Last page + dots --}}
                    @if ($end < $pendingRequests->lastPage())
                        @if ($end < $pendingRequests->lastPage() - 1)
                            <li class="page-item disabled"><span class="page-link dots">...</span></li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $pendingRequests->appends(request()->query())->url($pendingRequests->lastPage()) }}">{{ $pendingRequests->lastPage() }}</a>
                        </li>
                    @endif

                    {{-- Next Page --}}
                    <li class="page-item {{ $pendingRequests->currentPage() == $pendingRequests->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $pendingRequests->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @endif

    @endif
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header gradient-header">
                <h5 class="modal-title text-white">
                    <i class="bi bi-check-circle-fill me-2"></i>Approve Appointment Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: #10b981;"></i>
                </div>

                <h5 class="text-center mb-3" style="color: #1e293b; font-weight: 700;">Confirm Appointment Approval</h5>
                <p class="text-center text-muted mb-4" id="approveModalServiceName">Service: -</p>

                <!-- Date Field (for booking requests only) -->
                <div class="mb-3" id="appointmentDateGroup" style="display: none;">
                    <label for="appointmentDate" class="form-label fw-bold">
                        <i class="bi bi-calendar-event me-2"></i>Appointment Date
                    </label>
                    <input type="date" class="form-control" id="appointmentDate" required>
                    <small class="form-text text-muted mt-2">
                        <i class="bi bi-info-circle me-1"></i>Select the appointment date. Clinic hours are 11:00 AM to 6:00 PM.
                    </small>
                </div>

                <!-- Time Field (for booking requests only) -->
                <div class="mb-3" id="appointmentTimeGroup" style="display: none;">
                    <label class="form-label fw-bold">
                        <i class="bi bi-clock me-2"></i>Appointment Time
                    </label>
                    <div class="time-slots-picker" id="timeSlotsPicker" style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.75rem; background: #f8f9fa;">
                        <!-- Time slots will be generated dynamically -->
                        <div class="text-muted text-center" style="padding: 10px;">
                            <small>Please select a date first</small>
                        </div>
                    </div>
                    <input type="hidden" id="appointmentTime" required>
                    <small class="form-text text-muted mt-2" id="timeHelper">
                        <i class="bi bi-info-circle me-1"></i>Select the appointment time. Clinic hours are 11:00 AM to 6:00 PM.
                    </small>
                </div>

                <!-- Duration Field -->
                <div class="mb-3">
                    <label for="appointmentDuration" class="form-label fw-bold">
                        <i class="bi bi-clock-history me-2"></i>Appointment Duration (minutes)
                    </label>
                    <input type="number" class="form-control" id="appointmentDuration" min="15" max="480" step="15" value="30">
                    <small class="form-text text-muted" id="durationHelper">
                        Adjust the duration if needed. Default is based on the selected service.
                    </small>
                </div>

                <!-- Hidden fields to track request type and date -->
                <input type="hidden" id="approveRequestType" value="">
                <input type="hidden" id="approveRequestDate" value="">

                <div class="alert alert-info mb-0" role="alert" style="border-left: 4px solid #3b82f6;">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> This will create a confirmed appointment and notify the patient.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-success" id="confirmApproveBtn">
                    <i class="bi bi-check-circle me-1"></i>Approve Appointment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Deny Reason Modal -->
<div class="modal fade" id="denyReasonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header gradient-header">
                <h5 class="modal-title text-white">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Deny Appointment Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="deny-modal-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>

                <p class="deny-modal-message">
                    Are you sure you want to deny this appointment request?
                </p>

                <label for="denyReason" class="deny-reason-label">
                    <i class="bi bi-chat-left-text me-2"></i>Reason for Denial
                </label>
                <textarea
                    id="denyReason"
                    class="deny-reason-input"
                    placeholder="E.g., Time slot already booked, Outside clinic hours, No available dentist..."
                    required
                ></textarea>
                <div id="denyReasonError" class="mt-2" style="display:none; color:#dc2626; font-weight:600;">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Please provide a reason for denial.
                </div>

                <div class="deny-warning-box">
                    <p>
                        <i class="bi bi-info-circle-fill"></i>
                        <span>The patient will be notified of this denial along with the reason you provide.</span>
                    </p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-danger" id="confirmDenyBtn">
                    <i class="bi bi-x-circle me-1"></i>Confirm Denial
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentRequestId = null;
let currentRequestDuration = 30;

// Filter requests by type - now handled server-side via URL
// This function is kept for backward compatibility but filtering is done via URL
function filterRequests(type) {
    // Redirect to filtered URL
    const url = new URL(window.location.href);
    if (type === 'all') {
        url.searchParams.delete('type');
    } else {
        url.searchParams.set('type', type);
    }
    url.searchParams.delete('page'); // Reset to first page when filtering
    window.location.href = url.toString();
}

// Generate time slots for booking requests
function generateTimeSlotsForBooking(durationMinutes, selectedDate) {
    const timeSlotsPicker = document.getElementById('timeSlotsPicker');
    const timeInput = document.getElementById('appointmentTime');
    
    if (!timeSlotsPicker || !selectedDate) {
        if (timeSlotsPicker) {
            timeSlotsPicker.innerHTML = '<div class="text-muted text-center" style="padding: 10px;"><small>Please select a date</small></div>';
        }
        return;
    }

    // Clean and validate the date format (YYYY-MM-DD)
    let cleanDate = selectedDate.trim();
    // Remove any extra characters after the date (like :1)
    if (cleanDate.includes(':')) {
        cleanDate = cleanDate.split(':')[0];
    }
    // Validate date format
    const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!dateRegex.test(cleanDate)) {
        if (timeSlotsPicker) {
            timeSlotsPicker.innerHTML = '<div class="text-muted text-center" style="padding: 10px;"><small>Invalid date format. Please select a valid date.</small></div>';
        }
        return;
    }

    timeSlotsPicker.innerHTML = '<div class="text-muted text-center" style="padding: 10px;"><small>Loading available time slots...</small></div>';

    if (!durationMinutes) {
        durationMinutes = 30; // Default duration
    }

    // Fetch appointments and blocked times for the selected date
    fetch(`/admin/notifications/appointments-for-date?date=${cleanDate}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data.success) {
            timeSlotsPicker.innerHTML = '<div class="text-muted text-center" style="padding: 10px;"><small>Error loading time slots</small></div>';
            return;
        }

        const appointments = data.appointments || [];
        const blockedTimes = data.blocked_times || [];

        timeSlotsPicker.innerHTML = '';

        const [year, month, day] = cleanDate.split('-').map(Number);
        const clinicOpenHour = 11; // 11:00 AM
        const clinicCloseHour = 18; // 6:00 PM

        // Helper function to parse datetime strings as LOCAL time (same as admin appointment view)
        function parseLocalDateTime(datetimeStr) {
            if (!datetimeStr || typeof datetimeStr !== 'string') {
                return null;
            }
            try {
                const parts = datetimeStr.split(' ');
                if (parts.length !== 2) {
                    return null;
                }
                const [datePart, timePart] = parts;
                const [y, m, d] = datePart.split('-').map(Number);
                const [hours, minutes, seconds] = timePart.split(':').map(Number);
                if (isNaN(y) || isNaN(m) || isNaN(d) || isNaN(hours) || isNaN(minutes)) {
                    return null;
                }
                return new Date(y, m - 1, d, hours, minutes, seconds || 0);
            } catch (error) {
                return null;
            }
        }

        // Convert blocked times to format compatible with appointments
        const allCalendarItems = [
            ...appointments.map(apt => ({ ...apt, status: apt.status || 'pending' })),
            ...blockedTimes.map(bt => ({ 
                ...bt, 
                status: 'blocked',
                start_datetime: bt.start_datetime,
                end_datetime: bt.end_datetime
            }))
        ];

        // Parse selected date
        const selectedDateObj = new Date(year, month - 1, day);

        // Find blocked times on the selected date
        const blockedTimesOnDate = allCalendarItems.filter(item => {
            const itemDate = parseLocalDateTime(item.start_datetime);
            return item.status === 'blocked' && itemDate && itemDate.toDateString() === selectedDateObj.toDateString();
        });

        // Find booked appointments on the selected date (exclude cancelled and blocked)
        const bookedOnDate = allCalendarItems.filter(item => {
            const itemDate = parseLocalDateTime(item.start_datetime);
            if (!itemDate) return false;
            const statusLower = (item.status || 'pending').toLowerCase();
            return itemDate.toDateString() === selectedDateObj.toDateString() && 
                   statusLower !== 'cancelled' && 
                   item.status !== 'blocked';
        });

        // Generate time slots in 15-minute increments (same logic as admin appointment view)
        let slotIndex = 1;
        for (let hour = clinicOpenHour; hour < clinicCloseHour; hour++) {
            for (let minute = 0; minute < 60; minute += 15) {
                const start = new Date(year, month - 1, day, hour, minute, 0);
                const end = new Date(start.getTime() + durationMinutes * 60000);

                // Skip if end exceeds 18:00 (same as admin appointment view)
                if (end.getHours() > clinicCloseHour || (end.getHours() === clinicCloseHour && end.getMinutes() > 0)) {
                    continue;
                }

                // Check if this time slot conflicts with any blocked time
                // Treat touching the boundary as a conflict (inclusive overlap):
                // overlap if start <= blockedEnd AND end >= blockedStart
                const isBlocked = blockedTimesOnDate.some(blockedTime => {
                    const blockedStart = parseLocalDateTime(blockedTime.start_datetime);
                    const blockedEnd = parseLocalDateTime(blockedTime.end_datetime);
                    if (!blockedStart || !blockedEnd) return false;
                    return (start <= blockedEnd && end >= blockedStart);
                });

                // Check overlap with booked appointments
                const isBooked = bookedOnDate.some(apt => {
                    const aptStart = parseLocalDateTime(apt.start_datetime);
                    const aptEnd = parseLocalDateTime(apt.end_datetime);
                    if (!aptStart || !aptEnd) return false;
                    return (start < aptEnd && end > aptStart);
                });

                const isConflicting = isBlocked || isBooked;

                // Format time for display (e.g., "11:00 AM")
                const timeLabel = start.toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit',
                    hour12: true 
                });

                // Format time for input value (HH:MM)
                const timeValue = `${String(start.getHours()).padStart(2, '0')}:${String(start.getMinutes()).padStart(2, '0')}`;

                // Create time slot button
                const slotBtn = document.createElement('button');
                slotBtn.type = 'button';
                slotBtn.className = 'time-slot-btn' + (isConflicting ? ' disabled' : '') + (isBlocked ? ' blocked' : '');
                slotBtn.textContent = timeLabel;
                slotBtn.dataset.time = timeValue;
                slotBtn.dataset.display = timeLabel;
                
                if (isConflicting) {
                    slotBtn.disabled = true;
                    slotBtn.title = isBlocked ? 'This time slot is blocked' : 'This time slot is already booked';
                }

                // Add click handler (only for non-conflicting slots)
                if (!isConflicting) {
                    slotBtn.addEventListener('click', function() {
                        // Remove selected class from all buttons
                        document.querySelectorAll('.time-slot-btn').forEach(btn => {
                            btn.classList.remove('selected');
                        });
                        
                        // Add selected class to clicked button
                        this.classList.add('selected');
                        
                        // Set the hidden input value
                        timeInput.value = timeValue;
                    });
                }

                timeSlotsPicker.appendChild(slotBtn);
                slotIndex++;
            }
        }

        // If no slots generated, show message
        if (slotIndex === 1) {
            timeSlotsPicker.innerHTML = '<div class="text-muted text-center" style="padding: 10px;"><small>No available time slots for this duration</small></div>';
        }
    })
    .catch(error => {
        console.error('Error fetching appointments:', error);
        timeSlotsPicker.innerHTML = '<div class="text-muted text-center" style="padding: 10px;"><small>Error loading time slots. Please try again.</small></div>';
    });
}

function showApproveModal(requestId, serviceName, duration, hasService, requestType, requestDate) {
    currentRequestId = requestId;
    currentRequestDuration = duration;

    document.getElementById('approveModalServiceName').textContent = 'Service: ' + serviceName;
    document.getElementById('appointmentDuration').value = duration;
    document.getElementById('approveRequestType').value = requestType;
    document.getElementById('approveRequestDate').value = requestDate || '';

    const durationInput = document.getElementById('appointmentDuration');
    const helperText = document.getElementById('durationHelper');
    const dateGroup = document.getElementById('appointmentDateGroup');
    const dateInput = document.getElementById('appointmentDate');
    const timeGroup = document.getElementById('appointmentTimeGroup');
    const timeInput = document.getElementById('appointmentTime');

    // For booking requests, show date and time picker and make duration read-only (unless "Other" service)
    if (requestType === 'book') {
        // Show date and time picker
        dateGroup.style.display = 'block';
        timeGroup.style.display = 'block';
        dateInput.required = true;
        timeInput.required = true;
        
        // Set the date input value (use requested date if available, otherwise today)
        if (requestDate && requestDate.trim()) {
            // Clean the date (remove any extra characters)
            let cleanDate = requestDate.trim();
            if (cleanDate.includes(':')) {
                cleanDate = cleanDate.split(':')[0];
            }
            // Validate and set date
            const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (dateRegex.test(cleanDate)) {
                dateInput.value = cleanDate;
                // Generate time slots for the date
                generateTimeSlotsForBooking(duration, cleanDate);
            } else {
                // Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                dateInput.min = today;
            }
        } else {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        }
        
        // Add event listener for date change
        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                generateTimeSlotsForBooking(duration, selectedDate);
            } else {
                const timeSlotsPicker = document.getElementById('timeSlotsPicker');
                if (timeSlotsPicker) {
                    timeSlotsPicker.innerHTML = '<div class="text-muted text-center" style="padding: 10px;"><small>Please select a date</small></div>';
                }
            }
        });

        // Make duration read-only unless it's "Other" service
        if (hasService) {
            durationInput.readOnly = true;
            durationInput.disabled = true;
            durationInput.classList.add('bg-light');
            durationInput.style.cursor = 'not-allowed';
            durationInput.style.opacity = '0.7';
            helperText.innerHTML = '<strong>Note:</strong> Duration is based on the service from content management and cannot be changed.';
            helperText.classList.remove('text-warning');
            helperText.classList.add('text-info');
        } else {
            // For "Other" service, allow duration editing
            durationInput.readOnly = false;
            durationInput.disabled = false;
            durationInput.classList.remove('bg-light');
            durationInput.style.cursor = '';
            durationInput.style.opacity = '';
            helperText.innerHTML = '<strong>Note:</strong> This is an "Other" service. Please set an appropriate duration.';
            helperText.classList.add('text-warning');
        }
    } else if (requestType === 'reschedule') {
        // Hide time picker for reschedule
        timeGroup.style.display = 'none';
        timeInput.required = false;

    // For reschedule requests, disable duration editing
        durationInput.readOnly = true;
        durationInput.disabled = true;
        durationInput.classList.add('bg-light');
        durationInput.style.cursor = 'not-allowed';
        durationInput.style.opacity = '0.7';
        helperText.innerHTML = '<strong>Note:</strong> Duration is based on the original appointment and cannot be changed for reschedule requests.';
        helperText.classList.remove('text-warning');
        helperText.classList.add('text-info');
    } else {
        // Hide time picker for walk-in
        timeGroup.style.display = 'none';
        timeInput.required = false;

        // For walk-in requests, allow duration editing
        durationInput.readOnly = false;
        durationInput.disabled = false;
        durationInput.classList.remove('bg-light');
        durationInput.style.cursor = '';
        durationInput.style.opacity = '';
        helperText.classList.remove('text-info');

        // Update helper text based on whether it's a predefined service or "Other"
        if (!hasService) {
            helperText.innerHTML = '<strong>Note:</strong> This is an "Other" service. Please set an appropriate duration.';
            helperText.classList.add('text-warning');
        } else {
            helperText.innerHTML = 'Adjust the duration if needed. Default is based on the selected service.';
            helperText.classList.remove('text-warning');
        }
    }

    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

document.getElementById('confirmApproveBtn').addEventListener('click', function() {
    const requestType = document.getElementById('approveRequestType').value;
    const duration = parseInt(document.getElementById('appointmentDuration').value);
    const time = document.getElementById('appointmentTime').value;
    let date = document.getElementById('approveRequestDate').value;
    
    // For booking requests, get date from the date input field
    if (requestType === 'book') {
        const dateInput = document.getElementById('appointmentDate');
        if (dateInput) {
            date = dateInput.value;
        }
    }

    // For booking requests, validate time
    if (requestType === 'book') {
        if (!time || time.trim() === '') {
            showWarningModal('Please select an appointment time');
            return;
        }
        if (!duration || duration < 15 || duration > 480) {
            showWarningModal('Please enter a valid duration between 15 and 480 minutes');
            return;
        }
    } else if (requestType === 'walk-in') {
    // Only validate duration for walk-in requests
        if (!duration || duration < 15 || duration > 480) {
            showWarningModal('Please enter a valid duration between 15 and 480 minutes');
            return;
        }
    }

    approveRequest(currentRequestId, duration, requestType, time, date);
});

function approveRequest(requestId, duration, requestType, time, date) {
    const card = document.querySelector(`[data-request-id="${requestId}"]`);
    const btn = document.getElementById('confirmApproveBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';

    // Build payload based on request type
    const payload = {};
    if (requestType === 'walk-in') {
        payload.duration_minutes = duration;
    } else if (requestType === 'book') {
        payload.duration_minutes = duration;
        payload.appointment_time = time;
        payload.appointment_date = date;
    }

    fetch(`/admin/notifications/approve/${requestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('approveModal'));
            modal.hide();

            // Show success message
            showToast('Success', data.message + ' - Redirecting to appointment...', 'success');

            // Redirect to the appointment calendar with the correct month/year
            // Add a longer delay to ensure database transaction is committed
            setTimeout(() => {
                if (data.appointment && data.appointment.month && data.appointment.year) {
                    // Add timestamp to force cache refresh
                    const timestamp = new Date().getTime();
                    window.location.href = `/admin/appointment?month=${data.appointment.month}&year=${data.appointment.year}&_t=${timestamp}`;
                } else {
                    // Fallback: redirect to current month
                    const timestamp = new Date().getTime();
                    window.location.href = `/admin/appointment?_t=${timestamp}`;
                }
            }, 2000);
        } else {
            throw new Error(data.message || 'Failed to approve request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', error.message, 'danger');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function showDenyModal(requestId) {
    currentRequestId = requestId;
    document.getElementById('denyReason').value = '';
    const errorDiv = document.getElementById('denyReasonError');
    if (errorDiv) errorDiv.style.display = 'none';
    const confirmBtn = document.getElementById('confirmDenyBtn');
    if (confirmBtn) confirmBtn.disabled = true;
    const modal = new bootstrap.Modal(document.getElementById('denyReasonModal'));
    modal.show();
    // Focus textarea after modal shows
    setTimeout(() => document.getElementById('denyReason').focus(), 200);
}

// Enable/disable confirm button based on input and hide error on typing
document.getElementById('denyReason').addEventListener('input', function() {
    const hasText = this.value.trim().length > 0;
    document.getElementById('confirmDenyBtn').disabled = !hasText;
    const errorDiv = document.getElementById('denyReasonError');
    if (errorDiv && hasText) errorDiv.style.display = 'none';
});

document.getElementById('confirmDenyBtn').addEventListener('click', function() {
    const reason = document.getElementById('denyReason').value.trim();

    if (!reason) {
        const errorDiv = document.getElementById('denyReasonError');
        if (errorDiv) errorDiv.style.display = 'block';
        document.getElementById('denyReason').focus();
        return;
    }

    const card = document.querySelector(`[data-request-id="${currentRequestId}"]`);

    this.disabled = true;
    this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';

    fetch(`/admin/notifications/deny/${currentRequestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('denyReasonModal'));
            modal.hide();

            // Animate card removal
            card.style.transition = 'all 0.5s';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';

            setTimeout(() => {
                card.remove();

                // Check if no more requests
                if (document.querySelectorAll('.request-card').length === 0) {
                    location.reload();
                }
            }, 500);

            // Show toast notification
            showToast('Request Denied', data.message, 'warning');
        } else {
            throw new Error(data.message || 'Failed to deny request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', error.message, 'danger');
    })
    .finally(() => {
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirm Denial';
    });
});

// Subtle notification sound function
function playNotificationSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        // Subtle, pleasant notification sound
        oscillator.frequency.value = 800; // Starting frequency
        oscillator.type = 'sine'; // Soft sine wave

        // Fade in and out for subtlety
        gainNode.gain.setValueAtTime(0, audioContext.currentTime);
        gainNode.gain.linearRampToValueAtTime(0.15, audioContext.currentTime + 0.01); // Volume at 15%
        gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.2); // Fade out

        // Play two soft beeps
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);

        // Second beep after a short pause
        setTimeout(() => {
            const oscillator2 = audioContext.createOscillator();
            const gainNode2 = audioContext.createGain();

            oscillator2.connect(gainNode2);
            gainNode2.connect(audioContext.destination);

            oscillator2.frequency.value = 1000; // Slightly higher frequency
            oscillator2.type = 'sine';

            gainNode2.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode2.gain.linearRampToValueAtTime(0.15, audioContext.currentTime + 0.01);
            gainNode2.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.15);

            oscillator2.start(audioContext.currentTime);
            oscillator2.stop(audioContext.currentTime + 0.15);
        }, 150);
    } catch (error) {
        // Fallback: Silent if audio context is not supported or user interaction is required
        console.log('Notification sound unavailable');
    }
}

function showToast(title, message, type) {
    const alertClass = `alert-${type}`;
    const iconMap = {
        'success': 'bi-check-circle',
        'danger': 'bi-x-circle',
        'warning': 'bi-exclamation-triangle'
    };

    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    alert.innerHTML = `
        <i class="bi ${iconMap[type]} me-2"></i>
        <strong>${title}:</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alert);

    // Play notification sound when showing toast
    playNotificationSound();

    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Generic Warning Modal Function
function showWarningModal(message) {
    const modal = new bootstrap.Modal(document.getElementById('genericWarningModal'));
    document.getElementById('genericWarningMessage').textContent = message;
    modal.show();
}
</script>

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

@endsection
