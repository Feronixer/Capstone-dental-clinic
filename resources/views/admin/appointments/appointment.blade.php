@extends('layout.admin.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Appointments Calendar</h1>

    {{-- Action Buttons --}}
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-dark me-2" data-bs-toggle="modal" data-bs-target="#blockOffModal">
            <i class="bi bi-slash-circle"></i> Block Off Time
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
            <i class="bi bi-plus"></i> Add Appointment
        </button>
    </div>

    <div id="calendar"></div>
</div>
@endsection

@section( 'modals' )
{{-- ================== Block Off Time Modal ================== --}}
<div class="modal fade" id="blockOffModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="blockOffForm" method="POST" action="{{ route('appointments.block') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Block of Time</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Block off time on your calendar to prevent clients from booking appointments.</p>

          <div class="row mb-3">
            <div class="col">
              <label>Start Time</label>
              <input type="time" name="start_time" class="form-control" required>
            </div>
            <div class="col">
              <label>End Time</label>
              <input type="time" name="end_time" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Notes (Reason for blocking)</label>
            <textarea name="notes" class="form-control" placeholder="Vacation, Holiday, Maintenance"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ================== Add Appointment Modal ================== --}}
<div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="addAppointmentForm" method="POST" action="{{ route('appointments.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
                <label>Select Patient*</label>
                <select name="patient_id" class="form-select" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">
                            {{ $patient->info->first_name }} {{ $patient->info->last_name }} ({{ $patient->email }})
                        </option>
      @endforeach
  </select>
</div>
<div class="row">
  <div class="col-md-6 mb-3">
    <label>Select Date</label>
    <input type="date" name="date" class="form-control" required>
  </div>

  <div class="col-md-6 mb-3">
    <label>Start Time</label>
    <input type="time" name="start_time_clock" id="startTimeClock" class="form-control" step="1800" required>
  </div>
</div>

{{-- Optional: show a preview (read-only) of the computed end time --}}
<div class="mb-3">
  <label>End Time (auto)</label>
  <input type="text" id="endTimePreview" class="form-control" placeholder="Select service & start time" readonly>
</div>


       {{-- <div class="row">
            <div class="col-md-6 mb-3">
                <label>Select Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Start Time (30-min increments)</label>
                <input type="time" name="start_time_clock" class="form-control" step="1800" required>
            </div>
        </div>


          <div class="row custom-time d-none">
            <div class="col">
              <label>Start Time</label>
              <input type="datetime-local" name="start_time" class="form-control">
            </div>
            <div class="col">
              <label>End Time</label>
              <input type="datetime-local" name="end_time" class="form-control">
            </div>
          </div>--}}

     {{--  <div class="mb-3">
                <label>Service Name*</label>
                <select name="service" class="form-select" required>
                    <option value="">-- Select Procedure --</option>
                    @foreach($procedures as $proc)
                    <option value="{{ $proc }}">{{ $proc }}</option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1">
                    Most procedures auto-block 30–60 mins. “Crowns to dentures” auto-blocks a week.
                </small>
        </div>
--}}

<div class="mb-3">
  <label>Service*</label>
  <select name="service" id="serviceSelect" class="form-select" required>
      <option value="">-- Select Procedure --</option>
      @foreach($procedures as $p)
          <option value="{{ $p }}">{{ $p }}</option>
      @endforeach
  </select>
</div>


          <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control" placeholder="Add any relevant notes"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Schedule</button>
        </div>
      </form>
    </div>
  </div>
</div>


{{-- ================== Edit Appointment Modal ================== --}}
<div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="editAppointmentForm" method="POST">
          @csrf
          @method('PUT')
          <input type="hidden" name="id" id="editAppointmentId">

          <div class="modal-header">
              <h5 class="modal-title">Reschedule Appointment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
              <div class="mb-3">
                  <label>Patient Name</label>
                  <input type="text" id="editPatientName" name="patient_name" class="form-control" readonly>
              </div>

              <div class="mb-3">
                  <label>Service</label>
                  <input type="text" name="service" id="editService" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label>Start Time</label>
                  <input type="datetime-local" name="start_time" id="editStartTime" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label>End Time</label>
                  <input type="datetime-local" name="end_time" id="editEndTime" class="form-control">
              </div>

              <div class="mb-3">
                  <label>Notes</label>
                  <textarea name="notes" id="editNotes" class="form-control"></textarea>
              </div>
          </div>

          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Update</button>
          </div>
      </form>
    </div>
  </div>
</div>

{{--  Delete Confirmation Modal --}}
<div class="modal fade" id="deleteAppointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteAppointmentForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body text-center">
          <input type="hidden" name="id" id="deleteAppointmentId">
          <h5>Are you sure you want to delete this appointment?</h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ================== Generic Appointment Modal ================== --}}
<div class="modal fade" id="appointmentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary reschedule-btn">Reschedule</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection

