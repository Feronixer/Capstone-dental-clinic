@extends('layout.app')
@section('content')

<div>
    <form action="{{ url('/register') }}" method="post">
        @csrf
        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
            <label for="floatingInput">Email address</label>
        </div>
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
         <div class="form-floating">
            <input type="text" class="form-control" id="floatingInput" placeholder="Josh na hindi kyut" name="name">
            <label for="floatingInput">Name</label>
        </div>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-floating">
            <div class="input-group">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                <button class="btn btn-outline-secondary" type="button" id="toggleRegisterPassword" aria-label="Show password">
                    <i class="bi bi-eye" id="toggleRegisterPasswordIcon"></i>
                </button>
            </div>
            <label for="floatingPassword">Password</label>
        </div>
        @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<script>
    // Password toggle for register password field
    document.addEventListener('click', function(e) {
        if (e.target.closest('#toggleRegisterPassword')) {
            const toggleBtn = document.getElementById('toggleRegisterPassword');
            const passwordInput = document.getElementById('floatingPassword');
            const toggleIcon = document.getElementById('toggleRegisterPasswordIcon');
            
            if (toggleBtn && passwordInput && toggleIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                    toggleBtn.setAttribute('aria-label', 'Hide password');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                    toggleBtn.setAttribute('aria-label', 'Show password');
                }
            }
        }
    });
</script>
@endsection
