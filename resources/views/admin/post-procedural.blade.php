@extends('layout.admin.app')
@section('content')
<h1 class="mb-4">Post-Procedure Form</h1>
<div class="d-flex flex-column flex-lg-row gap-4">
    <aside class="w-15 w-lg-25">
        <div class="sub-nav">
            <a href="{{ route('admin-post-procedural') }}" class="sub-nav-link active">Form List</a>
            <a href="{{ route('admin-patientrecord') }}" class="sub-nav-link">Patient Record</a>
            <a href="{{ route('admin-patienthistory') }}" class="sub-nav-link ">Patient History</a>
            <a href="{{ route('admin-progressnote') }}" class="sub-nav-link">Progress note</a>

        </div>
    </aside>
    <section class="flex-grow-1">
        <div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient #</th>
                        <th>Name</th>
                        <th>Treatment</th>
                        <th>Info</th>
                        <th>History</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->id }}</td>
                        <td>{{ $patient->number }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->treatment }}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">View</a>
                            <a href="#" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">View</a>
                            <a href="#" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">View</a>
                            <a href="#" class="btn btn-primary btn-sm">Update</a>
                        </td>
                        <td>
                            <a href="#" class="btn btn-outline-secondary btn-sm">Clear</a>
                            <a href="#" class="btn btn-dark btn-sm">Remove</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
