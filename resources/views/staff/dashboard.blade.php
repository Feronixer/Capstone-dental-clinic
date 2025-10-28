@extends('layout.staff.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    .card {
        transition: all 0.3s ease;
    }
    .btn {
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Success Message -->
    @if(session('success'))
        <x-toast-message type="success" :message="session('success')" />
    @endif

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Good {{ now()->format('A') === 'AM' ? 'morning' : (now()->format('A') === 'PM' && now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0">You have {{ $todayAppointments }} appointments to manage today from {{ $totalPatients }} active patients.</p>
                    </div>
                    <div class="text-white" style="font-size: 4rem; opacity: 0.3;">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-4">
        <!-- Total Patient Load -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 65px; height: 65px; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                                <i class="bi bi-people-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Patient Load</p>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem; color: #0d6efd;">{{ $totalPatients }}</h2>
                            <small class="text-muted">Active patients</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 65px; height: 65px; background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);">
                                <i class="bi bi-calendar-check-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Today's Schedule</p>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem; color: #0dcaf0;">{{ $todayAppointments }}</h2>
                            <small class="text-muted">Appointments to manage</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Actions -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 65px; height: 65px; background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);">
                                <i class="bi bi-clock-history text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Pending</p>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem; color: #6c757d;">{{ $pendingAppointments }}</h2>
                            <small class="text-muted">Need confirmation</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row mb-4 g-4">
        <!-- Left Column - Today's Appointments -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 me-2 text-primary"></i>Today's Appointments
                        </h5>
                        <a href="{{ route('staff-appointment') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @if($todayAppointmentsList->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-0">No appointments scheduled for today</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($todayAppointmentsList as $appointment)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
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
                                            <div class="text-muted small">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($appointment->start_datetime)->format('g:i A') }}
                                                @if($appointment->service)
                                                    | <i class="bi bi-scissors me-1"></i>{{ $appointment->service->service_name }}
                                                @endif
                                            </div>
                                            @if($appointment->patient && $appointment->patient->info && $appointment->patient->info->phone)
                                                <div class="text-muted small mt-1">
                                                    <i class="bi bi-telephone me-1"></i>{{ $appointment->patient->info->phone }}
                                                </div>
                                            @endif
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
        </div>

        <!-- Right Column - Calendar & Quick Actions -->
        <div class="col-lg-6">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('staff-appointment') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-calendar-check d-block mb-2" style="font-size: 1.8rem;"></i>
                                <span class="fw-semibold">Appointments</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('staff-patient-records') }}" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-file-medical-fill d-block mb-2" style="font-size: 1.8rem;"></i>
                                <span class="fw-semibold">Patient Records</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('staff-post-procedural') }}" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-file-earmark-post d-block mb-2" style="font-size: 1.8rem;"></i>
                                <span class="fw-semibold">Post-Procedural</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('staff-content-management') }}" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-megaphone-fill d-block mb-2" style="font-size: 1.8rem;"></i>
                                <span class="fw-semibold">Announcements</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mini Calendar -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 me-2 text-primary"></i>Appointment Calendar
                        </h5>
                        <small class="text-muted fw-semibold">{{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mini-calendar" id="dashboard-calendar">
                        <!-- Calendar will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patients Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-people me-2 text-primary"></i>Recent Patient Activity
                        </h5>
                        <a href="{{ route('staff-patient-records') }}" class="btn btn-sm btn-outline-primary">
                            View All Patients <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($recentPatients->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-person-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-0">No recent patient activity</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($recentPatients as $patient)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow-sm h-100 hover-shadow transition">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 55px; height: 55px; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                                                        <i class="bi bi-person-fill text-white" style="font-size: 1.6rem;"></i>
                                                    </div>
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <h6 class="mb-2 fw-bold text-truncate">
                                                        @if($patient->info)
                                                            {{ $patient->info->first_name }} {{ $patient->info->last_name }}
                                                        @else
                                                            {{ $patient->name }}
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted d-block text-truncate mb-1">
                                                        <i class="bi bi-envelope me-1"></i>{{ $patient->email }}
                                                    </small>
                                                    @if($patient->info && $patient->info->phone)
                                                        <small class="text-muted d-block">
                                                            <i class="bi bi-telephone me-1"></i>{{ $patient->info->phone }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mini-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}

.mini-calendar-header {
    text-align: center;
    font-weight: 600;
    padding: 6px;
    color: #6c757d;
    font-size: 0.8rem;
}

.mini-calendar-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6px;
    border-radius: 8px;
    font-size: 0.85rem;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.mini-calendar-day:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.mini-calendar-day.has-appointments {
    background: #cfe2ff;
    border: 2px solid #0d6efd;
}

.mini-calendar-day.today {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    font-weight: bold;
}

.mini-calendar-day.other-month {
    opacity: 0.3;
}

.appointment-count {
    font-size: 0.65rem;
    color: #0d6efd;
    margin-top: 2px;
}

.mini-calendar-day.today .appointment-count {
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointments = @json($appointments);
    const currentMonth = {{ $currentMonth }};
    const currentYear = {{ $currentYear }};

    // Helper function to parse datetime strings as LOCAL time
    const parseLocalDateTime = (datetimeStr) => {
        if (!datetimeStr || typeof datetimeStr !== 'string') {
            return null;
        }

        try {
            const [datePart, timePart] = datetimeStr.split(' ');
            const [year, month, day] = datePart.split('-').map(Number);
            const [hours, minutes, seconds] = timePart.split(':').map(Number);

            if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hours) || isNaN(minutes)) {
                return null;
            }

            return new Date(year, month - 1, day, hours, minutes, seconds || 0);
        } catch (error) {
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
                window.location.href = '{{ route("staff-appointment") }}?month=' + currentMonth + '&year=' + currentYear;
            });

            calendarDiv.appendChild(dayElement);
        }
    }

    generateMiniCalendar();
});
</script>
@endsection
