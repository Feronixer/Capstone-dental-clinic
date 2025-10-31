@extends('layout.patient.app')
@section('content')
<style>
.notifications-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
}

.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.notifications-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.header-actions-group {
    display: flex;
    gap: 1rem;
}

.btn-mark-all-read,
.btn-clear-read {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-mark-all-read {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
}

.btn-mark-all-read:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.btn-clear-read {
    background: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.btn-clear-read:hover {
    background: #e2e8f0;
}

.notifications-filters {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: white;
    color: #64748b;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.filter-btn:hover {
    background: #f8fafc;
    color: #64748b;
    text-decoration: none;
}

.filter-btn.active {
    background: #2196F3;
    color: white;
    border-color: #2196F3;
}

.filter-btn.active:hover {
    background: #1976D2;
    color: white;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s;
    display: flex;
    gap: 1rem;
    position: relative;
    border-left: 4px solid transparent;
}

.notification-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.notification-card.unread {
    background: linear-gradient(to right, #f0f9ff 0%, #ffffff 100%);
    border-left-color: #2196F3;
}

.notification-icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-icon-wrapper i {
    font-size: 1.5rem;
    color: white;
}

.notification-content {
    flex: 1;
}

.notification-header-row {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 0.5rem;
}

.notification-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e293b;
    margin: 0;
}

.notification-time {
    font-size: 0.85rem;
    color: #94a3b8;
    white-space: nowrap;
}

.notification-message {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 0.75rem;
}

.notification-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-mark-read {
    background: #e0e7ff;
    color: #3730a3;
}

.btn-mark-read:hover {
    background: #c7d2fe;
}

.btn-mark-unread {
    background: #fef3c7;
    color: #92400e;
}

.btn-mark-unread:hover {
    background: #fde68a;
}

.btn-delete {
    background: #fee2e2;
    color: #991b1b;
}

.btn-delete:hover {
    background: #fecaca;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.empty-icon i {
    font-size: 2.5rem;
    color: #cbd5e1;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #64748b;
}

.pagination-wrapper {
    margin-top: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.pagination-info {
    color: #64748b;
    font-size: 0.95rem;
}

.pagination-info strong {
    color: #1e293b;
    font-weight: 600;
}

.pagination {
    gap: 0.25rem;
}

.pagination .page-item {
    margin: 0;
}

.pagination .page-link {
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
}

.pagination .page-link:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border-color: #2196F3;
    color: white;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
}

.pagination .page-item.disabled .page-link {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #cbd5e1;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination .page-link i {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .notifications-container {
        padding: 1rem;
    }

    .notifications-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }

    .header-actions-group {
        flex-direction: column;
    }

    .notification-card {
        padding: 1rem;
    }

    .notification-header-row {
        flex-direction: column;
        gap: 0.5rem;
    }

    .pagination-wrapper {
        padding: 1rem;
    }

    .pagination-wrapper > div {
        flex-direction: column;
        gap: 1rem !important;
    }

    .pagination-info {
        text-align: center;
    }

    .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }

    .pagination .page-link {
        padding: 0.4rem 0.6rem;
        min-width: 35px;
        font-size: 0.9rem;
    }
}

/* ============================================
   DARK MODE STYLES FOR PATIENT NOTIFICATIONS
   ============================================ */

/* Container Dark Mode */
[data-theme="dark"] .notifications-container {
    background: transparent !important;
}

/* Header Dark Mode */
[data-theme="dark"] .notifications-title {
    color: var(--dm-text-primary) !important;
}

/* Action Buttons Dark Mode */
[data-theme="dark"] .btn-mark-all-read {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
    color: white !important;
}

[data-theme="dark"] .btn-mark-all-read:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%) !important;
}

[data-theme="dark"] .btn-clear-read {
    background: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .btn-clear-read:hover {
    background: var(--dm-bg-tertiary) !important;
    color: var(--dm-text-primary) !important;
}

/* Filter Buttons Dark Mode */
[data-theme="dark"] .filter-btn {
    background: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .filter-btn:hover {
    background: var(--dm-bg-tertiary) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .filter-btn.active {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
    color: white !important;
    border-color: #2196F3 !important;
}

[data-theme="dark"] .filter-btn.active:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%) !important;
    color: white !important;
}

/* Notification Cards Dark Mode */
[data-theme="dark"] .notification-card {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .notification-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5) !important;
    background: var(--dm-bg-secondary) !important;
}

