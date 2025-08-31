@extends('layout.patient.app')

@section('content')

<div class="main-content-area">
    <aside class="left-sidebar">
        <div class="left-sidebar-card">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">ToothTalk: Appointment Tracker</h3>
            <div class="mini-calendar-header">
                <button id="miniCalPrev" class="p-1 text-gray-500 hover:text-gray-700"><i class="fas fa-chevron-left text-sm"></i></button>
                <span id="miniCalMonthYearDisplay" class="font-semibold text-gray-700">April 2025</span>
                <button id="miniCalNext" class="p-1 text-gray-500 hover:text-gray-700"><i class="fas fa-chevron-right text-sm"></i></button>
            </div>
            <div id="miniCalendarGrid" class="mini-calendar-grid"></div>
        </div>
        <div id="appointmentDescriptionCard" class="left-sidebar-card description-card mt-6" style="display: none;">
            <h4>Description:</h4>
            <p id="appointmentDescriptionText">You have a follow-up appointment for this day.</p>
            <ul class="list-disc list-inside mt-2">
                <li id="appointmentNatureText">Nature of appointment: - Flexible dentures</li>
            </ul>
        </div>
    </aside>


    <script>

	$(document).ready(function() {
	    var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		/*  className colors

		className: default(transparent), important(red), chill(pink), success(green), info(blue)

		*/


		/* initialize the external events
		-----------------------------------------------------------------*/

		$('#external-events div.external-event').each(function() {

			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()) // use the element's text as the event title
			};

			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);

			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});

		});


		/* initialize the calendar
		-----------------------------------------------------------------*/

		var calendar =  $('#calendar').fullCalendar({
			header: {
				left: 'title',
				center: 'agendaDay,agendaWeek,month',
				right: 'prev,next today'
			},
			editable: true,
			firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
			selectable: true,
			defaultView: 'month',

			axisFormat: 'h:mm',
			columnFormat: {
                month: 'ddd',    // Mon
                week: 'ddd d', // Mon 7
                day: 'dddd M/d',  // Monday 9/7
                agendaDay: 'dddd d'
            },
            titleFormat: {
                month: 'MMMM yyyy', // September 2009
                week: "MMMM yyyy", // September 2009
                day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
            },
			allDaySlot: false,
			selectHelper: true,
			select: function(start, end, allDay) {
				var title = prompt('Event Title:');
				if (title) {
					calendar.fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},
			droppable: true, // this allows things to be dropped onto the calendar !!!
			drop: function(date, allDay) { // this function is called when something is dropped

				// retrieve the dropped element's stored Event Object
				var originalEventObject = $(this).data('eventObject');

				// we need to copy it, so that multiple events don't have a reference to the same object
				var copiedEventObject = $.extend({}, originalEventObject);

				// assign it the date that was reported
				copiedEventObject.start = date;
				copiedEventObject.allDay = allDay;

				// render the event on the calendar
				// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
				$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

				// is the "remove after drop" checkbox checked?
				if ($('#drop-remove').is(':checked')) {
					// if so, remove the element from the "Draggable Events" list
					$(this).remove();
				}

			},

			events: [
				{
					title: 'All Day Event',
					start: new Date(y, m, 1)
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false,
					className: 'info'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false,
					className: 'info'
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
					className: 'important'
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false,
					className: 'important'
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false,
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'https://ccp.cloudaccess.net/aff.php?aff=5188',
					className: 'success'
				}
			],
		});


	});

</script>
<style>

	body {
		text-align: center;
		font-size: 14px;
		font-family: 'Roboto', sans-serif;
		background:url(http://www.digiphotohub.com/wp-content/uploads/2015/09/bigstock-Abstract-Blurred-Background-Of-92820527.jpg);
		}

	#wrap {
		width: 1100px;
		margin: 0 auto;
		}

	#external-events {
		float: left;
		width: 150px;
		padding: 0 10px;
		text-align: left;
		}

	#external-events h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;
		}

	.external-event { /* try to mimick the look of a real event */
		margin: 10px 0;
		padding: 2px 4px;
		background: #3366CC;
		color: #fff;
		font-size: .85em;
		cursor: pointer;
		}

	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
		}

	#external-events p input {
		margin: 0;
		vertical-align: middle;
		}

	#calendar {
/* 		float: right; */
        margin: 0 auto;
		width: 80rem;
		background-color: #FFFFFF;
		border-radius: 6px;
        box-shadow: 0 1px 2px #C3C3C3;
		-webkit-box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
-moz-box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
		}

</style>

<div id='wrap'>

<div id='calendar'></div>
 <div id="rescheduleSection" class="reschedule-section mt-8" style="display: none;">
            <div class="flex items-center mb-4">
                <input type="checkbox" id="requestRescheduleCheckbox" class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3">
                <label for="requestRescheduleCheckbox" class="text-lg font-medium text-gray-800">Request for Reschedule?</label>
            </div>
            <div id="rescheduleFormContainer" style="display: none;">
                <div class="mb-4">
                    <label for="rescheduleReason" class="block text-sm font-medium text-gray-700 mb-1">State the Reason:</label>
                    <input type="text" id="rescheduleReason" name="rescheduleReason" class="form-input-underline">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-input-icon">
                        <label for="rescheduleDate" class="block text-sm font-medium text-gray-700 mb-1">Select Date:</label>
                        <input type="date" id="rescheduleDate" name="rescheduleDate" class="form-input-underline">
                        <i class="fas fa-calendar-alt icon"></i>
                    </div>
                    <div class="form-input-icon">
                        <label for="rescheduleTime" class="block text-sm font-medium text-gray-700 mb-1">Select Time:</label>
                        <input type="time" id="rescheduleTime" name="rescheduleTime" class="form-input-underline">
                        <i class="fas fa-clock icon"></i>
                    </div>
                </div>
                <button id="submitRescheduleBtn" class="btn-submit-reschedule font-semibold rounded-md shadow-sm">Submit</button>
            </div>
        </div>
</div>


<div style='clear:both'></div>
</div>


<div id="ratingModal" class="rating-modal-overlay">
    <div class="rating-modal-content">
        <button id="closeRatingModalBtn" class="rating-close-btn">&times;</button>
        <h2 class="rating-title">Rate our Service</h2>
        <div id="starsRatingContainer" class="stars-container">
            <span class="star" data-value="1"><i class="fas fa-star"></i><span class="star-number">1</span></span>
            <span class="star" data-value="2"><i class="fas fa-star"></i><span class="star-number">2</span></span>
            <span class="star" data-value="3"><i class="fas fa-star"></i><span class="star-number">3</span></span>
            <span class="star" data-value="4"><i class="fas fa-star"></i><span class="star-number">4</span></span>
            <span class="star" data-value="5"><i class="fas fa-star"></i><span class="star-number">5</span></span>
        </div>
        <input type="hidden" id="selectedRating" name="rating" value="0">
        <button id="submitRatingBtn" class="submit-rating-btn" disabled>Submit Rating</button>
        <p id="ratingMessage" class="mt-4 text-sm" style="display:none;"></p>
    </div>
</div>



@endsection
