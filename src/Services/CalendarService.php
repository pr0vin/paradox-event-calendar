<?php

namespace Paradox\EventCalendar\Services;

use Carbon\Carbon;

class CalendarService
{
    public function month(int $year = null, int $month = null): array
    {
        $date = Carbon::create(
            $year ?? now()->year,
            $month ?? now()->month,
            1
        );

        return [
            'year' => $date->year,
            'month' => $date->month,
            'monthName' => $date->format('F'),
            'daysInMonth' => $date->daysInMonth,
            'startDay' => $date->dayOfWeek,
        ];
    }
}
