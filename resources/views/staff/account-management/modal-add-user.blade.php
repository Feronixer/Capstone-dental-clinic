<div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content enhanced-add-user-modal">
            <form id="addUserForm" action="/staff/account-management" method="POST">
                @csrf
                <div class="modal-header enhanced-modal-header">
                    <h1 class="modal-title fw-bold" id="addUserModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Create Account
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Progress Indicator -->
                <div class="progress-indicator-container">
                    <div class="progress-indicator">
                        <div class="progress-step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Account</div>
                        </div>
                        <div class="progress-line"></div>
                        <div class="progress-step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Patient Info</div>
                        </div>
                        <div class="progress-line"></div>
                        <div class="progress-step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Security</div>
                        </div>
                    </div>
                </div>

                <div class="modal-body enhanced-modal-body">
                    <!-- Step 1: Account Information -->
                    <div class="form-step active" data-step="1">
                        <div class="form-section">
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
                                <div class="col-md-6">
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
                                <div class="col-md-6">
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
                    </div>

                    <!-- Step 2: Patient Information -->
                    <div class="form-step" data-step="2">
                        <div class="form-section">
                            <div class="section-header mb-3">
                                <i class="bi bi-card-heading me-2"></i>
                                <h6 class="mb-0 fw-semibold">Patient Information</h6>
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
                                                class="form-select @error('gender') is-invalid @enderror"
                                                required>
                                            <option value="" {{ old('gender') === '' ? 'selected' : '' }} class="text-muted small">Select Gender</option>
                                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        <label for="floatingGender">
                                            <i class="bi bi-gender-ambiguous me-1"></i>Gender <span class="text-danger">*</span>
                                        </label>
                                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input name="birthday" type="date"
                                               id="floatingBirthday"
                                               placeholder="Enter birthday"
                                               value="{{ old('birthday') }}"
                                               class="form-control @error('birthday') is-invalid @enderror"
                                               required>
                                        <label for="floatingBirthday">
                                            <i class="bi bi-calendar-event me-1"></i>Birthday <span class="text-danger">*</span>
                                        </label>
                                        @error('birthday')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number"
                                               id="floatingAge"
                                               placeholder="Age"
                                               class="form-control"
                                               readonly
                                               style="background-color: #e9ecef; cursor: not-allowed;">
                                        <label for="floatingAge">
                                            <i class="bi bi-calendar3 me-1"></i>Age <small class="text-muted">(auto-calculated)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Security -->
                    <div class="form-step" data-step="3">
                        <div class="form-section">
                            <div class="section-header mb-3">
                                <i class="bi bi-lock-fill me-2"></i>
                                <h6 class="mb-0 fw-semibold">Security</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating password-input-wrapper">
                                        <input name="password" type="password"
                                               id="floatingPassword"
                                               placeholder="Enter password"
                                               autocomplete="new-password"
                                               class="form-control @error('password') is-invalid @enderror">
                                        <label for="floatingPassword">
                                            <i class="bi bi-key me-1"></i>Password <span class="text-danger">*</span>
                                        </label>
                                        <button type="button" class="password-toggle-btn" data-target="floatingPassword" aria-label="Toggle password visibility">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating password-input-wrapper">
                                        <input name="confirm_password" type="password"
                                               id="floatingConfirmPassword"
                                               placeholder="Confirm password"
                                               autocomplete="new-password"
                                               class="form-control @error('confirm_password') is-invalid @enderror">
                                        <label for="floatingConfirmPassword">
                                            <i class="bi bi-key-fill me-1"></i>Confirm Password <span class="text-danger">*</span>
                                        </label>
                                        <button type="button" class="password-toggle-btn" data-target="floatingConfirmPassword" aria-label="Toggle password visibility">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('confirm_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer enhanced-modal-footer">
                    <div class="step-navigation">
                        <button type="button" class="btn btn-primary btn-back" id="btnBack" style="display: none;">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </button>
                        <button type="button" class="btn btn-primary btn-next" id="btnNext">
                            <span>Next</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-primary btn-submit" id="btnSubmit" style="display: none;">
                            <i class="bi bi-check-circle me-2"></i>Create User
                        </button>
                    </div>
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
    padding: 1rem 1.5rem;
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

/* Progress Indicator */
.progress-indicator-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1rem 1.5rem 0.75rem 1.5rem;
    border-bottom: 2px solid #e9ecef;
}

