@extends('layout.admin.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="container-fluid px-4 py-4">
    <!-- Success Message -->
    @if(session('success'))
        <x-toast-message type="success" :message="session('success')" />
    @endif

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #16a085 0%, #0e7862 100%);">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Good {{ now()->format('A') === 'AM' ? 'morning' : (now()->format('A') === 'PM' && now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0">You have {{ $todayAppointments }} appointments scheduled for today and {{ $totalPatients }} total patients.</p>
                    </div>
                    <div class="text-white" style="font-size: 4rem; opacity: 0.3;">
                        <i class="bi bi-tooth"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <!-- Total Patients -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #16a085 0%, #0e7862 100%);">
                                <i class="bi bi-people-fill text-white" style="font-size: 1.8rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1">Total Patients</p>
                            <h3 class="fw-bold mb-0">{{ $totalPatients }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #3498db 0%, #2574b8 100%);">
                                <i class="bi bi-calendar-check-fill text-white" style="font-size: 1.8rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1">Today's Appointments</p>
                            <h3 class="fw-bold mb-0">{{ $todayAppointments }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Members -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #9b59b6 0%, #7e3d95 100%);">
                                <i class="bi bi-person-badge-fill text-white" style="font-size: 1.8rem;"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <p class="text-muted mb-1">Staff Members</p>
                            <h3 class="fw-bold mb-0">{{ $staffMembers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row mb-4 g-3">
        <!-- Left Column -->
        <div class="col-lg-6">
            <!-- Today's Appointments Section -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 me-2 text-primary"></i>Today's Appointments
                        </h5>
                        <a href="{{ route('admin-appointment') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($todayAppointmentsList->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-0">No appointments scheduled for today</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($todayAppointmentsList as $appointment)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1">
                                                @if($appointment->patient && $appointment->patient->info)
                                                    {{ $appointment->patient->info->first_name }} {{ $appointment->patient->info->last_name }}
                                                @else
                                                    Unknown Patient
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($appointment->start_datetime)->format('g:i A') }}
                                                @if($appointment->service)
                                                    - {{ $appointment->service->service_name }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="badge
                                                @if($appointment->status === 'Pending') bg-warning
                                                @elseif($appointment->status === 'Confirmed') bg-primary
                                                @elseif($appointment->status === 'Completed') bg-success
                                                @else bg-secondary
                                                @endif">
                                                {{ $appointment->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Patients Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-people me-2 text-primary"></i>Recent Patients
                        </h5>
                        <a href="{{ route('admin-account-management') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentPatients->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-person-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-0">No patients registered yet</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentPatients as $patient)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill text-success"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1">
                                                @if($patient->info)
                                                    {{ $patient->info->first_name }} {{ $patient->info->last_name }}
                                                @else
                                                    {{ $patient->name }}
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-envelope me-1"></i>{{ $patient->email }}
                                                @if($patient->info && $patient->info->phone)
                                                    <span class="ms-2"><i class="bi bi-telephone me-1"></i>{{ $patient->info->phone }}</span>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Appointment Calendar -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-calendar3 me-2" style="color: #16a085;"></i>Appointment Calendar
                    </h5>
                    <small class="text-muted">{{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}</small>
                </div>
                <div class="card-body">
                    <div class="mini-calendar" id="dashboard-calendar">
                        <!-- Calendar will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Row -->
    <div class="row g-3">
        <!-- User Demographics -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people-fill me-2" style="color: #16a085;"></i>User Demographics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sex Chart -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-center text-muted mb-3">Sex</h6>
                            <div style="height: 200px; position: relative;">
                                <canvas id="sexChart"></canvas>
                            </div>
                            <div class="mt-3 d-flex justify-content-center gap-3">
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #67B7DC; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Male</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #6794DC; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Female</small>
                                </div>
                            </div>
                        </div>
                        <!-- Age Chart -->
                        <div class="col-md-6">
                            <h6 class="text-center text-muted mb-3">Age</h6>
                            <div style="height: 200px; position: relative;">
                                <canvas id="ageChart"></canvas>
                            </div>
                            <div class="mt-3 d-flex justify-content-center gap-3">
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #6AD4DD; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Pediatric</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div style="width: 12px; height: 12px; background: #50B4C8; border-radius: 2px;" class="me-2"></div>
                                    <small class="text-muted">Adult</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Feedback -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-star-fill me-2" style="color: #16a085;"></i>Service Feedback
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 280px;">
                        <canvas id="feedbackChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mini-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.mini-calendar-header {
    text-align: center;
    font-weight: 600;
    padding: 8px;
    color: #6c757d;
    font-size: 0.85rem;
}

.mini-calendar-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px;
    border-radius: 8px;
    font-size: 0.9rem;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.mini-calendar-day:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.mini-calendar-day.has-appointments {
    background: #d1e7dd;
    border: 2px solid #16a085;
}

.mini-calendar-day.today {
    background: #16a085;
    color: white;
    font-weight: bold;
}

.mini-calendar-day.other-month {
    opacity: 0.3;
}

.appointment-count {
    font-size: 0.7rem;
    color: #16a085;
    margin-top: 2px;
}

.mini-calendar-day.today .appointment-count {
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointments = @json($appointments);
    const blockedTimes = @json($blockedTimes);
    const currentMonth = {{ $currentMonth }};
    const currentYear = {{ $currentYear }};

    // Helper function to parse datetime strings as LOCAL time
    const parseLocalDateTime = (datetimeStr) => {
        if (!datetimeStr || typeof datetimeStr !== 'string') {
            console.warn('Invalid datetime string:', datetimeStr);
            return null;
        }

        try {
            const [datePart, timePart] = datetimeStr.split(' ');
            const [year, month, day] = datePart.split('-').map(Number);
            const [hours, minutes, seconds] = timePart.split(':').map(Number);

            if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hours) || isNaN(minutes)) {
                console.warn('Invalid datetime values:', datetimeStr);
                return null;
            }

            return new Date(year, month - 1, day, hours, minutes, seconds || 0);
        } catch (error) {
            console.error('Error parsing datetime:', datetimeStr, error);
            return null;
        }
    };

    // Generate mini calendar
    function generateMiniCalendar() {
        const calendarDiv = document.getElementById('dashboard-calendar');
        if (!calendarDiv) return;

        calendarDiv.innerHTML = '';

        // Add day headers
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        days.forEach(day => {
            const header = document.createElement('div');
            header.className = 'mini-calendar-header';
            header.textContent = day;
            calendarDiv.appendChild(header);
        });

        // Generate calendar days
        const firstDay = new Date(currentYear, currentMonth - 1, 1);
        const lastDay = new Date(currentYear, currentMonth, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'mini-calendar-day other-month';
            calendarDiv.appendChild(emptyDay);
        }

        // Add days of the month
        const today = new Date();
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'mini-calendar-day';

            const cellDate = new Date(currentYear, currentMonth - 1, day);

            // Check if today
            if (cellDate.toDateString() === today.toDateString()) {
                dayElement.classList.add('today');
            }

            // Count appointments for this day
            const dayAppointments = appointments.filter(apt => {
                const aptDate = parseLocalDateTime(apt.start_datetime);
                return aptDate && aptDate.toDateString() === cellDate.toDateString();
            });

            if (dayAppointments.length > 0) {
                dayElement.classList.add('has-appointments');
            }

            dayElement.innerHTML = `
                <div>${day}</div>
                ${dayAppointments.length > 0 ? `<div class="appointment-count">${dayAppointments.length} apt${dayAppointments.length > 1 ? 's' : ''}</div>` : ''}
            `;

            // Click to go to appointment page
            dayElement.addEventListener('click', function() {
                window.location.href = '{{ route("admin-appointment") }}?month=' + currentMonth + '&year=' + currentYear;
            });

            calendarDiv.appendChild(dayElement);
        }
    }

    generateMiniCalendar();

    // Initialize Charts
    const chartColors = {
        teal: ['#67B7DC', '#6794DC'],
        cyan: ['#6AD4DD', '#50B4C8'],
        primary: '#16a085'
    };

    // Sex Distribution Chart
    const sexCtx = document.getElementById('sexChart');
    if (sexCtx) {
        new Chart(sexCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [{{ $maleCount }}, {{ $femaleCount }}],
                    backgroundColor: chartColors.teal,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $maleCount + $femaleCount }};
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Age Distribution Chart
    const ageCtx = document.getElementById('ageChart');
    if (ageCtx) {
        new Chart(ageCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pediatric', 'Adult'],
                datasets: [{
                    data: [{{ $pediatricCount }}, {{ $adultCount }}],
                    backgroundColor: chartColors.cyan,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $pediatricCount + $adultCount }};
                                const value = context.parsed;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Service Feedback Chart
    const feedbackCtx = document.getElementById('feedbackChart');
    if (feedbackCtx) {
        const feedbackData = @json($feedbackData);
        new Chart(feedbackCtx, {
            type: 'bar',
            data: {
                labels: ['⭐⭐⭐⭐⭐', '⭐⭐⭐⭐', '⭐⭐⭐', '⭐⭐', '⭐'],
                datasets: [{
                    data: [
                        feedbackData[5] || 0,
                        feedbackData[4] || 0,
                        feedbackData[3] || 0,
                        feedbackData[2] || 0,
                        feedbackData[1] || 0
                    ],
                    backgroundColor: '#16a085',
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const starCount = 5 - context[0].dataIndex;
                                return starCount + ' Star' + (starCount !== 1 ? 's' : '');
                            },
                            label: function(context) {
                                return 'Ratings: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
