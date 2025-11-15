@extends('layout.auth.app')
@section('content')
<section class="login-container">
    @if (session('error'))
        <x-toast-message type="danger" :message="session('error')" />
    @endif
    @if ($errors->any())
        <x-toast-message type="danger" message="Please fix the errors below" />
    @endif

    <div class="login-card">
        <div class="login-card-content">
            <div class="text-center mb-3">
                <h4 class="fw-bold mb-1">Change Administrator Password</h4>
                <p class="text-secondary mb-2">
                    <i class="bi bi-shield-fill-check me-1 text-primary"></i><strong>Administrator Portal</strong>
                </p>
                <div class="alert alert-info mb-0" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 1px solid #2196F3; color: #1565C0;">
                    <i class="bi bi-shield-exclamation me-2" style="color: #1976D2;"></i>
                    <strong style="color: #1565C0;">Administrator Password Change Required</strong>
                    <p class="mb-0 mt-1 small" style="color: #1565C0;">For security reasons, you must change your password on first login.</p>
                </div>
            </div>

            <form action="{{ route('admin.password.change.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               name="current_password" id="current_password" placeholder="Enter current password" autocomplete="current-password" required>
                    </div>
                    @error('current_password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-key-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                               name="new_password" id="new_password" placeholder="Enter new password" autocomplete="new-password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                            <i class="bi bi-eye" id="toggleNewPasswordIcon"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-danger mt-1 small">* {{ $message }}</p>
                    @enderror
                    <div id="passwordRequirements" class="password-requirements mt-2">
                        <div class="password-requirements-header">
                            <small class="text-muted"><strong>Requirements:</strong></small>
                        </div>
                        <div class="password-requirements-grid">
                            <div id="req-length" class="password-req-item">
                                <i class="bi bi-circle"></i>
                                <span>8+ chars</span>
                            </div>
                            <div id="req-uppercase" class="password-req-item">
                                <i class="bi bi-circle"></i>
                                <span>1 uppercase</span>
                            </div>
                            <div id="req-lowercase" class="password-req-item">
                                <i class="bi bi-circle"></i>
                                <span>1 lowercase</span>
                            </div>
                            <div id="req-number" class="password-req-item">
                                <i class="bi bi-circle"></i>
                                <span>1 number</span>
                            </div>
                            <div id="req-special" class="password-req-item">
                                <i class="bi bi-circle"></i>
                                <span>1 special</span>
                            </div>
                        </div>
                    </div>
                    <div id="passwordStrength" class="mt-2"></div>
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-check2-circle"></i>
                        </span>
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                               name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" autocomplete="new-password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="bi bi-eye" id="toggleConfirmPasswordIcon"></i>
                        </button>
                    </div>
                    @error('new_password_confirmation')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-custom-primary w-100 bg-primary border-primary">
                    <i class="bi bi-shield-lock me-2"></i>Change Password
                </button>

            </form>
        </div>
        <div class="login-card-image">
            <img src="{{ asset('images/tooth.png') }}" alt="Tooth Image">
        </div>
    </div>
    <p class="text-center mt-4 text-secondary">
        &copy; {{ date('Y') }} JValera Dental Clinic. All rights reserved.
    </p>
</section>

<style>
    /* Password Requirements Compact Styles */
    .password-requirements {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.4rem 0.5rem;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    .password-requirements-header {
        margin-bottom: 0.3rem;
    }

    .password-requirements-header small {
        font-size: 0.7rem;
    }

    .password-requirements-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.25rem 0.4rem;
    }

    .password-req-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.65rem;
        color: #6c757d;
        white-space: nowrap;
    }

    .password-req-item i {
        font-size: 0.6rem;
        flex-shrink: 0;
    }

    .password-req-item span {
        white-space: nowrap;
        line-height: 1.1;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Password Strength Indicator */
    #passwordStrength {
        margin-top: 0.5rem;
    }

    #passwordStrength .progress {
        height: 4px;
        border-radius: 2px;
    }

    #passwordStrength small {
        font-size: 0.75rem;
    }

    /* Improved spacing for form */
    .login-card-content .mb-3 {
        margin-bottom: 1rem !important;
    }

    .login-card-content .mb-4 {
        margin-bottom: 1.25rem !important;
    }

    .login-card-content .alert {
        padding: 0.6rem 0.875rem;
        margin-bottom: 0.875rem;
        font-size: 0.875rem;
        border-radius: 8px;
    }

    .login-card-content .alert.alert-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 1px solid #2196F3;
        color: #1565C0;
    }

    .login-card-content .alert strong {
        font-size: 0.9rem;
        color: #1565C0;
    }

    .login-card-content .alert p {
        margin-bottom: 0;
        font-size: 0.8rem;
        line-height: 1.3;
        color: #1565C0;
    }

    .login-card-content .text-center.mb-3 {
        margin-bottom: 1rem !important;
    }

    /* Responsive Styles for Mobile */
    @media screen and (max-width: 768px) {
        .login-card-content {
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .login-card-content form {
            width: 100%;
            max-width: 100%;
        }

        .password-requirements {
            padding: 0.35rem 0.4rem;
            margin-top: 0.5rem;
        }

        .password-requirements-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 0.2rem 0.3rem;
        }

        .password-req-item {
            font-size: 0.6rem;
            gap: 0.2rem;
        }

        .password-req-item i {
            font-size: 0.55rem;
        }

        .password-req-item span {
            font-size: 0.6rem;
        }

        .password-requirements-header {
            margin-bottom: 0.25rem;
        }

        .password-requirements-header small {
            font-size: 0.65rem;
        }

        .login-card-content .mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .login-card-content .mb-4 {
            margin-bottom: 1rem !important;
        }

        .login-card-content .alert {
            padding: 0.6rem 0.75rem;
            margin-bottom: 0.75rem;
        }

        .login-card-content .alert strong {
            font-size: 0.85rem;
        }

        .login-card-content .alert p {
            font-size: 0.75rem;
        }

        .login-card-content h4 {
            font-size: 1.4rem;
            margin-bottom: 0.3rem;
            line-height: 1.3;
        }

        .login-card-content .text-secondary {
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .login-card-content .text-center.mb-3 {
            margin-bottom: 0.875rem !important;
        }

        .btn-custom-primary {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            width: 100%;
        }

        .login-card-content .mt-3 {
            margin-top: 0.75rem !important;
        }

        .login-card-content .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.5rem;
        }

        .login-card-content .d-flex.justify-content-between > * {
            width: 100%;
        }

        #passwordStrength {
            margin-top: 0.4rem;
        }

        #passwordStrength .progress {
            height: 3px;
        }

        #passwordStrength small {
            font-size: 0.7rem;
        }

        .input-group {
            width: 100%;
            position: relative;
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
        }

        .input-group .form-control {
            font-size: 0.9rem;
            padding-right: 2.5rem;
            flex: 1 1 auto;
        }

        .input-group .btn-outline-secondary {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 5;
            border: none;
            background: transparent;
            padding: 0 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            width: auto;
            min-width: auto;
            flex: 0 0 auto;
            pointer-events: auto;
        }

        .input-group .btn-outline-secondary:hover {
            background: transparent;
            color: #495057;
        }

        .input-group .btn-outline-secondary:focus {
            box-shadow: none;
        }

        .form-control {
            font-size: 0.9rem;
        }

        .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.4rem;
        }
    }

    @media screen and (max-width: 480px) {
        .password-requirements {
            padding: 0.3rem 0.35rem;
        }

        .password-requirements-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.2rem 0.25rem;
        }

        .password-req-item {
            font-size: 0.6rem;
            gap: 0.2rem;
        }

        .password-req-item i {
            font-size: 0.55rem;
        }

        .password-req-item span {
            font-size: 0.6rem;
        }

        .password-requirements-header small {
            font-size: 0.65rem;
        }

        .login-card-content .mb-3 {
            margin-bottom: 0.6rem !important;
        }

        .login-card-content .mb-4 {
            margin-bottom: 0.75rem !important;
        }

        .login-card-content h4 {
            font-size: 1.3rem;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .login-card-content .text-secondary {
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
        }

        .btn-custom-primary {
            padding: 0.7rem 1.25rem;
            font-size: 0.875rem;
            width: 100%;
        }

        .login-card-content .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.5rem;
        }

        .login-card-content .d-flex.justify-content-between > * {
            width: 100%;
        }

        .login-card-content .btn-outline-secondary {
            width: 100%;
        }

        .input-group {
            width: 100%;
            position: relative;
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
        }

        .input-group .form-control {
            font-size: 0.875rem;
            padding-right: 2.5rem;
            flex: 1 1 auto;
        }

        .input-group .btn-outline-secondary {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 5;
            border: none;
            background: transparent;
            padding: 0 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            width: auto;
            min-width: auto;
            flex: 0 0 auto;
            pointer-events: auto;
        }

        .input-group .btn-outline-secondary:hover {
            background: transparent;
            color: #495057;
        }

        .input-group .btn-outline-secondary:focus {
            box-shadow: none;
        }

        .form-control {
            font-size: 0.875rem;
            padding: 0.6rem 0.75rem;
        }

        .form-label {
            font-size: 0.85rem;
        }
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure Bootstrap is available
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap is not loaded');
            return;
        }

        // Toggle password visibility
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        if (toggleNewPassword) {
            toggleNewPassword.addEventListener('click', function() {
                const input = document.getElementById('new_password');
                const icon = document.getElementById('toggleNewPasswordIcon');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }
        if (toggleConfirmPassword) {
            toggleConfirmPassword.addEventListener('click', function() {
                const input = document.getElementById('new_password_confirmation');
                const icon = document.getElementById('toggleConfirmPasswordIcon');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }

        // Password validation and strength indicator
        const passwordInput = document.getElementById('new_password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                validatePasswordRequirements(password);
                updatePasswordStrength(password);
            });
        }

        function validatePasswordRequirements(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };

            updateRequirementIcon('req-length', requirements.length);
            updateRequirementIcon('req-uppercase', requirements.uppercase);
            updateRequirementIcon('req-lowercase', requirements.lowercase);
            updateRequirementIcon('req-number', requirements.number);
            updateRequirementIcon('req-special', requirements.special);
        }

        function updateRequirementIcon(id, met) {
            const element = document.getElementById(id);
            if (element) {
                const icon = element.querySelector('i');
                if (icon) {
                    if (met) {
                        icon.classList.remove('bi-circle');
                        icon.classList.add('bi-check-circle-fill', 'text-success');
                        element.style.color = '#28a745';
                    } else {
                        icon.classList.remove('bi-check-circle-fill', 'text-success');
                        icon.classList.add('bi-circle');
                        element.style.color = '#6c757d';
                    }
                }
            }
        }

        function updatePasswordStrength(password) {
            const strengthDiv = document.getElementById('passwordStrength');
            if (!strengthDiv) return;

            if (!password) {
                strengthDiv.innerHTML = '';
                return;
            }

            let strength = 0;
            let checks = 0;

            if (password.length >= 8) {
                strength += 2;
                checks++;
            }
            if (password.length >= 12) {
                strength += 1;
            }
            if (/[a-z]/.test(password)) {
                strength += 1;
                checks++;
            }
            if (/[A-Z]/.test(password)) {
                strength += 1;
                checks++;
            }
            if (/[0-9]/.test(password)) {
                strength += 1;
                checks++;
            }
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 1;
                checks++;
            }

            let strengthText = '';
            let strengthClass = '';
            let progressColor = '';
            let progressPercentage = 0;

            if (checks < 3 || strength < 3) {
                strengthText = 'Weak';
                strengthClass = 'text-danger';
                progressColor = 'bg-danger';
                progressPercentage = Math.min(33, (strength / 6) * 33);
            } else if (checks < 5 || strength < 5) {
                strengthText = 'Average';
                strengthClass = 'text-warning';
                progressColor = 'bg-warning';
                progressPercentage = 33 + ((strength - 3) / 2) * 33;
            } else {
                strengthText = 'Strong';
                strengthClass = 'text-success';
                progressColor = 'bg-success';
                progressPercentage = 66 + ((strength - 5) / 1) * 34;
            }

            strengthDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="${strengthClass} fw-bold">Password Strength: ${strengthText}</small>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px;">
                    <div class="progress-bar ${progressColor}" role="progressbar" 
                         style="width: ${Math.min(100, progressPercentage)}%; transition: width 0.3s ease;" 
                         aria-valuenow="${progressPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            `;
        }
    });
</script>
@endsection

