@extends('layout.auth.app')
@section('content')
<section class="login-container">
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
                    <a href="#" class="text-muted">Forgot Password?</a>
                </div>
            </form>
        </div>
        <div class="login-card-image">
            <img src="{{ asset('images/tooth.png') }}" alt="Tooth Image" class="img-fluid">
        </div>
    </div>
    <p class="text-center mt-4 text-secondary">
        &copy; {{ date('Y') }} JValera Dental Clinic. All rights reserved.
    </p>
</section>
@endsection