[data-theme="dark"] .notification-card.unread {
    background: linear-gradient(to right, rgba(33, 150, 243, 0.15) 0%, var(--dm-card-bg) 100%) !important;
    border-left-color: #2196F3 !important;
}

/* Notification Content Dark Mode */
[data-theme="dark"] .notification-title {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .notification-time {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .notification-message {
    color: var(--dm-text-primary) !important;
}

/* Action Buttons in Cards Dark Mode */
[data-theme="dark"] .btn-mark-read {
    background: rgba(59, 130, 246, 0.2) !important;
    color: #60a5fa !important;
    border: 1px solid rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .btn-mark-read:hover {
    background: rgba(59, 130, 246, 0.3) !important;
    color: #93c5fd !important;
}

[data-theme="dark"] .btn-mark-unread {
    background: rgba(245, 158, 11, 0.2) !important;
    color: #fbbf24 !important;
    border: 1px solid rgba(245, 158, 11, 0.3) !important;
}

[data-theme="dark"] .btn-mark-unread:hover {
    background: rgba(245, 158, 11, 0.3) !important;
    color: #fcd34d !important;
}

[data-theme="dark"] .btn-delete {
    background: rgba(239, 68, 68, 0.2) !important;
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.3) !important;
}

[data-theme="dark"] .btn-delete:hover {
    background: rgba(239, 68, 68, 0.3) !important;
    color: #fca5a5 !important;
}

/* Empty State Dark Mode */
[data-theme="dark"] .empty-icon {
    background: var(--dm-bg-secondary) !important;
}

[data-theme="dark"] .empty-icon i {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .empty-title {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .empty-text {
    color: var(--dm-text-muted) !important;
}

/* Pagination Dark Mode */
[data-theme="dark"] .pagination-wrapper {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
}

[data-theme="dark"] .pagination-info {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination-info strong {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination .page-link {
    background: var(--dm-card-bg) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination .page-link:hover {
    background: var(--dm-bg-tertiary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
    border-color: #2196F3 !important;
    color: white !important;
}

[data-theme="dark"] .pagination .page-item.disabled .page-link {
    background: var(--dm-bg-secondary) !important;
    border-color: var(--dm-border-color) !important;
    color: var(--dm-text-muted) !important;
}

/* Modal Dark Mode */
[data-theme="dark"] .modern-modal .modal-header {
    background: linear-gradient(135deg, var(--dm-bg-tertiary) 0%, var(--dm-bg-secondary) 100%) !important;
}

[data-theme="dark"] .modern-modal .modal-title {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modern-modal .modal-body {
    background: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modern-modal .modal-body p {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .modern-modal .modal-body small.text-muted {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .modern-modal .modal-footer {
    background: var(--dm-bg-tertiary) !important;
}

[data-theme="dark"] .modern-modal .btn-close {
    filter: brightness(0) invert(1) !important;
    opacity: 0.8;
}

[data-theme="dark"] .modern-modal .btn-close:hover {
    opacity: 1 !important;
}

/* Confirm Icons Dark Mode */
[data-theme="dark"] .confirm-icon {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.2) 100%) !important;
}

[data-theme="dark"] .confirm-icon.danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%) !important;
}

[data-theme="dark"] .confirm-icon.warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%) !important;
}

/* Buttons in Modals Dark Mode */
[data-theme="dark"] .modern-modal .btn-secondary {
    background: var(--dm-bg-tertiary) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .modern-modal .btn-secondary:hover {
    background: var(--dm-bg-secondary) !important;
    color: var(--dm-text-primary) !important;
}

/* Notification View Modal Dark Mode */
[data-theme="dark"] .notification-modal-body {
    background: var(--dm-card-bg) !important;
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .notification-timestamp {
    color: var(--dm-text-muted) !important;
}

[data-theme="dark"] .notification-message-view {
    color: var(--dm-text-primary) !important;
}

[data-theme="dark"] .notification-modal-footer {
    background: var(--dm-bg-tertiary) !important;
    border-top-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .btn-close-modal {
    background: var(--dm-bg-secondary) !important;
    color: var(--dm-text-primary) !important;
    border-color: var(--dm-border-color) !important;
}

[data-theme="dark"] .btn-close-modal:hover {
    background: var(--dm-bg-tertiary) !important;
    color: var(--dm-text-primary) !important;
}
</style>

<div class="notifications-container">
    <!-- Header -->
    <div class="notifications-header">
        <h1 class="notifications-title">
            <i class="bi bi-bell me-2"></i>Notifications
        </h1>
        <div class="header-actions-group">
            @if($unreadCount > 0)
                <button class="btn-mark-all-read" onclick="markAllAsRead()">
                    <i class="bi bi-check-all"></i>
                    Mark All as Read
                </button>
            @endif
            <button class="btn-clear-read" onclick="clearReadNotifications()">
                <i class="bi bi-trash"></i>
                Clear Read
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="notifications-filters">
        <a href="{{ route('patient-notifications') }}" class="filter-btn {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}" data-filter="all">
            All
        </a>
        <a href="{{ route('patient-notifications', ['filter' => 'unread']) }}" class="filter-btn {{ request('filter') == 'unread' ? 'active' : '' }}" data-filter="unread">
            Unread ({{ $unreadCount }})
        </a>
        <a href="{{ route('patient-notifications', ['filter' => 'read']) }}" class="filter-btn {{ request('filter') == 'read' ? 'active' : '' }}" data-filter="read">
            Read
        </a>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list" id="notificationsList">
        @forelse($notifications as $notification)
            <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}"
                 data-id="{{ $notification->id }}"
                 data-read="{{ $notification->is_read ? 'true' : 'false' }}"
                 onclick="viewNotification({{ $notification->id }})"
                 style="cursor: pointer;">
                <div class="notification-icon-wrapper {{ $notification->icon_color }}">
                    <i class="bi {{ $notification->icon_class }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header-row">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <span class="notification-time">{{ $notification->time_ago }}</span>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>
                    <div class="notification-actions" onclick="event.stopPropagation();">
                        @if(!$notification->is_read)
                            <button class="btn-action btn-mark-read" onclick="markAsRead({{ $notification->id }})">
                                <i class="bi bi-check"></i>
                                Mark as Read
                            </button>
                        @else
                            <button class="btn-action btn-mark-unread" onclick="markAsUnread({{ $notification->id }})">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Mark as Unread
                            </button>
                        @endif
                        <button class="btn-action btn-delete" onclick="deleteNotification({{ $notification->id }})">
                            <i class="bi bi-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-bell-slash"></i>
                </div>
                <h3 class="empty-title">No Notifications</h3>
                <p class="empty-text">You don't have any notifications yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="pagination-wrapper">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Showing Data -->
                <div class="pagination-info">
                    Showing <strong>{{ $notifications->firstItem() }}</strong>
                    to <strong>{{ $notifications->lastItem() }}</strong>
                    of <strong>{{ $notifications->total() }}</strong> results
                </div>

                <!-- Pagination Controls -->
                <nav aria-label="Notification pagination">
                    <ul class="pagination mb-0">
                        {{-- Previous Page --}}
                        <li class="page-item {{ $notifications->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $notifications->previousPageUrl() }}" aria-label="Previous">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Page Numbers --}}
                        @php
                            $start = max($notifications->currentPage() - 1, 1);
                            $end = min($notifications->currentPage() + 1, $notifications->lastPage());
                        @endphp

                        {{-- First page --}}
                        @if ($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->url(1) }}">1</a>
                            </li>
                            @if ($start > 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        {{-- Page range --}}
                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $notifications->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $notifications->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Last page --}}
                        @if ($end < $notifications->lastPage())
                            @if ($end < $notifications->lastPage() - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->url($notifications->lastPage()) }}">{{ $notifications->lastPage() }}</a>
                            </li>
                        @endif

                        {{-- Next Page --}}
                        <li class="page-item {{ $notifications->currentPage() == $notifications->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $notifications->nextPageUrl() }}" aria-label="Next">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>

<!-- Mark All as Read Modal -->
<div class="modal fade" id="markAllReadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-check-all me-2 text-primary"></i>Mark All as Read
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="confirm-icon mb-3">
                    <i class="bi bi-question-circle"></i>
                </div>
                <p class="mb-0">Are you sure you want to mark all notifications as read?</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmMarkAllAsRead()">
                    <i class="bi bi-check-all me-1"></i>Yes, Mark All as Read
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Notification Modal -->
<div class="modal fade" id="deleteNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-trash me-2 text-danger"></i>Delete Notification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="confirm-icon danger mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <p class="mb-0">Are you sure you want to delete this notification?</p>
                <small class="text-muted">This action cannot be undone.</small>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteNotification()">
                    <i class="bi bi-trash me-1"></i>Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Read Notifications Modal -->
<div class="modal fade" id="clearReadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-trash me-2 text-warning"></i>Clear Read Notifications
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="confirm-icon warning mb-3">
                    <i class="bi bi-question-circle"></i>
                </div>
                <p class="mb-0">Are you sure you want to delete all read notifications?</p>
                <small class="text-muted">This will permanently remove all notifications you've already read.</small>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmClearRead()">
                    <i class="bi bi-trash me-1"></i>Yes, Clear All Read
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content notification-modal-content">
            <div class="notification-modal-header" id="viewNotificationHeader">
                <div class="d-flex align-items-center gap-3">
                    <div class="notification-icon-wrapper-view" id="viewNotificationIcon">
                        <i class="bi" id="viewNotificationIconClass"></i>
                    </div>
                    <h5 class="notification-modal-title mb-0" id="viewNotificationTitle">Notification Details</h5>
                </div>
                <button type="button" class="notification-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="notification-modal-body" id="viewNotificationBody">
                <div class="notification-meta-info">
                    <span class="notification-timestamp" id="viewNotificationTime"></span>
                    <span class="notification-status-badge" id="viewNotificationStatus"></span>
                </div>
                <div class="notification-message-view" id="viewNotificationMessage"></div>
            </div>
            <div class="notification-modal-footer">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modern-modal {
    border-radius: 16px;
    border: none;
    overflow: hidden;
}

.modern-modal .modal-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 1.5rem;
}

.modern-modal .modal-title {
    font-weight: 700;
    font-size: 1.25rem;
    color: #1e293b;
}

.modern-modal .modal-body {
    padding: 2rem;
}

.modern-modal .modal-footer {
    padding: 1.5rem;
    background: #f8fafc;
}

.confirm-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}

.confirm-icon i {
    font-size: 2.5rem;
    color: #2196F3;
}

.confirm-icon.danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
}

