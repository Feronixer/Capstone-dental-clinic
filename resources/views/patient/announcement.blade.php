@extends('layout.patient.app')
@section('content')

<main>
    <div class="content-container">
        <div class="announcement-center">
            <h2 class="announcement-title">ANNOUNCEMENT</h2>
            <div class="announcement-card">
                <div class="announcement-image-container">
                    <img src="https://placehold.co/700x394/A9CCE3/2C3E50?text=Day+of+Valor+Celebration" alt="Clinic Announcement Graphic" class="announcement-image" onerror="this.onerror=null;this.src='https://placehold.co/700x394/A9CCE3/2C3E50?text=Image+Error';">
                </div>
                <h3 class="announcement-subtitle">IT'S A CELEBRATION!</h3>
                <p class="announcement-text">
                    We are closed on April 9, 2025.<br>Clinical Operations Resume on April 10, 2025.
                </p>
            </div>
        </div>
    </div>
</main>
<script src="{{ asset('js/announcement.js') }}"></script>
@endsection