{{---FullCalendar Script--}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  // Utilities
  const pad = n => String(n).padStart(2, '0');
  const toLocalInput = (d) => {
    if (!d) return '';
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
  };

  // Keep a quick in-memory list of blocked ranges for validation
  let blockedRanges = [];

  const calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'local',              // important to match local time
    initialView: 'dayGridMonth',
    themeSystem: 'bootstrap5',
    height: 'auto',
    slotMinTime: '08:00:00',
    slotMaxTime: '20:00:00',
    slotDuration: '00:30:00',
    expandRows: true,
    dayMaxEventRows: 3,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },

    events: {
      url: '/admin/appointments/events',
      failure: () => console.error('Failed to load events.'),
      success: (evs) => {
        // cache blocked ranges for validation
        blockedRanges = evs
          .filter(e => (e.extendedProps && e.extendedProps.type === 'blocked'))
          .map(e => ({
            start: new Date(e.start).getTime(),
            end:   new Date(e.end).getTime()
          }));
      }
    },

    selectable: true,

    // If you allow drag-select on the calendar to create a new appt,
    // this prevents selecting inside blocked time.
    selectAllow: function(selectInfo) {
      // Prevent selecting past dates
      if (selectInfo.start < moment().startOf('day')) {
          return false;
      }
       if (selectInfo.start < new Date().setHours(0,0,0,0)) {
        return false;
    }
      // Prevent selecting blocked slots
       var overlap = calendar.getEvents().some(function(event) {
        return event.extendedProps && event.extendedProps.type === 'blocked'
            && selectInfo.start < event.end
            && selectInfo.end > event.start;
    });
    return !overlap;
},
    eventDidMount: function(info) {
        // Color blocked slots more visibly
        if (info.event.display === 'background') {
            info.el.style.backgroundColor = info.event.backgroundColor || info.event.extendedProps.color || '#ffb3b3';
            info.el.style.opacity = 0.7;
        }
        // Style appointments
        if (info.event.extendedProps && info.event.extendedProps.type === 'appointment') {
        info.el.style.backgroundColor = '#3788d8';
        info.el.style.color = '#000000';
        info.el.style.border = '2px solid #003B46';
        }
    },
    eventClick: function(event, jsEvent, view) {
      if (event.extendedProps && event.extendedProps.type === 'appointment') {
          // Show modal with details
          $('#appointmentModal .modal-title').text(event.title);
          $('#appointmentModal .modal-body').html(`
            <strong>Status:</strong> ${event.extendedProps.status || 'pending'}<br>
            <strong>Patient:</strong> ${event.extendedProps.patient_name}<br>
            <strong>Service:</strong> ${event.extendedProps.service}<br>
            <strong>Notes:</strong> ${event.extendedProps.notes || 'None'}<br>
            <strong>Start:</strong> ${moment(event.start).format('YYYY-MM-DD HH:mm')}<br>
            <strong>End:</strong> ${moment(event.end).format('YYYY-MM-DD HH:mm')}
          `);
          $('#appointmentModal .reschedule-btn').off('click').on('click', function() {
              openRescheduleForm(event);
          });
          $('#appointmentModal').modal('show');
      }
    },

    eventOverlap: function(stillEvent, movingEvent) {
      // Prevent overlapping an appointment with a blocked background (safety)
      if (stillEvent.extendedProps?.type === 'blocked' || movingEvent.extendedProps?.type === 'blocked') {
        return false;
      }
      return true;
    },

    eventClick: function(info) {
      const ev = info.event;
      const ex = ev.extendedProps || {};

      if (ex.type === 'blocked') {
        // Optional: show a small toast/modal explaining it's blocked
        return;
      }

      // --- Fill Edit Modal for appointments ---
      document.getElementById("editAppointmentId").value = ev.id;
      document.getElementById("editPatientName").value = ex.patient_name || (ev.title?.split(' - ')[0] ?? '');
      document.getElementById("editService").value = ex.service || (ev.title?.split(' - ')[1] ?? '');
      document.getElementById("editNotes").value = ex.notes || '';

      document.getElementById("editStartTime").value = toLocalInput(ev.start);
      document.getElementById("editEndTime").value   = toLocalInput(ev.end);

      document.getElementById("editAppointmentForm").action = `/admin/appointments/${ev.id}`;

      new bootstrap.Modal(document.getElementById('editAppointmentModal')).show();
    }
  });

  calendar.render();

  // ---- Prevent creating an appointment against blocked time via form ----
  const addForm = document.getElementById('addAppointmentForm');
  if (addForm) {
    addForm.addEventListener('submit', function (e) {
      // derive start/end from inputs before submit
      const dateStr = addForm.querySelector('input[name="date"]').value;        // YYYY-MM-DD
      const slot    = addForm.querySelector('select[name="time_slot"]').value;  // e.g. "09:00-09:30" or "custom"

      let startMs, endMs;

      if (slot !== 'custom') {
        const [s, f] = slot.split('-'); // "HH:MM"
        startMs = new Date(`${dateStr}T${s}:00`).getTime();
        endMs   = new Date(`${dateStr}T${f}:00`).getTime();
      } else {
        const startStr = addForm.querySelector('input[name="start_time"]').value;
        const endStr   = addForm.querySelector('input[name="end_time"]').value;
        if (!startStr || !endStr) return; // browser HTML5 will catch required anyway
        startMs = new Date(startStr).getTime();
        endMs   = new Date(endStr).getTime();
      }

      // Check overlap with any blocked range
      const overlapsBlocked = blockedRanges.some(b => !(endMs <= b.start || startMs >= b.end));
      if (overlapsBlocked) {
        e.preventDefault();
        // show a friendly message
        alert('This time is blocked. Please choose another time or unblock it first.');
      }
    });
  }
});
</script>
@endsection
