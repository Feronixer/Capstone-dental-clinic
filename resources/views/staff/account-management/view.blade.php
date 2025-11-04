@extends('layout.staff.app')
@section('content')
@if (session('success'))
    <x-toast-message type="success" :message="session('success')" />
@elseif (session('error'))
    <x-toast-message type="danger" :message="session('error')" />
@endif
<div id="toast-container" class="position-fixed top-0 end-0 px-3" style="z-index: 1055;"></div>
<section>
    <div class="admin-dashboard">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold" style="font-size: 28px; color: #0d6efd;">User Management</h5>
                    </div>
                    <div class="card-body p-4" id="users-table">
                        <!-- Enhanced Controls Section -->
                        <div class="account-controls-section mb-4">
                            <div class="row g-3 align-items-end">
                                <!-- Left: Show Entries -->
                                <div class="col-md-6 col-lg-3">
                                    <form id="per-page-form">
                                        <label class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-list-ol me-1 text-primary"></i>Show Entries
                                        </label>
                                        <select name="per_page" class="form-select form-select-sm">
                                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 entries</option>
                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 entries</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 entries</option>
                                        </select>
                                    </form>
                                </div>

                                <!-- Right: Filter and Search -->
                                <div class="col-md-6 col-lg-9">
                                    <div class="row g-3">
                                        <!-- Filter by Role -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small mb-1">
                                                <i class="bi bi-funnel me-1 text-primary"></i>Filter by Role
                                            </label>
                                            <select id="filter-role" name="role" class="form-select form-select-sm">
                                                <option value="">All Roles</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->role }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Search -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small mb-1">
                                                <i class="bi bi-search me-1 text-primary"></i>Search
                                            </label>
                                            <div class="search-bar-wrapper position-relative">
                                                <input type="text" id="search-input" name="search"
                                                       class="form-control form-control-sm ps-5"
                                                       placeholder="Search by name, email, username..."
                                                       value="{{ request('search') }}">
                                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Enhanced Table Section -->
                        <div class="table-responsive enhanced-table-container" style="min-height: 540px;">
                            <table class="table table-hover align-middle enhanced-table">
                                <thead class="table-header-enhanced">
                                    <tr>
                                        <x-table.th column="id" label="No." center="true" />
                                        <x-table.th column="username" label="Username" />
                                        <x-table.th column="name" label="Name" />
                                        <x-table.th column="email" label="Email (hidden)" />
                                        <th class="text-center">Gender</th>
                                        <x-table.th column="role" label="Role" />
                                        <x-table.th column="created_at" label="Created At" />
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body" class="table-body-enhanced">
                                    @include('staff.partials.users-table', ['users' => $users])
                                </tbody>
                                <tr id="loader" style="display: none;">
                                    <td colspan="8" class="align-middle text-center py-5">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="text-muted">Loading users...</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Enhanced Pagination Section -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-3 border-top" id="users-pagination">
                            <button class="btn btn-primary btn-enhanced-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="bi bi-person-plus-fill me-2"></i>Add User
                            </button>
                            <x-table.pagination :paginator="$users" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('staff.account-management.modal-add-user')
@include('staff.account-management.modal-edit-user')
{{-- Staff cannot delete users --}}
{{-- @include('admin.account-management.change-password-modal') --}}

<!-- View Email Modal (Staff) -->
<div class="modal fade" id="viewEmailModalStaff" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify to view email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="viewEmailFormStaff">
                    <input type="hidden" name="user_id" id="viewEmailUserIdStaff">
                    <div class="mb-3">
                        <label for="verifyPasswordStaff" class="form-label">Your Password</label>
                        <input type="password" class="form-control" id="verifyPasswordStaff" name="password" required>
                    </div>
                    <div class="text-danger small" id="viewEmailErrorStaff" style="display:none;"></div>
                </form>
                <div class="mt-2 small text-muted">For security, enter your password to reveal the patient's email.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmViewEmailBtnStaff">View Email</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm with Password Modal (Staff - Edit only) -->
<div class="modal fade" id="staffConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm with Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="staffConfirmPassword" class="form-label">Your Password</label>
                    <input type="password" class="form-control" id="staffConfirmPassword" autocomplete="current-password" required>
                </div>
                <div class="text-danger small" id="staffConfirmError" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="staffConfirmSubmitBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   ENHANCED ACCOUNT MANAGEMENT STYLES
   Blue Accent Theme: #0d6efd (Staff) / #3498db (Admin)
   ============================================ */