.progress-indicator {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
    z-index: 2;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border: 3px solid #e9ecef;
}

.step-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
}

.progress-step.active .step-number {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: #ffffff;
    border-color: #0d6efd;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    transform: scale(1.1);
}

.progress-step.active .step-label {
    color: #0d6efd;
    font-weight: 700;
}

.progress-step.completed .step-number {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    border-color: #10b981;
}

.progress-step.completed .step-label {
    color: #10b981;
}

.progress-line {
    flex: 1;
    height: 3px;
    background: #e9ecef;
    margin: 0 0.5rem;
    margin-bottom: 20px;
    position: relative;
    transition: all 0.3s ease;
}

.progress-line.completed {
    background: linear-gradient(90deg, #10b981 0%, #10b981 100%);
}

.progress-line.active {
    background: linear-gradient(90deg, #10b981 0%, #0d6efd 50%, #e9ecef 50%);
}

.enhanced-modal-body {
    padding: 1.25rem 1.5rem;
    background: #ffffff;
    min-height: 400px;
    overflow: hidden;
}

.form-step {
    display: none;
    animation: fadeIn 0.3s ease;
}

.form-step.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.form-section {
    padding: 1rem;
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
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
    color: #495057;
    margin-bottom: 0.75rem;
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

/* Adjust Role select dropdown text position */
#floatingRole {
    padding-top: 1.5rem;
    padding-bottom: 0.5rem;
    line-height: 1.5;
}

/* Adjust Gender select dropdown text position */
#floatingGender {
    padding-top: 1.5rem;
    padding-bottom: 0.5rem;
    line-height: 1.5;
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
    padding: 1rem 1.5rem;
    border-top: 2px solid #e9ecef;
    background: #f8f9fa;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.75rem;
}

.step-navigation {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.btn-back {
    border-radius: 8px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    transition: all 0.2s ease;
    color: #ffffff;
}

.btn-back:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.btn-back:active {
    transform: translateY(0);
}

.btn-next,
.btn-submit {
    border-radius: 8px;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    transition: all 0.2s ease;
    color: #ffffff;
}

.btn-next:hover,
.btn-submit:hover {
    background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.btn-next:active,
.btn-submit:active {
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 768px) {
    .enhanced-add-user-modal .modal-dialog {
        margin: 0.5rem;
    }

    .progress-indicator-container {
        padding: 1rem 0.75rem 0.75rem 0.75rem;
    }

    .step-number {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }

    .step-label {
        font-size: 0.65rem;
    }

    .enhanced-modal-body {
        padding: 1rem;
        min-height: 350px;
    }

    .form-section {
        padding: 1rem;
    }

    .step-navigation {
        width: 100%;
        justify-content: flex-end;
    }
}

/* Dark Mode Styles */
[data-theme="dark"] .progress-indicator-container {
    background: linear-gradient(135deg, var(--dm-bg-secondary, #1e293b) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .step-number {
    background: var(--dm-bg-tertiary, #334155) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .step-label {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .progress-step.active .step-number {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    border-color: #2563eb !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4) !important;
}

[data-theme="dark"] .progress-step.active .step-label {
    color: #60a5fa !important;
}

[data-theme="dark"] .progress-step.completed .step-number {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    border-color: #10b981 !important;
}

[data-theme="dark"] .progress-step.completed .step-label {
    color: #10b981 !important;
}

[data-theme="dark"] .progress-line {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .progress-line.completed {
    background: linear-gradient(90deg, #10b981 0%, #10b981 100%) !important;
}

[data-theme="dark"] .progress-line.active {
    background: linear-gradient(90deg, #10b981 0%, #2563eb 50%, var(--dm-border-color, #334155) 50%) !important;
}

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
    background-color: var(--dm-input-bg, #1e293b) !important;
    border-color: var(--dm-input-border, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .form-floating .form-control:focus,
[data-theme="dark"] .form-floating .form-control:focus-visible,
[data-theme="dark"] .form-floating .form-control:active,
[data-theme="dark"] .form-floating .form-select:focus,
[data-theme="dark"] .form-floating .form-select:focus-visible,
[data-theme="dark"] .form-floating .form-select:active {
    border-color: #60a5fa !important;
    box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.25) !important;
    background-color: #1e293b !important;
    color: #f1f5f9 !important;
    outline: none !important;
}

/* Ensure select dropdown has consistent background in dark mode */
[data-theme="dark"] .form-floating .form-select {
    background-color: #1e293b !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2360a5fa' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px 12px !important;
}

[data-theme="dark"] .form-floating .form-select:focus,
[data-theme="dark"] .form-floating .form-select:focus-visible,
[data-theme="dark"] .form-floating .form-select:active {
    background-color: #1e293b !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2360a5fa' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
    outline: none !important;
}

[data-theme="dark"] .form-floating > .form-control:not(:placeholder-shown) ~ label,
[data-theme="dark"] .form-floating > .form-select:not(:placeholder-shown) ~ label {
    color: var(--dm-text-secondary, #cbd5e1) !important;
}

[data-theme="dark"] .enhanced-modal-footer {
    background: var(--dm-bg-secondary, #1e293b) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .btn-back {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .btn-back:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
}

[data-theme="dark"] .btn-next,
[data-theme="dark"] .btn-submit {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .btn-next:hover,
[data-theme="dark"] .btn-submit:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
}

[data-theme="dark"] #floatingAge {
    background-color: var(--dm-bg-tertiary, #334155) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Gender select placeholder font size */
#floatingGender option:first-child {
    font-size: 0.875rem;
    color: #6c757d;
}

[data-theme="dark"] #floatingGender option:first-child {
    color: #94a3b8;
}

/* Password Toggle Button Styles */
.password-input-wrapper {
    position: relative;
}

.password-toggle-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: color 0.2s ease;
    outline: none;
}

.password-toggle-btn:hover {
    color: #0d6efd;
}

.password-toggle-btn:focus {
    color: #0d6efd;
    outline: none;
}

.password-toggle-btn i {
    font-size: 1.1rem;
}

.password-input-wrapper .form-control {
    padding-right: 3rem;
}

[data-theme="dark"] .password-toggle-btn {
    color: #94a3b8;
}

[data-theme="dark"] .password-toggle-btn:hover {
    color: #60a5fa;
}

[data-theme="dark"] .password-toggle-btn:focus {
    color: #60a5fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form functionality
    let currentStep = 1;
    const totalSteps = 3;
    const formSteps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const progressLines = document.querySelectorAll('.progress-line');
    const btnBack = document.getElementById('btnBack');
    const btnNext = document.getElementById('btnNext');
    const btnSubmit = document.getElementById('btnSubmit');
    const addUserForm = document.getElementById('addUserForm');

    // Password toggle functionality
    const passwordToggleButtons = document.querySelectorAll('.password-toggle-btn');
    
    passwordToggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (passwordInput) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        });
    });

    // Update progress indicator
    function updateProgress() {
        progressSteps.forEach((step, index) => {
            const stepNum = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNum < currentStep) {
                step.classList.add('completed');
            } else if (stepNum === currentStep) {
                step.classList.add('active');
            }
        });

        progressLines.forEach((line, index) => {
            const lineNum = index + 1;
            line.classList.remove('active', 'completed');
            
            if (lineNum < currentStep) {
                line.classList.add('completed');
            } else if (lineNum === currentStep - 1) {
                line.classList.add('active');
            }
        });
    }

    // Show step
    function showStep(step) {
        formSteps.forEach((formStep, index) => {
            if (index + 1 === step) {
                formStep.classList.add('active');
            } else {
                formStep.classList.remove('active');
            }
        });

        // Update buttons
        if (step === 1) {
            btnBack.style.display = 'none';
        } else {
            btnBack.style.display = 'inline-flex';
        }

        if (step === totalSteps) {
            btnNext.style.display = 'none';
            btnSubmit.style.display = 'inline-flex';
        } else {
            btnNext.style.display = 'inline-flex';
            btnSubmit.style.display = 'none';
        }

        updateProgress();
    }

    // Validate current step
    function validateStep(step) {
        const currentFormStep = formSteps[step - 1];
        const requiredFields = currentFormStep.querySelectorAll('[required], .form-control[required], .form-select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value || field.value.trim() === '') {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Special validation for step 1
        if (step === 1) {
            const username = document.getElementById('floatingUsername');
            const role = document.getElementById('floatingRole');
            const email = document.getElementById('floatingEmail');
            const phone = document.getElementById('floatingPhone');

            if (!username.value || !username.value.trim()) {
                username.classList.add('is-invalid');
                isValid = false;
            }
            // Check if role is selected and not the disabled placeholder option
            const selectedRoleOption = role.options[role.selectedIndex];
            if (!role.value || role.value === '' || selectedRoleOption.disabled) {
                role.classList.add('is-invalid');
                isValid = false;
            } else {
                role.classList.remove('is-invalid');
            }
            if (!email.value || !email.value.trim() || !email.value.includes('@')) {
                email.classList.add('is-invalid');
                isValid = false;
            }
            if (!phone.value || !phone.value.trim()) {
                phone.classList.add('is-invalid');
                isValid = false;
            }
        }

        // Special validation for step 2
        if (step === 2) {
            const firstName = document.getElementById('floatingFirstName');
            const lastName = document.getElementById('floatingLastName');
            const gender = document.getElementById('floatingGender');
            const birthday = document.getElementById('floatingBirthday');

            if (!firstName.value || !firstName.value.trim()) {
                firstName.classList.add('is-invalid');
                isValid = false;
            }
            if (!lastName.value || !lastName.value.trim()) {
                lastName.classList.add('is-invalid');
                isValid = false;
            }
            if (!gender.value || gender.value === '') {
                gender.classList.add('is-invalid');
                isValid = false;
            }
            if (!birthday.value || birthday.value === '') {
                birthday.classList.add('is-invalid');
                isValid = false;
            }
        }

        // Special validation for step 3
        if (step === 3) {
            const password = document.getElementById('floatingPassword');
            const confirmPassword = document.getElementById('floatingConfirmPassword');

            if (!password.value || password.value.length < 8) {
                password.classList.add('is-invalid');
                isValid = false;
            }
            if (!confirmPassword.value || confirmPassword.value !== password.value) {
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            }
        }

        return isValid;
    }

    // Next button click
    btnNext.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }
    });

    // Back button click
    btnBack.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Reset form to step 1
    function resetForm() {
        currentStep = 1;
        showStep(1);
        addUserForm.reset();
        
        // Clear all validation errors
        const invalidFields = addUserForm.querySelectorAll('.is-invalid');
        invalidFields.forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        // Reset age field
        const ageInput = document.getElementById('floatingAge');
        if (ageInput) {
            ageInput.value = '';
        }
        
        // Reset progress indicator
        progressSteps.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index === 0) {
                step.classList.add('active');
            }
        });
        
        progressLines.forEach(line => {
            line.classList.remove('active', 'completed');
        });
    }

    // Form submit
    addUserForm.addEventListener('submit', function(e) {
        if (!validateStep(currentStep)) {
            e.preventDefault();
            return false;
        }
        
        // If form is valid, it will submit normally
        // The form will be reset when modal is closed (handled below)
    });

    // Reset form when modal is closed
    const addUserModal = document.getElementById('addUserModal');
    if (addUserModal) {
        addUserModal.addEventListener('hidden.bs.modal', function() {
            resetForm();
        });
    }

    // Calculate age when birthday changes
    const birthdayInput = document.getElementById('floatingBirthday');
    const ageInput = document.getElementById('floatingAge');

    function calculateAge() {
        const birthday = birthdayInput.value;
        if (birthday) {
            const birthDate = new Date(birthday);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            ageInput.value = age;
        } else {
            ageInput.value = '';
        }
    }

    if (birthdayInput) {
        birthdayInput.addEventListener('change', calculateAge);
    }

    // Initialize
    showStep(1);
});
</script>

