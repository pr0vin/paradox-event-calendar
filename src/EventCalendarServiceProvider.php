<?php

namespace Paradox\EventCalendar;

use Illuminate\Support\ServiceProvider;
use Paradox\EventCalendar\Services\CalendarBuilder;

class EventCalendarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            CalendarBuilder::class,
            function () {
                return new CalendarBuilder();
            }
        );
    }


    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'event-calendar'
        );

        $this->loadViewComponentsAs(
            'event-calendar',
            [
                \Paradox\EventCalendar\Components\EventCalendar::class
            ]
        );
    }
}
