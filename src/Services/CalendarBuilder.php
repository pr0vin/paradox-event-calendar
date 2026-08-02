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


        // Days in current BS month
        $monthDays = $this->nepaliDate->daysInMonth(
            $year,
            $month
        );


        /*
         * Find first day weekday
         * BS date -> AD -> Carbon weekday
         */
        $firstDayAd = $this->nepaliDate
            ->bsToAd(
                $year,
                $month,
                1
            );


        // Sunday = 0, Monday = 1 ...
        $startDay = $firstDayAd->dayOfWeek;


        /*
         * Add empty cells before month starts
         */
        for ($i = 0; $i < $startDay; $i++) {

            $days[] = new CalendarDay(
                day: null,
                currentMonth: false
            );
        }


        /*
         * Add month days
         */
        for ($day = 1; $day <= $monthDays; $day++) {

            $adDate = $this->nepaliDate->bsToAd(
                $year,
                $month,
                $day
            );


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

                // Sunday = 0, Saturday = 6
                weekDay: $adDate->dayOfWeek,

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
