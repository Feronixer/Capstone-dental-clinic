<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  // helper for CSRF
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  const calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'local',
    initialView: 'timeGridWeek',
    themeSystem: 'bootstrap5',
    height: 'auto',
    slotMinTime: '08:00:00',
    slotMaxTime: '20:00:00',
    slotDuration: '00:30:00',
    nowIndicator: true,
    expandRows: true,
    dayMaxEventRows: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },

    // ✅ single source; your controller already merges appointments + blocked
    events: {
      url: '/admin/appointments/events',
      failure: () => console.error('Failed to load /admin/appointments/events')
    },

    selectable: true,

    // Optional: click-drag on calendar to quickly create a *blocked* slot
    select: async function(info) {
      if (!confirm(`Block ${info.start.toLocaleString()} → ${info.end.toLocaleString()} ?`)) return;

      // Controller expects date (YYYY-MM-DD) + H:i start/end
      const toParts = d => {
        const pad = n => String(n).padStart(2,'0');
        return {
          date: `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`,
          time: `${pad(d.getHours())}:${pad(d.getMinutes())}`
        };
      };
      const s = toParts(info.start);
      const e = toParts(info.end);

      const body = new URLSearchParams({
        date: s.date,
        start_time: s.time,
        end_time: e.time
      });

      const res = await fetch('/admin/appointments/appointments/block', { // matches your routes prefix
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
          'X-CSRF-TOKEN': csrf
        },
        body
      });

      if (!res.ok) {
        console.error('Block POST failed');
        alert('Failed to block time.');
        return;
      }
      calendar.refetchEvents();
    },

    // Prevent dropping/overlap onto background blocked events
    eventOverlap: function(still, moving) {
      if (still.extendedProps?.type === 'blocked' || moving.extendedProps?.type === 'blocked') {
        return false;
      }
      return true;
    },

    // Style blocked backgrounds a bit more obviously
    eventDidMount: function(arg) {
      if (arg.event.extendedProps?.type === 'blocked') {
        // background events come from your API with display:'background'
        // We can tone/color them here too:
        arg.el.style.backgroundColor = 'rgba(255, 77, 77, 0.35)'; // light red
      }
    }
  });

  calendar.render();
});
