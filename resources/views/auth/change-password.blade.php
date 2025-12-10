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
                <h4 class="fw-bold mb-1">Change Your Password</h4>
                <p class="text-secondary mb-2">Patient Portal</p>
                <div class="alert alert-info mb-0" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 1px solid #2196F3; color: #1565C0;">
                    <i class="bi bi-shield-exclamation me-2" style="color: #1976D2;"></i>
                    <strong style="color: #1565C0;">Password Change Required</strong>
                    <p class="mb-0 mt-1 small" style="color: #1565C0;">For your security, you must change your password on first login.</p>
                </div>
            </div>

            <form id="passwordChangeForm" action="{{ route('password.change.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <div class="input-group position-relative">
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                               name="new_password" id="new_password" placeholder="Enter new password" autocomplete="new-password" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password" aria-label="Show password">
                            <i class="bi bi-eye"></i>
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
                    <div class="input-group position-relative">
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                               name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" autocomplete="new-password" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password_confirmation" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('new_password_confirmation')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <button type="button" class="btn-custom-primary" id="verifyPasswordBtn">
                    <i class="bi bi-shield-lock me-2"></i>Change Password
                </button>

                <div class="mt-3">
                    <a href="{{ route('patient-profile') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
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

<!-- Error/Validation Modal -->
<div class="modal fade" id="validationErrorModal" tabindex="-1" aria-labelledby="validationErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-body p-0">
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 2.5rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: white; border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                        <i class="bi bi-exclamation-circle-fill" style="color: #ef4444; font-size: 3rem;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2" id="validationErrorTitle" style="font-size: 1.5rem;">Validation Error</h4>
                    <p class="text-white mb-4" id="validationErrorMessage" style="font-size: 1rem; line-height: 1.6; opacity: 0.95;">Please check your input</p>
                    <button type="button" class="btn btn-light fw-bold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 25px; transition: all 0.3s ease;">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Notification Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 10000;">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong id="successToastMessage">Success!</strong>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Password Verification Modal -->
<div class="modal fade" id="passwordVerificationModal" tabindex="-1" aria-labelledby="passwordVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); color: white; border-bottom: none;">
                <h5 class="modal-title" id="passwordVerificationModalLabel" style="color: white;">
                    <i class="bi bi-shield-lock me-2"></i>Email Verification Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div id="sendCodeSection">
                    <p class="mb-3" style="color: #333;">We'll send a verification code to your email address to verify your identity before changing your password.</p>
                    <div class="alert alert-info" style="background: #e3f2fd; border: 1px solid #1976D2; color: #1565C0; padding: 1rem; border-radius: 8px;">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Email:</strong> {{ Auth::user()->email }}
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <button type="button" class="btn btn-primary" id="sendVerificationCodeBtn" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); border: none; padding: 0.875rem 2rem; border-radius: 8px;">
                            <i class="bi bi-envelope me-2"></i>Send Verification Code
                        </button>
                    </div>
                </div>
                <div id="verifyCodeSection" style="display: none;">
                    <p class="mb-3" style="color: #333;">A verification code has been sent to your email address. Please enter the code below:</p>
                    <div class="mb-3">
                        <label for="verificationCode" class="form-label fw-semibold" style="color: #333;">Verification Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-center" id="verificationCode" placeholder="000000" maxlength="6" style="font-size: 1.5rem; letter-spacing: 0.5rem; font-weight: bold; padding: 1rem; border: 2px solid #1976D2; border-radius: 8px;">
                        <small class="text-muted" style="display: block; margin-top: 0.5rem;">Enter the 6-digit code sent to your email</small>
                        <div class="invalid-feedback" id="verificationCodeError" style="display: block; color: #ef5350; font-size: 0.875rem; margin-top: 0.25rem;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e3f2fd; padding: 1.5rem 2rem;">
                <button type="button" class="btn btn-outline-secondary" id="resendCodeBtn" style="display: none; padding: 0.875rem 2rem; border-radius: 8px;">
                    <i class="bi bi-arrow-clockwise me-2"></i>Resend Code
                </button>
                <button type="button" class="btn btn-primary" id="verifyCodeBtn" style="display: none; background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); border: none; padding: 0.875rem 2rem; border-radius: 8px;">
                    <i class="bi bi-check-circle me-2"></i>Verify & Continue
                </button>
            </div>
        </div>
    </div>
