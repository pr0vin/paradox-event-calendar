<?php

namespace Paradox\EventCalendar\Objects;


class CalendarEvent
{
    public function __construct(

        public string $title,

        public string $date,

        public array $data = []

    ) {}
}
