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
                <h2 class="fw-bold">Admin Password Reset</h2>
                <p class="text-secondary">
                    <i class="bi bi-shield-fill-check text-primary me-1"></i>
                    Enter your administrator email address
                </p>
            </div>

            <form action="{{ route('admin.password.reset.send') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label">Admin Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" placeholder="Enter your admin email address"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-custom-primary w-100 mb-3 bg-primary border-primary" id="sendCodeBtn">
                    <i class="bi bi-envelope me-2"></i>Send Verification Code
                </button>

                <div class="text-center">
                    <a href="{{ route('admin.login') }}" class="text-muted">
                        <i class="bi bi-arrow-left me-1"></i>Back to Admin Login
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
    const form = document.querySelector('form[action="{{ route("admin.password.reset.send") }}"]');
    const sendCodeBtn = document.getElementById('sendCodeBtn');
    
    if (form && sendCodeBtn) {
        form.addEventListener('submit', function(e) {
            // Prevent multiple submissions
            if (sendCodeBtn.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const originalText = sendCodeBtn.innerHTML;
            sendCodeBtn.disabled = true;
            sendCodeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';
            
            // Re-enable button after 5 seconds as fallback (in case form doesn't submit)
            setTimeout(() => {
                if (sendCodeBtn.disabled) {
                    sendCodeBtn.disabled = false;
                    sendCodeBtn.innerHTML = originalText;
                }
            }, 5000);
        });
    }
});
</script>
@endsection

