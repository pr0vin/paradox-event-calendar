@php
    $selectedYear = $selectedDate['year'] ?? null;
    $selectedMonth = $selectedDate['month'] ?? null;
    $selectedDay = $selectedDate['day'] ?? null;

    $todayYear = $today->year();
    $todayMonth = $today->month();
    $todayDay = $today->day();
@endphp

<div class="np-calendar-header">

    <button type="button" class="np-prev" aria-label="Previous month">
        ‹
    </button>

    <div class="np-month-year">

        <select class="np-month-select" aria-label="Select month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected($calendar->month == $m)>
                    {{ nepali_month($m) }}
                </option>
            @endfor
        </select>

        <select class="np-year-select" aria-label="Select year">
            @for ($y = 2000; $y <= 2090; $y++)
                <option value="{{ $y }}" @selected($calendar->year == $y)>
                    {{ toNepaliNumber($y) }}
                </option>
            @endfor
        </select>

    </div>

    <button type="button" class="np-next" aria-label="Next month">
        ›
    </button>

</div>

<table class="np-calendar-table">

    <thead>
        <tr>
            @foreach ($calendar->weekdays as $index => $weekday)
                <th class="{{ in_array($index, [0, 6]) ? 'holiday' : '' }}">
                    {{ $weekday }}
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody class="np-calendar-days">

        @foreach (collect($calendar->days)->chunk(7) as $week)
            <tr>
                @foreach ($week as $day)
                    <td>
                        @if ($day->day)
                            @php
                                $isSelected =
                                    (int) $selectedYear === (int) $day->year &&
                                    (int) $selectedMonth === (int) $day->month &&
                                    (int) $selectedDay === (int) $day->day;

                                $isToday =
                                    (int) $todayYear === (int) $day->year &&
                                    (int) $todayMonth === (int) $day->month &&
                                    (int) $todayDay === (int) $day->day;
                            @endphp

                            <button type="button"
                                class="np-day
                                    {{ $isToday ? 'today' : '' }}
                                    {{ in_array($day->weekDay, [0, 6]) ? 'holiday' : '' }}
                                    {{ $isSelected ? 'selected' : '' }}"
                                data-date="{{ sprintf('%04d-%02d-%02d', $day->year, $day->month, $day->day) }}">
                                {{ toNepaliNumber($day->day) }}
                            </button>
                        @else
                            <span class="np-empty"></span>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach

    </tbody>

</table>

<div class="np-calendar-footer">

    <button type="button" class="np-today">
        आज
    </button>

    <button type="button" class="np-clear">
        खाली गर्नुहोस्
    </button>

</div>
