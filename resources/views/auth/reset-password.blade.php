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
                <h2 class="fw-bold">Set New Password</h2>
                <p class="text-secondary">Create a strong password for your account</p>
            </div>

            <form action="{{ route('password.reset') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" id="password" placeholder="Enter new password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Password must be at least 8 characters long
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                               name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                            <i class="bi bi-eye" id="toggleIconConfirmation"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-custom-primary w-100 mb-3">
                    <i class="bi bi-key me-2"></i>Reset Password
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-muted">
                        <i class="bi bi-arrow-left me-1"></i>Back to Login
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
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const toggleIconConfirmation = document.getElementById('toggleIconConfirmation');

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
    const passwordInput = document.getElementById('password');
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = getPasswordStrength(password);
        updatePasswordStrengthIndicator(strength);
    });

    function getPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }

    function updatePasswordStrengthIndicator(strength) {
        // You can add a visual strength indicator here if needed
        console.log('Password strength:', strength);
    }
});
</script>
@endsection
