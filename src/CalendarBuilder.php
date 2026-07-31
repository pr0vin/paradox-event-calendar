<?php

namespace Paradox\EventCalendar\Services;


use Paradox\EventCalendar\Objects\Calendar;
use Paradox\EventCalendar\Objects\CalendarDay;

class CalendarBuilder
{
    public function build(
        int $year,
        int $month
    ): Calendar {

        $days = [];

        $weekdays = [
            'आइत',
            'सोम',
            'मंगल',
            'बुध',
            'बिही',
            'शुक्र',
            'शनि',
        ];

        /**
         * We'll replace this with paradox/nepali-date later.
         */
        $monthDays = 32;

        for ($i = 1; $i <= $monthDays; $i++) {

            $days[] = new CalendarDay(
                day: $i,
                today: false,
                currentMonth: true,
                events: []
            );
        }

        return new Calendar(
            year: $year,
            month: $month,
            days: $days,
            weekdays: $weekdays
        );
    }
}
