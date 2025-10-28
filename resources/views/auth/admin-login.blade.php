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
                    <i class="bi bi-shield-fill-check me-1 text-primary"></i><strong>Administrator Portal</strong>
                </p>
            </div>
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-person-badge-fill"></i>
                        </span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               name="username" placeholder="Enter admin username" value="{{ old('username') }}" required autofocus>
                    </div>
                    @error('username')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" id="password" placeholder="Enter your password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-custom-primary w-100 bg-primary border-primary">
                    <i class="bi bi-shield-lock me-2"></i>Administrator Login
                </button>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.password.forgot') }}" class="text-muted">
                        <i class="bi bi-key me-1"></i>Forgot Password?
                    </a>
                </div>
                <hr class="my-4">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('staff.login') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-shield-lock me-1"></i>Staff Login
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('login') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-person me-1"></i>Patient Login
                        </a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

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

