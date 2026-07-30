<?php

namespace Paradox\EventCalendar\Components;

use Illuminate\View\Component;

class EventCalendar extends Component
{
    public function render()
    {
        return view('event-calendar::event-calendar');
    }
}
