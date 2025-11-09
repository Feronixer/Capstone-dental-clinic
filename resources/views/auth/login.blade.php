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
                               name="password" id="patientPassword" placeholder="Enter your password" autocomplete="current-password" required>
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
                <div class="text-center mt-5">
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

    // Handle mobile keyboard appearance
    const inputs = document.querySelectorAll('.login-card-content input[type="text"], .login-card-content input[type="password"], .login-card-content input[type="email"]');
    const loginCardContent = document.querySelector('.login-card-content');
    
    inputs.forEach(function(input) {
        // Scroll input into view when focused (for mobile keyboards)
        input.addEventListener('focus', function() {
            // Small delay to allow keyboard to appear first
            setTimeout(function() {
                if (window.innerHeight < 600) { // Likely mobile device
                    input.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center',
                        inline: 'nearest'
                    });
                }
            }, 300);
        });

        // Prevent layout shift when keyboard appears
        input.addEventListener('blur', function() {
            // Reset scroll position if needed
            if (window.innerHeight < 600) {
                setTimeout(function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 100);
            }
        });
    });

    // Handle viewport resize (keyboard show/hide)
    let viewportHeight = window.innerHeight;
    window.addEventListener('resize', function() {
        const currentHeight = window.innerHeight;
        const heightDifference = viewportHeight - currentHeight;
        
        // If viewport shrunk significantly, keyboard likely appeared
        if (heightDifference > 150 && loginCardContent) {
            const activeElement = document.activeElement;
            if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA')) {
                setTimeout(function() {
                    activeElement.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center',
                        inline: 'nearest'
                    });
                }, 100);
            }
        }
        
        viewportHeight = currentHeight;
    });

    // Prevent body scroll on mobile when keyboard is open
    let isKeyboardOpen = false;
    inputs.forEach(function(input) {
        input.addEventListener('focus', function() {
            if (window.innerWidth <= 768) {
                isKeyboardOpen = true;
                document.body.style.overflow = 'hidden';
            }
        });

        input.addEventListener('blur', function() {
            if (isKeyboardOpen) {
                setTimeout(function() {
                    document.body.style.overflow = '';
                    isKeyboardOpen = false;
                }, 100);
            }
        });
    });
});
</script>
@endsection
