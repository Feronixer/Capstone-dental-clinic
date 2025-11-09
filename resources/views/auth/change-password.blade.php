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
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-2.png') }}" alt="Logo" class="login-logo mb-3">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Password Change Required</strong>
                    <p class="mb-0 mt-2 small">For your security, you must change your password on first login.</p>
                </div>
                <h4 class="fw-bold">Change Your Password</h4>
                <p class="text-secondary">Patient Portal</p>
            </div>

            <form id="passwordChangeForm" action="{{ route('password.change.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <div class="input-group">
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                           name="new_password" id="new_password" placeholder="Enter new password (min. 8 characters)" autocomplete="new-password" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="input-group">
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

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('patient-profile') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                    <div>
                    <button type="button" class="btn btn-link text-muted" id="logoutBtn">
                        <i class="bi bi-box-arrow-left me-1"></i>Logout
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    </div>
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

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content logout-modal-content">
            <div class="modal-body text-center p-4">
                <div class="logout-icon-wrapper mb-3">
                    <i class="bi bi-box-arrow-right text-white"></i>
                </div>
                <h5 class="logout-modal-title mb-2" id="logoutModalLabel">Logout</h5>
                <p class="logout-modal-message mb-4">Are you sure you want to logout?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel-logout" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-confirm-logout" id="confirmLogoutBtn">Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="resendCodeBtn" style="padding: 0.875rem 2rem; border-radius: 8px;">
                            <i class="bi bi-arrow-clockwise me-2"></i>Resend Code
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e3f2fd; padding: 1.5rem 2rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.875rem 2rem; border-radius: 8px;">Cancel</button>
                <button type="button" class="btn btn-primary" id="verifyCodeBtn" style="display: none; background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); border: none; padding: 0.875rem 2rem; border-radius: 8px;">
                    <i class="bi bi-check-circle me-2"></i>Verify & Continue
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Logout Modal Styles */
    .logout-modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(37, 99, 235, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    .logout-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.4);
    }

    .logout-icon-wrapper i {
        font-size: 2.5rem;
        color: white;
    }

    .logout-modal-title {
        font-size: 1.375rem !important;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .logout-modal-message {
        font-size: 1rem;
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    .btn-cancel-logout {
        background: #ffffff;
        color: #64748b;
        border: 2px solid #e2e8f0;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-cancel-logout:hover {
        background: #f8fafc;
        color: #2563eb;
        border-color: #2563eb;
        transform: translateY(-2px);
    }

    .btn-confirm-logout {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-confirm-logout:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.4);
    }

    /* Dark Mode Styles for Logout Modal */
    [data-theme="dark"] .logout-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .logout-modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .logout-modal-message {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .btn-cancel-logout {
        background: var(--dm-bg-tertiary, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .btn-cancel-logout:hover {
        background: var(--dm-bg-secondary, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: #3b82f6 !important;
    }

    [data-theme="dark"] .btn-confirm-logout {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
        color: white !important;
    }

    [data-theme="dark"] .btn-confirm-logout:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
        color: white !important;
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

        // Add event listener to logout button
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const modalElement = document.getElementById('logoutModal');
                if (!modalElement) {
                    console.error('Logout modal not found');
                    return;
                }
                const logoutModal = new bootstrap.Modal(modalElement);
                logoutModal.show();
            });
        }

        // Add event listener for confirm button
        const confirmBtn = document.getElementById('confirmLogoutBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const logoutForm = document.getElementById('logout-form');
                if (logoutForm) {
                    logoutForm.submit();
                }
            });
        }

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

                if (newPassword.length < 8) {
                    showValidationError('Password Too Short', 'Password must be at least 8 characters long.');
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

                if (newPassword.length < 8) {
                    passwordVerificationModal.hide();
                    showValidationError('Password Too Short', 'Password must be at least 8 characters long.');
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
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to send verification code');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showSuccessToast('Verification code sent to your email!');
                        sendCodeSection.style.display = 'none';
                        verifyCodeSection.style.display = 'block';
                        verifyCodeBtn.style.display = 'block';
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
                    console.error('Error:', error);
                    passwordVerificationModal.hide();
                    showValidationError('Error', error.message || 'Failed to send verification code. Please check your password fields.');
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Code verified, close modal and submit form
                        passwordVerificationModal.hide();
                        passwordChangeForm.submit();
                    } else {
                        verificationCodeInput.classList.add('is-invalid');
                        verificationCodeError.textContent = data.message || 'Invalid verification code';
                        verificationCodeError.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    verificationCodeInput.classList.add('is-invalid');
                    verificationCodeError.textContent = 'Error verifying code';
                    verificationCodeError.style.display = 'block';
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
    });
</script>
@endsection

