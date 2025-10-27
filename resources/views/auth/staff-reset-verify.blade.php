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
                    <i class="bi bi-shield-check me-2"></i>Staff Verification
                </h2>
                <p class="text-secondary">We sent a 6-digit code to</p>
                <p class="fw-bold text-primary">{{ $email }}</p>
            </div>

            <form action="{{ route('staff.password.reset.verify') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-4">
                    <label for="verification_code" class="form-label">Verification Code</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-key-fill"></i>
                        </span>
                        <input type="text" class="form-control text-center @error('verification_code') is-invalid @enderror"
                               name="verification_code" placeholder="000000"
                               maxlength="6" pattern="[0-9]{6}" required autofocus
                               style="font-size: 1.5rem; letter-spacing: 0.5rem;">
                    </div>
                    @error('verification_code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-clock me-1"></i>
                        The code will expire in 15 minutes
                    </div>
                </div>

                <button type="submit" class="btn-custom-primary w-100 mb-3">
                    <i class="bi bi-check-circle me-2"></i>Verify Code
                </button>

                <div class="text-center">
                    <p class="text-muted mb-2">Didn't receive the code?</p>
                    <a href="{{ route('staff.password.forgot') }}" class="text-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Resend Code
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

    codeInput.addEventListener('input', function(e) {
        // Only allow numbers
        e.target.value = e.target.value.replace(/[^0-9]/g, '');

        // Auto-submit when 6 digits are entered
        if (e.target.value.length === 6) {
            setTimeout(() => {
                e.target.form.submit();
            }, 300);
        }
    });

    // Focus on the input
    codeInput.focus();
});
</script>
@endsection

