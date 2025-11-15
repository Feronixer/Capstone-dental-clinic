/**
 * Real-Time Broadcasting System for Admin and Staff
 * Listens to Laravel broadcast events and updates the UI in real-time
 */

(function() {
    'use strict';

    // Configuration
    const BROADCAST_ENDPOINT = '/broadcasting/events';
    const RECONNECT_DELAY = 3000; // 3 seconds
    const MAX_RECONNECT_ATTEMPTS = 10;

    // State
    let eventSource = null;
    let reconnectAttempts = 0;
    let isConnected = false;
    let lastEventId = null;

    /**
     * Initialize real-time broadcasting
     */
    function init() {
        // Initialize for admin, staff, or patients (on calendar page)
        const userRole = window.userRole || (window.location.pathname.includes('/admin/') ? 'admin' : 
                        (window.location.pathname.includes('/staff/') ? 'staff' : 
                        (window.location.pathname.includes('/patient/calendar') ? 'patient' : null)));
        
        if (!userRole) {
            return;
        }

        connect();
    }

    /**
     * Connect to event stream (using polling)
     */
    function connect() {
        if (eventSource) {
            return; // Already connected
        }

        // Use polling for better compatibility
        isConnected = true;
        reconnectAttempts = 0;
        console.log('[Real-Time] Connected to broadcast stream (polling mode)');
        
        // Start polling
        pollForEvents();
    }

    /**
     * Poll for new events
     */
    function pollForEvents() {
        if (!isConnected || document.hidden) {
            return;
        }

        const url = BROADCAST_ENDPOINT + (lastEventId ? `?last_event_id=${lastEventId}` : '');
        
        fetch(url, { credentials: 'same-origin' })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch events');
                }
                return response.json();
            })
            .then(data => {
                if (data.events && data.events.length > 0) {
                    data.events.forEach(event => {
                        handleBroadcastEvent({
                            event: event.event,
                            data: event.data
                        });
                        lastEventId = event.id;
                    });
                }
                
                // Update last event ID
                if (data.last_event_id) {
                    lastEventId = data.last_event_id;
                }
                
                // Schedule next poll
                setTimeout(pollForEvents, 2000); // Poll every 2 seconds
            })
            .catch(error => {
                console.error('[Real-Time] Polling error:', error);
                reconnectAttempts++;
                
                if (reconnectAttempts < MAX_RECONNECT_ATTEMPTS) {
                    setTimeout(pollForEvents, RECONNECT_DELAY);
                } else {
                    console.error('[Real-Time] Max reconnection attempts reached.');
                    isConnected = false;
                }
            });
    }


    /**
     * Handle broadcast event
     */
    function handleBroadcastEvent(data) {
        if (!data || !data.event) {
            return;
        }

        switch (data.event) {
            case 'appointment.created':
                handleAppointmentCreated(data.data);
                break;
            case 'appointment.updated':
                handleAppointmentUpdated(data.data);
                break;
            case 'appointment.deleted':
                handleAppointmentDeleted(data.data);
                break;
            case 'blocked-time.updated':
                handleBlockedTimeUpdated(data.data);
                break;
            case 'staff-access-control.updated':
                handleStaffAccessControlUpdated(data.data);
                break;
        }
    }

    /**
     * Handle appointment created event
     */
    function handleAppointmentCreated(data) {
        console.log('[Real-Time] Appointment created:', data);
        
        // Ensure we have appointment data
        if (!data.appointment) {
            console.error('[Real-Time] No appointment data in create event');
            return;
        }

        // Trigger custom event
        window.dispatchEvent(new CustomEvent('appointmentCreated', {
            detail: data
        }));

        // Add/update appointment in calendar - try updateAppointmentInCalendar first
        if (typeof updateAppointmentInCalendar === 'function') {
            console.log('[Real-Time] Using updateAppointmentInCalendar function for new appointment');
            updateAppointmentInCalendar(data.appointment);
        } else if (typeof refreshAppointments === 'function') {
            console.log('[Real-Time] Using refreshAppointments function for new appointment');
            refreshAppointments({ appointments: [data.appointment] });
        } else if (typeof loadAppointments === 'function') {
            console.log('[Real-Time] Using loadAppointments function (full refresh) for new appointment');
            loadAppointments();
        } else {
            console.warn('[Real-Time] No update function found! Calendar may not update.');
        }
    }

    /**
     * Handle appointment updated event
     */
    function handleAppointmentUpdated(data) {
        console.log('[Real-Time] Appointment updated event received:', data);
        
        // Don't update if this update was made by the current user
        const currentUserId = window.currentUserId || null;
        if (data.updated_by && currentUserId && parseInt(data.updated_by) === parseInt(currentUserId)) {
            console.log('[Real-Time] Skipping update - made by current user');
            return; // Skip update if it was made by current user
        }

        // Ensure we have appointment data
        if (!data.appointment) {
            console.error('[Real-Time] No appointment data in update event');
            return;
        }

        console.log('[Real-Time] Processing appointment update:', {
            id: data.appointment.id,
            status: data.appointment.status,
            action: data.action
        });

        // Trigger custom event
        window.dispatchEvent(new CustomEvent('appointmentUpdated', {
            detail: data
        }));

        // Update appointment in calendar - try updateAppointmentInCalendar first
        if (typeof updateAppointmentInCalendar === 'function') {
            console.log('[Real-Time] Using updateAppointmentInCalendar function');
            updateAppointmentInCalendar(data.appointment);
        } else if (typeof refreshAppointments === 'function') {
            console.log('[Real-Time] Using refreshAppointments function');
            refreshAppointments({ appointments: [data.appointment] });
        } else if (typeof loadAppointments === 'function') {
            console.log('[Real-Time] Using loadAppointments function (full refresh)');
            loadAppointments();
        } else {
            console.warn('[Real-Time] No update function found! Calendar may not update.');
        }
    }

    /**
     * Handle appointment deleted event
     */
    function handleAppointmentDeleted(data) {
        console.log('[Real-Time] Appointment deleted:', data);
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('appointmentDeleted', {
            detail: data
        }));

        // Remove appointment from calendar
        if (typeof removeAppointmentFromCalendar === 'function') {
            removeAppointmentFromCalendar(data.id);
        } else if (typeof refreshAppointments === 'function') {
            refreshAppointments({ deleted_ids: [data.id] });
        } else if (typeof loadAppointments === 'function') {
            loadAppointments();
        }
    }

    /**
     * Handle blocked time updated event
     */
    function handleBlockedTimeUpdated(data) {
        console.log('[Real-Time] Blocked time updated:', data);
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('blockedTimeUpdated', {
            detail: data
        }));

        // Handle deletion
        if (data.action === 'deleted' && data.blocked_time && data.blocked_time.id) {
            console.log('[Real-Time] Removing blocked time:', data.blocked_time.id);
            // Remove blocked time from calendar
            if (typeof window.removeBlockedTimeFromCalendar === 'function') {
                window.removeBlockedTimeFromCalendar(data.blocked_time.id);
            } else if (typeof removeBlockedTimeFromCalendar === 'function') {
                removeBlockedTimeFromCalendar(data.blocked_time.id);
            } else if (typeof window.loadAppointments === 'function') {
                window.loadAppointments();
            } else if (typeof loadAppointments === 'function') {
                loadAppointments();
            } else {
                // Fallback: manually remove from window.blockedTimes and refresh
                if (window.blockedTimes && Array.isArray(window.blockedTimes)) {
                    window.blockedTimes = window.blockedTimes.filter(bt => bt.id != data.blocked_time.id);
                    if (typeof window.applyAllFormatters === 'function') {
                        window.applyAllFormatters();
                    }
                }
            }
        } else {
            // Refresh blocked times (create or update)
            console.log('[Real-Time] Adding/updating blocked time:', data.blocked_time);
            if (typeof window.refreshBlockedTimes === 'function') {
                window.refreshBlockedTimes(data.blocked_time);
            } else if (typeof refreshBlockedTimes === 'function') {
                refreshBlockedTimes(data.blocked_time);
            } else if (typeof window.loadAppointments === 'function') {
                window.loadAppointments();
            } else if (typeof loadAppointments === 'function') {
                loadAppointments();
            } else {
                // Fallback: manually add/update window.blockedTimes and refresh
                if (!window.blockedTimes) {
                    window.blockedTimes = [];
                }
                if (!Array.isArray(window.blockedTimes)) {
                    window.blockedTimes = Object.values(window.blockedTimes || []);
                }
                const index = window.blockedTimes.findIndex(bt => bt.id == data.blocked_time.id);
                if (index !== -1) {
                    window.blockedTimes[index] = data.blocked_time;
                } else {
                    window.blockedTimes.push(data.blocked_time);
                }
                if (typeof window.applyAllFormatters === 'function') {
                    window.applyAllFormatters();
                }
            }
        }
    }

    /**
     * Handle staff access control updated event
     */
    function handleStaffAccessControlUpdated(data) {
        console.log('[Real-Time] Staff access control updated:', data);
        
        // Only handle if this is for the current staff user
        const currentUserId = window.currentUserId || null;
        if (!currentUserId || parseInt(data.staff_id) !== parseInt(currentUserId)) {
            console.log('[Real-Time] Access control update is for different staff member, ignoring');
            return;
        }

        // Trigger custom event for other scripts to handle
        // Don't call checkStaffAccessAndRedirect directly - let the event listener handle it
        // This ensures proper timestamp checking and prevents processing old events
        window.dispatchEvent(new CustomEvent('staffAccessControlUpdated', {
            detail: data
        }));
    }

    /**
     * Show notification toast
     */
    function showNotification(title, message, type) {
        // Try to use existing notification system
        if (typeof showToast === 'function') {
            showToast(message, type);
            return;
        }

        // Create simple notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <strong>${title}</strong><br>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    /**
     * Disconnect from event stream
     */
    function disconnect() {
        isConnected = false;
        eventSource = null;
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Reconnect when page becomes visible
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && !isConnected) {
            connect();
        } else if (document.hidden) {
            disconnect();
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', disconnect);

    // Export functions for global access
    window.realtimeBroadcast = {
        connect,
        disconnect,
        isConnected: () => isConnected
    };

})();

