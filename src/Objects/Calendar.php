<?php

namespace Paradox\EventCalendar\Objects;

class Calendar
{
    public int $year;

    public int $month;

    /**
     * @var CalendarDay[]
     */
    public array $days = [];

    public array $weekdays = [];

    public function __construct(
        int $year,
        int $month,
        array $days,
        array $weekdays
    ) {
        $this->year = $year;
        $this->month = $month;
        $this->days = $days;
        $this->weekdays = $weekdays;
    }
}
