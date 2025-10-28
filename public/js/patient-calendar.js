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
            const classes = isToday ? 'mini-cal-day current' : 'mini-cal-day';
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

        periodDisplay.textContent = new Date(year, month).toLocaleDateString('en-US', {
            month: 'long',
            year: 'numeric'
        });

        if (currentView === 'month') {
            renderMonthView(content, year, month);
        } else if (currentView === 'week') {
            renderWeekView(content);
        } else {
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

                    html += `
                        <div class="calendar-day ${isToday ? 'today' : ''}" data-date="${year}-${month + 1}-${dayCount}">
                            <div class="day-number">${dayCount}</div>
                            <div class="day-events">
                                <!-- Events will be added here -->
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
        `;

        document.head.appendChild(style);
    }

    function renderWeekView(container) {
        container.innerHTML = '<div class="text-center p-5"><p class="text-muted">Week view coming soon...</p></div>';
    }

    function renderDayView(container) {
        container.innerHTML = '<div class="text-center p-5"><p class="text-muted">Day view coming soon...</p></div>';
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
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
            initMiniCalendar();
        });

        document.getElementById('nextPeriod').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
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

