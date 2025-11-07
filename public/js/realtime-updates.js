/**
 * Global Real-Time Updates System
 * Polls for notifications and updates across the entire system
 */

(function() {
    'use strict';

    // Configuration
    const POLL_INTERVAL = 5000; // 5 seconds
    const NOTIFICATION_POLL_INTERVAL = 5000; // 5 seconds for notifications
    const APPOINTMENT_POLL_INTERVAL = 10000; // 10 seconds for appointments

    // State
    let lastNotificationCheck = null;
    let lastAppointmentCheck = null;
    let notificationPollInterval = null;
    let appointmentPollInterval = null;
    let previousUnreadCount = 0;

    /**
     * Initialize real-time updates
     */
    function init() {
        // Start notification polling if user is authenticated
        if (window.userRole) {
            startNotificationPolling();
            
            // Start appointment polling if on calendar/appointment pages
            if (window.location.pathname.includes('/calendar') || 
                window.location.pathname.includes('/appointment')) {
                startAppointmentPolling();
            }
        }

        // Request browser notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    /**
     * Start polling for notifications
     */
    function startNotificationPolling() {
        // Load initial notifications
        pollNotifications();
        
        // Poll every 5 seconds
        if (notificationPollInterval) {
            clearInterval(notificationPollInterval);
        }
        notificationPollInterval = setInterval(pollNotifications, NOTIFICATION_POLL_INTERVAL);
    }

    /**
     * Poll for new notifications
     */
    async function pollNotifications() {
        try {
            const url = lastNotificationCheck 
                ? `/patient/notifications/poll?last_check=${encodeURIComponent(lastNotificationCheck)}`
                : '/patient/notifications/recent';
            
            const response = await fetch(url);
            if (!response.ok) return;
            
            const data = await response.json();

            // Check if unread count increased (new notification)
            const currentUnreadCount = data.unread_count || 0;
            if (previousUnreadCount !== undefined && currentUnreadCount > previousUnreadCount) {
                // New notification received
                handleNewNotification(data);
            }
            previousUnreadCount = currentUnreadCount;

            // Update last check timestamp
            if (data.timestamp) {
                lastNotificationCheck = data.timestamp;
            } else if (data.notifications && data.notifications.length > 0) {
                lastNotificationCheck = data.notifications[0].created_at;
            }

            // Update notification badge and list
            updateNotificationBadge(data.unread_count);
            updateNotificationSubtitle(data.unread_count);
            
            // Update notifications list if dropdown is open or we have new notifications
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown && (dropdown.classList.contains('show') || data.has_new)) {
                if (data.has_new && data.notifications) {
                    updateNotificationList(data.notifications);
                } else if (!lastNotificationCheck) {
                    // Initial load
                    if (typeof renderNotifications === 'function') {
                        renderNotifications(data.notifications || []);
                    }
                }
            }
        } catch (error) {
            console.error('Error polling notifications:', error);
        }
    }

    /**
     * Handle new notification
     */
    function handleNewNotification(data) {
        // Play sound if function exists
        if (typeof playNotificationSound === 'function') {
            playNotificationSound();
        }
        
        // Show browser notification if permission granted
        if (Notification.permission === 'granted' && data.has_new) {
            const newNotif = data.notifications && data.notifications[0];
            if (newNotif) {
                new Notification(newNotif.title, {
                    body: newNotif.message,
                    icon: '/images/logo4.png',
                    tag: 'notification-' + newNotif.id
                });
            }
        }

        // Trigger custom event for other scripts to listen
        window.dispatchEvent(new CustomEvent('newNotification', {
            detail: data
        }));
    }

    /**
     * Update notification badge
     */
    function updateNotificationBadge(count) {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    /**
     * Update notification subtitle
     */
    function updateNotificationSubtitle(count) {
        const subtitle = document.getElementById('notificationSubtitle');
        if (subtitle) {
            if (count === 0) {
                subtitle.textContent = 'No unread notifications';
            } else if (count === 1) {
                subtitle.textContent = 'You have 1 unread notification';
            } else {
                subtitle.textContent = `You have ${count} unread notifications`;
            }
        }
    }

    /**
     * Update notification list
     */
    function updateNotificationList(newNotifications) {
        if (typeof renderNotifications === 'function') {
            const existingNotifications = getCurrentNotifications();
            const allNotifications = [...newNotifications, ...existingNotifications]
                .filter((v, i, a) => a.findIndex(t => t.id === v.id) === i)
                .slice(0, 5);
            renderNotifications(allNotifications);
        }
    }

    /**
     * Get current notifications from DOM
     */
    function getCurrentNotifications() {
        const container = document.getElementById('notificationsList');
        if (!container) return [];
        
        const items = container.querySelectorAll('.notification-item');
        const notifications = [];
        items.forEach(item => {
            const title = item.querySelector('.notification-title')?.textContent;
            const message = item.querySelector('small')?.textContent;
            const timeAgo = item.querySelector('.notification-time')?.textContent;
            const isRead = !item.classList.contains('unread');
            const iconClass = item.querySelector('.bi')?.className.match(/bi-[\w-]+/)?.[0];
            const iconColor = item.querySelector('.notification-icon')?.className.match(/bg-\w+/)?.[0];
            
            if (title) {
                notifications.push({
                    title,
                    message,
                    time_ago: timeAgo,
                    is_read: isRead,
                    icon_class: iconClass,
                    icon_color: iconColor
                });
            }
        });
        return notifications;
    }

    /**
     * Start polling for appointments
     */
    function startAppointmentPolling() {
        // Poll every 10 seconds
        if (appointmentPollInterval) {
            clearInterval(appointmentPollInterval);
        }
        appointmentPollInterval = setInterval(pollAppointments, APPOINTMENT_POLL_INTERVAL);
    }

    /**
     * Poll for appointment updates
     */
    async function pollAppointments() {
        try {
            const url = lastAppointmentCheck 
                ? `/patient/calendar/poll?last_check=${encodeURIComponent(lastAppointmentCheck)}`
                : '/patient/calendar/poll';
            
            const response = await fetch(url);
            if (!response.ok) return;
            
            const data = await response.json();

            if (data.has_updates) {
                // Trigger custom event for appointment updates
                window.dispatchEvent(new CustomEvent('appointmentUpdate', {
                    detail: data
                }));

                // Call refresh function if it exists
                if (typeof refreshAppointments === 'function') {
                    refreshAppointments(data);
                }
            }

            // Update last check timestamp
            if (data.timestamp) {
                lastAppointmentCheck = data.timestamp;
            }
        } catch (error) {
            console.error('Error polling appointments:', error);
        }
    }

    /**
     * Stop all polling
     */
    function stopPolling() {
        if (notificationPollInterval) {
            clearInterval(notificationPollInterval);
            notificationPollInterval = null;
        }
        if (appointmentPollInterval) {
            clearInterval(appointmentPollInterval);
            appointmentPollInterval = null;
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Stop polling when page is hidden (to save resources)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopPolling();
        } else {
            init();
        }
    });

    // Export functions for global access
    window.realtimeUpdates = {
        startNotificationPolling,
        startAppointmentPolling,
        stopPolling,
        pollNotifications,
        pollAppointments
    };

})();

