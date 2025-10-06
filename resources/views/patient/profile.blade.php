@extends('layout.patient.app')
@section('content')

<div class="container">
    <div class="profile-page-padding">
        <h1 class="page-title-patient">PATIENT ACCOUNT</h1>
        <div class="account-container">
            <aside class="profile-sidebar">
                <img src="{{ asset('Assets/avatar.jpg') }}" alt="Profile Avatar" class="profile-avatar" onerror="this.onerror=null;this.src='{{ asset('images/avatar.jpg') }}';">
                <h2 class="profile-name">

                </h2>

                <button class="profile-action-btn" onclick="window.location.href='{{ url('changepass/ChangePassword') }}'">Change Password</button>
                <a href="{{ url('login') }}"><button class="profile-action-btn logout">Log Out</button></a>
            </aside>

            <section class="profile-details-area">
                <div class="details-row">
                    <div class="detail-block">
                        <span class="detail-block-label">First Name</span>

                    </div>
                    <div class="detail-block">
                        <span class="detail-block-label">Middle Name</span>

                    </div>
                    <div class="detail-block">
                        <span class="detail-block-label">Last Name</span>

                    </div>
                </div>
                <div class="details-row">
                    <div class="detail-block">
                        <span class="detail-block-label">Birthday</span>

                    </div>
                    <div class="detail-block">
                        <span class="detail-block-label">Age</span>

                    </div>
                    <div class="detail-block">
                        <span class="detail-block-label">Sex</span>

                    </div>
                </div>
                <div class="detail-block">
                    <span class="detail-block-label">Email</span>

                </div>
                <div class="detail-block">
                    <span class="detail-block-label">Contact Number</span>

                <div class="edit-button-container">

                </div>
            </section>
        </div>
    </div>
</div>

<script src="{{  asset('js/profile.js')  }}"></script>
@endsection
