<?php

namespace Paradox\EventCalendar\Objects;

class CalendarDay
{
    public ?int $day;

    public bool $today = false;

    public bool $currentMonth = true;

    public array $events = [];

    public function __construct(
        ?int $day = null,
        bool $today = false,
        bool $currentMonth = true,
        array $events = []
    ) {
        $this->day = $day;
        $this->today = $today;
        $this->currentMonth = $currentMonth;
        $this->events = $events;
    }
}
