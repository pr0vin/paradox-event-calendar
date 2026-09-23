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
            <div class="calendar-selector">

                <select id="calendar-year" class="calendar-select">

                    @for ($year = $calendar->year - 10; $year <= $calendar->year + 10; $year++)
                        <option value="{{ $year }}" {{ $year == $calendar->year ? 'selected' : '' }}>
                            {{ toNepaliNumber($year) }}
                        </option>
                    @endfor

                </select>

                <select id="calendar-month" class="calendar-select">

                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}" {{ $month == $calendar->month ? 'selected' : '' }}>
                            {{ nepali_month($month) }}
                        </option>
                    @endfor

                </select>

            </div>

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
                                    <div class="
                                            calendar-day
                                            @if ($day->today) today @endif
                                            @if ($day->weekDay == 0 || $day->weekDay == 6) holiday @endif
                                        "
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

        <div style="display:flex; justify-content:space-between">
            <h3>
                कार्यक्रमहरु
            </h3>
            {{-- ========================================================
             Calendar Legend
        ========================================================= --}}

            <div class="calendar-legend">

                <div class="legend-item">
                    <span class="legend-dot meeting"></span>
                    <span>बैठक</span>
                </div>

                <div class="legend-item">
                    <span class="legend-dot event"></span>
                    <span>कार्यक्रम</span>
                </div>

                <div class="legend-item">
                    <span class="legend-dot holiday"></span>
                    <span>बिदा</span>
                </div>

                <div class="legend-item">
                    <span class="legend-dot todo"></span>
                    <span>To-do</span>
                </div>

            </div>
        </div>


        {{-- ========================================================
             Event Filters
        ========================================================= --}}

        <div class="event-filters">

            <button type="button" class="event-filter active" data-filter="all">
                सबै
            </button>

            <button type="button" class="event-filter" data-filter="meeting">
                बैठक
            </button>

            <button type="button" class="event-filter" data-filter="event">
                कार्यक्रम
            </button>

            <button type="button" class="event-filter" data-filter="holiday">
                बिदा
            </button>

            <button type="button" class="event-filter" data-filter="todo">
                To-do
            </button>

        </div>


        {{-- ========================================================
             Event List
        ========================================================= --}}

        <div id="event-list">

            <div class="event-loading">
                Loading events...
            </div>

        </div>

    </div>

</div>


