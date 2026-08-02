<?php

namespace Paradox\EventCalendar;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Paradox\EventCalendar\Components\EventCalendar;

class EventCalendarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'event-calendar'
        );


        Blade::component(
            'event-calendar',
            EventCalendar::class
        );
    }
}
