<?php

namespace Paradox\EventCalendar\Services;

use Paradox\EventCalendar\Objects\Calendar;
use Paradox\EventCalendar\Objects\CalendarDay;
use Paradox\NepaliDate\NepaliDate;

class CalendarBuilder
{
    public function __construct(
        protected NepaliDate $nepaliDate
    ) {}


    public function build(
        int $year,
        int $month
    ): Calendar {

        $days = [];

        /*
        |--------------------------------------------------------------------------
        | Today
        |--------------------------------------------------------------------------
        */

        $today = $this->nepaliDate->today();


        /*
        |--------------------------------------------------------------------------
        | Week Names
        |--------------------------------------------------------------------------
        */

        $weekdays = [
            'आइत',
            'सोम',
            'मंगल',
            'बुध',
            'बिही',
            'शुक्र',
            'शनि',
        ];


        /*
        |--------------------------------------------------------------------------
        | Days In Month
        |--------------------------------------------------------------------------
        */

        $monthDays = $this->nepaliDate
            ->daysInMonth(
                $year,
                $month
            );


        /*
        |--------------------------------------------------------------------------
        | First Date
        |--------------------------------------------------------------------------
        */

        $firstDate = $this->nepaliDate
            ->create(
                $year,
                $month,
                1
            );


        $startDay = $firstDate->dayOfWeek();


        /*
        |--------------------------------------------------------------------------
        | Empty Cells Before Month
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $startDay; $i++) {

            $days[] = new CalendarDay(
                year: null,
                month: null,
                day: null,
                today: false,
                currentMonth: false,
                weekDay: $i,
                events: []
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Actual Days
        |--------------------------------------------------------------------------
        */

        for ($day = 1; $day <= $monthDays; $day++) {

            $adDate = $this->nepaliDate
                ->bsToAd(
                    $year,
                    $month,
                    $day
                );


            $days[] = new CalendarDay(
                year: $year,
                month: $month,
                day: $day,

                today: $today->year() === $year
                    &&
                    $today->month() === $month
                    &&
                    $today->day() === $day,

                currentMonth: true,

                weekDay: $adDate->dayOfWeek(),

                events: []
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Complete Last Row
        |--------------------------------------------------------------------------
        */

        while (count($days) % 7 !== 0) {

            $days[] = new CalendarDay(
                currentMonth: false,
                events: []
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Calendar
        |--------------------------------------------------------------------------
        */

        return new Calendar(
            year: $year,

            month: $month,

            monthName: $this->nepaliDate
                ->monthName($month),

            days: $days,

            weekdays: $weekdays
        );
    }
}
