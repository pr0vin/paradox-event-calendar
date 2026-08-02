<div class="nepali-calendar">

    <div class="calendar-header">

        <button class="calendar-btn">
            ‹ Previous
        </button>


        <h2>
            {{ nepali_month($calendar->month) }}
            {{ $calendar->year }}
        </h2>


        <button class="calendar-btn">
            Next ›
        </button>

    </div>


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
                                <div
                                    class="calendar-day

                                    @if ($day->today) today @endif

                                    @if ($day->weekDay == 0 || $day->weekDay == 6) holiday @endif
                                    ">

                                    <span class="day-number">
                                        {{ $day->day }}
                                    </span>


                                    @if (count($day->events))
                                        <div class="events">

                                            @foreach ($day->events as $event)
                                                <div class="event">
                                                    {{ $event->title }}
                                                </div>
                                            @endforeach

                                        </div>
                                    @endif


                                </div>
                            @endif

                        </td>
                    @endforeach

                </tr>
            @endforeach

        </tbody>


    </table>

</div>


<style>
    .nepali-calendar {
        width: 100%;
        background: white;
    }


    /* Header */

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


    /* Buttons */

    .calendar-btn {

        background: #2563eb;
        color: white;

        border: none;

        padding: 8px 18px;

        border-radius: 6px;

        font-size: 15px;

        cursor: pointer;

    }


    .calendar-btn:hover {

        opacity: 0.85;

    }


    /* Table */

    .calendar-table {

        width: 100%;

        border-collapse: collapse;

    }


    .calendar-table th {

        background: #f1f5f9;

        padding: 14px;

        text-align: center;

        font-size: 16px;

        font-weight: 600;

    }


    .calendar-table td {

        height: 100px;

        border: 1px solid #ddd;

        text-align: center;

        vertical-align: middle;

    }


    /* Day */

    .calendar-day {

        display: flex;

        justify-content: center;

        align-items: center;

        height: 100%;

        flex-direction: column;

    }


    .day-number {

        font-size: 26px;

        font-weight: 600;

    }


    /* Today */

    .calendar-day.today .day-number {

        background: #2563eb;

        color: white;

        width: 60px;
        height: 60px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

    }


    /* Saturday Sunday */

    .holiday {

        color: red !important;

    }


    /* Events */

    .events {

        margin-top: 8px;

    }


    .event {

        background: #16a34a;

        color: white;

        font-size: 12px;

        padding: 3px 8px;

        border-radius: 5px;

        margin-top: 3px;

    }
</style>
