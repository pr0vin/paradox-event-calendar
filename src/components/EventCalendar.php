<?php

namespace Paradox\EventCalendar\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Paradox\EventCalendar\Services\CalendarBuilder;
use Paradox\NepaliDate\NepaliDate;

class EventCalendar extends Component
{
    public int $year;
    public int $month;


    public function __construct(
        protected CalendarBuilder $builder,
        protected NepaliDate $nepaliDate,
        ?int $year = null,
        ?int $month = null
    ) {

        $today = $this->nepaliDate->today();

        $this->year = $year ?? $today->year();
        $this->month = $month ?? $today->month();
    }


    public function render(): View
    {
        $calendar = $this->builder->build(
            $this->year,
            $this->month
        );

        return view(
            'event-calendar::event-calendar',
            compact('calendar')
        );
    }
}
