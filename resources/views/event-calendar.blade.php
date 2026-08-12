<div class="calendar-wrapper" id="event-calendar" data-event-url="{{ $eventUrl }}">

    {{-- ============================================================
         Calendar Section
    ============================================================= --}}

    <div class="calendar-container">

        <div class="calendar-header">

            @php

                /*
                |--------------------------------------------------------------------------
                | Previous Month
                |--------------------------------------------------------------------------
                */

                $previousMonth = $calendar->month - 1;
                $previousYear = $calendar->year;

                if ($previousMonth == 0) {
                    $previousMonth = 12;
                    $previousYear--;
                }

                /*
                |--------------------------------------------------------------------------
                | Next Month
                |--------------------------------------------------------------------------
                */

                $nextMonth = $calendar->month + 1;
                $nextYear = $calendar->year;

                if ($nextMonth == 13) {
                    $nextMonth = 1;
                    $nextYear++;
                }

            @endphp


            {{-- Previous --}}
            <a class="calendar-btn" href="?year={{ $previousYear }}&month={{ $previousMonth }}">
                ‹ Previous
            </a>


            {{-- Current Month --}}
            <h2>
                {{ nepali_month($calendar->month) }}
                {{ $calendar->year }}
            </h2>


            {{-- Next --}}
            <a class="calendar-btn" href="?year={{ $nextYear }}&month={{ $nextMonth }}">
                Next ›
            </a>

        </div>


        {{-- ========================================================
             Calendar Table
        ========================================================= --}}

        <table class="calendar-table">

            <thead>

                <tr>

                    @foreach ($calendar->weekdays as $index => $weekday)
                        <th class="{{ $index == 0 || $index == 6 ? 'holiday' : '' }}">
                            {{ $weekday }}
                        </th>
                    @endforeach

                </tr>

            </thead>


            <tbody>

                @foreach (collect($calendar->days)->chunk(7) as $week)
                    <tr>

                        @foreach ($week as $day)
                            <td>

                                @if ($day->day)
                                    <div class="calendar-day
                                        @if ($day->today) today @endif
                                        @if ($day->weekDay == 0 || $day->weekDay == 6) holiday @endif"
                                        data-date="{{ sprintf('%04d-%02d-%02d', $day->year, $day->month, $day->day) }}">

                                        {{-- Day Number --}}
                                        <span class="day-number">
                                            {{ $day->day }}
                                        </span>


                                        {{-- Event Count Badge --}}
                                        <div class="day-event-count"></div>

                                    </div>
                                @endif

                            </td>
                        @endforeach

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>


    {{-- ============================================================
         Event Sidebar
    ============================================================= --}}

    <div class="event-sidebar">

        <h3>
            Events
        </h3>


        <div id="event-list">

            <div class="event-loading">
                Loading events...
            </div>

        </div>

    </div>

</div>


{{-- ================================================================
     CSS
================================================================= --}}

