@extends('layout.patient.app')
@section('content')

<section class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title   ">Appointments</h5>
                    <p class="card-text">View and manage your upcoming appointments.</p>
                    <a href="{{ url('/patient/appointments') }}" class="btn btn-primary">Go to Appointments</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Medical Records</h5>
                    <p class="card-text">Access your medical history and records.</p>
                    <a href="{{ url('/patient/medical-records') }}" class="btn btn-primary">View Medical Records</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Profile</h5>

                    <p class="card-text">Update your personal information and settings.</p>
                    <a href="{{ url('/patient/profile') }}" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
