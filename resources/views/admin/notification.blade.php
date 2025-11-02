@extends('layout.admin.app')
@section('content')
<style>
.notifications-container {
    padding: 2rem;
    background: #f8f9fa;
    min-height: calc(100vh - 80px);
}

.notifications-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
}

.notifications-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.notifications-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 1.05rem;
}

.requests-grid {
    display: grid;
    gap: 1.5rem;
}

.request-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border-left: 5px solid #667eea;
}

.request-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
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
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.patient-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    flex-shrink: 0;
}

.patient-details {
    flex: 1;
}

.patient-name {
    font-size: 1.3rem;
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
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.detail-box {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 1rem;
    border-radius: 12px;
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
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
}

.request-reason {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
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
    .request-header {
        flex-direction: column;
        gap: 1rem;
    }

    .patient-info {
        flex-direction: column;
        text-align: center;
    }

    .request-details-grid {
        grid-template-columns: 1fr;
    }

    .request-actions {
        flex-direction: column;
    }

    .btn-approve, .btn-deny {
        width: 100%;
        justify-content: center;
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
        <div class="requests-grid">
            @foreach($pendingRequests as $request)
                <div class="request-card" data-request-id="{{ $request->id }}">
                    <div class="request-header">
                        <span class="request-type-badge {{ $request->request_type }}">
                            <i class="bi {{ $request->isWalkIn() ? 'bi-lightning-charge-fill' : 'bi-arrow-repeat' }}"></i>
                            {{ $request->isWalkIn() ? 'Emergency Walk-in' : 'Reschedule Request' }}
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
                        <div class="detail-box">
                            <div class="detail-label">
                                <i class="bi bi-clock"></i> Requested Time
                            </div>
                            <div class="detail-value">
                                {{ $request->requested_datetime->format('g:i A') }}
                            </div>
                        </div>
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

                    <div class="request-reason">
                        <div class="request-reason-label">
                            <i class="bi bi-chat-left-text"></i> Reason for Request
                        </div>
                        <p class="request-reason-text">{{ $request->reason }}</p>
                    </div>

                    <div class="request-actions">
                        <button class="btn-deny" onclick="showDenyModal({{ $request->id }})">
                            <i class="bi bi-x-circle"></i> Deny
                        </button>
                        <button class="btn-approve" onclick="showApproveModal({{ $request->id }}, '{{ $request->service ? $request->service->service_name : $request->other_concern }}', {{ $request->duration_minutes }}, {{ $request->service_id ? 'true' : 'false' }}, '{{ $request->request_type }}')">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
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

                <!-- Hidden field to track request type -->
                <input type="hidden" id="approveRequestType" value="">

                <div class="alert alert-info mb-0" role="alert" style="border-left: 4px solid #3b82f6;">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> This will create a confirmed appointment and notify the patient.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
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

                <div class="deny-warning-box">
                    <p>
                        <i class="bi bi-info-circle-fill"></i>
                        <span>The patient will be notified of this denial along with the reason you provide.</span>
                    </p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left me-1"></i>Go Back
                </button>
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

function showApproveModal(requestId, serviceName, duration, hasService, requestType) {
    currentRequestId = requestId;
    currentRequestDuration = duration;

    document.getElementById('approveModalServiceName').textContent = 'Service: ' + serviceName;
    document.getElementById('appointmentDuration').value = duration;
    document.getElementById('approveRequestType').value = requestType;

    const durationInput = document.getElementById('appointmentDuration');
    const helperText = document.getElementById('durationHelper');

    // For reschedule requests, disable duration editing
    if (requestType === 'reschedule') {
        durationInput.readOnly = true;
        durationInput.disabled = true;
        durationInput.classList.add('bg-light');
        durationInput.style.cursor = 'not-allowed';
        durationInput.style.opacity = '0.7';
        helperText.innerHTML = '<strong>Note:</strong> Duration is based on the original appointment and cannot be changed for reschedule requests.';
        helperText.classList.remove('text-warning');
        helperText.classList.add('text-info');
    } else {
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

    // Only validate duration for walk-in requests
    if (requestType === 'walk-in') {
        if (!duration || duration < 15 || duration > 480) {
            alert('Please enter a valid duration between 15 and 480 minutes');
            return;
        }
    }

    approveRequest(currentRequestId, duration, requestType);
});

function approveRequest(requestId, duration, requestType) {
    const card = document.querySelector(`[data-request-id="${requestId}"]`);
    const btn = document.getElementById('confirmApproveBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';

    // Only send duration for walk-in requests
    const payload = requestType === 'walk-in' ? { duration_minutes: duration } : {};

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
    const modal = new bootstrap.Modal(document.getElementById('denyReasonModal'));
    modal.show();
}

document.getElementById('confirmDenyBtn').addEventListener('click', function() {
    const reason = document.getElementById('denyReason').value.trim();

    if (!reason) {
        alert('Please provide a reason for denial');
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
</script>
@endsection
