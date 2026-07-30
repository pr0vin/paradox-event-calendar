<?php

namespace Paradox\EventCalendar\Generators;

use Carbon\Carbon;
use Paradox\EventCalendar\DTO\Day;

class MonthGenerator
{
    public function generate(int $year, int $month): array
    {
        $date = Carbon::create($year, $month, 1);

        // Find the first visible date (Sunday before month starts)
        $startDate = $date->copy()->startOfWeek();

        $days = [];

        // Generate 42 calendar cells
        for ($i = 0; $i < 42; $i++) {

            $currentDate = $startDate->copy()->addDays($i);

            $days[] = new Day(
                date: $currentDate,
                isCurrentMonth: $currentDate->month === $month,
                isToday: $currentDate->isToday(),
            );
        }

        return $days;
    }
}