<style>
    /*
    |--------------------------------------------------------------------------
    | Calendar Wrapper
    |--------------------------------------------------------------------------
    */

    .calendar-wrapper {
        display: flex;
        gap: 20px;
        width: 100%;
    }


    /*
    |--------------------------------------------------------------------------
    | Calendar Container
    |--------------------------------------------------------------------------
    */

    .calendar-container {
        width: 80%;
    }


    /*
    |--------------------------------------------------------------------------
    | Calendar Header
    |--------------------------------------------------------------------------
    */

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }


    .calendar-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Navigation Buttons
    |--------------------------------------------------------------------------
    */

    .calendar-btn {
        background: #2563eb;
        color: white;
        padding: 8px 18px;
        border-radius: 6px;
        text-decoration: none;
    }


    .calendar-btn:hover {
        background: #1d4ed8;
        color: white;
        text-decoration: none;
    }


    /*
    |--------------------------------------------------------------------------
    | Calendar Table
    |--------------------------------------------------------------------------
    */

    .calendar-table {
        width: 100%;
        border-collapse: collapse;
    }


    .calendar-table th {
        background: #f1f5f9;
        padding: 14px;
        text-align: center;
    }


    .calendar-table td {
        height: 100px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: top;
        padding: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Calendar Day
    |--------------------------------------------------------------------------
    */

    .calendar-day {
        min-height: 100px;
        height: 100%;
        position: relative;
        padding: 10px;
        box-sizing: border-box;
    }


    /*
    |--------------------------------------------------------------------------
    | Day Number
    |--------------------------------------------------------------------------
    */

    .day-number {
        font-size: 26px;
        font-weight: 600;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 40px;
        height: 40px;

        margin: 0 auto;
    }


    /*
    |--------------------------------------------------------------------------
    | Today
    |--------------------------------------------------------------------------
    */

    .today .day-number {
        background: #2563eb;
        color: white;

        width: 45px;
        height: 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;
    }


    /*
    |--------------------------------------------------------------------------
    | Holiday
    |--------------------------------------------------------------------------
    */

    .holiday {
        color: red;
    }


    /*
    |--------------------------------------------------------------------------
    | Event Count Badge
    |--------------------------------------------------------------------------
    |
    | Hidden by default.
    |
    | JavaScript will change this to inline-flex only when
    | the date actually has one or more events.
    |
    */

    .day-event-count {
        display: none;

        align-items: center;
        justify-content: center;

        min-width: 22px;
        height: 22px;

        padding: 0 7px;

        margin: 5px auto 0;

        background: #16a34a;
        color: white;

        font-size: 11px;
        font-weight: 600;

        border-radius: 12px;

        line-height: 1;
    }


    /*
    |--------------------------------------------------------------------------
    | Event Sidebar
    |--------------------------------------------------------------------------
    */

    .event-sidebar {
        width: 20%;

        background: #f8fafc;

        padding: 15px;

        border-radius: 8px;

        box-sizing: border-box;
    }


    .event-sidebar h3 {
        margin-top: 0;
        margin-bottom: 15px;

        font-size: 22px;
    }


    /*
    |--------------------------------------------------------------------------
    | Event Card
    |--------------------------------------------------------------------------
    */

    .event-card {
        background: white;

        border-left: 4px solid #16a34a;

        padding: 10px;

        margin-bottom: 10px;

        border-radius: 6px;

        box-shadow: 0 1px 3px #ddd;
    }


    .event-title {
        font-weight: 600;
        margin-bottom: 4px;
    }


    .event-date {
        font-size: 13px;
        color: #64748b;
    }


    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    */

    .event-loading {
        color: #64748b;
        font-size: 14px;
    }


    /*
    |--------------------------------------------------------------------------
    | No Events
    |--------------------------------------------------------------------------
    */

    .no-events {
        color: #64748b;
        font-size: 14px;
    }


    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    */

    .event-error {
        color: #dc2626;
        font-size: 14px;
    }


    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 768px) {

        .calendar-wrapper {
            flex-direction: column;
        }


        .calendar-container {
            width: 100%;
        }


        .event-sidebar {
            width: 100%;
        }


        .calendar-header h2 {
            font-size: 20px;
        }


        .calendar-btn {
            padding: 6px 10px;
            font-size: 13px;
        }


        .calendar-table th {
            padding: 8px 4px;
            font-size: 13px;
        }


        .calendar-table td {
            height: 70px;
        }


        .calendar-day {
            min-height: 70px;
            padding: 5px;
        }


        .day-number {
            font-size: 18px;

            width: 30px;
            height: 30px;
        }


        .today .day-number {
            width: 32px;
            height: 32px;
        }


        .day-event-count {
            min-width: 20px;
            height: 20px;

            font-size: 10px;

            padding: 0 6px;
        }

    }
</style>


