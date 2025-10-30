@extends('layout.staff.app')
@section('content')
<style>
    .archive-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 30px 30px;
    }

    .archive-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 5px solid #667eea;
    }

    .archive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .archive-date {
        color: #667eea;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .archive-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.75rem;
    }

    .archive-content {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .archive-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.875rem;
        color: #718096;
    }

    .archive-meta i {
        color: #667eea;
    }

    .archive-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .ticker-badge {
        background: #fbbf24;
        color: #78350f;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: #4a5568;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #718096;
    }

    .back-button {
        background: white;
        color: #667eea;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .back-button:hover {
        background: #f7fafc;
        transform: translateX(-5px);
        color: #667eea;
        text-decoration: none;
    }

    /* Pagination Styling */
    .pagination {
        margin-top: 2rem;
    }

    .pagination .page-link {
        color: #667eea;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        margin: 0 3px;
    }

    .pagination .page-link:hover {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="archive-header text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-2">
                <i class="bi bi-archive me-2"></i>Announcement Archive
            </h1>
            <p class="lead mb-4">Previous announcements and updates from our clinic.</p>
            <a href="{{ route('staff-content-management') }}" class="back-button">
                <i class="bi bi-arrow-left me-2"></i>Back to Content Management
            </a>
        </div>
    </div>

    <!-- Archives Content -->
    <div class="container">
        @if($archives->count() > 0)
            <div class="row">
                @foreach($archives as $archive)
                    <div class="col-md-6 col-lg-4">
                        <div class="archive-card">
                            <!-- Date -->
                            <div class="archive-date">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $archive->archived_at->format('F j, Y') }}
                            </div>

                            <!-- Ticker Badge if present -->
                            @if($archive->ticker_text && $archive->show_ticker)
                                <div class="ticker-badge">
                                    <i class="bi bi-megaphone-fill me-1"></i>Ticker Included
                                </div>
                            @endif

                            <!-- Image if present -->
                            @if($archive->image_path)
                                <img src="{{ asset('storage/' . $archive->image_path) }}"
                                     alt="{{ $archive->title }}"
                                     class="archive-image">
                            @endif

                            <!-- Title -->
                            <h3 class="archive-title">{{ $archive->title }}</h3>

                            <!-- Content -->
                            <div class="archive-content">
                                {{ Str::limit($archive->content, 150) }}
                            </div>

                            <!-- Ticker Text if present -->
                            @if($archive->ticker_text)
                                <div class="alert alert-warning mb-3" style="font-size: 0.875rem;">
                                    <strong><i class="bi bi-megaphone-fill me-1"></i>Ticker:</strong><br>
                                    {{ Str::limit($archive->ticker_text, 100) }}
                                </div>
                            @endif

                            <!-- Meta Information -->
                            <div class="archive-meta">
                                <div>
                                    <i class="bi bi-person-circle"></i>
                                    <strong>By:</strong> {{ $archive->archivedBy->name ?? 'Unknown' }}
                                </div>
                                <div>
                                    <i class="bi bi-clock"></i>
                                    {{ $archive->archived_at->format('g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $archives->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-archive"></i>
                <h4>No Archived Announcements</h4>
                <p>Archive history will appear here when announcements are updated.</p>
                <a href="{{ route('staff-content-management') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left me-2"></i>Go to Content Management
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

