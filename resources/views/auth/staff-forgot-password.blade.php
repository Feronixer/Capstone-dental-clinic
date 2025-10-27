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
                    <i class="bi bi-shield-lock me-2"></i>Staff Password Recovery
                </h2>
                <p class="text-secondary">Enter your email address and we'll send you a verification code</p>
            </div>

            <form action="{{ route('staff.password.reset.send') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label">Staff Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" placeholder="Enter your staff email address"
                               value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-custom-primary w-100 mb-3">
                    <i class="bi bi-envelope-check me-2"></i>Send Verification Code
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
@endsection

