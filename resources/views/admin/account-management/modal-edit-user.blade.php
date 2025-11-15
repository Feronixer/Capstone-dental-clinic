<div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content enhanced-edit-user-modal">
            <form id="editUserForm">
                @csrf
                <input type="hidden" name="user_id" id="modalUserId">
                <div class="modal-header enhanced-modal-header">
                    <h1 class="modal-title fw-bold" id="editUserModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit User
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body enhanced-modal-body">
                    <!-- Account Information Section -->
                    <div class="form-section mb-3">
                        <div class="section-header mb-3">
                            <i class="bi bi-person-circle me-2"></i>
                            <h6 class="mb-0 fw-semibold">Account Information</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="username" type="text" class="form-control" id="editFloatingUsername" placeholder="Enter username">
                                    <label for="editFloatingUsername">
                                        <i class="bi bi-at me-1"></i>Username
                                    </label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="email" type="email" class="form-control" id="editFloatingEmail" placeholder="name@example.com">
                                    <label for="editFloatingEmail">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select name="role_id" class="form-select" id="editFloatingRole" disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                        <option disabled>-- Select Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="editFloatingRole">
                                        <i class="bi bi-lock me-1"></i>Role <small class="text-muted">(Locked)</small>
                                    </label>
                                    <input type="hidden" name="role_id" id="editFloatingRoleHidden">
                        <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Information Section -->
                    <div class="form-section mb-3">
                        <div class="section-header mb-3">
                            <i class="bi bi-card-heading me-2"></i>
                            <h6 class="mb-0 fw-semibold">Patient Information</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="first_name" type="text" class="form-control" id="editFloatingFirstName" placeholder="Enter first name">
                                    <label for="editFloatingFirstName">
                                        <i class="bi bi-person me-1"></i>First Name
                                    </label>
                        <div class="invalid-feedback"></div>
                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="middle_name" type="text" class="form-control" id="editFloatingMiddleName" placeholder="Enter middle name">
                                    <label for="editFloatingMiddleName">
                                        <i class="bi bi-person me-1"></i>Middle Name <small class="text-muted">(Optional)</small>
                                    </label>
                        <div class="invalid-feedback"></div>
                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="last_name" type="text" class="form-control" id="editFloatingLastName" placeholder="Enter last name">
                                    <label for="editFloatingLastName">
                                        <i class="bi bi-person me-1"></i>Last Name
                                    </label>
                        <div class="invalid-feedback"></div>
                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select name="gender" class="form-select" id="editFloatingGender" required>
                            <option value="" class="text-muted small">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                                    <label for="editFloatingGender">
                                        <i class="bi bi-gender-ambiguous me-1"></i>Gender <span class="text-danger">*</span>
                                    </label>
                        <div class="invalid-feedback"></div>
                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="birthday" type="date" class="form-control" id="editFloatingBirthday" placeholder="Enter birthday" required>
                                    <label for="editFloatingBirthday">
                                        <i class="bi bi-calendar-event me-1"></i>Birthday <span class="text-danger">*</span>
                                    </label>
                        <div class="invalid-feedback"></div>
                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="editFloatingAge" placeholder="Age" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                                    <label for="editFloatingAge">
                                        <i class="bi bi-calendar3 me-1"></i>Age <small class="text-muted">(auto-calculated)</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section mb-3">
                        <div class="section-header mb-3">
                            <i class="bi bi-telephone me-2"></i>
                            <h6 class="mb-0 fw-semibold">Contact Information</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="phone" type="tel" class="form-control" id="editFloatingPhone" placeholder="09XXXXXXXXX" pattern="09[0-9]{9}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0) { if(!this.value.startsWith('09')) { if(this.value.startsWith('0')) { this.value = '09' + this.value.substring(1).substring(0, 9); } else { this.value = '09' + this.value.substring(0, 9); } } else { this.value = this.value.substring(0, 11); } }">
                                    <label for="editFloatingPhone">
                                        <i class="bi bi-phone me-1"></i>Phone Number
                                    </label>
                                    <small class="text-muted">Format: 09XXXXXXXXX (must start with 09)</small>
                        <div class="invalid-feedback"></div>
                    </div>
                            </div>
                    </div>
                    </div>
                </div>

                <div class="modal-footer enhanced-modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Enhanced Edit User Modal Styles */
.enhanced-edit-user-modal {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.enhanced-modal-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: #ffffff;
    padding: 1.25rem 1.5rem;
    border-bottom: none;
}

.enhanced-modal-header .modal-title {
    font-size: 1.25rem;
    margin: 0;
    display: flex;
    align-items: center;
}

