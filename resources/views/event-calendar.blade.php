<div class="calendar">

    <h2>{{ $calendar->year }} / {{ $calendar->month }}</h2>

    <table border="1" width="100%" cellpadding="8">
        <thead>
            <tr>
                @foreach ($calendar->weekdays as $weekday)
                    <th>{{ $weekday }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach (collect($calendar->days)->chunk(7) as $week)
                <tr>
                    @foreach ($week as $day)
                        <td style="height:80px">
                            {{ $day->day }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
