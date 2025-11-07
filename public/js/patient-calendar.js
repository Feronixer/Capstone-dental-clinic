// Patient Calendar JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Ensure data is available and is an array (set by blade template)
    if (!window.patientAppointments) {
        window.patientAppointments = [];
    }
    // Convert to array if it's an object (Laravel collection serialization issue)
    if (!Array.isArray(window.patientAppointments)) {
        console.warn('window.patientAppointments is not an array, converting...', typeof window.patientAppointments);
        window.patientAppointments = Object.values(window.patientAppointments);
    }

    if (!window.allAppointments) {
        window.allAppointments = [];
    }
    if (!Array.isArray(window.allAppointments)) {
        window.allAppointments = Object.values(window.allAppointments);
    }

    if (!window.blockedTimes) {
        window.blockedTimes = [];
    }
    if (!Array.isArray(window.blockedTimes)) {
        window.blockedTimes = Object.values(window.blockedTimes);
    }

    // Calendar state variables (accessible to all functions)
    let currentDate = new Date();
    let currentView = 'month';
    let isInitialRender = true;

    // Wait for data to be ready before initializing
    function tryInit() {
        // Check if data is set (window.allAppointments or window.patientAppointments exists, even if empty array)
        if (typeof window.allAppointments !== 'undefined' || typeof window.patientAppointments !== 'undefined') {
            // Debug: Log appointments data
            console.log('=== Calendar Initialization ===');
            console.log('Patient appointments loaded:', window.patientAppointments ? window.patientAppointments.length : 0, 'appointments');
            console.log('All appointments loaded:', window.allAppointments ? window.allAppointments.length : 0, 'appointments');
            
            if (window.allAppointments && window.allAppointments.length > 0) {
                console.log('Sample appointment from allAppointments:', window.allAppointments[0]);
                console.log('First appointment date:', window.allAppointments[0].start_datetime);
                console.log('All appointment dates from allAppointments:', window.allAppointments.map(function(a) {
                    return a.start_datetime ? a.start_datetime.split(' ')[0] : 'no date';
                }));
            } else if (window.patientAppointments && window.patientAppointments.length > 0) {
                console.log('Sample appointment from patientAppointments:', window.patientAppointments[0]);
                console.log('First appointment date:', window.patientAppointments[0].start_datetime);
                console.log('All appointment dates from patientAppointments:', window.patientAppointments.map(function(a) {
                    return a.start_datetime ? a.start_datetime.split(' ')[0] : 'no date';
                }));
            } else {
                console.warn('No appointments found - arrays are empty');
            }
            
            // Verify allAppointments is being used
            if (window.allAppointments && window.allAppointments.length > 0) {
                console.log('✓ Using allAppointments for calendar rendering (' + window.allAppointments.length + ' appointments)');
            } else {
                console.warn('⚠ allAppointments is empty or undefined, falling back to patientAppointments');
            }

            // Initialize calendar
            renderCalendar();
            initEventListeners();
        } else {
            // Retry after a short delay if data isn't ready
            console.log('Waiting for appointment data...');
            setTimeout(tryInit, 100);
        }
    }

    // Start initialization
    tryInit();

    // Main Calendar Rendering with smooth transition
    function renderCalendar() {
        // Ensure data is available (check allAppointments first, then patientAppointments)
        if ((!window.allAppointments || !Array.isArray(window.allAppointments)) && 
            (!window.patientAppointments || !Array.isArray(window.patientAppointments))) {
            console.error('Cannot render calendar: appointments data not available');
            return;
        }

        const content = document.getElementById('calendarContent');
        const periodDisplay = document.getElementById('currentPeriodDisplay');

        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();

        // Store current height to prevent shrinking (only if not initial render)
        if (!isInitialRender) {
            const currentHeight = content.offsetHeight;
            if (currentHeight > 0) {
                content.style.minHeight = currentHeight + 'px';
            }
            // Add updating class for subtle opacity change
            content.classList.add('updating');
        }

        // Use requestAnimationFrame for smooth update
        requestAnimationFrame(() => {
            // Update period display based on view
            if (currentView === 'month') {
                periodDisplay.textContent = new Date(year, month).toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });
                renderMonthView(content, year, month);
            } else if (currentView === 'week') {
                const startOfWeek = new Date(currentDate);
                startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);

                periodDisplay.textContent = `${startOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${endOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
                renderWeekView(content);
            } else {
                periodDisplay.textContent = currentDate.toLocaleDateString('en-US', {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                renderDayView(content);
            }

            // Remove updating class and min-height after render
            requestAnimationFrame(() => {
                content.classList.remove('updating');
                content.style.minHeight = '';
            });

            // Mark as not initial render after first render
            if (isInitialRender) {
                isInitialRender = false;
            }
        });
    }

    function renderMonthView(container, year, month) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let html = `
            <div class="calendar-grid month-grid">
                <div class="calendar-header-row">
                    <div class="calendar-header-cell">Sunday</div>
                    <div class="calendar-header-cell">Monday</div>
                    <div class="calendar-header-cell">Tuesday</div>
                    <div class="calendar-header-cell">Wednesday</div>
                    <div class="calendar-header-cell">Thursday</div>
                    <div class="calendar-header-cell">Friday</div>
                    <div class="calendar-header-cell">Saturday</div>
                </div>
                <div class="calendar-body">
        `;

        let dayCount = 1;
        for (let week = 0; week < 6; week++) {
            html += '<div class="calendar-week">';
            for (let day = 0; day < 7; day++) {
                if ((week === 0 && day < firstDay) || dayCount > daysInMonth) {
                    html += '<div class="calendar-day empty"></div>';
                } else {
                    const today = new Date();
                    const isToday = dayCount === today.getDate() &&
                                   month === today.getMonth() &&
                                   year === today.getFullYear();

                    // Get appointments for this day
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                    const dayAppointments = getAppointmentsForDate(dateStr);
                    const dayBlockedTimes = getBlockedTimesForDate(dateStr);

                    // Debug logging for days with appointments or first few days
                    if (dayAppointments.length > 0 || (dayCount <= 5 && window.allAppointments && window.allAppointments.length > 0)) {
                        console.log('Date ' + dateStr + ':', {
                            foundAppointments: dayAppointments.length,
                            totalAllAppointments: window.allAppointments ? window.allAppointments.length : 0,
                            totalPatientAppointments: window.patientAppointments ? window.patientAppointments.length : 0,
                            appointments: dayAppointments.map(function(a) {
                                return {
                                    id: a.id,
                                    patient_id: a.patient_id,
                                    is_own: a.is_own_appointment,
                                    patient_name: a.patient_name,
                                    title: a.service ? a.service.service_name : a.reason_for_visit,
                                    time: a.start_datetime
                                };
                            }),
                            sampleDates: window.allAppointments && window.allAppointments.length > 0 ? window.allAppointments.slice(0, 5).map(function(a) {
                                return a.start_datetime ? a.start_datetime.split(' ')[0] : 'no date';
                            }) : []
                        });
                    }

                    let appointmentsHtml = '';
                    dayAppointments.forEach(function(apt) {
                        try {
                            // Parse datetime string manually to avoid timezone issues
                            // Format: YYYY-MM-DD HH:mm:ss
                            if (!apt.start_datetime) {
                                console.error('Appointment missing start_datetime:', apt);
                                return;
                            }

                            const [datePart, timePart] = apt.start_datetime.split(' ');
                            if (!datePart || !timePart) {
                                console.error('Invalid datetime format:', apt.start_datetime);
                                return;
                            }

                            const [aptYear, aptMonth, aptDay] = datePart.split('-').map(Number);
                            const [hours, minutes] = timePart.split(':').map(Number);

                            if (isNaN(aptYear) || isNaN(aptMonth) || isNaN(aptDay) || isNaN(hours) || isNaN(minutes)) {
                                console.error('Invalid date/time values:', {aptYear, aptMonth, aptDay, hours, minutes});
                                return;
                            }

                            const timeDate = new Date(aptYear, aptMonth - 1, aptDay, hours, minutes);

                            const time = timeDate.toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            
                            // Parse end time for other patients' appointments
                            let endTimeStr = '';
                            if (apt.end_datetime) {
                                const [endDatePart, endTimePart] = apt.end_datetime.split(' ');
                                if (endDatePart && endTimePart) {
                                    const [endYear, endMonth, endDay] = endDatePart.split('-').map(Number);
                                    const [endHours, endMinutes] = endTimePart.split(':').map(Number);
                                    if (!isNaN(endYear) && !isNaN(endMonth) && !isNaN(endDay) && !isNaN(endHours) && !isNaN(endMinutes)) {
                                        const endTimeDate = new Date(endYear, endMonth - 1, endDay, endHours, endMinutes);
                                        endTimeStr = endTimeDate.toLocaleTimeString('en-US', {
                                            hour: 'numeric',
                                            minute: '2-digit',
                                            hour12: true
                                        });
                                    }
                                }
                            }
                            
                            // Check if this is the patient's own appointment or another patient's appointment
                            const isOwnAppointment = apt.is_own_appointment === true || 
                                                   (window.patientAppointments && window.patientAppointments.some(function(pa) {
                                                       return pa && pa.id === apt.id;
                                                   }));
                            
                            // Get proper title - don't show reschedule request text
                            let title = 'Appointment';
                            if (apt.service && apt.service.service_name) {
                                title = apt.service.service_name;
                            } else if (apt.reason_for_visit && !apt.reason_for_visit.toLowerCase().includes('reschedule')) {
                                title = apt.reason_for_visit;
                            }
                            
                            // For other patients' appointments, show "Booked Time" with end time (no patient name for privacy)
                            let displayTime = time;
                            if (!isOwnAppointment && endTimeStr) {
                                displayTime = `${time} - ${endTimeStr}`;
                                title = 'Booked Time';
                            }
                            
                            let status = apt.status ? apt.status.toLowerCase() : 'pending';
                            // Map "missed" to "blocked" for styling consistency
                            if (status === 'missed') {
                                status = 'blocked';
                            }
                            
                            // For other patients' appointments, use "booked" status for styling
                            if (!isOwnAppointment) {
                                status = 'booked';
                            }
                            
                            const isCompleted = status === 'completed';
                            const isCancelled = status === 'cancelled';
                            const strikethrough = (isCompleted || isCancelled) ? 'style="text-decoration: line-through; opacity: 0.7;"' : '';
                            const notes = isCancelled && apt.notes ? apt.notes : '';
                            
                            // Add data attribute to mark if it's editable
                            const editableAttr = isOwnAppointment ? '' : 'data-read-only="true"';

                            appointmentsHtml += `
                                <div class="event-item ${status}" data-appointment-id="${apt.id}" ${editableAttr} ${strikethrough}>
                                    <div class="event-time">${displayTime}</div>
                                    <div class="event-title">${title}</div>
                                    ${notes ? `<div class="event-notes">${notes}</div>` : ''}
                                </div>
                            `;
                        } catch (error) {
                            console.error('Error rendering appointment:', apt, error);
                        }
                    });

                    // Add blocked times
                    dayBlockedTimes.forEach(function(blocked) {
                        const startTime = new Date(blocked.start_datetime);
                        const endTime = new Date(blocked.end_datetime);
                        // Check if it's a full day closure (00:00 to 23:59)
                        const isFullDayClosure = startTime.getHours() === 0 && startTime.getMinutes() === 0 &&
                                                 endTime.getHours() === 23 && endTime.getMinutes() === 59;

                        const title = blocked.title || 'Clinic Unavailable';
                        const displayTitle = (title === 'Clinic Closed' || isFullDayClosure) ? 'Clinic Closed' : title;

                        const notes = blocked.notes || '';
                        if (isFullDayClosure) {
                            // Full day closure - don't show time
                            appointmentsHtml += `
                                <div class="event-item blocked" data-blocked-time-id="${blocked.id}">
                                    <div class="event-title">${displayTitle}</div>
                                    ${notes ? `<div class="event-notes">${notes}</div>` : ''}
                                </div>
                            `;
                        } else {
                            // Partial day block - show time
                            const time = startTime.toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            appointmentsHtml += `
                                <div class="event-item blocked" data-blocked-time-id="${blocked.id}">
                                    <div class="event-time">${time}</div>
                                    <div class="event-title">${displayTitle}</div>
                                    ${notes ? `<div class="event-notes">${notes}</div>` : ''}
                                </div>
                            `;
                        }
                    });

                    html += `
                        <div class="calendar-day ${isToday ? 'today' : ''}" data-date="${dateStr}">
                            <div class="day-number">${dayCount}</div>
                            <div class="day-events">
                                ${appointmentsHtml}
                            </div>
                        </div>
                    `;
                    dayCount++;
                }
            }
            html += '</div>';
            if (dayCount > daysInMonth) break;
        }

        html += '</div></div>';
        container.innerHTML = html;

        // Add styles
        addCalendarStyles();

        // Add click handlers to appointment items (only for patient's own appointments)
        document.querySelectorAll('.event-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const appointmentId = this.dataset.appointmentId;
                const isReadOnly = this.dataset.readOnly === 'true';
                
                // Only show details for patient's own appointments (no modal for other patients' appointments)
                if (!isReadOnly) {
                    showAppointmentDetails(appointmentId);
                }
                // Do nothing for other patients' appointments (privacy)
            });
        });
    }

    // Get appointments for a specific date (includes both patient's own and other patients' appointments)
    function getAppointmentsForDate(dateStr) {
        // Use allAppointments as primary source (includes all appointments with is_own_appointment flag)
        const allApts = [];
        
        // Prioritize allAppointments (which includes all appointments with is_own_appointment flag)
        if (window.allAppointments && Array.isArray(window.allAppointments)) {
            console.log('getAppointmentsForDate: Processing ' + window.allAppointments.length + ' appointments from allAppointments');
            window.allAppointments.forEach(function(apt) {
                if (apt && apt.start_datetime) {
                    allApts.push(apt);
                } else {
                    console.warn('getAppointmentsForDate: Skipping appointment (missing data):', apt);
                }
            });
        } else {
            console.warn('getAppointmentsForDate: window.allAppointments is not available or not an array');
        }
        
        // Also add patient's own appointments that might not be in allAppointments (fallback)
        if (window.patientAppointments && Array.isArray(window.patientAppointments)) {
            window.patientAppointments.forEach(function(apt) {
                if (apt && apt.start_datetime) {
                    // Only add if it's not already in allApts (avoid duplicates)
                    const isDuplicate = allApts.some(function(pa) {
                        return pa && pa.id === apt.id;
                    });
                    if (!isDuplicate) {
                        allApts.push(apt);
                    }
                }
            });
        }

        console.log('getAppointmentsForDate: Total appointments collected: ' + allApts.length + ' (allAppointments: ' + (window.allAppointments ? window.allAppointments.length : 0) + ', patientAppointments: ' + (window.patientAppointments ? window.patientAppointments.length : 0) + ')');

        // Use a counter for debug logging instead of accessing matched.length inside the filter
        let matchCount = 0;
        const matched = allApts.filter(function(apt) {
            if (!apt || !apt.start_datetime) {
                if (apt) {
                    console.warn('Appointment missing start_datetime:', apt);
                }
                return false;
            }

            // Extract date part from datetime string (format: YYYY-MM-DD HH:mm:ss)
            const aptDate = apt.start_datetime.split(' ')[0];
            const matches = aptDate === dateStr;
            
            // Debug logging for first few appointments
            if (matches && matchCount < 3) {
                console.log('Matching appointment for date ' + dateStr + ':', {
                    id: apt.id,
                    patient_id: apt.patient_id,
                    is_own_appointment: apt.is_own_appointment,
                    patient_name: apt.patient_name,
                    service: apt.service ? apt.service.service_name : null,
                    reason_for_visit: apt.reason_for_visit,
                    status: apt.status,
                    start_datetime: apt.start_datetime
                });
                matchCount++;
            }
            
            return matches;
        });

        console.log('getAppointmentsForDate(' + dateStr + ') found ' + matched.length + ' appointments out of ' + allApts.length + ' total appointments');
        return matched;
    }

    // Get blocked times for a specific date
    function getBlockedTimesForDate(dateStr) {
        if (!window.blockedTimes) return [];

        return window.blockedTimes.filter(function(blocked) {
            const blockedDate = blocked.start_datetime.split(' ')[0];
            return blockedDate === dateStr;
        });
    }

    // Show appointment details in modal
    function showAppointmentDetails(appointmentId) {
        const appointment = window.patientAppointments.find(function(apt) {
            return apt.id == appointmentId;
        });
        if (!appointment) return;

        // Parse datetime strings manually to avoid timezone issues
        const [startDatePart, startTimePart] = appointment.start_datetime.split(' ');
        const [startYear, startMonth, startDay] = startDatePart.split('-').map(Number);
        const [startHours, startMinutes] = startTimePart.split(':').map(Number);
        const startDate = new Date(startYear, startMonth - 1, startDay, startHours, startMinutes);

        const [endDatePart, endTimePart] = appointment.end_datetime.split(' ');
        const [endYear, endMonth, endDay] = endDatePart.split('-').map(Number);
        const [endHours, endMinutes] = endTimePart.split(':').map(Number);
        const endDate = new Date(endYear, endMonth - 1, endDay, endHours, endMinutes);

        const dateFormatted = startDate.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const startTime = startDate.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        const endTime = endDate.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        // Get proper service name
        let serviceName = 'General Appointment';

        // Try to get service name from service relationship
        if (appointment.service) {
            if (typeof appointment.service === 'object' && appointment.service.service_name) {
                serviceName = appointment.service.service_name;
            }
        }

        // If no service name found and reason_for_visit exists
        if (serviceName === 'General Appointment' && appointment.reason_for_visit) {
            // Don't use reason_for_visit if it looks like a reschedule request
            const reason = appointment.reason_for_visit.toLowerCase();
            if (!reason.includes('reschedule') && !reason.includes('i would like to')) {
                serviceName = appointment.reason_for_visit;
            }
        }

        const status = appointment.status || 'Pending';
        const duration = appointment.duration_minutes || 30;
        const notes = appointment.notes || 'No additional notes';

        // Get status color class
        const statusClass = status.toLowerCase();
        let statusBadgeClass = '';
        switch(statusClass) {
            case 'confirmed':
                statusBadgeClass = 'bg-success';
                break;
            case 'pending':
                statusBadgeClass = 'bg-warning';
                break;
            case 'completed':
                statusBadgeClass = 'bg-primary';
                break;
            case 'cancelled':
                statusBadgeClass = 'bg-danger';
                break;
            case 'missed':
            case 'blocked':
                statusBadgeClass = 'bg-secondary';
                break;
            default:
                statusBadgeClass = 'bg-secondary';
        }

        // Build modal content
        const modalContent = `
            <div class="appointment-details-grid">
                <div class="detail-card">
                    <div class="detail-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">${dateFormatted}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">${startTime} - ${endTime}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Service</div>
                        <div class="detail-value">${serviceName}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">${duration} minutes</div>
                    </div>
                </div>

                <div class="detail-card full-width">
                    <div class="detail-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge ${statusBadgeClass} px-3 py-2">${status}</span>
                        </div>
                    </div>
                </div>

                ${notes !== 'No additional notes' ? `
                <div class="detail-card full-width">
                    <div class="detail-icon">
                        <i class="bi bi-sticky"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Notes</div>
                        <div class="detail-value">${notes}</div>
                    </div>
                </div>
                ` : ''}
            </div>
        `;

        // Populate modal and show it
        document.getElementById('appointmentDetailsContent').innerHTML = modalContent;

        // Store appointment ID for potential actions
        document.getElementById('appointmentDetailsModal').dataset.appointmentId = appointmentId;

        // Update modal title to show it's the patient's own appointment
        const modalTitle = document.querySelector('#appointmentDetailsModal .modal-title');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-calendar-check me-2"></i>Appointment Details';
        }

        // Show and configure reschedule button for patient's own appointments
        const rescheduleBtn = document.getElementById('modalRescheduleBtn');
        if (rescheduleBtn) {
            rescheduleBtn.style.display = 'block'; // Show button for patient's own appointments
            if (statusClass === 'completed' || statusClass === 'cancelled' || statusClass === 'missed' || statusClass === 'blocked') {
                rescheduleBtn.disabled = true;
                rescheduleBtn.style.opacity = '0.5';
                rescheduleBtn.style.cursor = 'not-allowed';
                if (statusClass === 'missed' || statusClass === 'blocked') {
                    rescheduleBtn.title = 'Cannot reschedule missed appointments. Please book a new appointment instead.';
                } else {
                    rescheduleBtn.title = `Cannot reschedule ${statusClass} appointments`;
                }
            } else {
                rescheduleBtn.disabled = false;
                rescheduleBtn.style.opacity = '1';
                rescheduleBtn.style.cursor = 'pointer';
                rescheduleBtn.title = 'Request to reschedule this appointment';
            }
        }

        // Show modal using Bootstrap
        const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
        modal.show();
    }

    // Show read-only appointment details for other patients' appointments
    function showOtherAppointmentDetails(appointmentId) {
        // Find appointment in allAppointments (not just patient's own)
        let appointment = null;
        
        // First check allAppointments
        if (window.allAppointments && Array.isArray(window.allAppointments)) {
            appointment = window.allAppointments.find(function(apt) {
                return apt.id == appointmentId;
            });
        }
        
        // If not found, check patientAppointments (shouldn't happen, but just in case)
        if (!appointment && window.patientAppointments && Array.isArray(window.patientAppointments)) {
            appointment = window.patientAppointments.find(function(apt) {
                return apt.id == appointmentId;
            });
        }
        
        if (!appointment) {
            console.warn('Appointment not found:', appointmentId);
            return;
        }

        // Parse datetime strings manually to avoid timezone issues
        const [startDatePart, startTimePart] = appointment.start_datetime.split(' ');
        const [startYear, startMonth, startDay] = startDatePart.split('-').map(Number);
        const [startHours, startMinutes] = startTimePart.split(':').map(Number);
        const startDate = new Date(startYear, startMonth - 1, startDay, startHours, startMinutes);

        const [endDatePart, endTimePart] = appointment.end_datetime.split(' ');
        const [endYear, endMonth, endDay] = endDatePart.split('-').map(Number);
        const [endHours, endMinutes] = endTimePart.split(':').map(Number);
        const endDate = new Date(endYear, endMonth - 1, endDay, endHours, endMinutes);

        const dateFormatted = startDate.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const startTime = startDate.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        const endTime = endDate.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        // Get proper service name
        let serviceName = 'General Appointment';
        if (appointment.service) {
            if (typeof appointment.service === 'object' && appointment.service.service_name) {
                serviceName = appointment.service.service_name;
            }
        }
        if (serviceName === 'General Appointment' && appointment.reason_for_visit) {
            const reason = appointment.reason_for_visit.toLowerCase();
            if (!reason.includes('reschedule') && !reason.includes('i would like to')) {
                serviceName = appointment.reason_for_visit;
            }
        }

        const status = appointment.status || 'Pending';
        const duration = appointment.duration_minutes || 30;

        // Get status color class
        const statusClass = status.toLowerCase();
        let statusBadgeClass = '';
        switch(statusClass) {
            case 'confirmed':
                statusBadgeClass = 'bg-primary';
                break;
            case 'pending':
                statusBadgeClass = 'bg-warning';
                break;
            case 'completed':
                statusBadgeClass = 'bg-success';
                break;
            case 'cancelled':
                statusBadgeClass = 'bg-danger';
                break;
            case 'missed':
            case 'blocked':
                statusBadgeClass = 'bg-secondary';
                break;
            default:
                statusBadgeClass = 'bg-secondary';
        }

        // Create read-only modal content
        const modalContent = `
            <div class="text-center mb-4">
                <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #e5e7eb, #d1d5db); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-calendar-x text-secondary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Booked Time Slot</h5>
                <p class="text-muted mb-0">This time slot is already booked by another patient.</p>
            </div>

            <div class="appointment-details-grid">
                <div class="detail-card">
                    <div class="detail-icon" style="background: linear-gradient(135deg, #e5e7eb, #d1d5db);">
                        <i class="bi bi-calendar-event" style="color: #6b7280;"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">${dateFormatted}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-icon" style="background: linear-gradient(135deg, #e5e7eb, #d1d5db);">
                        <i class="bi bi-clock" style="color: #6b7280;"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">${startTime} - ${endTime}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-icon" style="background: linear-gradient(135deg, #e5e7eb, #d1d5db);">
                        <i class="bi bi-heart-pulse" style="color: #6b7280;"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Service</div>
                        <div class="detail-value">${serviceName}</div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-icon" style="background: linear-gradient(135deg, #e5e7eb, #d1d5db);">
                        <i class="bi bi-hourglass-split" style="color: #6b7280;"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">${duration} minutes</div>
                    </div>
                </div>

                <div class="detail-card full-width">
                    <div class="detail-icon" style="background: linear-gradient(135deg, #e5e7eb, #d1d5db);">
                        <i class="bi bi-info-circle" style="color: #6b7280;"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge ${statusBadgeClass} px-3 py-2">${status}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Populate modal and show it
        document.getElementById('appointmentDetailsContent').innerHTML = modalContent;

        // Store appointment ID
        document.getElementById('appointmentDetailsModal').dataset.appointmentId = appointmentId;

        // Update modal title to indicate it's a booked time slot
        const modalTitle = document.querySelector('#appointmentDetailsModal .modal-title');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-calendar-x me-2"></i>Booked Time Slot';
        }

        // Hide reschedule button for other patients' appointments
        const rescheduleBtn = document.getElementById('modalRescheduleBtn');
        if (rescheduleBtn) {
            rescheduleBtn.style.display = 'none';
        }

        // Show modal using Bootstrap
        const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
        modal.show();
    }

    function addCalendarStyles() {
        if (document.getElementById('calendar-dynamic-styles')) return;

        const style = document.createElement('style');
        style.id = 'calendar-dynamic-styles';
        style.textContent = `
            .calendar-grid {
                width: 100%;
            }

            .calendar-header-row {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 1px;
                background: #e2e8f0;
                border-radius: 12px 12px 0 0;
                overflow: hidden;
            }

            .calendar-header-cell {
                background: #f8fafc;
                padding: 0.625rem 0.5rem;
                text-align: center;
                font-weight: 600;
                color: #64748b;
                font-size: 0.8rem;
            }

            .calendar-body {
                display: flex;
                flex-direction: column;
                gap: 1px;
                background: #e2e8f0;
            }

            .calendar-week {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 1px;
            }

            .calendar-day {
                background: white;
                min-height: 80px;
                padding: 0.5rem;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                flex-direction: column;
            }

            .calendar-day:hover {
                background: #f8fafc;
            }

            .calendar-day.empty {
                background: #fafafa;
                cursor: default;
            }

            .calendar-day.today {
                background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            }

            .calendar-day.today .day-number {
                background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
                color: white;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
            }

            .day-number {
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.375rem;
                font-size: 0.875rem;
            }

            .day-events {
                display: flex;
                flex-direction: column;
                gap: 0.2rem;
                flex: 1;
                overflow: hidden;
            }

            .event-item {
                background: #e3f2fd;
                border-left: 2px solid #2196F3;
                padding: 0.25rem 0.375rem;
                border-radius: 4px;
                font-size: 0.7rem;
                cursor: pointer;
                transition: all 0.2s;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .event-item:hover {
                transform: translateX(2px);
                box-shadow: 0 2px 6px rgba(33, 150, 243, 0.3);
            }

            .event-item.pending {
                background: #fff3e0;
                border-left-color: #ff9800;
            }

            .event-item.confirmed {
                background: #e8f5e9;
                border-left-color: #4caf50;
            }

            .event-item.completed {
                background: #f3e5f5;
                border-left-color: #9c27b0;
            }

            .event-item.cancelled {
                background: #ffebee;
                border-left-color: #f44336;
                opacity: 0.7;
            }

            .event-item.blocked {
                background: #f3f4f6;
                border-left-color: #6b7280;
                cursor: not-allowed;
            }

            .event-item.blocked:hover {
                transform: none;
                box-shadow: none;
            }

            .event-item.booked {
                background: #e0f2fe;
                border-left: 2px solid #0ea5e9;
                color: #0c4a6e;
                cursor: default;
                opacity: 1;
            }

            .event-item.booked:hover {
                transform: none;
                box-shadow: none;
                opacity: 1;
            }

            .event-item[data-read-only="true"] {
                cursor: default;
            }

            .event-time {
                font-weight: 600;
                color: #1e293b;
                font-size: 0.65rem;
                margin-bottom: 0.1rem;
            }

            .event-title {
                color: #64748b;
                font-size: 0.65rem;
                line-height: 1.2;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        `;

        document.head.appendChild(style);
    }

    function renderWeekView(container) {
        const startOfWeek = new Date(currentDate);
        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay()); // Start on Sunday

        const hours = Array.from({length: 10}, (_, i) => i + 8); // 8 AM to 5 PM (reduced from 8 PM)
        const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        let html = '<div class="week-view">';

        // Header with days
        html += '<div class="week-header">';
        html += '<div class="time-column-header">Time</div>';
        for (let i = 0; i < 7; i++) {
            const day = new Date(startOfWeek);
            day.setDate(startOfWeek.getDate() + i);
            const isToday = day.toDateString() === new Date().toDateString();

            html += `
                <div class="week-day-header ${isToday ? 'today' : ''}">
                    <div class="day-name">${weekDays[i]}</div>
                    <div class="day-date">${day.getDate()}</div>
                </div>
            `;
        }
        html += '</div>';

        // Time slots
        html += '<div class="week-body">';
        hours.forEach(hour => {
            html += '<div class="week-row">';
            html += `<div class="time-slot">${hour === 12 ? '12' : hour % 12}:00 ${hour < 12 ? 'AM' : 'PM'}</div>`;

            for (let i = 0; i < 7; i++) {
                const day = new Date(startOfWeek);
                day.setDate(startOfWeek.getDate() + i);
                const dateStr = `${day.getFullYear()}-${String(day.getMonth() + 1).padStart(2, '0')}-${String(day.getDate()).padStart(2, '0')}`;

                const dayAppointments = getAppointmentsForDate(dateStr).filter(apt => {
                    // Parse datetime string manually to avoid timezone issues
                    const [datePart, timePart] = apt.start_datetime.split(' ');
                    const [aptHours] = timePart.split(':').map(Number);
                    return aptHours === hour;
                });

                // Get all blocked times for the day (not filtered by hour yet)
                const allDayBlockedTimes = getBlockedTimesForDate(dateStr);

                let cellContent = '';
                dayAppointments.forEach(apt => {
                    // Parse datetime string manually to avoid timezone issues
                    const [datePart, timePart] = apt.start_datetime.split(' ');
                    const [aptYear, aptMonth, aptDay] = datePart.split('-').map(Number);
                    const [hours, minutes] = timePart.split(':').map(Number);
                    const timeDate = new Date(aptYear, aptMonth - 1, aptDay, hours, minutes);

                    const time = timeDate.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    
                    // Parse end time for other patients' appointments
                    let endTimeStr = '';
                    if (apt.end_datetime) {
                        const [endDatePart, endTimePart] = apt.end_datetime.split(' ');
                        if (endDatePart && endTimePart) {
                            const [endYear, endMonth, endDay] = endDatePart.split('-').map(Number);
                            const [endHours, endMinutes] = endTimePart.split(':').map(Number);
                            if (!isNaN(endYear) && !isNaN(endMonth) && !isNaN(endDay) && !isNaN(endHours) && !isNaN(endMinutes)) {
                                const endTimeDate = new Date(endYear, endMonth - 1, endDay, endHours, endMinutes);
                                endTimeStr = endTimeDate.toLocaleTimeString('en-US', {
                                    hour: 'numeric',
                                    minute: '2-digit',
                                    hour12: true
                                });
                            }
                        }
                    }
                    
                    // Check if this is the patient's own appointment or another patient's appointment
                    const isOwnAppointment = apt.is_own_appointment === true || 
                                           (window.patientAppointments && window.patientAppointments.some(function(pa) {
                                               return pa && pa.id === apt.id;
                                           }));
                    
                    // Get proper title
                    let title = apt.service && apt.service.service_name ? apt.service.service_name : (apt.reason_for_visit || 'Appointment');
                    
                    // For other patients' appointments, show "Booked Time" with end time (no patient name for privacy)
                    let displayTime = time;
                    if (!isOwnAppointment) {
                        title = 'Booked Time';
                        if (endTimeStr) {
                            displayTime = `${time} - ${endTimeStr}`;
                        }
                    }
                    
                    let status = apt.status ? apt.status.toLowerCase() : 'pending';
                    // Map "missed" to "blocked" for styling consistency
                    if (status === 'missed') {
                        status = 'blocked';
                    }
                    
                    // For other patients' appointments, use "booked" status for styling
                    if (!isOwnAppointment) {
                        status = 'booked';
                    }
                    
                    const isCompleted = status === 'completed';
                    const isCancelled = status === 'cancelled';
                    const strikethrough = (isCompleted || isCancelled) ? 'style="text-decoration: line-through; opacity: 0.7;"' : '';
                    const notes = isCancelled && apt.notes ? apt.notes : '';
                    
                    // Add data attribute to mark if it's editable
                    const editableAttr = isOwnAppointment ? '' : 'data-read-only="true"';

                    cellContent += `
                        <div class="week-appointment ${status}" data-appointment-id="${apt.id}" ${editableAttr} ${strikethrough}>
                            <div class="week-apt-time">${displayTime}</div>
                            <div class="week-apt-title">${title}</div>
                            ${notes ? `<div class="week-apt-notes">${notes}</div>` : ''}
                        </div>
                    `;
                });

                // Add blocked times
                allDayBlockedTimes.forEach(blocked => {
                    const startTime = new Date(blocked.start_datetime);
                    const endTime = new Date(blocked.end_datetime);
                    // Check if it's a full day closure (00:00 to 23:59)
                    const isFullDayClosure = startTime.getHours() === 0 && startTime.getMinutes() === 0 &&
                                             endTime.getHours() === 23 && endTime.getMinutes() === 59;

                    const title = blocked.title || 'Clinic Unavailable';
                    const displayTitle = (title === 'Clinic Closed' || isFullDayClosure) ? 'Clinic Closed' : title;

                    const notes = blocked.notes || '';
                    if (isFullDayClosure && hour === 8) {
                        // Full day closure - show only at first hour (8 AM) without time
                        cellContent += `
                            <div class="week-appointment blocked full-day-closure" data-blocked-time-id="${blocked.id}">
                                <div class="week-apt-title">${displayTitle}</div>
                                ${notes ? `<div class="week-apt-notes">${notes}</div>` : ''}
                            </div>
                        `;
                    } else if (!isFullDayClosure) {
                        // Partial day block - show time only in its hour slot
                        const blockedHour = startTime.getHours();
                        if (blockedHour === hour) {
                            const time = startTime.toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            cellContent += `
                                <div class="week-appointment blocked" data-blocked-time-id="${blocked.id}">
                                    <div class="week-apt-time">${time}</div>
                                    <div class="week-apt-title">${displayTitle}</div>
                                    ${notes ? `<div class="week-apt-notes">${notes}</div>` : ''}
                                </div>
                            `;
                        }
                    }
                });

                html += `<div class="week-cell">${cellContent}</div>`;
            }
            html += '</div>';
        });
        html += '</div></div>';

        container.innerHTML = html;

        // Add click handlers (only for patient's own appointments)
        document.querySelectorAll('.week-appointment').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.appointmentId;
                if (!appointmentId) return; // Skip blocked times
                
                const isReadOnly = this.dataset.readOnly === 'true';
                
                // Only show details for patient's own appointments (no modal for other patients' appointments)
                if (!isReadOnly) {
                    showAppointmentDetails(appointmentId);
                }
                // Do nothing for other patients' appointments (privacy)
            });
        });
    }

    function renderDayView(container) {
        const hours = Array.from({length: 13}, (_, i) => i + 8); // 8 AM to 8 PM
        const dateStr = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(currentDate.getDate()).padStart(2, '0')}`;
        const dayAppointments = getAppointmentsForDate(dateStr);
        const dayBlockedTimes = getBlockedTimesForDate(dateStr);

        const dayName = currentDate.toLocaleDateString('en-US', { weekday: 'long' });
        const dateFormatted = currentDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        let html = '<div class="day-view">';

        // Day header
        html += `
            <div class="day-view-header">
                <h3 class="day-view-title">${dayName}</h3>
                <p class="day-view-date">${dateFormatted}</p>
            </div>
        `;

        // Time slots
        html += '<div class="day-view-body">';
        hours.forEach(hour => {
            const hourAppointments = dayAppointments.filter(apt => {
                // Parse datetime string manually to avoid timezone issues
                const [datePart, timePart] = apt.start_datetime.split(' ');
                const [aptHours] = timePart.split(':').map(Number);
                return aptHours === hour;
            });

            // Get blocked times for this hour (including full-day closures that should show at 8 AM)
            const hourBlockedTimes = dayBlockedTimes.filter(blocked => {
                const startTime = new Date(blocked.start_datetime);
                const endTime = new Date(blocked.end_datetime);
                // Check if it's a full day closure
                const isFullDayClosure = startTime.getHours() === 0 && startTime.getMinutes() === 0 &&
                                         endTime.getHours() === 23 && endTime.getMinutes() === 59;

                if (isFullDayClosure) {
                    // Full day closures show at hour 8 (first visible hour)
                    return hour === 8;
                } else {
                    // Partial blocks show in their actual hour
                    const blockedHour = startTime.getHours();
                    return blockedHour === hour;
                }
            });

            html += `
                <div class="day-time-row">
                    <div class="day-time-label">
                        ${hour === 12 ? '12' : hour % 12}:00 ${hour < 12 ? 'AM' : 'PM'}
                    </div>
                    <div class="day-time-content">
            `;

            let hasItems = false;

            if (hourAppointments.length > 0) {
                hasItems = true;
                hourAppointments.forEach(apt => {
                    // Parse datetime strings manually to avoid timezone issues
                    const [startDatePart, startTimePart] = apt.start_datetime.split(' ');
                    const [startYear, startMonth, startDay] = startDatePart.split('-').map(Number);
                    const [startHours, startMinutes] = startTimePart.split(':').map(Number);
                    const startDateObj = new Date(startYear, startMonth - 1, startDay, startHours, startMinutes);

                    const [endDatePart, endTimePart] = apt.end_datetime.split(' ');
                    const [endYear, endMonth, endDay] = endDatePart.split('-').map(Number);
                    const [endHours, endMinutes] = endTimePart.split(':').map(Number);
                    const endDateObj = new Date(endYear, endMonth - 1, endDay, endHours, endMinutes);

                    const startTime = startDateObj.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    const endTime = endDateObj.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    
                    // Check if this is the patient's own appointment or another patient's appointment
                    const isOwnAppointment = apt.is_own_appointment === true || 
                                           (window.patientAppointments && window.patientAppointments.some(function(pa) {
                                               return pa && pa.id === apt.id;
                                           }));
                    
                    // Get proper title
                    let title = apt.service && apt.service.service_name ? apt.service.service_name : (apt.reason_for_visit || 'Appointment');
                    
                    // For other patients' appointments, show "Booked Time" (no patient name for privacy)
                    if (!isOwnAppointment) {
                        title = 'Booked Time';
                    }
                    
                    let status = apt.status ? apt.status.toLowerCase() : 'pending';
                    // Map "missed" to "blocked" for styling consistency
                    if (status === 'missed') {
                        status = 'blocked';
                    }
                    
                    // For other patients' appointments, use "booked" status for styling
                    if (!isOwnAppointment) {
                        status = 'booked';
                    }
                    
                    const isCompleted = status === 'completed';
                    const isCancelled = status === 'cancelled';
                    const strikethrough = (isCompleted || isCancelled) ? 'style="text-decoration: line-through; opacity: 0.7;"' : '';
                    const notes = isCancelled && apt.notes ? apt.notes : '';
                    
                    // Add data attribute to mark if it's editable
                    const editableAttr = isOwnAppointment ? '' : 'data-read-only="true"';

                    html += `
                        <div class="day-appointment ${status}" data-appointment-id="${apt.id}" ${editableAttr} ${strikethrough}>
                            <div class="day-apt-header">
                                <div class="day-apt-time">
                                    <i class="bi bi-clock me-1"></i>${startTime} - ${endTime}
                                </div>
                                <span class="day-apt-badge ${status}">${isOwnAppointment ? apt.status : 'Booked'}</span>
                            </div>
                            <div class="day-apt-title">${title}</div>
                            ${notes ? `<div class="day-apt-notes"><i class="bi bi-sticky me-1"></i>${notes}</div>` : ''}
                        </div>
                    `;
                });
            }

            // Add blocked times
            if (hourBlockedTimes.length > 0) {
                hasItems = true;
                hourBlockedTimes.forEach(blocked => {
                    const startTimeObj = new Date(blocked.start_datetime);
                    const endTimeObj = new Date(blocked.end_datetime);
                    // Check if it's a full day closure (00:00 to 23:59)
                    const isFullDayClosure = startTimeObj.getHours() === 0 && startTimeObj.getMinutes() === 0 &&
                                             endTimeObj.getHours() === 23 && endTimeObj.getMinutes() === 59;

                    const title = blocked.title || 'Clinic Unavailable';
                    const displayTitle = (title === 'Clinic Closed' || isFullDayClosure) ? 'Clinic Closed' : title;

                    if (isFullDayClosure && hour === 8) {
                        // Full day closure - show only at first hour (8 AM) without time
                        html += `
                            <div class="day-appointment blocked full-day-closure" data-blocked-time-id="${blocked.id}">
                                <div class="day-apt-header">
                                    <span class="day-apt-badge blocked">Clinic Closed</span>
                                </div>
                                <div class="day-apt-title">${displayTitle}</div>
                                ${blocked.notes ? `<div class="day-apt-notes"><i class="bi bi-sticky me-1"></i>${blocked.notes}</div>` : ''}
                            </div>
                        `;
                    } else if (!isFullDayClosure) {
                        // Partial day block - show time only in its hour slot
                        const blockedHour = startTimeObj.getHours();
                        if (blockedHour === hour) {
                            const startTime = startTimeObj.toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            const endTime = endTimeObj.toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                            html += `
                                <div class="day-appointment blocked" data-blocked-time-id="${blocked.id}">
                                    <div class="day-apt-header">
                                        <div class="day-apt-time">
                                            <i class="bi bi-clock me-1"></i>${startTime} - ${endTime}
                                        </div>
                                        <span class="day-apt-badge blocked">Unavailable</span>
                                    </div>
                                    <div class="day-apt-title">${displayTitle}</div>
                                    ${blocked.notes ? `<div class="day-apt-notes"><i class="bi bi-sticky me-1"></i>${blocked.notes}</div>` : ''}
                                </div>
                            `;
                        }
                    }
                });
            }

            if (!hasItems) {
                html += '<div class="day-empty-slot">No appointments</div>';
            }

            html += '</div></div>';
        });
        html += '</div></div>';

        container.innerHTML = html;

        // Add click handlers (only for patient's own appointments)
        document.querySelectorAll('.day-appointment').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.appointmentId;
                if (!appointmentId) return; // Skip blocked times
                
                const isReadOnly = this.dataset.readOnly === 'true';
                
                // Only show details for patient's own appointments (no modal for other patients' appointments)
                if (!isReadOnly) {
                    showAppointmentDetails(appointmentId);
                }
                // Do nothing for other patients' appointments (privacy)
            });
        });
    }

    // Event Listeners
    function initEventListeners() {
        // View toggles
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Only process buttons that have a data-view attribute (not appointment action buttons)
                if (!this.dataset.view) {
                    return; // Skip buttons without data-view (like Emergency/Reschedule buttons)
                }
                document.querySelectorAll('.view-btn').forEach(b => {
                    // Only toggle active class for actual view buttons
                    if (b.dataset.view) {
                        b.classList.remove('active');
                    }
                });
                this.classList.add('active');
                currentView = this.dataset.view;
                renderCalendar();
            });
        });

        // Navigation
        document.getElementById('prevPeriod').addEventListener('click', () => {
            if (currentView === 'month') {
                currentDate.setMonth(currentDate.getMonth() - 1);
            } else if (currentView === 'week') {
                currentDate.setDate(currentDate.getDate() - 7);
            } else {
                currentDate.setDate(currentDate.getDate() - 1);
            }
            renderCalendar();
        });

        document.getElementById('nextPeriod').addEventListener('click', () => {
            if (currentView === 'month') {
                currentDate.setMonth(currentDate.getMonth() + 1);
            } else if (currentView === 'week') {
                currentDate.setDate(currentDate.getDate() + 7);
            } else {
                currentDate.setDate(currentDate.getDate() + 1);
            }
            renderCalendar();
        });

        document.getElementById('todayBtn').addEventListener('click', () => {
            currentDate = new Date();
            renderCalendar();
        });


        // Star rating
        const stars = document.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                document.getElementById('selectedRating').value = rating;

                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });

                document.getElementById('submitRatingBtn').disabled = false;
            });
        });
    }
});

