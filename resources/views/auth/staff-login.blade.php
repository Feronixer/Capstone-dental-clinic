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
                <h2 class="fw-bold">JValera Dental Clinic</h2>
                <p class="text-secondary">
                    <i class="bi bi-shield-lock-fill me-1"></i>Staff Portal
                </p>
            </div>
            <form action="{{ route('staff.login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               name="username" placeholder="Enter your username" value="{{ old('username') }}" required>
                    </div>
                    @error('username')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" id="staffPassword" placeholder="Enter your password" required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleStaffPassword">
                            <i class="bi bi-eye" id="toggleStaffIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-custom-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Staff Log In
                </button>
                <div class="text-center mt-3">
                    <a href="{{ route('staff.password.forgot') }}" class="text-muted">
                        <i class="bi bi-key me-1"></i>Forgot Password?
                    </a>
                </div>
                <hr class="my-4">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.login') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-shield-fill-check me-1"></i>Admin
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('login') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-person me-1"></i>Patient
                        </a>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="/" class="text-muted">
                        <i class="bi bi-arrow-left me-1"></i>Back to Homepage
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
    const passwordInput = document.getElementById('staffPassword');
    const toggleIcon = document.getElementById('toggleStaffIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    }
});
</script>
@endsection

