<?php

namespace Paradox\EventCalendar\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Paradox\EventCalendar\Services\CalendarBuilder;
use Paradox\NepaliDate\NepaliDate;

class EventCalendar extends Component
{
    public int $year;

    public int $month;

    public ?string $eventUrl;

    public function __construct(
        protected CalendarBuilder $builder,
        protected NepaliDate $nepaliDate,
        ?int $year = null,
        ?int $month = null,
        ?string $eventUrl = null
    ) {
        $today = $this->nepaliDate->today();

        /*
        |--------------------------------------------------------------------------
        | Year
        |--------------------------------------------------------------------------
        | Priority:
        | 1. Component parameter
        | 2. URL ?year=
        | 3. Current Nepali year
        */

        $this->year = $year
            ?? (int) request(
                'year',
                $today->year()
            );


        /*
        |--------------------------------------------------------------------------
        | Month
        |--------------------------------------------------------------------------
        | Priority:
        | 1. Component parameter
        | 2. URL ?month=
        | 3. Current Nepali month
        */

        $this->month = $month
            ?? (int) request(
                'month',
                $today->month()
            );


        $this->eventUrl = $eventUrl;
    }


    public function render(): View
    {
        $calendar = $this->builder->build(
            $this->year,
            $this->month
        );

        return view(
            'event-calendar::event-calendar',
            [
                'calendar' => $calendar,
                'eventUrl' => $this->eventUrl,
            ]
        );
    }
}
