<?php

namespace Paradox\EventCalendar\Objects;


class Calendar
{

    public function __construct(

        public int $year,

        public int $month,

        public string $monthName,

        public array $days,

        public array $weekdays

    ) {}


    public function weeks(): array
    {
        return array_chunk(
            $this->days,
            7
        );
    }


    public function previousMonth(): array
    {

        if ($this->month === 1) {

            return [
                'year' => $this->year - 1,
                'month' => 12
            ];
        }


        return [
            'year' => $this->year,
            'month' => $this->month - 1
        ];
    }



    public function nextMonth(): array
    {

        if ($this->month === 12) {

            return [
                'year' => $this->year + 1,
                'month' => 1
            ];
        }


        return [
            'year' => $this->year,
            'month' => $this->month + 1
        ];
    }
}
