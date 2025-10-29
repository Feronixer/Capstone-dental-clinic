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
}

.filter-btn:hover {
    background: #f8fafc;
}

.filter-btn.active {
    background: #2196F3;
    color: white;
    border-color: #2196F3;
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
    display: flex;
    justify-content: center;
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
        <button class="filter-btn active" data-filter="all" onclick="filterNotifications('all')">
            All
        </button>
        <button class="filter-btn" data-filter="unread" onclick="filterNotifications('unread')">
            Unread ({{ $unreadCount }})
        </button>
        <button class="filter-btn" data-filter="read" onclick="filterNotifications('read')">
            Read
        </button>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list" id="notificationsList">
        @forelse($notifications as $notification)
            <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}"
                 data-id="{{ $notification->id }}"
                 data-read="{{ $notification->is_read ? 'true' : 'false' }}">
                <div class="notification-icon-wrapper {{ $notification->icon_color }}">
                    <i class="bi {{ $notification->icon_class }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header-row">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <span class="notification-time">{{ $notification->time_ago }}</span>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>
                    <div class="notification-actions">
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
            {{ $notifications->links() }}
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
</style>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let currentNotificationId = null;

// Filter notifications
function filterNotifications(filter) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Filter cards
    const cards = document.querySelectorAll('.notification-card');
    cards.forEach(card => {
        const isRead = card.dataset.read === 'true';

        if (filter === 'all') {
            card.style.display = 'flex';
        } else if (filter === 'unread' && !isRead) {
            card.style.display = 'flex';
        } else if (filter === 'read' && isRead) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
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