{{-- ================================================================
     CSS
================================================================ --}}

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
        margin-bottom: 15px;
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
        transition: background 0.2s ease;
    }

    .calendar-btn:hover {
        background: #1d4ed8;
        color: white;
        text-decoration: none;
    }


    /*
    |--------------------------------------------------------------------------
    | Selector
    |--------------------------------------------------------------------------
    */

    .calendar-selector {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .calendar-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;

        background-color: #ffffff;

        border: none;
        border-radius: 6px;

        padding: 9px 38px 9px 12px;

        font-size: 16px;
        font-weight: 600;
        line-height: 1.4;

        color: #1f2937;

        cursor: pointer;
        outline: none;

        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");

        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
    }

    .calendar-select:hover {
        background-color: #f8fafc;
    }

    .calendar-select:focus {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    #calendar-year {
        min-width: 95px;
    }

    #calendar-month {
        min-width: 145px;
    }


    /*
    |--------------------------------------------------------------------------
    | Legend
    |--------------------------------------------------------------------------
    */

    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;

        font-size: 13px;
        color: #475569;
    }

    .legend-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-dot.meeting {
        background: #2563eb;
    }

    .legend-dot.event {
        background: #16a34a;
    }

    .legend-dot.holiday {
        background: #dc2626;
    }

    .legend-dot.todo {
        background: #9333ea;
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

        transition: background 0.15s ease;
    }

    .calendar-day:hover {
        background: #f8fafc;
    }


    /*
    |--------------------------------------------------------------------------
    | Selected Day
    |--------------------------------------------------------------------------
    */

    .calendar-day.selected {
        background: #eff6ff;
        box-shadow: inset 0 0 0 2px #2563eb;
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

    .calendar-day.is-holiday .day-number {
        color: #dc2626;
        font-weight: 700;
    }

    .calendar-day.is-holiday.today .day-number {
        color: #dc2626;
        background: #fee2e2;
    }


    /*
    |--------------------------------------------------------------------------
    | Weekend / Holiday Day
    |--------------------------------------------------------------------------
    */

    .holiday {
        color: #dc2626;
    }


    /*
    |--------------------------------------------------------------------------
    | Event Count
    |--------------------------------------------------------------------------
    */

    .day-event-count {
        display: none;

        align-items: center;
        justify-content: center;

        min-width: 22px;
        height: 22px;

        padding: 0 7px;

        margin: 5px auto 0;

        background: #dcfce7;
        color: #16a34a;

        font-size: 10px;
        font-weight: 600;

        border-radius: 12px;

        line-height: 1;
    }


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

        display: flex;
        flex-direction: column;

        min-height: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Sidebar Heading
    |--------------------------------------------------------------------------
    */

    .event-sidebar h3 {
        margin-top: 0;
        margin-bottom: 12px;

        font-size: 22px;

        flex-shrink: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    .event-filters {
        display: flex;

        flex-wrap: wrap;

        gap: 6px;

        margin-bottom: 12px;

        flex-shrink: 0;
    }

    .event-filter {
        border: 1px solid #cbd5e1;

        background: white;

        color: #475569;

        padding: 6px 10px;

        border-radius: 5px;

        font-size: 12px;

        cursor: pointer;

        transition:
            background 0.15s ease,
            color 0.15s ease,
            border-color 0.15s ease;
    }

    .event-filter:hover {
        background: #f1f5f9;
    }

    .event-filter.active {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }


    /*
    |--------------------------------------------------------------------------
    | Event List
    |--------------------------------------------------------------------------
    */

    #event-list {
        overflow-y: auto;

        flex: 1;

        min-height: 0;

        padding-right: 5px;
    }


    /*
    |--------------------------------------------------------------------------
    | Scrollbar
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


    /*
    |--------------------------------------------------------------------------
    | Event Type Colors
    |--------------------------------------------------------------------------
    */

    .event-card.type-meeting {
        border-left-color: #2563eb;
    }

    .event-card.type-event {
        border-left-color: #16a34a;
    }

    .event-card.type-holiday {
        border-left-color: #dc2626;
    }

    .event-card.type-todo {
        border-left-color: #9333ea;
    }


    /*
    |--------------------------------------------------------------------------
    | Event Title
    |--------------------------------------------------------------------------
    */

    .event-title {
        font-weight: 600;

        margin-bottom: 7px;

        color: #1e293b;

        line-height: 1.4;
    }


    /*
    |--------------------------------------------------------------------------
    | Event Meta
    |--------------------------------------------------------------------------
    */

    .event-meta {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 8px;

        flex-wrap: wrap;
    }


    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    .event-date {
        font-size: 13px;

        color: #64748b;
    }


    /*
    |--------------------------------------------------------------------------
    | Type Badge
    |--------------------------------------------------------------------------
    */

    .event-type {
        display: inline-flex;

        align-items: center;

        padding: 3px 7px;

        border-radius: 4px;

        font-size: 11px;

        font-weight: 600;

        white-space: nowrap;
    }


    .event-type.meeting {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .event-type.event {
        background: #dcfce7;
        color: #15803d;
    }

    .event-type.holiday {
        background: #fee2e2;
        color: #b91c1c;
    }

    .event-type.todo {
        background: #f3e8ff;
        color: #7e22ce;
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

        padding: 10px 0;
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

            height: auto !important;
        }

        .calendar-header {
            margin-bottom: 12px;
        }

        .calendar-select {
            padding: 7px 30px 7px 9px;

            font-size: 14px;

            background-position: right 8px center;
        }

        #calendar-year {
            min-width: 75px;
        }

        #calendar-month {
            min-width: 115px;
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

        .calendar-legend {
            gap: 10px;
        }

        .legend-item {
            font-size: 12px;
        }

    }
</style>


