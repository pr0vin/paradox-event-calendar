<?php

namespace Paradox\EventCalendar;

use Paradox\EventCalendar\Generators\MonthGenerator;

class Calendar
{
    public function month(int $year, int $month)
    {
        return (new MonthGenerator())->generate($year, $month);
    }
}
