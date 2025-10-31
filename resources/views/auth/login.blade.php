@extends('layout.auth.app')
@section('content')
<section class="login-container">
@if (session('success'))
    <x-toast-message type="success" :message="session('success')" />
@endif
    <div class="login-card">
        <div class="login-card-content">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-2.png') }}" alt="Logo" class="login-logo mb-3">
                <h2 class="fw-bold">JValera Dental Clinic</h2>
                <p class="text-secondary">
                    <i class="bi bi-person-fill me-1 text-success"></i><strong>Patient Portal</strong>
                </p>
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email_username" class="form-label">Email or Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-success text-white">
                            <i class="bi bi-person-badge-fill"></i>
                        </span>
                        <input type="text" class="form-control @error('email') is-invalid @enderror @error('username') is-invalid @enderror"
                               name="email_username" id="email_username" placeholder="Enter email or username" value="{{ old('email_username') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="alert alert-danger-custom mt-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    @error('username')
                        <div class="alert alert-danger-custom mt-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    @error('email_username')
                        <div class="alert alert-danger-custom mt-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    @if($errors->has('error'))
                        <div class="alert alert-danger-custom mt-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <span>{{ $errors->first('error') }}</span>
                        </div>
                    @endif
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-success text-white">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" id="patientPassword" placeholder="Enter your password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePatientPassword">
                            <i class="bi bi-eye" id="togglePatientIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="alert alert-danger-custom mt-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn-custom-primary w-100 bg-success border-success">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Patient Login
                </button>
                <div class="text-center mt-3">
                    <a href="{{ route('password.forgot') }}" class="text-muted">
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
                        <a href="{{ route('staff.login') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-shield-lock me-1"></i>Staff
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
    const togglePassword = document.getElementById('togglePatientPassword');
    const passwordInput = document.getElementById('patientPassword');
    const toggleIcon = document.getElementById('togglePatientIcon');

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
