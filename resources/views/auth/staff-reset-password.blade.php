@extends('layout.auth.app')
@section('content')
<section class="login-container">
    @if (session('success'))
        <x-toast-message type="success" :message="session('success')" />
    @endif
    @if ($errors->has('error'))
        <x-toast-message type="danger" :message="$errors->first('error')" />
    @endif

    <div class="login-card">
        <div class="login-card-content">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-2.png') }}" alt="Logo" class="login-logo mb-3">
                <h2 class="fw-bold">
                    <i class="bi bi-shield-lock me-2"></i>Set New Staff Password
                </h2>
                <p class="text-secondary">Create a strong password for your staff account</p>
            </div>

            <form action="{{ route('staff.password.reset') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" id="staffNewPassword" placeholder="Enter new password" autocomplete="new-password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleStaffPassword">
                            <i class="bi bi-eye" id="toggleStaffIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div id="passwordRequirements" class="mt-2 small">
                        <div class="text-muted mb-2"><strong>Password Requirements:</strong></div>
                        <div id="req-length" class="d-flex align-items-center mb-1">
                            <i class="bi bi-circle me-2"></i>
                            <span>At least 8 characters</span>
                        </div>
                        <div id="req-uppercase" class="d-flex align-items-center mb-1">
                            <i class="bi bi-circle me-2"></i>
                            <span>At least 1 uppercase letter</span>
                        </div>
                        <div id="req-lowercase" class="d-flex align-items-center mb-1">
                            <i class="bi bi-circle me-2"></i>
                            <span>At least 1 lowercase letter</span>
                        </div>
                        <div id="req-number" class="d-flex align-items-center mb-1">
                            <i class="bi bi-circle me-2"></i>
                            <span>At least 1 number</span>
                        </div>
                        <div id="req-special" class="d-flex align-items-center mb-1">
                            <i class="bi bi-circle me-2"></i>
                            <span>At least 1 special character</span>
                        </div>
                    </div>
                    <div id="passwordStrength" class="mt-2"></div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                               name="password_confirmation" id="staffPasswordConfirmation" placeholder="Confirm new password" autocomplete="new-password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleStaffPasswordConfirmation">
                            <i class="bi bi-eye" id="toggleStaffIconConfirmation"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-custom-primary w-100 mb-3">
                    <i class="bi bi-shield-check me-2"></i>Reset Staff Password
                </button>

                <div class="text-center">
                    <a href="{{ route('staff.login') }}" class="text-muted">
                        <i class="bi bi-arrow-left me-1"></i>Back to Staff Login
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('toggleStaffPassword');
    const passwordInput = document.getElementById('staffNewPassword');
    const toggleIcon = document.getElementById('toggleStaffIcon');

    const togglePasswordConfirmation = document.getElementById('toggleStaffPasswordConfirmation');
    const passwordConfirmationInput = document.getElementById('staffPasswordConfirmation');
    const toggleIconConfirmation = document.getElementById('toggleStaffIconConfirmation');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
    });

    togglePasswordConfirmation.addEventListener('click', function() {
        const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmationInput.setAttribute('type', type);
        toggleIconConfirmation.classList.toggle('bi-eye');
        toggleIconConfirmation.classList.toggle('bi-eye-slash');
    });

    // Password validation and strength indicator
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
                } else {
                    icon.classList.remove('bi-check-circle-fill', 'text-success');
                    icon.classList.add('bi-circle');
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

