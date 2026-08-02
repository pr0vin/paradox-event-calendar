<?php

namespace Paradox\EventCalendar\Objects;

class CalendarDay
{

    public function __construct(
        public ?int $year = null,
        public ?int $month = null,
        public ?int $day = null,
        public bool $today = false,
        public bool $currentMonth = true,
        public int $weekDay = 0,
        public array $events = []
    ) {}


    /**
     * Return formatted BS date.
     */
    public function date(): ?string
    {
        if ($this->year === null || $this->month === null || $this->day === null) {
            return null;
        }

        return sprintf(
            '%04d-%02d-%02d',
            $this->year,
            $this->month,
            $this->day
        );
    }

    /**
     * Check whether this day has events.
     */
    public function hasEvents(): bool
    {
        return count($this->events) > 0;
    }
}
