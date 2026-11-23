<?php

namespace Paradox\EventCalendar\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;
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

        $requestYear = request('year');
        $requestMonth = request('month');

        $this->year = $year
            ?? (is_numeric($requestYear) && (int) $requestYear > 0
                ? (int) $requestYear
                : $today->year());

        $this->month = $month
            ?? (is_numeric($requestMonth) && (int) $requestMonth >= 1 && (int) $requestMonth <= 12
                ? (int) $requestMonth
                : $today->month());

        $this->eventUrl = $eventUrl;
    }

    public function render(): View
    {
        $calendar = $this->builder->build(
            $this->year,
            $this->month
        );

        return view('event-calendar::event-calendar', [
            'calendar' => $calendar,
            'eventUrl' => $this->eventUrl,
        ]);
    }
}
