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

        public ?int $weekDay = null,

        public array $events = []

    ) {}


    public function isEmpty(): bool
    {
        return $this->day === null;
    }


    public function date(): ?string
    {
        if ($this->isEmpty()) {
            return null;
        }

        return sprintf(
            '%04d-%02d-%02d',
            $this->year,
            $this->month,
            $this->day
        );
    }
}
