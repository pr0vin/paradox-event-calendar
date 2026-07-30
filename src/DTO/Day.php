<?php

namespace Paradox\EventCalendar\DTO;

use Carbon\Carbon;

class Day
{
    public function __construct(
        public Carbon $date,
        public bool $isCurrentMonth,
        public bool $isToday = false,
        public array $events = [],
    ) {}
}