/* Card and Container Enhancements */
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 2px solid #e9ecef;
}

.card-body {
    background: #ffffff;
}

/* Controls Section */
.account-controls-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.account-controls-section .form-label {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    color: #495057;
}

.account-controls-section .form-label i {
    color: #0d6efd;
}

.account-controls-section .form-select-sm,
.account-controls-section .form-control-sm {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.account-controls-section .form-select-sm:focus,
.account-controls-section .form-control-sm:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.search-bar-wrapper i {
    z-index: 10;
    pointer-events: none;
}

/* Enhanced Table Styles */
.enhanced-table-container {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.enhanced-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table-header-enhanced {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.table-header-enhanced th {
    background: transparent !important;
    color: #ffffff !important;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-header-enhanced th .sortable-form .btn-link {
    color: #ffffff !important;
    text-decoration: none;
    transition: all 0.2s ease;
}

.table-header-enhanced th .sortable-form .btn-link:hover {
    color: #dbeafe !important;
    transform: translateY(-1px);
}

.table-header-enhanced th .sortable-form .btn-link.active {
    color: #dbeafe !important;
}

.table-header-enhanced th .text-secondary {
    color: rgba(255, 255, 255, 0.7) !important;
}

.table-body-enhanced tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.table-body-enhanced tr:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.1);
}

.table-body-enhanced td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    font-size: 0.9rem;
    color: #495057;
}

.table-body-enhanced tr:nth-of-type(even) {
    background-color: #ffffff;
}

.table-body-enhanced tr:nth-of-type(odd) {
    background-color: #f8f9fa;
}

/* Enhanced Action Buttons */
.table-body-enhanced .btn {
    width: 38px;
    height: 38px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin: 0 2px;
    transition: all 0.2s ease;
    border: none;
}

.table-body-enhanced .btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: #ffffff;
}

.table-body-enhanced .btn-primary:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
}

.table-body-enhanced .btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: #ffffff;
}

.table-body-enhanced .btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.table-body-enhanced .btn i {
    font-size: 1rem;
}

/* Enhanced Add User Button */
.btn-enhanced-add {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    border-radius: 8px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
}

.btn-enhanced-add:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.btn-enhanced-add:active {
    transform: translateY(0);
}

/* Enhanced Pagination */
#users-pagination .pagination {
    margin-bottom: 0;
}

#users-pagination .pagination .page-link {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    color: #495057;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    transition: all 0.2s ease;
}

#users-pagination .pagination .page-link:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #ffffff;
    transform: translateY(-1px);
}

#users-pagination .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border-color: #0d6efd;
    color: #ffffff;
    font-weight: 600;
}

#users-pagination .pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #adb5bd;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .account-controls-section .row {
        gap: 1rem !important;
    }

    .account-controls-section .col-md-6,
    .account-controls-section .col-lg-3,
    .account-controls-section .col-lg-9 {
        width: 100% !important;
        flex: 0 0 100% !important;
    }

    .enhanced-table-container {
        overflow-x: auto;
    }

    .table-body-enhanced {
        font-size: 0.85rem;
    }

    .table-body-enhanced td {
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
    }

    .btn-enhanced-add {
        width: 100%;
        margin-bottom: 1rem;
    }

    #users-pagination {
        flex-direction: column;
        align-items: stretch !important;
    }

    #users-pagination .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }
}

/* ============================================
   DARK MODE STYLES
   ============================================ */

