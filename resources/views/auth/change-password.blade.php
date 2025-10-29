@extends('layout.auth.app')
@section('content')
<section class="login-container">
    @if (session('error'))
        <x-toast-message type="danger" :message="session('error')" />
    @endif
    @if ($errors->any())
        <x-toast-message type="danger" message="Please fix the errors below" />
    @endif

    <div class="login-card">
        <div class="login-card-content">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-2.png') }}" alt="Logo" class="login-logo mb-3">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Password Change Required</strong>
                    <p class="mb-0 mt-2 small">For your security, you must change your password on first login.</p>
                </div>
                <h4 class="fw-bold">Change Your Password</h4>
                <p class="text-secondary">Patient Portal</p>
            </div>

            <form action="{{ route('password.change.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                           name="current_password" id="current_password" placeholder="Enter current password" required>
                    @error('current_password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                           name="new_password" id="new_password" placeholder="Enter new password (min. 8 characters)" required>
                    @error('new_password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                           name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" required>
                    @error('new_password_confirmation')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-custom-primary">
                    <i class="bi bi-shield-lock me-2"></i>Change Password
                </button>

                <div class="text-center mt-3">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted">
                            <i class="bi bi-box-arrow-left me-1"></i>Logout
                        </button>
                    </form>
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

