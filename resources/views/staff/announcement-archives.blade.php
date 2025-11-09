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
        padding: 1.25rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 5px solid #667eea;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 500px;
    }

    .archive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .archive-date {
        color: #667eea;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .archive-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .archive-subheading {
        font-size: 0.9rem;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .archive-content {
        color: #4a5568;
        line-height: 1.6;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
        min-height: 0;
    }

    .archive-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        font-size: 0.8rem;
        color: #718096;
    }
    
    .archive-meta-section {
        padding-top: 0.75rem;
        border-top: 1px solid #e2e8f0;
        margin-top: 0.75rem;
    }
    
    .archive-date-time {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 0.5rem;
        font-size: 0.8rem;
    }
    
    .archive-date-time-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: #64748b;
    }

    .archive-meta i {
        color: #667eea;
    }

    .archive-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        flex-shrink: 0;
    }
    
    .archive-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    
    .archive-content-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    
    .archive-footer {
        margin-top: auto;
        padding-top: 0.75rem;
    }

    .ticker-badge {
        background: #fbbf24;
        color: #78350f;
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    
    .archive-ticker-box {
        background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: 8px;
        padding: 0.625rem;
        margin-bottom: 0.75rem;
        font-size: 0.8rem;
        line-height: 1.5;
    }
    
    .archive-ticker-box strong {
        font-size: 0.75rem;
        color: #78350f;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        margin-bottom: 0.25rem;
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
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pagination .page-link {
        color: #667eea;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin: 0 2px;
        padding: 0.5rem 1rem;
        min-width: 40px;
        text-align: center;
        background: white;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }

    .pagination .page-link:hover {
        background-color: #667eea;
        color: white !important;
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }

    .pagination .page-item.active .page-link {
        background-color: #667eea !important;
        border-color: #667eea !important;
        color: white !important;
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #f7fafc;
        border-color: #e2e8f0;
        color: #a0aec0;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
        background-color: #f7fafc;
        color: #a0aec0;
    }

    /* Fix chevron arrows in pagination */
    .pagination .page-link svg,
    .pagination .page-link i {
        font-size: 1rem !important;
        width: 1rem !important;
        height: 1rem !important;
    }

    .pagination .page-link[aria-label="Previous"],
    .pagination .page-link[aria-label="Next"] {
        font-size: 1rem !important;
    }

    .pagination .page-link::before,
    .pagination .page-link::after {
        font-size: 1rem !important;
    }

    .pagination li {
        list-style: none;
        display: inline-block;
    }

    .pagination .page-link {
        line-height: 1.5;
        text-decoration: none;
    }

    .pagination .page-link[aria-hidden="true"] {
        font-size: 1rem !important;
    }

    /* Showing results text */
    .pagination-info {
        color: #4a5568;
        font-size: 0.9rem;
        margin-right: 1rem;
    }

    .pagination-info strong {
        color: #667eea;
        font-weight: 600;
    }

    /* Dark Mode Styles */
    [data-theme="dark"] .archive-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }

    [data-theme="dark"] .archive-card {
        background: var(--dm-card-bg, #1e293b) !important;
        border-left-color: #667eea !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .archive-date {
        color: #a78bfa !important;
    }

    [data-theme="dark"] .archive-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .archive-content {
        color: var(--dm-text-secondary, #cbd5e1) !important;
    }
    
    [data-theme="dark"] .archive-subheading {
        color: #a78bfa !important;
    }
    
    [data-theme="dark"] .archive-date-time-item {
        color: var(--dm-text-muted, #94a3b8) !important;
    }
    
    [data-theme="dark"] .archive-ticker-box {
        background: rgba(251, 191, 36, 0.15) !important;
        border-color: rgba(251, 191, 36, 0.4) !important;
        color: var(--dm-text-secondary, #cbd5e1) !important;
    }
    
    [data-theme="dark"] .archive-ticker-box strong {
        color: #fbbf24 !important;
    }

    [data-theme="dark"] .archive-meta {
        border-top-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .archive-meta i {
        color: #667eea !important;
    }

    [data-theme="dark"] .empty-state {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .empty-state i {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .empty-state h4 {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .empty-state p {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .back-button {
        background: var(--dm-bg-secondary, #1e293b) !important;
        color: #a78bfa !important;
        border: 1px solid var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .back-button:hover {
        background: var(--dm-bg-tertiary, #334155) !important;
        color: #c4b5fd !important;
    }

    /* Dark Mode Pagination */
    [data-theme="dark"] .pagination .page-link {
        background: var(--dm-card-bg, #1e293b) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .pagination .page-link:hover {
        background-color: #667eea !important;
        border-color: #667eea !important;
        color: white !important;
    }

    [data-theme="dark"] .pagination .page-item.active .page-link {
        background-color: #667eea !important;
        border-color: #667eea !important;
        color: white !important;
    }

    [data-theme="dark"] .pagination .page-item.disabled .page-link {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        border-color: var(--dm-border-color, #475569) !important;
        color: var(--dm-text-muted, #64748b) !important;
        opacity: 0.5;
    }

    [data-theme="dark"] .pagination .page-item.disabled .page-link:hover {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        color: var(--dm-text-muted, #64748b) !important;
    }

    [data-theme="dark"] .pagination-info {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .pagination-info strong {
        color: #a78bfa !important;
    }

    /* Delete Button */
    .archive-delete-btn {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.1);
        border: 2px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        font-size: 1rem;
    }

    .archive-delete-btn:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    [data-theme="dark"] .archive-delete-btn {
        background: rgba(239, 68, 68, 0.2) !important;
        border-color: rgba(239, 68, 68, 0.4) !important;
        color: #f87171 !important;
    }

    [data-theme="dark"] .archive-delete-btn:hover {
        background: #ef4444 !important;
        color: white !important;
        border-color: #ef4444 !important;
    }

    /* Feedback Modal */
    #feedbackModalContent.success-modal {
        border-top: 4px solid #10b981;
    }

    #feedbackModalContent.error-modal {
        border-top: 4px solid #ef4444;
    }

    #feedbackModal .success-icon {
        color: #10b981;
    }

    #feedbackModal .error-icon {
        color: #ef4444;
    }

    [data-theme="dark"] #feedbackModal .modal-content {
        background: var(--dm-card-bg, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] #feedbackModal .modal-content.success-modal {
        border-top-color: #10b981 !important;
    }

    [data-theme="dark"] #feedbackModal .modal-content.error-modal {
        border-top-color: #ef4444 !important;
    }

    [data-theme="dark"] #feedbackModal #feedbackMessage {
        color: var(--dm-text-muted, #94a3b8) !important;
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
            <div class="row g-3">
                @foreach($archives as $archive)
                    <div class="col-md-6 col-lg-4">
                        <div class="archive-card">
                            <!-- Delete Button -->
                            @if(!$accessControl || $accessControl->can_delete_archives)
                            <button type="button" class="archive-delete-btn" onclick="confirmDeleteArchive({{ $archive->id }}, '{{ addslashes($archive->title) }}')" title="Delete Archive">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            @endif

                            <!-- Header: Date and Ticker Badge -->
                            <div class="archive-card-header">
                                <div class="archive-date">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $archive->archived_at->format('F j, Y') }}
                                </div>
                                @if($archive->ticker_text && $archive->show_ticker)
                                    <div class="ticker-badge">
                                        <i class="bi bi-megaphone-fill me-1"></i>Ticker
                                    </div>
                                @endif
                            </div>

                            <!-- Content Wrapper -->
                            <div class="archive-content-wrapper">
                                <!-- Image if present -->
                                @if($archive->image_path)
                                    <img src="{{ asset('storage/' . $archive->image_path) }}"
                                         alt="{{ $archive->title }}"
                                         class="archive-image">
                                @endif

                                <!-- Title -->
                                <h3 class="archive-title">{{ $archive->title }}</h3>

                                <!-- Subheading if present -->
                                @if($archive->subheading)
                                    <div class="archive-subheading">
                                        {{ Str::limit($archive->subheading, 100) }}
                                    </div>
                                @endif

                                <!-- Content -->
                                <div class="archive-content">
                                    {{ Str::limit($archive->content, 180) }}
                                </div>

                                <!-- Date and Time if present -->
                                @if($archive->date_start || $archive->time_start || $archive->is_whole_day)
                                    <div class="archive-date-time">
                                        @if($archive->date_start)
                                            <div class="archive-date-time-item">
                                                <i class="bi bi-calendar-event"></i>
                                                <span>{{ $archive->formatted_date_range }}</span>
                                            </div>
                                        @endif
                                        @if($archive->time_start || $archive->is_whole_day)
                                            <div class="archive-date-time-item">
                                                <i class="bi bi-clock-fill"></i>
                                                <span>{{ $archive->formatted_time_range }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Ticker Text if present -->
                                @if($archive->ticker_text)
                                    <div class="archive-ticker-box">
                                        <strong><i class="bi bi-megaphone-fill"></i>Ticker</strong>
                                        <div>{{ Str::limit($archive->ticker_text, 120) }}</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer: Meta Information -->
                            <div class="archive-footer">
                                <div class="archive-meta archive-meta-section">
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
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mt-4">
                <div class="pagination-info">
                    Showing <strong>{{ $archives->firstItem() }}</strong>
                    to <strong>{{ $archives->lastItem() }}</strong>
                    of <strong>{{ $archives->total() }}</strong> results
                </div>
                <nav>
                {{ $archives->links() }}
                </nav>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-archive"></i>
                <h4>No Archived Announcements</h4>
                <p>Archive history will appear here when announcements are updated.</p>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteArchiveModal" tabindex="-1" aria-labelledby="deleteArchiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="deleteArchiveModalLabel">Delete Archive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Are you sure you want to delete this archive?</h6>
                <p class="text-muted mb-0" id="deleteArchiveTitle"></p>
                <p class="text-danger small mt-2 mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteArchiveBtn">
                    <i class="bi bi-trash me-1"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" id="feedbackModalContent">
            <div class="modal-body text-center py-4 px-4">
                <div class="mb-3" id="feedbackIcon">
                    <i class="bi" style="font-size: 3.5rem;"></i>
                </div>
                <h6 class="mb-2" id="feedbackTitle"></h6>
                <p class="mb-0 small text-muted" id="feedbackMessage"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-primary btn-sm px-4" data-bs-dismiss="modal" id="feedbackOkBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
let archiveToDelete = null;

function confirmDeleteArchive(archiveId, archiveTitle) {
    archiveToDelete = archiveId;
    document.getElementById('deleteArchiveTitle').textContent = `"${archiveTitle}"`;
    const modal = new bootstrap.Modal(document.getElementById('deleteArchiveModal'));
    modal.show();
}

document.getElementById('confirmDeleteArchiveBtn').addEventListener('click', function() {
    if (!archiveToDelete) return;

    const btn = this;
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deleting...';

    fetch(`/staff/announcement-archives/${archiveToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteArchiveModal'));
        modal.hide();
        
        if (data.success) {
            showFeedbackModal('Archive deleted successfully', 'success');
            // Reload page after modal is closed
            setTimeout(() => {
                const feedbackModal = bootstrap.Modal.getInstance(document.getElementById('feedbackModal'));
                if (feedbackModal) {
                    feedbackModal.hide();
                    setTimeout(() => location.reload(), 300);
                } else {
                    location.reload();
                }
            }, 1500);
        } else {
            showFeedbackModal(data.message || 'Error deleting archive', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteArchiveModal'));
        modal.hide();
        
        showFeedbackModal('Error deleting archive', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    })
    .finally(() => {
        archiveToDelete = null;
    });
});

// Show feedback modal
function showFeedbackModal(message, type = 'success') {
    const modal = document.getElementById('feedbackModal');
    const modalContent = document.getElementById('feedbackModalContent');
    const icon = document.getElementById('feedbackIcon').querySelector('i');
    const title = document.getElementById('feedbackTitle');
    const messageEl = document.getElementById('feedbackMessage');
    
    // Remove existing classes
    modalContent.classList.remove('success-modal', 'error-modal');
    icon.classList.remove('bi-check-circle-fill', 'bi-x-circle-fill', 'success-icon', 'error-icon');
    
    if (type === 'success') {
        modalContent.classList.add('success-modal');
        icon.classList.add('bi-check-circle-fill', 'success-icon');
        title.textContent = 'Success!';
        messageEl.textContent = message;
    } else {
        modalContent.classList.add('error-modal');
        icon.classList.add('bi-x-circle-fill', 'error-icon');
        title.textContent = 'Error';
        messageEl.textContent = message;
    }
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}
</script>

@endsection