{{-- ================================================================
     JavaScript
================================================================ --}}

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

        const filterButtons =
            document.querySelectorAll('.event-filter');


        if (!calendar) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | URL Parameters
        |--------------------------------------------------------------------------
        */

        const params =
            new URLSearchParams(window.location.search);

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


        /*
        |--------------------------------------------------------------------------
        | Current Filter
        |--------------------------------------------------------------------------
        |
        | Possible values:
        |
        | all
        | meeting
        | event
        | holiday
        | todo
        |
        */

        let activeFilter = 'all';


        /*
        |--------------------------------------------------------------------------
        | Event Type Configuration
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | If API does not provide "type",
        | normalizeEvent() automatically uses "event".
        |
        */

        const eventTypes = {

            meeting: {
                label: 'बैठक',
                className: 'meeting'
            },

            event: {
                label: 'कार्यक्रम',
                className: 'event'
            },

            holiday: {
                label: 'बिदा',
                className: 'holiday'
            },

            todo: {
                label: 'To-do',
                className: 'todo'
            }

        };


        /*
        |--------------------------------------------------------------------------
        | Event Data
        |--------------------------------------------------------------------------
        */

        let monthlyEvents = [];

        let sidebarEvents = [];


        /*
        |--------------------------------------------------------------------------
        | Change Year / Month
        |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | Remove all query parameters
            |--------------------------------------------------------------------------
            */

            url.search = '';

            /*
            |--------------------------------------------------------------------------
            | Add year
            |--------------------------------------------------------------------------
            */

            url.searchParams.set(
                'year',
                selectedYear
            );

            /*
            |--------------------------------------------------------------------------
            | Add month
            |--------------------------------------------------------------------------
            */

            url.searchParams.set(
                'month',
                String(selectedMonth).padStart(2, '0')
            );

            /*
            |--------------------------------------------------------------------------
            | Do NOT add day
            |--------------------------------------------------------------------------
            */

            window.location.href =
                url.toString();
        }


        if (yearSelect) {

            yearSelect.addEventListener(
                'change',
                changeCalendar
            );

        }


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

                        const url =
                            new URL(window.location.href);

                        /*
                        |--------------------------------------------------------------------------
                        | Remove old parameters
                        |--------------------------------------------------------------------------
                        */

                        url.search = '';

                        /*
                        |--------------------------------------------------------------------------
                        | Set selected date
                        |--------------------------------------------------------------------------
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

                        window.location.href =
                            url.toString();

                    }
                );

            });


        /*
        |--------------------------------------------------------------------------
        | Highlight Selected Day
        |--------------------------------------------------------------------------
        */

        if (day) {

            calendar
                .querySelectorAll('.calendar-day')
                .forEach(function(dayElement) {

                    const date =
                        dayElement.dataset.date;

                    if (!date) {
                        return;
                    }

                    const parts =
                        date.split('-');

                    const elementDay =
                        Number(parts[2]);

                    if (elementDay === day) {

                        dayElement.classList.add(
                            'selected'
                        );

                    }

                });

        }


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
        | Monthly API URL
        |--------------------------------------------------------------------------
        |
        | This request ALWAYS gets the complete month.
        |
        | Used for:
        |
        | - Calendar counts
        | - Calendar filtering
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
        | Sidebar API URL
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


        /*
        |--------------------------------------------------------------------------
        | Normalize Event
        |--------------------------------------------------------------------------
        |
        | If API returns:
        |
        | {
        |     title: "Meeting"
        | }
        |
        | it automatically becomes:
        |
        | type: "event"
        |
        */

        function normalizeEvent(event) {

            if (!event || typeof event !== 'object') {
                return null;
            }

            let type =
                String(event.type || 'event')
                .trim()
                .toLowerCase();

            /*
            |--------------------------------------------------------------------------
            | Unknown type
            |--------------------------------------------------------------------------
            |
            | If an API sends something unexpected,
            | fall back to event.
            |
            */

            if (!eventTypes[type]) {
                type = 'event';
            }

            return {
                ...event,
                type: type,

                title: event.title ||
                    event.name ||
                    'Untitled Event',

                date: normalizeDate(event.date)
            };

        }


        /*
        |--------------------------------------------------------------------------
        | Normalize Event Collection
        |--------------------------------------------------------------------------
        */

        function normalizeEvents(events) {

            if (!Array.isArray(events)) {
                return [];
            }

            return events
                .map(normalizeEvent)
                .filter(function(event) {
                    return event !== null;
                });

        }


        /*
        |--------------------------------------------------------------------------
        | Fetch Monthly Events
        |--------------------------------------------------------------------------
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

                    monthlyEvents =
                        normalizeEvents(events);

                    /*
                    |--------------------------------------------------------------------------
                    | Render counts using current filter
                    |--------------------------------------------------------------------------
                    */

                    renderCalendarCounts();

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Fetch Sidebar Events
        |--------------------------------------------------------------------------
        */

        function fetchSidebarEvents() {

            if (!eventList) {
                return;
            }

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

                    sidebarEvents =
                        normalizeEvents(events);

                    renderSidebar();

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
        | Filter Events
        |--------------------------------------------------------------------------
        */

        function filterEvents(events) {

            if (activeFilter === 'all') {
                return events;
            }

            return events.filter(function(event) {

                return event.type === activeFilter;

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Filter Buttons
        |--------------------------------------------------------------------------
        */

        filterButtons.forEach(function(button) {

            button.addEventListener(
                'click',
                function() {

                    /*
                    |--------------------------------------------------------------------------
                    | Set active filter
                    |--------------------------------------------------------------------------
                    */

                    activeFilter =
                        this.dataset.filter || 'all';

                    /*
                    |--------------------------------------------------------------------------
                    | Update button state
                    |--------------------------------------------------------------------------
                    */

                    filterButtons.forEach(
                        function(item) {

                            item.classList.remove(
                                'active'
                            );

                        }
                    );

                    this.classList.add(
                        'active'
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Update calendar counts
                    |--------------------------------------------------------------------------
                    */

                    renderCalendarCounts();

                    /*
                    |--------------------------------------------------------------------------
                    | Update sidebar
                    |--------------------------------------------------------------------------
                    */

                    renderSidebar();

                }
            );

        });


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

                const date =
                    normalizeDate(event.date);

                if (!eventsByDate[date]) {

                    eventsByDate[date] = [];

                }

                eventsByDate[date].push(event);

            });

            return eventsByDate;

        }


        /*
        |--------------------------------------------------------------------------
        | Render Calendar Counts
        |--------------------------------------------------------------------------
        */

        function renderCalendarCounts() {

            /*
            |--------------------------------------------------------------------------
            | Clear existing counts AND holiday classes
            |--------------------------------------------------------------------------
            */

            calendar
                .querySelectorAll('.calendar-day')
                .forEach(function(dayElement) {

                    dayElement.classList.remove('is-holiday');

                });

            calendar
                .querySelectorAll('.day-event-count')
                .forEach(function(countElement) {

                    countElement.style.display = 'none';
                    countElement.textContent = '';

                });


            /*
            |--------------------------------------------------------------------------
            | Group ALL monthly events
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | We use monthlyEvents here, not filteredEvents,
            | because a holiday should remain red even when
            | another filter is selected.
            |
            */

            const allEventsByDate =
                groupEventsByDate(monthlyEvents);


            /*
            |--------------------------------------------------------------------------
            | Mark Holiday Dates
            |--------------------------------------------------------------------------
            */

            Object.keys(allEventsByDate)
                .forEach(function(date) {

                    const events =
                        allEventsByDate[date];


                    const isHoliday =
                        events.some(function(event) {

                            return event.type === 'holiday';

                        });


                    if (!isHoliday) {
                        return;
                    }


                    const dayElement =
                        Array.from(
                            calendar.querySelectorAll('.calendar-day')
                        )
                        .find(function(element) {

                            return normalizeDate(
                                element.dataset.date
                            ) === normalizeDate(date);

                        });


                    if (!dayElement) {
                        return;
                    }


                    dayElement.classList.add('is-holiday');

                });


            /*
            |--------------------------------------------------------------------------
            | Apply Current Filter For Counts
            |--------------------------------------------------------------------------
            */

            const filteredEvents =
                filterEvents(monthlyEvents);


            /*
            |--------------------------------------------------------------------------
            | Group Filtered Events
            |--------------------------------------------------------------------------
            */

            const eventsByDate =
                groupEventsByDate(filteredEvents);


            /*
            |--------------------------------------------------------------------------
            | Display Counts
            |--------------------------------------------------------------------------
            */

            Object.keys(eventsByDate)
                .forEach(function(date) {

                    const dayElement =
                        Array.from(
                            calendar.querySelectorAll('.calendar-day')
                        )
                        .find(function(element) {

                            return normalizeDate(
                                element.dataset.date
                            ) === normalizeDate(date);

                        });


                    if (!dayElement) {
                        return;
                    }


                    const eventCount =
                        eventsByDate[date].length;


                    const countElement =
                        dayElement.querySelector(
                            '.day-event-count'
                        );


                    if (!countElement) {
                        return;
                    }


                    countElement.textContent =
                        eventCount === 1 ?
                        '१ कार्यक्रम' :
                        toNepaliDigits(eventCount) + ' कार्यक्रम';


                    countElement.style.display =
                        'inline-flex';

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Render Sidebar
        |--------------------------------------------------------------------------
        */

        function renderSidebar() {

            if (!eventList) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Apply active filter
            |--------------------------------------------------------------------------
            */

            const filteredEvents =
                filterEvents(sidebarEvents);


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

            if (filteredEvents.length === 0) {

                let message = 'No events found.';

                if (activeFilter !== 'all') {

                    const typeConfig =
                        eventTypes[activeFilter];

                    if (typeConfig) {

                        message =
                            'No ' +
                            typeConfig.label +
                            ' found.';

                    }

                }

                eventList.innerHTML = `
                    <div class="no-events">
                        ${message}
                    </div>
                `;

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Render
            |--------------------------------------------------------------------------
            */

            filteredEvents.forEach(
                function(event) {

                    renderEventCard(event);

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Render Event Card
        |--------------------------------------------------------------------------
        */

        function renderEventCard(event) {

            const type =
                event.type || 'event';

            const typeConfig =
                eventTypes[type] ||
                eventTypes.event;


            /*
            |--------------------------------------------------------------------------
            | Card
            |--------------------------------------------------------------------------
            */

            const card =
                document.createElement('div');

            card.className =
                'event-card type-' +
                typeConfig.className;


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
            | Meta
            |--------------------------------------------------------------------------
            */

            const meta =
                document.createElement('div');

            meta.className =
                'event-meta';


            /*
            |--------------------------------------------------------------------------
            | Date
            |--------------------------------------------------------------------------
            */

            const date =
                document.createElement('span');

            date.className =
                'event-date';

            date.textContent =
                formatEventDate(event.date);


            /*
            |--------------------------------------------------------------------------
            | Type
            |--------------------------------------------------------------------------
            */

            const typeBadge =
                document.createElement('span');

            typeBadge.className =
                'event-type ' +
                typeConfig.className;

            typeBadge.textContent =
                typeConfig.label;


            /*
            |--------------------------------------------------------------------------
            | Append
            |--------------------------------------------------------------------------
            */

            meta.appendChild(date);

            meta.appendChild(typeBadge);

            card.appendChild(title);

            card.appendChild(meta);


            /*
            |--------------------------------------------------------------------------
            | Optional URL
            |--------------------------------------------------------------------------
            */

            if (event.url) {

                card.style.cursor = 'pointer';

                card.addEventListener(
                    'click',
                    function() {

                        window.location.href =
                            event.url;

                    }
                );

            }


            eventList.appendChild(card);

        }


        /*
        |--------------------------------------------------------------------------
        | Format Event Date
        |--------------------------------------------------------------------------
        |
        | Keeps the API date as:
        |
        | 2083-05-07
        |
        | and converts numbers to Nepali numbers.
        |
        */

        function formatEventDate(date) {

            if (!date) {
                return '';
            }

            const normalized =
                normalizeDate(date);

            const parts =
                normalized.split('-');

            if (parts.length !== 3) {
                return normalized;
            }

            const eventYear =
                parts[0];

            const eventMonth =
                parts[1];

            const eventDay =
                parts[2];


            return (
                toNepaliDigits(eventYear) +
                '-' +
                toNepaliDigits(eventMonth) +
                '-' +
                toNepaliDigits(eventDay)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Nepali Numbers
        |--------------------------------------------------------------------------
        */

        function toNepaliDigits(value) {

            const digits = [
                '०',
                '१',
                '२',
                '३',
                '४',
                '५',
                '६',
                '७',
                '८',
                '९'
            ];

            return String(value)
                .replace(
                    /\d/g,
                    function(digit) {

                        return digits[
                            Number(digit)
                        ];

                    }
                );

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
        | Match Sidebar Height With Calendar
        |--------------------------------------------------------------------------
        */

        function matchSidebarHeight() {

            const calendarContainer =
                document.querySelector(
                    '.calendar-container'
                );

            const sidebar =
                document.querySelector(
                    '.event-sidebar'
                );


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
                calendarContainer.offsetHeight +
                'px';

        }


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        */

        fetchMonthlyEvents()
            .catch(function(error) {

                console.error(
                    'Failed to load monthly events:',
                    error
                );

            });


        fetchSidebarEvents();


        /*
        |--------------------------------------------------------------------------
        | Sidebar Height
        |--------------------------------------------------------------------------
        */

        matchSidebarHeight();


        window.addEventListener(
            'resize',
            matchSidebarHeight
        );

    });
</script>
