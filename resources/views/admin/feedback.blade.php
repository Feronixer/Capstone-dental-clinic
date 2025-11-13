@extends('layout.admin.app')
@section('content')

<style>
    .feedback-container {
        padding: 1.5rem;
        max-width: 100%;
    }

    .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #b2dfdb;
    }

    .filter-card .card-body {
        padding: 1.5rem !important;
    }

    .filter-card .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: block;
    }

    .filter-card .form-select {
        border: 1px solid #16a085;
        border-radius: 8px;
        transition: all 0.3s ease;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2316a085' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .filter-card .form-select:focus {
        border-color: #0e7862;
        box-shadow: 0 0 0 0.2rem rgba(22, 160, 133, 0.25);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230e7862' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    }

    /* Dark Mode Styles for Filters */
    [data-theme="dark"] .filter-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .filter-card .form-label,
    [data-theme="dark"] .filter-card .form-label[style*="color: #16a085"],
    [data-theme="dark"] .filter-card label[style*="color: #16a085"] {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .filter-card .form-select {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: var(--dm-border-color, #475569) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2316a085' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 18px 14px !important;
        padding-right: 2.5rem !important;
    }

    [data-theme="dark"] .filter-card .form-select:focus {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: #16a085 !important;
        box-shadow: 0 0 0 0.2rem rgba(22, 160, 133, 0.25) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
        background-size: 20px 16px !important;
    }

    [data-theme="dark"] .filter-card .form-select option {
        background-color: var(--dm-card-bg, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .filter-card .btn-outline-secondary {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: var(--dm-border-color, #475569) !important;
    }

    [data-theme="dark"] .filter-card .btn-outline-secondary:hover {
        background-color: var(--dm-bg-secondary, #0f172a) !important;
        border-color: var(--dm-border-color, #475569) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    .feedback-table-wrapper {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .feedback-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .feedback-table thead {
        background: linear-gradient(135deg, #16a085 0%, #0e7862 100%);
        color: white;
    }

    .feedback-table thead th {
        padding: 1rem;
        font-weight: 700;
        font-size: 1rem;
        text-align: center;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .feedback-table thead th:first-child {
        border-top-left-radius: 12px;
    }

    .feedback-table thead th:last-child {
        border-top-right-radius: 12px;
    }

    .feedback-row {
        border-bottom: 1px solid #b2dfdb;
        transition: all 0.3s ease;
    }

    .feedback-row:last-child {
        border-bottom: none;
    }

    .feedback-row:hover {
        background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 50%);
        transform: translateX(5px);
    }

    .feedback-row td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        text-align: center;
    }

    .service-name {
        color: #16a085;
        font-weight: 500;
    }

    .rating-stars {
        font-size: 1.1rem;
        display: flex;
        gap: 2px;
        justify-content: center;
    }

    .rating-stars .bi-star-fill {
        color: #16a085 !important;
    }

    .feedback-comment {
        color: #16a085;
        line-height: 1.6;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        overflow-wrap: anywhere;
        font-family: 'Poppins', sans-serif;
    }

    .submission-info {
        line-height: 1.6;
        white-space: nowrap;
    }

    /* Dark Mode Styles */
    [data-theme="dark"] .feedback-table-wrapper {
        background: var(--dm-card-bg, #1e293b) !important;
    }

    [data-theme="dark"] .feedback-row {
        border-bottom-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .feedback-row:hover {
        background: var(--dm-bg-tertiary, #334155) !important;
    }

    [data-theme="dark"] .feedback-row td {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .service-name {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .feedback-comment {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* Responsive Table */
    @media (max-width: 1200px) {
        .feedback-table-wrapper {
            overflow-x: auto;
        }
        
        .feedback-table {
            min-width: 800px;
        }
    }

    /* Pagination Styles */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 2px solid #f1f5f9;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: #64748b;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-info strong {
        color: #1e293b;
    }

    .pagination-links {
        display: flex;
        justify-content: flex-end;
        flex: 1;
    }

    .modern-pagination {
        list-style: none;
        display: flex;
        gap: 0.5rem;
        padding: 0;
        margin: 0;
        align-items: center;
    }

    .modern-pagination .page-item {
        display: flex;
    }

    .modern-pagination .page-link {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1.5px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #1f2937;
        background: #ffffff;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);
    }

    .modern-pagination .page-link i {
        font-size: 1rem;
    }

    .modern-pagination .page-link:hover {
        border-color: #667eea;
        color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        transform: translateY(-1px);
    }

    .modern-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 6px 18px rgba(102, 126, 234, 0.35);
    }

    .modern-pagination .page-item.disabled .page-link {
        background: #f3f4f6;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        box-shadow: none;
    }

    .modern-pagination .page-link.dots {
        width: auto;
        padding: 0 0.8rem;
        cursor: default;
        border-style: dashed;
        color: #9ca3af;
        box-shadow: none;
    }

    @media (max-width: 768px) {
        .pagination-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .pagination-links {
            justify-content: center;
            width: 100%;
        }
    }

    /* Dark Mode Pagination */
    [data-theme="dark"] .pagination-container {
        border-top-color: var(--dm-border-color) !important;
    }

    [data-theme="dark"] .pagination-info {
        color: var(--dm-text-primary) !important;
    }

    [data-theme="dark"] .pagination-info strong {
        color: var(--dm-text-primary) !important;
    }

    [data-theme="dark"] .modern-pagination .page-link {
        background: var(--dm-card-bg) !important;
        border-color: var(--dm-border-color) !important;
        color: var(--dm-text-primary) !important;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.25) !important;
    }

    [data-theme="dark"] .modern-pagination .page-link:hover {
        border-color: #667eea !important;
        color: #a5b4fc !important;
        box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4) !important;
    }

    [data-theme="dark"] .modern-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(102, 126, 234, 0.45) !important;
    }

    [data-theme="dark"] .modern-pagination .page-item.disabled .page-link {
        background: var(--dm-bg-secondary) !important;
        border-color: var(--dm-border-color) !important;
        color: var(--dm-text-muted) !important;
        box-shadow: none !important;
    }

    [data-theme="dark"] .modern-pagination .page-link.dots {
        border-color: var(--dm-border-color) !important;
        color: var(--dm-text-muted) !important;
    }

    .feedback-header-btn.btn-outline-primary {
        border-color: #16a085;
        color: #16a085;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .feedback-header-btn.btn-outline-primary:hover,
    .feedback-header-btn.btn-outline-primary:focus {
        background: linear-gradient(135deg, #16a085 0%, #0e7862 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 6px 14px rgba(22, 160, 133, 0.25);
    }

    [data-theme="dark"] .feedback-header-btn.btn-outline-primary {
        border-color: rgba(22, 160, 133, 0.7) !important;
        color: #6ee7b7 !important;
    }

    [data-theme="dark"] .feedback-header-btn.btn-outline-primary:hover,
    [data-theme="dark"] .feedback-header-btn.btn-outline-primary:focus {
        background: linear-gradient(135deg, #16a085 0%, #0e7862 100%) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(23, 148, 123, 0.35) !important;
    }
</style>

<div class="container-fluid feedback-container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width: 50px; height: 50px; background: linear-gradient(135deg, #16a085 0%, #0e7862 100%); box-shadow: 0 4px 12px rgba(22, 160, 133, 0.3);">
                        <i class="bi bi-chat-dots-fill text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 fw-bold" style="color: #16a085;">All Patient Feedback</h2>
                        <small class="text-muted">Complete list of patient reviews and ratings</small>
                    </div>
                </div>
                <a href="{{ route('admin-dashboard') }}" class="btn btn-outline-primary feedback-header-btn">
                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin-feedback') }}" class="row g-4 align-items-end">
                        <div class="col-lg col-md-4 col-6 mb-2 mb-lg-0">
                            <label for="serviceFilter" class="form-label fw-semibold" style="color: #16a085;">
                                <i class="bi bi-funnel me-1"></i>Filter by Service
                            </label>
                            <select name="service" id="serviceFilter" class="form-select" onchange="this.form.submit()">
                                <option value="all" {{ ($serviceFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Services</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ ($serviceFilter ?? '') == $service->id ? 'selected' : '' }}>
                                        {{ $service->service_name }}
                                    </option>
                                @endforeach
                                <option value="other" {{ ($serviceFilter ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-lg col-md-4 col-6 mb-2 mb-lg-0">
                            <label for="ratingFilter" class="form-label fw-semibold" style="color: #16a085;">
                                <i class="bi bi-star me-1"></i>Filter by Rating
                            </label>
                            <select name="rating" id="ratingFilter" class="form-select" onchange="this.form.submit()">
                                <option value="all" {{ ($ratingFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Ratings</option>
                                <option value="5" {{ ($ratingFilter ?? '') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars</option>
                                <option value="4" {{ ($ratingFilter ?? '') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Stars</option>
                                <option value="3" {{ ($ratingFilter ?? '') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3 Stars</option>
                                <option value="2" {{ ($ratingFilter ?? '') == '2' ? 'selected' : '' }}>⭐⭐ 2 Stars</option>
                                <option value="1" {{ ($ratingFilter ?? '') == '1' ? 'selected' : '' }}>⭐ 1 Star</option>
                            </select>
                        </div>
                        <div class="col-lg col-md-4 col-6 mb-2 mb-lg-0">
                            <label for="monthFilter" class="form-label fw-semibold" style="color: #16a085;">
                                <i class="bi bi-calendar-month me-1"></i>Filter by Month
                            </label>
                            <select name="month" id="monthFilter" class="form-select" onchange="this.form.submit()">
                                <option value="all" {{ ($monthFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Months</option>
                                @php
                                    $currentYear = \Carbon\Carbon::now()->year;
                                    
                                    // Generate months for current year (January to December)
                                    for ($month = 1; $month <= 12; $month++) {
                                        $monthValue = $currentYear . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                                        $date = \Carbon\Carbon::create($currentYear, $month, 1);
                                        $monthName = $date->format('F Y');
                                        $isSelected = ($monthFilter ?? '') === $monthValue;
                                        echo "<option value=\"{$monthValue}\"" . ($isSelected ? ' selected' : '') . ">{$monthName}</option>";
                                    }
                                @endphp
                            </select>
                        </div>
                        <div class="col-lg col-md-4 col-6 mb-2 mb-lg-0">
                            <label for="sortFilter" class="form-label fw-semibold" style="color: #16a085;">
                                <i class="bi bi-sort-down me-1"></i>Sort By
                            </label>
                            <select name="sort" id="sortFilter" class="form-select" onchange="this.form.submit()">
                                <option value="newest" {{ ($sortFilter ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest to Oldest</option>
                                <option value="oldest" {{ ($sortFilter ?? '') === 'oldest' ? 'selected' : '' }}>Oldest to Newest</option>
                            </select>
                        </div>
                        <div class="col-lg-auto col-md-4 col-6 mb-2 mb-lg-0">
                            <label class="form-label fw-semibold d-block" style="color: #16a085; visibility: hidden;">Clear</label>
                            <a href="{{ route('admin-feedback') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle me-1"></i>Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="row">
        <div class="col-12">
            @if($allFeedback->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-chat-left-dots" style="font-size: 4rem; opacity: 0.3; color: #16a085;"></i>
                        <p class="mt-3 mb-0 text-muted fs-5">No patient feedback available yet</p>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="feedback-table-wrapper">
                            <table class="feedback-table">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Patient</th>
                                        <th style="width: 18%;">Service</th>
                                        <th style="width: 12%;">Rating</th>
                                        <th style="width: 30%;">Comments</th>
                                        <th style="width: 20%;">Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allFeedback as $feedback)
                                        <tr class="feedback-row">
                                            <td>
                                                <strong style="color: #16a085;">{{ $feedback->patient && $feedback->patient->info
                                                    ? trim($feedback->patient->info->first_name . ' ' . $feedback->patient->info->last_name)
                                                    : ($feedback->patient ? $feedback->patient->name : 'Unknown') }}</strong>
                                            </td>
                                            <td>
                                                <span class="service-name">{{ $feedback->service ? $feedback->service->service_name : 'Other' }}</span>
                                            </td>
                                            <td>
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $feedback->rating)
                                                            <i class="bi bi-star-fill" style="color: #16a085;"></i>
                                                        @else
                                                            <i class="bi bi-star" style="color: #ccc;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($feedback->patient_feedback)
                                                    <div class="feedback-comment">
                                                        <em style="color: #16a085; font-weight: 500;">{{ $feedback->patient_feedback }}</em>
                                                    </div>
                                                @else
                                                    <span class="text-muted fst-italic" style="font-size: 0.9rem;">No comment</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="submission-info">
                                                    <small style="color: #16a085;">
                                                        <i class="bi bi-calendar-check me-1"></i>
                                                        {{ $feedback->rated_at
                                                            ? $feedback->rated_at->timezone('Asia/Manila')->format('M j, Y g:i A')
                                                            : ($feedback->updated_at ? $feedback->updated_at->timezone('Asia/Manila')->format('M j, Y g:i A') : 'N/A') }}
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($allFeedback->hasPages())
                    <div class="pagination-container">
                        <div class="pagination-info">
                            <i class="bi bi-info-circle"></i>
                            <span>Showing <strong>{{ $allFeedback->firstItem() }}</strong> to <strong>{{ $allFeedback->lastItem() }}</strong> of <strong>{{ $allFeedback->total() }}</strong> feedback</span>
                        </div>
                        <div class="pagination-links">
                            <ul class="modern-pagination">
                                {{-- Previous Page --}}
                                <li class="page-item {{ $allFeedback->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $allFeedback->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>

                                {{-- Page Numbers --}}
                                @php
                                    $start = max($allFeedback->currentPage() - 2, 1);
                                    $end = min($allFeedback->currentPage() + 2, $allFeedback->lastPage());
                                @endphp

                                {{-- First page + dots --}}
                                @if ($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $allFeedback->appends(request()->query())->url(1) }}">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="page-item disabled"><span class="page-link dots">...</span></li>
                                    @endif
                                @endif

                                {{-- Page range --}}
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $allFeedback->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $allFeedback->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                {{-- Last page + dots --}}
                                @if ($end < $allFeedback->lastPage())
                                    @if ($end < $allFeedback->lastPage() - 1)
                                        <li class="page-item disabled"><span class="page-link dots">...</span></li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $allFeedback->appends(request()->query())->url($allFeedback->lastPage()) }}">{{ $allFeedback->lastPage() }}</a>
                                    </li>
                                @endif

                                {{-- Next Page --}}
                                <li class="page-item {{ $allFeedback->currentPage() == $allFeedback->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $allFeedback->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@endsection

