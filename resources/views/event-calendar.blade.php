<div class="calendar-wrapper" id="event-calendar" data-event-url="{{ $eventUrl }}">

    {{-- ============================================================
         Calendar
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

            <a class="calendar-btn" href="{{ request()->url() }}?year={{ $previousYear }}&month={{ $previousMonth }}">
                ‹
            </a>


            {{-- Current Month --}}

            <div class="calendar-selector"> <select id="calendar-year" class="calendar-select">
                    @for ($year = $calendar->year - 10; $year <= $calendar->year + 10; $year++)
                        <option value="{{ $year }}" {{ $year == $calendar->year ? 'selected' : '' }}>
                            {{ toNepaliNumber($year) }} </option>
                    @endfor
                </select> <select id="calendar-month" class="calendar-select">
                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}" {{ $month == $calendar->month ? 'selected' : '' }}>
                            {{ nepali_month($month) }} </option>
                    @endfor
                </select> </div>


            {{-- Next --}}

            <a class="calendar-btn" href="{{ request()->url() }}?year={{ $nextYear }}&month={{ $nextMonth }}">
                ›
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
                                            {{ toNepaliNumber($day->day) }}
                                        </span>


                                        {{-- Event Count --}}

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
            कार्यक्रमहरु
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
    | Wrapper
    |--------------------------------------------------------------------------
    */

    .calendar-wrapper {
        display: flex;
        gap: 20px;
        width: 100%;
    }


    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */

    .calendar-container {
        width: 70%;
    }


    /*
    |--------------------------------------------------------------------------
    | Header
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
    | Buttons
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
    | Table
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
    | Day
    |--------------------------------------------------------------------------
    */

    .calendar-day {
        cursor: pointer;
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
    | JavaScript shows it only when the date has events.
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
    | Sidebar
    |--------------------------------------------------------------------------
    */

    /* .event-sidebar {
        width: 30%;

        background: #f8fafc;

        padding: 15px;

        border-radius: 8px;

        box-sizing: border-box;
    }


    .event-sidebar h3 {
        margin-top: 0;

        margin-bottom: 15px;

        font-size: 22px;
    } */
    /*
|--------------------------------------------------------------------------
| Sidebar
|--------------------------------------------------------------------------
*/

    .event-sidebar {
        width: 30%;

        background: #f8fafc;

        padding: 15px;

        border-radius: 8px;

        box-sizing: border-box;

        /*
    | Make sidebar height match calendar
    */
        display: flex;
        flex-direction: column;

        /*
    | Important:
    | Prevent flex child from forcing the sidebar larger.
    */
        min-height: 0;
    }


    /*
|--------------------------------------------------------------------------
| Sidebar Heading
|--------------------------------------------------------------------------
*/

    .event-sidebar h3 {
        margin-top: 0;
        margin-bottom: 15px;

        font-size: 22px;

        flex-shrink: 0;
    }


    /*
|--------------------------------------------------------------------------
| Event List
|--------------------------------------------------------------------------
|
| Only this area scrolls.
|
*/

    #event-list {
        overflow-y: auto;

        flex: 1;

        min-height: 0;

        padding-right: 5px;
    }


    /*
|--------------------------------------------------------------------------
| Optional Scrollbar
|--------------------------------------------------------------------------
*/

    #event-list::-webkit-scrollbar {
        width: 6px;
    }

    #event-list::-webkit-scrollbar-track {
        background: #e5e7eb;

        border-radius: 10px;
    }

    #event-list::-webkit-scrollbar-thumb {
        background: #94a3b8;

        border-radius: 10px;
    }

    #event-list::-webkit-scrollbar-thumb:hover {
        background: #64748b;
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

    .calendar-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;

        background-color: #ffffff;

        /* border: 1px solid #d1d5db; */
        border: none;
        border-radius: 6px;

        padding: 9px 38px 9px 12px;

        font-size: 16px;
        font-weight: 600;
        line-height: 1.4;

        color: #1f2937;

        cursor: pointer;
        outline: none;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background-color 0.2s ease;

        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");

        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
    }

    .calendar-select:hover {
        border-color: #2563eb;
        background-color: #f8fafc;
    }

    .calendar-select:focus {
        border-color: #2563eb;

        box-shadow:
            0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* Year */
    #calendar-year {
        min-width: 95px;
    }

    /* Month */
    #calendar-month {
        min-width: 145px;
    }


    /* ============================================================
   Mobile
   ============================================================ */

    @media (max-width: 768px) {

        .calendar-selector {
            gap: 5px;
        }

        .calendar-select {
            padding: 7px 30px 7px 9px;
            font-size: 14px;
            line-height: 1.4;

            background-position: right 8px center;
        }

        #calendar-year {
            min-width: 75px;
        }

        #calendar-month {
            min-width: 115px;
        }
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
        | Elements
        |--------------------------------------------------------------------------
        */

        const yearSelect = document.getElementById('calendar-year');
        const monthSelect = document.getElementById('calendar-month');
        const calendar = document.getElementById('event-calendar');
        const eventList = document.getElementById('event-list');


        if (!calendar) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | URL Parameters
        |--------------------------------------------------------------------------
        */

        const params = new URLSearchParams(window.location.search);

        const yearParam = params.get('year');
        const monthParam = params.get('month');
        const dayParam = params.get('day');

        const year = yearParam ?
            Number(yearParam) :
            null;

        const month = monthParam ?
            Number(monthParam) :
            null;

        const day = dayParam ?
            Number(dayParam) :
            null;


        // console.log('URL parameters:', {
        //     year,
        //     month,
        //     day
        // });


        /*
        |--------------------------------------------------------------------------
        | Change Year / Month
        |--------------------------------------------------------------------------
        |
        | When year or month changes:
        |
        | ?year=2083&month=05
        |
        | The selected day is removed.
        |
        */

        function changeCalendar() {

            if (!yearSelect || !monthSelect) {
                return;
            }

            const selectedYear =
                Number(yearSelect.value);

            const selectedMonth =
                Number(monthSelect.value);


            if (!selectedYear || !selectedMonth) {
                return;
            }


            const url =
                new URL(window.location.href);


            /*
            | Remove all query parameters
            */

            url.search = '';


            /*
            | Add year
            */

            url.searchParams.set(
                'year',
                selectedYear
            );


            /*
            | Add month
            */

            url.searchParams.set(
                'month',
                String(selectedMonth).padStart(2, '0')
            );


            /*
            | Do NOT add day
            */


            window.location.href =
                url.toString();
        }


        /*
        |--------------------------------------------------------------------------
        | Year Change
        |--------------------------------------------------------------------------
        */

        if (yearSelect) {

            yearSelect.addEventListener(
                'change',
                changeCalendar
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Month Change
        |--------------------------------------------------------------------------
        */

        if (monthSelect) {

            monthSelect.addEventListener(
                'change',
                changeCalendar
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Day Click
        |--------------------------------------------------------------------------
        */

        calendar
            .querySelectorAll('.calendar-day')
            .forEach(function(dayElement) {

                dayElement.addEventListener(
                    'click',
                    function() {

                        const date =
                            this.dataset.date;


                        if (!date) {
                            return;
                        }


                        const parts =
                            date.split('-');


                        if (parts.length !== 3) {
                            return;
                        }


                        const selectedYear =
                            Number(parts[0]);

                        const selectedMonth =
                            Number(parts[1]);

                        const selectedDay =
                            Number(parts[2]);


                        /*
                        |--------------------------------------------------------------------------
                        | Create URL
                        |--------------------------------------------------------------------------
                        */

                        const url =
                            new URL(window.location.href);


                        /*
                        | Remove old parameters
                        */

                        url.search = '';


                        /*
                        | Set selected date
                        */

                        url.searchParams.set(
                            'year',
                            selectedYear
                        );

                        url.searchParams.set(
                            'month',
                            String(selectedMonth).padStart(2, '0')
                        );

                        url.searchParams.set(
                            'day',
                            String(selectedDay).padStart(2, '0')
                        );


                        /*
                        | Redirect
                        */

                        window.location.href =
                            url.toString();

                    }
                );

            });


        /*
        |--------------------------------------------------------------------------
        | Event API URL
        |--------------------------------------------------------------------------
        */

        const eventUrl =
            calendar.dataset.eventUrl;


        if (!eventUrl) {

            console.error(
                'Event URL is missing.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Monthly API URL
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | This request NEVER contains "day".
        |
        | It always gets ALL events for the month.
        |
        */

        const monthlyEventUrl =
            new URL(
                eventUrl,
                window.location.origin
            );


        monthlyEventUrl.searchParams.set(
            'year',
            year
        );


        monthlyEventUrl.searchParams.set(
            'month',
            month
        );


        /*
        |--------------------------------------------------------------------------
        | Create Sidebar API URL
        |--------------------------------------------------------------------------
        |
        | If day exists:
        |
        | ?year=2083&month=05&day=25
        |
        | Otherwise:
        |
        | ?year=2083&month=05
        |
        */

        const sidebarEventUrl =
            new URL(
                eventUrl,
                window.location.origin
            );


        sidebarEventUrl.searchParams.set(
            'year',
            year
        );


        sidebarEventUrl.searchParams.set(
            'month',
            month
        );


        if (day) {

            sidebarEventUrl.searchParams.set(
                'day',
                day
            );

        }


        // console.log(
        //     'Monthly API:',
        //     monthlyEventUrl.toString()
        // );


        // console.log(
        //     'Sidebar API:',
        //     sidebarEventUrl.toString()
        // );


        /*
        |--------------------------------------------------------------------------
        | Fetch Monthly Events
        |--------------------------------------------------------------------------
        |
        | This request is ONLY used for:
        |
        | - Event counts
        | - Calendar markers
        |
        */

        function fetchMonthlyEvents() {

            return fetch(
                    monthlyEventUrl.toString(), {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                )

                .then(function(response) {

                    if (!response.ok) {

                        throw new Error(
                            'Monthly API HTTP error: ' +
                            response.status
                        );

                    }

                    return response.json();

                })

                .then(function(events) {

                    // console.log(
                    //     'Monthly events:',
                    //     events
                    // );


                    if (!Array.isArray(events)) {

                        throw new Error(
                            'Monthly event API must return an array.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Group Events By Date
                    |--------------------------------------------------------------------------
                    */

                    const eventsByDate =
                        groupEventsByDate(events);


                    /*
                    |--------------------------------------------------------------------------
                    | Show Event Counts
                    |--------------------------------------------------------------------------
                    */

                    showEventCounts(eventsByDate);

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Fetch Sidebar Events
        |--------------------------------------------------------------------------
        |
        | This request is ONLY used for the sidebar.
        |
        */

        function fetchSidebarEvents() {

            if (!eventList) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Loading
            |--------------------------------------------------------------------------
            */

            eventList.innerHTML = `
            <div class="event-loading">
                Loading events...
            </div>
        `;


            fetch(
                    sidebarEventUrl.toString(), {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                )

                .then(function(response) {

                    if (!response.ok) {

                        throw new Error(
                            'Sidebar API HTTP error: ' +
                            response.status
                        );

                    }

                    return response.json();

                })

                .then(function(events) {

                    // console.log(
                    //     'Sidebar events:',
                    //     events
                    // );


                    if (!Array.isArray(events)) {

                        throw new Error(
                            'Sidebar event API must return an array.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Clear
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
                    | Render Sidebar
                    |--------------------------------------------------------------------------
                    */

                    renderEventList(events);

                })

                .catch(function(error) {

                    console.error(
                        'Failed to load sidebar events:',
                        error
                    );


                    eventList.innerHTML = `
                <div class="event-error">
                    Failed to load events.
                </div>
            `;

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Group Events By Date
        |--------------------------------------------------------------------------
        */

        function groupEventsByDate(events) {

            const eventsByDate = {};


            events.forEach(function(event) {

                if (!event.date) {
                    return;
                }


                /*
                | Normalize date
                |
                | 2083-05-07 stays 2083-05-07
                */

                const date =
                    String(event.date);


                if (!eventsByDate[date]) {

                    eventsByDate[date] = [];

                }


                eventsByDate[date].push(event);

            });


            return eventsByDate;
        }


        /*
        |--------------------------------------------------------------------------
        | Show Event Counts On Calendar
        |--------------------------------------------------------------------------
        */

        function showEventCounts(eventsByDate) {

            Object.keys(eventsByDate)
                .forEach(function(date) {

                    /*
                    |--------------------------------------------------------------------------
                    | Find calendar day
                    |--------------------------------------------------------------------------
                    */

                    const dayElement =
                        Array.from(
                            calendar.querySelectorAll(
                                '.calendar-day'
                            )
                        )
                        .find(function(element) {

                            /*
                            | Compare normalized dates
                            */

                            return normalizeDate(
                                element.dataset.date
                            ) === normalizeDate(date);

                        });


                    if (!dayElement) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Count
                    |--------------------------------------------------------------------------
                    */

                    const eventCount =
                        eventsByDate[date].length;


                    /*
                    |--------------------------------------------------------------------------
                    | Count Element
                    |--------------------------------------------------------------------------
                    */

                    const countElement =
                        dayElement.querySelector(
                            '.day-event-count'
                        );


                    if (!countElement) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Display
                    |--------------------------------------------------------------------------
                    */

                    countElement.textContent =
                        eventCount === 1 ?
                        '1 event' :
                        eventCount + ' events';


                    countElement.style.display =
                        'inline-flex';

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Normalize Date
        |--------------------------------------------------------------------------
        |
        | Makes:
        |
        | 2083-05-07
        | 2083-5-7
        |
        | equivalent.
        |
        */

        function normalizeDate(date) {

            if (!date) {
                return '';
            }


            const parts =
                String(date).split('-');


            if (parts.length !== 3) {
                return String(date);
            }


            const eventYear =
                Number(parts[0]);

            const eventMonth =
                Number(parts[1]);

            const eventDay =
                Number(parts[2]);


            return (
                eventYear +
                '-' +
                String(eventMonth).padStart(2, '0') +
                '-' +
                String(eventDay).padStart(2, '0')
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Render Sidebar Event List
        |--------------------------------------------------------------------------
        */

        function renderEventList(events) {

            if (!eventList) {
                return;
            }


            events.forEach(function(event) {

                /*
                |--------------------------------------------------------------------------
                | Card
                |--------------------------------------------------------------------------
                */

                const card =
                    document.createElement('div');

                card.className =
                    'event-card';


                /*
                |--------------------------------------------------------------------------
                | Title
                |--------------------------------------------------------------------------
                */

                const title =
                    document.createElement('div');

                title.className =
                    'event-title';

                title.textContent =
                    event.title ||
                    'Untitled Event';


                /*
                |--------------------------------------------------------------------------
                | Date
                |--------------------------------------------------------------------------
                */

                const date =
                    document.createElement('div');

                date.className =
                    'event-date';

                date.textContent =
                    event.date || '';


                /*
                |--------------------------------------------------------------------------
                | Append
                |--------------------------------------------------------------------------
                */

                card.appendChild(title);

                card.appendChild(date);

                eventList.appendChild(card);

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Show Error
        |--------------------------------------------------------------------------
        */

        function showError(message) {

            if (!eventList) {
                return;
            }


            eventList.innerHTML = `
            <div class="event-error">
                ${message}
            </div>
        `;

        }


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        */

        /*
        | 1. Always fetch the complete month
        |    for calendar event counts.
        */

        fetchMonthlyEvents()
            .catch(function(error) {

                console.error(
                    'Failed to load monthly events:',
                    error
                );

            });


        /*
        | 2. Fetch sidebar events
        |
        | If day exists → selected day's events
        | If day doesn't exist → month's events
        */

        fetchSidebarEvents();



        // sidebar events

        /*
|--------------------------------------------------------------------------
| Match Sidebar Height With Calendar
|--------------------------------------------------------------------------
*/

        function matchSidebarHeight() {

            const calendarContainer =
                document.querySelector('.calendar-container');

            const sidebar =
                document.querySelector('.event-sidebar');

            if (!calendarContainer || !sidebar) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Mobile
            |--------------------------------------------------------------------------
            */

            if (window.innerWidth <= 768) {

                sidebar.style.height = '';

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Desktop
            |--------------------------------------------------------------------------
            */

            sidebar.style.height =
                calendarContainer.offsetHeight + 'px';

        }


        /*
        |--------------------------------------------------------------------------
        | Initial
        |--------------------------------------------------------------------------
        */

        matchSidebarHeight();


        /*
        |--------------------------------------------------------------------------
        | Resize
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            matchSidebarHeight
        );

    });
</script>