</div>

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

    .login-card-content .login-logo {
        margin-bottom: 0.75rem !important;
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

        .input-group .toggle-password {
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

        .input-group .toggle-password:hover {
            background: transparent;
            color: #495057;
        }

        .input-group .toggle-password:focus {
            box-shadow: none;
        }

        .form-control {
            font-size: 0.9rem;
        }

        .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.4rem;
        }

        .btn-outline-secondary {
            width: 100%;
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

        .input-group .toggle-password {
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

        .input-group .toggle-password:hover {
            background: transparent;
            color: #495057;
        }

        .input-group .toggle-password:focus {
            box-shadow: none;
        }

        .form-control {
            font-size: 0.875rem;
            padding: 0.6rem 0.75rem;
        }

        .form-label {
            font-size: 0.85rem;
        }

        .btn-outline-secondary {
            width: 100%;
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

        // Toggle password visibility for any eye button
        document.querySelectorAll('.toggle-password').forEach(function(btn){
            btn.addEventListener('click', function(){
                const input = document.querySelector(this.getAttribute('data-target'));
                if(!input) return;
                const icon = this.querySelector('i');
                const isPwd = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPwd ? 'text' : 'password');
                if(icon){
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                }
                this.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
            });
        });

        // Password Change Verification
        const passwordVerificationModal = new bootstrap.Modal(document.getElementById('passwordVerificationModal'));
        const validationErrorModal = new bootstrap.Modal(document.getElementById('validationErrorModal'));
        const verifyPasswordBtn = document.getElementById('verifyPasswordBtn');
        const sendCodeSection = document.getElementById('sendCodeSection');
        const verifyCodeSection = document.getElementById('verifyCodeSection');
        const sendVerificationCodeBtn = document.getElementById('sendVerificationCodeBtn');
        const verifyCodeBtn = document.getElementById('verifyCodeBtn');
        const resendCodeBtn = document.getElementById('resendCodeBtn');
        const verificationCodeInput = document.getElementById('verificationCode');
        const verificationCodeError = document.getElementById('verificationCodeError');
        const passwordChangeForm = document.getElementById('passwordChangeForm');
        const validationErrorTitle = document.getElementById('validationErrorTitle');
        const validationErrorMessage = document.getElementById('validationErrorMessage');

        // Function to show validation error modal
        function showValidationError(title, message) {
            validationErrorTitle.textContent = title || 'Validation Error';
            validationErrorMessage.textContent = message || 'Please check your input';
            validationErrorModal.show();
        }

        // Function to show success toast
        function showSuccessToast(message) {
            const toastElement = document.getElementById('successToast');
            const toastMessage = document.getElementById('successToastMessage');
            toastMessage.textContent = message || 'Success!';
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 3000
            });
            toast.show();
        }

        // Show verification modal when clicking Change Password
        if (verifyPasswordBtn) {
            verifyPasswordBtn.addEventListener('click', function() {
                const newPassword = document.getElementById('new_password').value.trim();
                const confirmPassword = document.getElementById('new_password_confirmation').value.trim();

                // Validate password fields before showing modal
                if (!newPassword || !confirmPassword) {
                    showValidationError('Password Required', 'Please fill in both password fields before proceeding.');
                    return;
                }

                // Check password requirements
                const requirements = {
                    length: newPassword.length >= 8,
                    uppercase: /[A-Z]/.test(newPassword),
                    lowercase: /[a-z]/.test(newPassword),
                    number: /[0-9]/.test(newPassword),
                    special: /[^A-Za-z0-9]/.test(newPassword)
                };

                if (!requirements.length) {
                    showValidationError('Password Too Short', 'Password must be at least 8 characters long.');
                    document.getElementById('new_password').focus();
                    return;
                }
                if (!requirements.uppercase) {
                    showValidationError('Password Requirements', 'Password must contain at least one uppercase letter.');
                    document.getElementById('new_password').focus();
                    return;
                }
                if (!requirements.lowercase) {
                    showValidationError('Password Requirements', 'Password must contain at least one lowercase letter.');
                    document.getElementById('new_password').focus();
                    return;
                }
                if (!requirements.number) {
                    showValidationError('Password Requirements', 'Password must contain at least one number.');
                    document.getElementById('new_password').focus();
                    return;
                }
                if (!requirements.special) {
                    showValidationError('Password Requirements', 'Password must contain at least one special character.');
                    document.getElementById('new_password').focus();
                    return;
                }

                if (newPassword !== confirmPassword) {
                    showValidationError('Passwords Do Not Match', 'Please make sure both passwords are the same.');
                    document.getElementById('new_password_confirmation').focus();
                    return;
                }

                verificationCodeInput.value = '';
                verificationCodeError.textContent = '';
                verificationCodeError.style.display = 'none';
                verificationCodeInput.classList.remove('is-invalid');
                sendCodeSection.style.display = 'block';
                verifyCodeSection.style.display = 'none';
                verifyCodeBtn.style.display = 'none';
                passwordVerificationModal.show();
            });
        }

        // Send verification code
        if (sendVerificationCodeBtn) {
            sendVerificationCodeBtn.addEventListener('click', function() {
                const newPassword = document.getElementById('new_password').value.trim();
                const confirmPassword = document.getElementById('new_password_confirmation').value.trim();

                // Validate password fields again before sending code
                if (!newPassword || !confirmPassword) {
                    passwordVerificationModal.hide();
                    showValidationError('Password Required', 'Please fill in both password fields before sending verification code.');
                    return;
                }

                // Check password requirements
                const requirements = {
                    length: newPassword.length >= 8,
                    uppercase: /[A-Z]/.test(newPassword),
                    lowercase: /[a-z]/.test(newPassword),
                    number: /[0-9]/.test(newPassword),
                    special: /[^A-Za-z0-9]/.test(newPassword)
                };

                if (!requirements.length) {
                    passwordVerificationModal.hide();
                    showValidationError('Password Too Short', 'Password must be at least 8 characters long.');
                    document.getElementById('new_password').focus();
                    return;
                }
                if (!requirements.uppercase || !requirements.lowercase || !requirements.number || !requirements.special) {
                    passwordVerificationModal.hide();
                    showValidationError('Password Requirements', 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
                    document.getElementById('new_password').focus();
                    return;
                }

                if (newPassword !== confirmPassword) {
                    passwordVerificationModal.hide();
                    showValidationError('Passwords Do Not Match', 'Please make sure both passwords are the same.');
                    document.getElementById('new_password_confirmation').focus();
                    return;
                }

                const btn = this;
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';

                const formData = new FormData();
                formData.append('send_code', 'true');
                formData.append('new_password', newPassword);
                formData.append('new_password_confirmation', confirmPassword);

                fetch('{{ route("password.change.submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    // Try to parse JSON response
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        // If response is not JSON, create error object
                        throw new Error('Server returned an invalid response. Please try again.');
                    }

                    if (!response.ok) {
                        // Handle error responses
                        let errorMessage = data.message || 'Failed to send verification code';
                        if (data.errors) {
                            const errorMessages = [];
                            for (const field in data.errors) {
                                errorMessages.push(data.errors[field][0]);
                            }
                            errorMessage = errorMessages.join('. ');
                        }
                        throw new Error(errorMessage);
                    }

                    return data;
                })
                .then(data => {
                    if (data.success) {
                        showSuccessToast('Verification code sent to your email!');
                        sendCodeSection.style.display = 'none';
                        verifyCodeSection.style.display = 'block';
                        verifyCodeBtn.style.display = 'block';
                        resendCodeBtn.style.display = 'block';
                        verificationCodeInput.focus();
                    } else {
                        // Handle validation errors
                        let errorMessage = data.message || 'Failed to send verification code';
                        if (data.errors) {
                            const errorMessages = [];
                            for (const field in data.errors) {
                                errorMessages.push(data.errors[field][0]);
                            }
                            errorMessage = errorMessages.join('. ');
                        }
                        passwordVerificationModal.hide();
                        showValidationError('Error', errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Error sending verification code:', error);
                    passwordVerificationModal.hide();
                    showValidationError('Error', error.message || 'Failed to send verification code. Please check your internet connection and try again.');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            });
        }

        // Resend verification code
        if (resendCodeBtn) {
            resendCodeBtn.addEventListener('click', function() {
                sendVerificationCodeBtn.click();
            });
        }

        // Verify code
        if (verifyCodeBtn) {
            verifyCodeBtn.addEventListener('click', function() {
                const code = verificationCodeInput.value.trim();
                
                if (!code || code.length !== 6) {
                    verificationCodeInput.classList.add('is-invalid');
                    verificationCodeError.textContent = 'Please enter a valid 6-digit code';
                    verificationCodeError.style.display = 'block';
                    return;
                }

                // Prevent multiple clicks
                if (verifyCodeBtn.disabled) {
                    return;
                }

                const btn = this;
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Verifying...';

                const formData = new FormData();
                formData.append('verification_code', code);
                formData.append('verify_code', 'true');

                fetch('{{ route("password.change.submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    // Try to parse JSON response
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        // If response is not JSON, create error object
                        throw new Error('Server returned an invalid response. Please try again.');
                    }

                    if (!response.ok) {
                        // Handle error responses
                        let errorMessage = data.message || 'Invalid verification code';
                        throw new Error(errorMessage);
                    }

                    return data;
                })
                .then(data => {
                    if (data.success) {
                        // Code verified, close modal and submit form
                        passwordVerificationModal.hide();
                        passwordChangeForm.submit();
                    } else {
                        verificationCodeInput.classList.add('is-invalid');
                        verificationCodeError.textContent = data.message || 'Invalid verification code';
                        verificationCodeError.style.display = 'block';
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error verifying code:', error);
                    verificationCodeInput.classList.add('is-invalid');
                    verificationCodeError.textContent = error.message || 'Error verifying code. Please try again.';
                    verificationCodeError.style.display = 'block';
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            });
        }

        // Clear error when typing in modal
        if (verificationCodeInput) {
            verificationCodeInput.addEventListener('input', function() {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    verificationCodeError.textContent = '';
                    verificationCodeError.style.display = 'none';
                }
            });
        }

        // Toggle password visibility in verification modal
        document.querySelectorAll('.toggle-password-btn').forEach(function(btn){
            btn.addEventListener('click', function(){
                const input = document.querySelector(this.getAttribute('data-target'));
                if(!input) return;
                const icon = this.querySelector('i');
                const isPwd = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPwd ? 'text' : 'password');
                if(icon){
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                }
            });
        });

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

