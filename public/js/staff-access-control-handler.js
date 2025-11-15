/**
 * Staff Access Control Real-Time Handler
 * Handles real-time access control updates and redirects staff when access is revoked
 */

(function() {
    'use strict';

    // Map routes to access control fields
    const routeAccessMap = {
        'staff-dashboard': 'access_dashboard',
        'staff-appointment': 'access_appointments',
        'staff-account-management': 'access_user_management',
        'staff-content-management': 'access_content_management',
        'staff-post-procedural': 'access_post_procedural',
        'staff-toothtalk': 'access_toothtalk',
        'staff-chat': 'access_live_chat',
        'staff-notification': 'access_notifications',
        'staff-profile': 'access_profile',
    };

    // Map route names to URLs for redirect
    const routeUrlMap = {
        'staff-dashboard': '/staff/dashboard',
        'staff-appointment': '/staff/appointment',
        'staff-account-management': '/staff/account-management',
        'staff-content-management': '/staff/content-management',
        'staff-post-procedural': '/staff/post-procedural',
        'staff-toothtalk': '/staff/toothtalk',
        'staff-chat': '/staff/chat',
        'staff-notification': '/staff/notifications',
        'staff-profile': '/staff/profile',
    };

    /**
     * Get current route name from URL
     */
    function getCurrentRoute() {
        const path = window.location.pathname;
        
        // Check each route
        for (const [routeName, routePath] of Object.entries(routeUrlMap)) {
            if (path.startsWith(routePath)) {
                return routeName;
            }
        }
        
        // Fallback: try to match by path segments
        if (path.includes('/staff/dashboard')) return 'staff-dashboard';
        if (path.includes('/staff/appointment')) return 'staff-appointment';
        if (path.includes('/staff/account-management')) return 'staff-account-management';
        if (path.includes('/staff/content-management')) return 'staff-content-management';
        if (path.includes('/staff/post-procedural')) return 'staff-post-procedural';
        if (path.includes('/staff/toothtalk')) return 'staff-toothtalk';
        if (path.includes('/staff/chat')) return 'staff-chat';
        if (path.includes('/staff/notifications')) return 'staff-notification';
        if (path.includes('/staff/profile')) return 'staff-profile';
        
        return null;
    }

    /**
     * Cancel all pending fetch requests
     */
    function cancelAllPendingRequests() {
        // Abort all active fetch requests by tracking them
        if (window.activeFetchControllers) {
            window.activeFetchControllers.forEach(controller => {
                try {
                    controller.abort();
                } catch (e) {
                    // Ignore errors
                }
            });
            window.activeFetchControllers = [];
        }
    }

    /**
     * Disconnect from live chat and cancel all operations
     */
    function disconnectFromChat() {
        console.log('[Access Control] Disconnecting from chat immediately...');
        
        // Cancel all pending requests first
        cancelAllPendingRequests();
        
        // Dispatch custom event for chat page to handle
        window.dispatchEvent(new CustomEvent('staffAccessRevoked', {
            detail: { feature: 'chat', immediate: true }
        }));
        
        // Stop polling immediately
        try {
            if (window.pollingInterval) {
                clearInterval(window.pollingInterval);
                window.pollingInterval = null;
            }
        } catch (e) {
            // Ignore errors
        }
        
        // Clear any pending message sends
        try {
            if (window.pendingMessageSend) {
                if (window.pendingMessageSend.abort) {
                    window.pendingMessageSend.abort();
                }
                window.pendingMessageSend = null;
            }
        } catch (e) {
            // Ignore errors
        }
        
        // Clear current conversation
        try {
            if (typeof window.currentConversationId !== 'undefined') {
                window.currentConversationId = null;
            }
        } catch (e) {
            // Ignore errors
        }
    }

    /**
     * Disconnect from other active features
     */
    function disconnectFromActiveFeatures(revokedAccess) {
        // Disconnect from chat if access was revoked
        if (revokedAccess.includes('access_live_chat')) {
            disconnectFromChat();
        }
        
        // Add other feature disconnections here as needed
        // For example, if there are WebSocket connections, polling, etc.
    }

    /**
     * List of feature access controls (these trigger page reload, not redirect)
     */
    const featureAccessControls = [
        'can_create_appointments',
        'can_edit_appointments',
        'can_delete_appointments',
        'can_update_appointment_status',
        'can_view_all_appointments',
        'can_create_users',
        'can_edit_users',
        'can_delete_users',
        'can_create_announcements',
        'can_edit_announcements',
        'can_delete_announcements',
        'can_manage_announcements',
        'can_delete_archives',
        'can_manage_services',
        'can_manage_events',
        'can_send_emails',
        'can_manage_mails',
        'can_view_patient_records',
        'can_create_patient_records',
        'can_edit_patient_records',
        'can_delete_patient_records',
        'can_respond_to_chat',
        'can_attach_files',
        'can_export_data',
    ];

    /**
     * Check if any feature access was revoked
     */
    function checkFeatureAccessRevoked(accessControl) {
        const revokedFeatures = [];
        featureAccessControls.forEach(featureKey => {
            const featureValue = accessControl[featureKey];
            const isRevoked = featureValue === false || featureValue === 0 || featureValue === '0' || featureValue === 'false';
            if (isRevoked) {
                revokedFeatures.push(featureKey);
            }
        });
        return revokedFeatures;
    }

    /**
     * Check access and redirect/reload if needed
     */
    function checkStaffAccessAndRedirect(accessControl) {
        if (!accessControl || typeof accessControl !== 'object') {
            console.warn('[Access Control] No access control data provided or invalid format');
            return;
        }

        // First, check for feature access revocations (these trigger page reload)
        const revokedFeatures = checkFeatureAccessRevoked(accessControl);
        if (revokedFeatures.length > 0) {
            console.log('[Access Control] Feature access revoked:', revokedFeatures);
            
            // Cancel all pending operations immediately
            cancelAllPendingRequests();
            
            // Clear any unsaved input in forms/inputs before reload
            try {
                // Clear chat input if on chat page
                const chatInput = document.getElementById('chat-input');
                if (chatInput) {
                    chatInput.value = '';
                }
                
                // Clear any textareas that might have unsaved content
                const textareas = document.querySelectorAll('textarea');
                textareas.forEach(textarea => {
                    if (!textarea.disabled && !textarea.readOnly) {
                        textarea.value = '';
                    }
                });
            } catch (e) {
                // Ignore errors
            }
            
            // Reload the current page to reflect feature access changes
            console.log('[Access Control] Reloading page due to feature access revocation...');
            window.location.reload();
            return; // Don't check navigation access if feature access was revoked
        }

        // Then check navigation access (these trigger redirect to dashboard)
        const currentRoute = getCurrentRoute();
        if (!currentRoute) {
            console.log('[Access Control] Could not determine current route');
            return;
        }

        const requiredAccess = routeAccessMap[currentRoute];
        if (!requiredAccess) {
            console.log('[Access Control] No access control mapping for route:', currentRoute);
            return;
        }

        // Check if navigation access was revoked
        // Access is granted if: true, 1, null (defaults to true), or undefined
        // Access is revoked if: false, 0, '0', or 'false'
        const accessValue = accessControl[requiredAccess];
        
        // Debug logging
        console.log('[Access Control] Checking navigation access:', {
            route: currentRoute,
            requiredAccess: requiredAccess,
            accessValue: accessValue,
            accessValueType: typeof accessValue
        });
        
        // Only redirect if access is EXPLICITLY revoked (false or 0)
        // If null/undefined, default to granted access
        const isExplicitlyRevoked = accessValue === false || accessValue === 0 || accessValue === '0' || accessValue === 'false';
        const hasAccess = !isExplicitlyRevoked; // If not explicitly revoked, access is granted
        
        console.log('[Access Control] Navigation access check result:', {
            isExplicitlyRevoked: isExplicitlyRevoked,
            hasAccess: hasAccess
        });
        
        if (!hasAccess) {
            console.log('[Access Control] Navigation access revoked for:', requiredAccess, 'Current route:', currentRoute);
            
            // Collect all revoked navigation access for disconnection
            const revokedAccess = [];
            Object.values(routeAccessMap).forEach(accessKey => {
                if (accessControl[accessKey] === false || accessControl[accessKey] === 0) {
                    revokedAccess.push(accessKey);
                }
            });
            
            // Cancel all pending operations immediately
            cancelAllPendingRequests();
            
            // Clear any unsaved input in forms/inputs before redirect
            try {
                // Clear chat input if on chat page
                const chatInput = document.getElementById('chat-input');
                if (chatInput) {
                    chatInput.value = '';
                }
                
                // Clear any textareas that might have unsaved content
                const textareas = document.querySelectorAll('textarea');
                textareas.forEach(textarea => {
                    if (!textarea.disabled && !textarea.readOnly) {
                        textarea.value = '';
                    }
                });
            } catch (e) {
                // Ignore errors
            }
            
            // Disconnect from active features
            disconnectFromActiveFeatures(revokedAccess);
            
            // Immediately redirect to dashboard - no delay, no saving
            // Use replace() instead of href to prevent back button navigation
            console.log('[Access Control] Immediately redirecting to dashboard...');
            window.location.replace(routeUrlMap['staff-dashboard'] || '/staff/dashboard');
        } else {
            console.log('[Access Control] Navigation access still granted for:', requiredAccess);
        }
    }

    /**
     * Initialize access control monitoring
     */
    function init() {
        // Only run on staff pages
        if (!window.location.pathname.startsWith('/staff/')) {
            return;
        }

        // Track when page was loaded to ignore old events from cache
        const pageLoadTime = Date.now();
        const PAGE_LOAD_BUFFER_MS = 2000; // 2 second buffer to allow for page load time

        // Listen for access control updates
        window.addEventListener('staffAccessControlUpdated', function(event) {
            console.log('[Access Control] Received access control update event', event.detail);
            const data = event.detail;
            
            // Only process if we have valid access control data
            if (data && data.access_control && typeof data.access_control === 'object') {
                // Verify this is a real update (has staff_id and timestamp)
                if (data.staff_id && data.timestamp) {
                    // Parse timestamp (format: 'Y-m-d H:i:s')
                    try {
                        // Convert 'Y-m-d H:i:s' to ISO format for Date parsing
                        const timestampStr = data.timestamp.replace(' ', 'T') + '+08:00'; // Assuming Asia/Manila timezone
                        const eventTime = new Date(timestampStr).getTime();
                        const currentTime = Date.now();
                        
                        // Only process events that happened AFTER page load (with small buffer)
                        // This prevents processing old cached events when page first loads
                        if (eventTime < (pageLoadTime - PAGE_LOAD_BUFFER_MS)) {
                            console.log('[Access Control] Ignoring event from before page load. Event time:', new Date(eventTime), 'Page load:', new Date(pageLoadTime));
                            return;
                        }
                        
                        console.log('[Access Control] Processing recent access control update for staff:', data.staff_id);
                        checkStaffAccessAndRedirect(data.access_control);
                    } catch (e) {
                        console.warn('[Access Control] Could not parse event timestamp:', e, 'Processing anyway');
                        // If we can't parse timestamp, only process if page has been loaded for a while
                        const timeSincePageLoad = Date.now() - pageLoadTime;
                        if (timeSincePageLoad > 3000) { // Only process if page loaded more than 3 seconds ago
                            checkStaffAccessAndRedirect(data.access_control);
                        }
                    }
                } else {
                    console.warn('[Access Control] Invalid event data - missing staff_id or timestamp');
                }
            } else {
                console.warn('[Access Control] Invalid event data - missing or invalid access_control');
            }
        });

        // Make function globally available for realtime-broadcast.js
        window.checkStaffAccessAndRedirect = checkStaffAccessAndRedirect;

        console.log('[Access Control] Staff access control handler initialized');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