.confirm-icon.danger i {
    color: #ef4444;
}

.confirm-icon.warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
}

.confirm-icon.warning i {
    color: #f59e0b;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
    color: white;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    color: white;
}

.btn-secondary {
    background: #e2e8f0;
    color: #64748b;
    border: none;
}

.btn-secondary:hover {
    background: #cbd5e1;
    color: #475569;
}

/* Notification View Modal Styles */
.notification-modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    max-width: 550px;
    width: 100%;
}

.notification-modal-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 1.5rem 1.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: none;
}

.notification-icon-wrapper-view {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.notification-icon-wrapper-view i {
    font-size: 1.5rem;
    color: white;
}

.notification-modal-title {
    font-weight: 700;
    font-size: 1.35rem;
    color: white;
    margin: 0;
}

.notification-modal-close {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.5rem;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
}

.notification-modal-close:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.notification-modal-body {
    padding: 1.75rem;
    background: white;
}

.notification-meta-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}

.notification-timestamp {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.notification-status-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.notification-status-badge.badge-read {
    background: #10b981;
    color: white;
}

.notification-status-badge.badge-unread {
    background: #3b82f6;
    color: white;
}

.notification-message-view {
    color: #1e293b;
    line-height: 1.7;
    font-size: 1rem;
    white-space: pre-wrap;
    word-wrap: break-word;
    margin-bottom: 0;
}

.notification-modal-footer {
    padding: 1.25rem 1.75rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
}

.btn-close-modal {
    background: #e2e8f0;
    color: #64748b;
    border: none;
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-close-modal:hover {
    background: #cbd5e1;
    color: #475569;
}

/* Icon color adjustments for different notification types */
.notification-icon-wrapper-view.bg-success {
    background: rgba(16, 185, 129, 0.2) !important;
}

.notification-icon-wrapper-view.bg-warning {
    background: rgba(245, 158, 11, 0.2) !important;
}

.notification-icon-wrapper-view.bg-info {
    background: rgba(59, 130, 246, 0.2) !important;
}

.notification-icon-wrapper-view.bg-danger {
    background: rgba(239, 68, 68, 0.2) !important;
}

.notification-icon-wrapper-view.bg-primary {
    background: rgba(59, 130, 246, 0.2) !important;
}

.notification-modal-header.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.notification-modal-header.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.notification-modal-header.bg-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

.notification-modal-header.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

.notification-modal-header.bg-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}
</style>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let currentNotificationId = null;

// View notification details
async function viewNotification(id) {
    try {
        const response = await fetch(`/patient/notifications/${id}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            const notification = data.notification;
            const modal = new bootstrap.Modal(document.getElementById('viewNotificationModal'));

            // Update modal header with icon color
            const header = document.getElementById('viewNotificationHeader');
            header.className = `notification-modal-header ${notification.icon_color}`;

            // Update modal content
            document.getElementById('viewNotificationTitle').textContent = notification.title;
            document.getElementById('viewNotificationMessage').textContent = notification.message;
            document.getElementById('viewNotificationTime').textContent = notification.created_at;
            document.getElementById('viewNotificationIconClass').className = `bi ${notification.icon_class}`;
            document.getElementById('viewNotificationIcon').className = `notification-icon-wrapper-view ${notification.icon_color}`;

            // Update status badge
            const statusBadge = document.getElementById('viewNotificationStatus');
            if (notification.is_read) {
                statusBadge.textContent = 'Read';
                statusBadge.className = 'notification-status-badge badge-read';
            } else {
                statusBadge.textContent = 'Unread';
                statusBadge.className = 'notification-status-badge badge-unread';
            }

            modal.show();

            // Reload page after modal is closed if notification was unread (to update UI)
            if (!notification.is_read) {
                const modalElement = document.getElementById('viewNotificationModal');
                modalElement.addEventListener('hidden.bs.modal', function handler() {
                    location.reload();
                    modalElement.removeEventListener('hidden.bs.modal', handler);
                });
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load notification details');
    }
}

// Mark notification as read
async function markAsRead(id) {
    try {
        const response = await fetch(`/patient/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to mark notification as read');
    }
}

// Mark notification as unread
async function markAsUnread(id) {
    try {
        const response = await fetch(`/patient/notifications/${id}/unread`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to mark notification as unread');
    }
}

// Show mark all as read modal
function markAllAsRead() {
    const modal = new bootstrap.Modal(document.getElementById('markAllReadModal'));
    modal.show();
}

// Confirm mark all as read
async function confirmMarkAllAsRead() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('markAllReadModal'));
    modal.hide();

    try {
        const response = await fetch('/patient/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to mark all as read');
    }
}

// Show delete notification modal
function deleteNotification(id) {
    currentNotificationId = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteNotificationModal'));
    modal.show();
}

// Confirm delete notification
async function confirmDeleteNotification() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteNotificationModal'));
    modal.hide();

    try {
        const response = await fetch(`/patient/notifications/${currentNotificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete notification');
    }
}

// Show clear read notifications modal
function clearReadNotifications() {
    const modal = new bootstrap.Modal(document.getElementById('clearReadModal'));
    modal.show();
}

// Confirm clear read notifications
async function confirmClearRead() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('clearReadModal'));
    modal.hide();

    try {
        const response = await fetch('/patient/notifications/clear-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to clear notifications');
    }
}
</script>
@endsection

