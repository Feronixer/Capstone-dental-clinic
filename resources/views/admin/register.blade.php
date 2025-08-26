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
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
            <label for="floatingPassword">Password</label>
        </div>
        @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
@endsection
