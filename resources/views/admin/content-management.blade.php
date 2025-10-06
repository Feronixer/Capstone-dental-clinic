@extends('layout.admin.app')
@section('content')

<div class="flex-1 p-6 md:p-8 overflow-y-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Content Management</h1>

<div class="content-wrapper">
   {{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success auto-hide">
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error auto-hide">
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    {{-- Announcement Section --}}
    <section class="card">
        <h2 class="section-title">Update Announcement</h2>

        @if($announcements->isNotEmpty())
            @php $announcement = $announcements->first(); @endphp
            <div class="announcement-box">
                <div class="announcement-image">
                    @if($announcement->image)
                        <img src="{{ asset('storage/' . $announcement->image) }}" alt="Announcement Image"
                         style="max-width:200px; height:auto;">
                        @else
                        <p>No image</p>
                    @endif


                </div>

                <div class="announcement-details">
                    <h3>{{ strtoupper($announcement->title) }}</h3>
                    <p>{{ $announcement->body }}</p>
                </div>

                <div>
                <button class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#editAnnouncementModal"
                    onclick="setEditAnnouncementData({{ $announcement->id }}, '{{ $announcement->title }}', `{{ $announcement->body }}`)">
                Edit
            </button>
                </div>
            </div>
        @else
            <p>No announcements available.</p>
        @endif
    </section>

    {{-- Service Section --}}
    <section class="card">
        <div class="section-header">
            <h2 class="section-title">Service</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
    + Add Service
</button>
        </div>
        @if(session('modal_success'))
    @include('admin.partials.delete-success-modal')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
@endif
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span id="successMessage">{{ session('modal_success') }}</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>


        <table class="custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Icon</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    @foreach($services as $service)
        <tr>
            <td>{{ $loop->iteration }}</td> {{-- continuous numbering --}}
            <td><i class="fas {{ $service->icon_fa }}"></i></td>
            <td>{{ $service->service }}</td>
            <td>{{ $service->price }}</td>
            <td>{{ $service->description }}</td>
            <td>
                <button class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#editServiceModal"
                        onclick="setEditServiceData({{ $service->id }}, '{{ $service->icon_fa }}', '{{ $service->service }}', '{{ $service->price }}', `{{ $service->description }}`)">
                    Edit
                </button>

                <button class="btn btn-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteServiceModal"
                        onclick="setDeleteServiceId({{ $service->id }})">
                    Delete
                </button>
            </td>
        </tr>
    @endforeach
</tbody>
        </table>
    </section>
</div>

{{-- Success Modal --}}
@if(session('modal_success'))
    @include('admin.partials.delete-success-modal')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
@endif


{{-- Mail Settings --}}
<section class="card">
    <h2 class="section-title">Patient Mail Settings</h2>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="mailTabs" role="tablist">
        @foreach($mailSettings as $setting)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                        id="tab-{{ $setting->id }}"
                        data-bs-toggle="tab"
                        data-bs-target="#mail-{{ $setting->id }}"
                        type="button" role="tab">
                    {{ $setting->name ?? 'Template '.$setting->id }}
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="mailTabsContent">
        @foreach($mailSettings as $setting)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                 id="mail-{{ $setting->id }}"
                 role="tabpanel">

                <h5>{{ $setting->name ?? 'Template '.$setting->id }}</h5>

                <div class="mb-3">
                    <label class="form-label">Mail Structure</label>
                    <pre class="border p-2 bg-light">{{ $setting->structure }}</pre>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mail Preview</label>
                    <div class="border p-3">
                        {{ $setting->preview }}
                    </div>
                </div>

                <button class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#editMailModal"
                        onclick="setEditMailData({{ $setting->id }}, `{{ $setting->structure }}`, `{{ $setting->preview }}`)">
                    Edit
                </button>
            </div>
        @endforeach
    </div>
</section>


{{-- Modals --}}
@include('admin.partials.add-service-modal')
@include('admin.partials.edit-service-modal')
@include('admin.partials.deleteServicemodal')
@include('admin.partials.edit-announcement-modal')

<script src="{{ asset('js/contentManagement.js') }}"></script>
@endsection
