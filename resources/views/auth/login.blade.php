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
                <p class="text-secondary">Patient Portal</p>
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter your email">
                    @error('email')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter your password">
                    @error('password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-custom-primary">Log In</button>
                <div class="text-center mt-3">
                    <a href="{{ route('password.forgot') }}" class="text-muted">Forgot Password?</a>
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
