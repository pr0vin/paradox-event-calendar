<?php

namespace Paradox\EventCalendar\Services;

use Carbon\Carbon;

class CalendarService
{
    public function month(
        ?int $year = null,
        ?int $month = null
    ): array {

        $today = Carbon::now();

        $date = Carbon::create(
            $year ?? $today->year,
            $month ?? $today->month,
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