[data-theme="dark"] .card {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .card-header {
    background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-tertiary, #334155) 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .card-header h5 {
    color: #60a5fa !important;
}

[data-theme="dark"] .card-body {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .account-controls-section {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .account-controls-section .form-label {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .account-controls-section .form-label i {
    color: #60a5fa !important;
}

[data-theme="dark"] .account-controls-section .form-select-sm,
[data-theme="dark"] .account-controls-section .form-control-sm {
    background-color: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-input-border, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .account-controls-section .form-select-sm:focus,
[data-theme="dark"] .account-controls-section .form-control-sm:focus {
    background-color: var(--dm-input-bg, #0f172a) !important;
    border-color: #60a5fa !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.25) !important;
}

[data-theme="dark"] .search-bar-wrapper i {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .enhanced-table-container {
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .table-header-enhanced {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .table-header-enhanced th {
    background: transparent !important;
    color: #ffffff !important;
    border-bottom-color: rgba(255, 255, 255, 0.2) !important;
}

[data-theme="dark"] .table-header-enhanced th .sortable-form .btn-link {
    color: #ffffff !important;
}

[data-theme="dark"] .table-header-enhanced th .sortable-form .btn-link:hover {
    color: #dbeafe !important;
}

[data-theme="dark"] .table-header-enhanced th .sortable-form .btn-link.active {
    color: #dbeafe !important;
}

[data-theme="dark"] .table-header-enhanced th .text-secondary {
    color: rgba(255, 255, 255, 0.7) !important;
}

[data-theme="dark"] .table-body-enhanced tr:nth-of-type(even) {
    background-color: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .table-body-enhanced tr:nth-of-type(odd) {
    background-color: var(--dm-bg-secondary, #1e293b) !important;
}

[data-theme="dark"] .table-body-enhanced tr:hover {
    background-color: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .table-body-enhanced td {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .table-body-enhanced .btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}

[data-theme="dark"] .table-body-enhanced .btn-primary:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
}

[data-theme="dark"] .table-body-enhanced .btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

[data-theme="dark"] .table-body-enhanced .btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
}

[data-theme="dark"] .btn-enhanced-add {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}

[data-theme="dark"] .btn-enhanced-add:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
}

[data-theme="dark"] #users-pagination .pagination .page-link {
    background-color: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #users-pagination .pagination .page-link:hover {
    background-color: #2563eb !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
}

[data-theme="dark"] #users-pagination .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
}

[data-theme="dark"] #users-pagination .pagination .page-item.disabled .page-link {
    background-color: var(--dm-bg-secondary, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
    opacity: 0.6;
}

[data-theme="dark"] #users-pagination {
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #loader .text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Fix form-floating text overlap in dark mode */
[data-theme="dark"] .form-floating > .form-control,
[data-theme="dark"] .form-floating > .form-select {
    background-color: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-input-border, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-floating > .form-control:focus,
[data-theme="dark"] .form-floating > .form-select:focus {
    background-color: var(--dm-input-bg, #0f172a) !important;
    border-color: #60a5fa !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    box-shadow: 0 0 0 0.25rem rgba(96, 165, 250, 0.25) !important;
}

[data-theme="dark"] .form-floating > .form-control::placeholder {
    color: transparent !important;
    opacity: 0 !important;
}

[data-theme="dark"] .form-floating > .form-control:not(:placeholder-shown),
[data-theme="dark"] .form-floating > .form-control:focus {
    padding-top: 1.625rem !important;
    padding-bottom: 0.625rem !important;
}

[data-theme="dark"] .form-floating > .form-control:not(:placeholder-shown) ~ label,
[data-theme="dark"] .form-floating > .form-control:focus ~ label {
    opacity: 0.65 !important;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-floating > label {
    color: var(--dm-text-muted, #94a3b8) !important;
    background-color: transparent !important;
    padding: 0.5rem 0.75rem !important;
    pointer-events: none !important;
    border: 0 !important;
    transform-origin: 0 0 !important;
    height: 100% !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
}

[data-theme="dark"] .form-floating > .form-control:focus ~ label::after,
[data-theme="dark"] .form-floating > .form-select:focus ~ label::after {
    background-color: var(--dm-input-bg, #0f172a) !important;
}

/* Gender select placeholder font size */
#floatingGender option:first-child,
#editFloatingGender option:first-child {
    font-size: 0.875rem;
    color: #6c757d;
}

[data-theme="dark"] #floatingGender option:first-child,
[data-theme="dark"] #editFloatingGender option:first-child {
    color: #94a3b8;
}
</style>

<script>
$(document).ready(function () {
    let isLoading = false;
    let debounceTimer;

    $(document).on('submit', '.sortable-form', function(e) {
        e.preventDefault();
        if (isLoading) return;

        let form = $(this);
        let sort = form.data('sort');
        let direction = form.data('direction');

        $('.sortable-form i')
            .removeClass('bi-arrow-up bi-arrow-down')
            .addClass('bi-arrow-down-up text-secondary');

        let icon = form.find('i');
        if (direction === 'asc') {
            icon.removeClass('bi-arrow-down-up text-secondary')
                .addClass('bi-arrow-up text-primary');
            form.data('direction', 'desc');
        } else {
            icon.removeClass('bi-arrow-down-up text-secondary')
                .addClass('bi-arrow-down text-primary');
            form.data('direction', 'asc');
        }

        let perPage = $('#per-page-form select[name="per_page"]').val();
        let role = $('#filter-role').val();
        let search = $('#search-input').val();

        let url = "{{ route('staff-account-management') }}";
        url += `?per_page=${perPage}&role=${role}&search=${search}&sort=${sort}&direction=${direction}`;
        fetchUsers(url);
    });

    // Pagination link click
    $(document).on('click', '#users-pagination .pagination a', function(e) {
        e.preventDefault();
        if (isLoading) return;

        let url = $(this).attr('href');
        if (url === '#') return;

        fetchUsers(url);
    });

    // Per-page dropdown change
    $(document).on('change', '#per-page-form select[name="per_page"]', function(e) {
        e.preventDefault();
        if (isLoading) return;

        let perPage = $(this).val();
        let role = $('#filter-role').val();
        let search = $('#search-input').val();
        let url = "{{ route('staff-account-management') }}?per_page=" + perPage + "&role=" + role + "&search=" + search;

        fetchUsers(url);
    });

    // Role filter
    $(document).on('change', '#filter-role', function() {
        if (isLoading) return;

        let perPage = $('#per-page-form select[name="per_page"]').val();
        let role = $('#filter-role').val();
        let search = $('#search-input').val();
        let url = "{{ route('staff-account-management') }}?per_page=" + perPage + "&role=" + role + "&search=" + search;

        fetchUsers(url);
    });

    // Search input typing
    $(document).on('keyup', '#search-input', function(e) {
        if (isLoading) return;

        // If Enter key is pressed, search immediately
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            clearTimeout(debounceTimer);

            $("#loader").show();
            $("#users-table-body").hide();

            let perPage = $('#per-page-form select[name="per_page"]').val();
            let role = $('#filter-role').val();
            let search = $('#search-input').val();
            let url = "{{ route('staff-account-management') }}?per_page=" + perPage + "&role=" + role + "&search=" + encodeURIComponent(search);

            fetchUsers(url);
            return;
        }

        // Otherwise, use debounce for automatic search
        clearTimeout(debounceTimer);
        $("#loader").show();
        $("#users-table-body").hide();

        debounceTimer = setTimeout(function() {
            let perPage = $('#per-page-form select[name="per_page"]').val();
            let role = $('#filter-role').val();
            let search = $('#search-input').val();
            let url = "{{ route('staff-account-management') }}?per_page=" + perPage + "&role=" + role + "&search=" + encodeURIComponent(search);

            fetchUsers(url);
        }, 1500);
    });

    function fetchUsers(url) {
        isLoading = true;
        $("#users-pagination .pagination a").addClass("disabled").css("pointer-events", "none");

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function () {
                $("#loader").show();
                $("#users-table-body").hide();
            },
            success: function (response) {
                setTimeout(function() {
                    $("#users-table-body").html(response.html).show();
                    $("#users-pagination").html(response.pagination_html);
                    $("#loader").hide();
                }, 500);
            },
            complete: function () {
                setTimeout(function() {
                    $("#users-pagination .pagination a").removeClass("disabled").css("pointer-events", "auto");
                    isLoading = false;
                }, 500);
            },
            error: function () {
                alert("Failed to load data.");
                $("#loader").hide();
                $("#users-table-body").show();
                $("#users-pagination .pagination a").removeClass("disabled").css("pointer-events", "auto");
                isLoading = false;
            }
        });
    }
    $(document).ready(function () {
        $('#editUserModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let userId = button.data('id');
            console.log(userId);
            let modal = $(this);

            // ilagay sa hidden input ang user id
            modal.find('#modalUserId').val(userId);

            // kuha ng user
            $.get('/staff/account-management/users/' + userId, function (data) {
                modal.find('input[name="username"]').val(data.username);
                modal.find('input[name="first_name"]').val(data.info.first_name);
                modal.find('input[name="middle_name"]').val(data.info.middle_name);
                modal.find('input[name="last_name"]').val(data.info.last_name);
                modal.find('input[name="email"]').val(data.email);
                modal.find('input[name="phone"]').val(data.info.phone);
                modal.find('select[name="role_id"]').val(data.role_id);
                modal.find('select[name="gender"]').val(data.info.gender || '');
                modal.find('input[name="birthday"]').val(data.info.birthday || '');
                // Calculate and display age
                calculateAgeFromBirthday('#editFloatingBirthday', '#editFloatingAge', data.info.birthday);
            });
        });
        //change password
        $('#changePasswordModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let userId = button.data('id');
            $(this).find('#modalChangePasswordUserId').val(userId);
        });
        // Staff cannot delete users - modal removed
    });

    $('#editUserModal').on('hidden.bs.modal', function () {
        let form = $('#editUserForm');
        form.find('.invalid-feedback').text('').hide();
        form.find('.form-control, .form-select').removeClass('is-invalid');
    });

    $('#changePasswordModal').on('hidden.bs.modal', function () {
        let form = $('#editUserForm');
        form.find('.invalid-feedback').text('').hide();
        form.find('.form-control, .form-select').removeClass('is-invalid');
    });


    // Intercept edit submit to require staff password
    let pendingEditPayload = null;
    $('#editUserForm').on('submit', function (e) {
        e.preventDefault();
        const userId = $(this).find('input[name="user_id"]').val();
        pendingEditPayload = {
            userId: userId,
            data: $(this).serializeArray()
        };
        $('#staffConfirmPassword').val('');
        $('#staffConfirmError').hide();
        // Hide parent modal, show confirm in center
        $('#editUserModal').modal('hide');
        new bootstrap.Modal(document.getElementById('staffConfirmModal')).show();
    });

    document.getElementById('staffConfirmSubmitBtn').addEventListener('click', function(){
        const pwd = document.getElementById('staffConfirmPassword').value;
        if (!pwd || !pendingEditPayload) return;
        const modal = bootstrap.Modal.getInstance(document.getElementById('staffConfirmModal'));
        const userId = pendingEditPayload.userId;
        const dataArray = pendingEditPayload.data;
        dataArray.push({ name: 'staff_password', value: pwd });
        $.ajax({
            url: '/staff/account-management/users/' + userId,
            method: 'PUT',
            data: $.param(dataArray),
            success: function(response){
                modal.hide();
                showToast('success', response.message);
                fetchUsers("{{ route('staff-account-management') }}");
            },
            error: function(xhr){
                if (xhr.status === 403) {
                    $('#staffConfirmError').text(xhr.responseJSON?.message || 'Incorrect password.').show();
                } else if (xhr.status === 422) {
                    modal.hide();
                    let errors = xhr.responseJSON.errors;
                    $('#editUserForm .invalid-feedback').text('').hide();
                    $('#editUserForm .form-control, #editUserForm .form-select').removeClass('is-invalid');
                    $.each(errors, function (key, value) {
                        let input = $('#editUserForm').find(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.closest('.form-floating').find('.invalid-feedback').text(value[0]).show();
                    });
                } else {
                    modal.hide();
                    showToast('danger', 'Unexpected error occurred');
                }
            },
            complete: function(){ pendingEditPayload = null; }
        });
    });

    $('#changePasswordForm').on('submit', function (e) {
        e.preventDefault();

        let userId = $(this).find('input[name="user_id"]').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/staff/account-management/users/change-password/' + userId,
            method: 'POST',
            data: formData,
            success: function (response) {
                $('#changePasswordModal').modal('hide');
                showToast('success', response.message);
                fetchUsers("{{ route('staff-account-management') }}");
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('#changePasswordForm .invalid-feedback').text('').hide();
                    $('#changePasswordForm .form-control, #editUserForm .form-select').removeClass('is-invalid');
                    $.each(errors, function (key, value) {
                        let input = $('#changePasswordForm').find(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.closest('.form-floating').find('.invalid-feedback')
                            .text(value[0])
                            .show();
                    });
                } else {
                    showToast('danger', 'Unexpected error occurred');
                }
            }
        });
    });

    // Staff cannot delete users - form handler removed

    // View email toggle + verification
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-email-btn');
        if (!btn) return;
        const userId = btn.getAttribute('data-user-id');
        const span = document.querySelector(`.masked-email[data-user-id="${userId}"]`);
        const icon = btn.querySelector('[data-icon]');
        if (span.getAttribute('data-visible') === '1') {
            span.textContent = '••••••••';
            span.setAttribute('data-visible','0');
            if (icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
            return;
        }
        document.getElementById('viewEmailUserIdStaff').value = userId;
        document.getElementById('verifyPasswordStaff').value = '';
        document.getElementById('viewEmailErrorStaff').style.display = 'none';
        new bootstrap.Modal(document.getElementById('viewEmailModalStaff')).show();
    });

    document.getElementById('confirmViewEmailBtnStaff').addEventListener('click', async function(){
        const userId = document.getElementById('viewEmailUserIdStaff').value;
        const password = document.getElementById('verifyPasswordStaff').value;
        const errorBox = document.getElementById('viewEmailErrorStaff');
        try {
            const res = await fetch(`/staff/account-management/${userId}/reveal-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ password })
            });
            const data = await res.json();
            if (!res.ok || data.success === false) {
                errorBox.textContent = data.message || 'Verification failed.';
                errorBox.style.display = 'block';
                return;
            }
            const span = document.querySelector(`.masked-email[data-user-id="${userId}"]`);
            span.textContent = data.email;
            span.setAttribute('data-visible','1');
            const btn = document.querySelector(`.view-email-btn[data-user-id="${userId}"]`);
            if (btn) { const icon = btn.querySelector('[data-icon]'); if (icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); } }
            bootstrap.Modal.getInstance(document.getElementById('viewEmailModalStaff')).hide();
        } catch(err) {
            errorBox.textContent = 'Something went wrong. Please try again.';
            errorBox.style.display = 'block';
        }
    });

    // Handle add user form submission with AJAX for better error handling
    $(document).on('submit', '#addUserForm', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let form = $(this);

        // Clear previous errors
        form.find('.invalid-feedback').text('').hide();
        form.find('.form-control, .form-select').removeClass('is-invalid');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addUserModal').modal('hide');
                form[0].reset();
                showToast('success', 'Patient account added successfully.');
                fetchUsers("{{ route('staff-account-management') }}");
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    form.find('.invalid-feedback').text('').hide();
                    form.find('.form-control, .form-select').removeClass('is-invalid');
                    $.each(errors, function(key, value) {
                        let input = form.find(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.closest('.form-floating').find('.invalid-feedback')
                            .text(value[0])
                            .show();
                    });
                } else {
                    showToast('danger', 'Unexpected error occurred');
                }
            }
        });
    });

    // Handle Enter key in add user form - using event delegation for dynamic content
    $(document).on('keydown', '#addUserForm input, #addUserForm select', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            $('#addUserForm').trigger('submit');
        }
    });

    // Function to calculate age from birthday
    function calculateAgeFromBirthday(birthdayInputId, ageInputId, birthdayValue) {
        let birthday = birthdayValue || $(birthdayInputId).val();
        if (birthday) {
            const birthDate = new Date(birthday);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            $(ageInputId).val(age);
        } else {
            $(ageInputId).val('');
        }
    }

    // Calculate age when birthday changes in add modal
    $(document).on('change', '#floatingBirthday', function() {
        calculateAgeFromBirthday('#floatingBirthday', '#floatingAge');
    });

    // Calculate age when birthday changes in edit modal
    $(document).on('change', '#editFloatingBirthday', function() {
        calculateAgeFromBirthday('#editFloatingBirthday', '#editFloatingAge');
    });

    // Calculate initial age in add modal if birthday exists
    $(document).ready(function() {
        if ($('#floatingBirthday').val()) {
            calculateAgeFromBirthday('#floatingBirthday', '#floatingAge');
        }
    });

    function showToast(type, message) {
        let toast = `
            <div class="toast align-items-center text-bg-${type} border-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
        $('#toast-container').append(toast);
        let bsToast = new bootstrap.Toast($('#toast-container .toast').last()[0]);
        bsToast.show();
    }
});
</script>
@endsection
