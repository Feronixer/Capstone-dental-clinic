<div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content enhanced-add-user-modal">
            <form action="/staff/account-management" method="POST">
                @csrf
                <div class="modal-header enhanced-modal-header">
                    <h1 class="modal-title fw-bold" id="addUserModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Add New User
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body enhanced-modal-body">
                    <!-- Account Information Section -->
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <i class="bi bi-person-circle me-2"></i>
                            <h6 class="mb-0 fw-semibold">Account Information</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="username" type="text"
                                           id="floatingUsername"
                                           placeholder="Enter username"
                                           value="{{ old('username') }}"
                                           class="form-control @error('username') is-invalid @enderror">
                                    <label for="floatingUsername">
                                        <i class="bi bi-at me-1"></i>Username <span class="text-danger">*</span>
                                    </label>
                                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="role_id"
                                            id="floatingRole"
                                            class="form-select @error('role_id') is-invalid @enderror">
                                        <option disabled {{ old('role_id') ? '' : 'selected' }}>-- Select Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="floatingRole">
                                        <i class="bi bi-shield-check me-1"></i>Role <span class="text-danger">*</span>
                                    </label>
                                    @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input name="email" type="email"
                                           id="floatingEmail"
                                           placeholder="name@example.com"
                                           value="{{ old('email') }}"
                                           class="form-control @error('email') is-invalid @enderror">
                                    <label for="floatingEmail">
                                        <i class="bi bi-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                    </label>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <i class="bi bi-card-heading me-2"></i>
                            <h6 class="mb-0 fw-semibold">Personal Information</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="first_name" type="text"
                                           id="floatingFirstName"
                                           placeholder="Enter first name"
                                           value="{{ old('first_name') }}"
                                           class="form-control @error('first_name') is-invalid @enderror">
                                    <label for="floatingFirstName">
                                        <i class="bi bi-person me-1"></i>First Name <span class="text-danger">*</span>
                                    </label>
                                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="middle_name" type="text"
                                           id="floatingMiddleName"
                                           placeholder="Enter middle name"
                                           value="{{ old('middle_name') }}"
                                           class="form-control">
                                    <label for="floatingMiddleName">
                                        <i class="bi bi-person me-1"></i>Middle Name <small class="text-muted">(Optional)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input name="last_name" type="text"
                                           id="floatingLastName"
                                           placeholder="Enter last name"
                                           value="{{ old('last_name') }}"
                                           class="form-control @error('last_name') is-invalid @enderror">
                                    <label for="floatingLastName">
                                        <i class="bi bi-person me-1"></i>Last Name <span class="text-danger">*</span>
                                    </label>
                                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select name="gender"
                                            id="floatingGender"
                                            class="form-select @error('gender') is-invalid @enderror">
                                        <option value="" {{ old('gender') === '' ? 'selected' : '' }}>Select Gender</option>
                                        <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <label for="floatingGender">
                                        <i class="bi bi-gender-ambiguous me-1"></i>Gender <small class="text-muted">(Optional)</small>
                                    </label>
                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <i class="bi bi-telephone me-2"></i>
                            <h6 class="mb-0 fw-semibold">Contact Information</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input name="phone" type="text"
                                           id="floatingPhone"
                                           placeholder="Enter phone number"
                                           value="{{ old('phone') }}"
                                           class="form-control @error('phone') is-invalid @enderror">
                                    <label for="floatingPhone">
                                        <i class="bi bi-phone me-1"></i>Phone Number <span class="text-danger">*</span>
                                    </label>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="form-section mb-3">
                        <div class="section-header mb-3">
                            <i class="bi bi-lock-fill me-2"></i>
                            <h6 class="mb-0 fw-semibold">Security</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="password" type="password"
                                           id="floatingPassword"
                                           placeholder="Enter password"
                                           class="form-control @error('password') is-invalid @enderror">
                                    <label for="floatingPassword">
                                        <i class="bi bi-key me-1"></i>Password <span class="text-danger">*</span>
                                    </label>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="confirm_password" type="password"
                                           id="floatingConfirmPassword"
                                           placeholder="Confirm password"
                                           class="form-control @error('confirm_password') is-invalid @enderror">
                                    <label for="floatingConfirmPassword">
                                        <i class="bi bi-key-fill me-1"></i>Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    @error('confirm_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer enhanced-modal-footer">
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="bi bi-check-circle me-2"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Enhanced Add User Modal Styles */
.enhanced-add-user-modal {
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

/* Responsive Design */
@media (max-width: 768px) {
    .enhanced-add-user-modal .modal-dialog {
        margin: 0.5rem;
    }

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
</style>

