<?php

namespace Paradox\EventCalendar\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Paradox\EventCalendar\Services\CalendarBuilder;

class EventCalendar extends Component
{
    public function __construct(
        public int $year = 2083,
        public int $month = 1
    ) {}


    public function render(): View
    {
        $calendar = app(CalendarBuilder::class)
            ->build(
                $this->year,
                $this->month
            );

        return view(
            'event-calendar::event-calendar',
            compact('calendar')
        );
    }
}