{{-- ================================================================
     JavaScript
================================================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /*
        |--------------------------------------------------------------------------
        | Calendar
        |--------------------------------------------------------------------------
        */

        const calendar =
            document.getElementById('event-calendar');


        if (!calendar) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Event URL
        |--------------------------------------------------------------------------
        */

        const eventUrl =
            calendar.dataset.eventUrl;


        console.log('Event URL:', eventUrl);


        if (!eventUrl) {

            console.error(
                'Event URL is missing.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Event List
        |--------------------------------------------------------------------------
        */

        const eventList =
            document.getElementById('event-list');


        /*
        |--------------------------------------------------------------------------
        | Fetch Events
        |--------------------------------------------------------------------------
        */

        fetch(eventUrl, {

                method: 'GET',

                headers: {
                    'Accept': 'application/json'
                }

            })

            .then(function(response) {

                if (!response.ok) {

                    throw new Error(
                        'HTTP error: ' + response.status
                    );

                }

                return response.json();

            })

            .then(function(events) {

                console.log('Events:', events);


                /*
                |--------------------------------------------------------------------------
                | Validate Response
                |--------------------------------------------------------------------------
                */

                if (!Array.isArray(events)) {

                    console.error(
                        'Event API must return an array.',
                        events
                    );


                    eventList.innerHTML = `
                <div class="event-error">
                    Invalid event response.
                </div>
            `;


                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Clear Loading
                |--------------------------------------------------------------------------
                */

                eventList.innerHTML = '';


                /*
                |--------------------------------------------------------------------------
                | No Events
                |--------------------------------------------------------------------------
                */

                if (events.length === 0) {

                    eventList.innerHTML = `
                <div class="no-events">
                    No events found.
                </div>
            `;


                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Display Events in Sidebar
                |--------------------------------------------------------------------------
                */

                events.forEach(function(event) {

                    const card =
                        document.createElement('div');


                    card.className =
                        'event-card';


                    /*
                    |--------------------------------------------------------------------------
                    | Event Title
                    |--------------------------------------------------------------------------
                    */

                    const title =
                        document.createElement('div');


                    title.className =
                        'event-title';


                    title.textContent =
                        event.title ?? 'Untitled Event';


                    /*
                    |--------------------------------------------------------------------------
                    | Event Date
                    |--------------------------------------------------------------------------
                    */

                    const date =
                        document.createElement('div');


                    date.className =
                        'event-date';


                    date.textContent =
                        event.date ?? '';


                    /*
                    |--------------------------------------------------------------------------
                    | Add To Card
                    |--------------------------------------------------------------------------
                    */

                    card.appendChild(title);

                    card.appendChild(date);


                    /*
                    |--------------------------------------------------------------------------
                    | Add Card To Sidebar
                    |--------------------------------------------------------------------------
                    */

                    eventList.appendChild(card);

                });


                /*
                |--------------------------------------------------------------------------
                | Group Events By Date
                |--------------------------------------------------------------------------
                |
                | Example:
                |
                | 2083-04-15 => [event1, event2, event3]
                |
                */

                const eventsByDate = {};


                events.forEach(function(event) {

                    /*
                    |--------------------------------------------------------------------------
                    | Ignore Events Without Date
                    |--------------------------------------------------------------------------
                    */

                    if (!event.date) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create Date Group
                    |--------------------------------------------------------------------------
                    */

                    if (!eventsByDate[event.date]) {

                        eventsByDate[event.date] = [];

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Add Event To Date
                    |--------------------------------------------------------------------------
                    */

                    eventsByDate[event.date].push(event);

                });


                /*
                |--------------------------------------------------------------------------
                | Display Event Count On Calendar
                |--------------------------------------------------------------------------
                */

                Object.keys(eventsByDate).forEach(function(date) {

                    /*
                    |--------------------------------------------------------------------------
                    | Find Calendar Day
                    |--------------------------------------------------------------------------
                    */

                    const day =
                        calendar.querySelector(
                            '.calendar-day[data-date="' +
                            date +
                            '"]'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Date Not In Current Month
                    |--------------------------------------------------------------------------
                    */

                    if (!day) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Count Events
                    |--------------------------------------------------------------------------
                    */

                    const eventCount =
                        eventsByDate[date].length;


                    /*
                    |--------------------------------------------------------------------------
                    | Don't Show Empty Badge
                    |--------------------------------------------------------------------------
                    */

                    if (eventCount <= 0) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find Badge
                    |--------------------------------------------------------------------------
                    */

                    const countElement =
                        day.querySelector(
                            '.day-event-count'
                        );


                    if (!countElement) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Set Count
                    |--------------------------------------------------------------------------
                    */

                    countElement.textContent =
                        eventCount +
                        (
                            eventCount === 1 ?
                            ' event' :
                            ' events'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Show Badge
                    |--------------------------------------------------------------------------
                    */

                    countElement.style.display =
                        'inline-flex';

                });

            })


            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            .catch(function(error) {

                console.error(
                    'Failed to load events:',
                    error
                );


                eventList.innerHTML = `
            <div class="event-error">
                Failed to load events.
            </div>
        `;

            });

    });
</script>
