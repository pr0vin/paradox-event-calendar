<?php

namespace Paradox\EventCalendar;

use Illuminate\Support\ServiceProvider;
use Paradox\EventCalendar\Components\EventCalendar;

class EventCalendarServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'event-calendar'
        );

        $this->publishes([
            __DIR__ . '/../resources/views'
            => resource_path('views/vendor/event-calendar'),
        ], 'event-calendar-views');

        $this->loadViewComponentsAs('', [
            EventCalendar::class,
        ]);
    }
}
