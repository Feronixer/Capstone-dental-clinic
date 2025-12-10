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
                <h2 class="fw-bold">Enter Verification Code</h2>
                <p class="text-secondary">We sent a 6-digit code to</p>
                <p class="fw-bold text-primary">{{ $email }}</p>
            </div>

            <form action="{{ route('password.reset.verify') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-4">
                    <label for="verification_code" class="form-label">Verification Code</label>
                    <input type="text" class="form-control @error('verification_code') is-invalid @enderror"
                           name="verification_code" placeholder="Enter 6-digit code"
                           maxlength="6" pattern="[0-9]{6}" required autofocus>
                    @error('verification_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        The code will expire in 30 minutes
                    </div>
                </div>

                <button type="submit" class="btn-custom-primary w-100 mb-3" id="verifyCodeBtn">
                    <i class="bi bi-check-circle me-2"></i>Verify Code
                </button>

                <div class="text-center">
                    <p class="text-muted mb-2">Didn't receive the code?</p>
                    <a href="{{ route('password.forgot') }}" class="text-primary">
                        <i class="bi bi-arrow-left me-1"></i>Try again
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
// Auto-focus and format verification code input
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.querySelector('input[name="verification_code"]');
    const verifyCodeBtn = document.getElementById('verifyCodeBtn');
    const form = document.querySelector('form[action="{{ route("password.reset.verify") }}"]');
    let isSubmitting = false;

    // Handle form submission
    if (form && verifyCodeBtn) {
        form.addEventListener('submit', function(e) {
            // Prevent multiple submissions
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            
            isSubmitting = true;
            const originalText = verifyCodeBtn.innerHTML;
            verifyCodeBtn.disabled = true;
            verifyCodeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Verifying...';
            
            // Re-enable after 5 seconds as fallback
            setTimeout(() => {
                if (isSubmitting) {
                    isSubmitting = false;
                    verifyCodeBtn.disabled = false;
                    verifyCodeBtn.innerHTML = originalText;
                }
            }, 5000);
        });
    }

    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            // Only allow numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Focus on the input
        codeInput.focus();
    }
});
</script>
@endsection
