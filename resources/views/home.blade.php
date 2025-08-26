@extends('layout.app')
@section('content')

<section class="home-container">
    <div class="home-content">
        <img src="{{ asset('images/hero.jpg') }}" alt="logo-banner">
    </div>
    <div class="home-body">
        <h2>Have confidence in your <span>SMILE</span> in no time!</h2>
        <p>If you are seeking for your follow up care</p>
        <a href="#" class="btn btn-custom">Sign in with your patient account</a>
    </div>
</section>

<section class="home-video-container">
    <div class="video-content">

    </div>
</section>
@endsection
