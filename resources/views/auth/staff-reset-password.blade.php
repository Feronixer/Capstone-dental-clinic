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
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Password must be at least 8 characters long
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

    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = getPasswordStrength(password);
        updatePasswordStrengthIndicator(strength);
    });

    function getPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }

    function updatePasswordStrengthIndicator(strength) {
        const strengthDiv = document.getElementById('passwordStrength');
        let strengthText = '';
        let strengthClass = '';
        let progressPercentage = 0;

        if (strength === 0) {
            strengthText = '';
            strengthClass = '';
            progressPercentage = 0;
        } else if (strength <= 2) {
            strengthText = 'Weak';
            strengthClass = 'text-danger';
            progressPercentage = 33;
        } else if (strength <= 4) {
            strengthText = 'Medium';
            strengthClass = 'text-warning';
            progressPercentage = 66;
        } else {
            strengthText = 'Strong';
            strengthClass = 'text-success';
            progressPercentage = 100;
        }

        if (strengthText) {
            strengthDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="${strengthClass} fw-bold">Password Strength: ${strengthText}</small>
                </div>
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar ${strengthClass === 'text-danger' ? 'bg-danger' : strengthClass === 'text-warning' ? 'bg-warning' : 'bg-success'}"
                         role="progressbar" style="width: ${progressPercentage}%" aria-valuenow="${progressPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            `;
        } else {
            strengthDiv.innerHTML = '';
        }
    }
});
</script>
@endsection

