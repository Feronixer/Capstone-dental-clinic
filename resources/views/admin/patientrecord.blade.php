@extends('layout.admin.app')

@section('content')
<h1 class="mb-4">Patient History</h1>

<div class="d-flex flex-column flex-lg-row gap-4">
    {{-- Sidebar (original style) --}}
    <aside class="w-15 w-lg-25">
        <div class="sub-nav">
            <a href="{{ route('admin-post-procedural') }}" class="sub-nav-link">Form List</a>
            <a href="{{ route('admin-patientrecord') }}" class="sub-nav-link active">Patient Record</a>
            <a href="{{ route('admin-patienthistory') }}" class="sub-nav-link">Patient History</a>
            <a href="{{ route('admin-progressnote') }}" class="sub-nav-link ">Progress note</a>
        </div>
    </aside>

    {{-- Main content --}}
    <section class="flex-grow-1">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Patient History</strong>
                {{-- Optional action button --}}

            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 12rem;">Date</th>
                                <th>History Details</th>
                                <th style="width: 14rem;">Doctor</th>
                                <th style="width: 12rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Example static row; replace with your loop --}}
                            {{-- @forelse($histories as $history) --}}
                            <tr>
                                <td>2025-09-29</td>
                                <td>Initial Consultation</td>
                                <td>Dr. Valera</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="#" class="btn btn-info btn-sm">View</a>
                                        <a href="#" class="btn btn-secondary btn-sm">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            {{-- @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No history yet.</td>
                                </tr>
                            @endforelse --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