.enhanced-modal-header .btn-close-white {
    opacity: 0.9;
    filter: brightness(0) invert(1);
}

.enhanced-modal-header .btn-close-white:hover {
    opacity: 1;
}

.enhanced-modal-body {
    padding: 1.5rem;
    background: #ffffff;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.form-section {
    padding: 1.25rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.form-section:hover {
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
    border-color: #dee2e6;
}

.section-header {
    display: flex;
    align-items: center;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
    color: #495057;
}

.section-header i {
    color: #0d6efd;
    font-size: 1.1rem;
}

.section-header h6 {
    color: #212529;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}

.form-floating label {
    font-size: 0.875rem;
    color: #495057;
    font-weight: 500;
}

.form-floating label i {
    color: #0d6efd;
    margin-right: 0.25rem;
}

.form-floating .form-control,
.form-floating .form-select {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-floating .form-control:focus,
.form-floating .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select:not(:placeholder-shown) ~ label {
    color: #495057;
    opacity: 0.85;
}

.enhanced-modal-footer {
    padding: 1.25rem 1.5rem;
    border-top: 2px solid #e9ecef;
    background: #f8f9fa;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.btn-cancel {
    border-radius: 8px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    border: 2px solid #dee2e6;
    transition: all 0.2s ease;
}

.btn-cancel:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #ffffff;
    transform: translateY(-1px);
}

.btn-save {
    border-radius: 8px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    transition: all 0.2s ease;
}

.btn-save:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.btn-save:active {
    transform: translateY(0);
}

/* Adjust Role and Gender select dropdown text position */
#editFloatingRole,
#editFloatingGender {
    padding-top: 1.5rem;
    padding-bottom: 0.5rem;
    line-height: 1.5;
}

/* Disabled Role Field Styling */
#editFloatingRole:disabled {
    background-color: #e9ecef !important;
    cursor: not-allowed !important;
    opacity: 0.7;
}

#editFloatingRole:disabled option {
    color: #6c757d;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .enhanced-edit-user-modal .modal-dialog {
        max-width: 95%;
        margin: 0.5rem;
    }
}

@media (max-width: 768px) {
    .enhanced-modal-body {
        padding: 1rem;
    }

    .form-section {
        padding: 1rem;
    }

    .row.g-3 > * {
        margin-bottom: 0.75rem;
    }

    .enhanced-modal-footer {
        flex-direction: column;
    }

    .enhanced-modal-footer .btn {
        width: 100%;
    }
}

/* Dark Mode Styles */
[data-theme="dark"] .enhanced-modal-body {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-section {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .form-section:hover {
    border-color: #60a5fa !important;
    box-shadow: 0 4px 12px rgba(96, 165, 250, 0.15) !important;
}

[data-theme="dark"] .section-header {
    border-bottom-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .section-header h6 {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .section-header i {
    color: #60a5fa !important;
}

[data-theme="dark"] .form-floating label {
    color: var(--dm-text-secondary, #cbd5e1) !important;
}

[data-theme="dark"] .form-floating label i {
    color: #60a5fa !important;
}

[data-theme="dark"] .form-floating .form-control,
[data-theme="dark"] .form-floating .form-select {
    background-color: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-input-border, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-floating .form-control:focus,
[data-theme="dark"] .form-floating .form-select:focus {
    border-color: #60a5fa !important;
    box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.25) !important;
    background-color: var(--dm-input-bg, #0f172a) !important;
}

[data-theme="dark"] .form-floating > .form-control:not(:placeholder-shown) ~ label,
[data-theme="dark"] .form-floating > .form-select:not(:placeholder-shown) ~ label {
    color: var(--dm-text-secondary, #cbd5e1) !important;
}

[data-theme="dark"] .enhanced-modal-footer {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .btn-cancel {
    background-color: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .btn-cancel:hover {
    background-color: var(--dm-bg-tertiary, #334155) !important;
    border-color: #60a5fa !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .btn-save {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .btn-save:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
}

[data-theme="dark"] #editFloatingAge {
    background-color: var(--dm-bg-tertiary, #334155) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] #editFloatingGender option:first-child,
[data-theme="dark"] #editFloatingRole option:first-child {
    color: #94a3b8;
}

/* Dark Mode Disabled Role Field */
[data-theme="dark"] #editFloatingRole:disabled {
    background-color: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
    cursor: not-allowed !important;
    opacity: 0.7;
}

[data-theme="dark"] #editFloatingRole:disabled option {
    color: var(--dm-text-muted, #94a3b8);
    background-color: var(--dm-bg-tertiary, #334155);
}
</style>
