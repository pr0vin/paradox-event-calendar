<?php

namespace Paradox\EventCalendar\Services;

use Paradox\EventCalendar\Objects\Calendar;
use Paradox\EventCalendar\Objects\CalendarDay;
use Paradox\NepaliDate\NepaliDate;


class CalendarBuilder
{

    public function __construct(

        protected NepaliDate $nepaliDate,

        protected EventProvider $eventProvider

    ) {}



    public function build(

        int $year,

        int $month,

    ): Calendar {


        /*
        |--------------------------------------------------------------------------
        | Load Events
        |--------------------------------------------------------------------------
        */

        $events = [];


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
            'शनि'

        ];



        /*
        |--------------------------------------------------------------------------
        | Days in Month
        |--------------------------------------------------------------------------
        */

        $monthDays = $this->nepaliDate
            ->daysInMonth(
                $year,
                $month
            );




        /*
        |--------------------------------------------------------------------------
        | First Week Day
        |--------------------------------------------------------------------------
        */

        $firstDate = $this->nepaliDate
            ->create(
                $year,
                $month,
                1
            );


        $startDay = $firstDate
            ->dayOfWeek();




        /*
        |--------------------------------------------------------------------------
        | Empty cells before month
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


                events: $this->eventsForDate(

                    $year,

                    $month,

                    $day,

                    $events

                )

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




        return new Calendar(

            year: $year,

            month: $month,

            monthName: $this->nepaliDate
                ->monthName($month),


            days: $days,


            weekdays: $weekdays

        );
    }





    /**
     * Get events for specific BS date
     */
    protected function eventsForDate(

        int $year,

        int $month,

        int $day,

        array $events

    ): array {


        $date = sprintf(

            '%04d-%02d-%02d',

            $year,

            $month,

            $day

        );



        return collect($events)

            ->filter(function ($event) use ($date) {


                /*
                 | Support object
                 */

                if (is_object($event)) {

                    return $event->date === $date;
                }



                /*
                 | Support array from API
                 */

                if (is_array($event)) {

                    return ($event['date'] ?? null) === $date;
                }


                return false;
            })

            ->values()

            ->toArray();
    }
}
