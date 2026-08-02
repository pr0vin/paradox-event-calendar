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


        // Current BS date
        $today = $this->nepaliDate->today();


        $weekdays = [
            'आइत',
            'सोम',
            'मंगल',
            'बुध',
            'बिही',
            'शुक्र',
            'शनि',
        ];


        // Total days in month
        $monthDays = $this->nepaliDate->daysInMonth(
            $year,
            $month
        );


        /*
        |--------------------------------------------------------------------------
        | First day weekday
        |--------------------------------------------------------------------------
        */

        $firstDay = $this->nepaliDate->create(
            $year,
            $month,
            1
        );


        // 0 Sunday - 6 Saturday
        $startDay = $firstDay->dayOfWeek();



        /*
        |--------------------------------------------------------------------------
        | Empty cells before month start
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
        | Month days
        |--------------------------------------------------------------------------
        */

        for ($day = 1; $day <= $monthDays; $day++) {


            $days[] = new CalendarDay(

                year: $year,

                month: $month,

                day: $day,


                today: (
                    $today->year() === $year &&
                    $today->month() === $month &&
                    $today->day() === $day
                ),


                currentMonth: true,


                // calculate from position
                weekDay: ($startDay + $day - 1) % 7,


                events: []
            );
        }



        /*
        |--------------------------------------------------------------------------
        | Complete final row
        |--------------------------------------------------------------------------
        */

        while (count($days) % 7 !== 0) {

            $days[] = new CalendarDay(
                day: null,
                currentMonth: false,
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
