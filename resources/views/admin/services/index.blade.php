@extends('layout.admin.app')

@section('content')

<style>
    .services-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        border-radius: 18px;
        padding: 28px 32px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 18px 35px rgba(13, 110, 253, 0.25);
    }

    .services-hero::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        top: -90px;
        right: -70px;
        pointer-events: none;
    }

    .services-metric-card {
        border: none;
        border-radius: 12px;
        padding: 16px;
        height: 100%;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .services-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.1);
    }

    .services-metric-card .metric-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .services-table-wrapper {
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.1);
        overflow: hidden;
    }

    .services-table thead {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.08) 0%, rgba(13, 110, 253, 0.02) 100%);
        text-transform: uppercase;
        font-size: 0.77rem;
        letter-spacing: 0.7px;
        color: #4a5568;
    }

    .services-table tbody tr {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .services-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        background: #f8fafc;
    }

    .services-table .service-name-badge {
        font-weight: 600;
        color: #0d6efd;
        background: rgba(13, 110, 253, 0.12);
        border-radius: 999px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .services-table .duration-chip {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        border-radius: 999px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .services-table .date-chip {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
        border-radius: 999px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .services-table .description-cell {
        color: #4a5568;
        font-size: 0.93rem;
    }

    /* Dark Mode Styles */
    [data-theme="dark"] .services-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #581c87 100%);
        box-shadow: 0 18px 35px rgba(13, 110, 253, 0.15);
    }

    [data-theme="dark"] .services-metric-card {
        background: var(--dm-card-bg, #1e293b) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .services-metric-card:hover {
        box-shadow: 0 16px 35px rgba(0, 0, 0, 0.4);
        border-color: rgba(13, 110, 253, 0.3) !important;
    }

    [data-theme="dark"] .services-metric-card h6 {
        color: var(--dm-text-secondary, #94a3b8) !important;
    }

    [data-theme="dark"] .services-metric-card h3 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .services-metric-card small {
        color: var(--dm-text-secondary, #94a3b8) !important;
    }

    [data-theme="dark"] .services-table-wrapper {
        background: var(--dm-card-bg, #1e293b) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .services-table thead {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.15) 0%, rgba(13, 110, 253, 0.05) 100%) !important;
        color: var(--dm-text-secondary, #94a3b8) !important;
        border-bottom: 1px solid var(--dm-border-color, #334155) !important;
    }
    [data-theme="dark"] .services-table thead th {
        color: var(--dm-text-secondary, #cbd5e1) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .services-table>:not(caption)>*>* {
        background-color: rgba(15, 23, 42, 0.85) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .services-table tbody tr:nth-child(even)>* {
        background-color: rgba(30, 41, 59, 0.9) !important;
    }

    [data-theme="dark"] .services-table tbody tr:hover>* {
        background-color: var(--dm-bg-tertiary, #334155) !important;
    }

    [data-theme="dark"] .services-table tbody tr {
        border-bottom: 1px solid var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .services-table tbody td {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .services-table .service-name-badge {
        color:rgb(255, 255, 255) !important;
        background: rgba(13, 110, 253, 0.25) !important;
        border: 1px solid rgba(13, 110, 253, 0.3) !important;
    }

    [data-theme="dark"] .services-table .service-name-badge i {
        color: #60a5fa !important;
    }

    [data-theme="dark"] .services-table .duration-chip {
        background: rgba(25, 135, 84, 0.25) !important;
        color:rgb(255, 255, 255) !important;
        border: 1px solid rgba(25, 135, 84, 0.3) !important;
    }

    [data-theme="dark"] .services-table .duration-chip i {
        color: #4ade80 !important;
    }

    [data-theme="dark"] .services-table .date-chip {
        background: rgba(139, 92, 246, 0.25) !important;
        color:rgb(255, 255, 255) !important;
        border: 1px solid rgba(139, 92, 246, 0.3) !important;
    }

    [data-theme="dark"] .services-table .date-chip i {
        color: #a78bfa !important;
    }

    [data-theme="dark"] .services-table .description-cell {
        color: var(--dm-text-secondary, #cbd5e1) !important;
    }

    [data-theme="dark"] .services-table-wrapper .text-muted {
        color: var(--dm-text-secondary, #94a3b8) !important;
    }

    [data-theme="dark"] .services-table-wrapper .pagination {
        --bs-pagination-color: var(--dm-text-primary, #f1f5f9);
        --bs-pagination-bg: var(--dm-card-bg, #1e293b);
        --bs-pagination-border-color: var(--dm-border-color, #334155);
        --bs-pagination-hover-color: #60a5fa;
        --bs-pagination-hover-bg: var(--dm-bg-tertiary, #334155);
        --bs-pagination-hover-border-color: var(--dm-border-color, #334155);
        --bs-pagination-active-color: #ffffff;
        --bs-pagination-active-bg: #0d6efd;
        --bs-pagination-active-border-color: #0d6efd;
    }

    [data-theme="dark"] .services-table-wrapper .text-center.text-muted {
        color: var(--dm-text-secondary, #94a3b8) !important;
    }

    [data-theme="dark"] .services-table-wrapper .text-center.text-muted h5 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .services-table-wrapper .text-center.text-muted i {
        color: var(--dm-text-secondary, #64748b) !important;
    }

    [data-theme="dark"] .services-hero .btn-light {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #f8fafc !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(8px);
    }

    [data-theme="dark"] .services-hero .btn-light:hover,
    [data-theme="dark"] .services-hero .btn-light:focus {
        background: rgba(255, 255, 255, 0.18) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.6);
    }
</style>

<div class="container-fluid py-4">
    <div class="services-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h2 class="fw-bold mb-2">Clinic Services</h2>
                <p class="mb-0 opacity-75">Complete list of services offered by the clinic with durations and descriptions.</p>
            </div>
            <a href="{{ route('admin-dashboard') }}" class="btn btn-light btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <div class="services-table-wrapper">
        @if($clinicServices->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox display-5 d-block mb-3"></i>
                <h5 class="fw-semibold">No services configured yet</h5>
                <p class="mb-0">Add services through the Content Management section to see them here.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table services-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 25%;">Service Name</th>
                            <th scope="col" style="width: 15%;" class="text-center">Duration</th>
                            <th scope="col" style="width: 15%;" class="text-center">Date Placed</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clinicServices as $index => $service)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $clinicServices->firstItem() + $index }}</td>
                                <td>
                                    <span class="service-name-badge">
                                        <i class="bi bi-patch-check"></i>{{ $service->service_name }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="duration-chip">
                                        <i class="bi bi-clock"></i>{{ $service->default_duration_minutes ? $service->default_duration_minutes . ' mins' : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="date-chip">
                                        <i class="bi bi-calendar-event"></i>{{ $service->created_at ? $service->created_at->timezone('Asia/Manila')->format('M j, Y') : 'Not set' }}
                                    </span>
                                </td>
                                <td class="description-cell">
                                    {{ $service->description ? \Illuminate\Support\Str::limit($service->description, 160) : 'No description provided.' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $clinicServices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

