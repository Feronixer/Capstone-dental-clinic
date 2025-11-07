@extends('layout.admin.app')

@section('title', 'Staff Activity Logs')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-modern">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-gradient-primary me-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold">Staff Activity Logs</h2>
                        <p class="text-muted mb-0">Monitor and track all staff activities across the system</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card modern-card shadow-lg border-0">
                <div class="card-body p-4">
                    <!-- Filters Section -->
                    <div class="filters-section mb-4">
                        <h5 class="mb-3 fw-semibold"><i class="bi bi-funnel me-2"></i>Filter Options</h5>
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <label for="filterStaff" class="form-label fw-medium">
                                    <i class="bi bi-person-circle me-1"></i>Staff Member
                                </label>
                                <select id="filterStaff" class="form-select modern-select">
                                    <option value="">All Staff</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" {{ request('user_id') == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->info->first_name ?? '' }} {{ $staff->info->last_name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label for="filterModule" class="form-label fw-medium">
                                    <i class="bi bi-grid-3x3-gap me-1"></i>Module
                                </label>
                                <select id="filterModule" class="form-select modern-select">
                                    <option value="">All Modules</option>
                                    <option value="auth" {{ request('module') == 'auth' ? 'selected' : '' }}>Authentication</option>
                                    <option value="appointment" {{ request('module') == 'appointment' ? 'selected' : '' }}>Appointment</option>
                                    <option value="patient_record" {{ request('module') == 'patient_record' ? 'selected' : '' }}>Patient Record</option>
                                    <option value="patient_history" {{ request('module') == 'patient_history' ? 'selected' : '' }}>Patient History</option>
                                    <option value="progress_note" {{ request('module') == 'progress_note' ? 'selected' : '' }}>Progress Note</option>
                                    <option value="blocked_time" {{ request('module') == 'blocked_time' ? 'selected' : '' }}>Blocked Time</option>
                                    <option value="live_chat" {{ request('module') == 'live_chat' ? 'selected' : '' }}>Live Chat</option>
                                    <optgroup label="Content Management">
                                        <option value="announcement" {{ request('module') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="ticker" {{ request('module') == 'ticker' ? 'selected' : '' }}>Ticker Notification</option>
                                        <option value="service" {{ request('module') == 'service' ? 'selected' : '' }}>Service</option>
                                        <option value="mail_template" {{ request('module') == 'mail_template' ? 'selected' : '' }}>Mail Template</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <label for="filterAction" class="form-label fw-medium">
                                    <i class="bi bi-lightning me-1"></i>Action
                                </label>
                                <select id="filterAction" class="form-select modern-select">
                                    <option value="">All Actions</option>
                                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                                    <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                    <option value="viewed" {{ request('action') == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                    <option value="replied" {{ request('action') == 'replied' ? 'selected' : '' }}>Replied</option>
                                    <option value="cleared" {{ request('action') == 'cleared' ? 'selected' : '' }}>Cleared</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <label for="filterDateFrom" class="form-label fw-medium">
                                    <i class="bi bi-calendar-event me-1"></i>Date From
                                </label>
                                <input type="date" id="filterDateFrom" class="form-control modern-input" value="{{ request('date_from') }}">
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <label for="filterDateTo" class="form-label fw-medium">
                                    <i class="bi bi-calendar-check me-1"></i>Date To
                                </label>
                                <input type="date" id="filterDateTo" class="form-control modern-input" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-8">
                                <div class="input-group modern-search position-relative">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchLogs" class="form-control border-start-0 ps-0 pe-5" placeholder="Search by staff name, module, action, or description..." value="{{ request('search') }}">
                                    @if(request('search'))
                                    <button type="button" id="clearSearch" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted" style="z-index: 10; padding: 0.375rem 0.75rem;" title="Clear search">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" id="btnReset" class="btn btn-outline-secondary btn-modern me-2">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </button>
                                <button type="button" id="btnFilter" class="btn btn-primary btn-modern">
                                    <i class="bi bi-funnel-fill me-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Logs Table -->
                    <div class="logs-table-container">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="18%">Staff Member</th>
                                    <th width="12%">Module</th>
                                    <th width="10%">Action</th>
                                    <th width="35%">Description</th>
                                    <th width="15%">Date & Time</th>
                                    <th width="5%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                @forelse($logs as $log)
                                    <tr class="log-row">
                                        <td class="text-center">
                                            <span class="row-number">{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</span>
                                        </td>
                                        <td>
                                            <div class="staff-info">
                                                <div class="avatar-modern">
                                                    {{ strtoupper(substr($log->user->info->first_name ?? 'S', 0, 1)) }}
                                                </div>
                                                <div class="staff-details">
                                                    <div class="staff-name">{{ $log->user->info->first_name ?? '' }} {{ $log->user->info->last_name ?? '' }}</div>
                                                    <div class="staff-email">{{ $log->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $moduleIcons = [
                                                    'appointment' => 'calendar-check',
                                                    'patient_record' => 'file-medical',
                                                    'patient_history' => 'clock-history',
                                                    'progress_note' => 'journal-text',
                                                    'announcement' => 'megaphone',
                                                    'ticker' => 'broadcast',
                                                    'service' => 'tools',
                                                    'mail_template' => 'envelope'
                                                ];
                                                $icon = $moduleIcons[$log->module] ?? 'circle';
                                            @endphp
                                            <span class="module-badge">
                                                <i class="bi bi-{{ $icon }} me-1"></i>
                                                {{ ucwords(str_replace('_', ' ', $log->module)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $actionData = [
                                                    'created' => ['color' => 'success', 'icon' => 'plus-circle'],
                                                    'updated' => ['color' => 'primary', 'icon' => 'pencil-square'],
                                                    'deleted' => ['color' => 'danger', 'icon' => 'trash'],
                                                    'viewed' => ['color' => 'info', 'icon' => 'eye']
                                                ];
                                                $data = $actionData[$log->action] ?? ['color' => 'secondary', 'icon' => 'circle'];
                                            @endphp
                                            <span class="action-badge action-{{ $data['color'] }}">
                                                <i class="bi bi-{{ $data['icon'] }}"></i>
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="log-description">{{ $log->description }}</div>
                                        </td>
                                        <td>
                                            <div class="datetime-info">
                                                <div class="date-text">
                                                    <i class="bi bi-calendar3 me-1"></i>{{ $log->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="time-text">
                                                    <i class="bi bi-clock me-1"></i>{{ $log->created_at->format('h:i A') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-view-modern" onclick="viewLogDetails({{ $log->id }})" title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">
                                            <div class="empty-state-content">
                                                <div class="empty-icon">
                                                    <i class="bi bi-inbox"></i>
                                                </div>
                                                <h5 class="mt-3 mb-2">No Activity Logs Found</h5>
                                                <p class="text-muted">No staff activities match your current filters</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            <i class="bi bi-list-ul me-2"></i>
                            Showing <strong>{{ $logs->firstItem() ?? 0 }}</strong> to <strong>{{ $logs->lastItem() ?? 0 }}</strong> of <strong>{{ $logs->total() }}</strong> entries
                        </div>
                        <div class="pagination-links">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Activity Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Color Palette */
:root {
    --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #0ea5e9;
    --secondary-color: #6b7280;
}

/* Page Header */
.page-header-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    padding: 2rem;
    border-radius: 15px;
    color: white !important;
    box-shadow: 0 10px 40px rgba(59, 130, 246, 0.3);
}

.page-header-modern h2,
.page-header-modern p,
.page-header-modern .text-muted {
    color: white !important;
}

.page-header-modern .icon-circle {
    color: white !important;
}

.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.bg-gradient-primary {
    background: rgba(255, 255, 255, 0.15);
}

/* Modern Card */
.modern-card {
    border-radius: 20px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
}

/* Filters Section */
.filters-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.form-label.fw-medium {
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.modern-select, .modern-input {
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    padding: 0.625rem 1rem;
    transition: all 0.3s ease;
}

.modern-select:focus, .modern-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modern-search {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.modern-search .input-group-text {
    border: 1.5px solid #e5e7eb;
}

.modern-search .form-control {
    border: 1.5px solid #e5e7eb;
}

.modern-search .form-control:focus {
    border-color: #3b82f6;
    box-shadow: none;
}

#clearSearch {
    border: none;
    background: transparent;
    text-decoration: none;
    transition: all 0.2s ease;
}

#clearSearch:hover {
    color: #3b82f6 !important;
    transform: scale(1.1);
}

#clearSearch i {
    font-size: 1rem;
}

/* Modern Buttons */
.btn-modern {
    border-radius: 10px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary.btn-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.btn-primary.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
}

.btn-outline-secondary.btn-modern {
    border: 2px solid #d1d5db;
    color: #6b7280;
}

.btn-outline-secondary.btn-modern:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    transform: translateY(-2px);
}

/* Modern Table */
.logs-table-container {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.modern-table {
    margin-bottom: 0;
}

.modern-table thead {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
}

.modern-table thead th {
    border: none;
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.modern-table tbody tr.log-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f3f4f6;
}

.modern-table tbody tr.log-row:hover {
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(29, 78, 216, 0.05) 100%);
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.modern-table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border: none;
}

/* Row Number */
.row-number {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    background: #f3f4f6;
    border-radius: 8px;
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
}

/* Staff Info */
.staff-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.avatar-modern {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
}

.staff-details {
    flex: 1;
}

.staff-name {
    font-weight: 600;
    color: #111827;
    font-size: 0.9375rem;
    margin-bottom: 0.125rem;
}

.staff-email {
    color: #6b7280;
    font-size: 0.8125rem;
}

/* Module Badge */
.module-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border-radius: 8px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
}

/* Action Badge */
.action-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.8125rem;
    font-weight: 600;
    white-space: nowrap;
}

.action-success {
    background: #d1fae5;
    color: #065f46;
}

.action-primary {
    background: #dbeafe;
    color: #1e40af;
}

.action-danger {
    background: #fee2e2;
    color: #991b1b;
}

.action-info {
    background: #e0e7ff;
    color: #3730a3;
}

.action-secondary {
    background: #f3f4f6;
    color: #4b5563;
}

/* Log Description */
.log-description {
    color: #374151;
    line-height: 1.5;
    font-size: 0.9375rem;
}

/* DateTime Info */
.datetime-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.date-text, .time-text {
    display: flex;
    align-items: center;
    font-size: 0.8125rem;
    color: #6b7280;
}

.date-text {
    font-weight: 600;
    color: #374151;
}

/* View Button */
.btn-view-modern {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: none;
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.btn-view-modern:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5);
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem !important;
    background: #f9fafb;
}

.empty-state-content {
    text-align: center;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
}

.empty-state h5 {
    color: #111827;
    font-weight: 600;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid #f3f4f6;
}

.pagination-info {
    color: #6b7280;
    font-size: 0.9375rem;
}

.pagination-info strong {
    color: #111827;
}

/* Modal Improvements */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border-radius: 20px 20px 0 0;
    padding: 1.5rem;
}

.modal-title {
    font-weight: 700;
}

.btn-close {
    filter: brightness(0) invert(1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header-modern {
        padding: 1.5rem;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
    }

    .filters-section {
        padding: 1rem;
    }

    .modern-table thead th {
        font-size: 0.7rem;
        padding: 0.75rem 0.5rem;
    }

    .modern-table tbody td {
        padding: 1rem 0.5rem;
    }

    .staff-info {
        flex-direction: column;
        align-items: flex-start;
    }

    .pagination-container {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.log-row {
    animation: fadeIn 0.3s ease-out;
}

/* ============================================
   DARK MODE STYLES FOR ACTIVITY LOGS
   ============================================ */

/* Page Header Dark Mode */
[data-theme="dark"] .page-header-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white !important;
}

[data-theme="dark"] .page-header-modern h2,
[data-theme="dark"] .page-header-modern p {
    color: white !important;
}

/* Container Dark Mode */
[data-theme="dark"] .container-fluid {
    background: transparent !important;
}

/* Modern Card Dark Mode */
[data-theme="dark"] .modern-card {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modern-card .card-body {
    background: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
}

/* Filters Section Dark Mode */
[data-theme="dark"] .filters-section {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .filters-section h5 {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .form-label.fw-medium {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modern-select,
[data-theme="dark"] .modern-input {
    background-color: var(--dm-input-bg) !important;
    border-color: var(--dm-input-border) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modern-select:focus,
[data-theme="dark"] .modern-input:focus {
    background-color: var(--dm-input-bg) !important;
    border-color: #3b82f6 !important;
    color: var(--dm-text-primary) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

/* Date input calendar icon - Dark Mode */
[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(2) !important;
    cursor: pointer;
    opacity: 0.9;
}

[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
    filter: invert(1) brightness(2.5) !important;
}

/* Firefox date input calendar icon - Dark Mode */
[data-theme="dark"] input[type="date"]::-moz-calendar-picker-indicator {
    filter: invert(1) brightness(2) !important;
    cursor: pointer;
    opacity: 0.9;
}

[data-theme="dark"] input[type="date"]::-moz-calendar-picker-indicator:hover {
    opacity: 1;
    filter: invert(1) brightness(2.5) !important;
}

[data-theme="dark"] .modern-search .input-group-text {
    background: var(--dm-input-bg) !important;
    border-color: var(--dm-input-border) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modern-search .form-control {
    background: var(--dm-input-bg) !important;
    border-color: var(--dm-input-border) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modern-search .form-control:focus {
    background: var(--dm-input-bg) !important;
    border-color: #3b82f6 !important;
    color: var(--dm-text-primary) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

[data-theme="dark"] .modern-search .form-control::placeholder {
    color: var(--dm-text-muted) !important;
    opacity: 0.7;
}

[data-theme="dark"] .modern-search .input-group-text i {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] #clearSearch {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] #clearSearch:hover {
    color: #60a5fa !important;
}

/* Buttons Dark Mode */
[data-theme="dark"] .btn-primary.btn-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4) !important;
}

[data-theme="dark"] .btn-primary.btn-modern:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6) !important;
}

[data-theme="dark"] .btn-outline-secondary.btn-modern {
    background: transparent !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .btn-outline-secondary.btn-modern:hover {
    background: var(--dm-bg-tertiary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

/* Table Dark Mode */
[data-theme="dark"] .logs-table-container {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modern-table thead {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
}

[data-theme="dark"] .modern-table thead th {
    color: white !important;
    background: transparent !important;
    border: none !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    font-weight: 600 !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
}

[data-theme="dark"] .modern-table tbody tr.log-row {
    background: var(--dm-card-bg) !important;
    border-bottom: 1px solid var(--dm-border-color) !important;
}

[data-theme="dark"] .modern-table tbody tr.log-row:nth-of-type(odd) {
    background: var(--dm-card-bg) !important;
}

[data-theme="dark"] .modern-table tbody tr.log-row:nth-of-type(even) {
    background: var(--dm-bg-secondary) !important;
}

[data-theme="dark"] .modern-table tbody tr.log-row:hover {
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, rgba(29, 78, 216, 0.15) 100%) !important;
}

[data-theme="dark"] .modern-table tbody td {
    background-color: transparent !important;
    color: var(--dm-text-primary) !important;
    border: none !important;
}

/* Row Number Dark Mode */
[data-theme="dark"] .row-number {
    background: var(--dm-bg-tertiary) !important;
    color: white !important;
    font-weight: 600 !important;
    border: 1px solid var(--dm-border-color) !important;
}

/* Staff Info Dark Mode */
[data-theme="dark"] .staff-name {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .staff-email {
    color: var(--dm-text-muted) !important;
}

/* Avatar Dark Mode */
[data-theme="dark"] .avatar-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white !important;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.4) !important;
}

/* Module Badge Dark Mode */
[data-theme="dark"] .module-badge {
    background: var(--dm-bg-tertiary) !important;
    color: white !important;
    border: 1px solid var(--dm-border-color) !important;
    font-weight: 600 !important;
}

[data-theme="dark"] .module-badge i {
    color: white !important;
}

/* Action Badges Dark Mode */
[data-theme="dark"] .action-success {
    background: rgba(16, 185, 129, 0.25) !important;
    color: #6ee7b7 !important;
    border: 1px solid rgba(16, 185, 129, 0.4) !important;
    font-weight: 600 !important;
}

[data-theme="dark"] .action-success i {
    color: #6ee7b7 !important;
}

[data-theme="dark"] .action-primary {
    background: rgba(59, 130, 246, 0.25) !important;
    color: #93c5fd !important;
    border: 1px solid rgba(59, 130, 246, 0.4) !important;
    font-weight: 600 !important;
}

[data-theme="dark"] .action-primary i {
    color: #93c5fd !important;
}

[data-theme="dark"] .action-danger {
    background: rgba(239, 68, 68, 0.25) !important;
    color: #fca5a5 !important;
    border: 1px solid rgba(239, 68, 68, 0.4) !important;
    font-weight: 600 !important;
}

[data-theme="dark"] .action-danger i {
    color: #fca5a5 !important;
}

[data-theme="dark"] .action-info {
    background: rgba(14, 165, 233, 0.25) !important;
    color: #7dd3fc !important;
    border: 1px solid rgba(14, 165, 233, 0.4) !important;
    font-weight: 600 !important;
}

[data-theme="dark"] .action-info i {
    color: #7dd3fc !important;
}

[data-theme="dark"] .action-secondary {
    background: var(--dm-bg-secondary) !important;
    color: var(--dm-text-primary) !important;
    border: 1px solid var(--dm-border-color) !important;
}

/* View Button Dark Mode */
[data-theme="dark"] .btn-view-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4) !important;
}

[data-theme="dark"] .btn-view-modern:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.6) !important;
    transform: scale(1.1) !important;
}

[data-theme="dark"] .btn-view-modern i {
    color: white !important;
}

/* Log Description Dark Mode */
[data-theme="dark"] .log-description {
    color: var(--dm-text-primary) !important;
}

/* DateTime Info Dark Mode */
[data-theme="dark"] .date-text {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .time-text {
    color: var(--dm-text-muted) !important;
}

/* Empty State Dark Mode */
[data-theme="dark"] .empty-state {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .empty-state-content {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .empty-state h5 {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .empty-state .text-muted {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .empty-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white !important;
}

/* Pagination Dark Mode */
[data-theme="dark"] .pagination-container {
    border-top-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .pagination-info {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination-info strong {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination .page-link {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination .page-link:hover {
    background: var(--dm-bg-tertiary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    border-color: #3b82f6 !important;
    color: white !important;
}

[data-theme="dark"] .pagination .page-item.disabled .page-link {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-muted) !important;
}

/* Modal Dark Mode */
[data-theme="dark"] .modal-content {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modal-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: white !important;
}

[data-theme="dark"] .modal-title {
    color: white !important;
}

[data-theme="dark"] .modal-body {
    background: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body p,
[data-theme="dark"] .modal-body strong {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .badge {
    color: inherit !important;
}

[data-theme="dark"] .modal-body .table {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .table thead {
    background: var(--dm-bg-tertiary) !important;
}

[data-theme="dark"] .modal-body .table thead th {
    background: var(--dm-bg-tertiary) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modal-body .table tbody td {
    background: transparent !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .table tbody tr.bg-light td {
    background: var(--dm-bg-secondary) !important;
}

[data-theme="dark"] .modal-body .alert {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .alert-info {
    background: rgba(59, 130, 246, 0.1) !important;
    border-color: #3b82f6 !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .alert-danger {
    background: rgba(239, 68, 68, 0.1) !important;
    border-color: #ef4444 !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .alert strong {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-body .table-light {
    background: var(--dm-bg-tertiary) !important;
}

[data-theme="dark"] .modal-body pre {
    background: var(--dm-bg-secondary) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modal-footer {
    background: var(--dm-bg-tertiary) !important;
    border-top-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modal-footer .btn-secondary {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modal-footer .btn-secondary:hover {
    background: var(--dm-bg-tertiary) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .btn-close {
    filter: brightness(0) invert(1) !important;
    opacity: 0.8;
}

[data-theme="dark"] .btn-close:hover {
    opacity: 1 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('btnFilter');
    const resetBtn = document.getElementById('btnReset');
    const searchInput = document.getElementById('searchLogs');

    // Filter button
    filterBtn.addEventListener('click', applyFilters);

    // Reset button
    resetBtn.addEventListener('click', function() {
        document.getElementById('filterStaff').value = '';
        document.getElementById('filterModule').value = '';
        document.getElementById('filterAction').value = '';
        document.getElementById('filterDateFrom').value = '';
        document.getElementById('filterDateTo').value = '';
        searchInput.value = '';
        window.location.href = '{{ route('admin-activity-logs') }}';
    });

    // Search with debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        // Show loading indicator
        const searchIcon = document.querySelector('.modern-search .input-group-text i');
        searchIcon.className = 'bi bi-arrow-repeat spinner-border spinner-border-sm text-muted';

        searchTimeout = setTimeout(function() {
            applyFilters();
        }, 300); // Reduced debounce time for faster response
    });

    // Allow Enter key to trigger search immediately
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            applyFilters();
        }
    });

    // Clear search button
    const clearSearchBtn = document.getElementById('clearSearch');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            applyFilters();
        });
    }
});

function applyFilters() {
    const params = new URLSearchParams();

    const staff = document.getElementById('filterStaff').value;
    const module = document.getElementById('filterModule').value;
    const action = document.getElementById('filterAction').value;
    const dateFrom = document.getElementById('filterDateFrom').value;
    const dateTo = document.getElementById('filterDateTo').value;
    const search = document.getElementById('searchLogs').value;

    if (staff) params.append('user_id', staff);
    if (module) params.append('module', module);
    if (action) params.append('action', action);
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    if (search) params.append('search', search);

    const url = params.toString() ? '{{ route('admin-activity-logs') }}?' + params.toString() : '{{ route('admin-activity-logs') }}';
    window.location.href = url;
}

function viewLogDetails(logId) {
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    modal.show();

    fetch(`/admin/activity-logs/${logId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const log = data.log;

                // Generate changes comparison
                let changesHtml = '';
                if (log.old_values && log.new_values) {
                    changesHtml = generateChangesComparison(log.old_values, log.new_values);
                } else if (log.new_values) {
                    changesHtml = '<div class="alert alert-info"><strong>New record created</strong> - No previous values to compare</div>';
                }

                const content = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Staff Member:</strong><br>${log.user.info.first_name} ${log.user.info.last_name}</p>
                            <p><strong>Module:</strong><br><span class="badge bg-secondary">${log.module.replace(/_/g, ' ').toUpperCase()}</span></p>
                            <p><strong>Action:</strong><br><span class="badge bg-primary">${log.action.toUpperCase()}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date & Time:</strong><br>${new Date(log.created_at).toLocaleString()}</p>
                            <p><strong>IP Address:</strong><br>${log.ip_address || 'N/A'}</p>
                            <p><strong>Record ID:</strong><br>${log.record_id || 'N/A'}</p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Description:</strong></p>
                    <p class="mb-3">${log.description}</p>
                    ${changesHtml ? `<hr><p><strong>Changes Made:</strong></p>${changesHtml}` : ''}
                `;

                document.getElementById('logDetailsContent').innerHTML = content;
            }
        })
        .catch(error => {
            document.getElementById('logDetailsContent').innerHTML = '<div class="alert alert-danger">Error loading details</div>';
        });
}

function generateChangesComparison(oldValues, newValues) {
    // Fields to ignore (technical/system fields)
    const ignoreFields = ['id', 'created_at', 'updated_at', 'sent_at', 'user_id', 'appointment_id', 'patient_record_id'];

    // Field labels for better readability
    const fieldLabels = {
        // Appointments
        'patient_id': 'Patient',
        'service_id': 'Service',
        'start_datetime': 'Start Date/Time',
        'end_datetime': 'End Date/Time',
        'duration_minutes': 'Duration',
        'status': 'Status',
        'reason_for_visit': 'Reason for Visit',
        'is_new_patient': 'New Patient',
        'original_datetime': 'Original Date/Time',
        'rescheduled_at': 'Rescheduled At',
        // Announcements
        'title': 'Title',
        'content': 'Content',
        'image_path': 'Image',
        'ticker_text': 'Ticker Text',
        'show_ticker': 'Show Ticker',
        // Services
        'service_name': 'Service Name',
        'description': 'Description',
        'price': 'Price',
        'default_duration_minutes': 'Default Duration',
        'icon_class': 'Icon',
        // Mail Templates
        'type': 'Template Type',
        'subject': 'Email Subject',
        'content': 'Email Content',
        // Patient Records
        'patient_number': 'Patient Number',
        'home_address': 'Home Address',
        'date_of_birth': 'Date of Birth',
        'age': 'Age',
        'sex': 'Sex',
        'nickname': 'Nickname',
        'religion': 'Religion',
        'occupation': 'Occupation',
        'contact': 'Contact Number',
        'guardian_name': 'Guardian Name',
        'guardian_contact': 'Guardian Contact',
        'guardian_occupation': 'Guardian Occupation',
        'other_notes': 'Other Notes',
        'notes': 'Notes',
        'medical_history': 'Medical History',
        'health_questions': 'Health Questions',
        'allergies_detail': 'Allergies',
        'is_pregnant': 'Pregnant',
        'is_nursing': 'Nursing',
        'takes_birth_control': 'Takes Birth Control',
        'previous_dentist': 'Previous Dentist',
        'last_dental_visit': 'Last Dental Visit',
        'treatment_done': 'Previous Treatment',
        'physician_name': 'Physician Name',
        'physician_specialty': 'Physician Specialty',
        'procedure_performed': 'Procedure Performed',
        'materials_used': 'Materials Used',
        'anesthesia_used': 'Anesthesia Used',
        'complications': 'Complications',
        'note_date': 'Note Date',
        'progress_description': 'Progress Description',
        'treatment_response': 'Treatment Response',
        'next_steps': 'Next Steps'
    };

    let changesHtml = '<div class="table-responsive"><table class="table table-sm table-bordered">';
    changesHtml += '<thead class="table-light"><tr><th width="30%">Field</th><th width="35%">Old Value</th><th width="35%">New Value</th></tr></thead><tbody>';

    let hasChanges = false;

    // Compare values
    for (let key in newValues) {
        if (ignoreFields.includes(key)) continue;

        const oldVal = oldValues ? oldValues[key] : null;
        const newVal = newValues[key];

        // Skip if values are the same
        if (JSON.stringify(oldVal) === JSON.stringify(newVal)) continue;

        hasChanges = true;
        const label = fieldLabels[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

        changesHtml += '<tr>';
        changesHtml += `<td><strong>${label}</strong></td>`;
        changesHtml += `<td>${formatValue(oldVal)}</td>`;
        changesHtml += `<td class="bg-light"><strong>${formatValue(newVal)}</strong></td>`;
        changesHtml += '</tr>';
    }

    changesHtml += '</tbody></table></div>';

    if (!hasChanges) {
        return '<div class="alert alert-info">No field changes detected</div>';
    }

    return changesHtml;
}

function formatValue(value) {
    if (value === null || value === undefined || value === '') {
        return '<em class="text-muted">Empty</em>';
    }

    if (typeof value === 'boolean') {
        return value ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
    }

    if (typeof value === 'object') {
        // For JSON objects, format nicely
        return '<pre class="mb-0" style="font-size: 0.85rem; max-height: 200px; overflow-y: auto;">' + JSON.stringify(value, null, 2) + '</pre>';
    }

    // Check if it's a date/timestamp
    const strValue = String(value);

    // Match ISO date format or timestamp
    if (strValue.match(/^\d{4}-\d{2}-\d{2}/) || strValue.match(/T\d{2}:\d{2}:\d{2}/)) {
        try {
            const date = new Date(value);
            if (!isNaN(date.getTime())) {
                // Always show date only (no time)
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
        } catch (e) {
            // Not a valid date, continue with normal formatting
        }
    }

    // Limit long text
    if (strValue.length > 100) {
        return strValue.substring(0, 100) + '...';
    }

    return strValue;
}
</script>
@endsection

