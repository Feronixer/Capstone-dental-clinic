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
        console.log('renderMonthView called for', year, month);
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

                    // Check if day is fully booked (11:00 AM - 6:00 PM)
                    const isFullyBooked = checkIfDayIsFullyBooked(dateStr, dayAppointments, dayBlockedTimes);
                    
                    // Check if there's a full day closure (clinic closed)
                    const hasFullDayClosure = dayBlockedTimes && dayBlockedTimes.length > 0 && dayBlockedTimes.some(function(blocked) {
                        if (!blocked || !blocked.start_datetime || !blocked.end_datetime) return false;
                        const startTime = parseLocalDateTime(blocked.start_datetime);
                        const endTime = parseLocalDateTime(blocked.end_datetime);
                        if (!startTime || !endTime) return false;
                        return startTime.getHours() === 0 && startTime.getMinutes() === 0 &&
                               endTime.getHours() === 23 && endTime.getMinutes() === 59;
                    });
                    
                    // Prioritize patient's own appointments
                    const currentPatientId = window.currentPatientId;
                    const patientOwnAppointments = [];
                    const otherAppointments = [];
                    
                    // Separate appointments into patient's own and others
                    dayAppointments.forEach(function(apt) {
                        const isOwnAppointment = apt.is_own_appointment === true || 
                                               (currentPatientId && apt.patient_id === currentPatientId) ||
                                               (window.patientAppointments && window.patientAppointments.some(function(pa) {
                                                   return pa && pa.id === apt.id;
                                               }));
                        
                        if (isOwnAppointment) {
                            patientOwnAppointments.push(apt);
                        } else {
                            otherAppointments.push(apt);
                        }
                    });
                    
                    // Sort both arrays chronologically by start_datetime
                    function sortByDateTime(a, b) {
                        const timeA = parseLocalDateTime(a.start_datetime);
                        const timeB = parseLocalDateTime(b.start_datetime);
                        if (!timeA || !timeB) return 0;
                        return timeA - timeB;
                    }
                    
                    patientOwnAppointments.sort(sortByDateTime);
                    otherAppointments.sort(sortByDateTime);
                    
                    // Combine arrays with patient's own appointments first
                    const prioritizedAppointments = patientOwnAppointments.concat(otherAppointments);
                    
                    let appointmentsHtml = '';
                    const maxVisible = 2; // Show only first 2 appointments
                    const visibleAppointments = prioritizedAppointments.slice(0, maxVisible);
                    const hiddenCount = Math.max(0, prioritizedAppointments.length - maxVisible);
                    
                    // Debug: Log if there are 3+ appointments
                    if (prioritizedAppointments.length >= 3) {
                        console.log('Day ' + dateStr + ' has ' + prioritizedAppointments.length + ' appointments (' + patientOwnAppointments.length + ' own, ' + otherAppointments.length + ' others). Showing ' + visibleAppointments.length + ', hiding ' + hiddenCount);
                    }
                    
                    visibleAppointments.forEach(function(apt) {
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
                    
                    // Add "X more" indicator if there are 3 or more appointments (hiddenCount > 0 means >= 3)
                    if (hiddenCount > 0) {
                        console.log('Adding "X more" indicator for', dateStr, 'with', hiddenCount, 'hidden appointments');
                        appointmentsHtml += `
                            <div class="event-more-indicator" data-date="${dateStr}">
                                <span class="more-text">${hiddenCount} more</span>
                            </div>
                        `;
                    }

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

                    // Add fully booked indicator only if fully booked AND not closed (no full day closure)
                    if (isFullyBooked && !hasFullDayClosure) {
                        console.log('Day', dateStr, 'is fully booked');
                    }
                    const fullyBookedHtml = (isFullyBooked && !hasFullDayClosure) ? '<div class="fully-booked-indicator"><i class="bi bi-x-circle"></i> Fully Booked</div>' : '';
                    
                    // Store prioritized appointments in data attribute for modal
                    html += `
                        <div class="calendar-day ${isToday ? 'today' : ''} ${(isFullyBooked && !hasFullDayClosure) ? 'fully-booked' : ''}" data-date="${dateStr}" data-day-appointments='${JSON.stringify(prioritizedAppointments)}' data-day-blocked='${JSON.stringify(dayBlockedTimes)}'>
                            <div class="day-number">${dayCount}</div>
                            ${fullyBookedHtml}
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
                const blockedTimeId = this.dataset.blockedTimeId;
                
                // Check if it's a blocked time
                if (blockedTimeId) {
                    showBlockedTimeDetails(blockedTimeId);
                    return;
                }
                
                // Handle appointments
                if (appointmentId) {
                    const isReadOnly = this.dataset.readOnly === 'true';
                    
                    // Show details for patient's own appointments
                    if (!isReadOnly) {
                        showAppointmentDetails(appointmentId);
                    } else {
                        // Show read-only modal for booked time (other patients' appointments)
                        showOtherAppointmentDetails(appointmentId);
                    }
                }
            });
        });
        
        // Add click handlers to calendar days to show all appointments
        document.querySelectorAll('.calendar-day[data-date]').forEach(function(dayElement) {
            const dateStr = dayElement.dataset.date;
            if (!dateStr) return;
            
            try {
                const appointments = JSON.parse(dayElement.dataset.dayAppointments || '[]');
                const blockedTimes = JSON.parse(dayElement.dataset.dayBlocked || '[]');
                
                // Only make clickable if there are appointments or blocked times
                if (appointments.length > 0 || blockedTimes.length > 0) {
                    dayElement.style.cursor = 'pointer';
                    dayElement.addEventListener('click', function(e) {
                        // Don't trigger if clicking on an appointment item or more indicator
                        if (e.target.closest('.event-item') || e.target.closest('.event-more-indicator') || e.target.closest('.fully-booked-indicator')) {
                            return;
                        }
                        
                        showDayAppointmentsModal(dateStr, appointments, blockedTimes);
                    });
                }
            } catch (error) {
                console.error('Error parsing day appointments:', error);
            }
        });
        
        // Add click handler for "X more" indicator
        document.querySelectorAll('.event-more-indicator').forEach(function(indicator) {
            indicator.addEventListener('click', function(e) {
                e.stopPropagation();
                const dateStr = this.dataset.date;
                if (!dateStr) return;
                
                const dayElement = this.closest('.calendar-day');
                if (dayElement) {
                    try {
                        const appointments = JSON.parse(dayElement.dataset.dayAppointments || '[]');
                        const blockedTimes = JSON.parse(dayElement.dataset.dayBlocked || '[]');
                        
                        if (appointments.length > 0 || blockedTimes.length > 0) {
                            showDayAppointmentsModal(dateStr, appointments, blockedTimes);
                        }
                    } catch (error) {
                        console.error('Error parsing day appointments:', error);
                    }
                }
            });
        });
    }
    
    // Function to parse datetime string as LOCAL time
    function parseLocalDateTime(datetimeStr) {
        if (!datetimeStr || typeof datetimeStr !== 'string') return null;
        const parts = datetimeStr.split(' ');
        if (parts.length !== 2) return null;
        const [datePart, timePart] = parts;
        const [year, month, day] = datePart.split('-').map(Number);
        const [hours, minutes, seconds] = timePart.split(':').map(Number);
        if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hours) || isNaN(minutes)) return null;
        return new Date(year, month - 1, day, hours, minutes, seconds || 0);
    }
    
    // Function to check if a day is fully booked (11:00 AM - 6:00 PM)
    function checkIfDayIsFullyBooked(dateStr, appointments, blockedTimes) {
        // Check if there's a full day closure
        const hasFullDayClosure = blockedTimes && blockedTimes.length > 0 && blockedTimes.some(function(blocked) {
            if (!blocked || !blocked.start_datetime || !blocked.end_datetime) return false;
            const startTime = parseLocalDateTime(blocked.start_datetime);
            const endTime = parseLocalDateTime(blocked.end_datetime);
            if (!startTime || !endTime) return false;
            return startTime.getHours() === 0 && startTime.getMinutes() === 0 &&
                   endTime.getHours() === 23 && endTime.getMinutes() === 59;
        });
        
        if (hasFullDayClosure) {
            return true;
        }
        
        // Generate all 15-minute time slots from 11:00 AM to 6:00 PM
        const timeSlots = [];
        for (let hour = 11; hour <= 18; hour++) {
            for (let minute = 0; minute < 60; minute += 15) {
                if (hour === 18 && minute > 0) break; // Stop at 6:00 PM
                timeSlots.push({ hour: hour, minute: minute });
            }
        }
        
        // Check each time slot for availability
        // We check for slots that can fit at least a minimum service duration (15 minutes minimum)
        // This ensures that if no service can fit in any slot, the day is marked as fully booked
        const [year, month, day] = dateStr.split('-').map(Number);
        const minServiceDuration = 15; // Minimum service duration in minutes (most services need at least 15 minutes)
        const defaultDuration = 30; // Default appointment duration in minutes
        
        // Check slots with minimum duration first (15 minutes), then default duration (30 minutes)
        const durationsToCheck = [minServiceDuration, defaultDuration];
        
        for (let d = 0; d < durationsToCheck.length; d++) {
            const duration = durationsToCheck[d];
            
            for (let i = 0; i < timeSlots.length; i++) {
                const slot = timeSlots[i];
                const slotStart = new Date(year, month - 1, day, slot.hour, slot.minute);
                const slotEnd = new Date(slotStart.getTime() + duration * 60000);
                
                // Make sure slot doesn't go past clinic closing time (6:00 PM)
                if (slotEnd.getHours() > 18 || (slotEnd.getHours() === 18 && slotEnd.getMinutes() > 0)) {
                    continue; // Skip slots that extend past closing time
                }
                
                // Check if this slot is available
                let isAvailable = true;
                
                // Check against appointments
                if (appointments && appointments.length > 0) {
                    for (let j = 0; j < appointments.length; j++) {
                        const apt = appointments[j];
                        if (!apt || !apt.start_datetime || !apt.end_datetime) continue;
                        
                        const aptStart = parseLocalDateTime(apt.start_datetime);
                        const aptEnd = parseLocalDateTime(apt.end_datetime);
                        if (!aptStart || !aptEnd) continue;
                        
                        // Check for overlap (excluding cancelled appointments)
                        const status = (apt.status || '').toLowerCase();
                        if (status !== 'cancelled' && slotStart < aptEnd && slotEnd > aptStart) {
                            isAvailable = false;
                            break;
                        }
                    }
                }
                
                // Check against blocked times
                if (isAvailable && blockedTimes && blockedTimes.length > 0) {
                    for (let j = 0; j < blockedTimes.length; j++) {
                        const blocked = blockedTimes[j];
                        if (!blocked || !blocked.start_datetime || !blocked.end_datetime) continue;
                        
                        const blockStart = parseLocalDateTime(blocked.start_datetime);
                        const blockEnd = parseLocalDateTime(blocked.end_datetime);
                        if (!blockStart || !blockEnd) continue;
                        
                        // Check for overlap
                        if (slotStart < blockEnd && slotEnd > blockStart) {
                            isAvailable = false;
                            break;
                        }
                    }
                }
                
                // If any slot is available, day is not fully booked
                if (isAvailable) {
                    return false;
                }
            }
        }
        
        // All slots are booked
        return true;
    }
    
    // Function to show day appointments modal
    function showDayAppointmentsModal(dateStr, appointments, blockedTimes) {
        const [year, month, day] = dateStr.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        const formattedDate = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Check if day is fully booked
        const isFullyBooked = checkIfDayIsFullyBooked(dateStr, appointments, blockedTimes);
        
        // Check if there's a full day closure (clinic closed)
        const hasFullDayClosure = blockedTimes && blockedTimes.length > 0 && blockedTimes.some(function(blocked) {
            if (!blocked || !blocked.start_datetime || !blocked.end_datetime) return false;
            const startTime = parseLocalDateTime(blocked.start_datetime);
            const endTime = parseLocalDateTime(blocked.end_datetime);
            if (!startTime || !endTime) return false;
            return startTime.getHours() === 0 && startTime.getMinutes() === 0 &&
                   endTime.getHours() === 23 && endTime.getMinutes() === 59;
        });
        
        // Sort appointments by time
        appointments.sort(function(a, b) {
            const timeA = parseLocalDateTime(a.start_datetime);
            const timeB = parseLocalDateTime(b.start_datetime);
            if (!timeA || !timeB) return 0;
            return timeA - timeB;
        });
        
        // Build fully booked indicator HTML only if fully booked AND not closed (no full day closure)
        const fullyBookedIndicatorHtml = (isFullyBooked && !hasFullDayClosure) ? `
            <div class="modal-fully-booked-indicator">
                <i class="bi bi-x-circle me-2"></i>
                <span>Fully Booked</span>
            </div>
        ` : '';
        
        let modalContent = `
            <div class="day-appointments-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-event me-2"></i>${formattedDate}
                </h5>
                <div class="d-flex align-items-center justify-content-between">
                    <p class="text-muted mb-0">${appointments.length} appointment${appointments.length !== 1 ? 's' : ''}</p>
                    ${fullyBookedIndicatorHtml}
                </div>
            </div>
            <div class="day-appointments-list">
        `;
        
        if (appointments.length === 0 && blockedTimes.length === 0) {
            modalContent += '<div class="text-center text-muted py-4">No appointments scheduled for this day.</div>';
        } else {
            // Show appointments
            appointments.forEach(function(apt) {
                const aptStart = parseLocalDateTime(apt.start_datetime);
                const aptEnd = parseLocalDateTime(apt.end_datetime);
                
                if (!aptStart) return;
                
                const timeStr = aptStart.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                
                let endTimeStr = '';
                if (aptEnd) {
                    endTimeStr = aptEnd.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                }
                
                const isOwnAppointment = apt.is_own_appointment === true || 
                                       (window.patientAppointments && window.patientAppointments.some(function(pa) {
                                           return pa && pa.id === apt.id;
                                       }));
                
                let title = 'Appointment';
                if (apt.service && apt.service.service_name) {
                    title = apt.service.service_name;
                } else if (apt.reason_for_visit && !apt.reason_for_visit.toLowerCase().includes('reschedule')) {
                    title = apt.reason_for_visit;
                }
                
                let status = (apt.status || 'pending').toLowerCase();
                if (status === 'missed') status = 'blocked';
                if (!isOwnAppointment) status = 'booked';
                
                const isCompleted = status === 'completed';
                const isCancelled = status === 'cancelled';
                
                // Only make patient's own appointments clickable
                const clickableClass = isOwnAppointment ? 'clickable-appointment' : '';
                const cursorStyle = isOwnAppointment ? 'cursor: pointer;' : 'cursor: default;';
                
                modalContent += `
                    <div class="day-appointment-item ${status} ${clickableClass}" data-appointment-id="${apt.id}" data-is-own="${isOwnAppointment}" style="${cursorStyle}">
                        <div class="appointment-time">
                            <i class="bi bi-clock"></i>
                            ${timeStr}${endTimeStr ? ' - ' + endTimeStr : ''}
                        </div>
                        <div class="appointment-title ${isCompleted || isCancelled ? 'text-decoration-line-through' : ''}">${title}</div>
                        ${apt.notes && isCancelled ? `<div class="appointment-notes text-muted small">${apt.notes}</div>` : ''}
                    </div>
                `;
            });
            
            // Show blocked times
            blockedTimes.forEach(function(blocked) {
                const blockStart = parseLocalDateTime(blocked.start_datetime);
                const blockEnd = parseLocalDateTime(blocked.end_datetime);
                
                if (!blockStart) return;
                
                const isFullDayClosure = blockStart.getHours() === 0 && blockStart.getMinutes() === 0 &&
                                         blockEnd && blockEnd.getHours() === 23 && blockEnd.getMinutes() === 59;
                
                const title = blocked.title || 'Clinic Unavailable';
                const displayTitle = (title === 'Clinic Closed' || isFullDayClosure) ? 'Clinic Closed' : title;
                
                let timeStr = '';
                if (!isFullDayClosure && blockStart) {
                    timeStr = blockStart.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    
                    if (blockEnd) {
                        const endTimeStr = blockEnd.toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                        timeStr += ' - ' + endTimeStr;
                    }
                }
                
                modalContent += `
                    <div class="day-appointment-item blocked">
                        <div class="appointment-time">
                            <i class="bi bi-x-circle"></i>
                            ${isFullDayClosure ? 'All Day' : timeStr}
                        </div>
                        <div class="appointment-title">${displayTitle}</div>
                        ${blocked.notes ? `<div class="appointment-notes text-muted small">${blocked.notes}</div>` : ''}
                    </div>
                `;
            });
        }
        
        modalContent += '</div>';
        
        // Update modal content
        const modal = document.getElementById('dayAppointmentsModal');
        if (modal) {
            const modalBody = modal.querySelector('.modal-body');
            if (modalBody) {
                modalBody.innerHTML = modalContent;
                
                // Add click event listeners to patient's own appointment items after content is inserted
                modalBody.querySelectorAll('.day-appointment-item[data-appointment-id]').forEach(function(item) {
                    const appointmentId = item.dataset.appointmentId;
                    if (appointmentId) {
                        const isOwnAppointment = item.dataset.isOwn === 'true';
                        item.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Close the day appointments modal first
                            const bsModal = bootstrap.Modal.getInstance(modal);
                            if (bsModal) {
                                bsModal.hide();
                            }
                            // Then open the appointment details modal
                            if (isOwnAppointment) {
                                showAppointmentDetails(parseInt(appointmentId));
                            } else {
                                // Show read-only modal for booked time (other patients' appointments)
                                showOtherAppointmentDetails(parseInt(appointmentId));
                            }
                        });
                    }
                });
            }
            
            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
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

    // Show blocked time details in modal
    function showBlockedTimeDetails(blockedTimeId) {
        // Find the blocked time in the window.blockedTimes array
        const blockedTime = window.blockedTimes.find(function(bt) {
            return bt.id == blockedTimeId;
        });
        
        if (!blockedTime) {
            console.error('Blocked time not found:', blockedTimeId);
            return;
        }
        
        // Parse datetime strings
        const startDateTime = parseLocalDateTime(blockedTime.start_datetime);
        const endDateTime = parseLocalDateTime(blockedTime.end_datetime);
        
        if (!startDateTime || !endDateTime) {
            console.error('Invalid datetime for blocked time:', blockedTime);
            return;
        }
        
        // Format date
        const dateStr = startDateTime.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Check if it's a full day closure
        const isFullDayClosure = startDateTime.getHours() === 0 && startDateTime.getMinutes() === 0 &&
                                 endDateTime.getHours() === 23 && endDateTime.getMinutes() === 59;
        
        // Format time
        let timeStr = '';
        if (isFullDayClosure) {
            timeStr = 'All Day';
        } else {
            const startTime = startDateTime.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            const endTime = endDateTime.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            timeStr = `${startTime} - ${endTime}`;
        }
        
        // Calculate duration
        const durationMs = endDateTime - startDateTime;
        const durationMinutes = Math.floor(durationMs / (1000 * 60));
        let durationStr = '';
        if (isFullDayClosure) {
            durationStr = 'All Day';
        } else if (durationMinutes < 60) {
            durationStr = `${durationMinutes} minutes`;
        } else {
            const hours = Math.floor(durationMinutes / 60);
            const minutes = durationMinutes % 60;
            if (minutes === 0) {
                durationStr = `${hours} ${hours === 1 ? 'hour' : 'hours'}`;
            } else {
                durationStr = `${hours} ${hours === 1 ? 'hour' : 'hours'} ${minutes} ${minutes === 1 ? 'minute' : 'minutes'}`;
            }
        }
        
        // Get title and reason
        const title = blockedTime.title || 'Clinic Unavailable';
        const displayTitle = (title === 'Clinic Closed' || isFullDayClosure) ? 'Clinic Closed' : title;
        const reason = blockedTime.notes || 'No reason provided';
        
        // Create modal content
        const modalContent = `
            <div class="text-center mb-4">
                <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-x-circle text-warning" style="font-size: 2.5rem; color: #92400e !important;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">${displayTitle}</h5>
            </div>
            
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                    <strong>Date:</strong>
                    <span class="ms-2">${dateStr}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <strong>Time:</strong>
                    <span class="ms-2">${timeStr}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-hourglass-split me-2 text-primary"></i>
                    <strong>Duration:</strong>
                    <span class="ms-2">${durationStr}</span>
                </div>
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle me-2 text-primary mt-1"></i>
                    <div>
                        <strong>Reason:</strong>
                        <p class="mb-0 ms-2">${reason}</p>
                    </div>
                </div>
            </div>
        `;
        
        // Create and show modal
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'blockedTimeDetailsModal';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Blocked Time Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${modalContent}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        // Remove modal from DOM after it's hidden
        modal.addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(modal);
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
                <p class="text-muted mb-0">This particular appointment was occupied by another patient.</p>
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
                position: relative;
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
                background: #fef3c7;
                border-left-color: #92400e;
                color: #78350f;
                cursor: not-allowed;
            }

            .event-item.blocked:hover {
                transform: none;
                box-shadow: none;
            }

            .event-item.booked {
                background: #f3e8ff;
                border-left: 2px solid #9333ea;
                color: #6b21a8;
                cursor: pointer;
                opacity: 1;
            }

            .event-item.booked:hover {
                transform: translateX(2px);
                box-shadow: 0 2px 8px rgba(147, 51, 234, 0.3);
                opacity: 1;
            }

            .event-item[data-read-only="true"] {
                cursor: pointer;
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

        // Add click handlers for appointments (including booked time) and blocked times
        document.querySelectorAll('.week-appointment').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.appointmentId;
                const blockedTimeId = this.dataset.blockedTimeId;
                
                // Check if it's a blocked time
                if (blockedTimeId) {
                    showBlockedTimeDetails(blockedTimeId);
                    return;
                }
                
                // Handle appointments
                if (appointmentId) {
                    const isReadOnly = this.dataset.readOnly === 'true';
                    
                    // Show details for patient's own appointments
                    if (!isReadOnly) {
                        showAppointmentDetails(appointmentId);
                    } else {
                        // Show read-only modal for booked time (other patients' appointments)
                        showOtherAppointmentDetails(appointmentId);
                    }
                }
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

        // Add click handlers for appointments (including booked time) and blocked times
        document.querySelectorAll('.day-appointment').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.appointmentId;
                const blockedTimeId = this.dataset.blockedTimeId;
                
                // Check if it's a blocked time
                if (blockedTimeId) {
                    showBlockedTimeDetails(blockedTimeId);
                    return;
                }
                
                // Handle appointments
                if (appointmentId) {
                    const isReadOnly = this.dataset.readOnly === 'true';
                    
                    // Show details for patient's own appointments
                    if (!isReadOnly) {
                        showAppointmentDetails(appointmentId);
                    } else {
                        // Show read-only modal for booked time (other patients' appointments)
                        showOtherAppointmentDetails(appointmentId);
                    }
                }
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

    // Real-time appointment updates
    function refreshAppointments(data) {
        if (!data || !data.has_updates) return;

        // Update appointment data
        if (data.appointments) {
            window.patientAppointments = data.appointments;
        }
        if (data.upcoming_appointments) {
            // Update upcoming appointments in sidebar
            updateUpcomingAppointments(data.upcoming_appointments);
        }
        if (data.pending_requests) {
            // Update pending requests
            updatePendingRequests(data.pending_requests);
        }

        // Re-render calendar
        renderCalendar();
    }

    function updateUpcomingAppointments(upcomingAppointments) {
        const upcomingList = document.getElementById('upcomingAppointments');
        if (!upcomingList) return;

        // Update the upcoming appointments list
        // This will be handled by the blade template's @forelse loop
        // We'll trigger a page refresh or update the DOM directly
        if (upcomingAppointments && upcomingAppointments.length > 0) {
            // Dispatch custom event to update the list
            window.dispatchEvent(new CustomEvent('upcomingAppointmentsUpdate', {
                detail: upcomingAppointments
            }));
        }
    }

    function updatePendingRequests(pendingRequests) {
        const pendingList = document.getElementById('pendingRequestsList');
        if (!pendingList) return;

        // Update pending requests list
        if (pendingRequests && pendingRequests.length > 0) {
            window.dispatchEvent(new CustomEvent('pendingRequestsUpdate', {
                detail: pendingRequests
            }));
        }
    }

    // Listen for appointment updates from real-time system
    window.addEventListener('appointmentUpdate', function(event) {
        refreshAppointments(event.detail);
    });

    // Export refresh function for global access
    window.refreshAppointments = refreshAppointments;
});

