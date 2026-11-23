<?php

namespace Paradox\EventCalendar;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Paradox\EventCalendar\Components\EventCalendar;
use Paradox\EventCalendar\Components\NepaliDatePicker;
use Paradox\EventCalendar\Services\EventProvider;

class EventCalendarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            EventProvider::class,
            fn() => new EventProvider()
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'event-calendar'
        );

        $this->loadRoutesFrom(
            __DIR__ . '/../routes/web.php'
        );

        Blade::component('event-calendar', EventCalendar::class);

        Blade::component(
            'nepali-date-picker',
            NepaliDatePicker::class
        );
    }
}
