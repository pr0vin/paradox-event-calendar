<?php

namespace Paradox\EventCalendar\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class EventCalendar extends Component
{
    public function render()
    {
        $calendar = app(\Paradox\EventCalendar\Services\CalendarService::class);

        return view('event-calendar::event-calendar', [
            'calendar' => $calendar->month()
        ]);
    }
}
