// Patient Calendar JavaScript

document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    let currentView = 'month';

    // Initialize
    initMiniCalendar();
    renderCalendar();
    initEventListeners();

    // Mini Calendar
    function initMiniCalendar() {
        const grid = document.getElementById('miniCalendarGrid');
        const monthYear = document.getElementById('miniCalMonthYear');

        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();

        monthYear.textContent = new Date(year, month).toLocaleDateString('en-US', {
            month: 'long',
            year: 'numeric'
        });

        // Create mini calendar grid
        const weekDays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
        let html = '';

        // Add day headers
        weekDays.forEach(day => {
            html += `<div class="mini-cal-day header">${day}</div>`;
        });

        // Get first day of month and total days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        // Add empty cells for days before month starts
        for (let i = 0; i < firstDay; i++) {
            html += `<div class="mini-cal-day"></div>`;
        }

        // Add days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === today.getDate() &&
                           month === today.getMonth() &&
                           year === today.getFullYear();

            // Check if this day has appointments
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const hasAppts = getAppointmentsForDate(dateStr).length > 0;

            let classes = isToday ? 'mini-cal-day current' : 'mini-cal-day';
            if (hasAppts) classes += ' has-events';

            html += `<div class="${classes}">${day}</div>`;
        }

        grid.innerHTML = html;
    }

    // Main Calendar Rendering
    function renderCalendar() {
        const content = document.getElementById('calendarContent');
        const periodDisplay = document.getElementById('currentPeriodDisplay');

        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();

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

                    let appointmentsHtml = '';
                    dayAppointments.forEach(apt => {
                        const time = new Date(apt.start_datetime).toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                        // Get proper title - don't show reschedule request text
                        let title = 'Appointment';
                        if (apt.service && apt.service.service_name) {
                            title = apt.service.service_name;
                        } else if (apt.reason_for_visit && !apt.reason_for_visit.toLowerCase().includes('reschedule')) {
                            title = apt.reason_for_visit;
                        }
                        const status = apt.status ? apt.status.toLowerCase() : 'pending';
                        const isCompleted = status === 'completed';
                        const strikethrough = isCompleted ? 'style="text-decoration: line-through; opacity: 0.7;"' : '';

                        appointmentsHtml += `
                            <div class="event-item ${status}" data-appointment-id="${apt.id}" ${strikethrough}>
                                <div class="event-time">${time}</div>
                                <div class="event-title">${title}</div>
                            </div>
                        `;
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

        // Add click handlers to appointment items
        document.querySelectorAll('.event-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const appointmentId = this.dataset.appointmentId;
                showAppointmentDetails(appointmentId);
            });
        });
    }

    // Get appointments for a specific date
    function getAppointmentsForDate(dateStr) {
        if (!window.patientAppointments) return [];

        return window.patientAppointments.filter(apt => {
            const aptDate = apt.start_datetime.split(' ')[0];
            return aptDate === dateStr;
        });
    }

    // Show appointment details in modal
    function showAppointmentDetails(appointmentId) {
        const appointment = window.patientAppointments.find(apt => apt.id == appointmentId);
        if (!appointment) return;

        const startDate = new Date(appointment.start_datetime);
        const endDate = new Date(appointment.end_datetime);

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

        // Disable reschedule button if appointment is completed or cancelled
        const rescheduleBtn = document.getElementById('modalRescheduleBtn');
        if (rescheduleBtn) {
            if (statusClass === 'completed' || statusClass === 'cancelled') {
                rescheduleBtn.disabled = true;
                rescheduleBtn.style.opacity = '0.5';
                rescheduleBtn.style.cursor = 'not-allowed';
                rescheduleBtn.title = `Cannot reschedule ${statusClass} appointments`;
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
                padding: 1rem;
                text-align: center;
                font-weight: 600;
                color: #64748b;
                font-size: 0.9rem;
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
                min-height: 120px;
                padding: 0.75rem;
                cursor: pointer;
                transition: all 0.2s;
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
                background: #667eea;
                color: white;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .day-number {
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.5rem;
            }

            .day-events {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .event-item {
                background: #e3f2fd;
                border-left: 3px solid #2196F3;
                padding: 0.4rem;
                border-radius: 4px;
                font-size: 0.75rem;
                cursor: pointer;
                transition: all 0.2s;
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

            .event-time {
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.1rem;
            }

            .event-title {
                color: #64748b;
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

        const hours = Array.from({length: 13}, (_, i) => i + 8); // 8 AM to 8 PM
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
                    const aptHour = new Date(apt.start_datetime).getHours();
                    return aptHour === hour;
                });

                let cellContent = '';
                dayAppointments.forEach(apt => {
                    const time = new Date(apt.start_datetime).toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    const title = apt.service ? apt.service.service_name : (apt.reason_for_visit || 'Appointment');
                    const status = apt.status ? apt.status.toLowerCase() : 'pending';
                    const isCompleted = status === 'completed';
                    const strikethrough = isCompleted ? 'style="text-decoration: line-through; opacity: 0.7;"' : '';

                    cellContent += `
                        <div class="week-appointment ${status}" data-appointment-id="${apt.id}" ${strikethrough}>
                            <div class="week-apt-time">${time}</div>
                            <div class="week-apt-title">${title}</div>
                        </div>
                    `;
                });

                html += `<div class="week-cell">${cellContent}</div>`;
            }
            html += '</div>';
        });
        html += '</div></div>';

        container.innerHTML = html;

        // Add click handlers
        document.querySelectorAll('.week-appointment').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.appointmentId;
                showAppointmentDetails(appointmentId);
            });
        });
    }

    function renderDayView(container) {
        const hours = Array.from({length: 13}, (_, i) => i + 8); // 8 AM to 8 PM
        const dateStr = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(currentDate.getDate()).padStart(2, '0')}`;
        const dayAppointments = getAppointmentsForDate(dateStr);

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
                const aptHour = new Date(apt.start_datetime).getHours();
                return aptHour === hour;
            });

            html += `
                <div class="day-time-row">
                    <div class="day-time-label">
                        ${hour === 12 ? '12' : hour % 12}:00 ${hour < 12 ? 'AM' : 'PM'}
                    </div>
                    <div class="day-time-content">
            `;

            if (hourAppointments.length > 0) {
                hourAppointments.forEach(apt => {
                    const startTime = new Date(apt.start_datetime).toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    const endTime = new Date(apt.end_datetime).toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    const title = apt.service ? apt.service.service_name : (apt.reason_for_visit || 'Appointment');
                    const status = apt.status ? apt.status.toLowerCase() : 'pending';
                    const isCompleted = status === 'completed';
                    const strikethrough = isCompleted ? 'style="text-decoration: line-through; opacity: 0.7;"' : '';

                    html += `
                        <div class="day-appointment ${status}" data-appointment-id="${apt.id}" ${strikethrough}>
                            <div class="day-apt-header">
                                <div class="day-apt-time">
                                    <i class="bi bi-clock me-1"></i>${startTime} - ${endTime}
                                </div>
                                <span class="day-apt-badge ${status}">${apt.status}</span>
                            </div>
                            <div class="day-apt-title">${title}</div>
                            ${apt.notes ? `<div class="day-apt-notes"><i class="bi bi-sticky me-1"></i>${apt.notes}</div>` : ''}
                        </div>
                    `;
                });
            } else {
                html += '<div class="day-empty-slot">No appointments</div>';
            }

            html += '</div></div>';
        });
        html += '</div></div>';

        container.innerHTML = html;

        // Add click handlers
        document.querySelectorAll('.day-appointment').forEach(item => {
            item.addEventListener('click', function() {
                const appointmentId = this.dataset.appointmentId;
                showAppointmentDetails(appointmentId);
            });
        });
    }

    // Event Listeners
    function initEventListeners() {
        // View toggles
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
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
            initMiniCalendar();
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
            initMiniCalendar();
        });

        document.getElementById('todayBtn').addEventListener('click', () => {
            currentDate = new Date();
            renderCalendar();
            initMiniCalendar();
        });

        // Mini calendar navigation
        document.getElementById('miniCalPrev').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            initMiniCalendar();
            renderCalendar();
        });

        document.getElementById('miniCalNext').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            initMiniCalendar();
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

