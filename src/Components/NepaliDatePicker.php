<?php

namespace Paradox\EventCalendar\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Paradox\EventCalendar\Services\CalendarBuilder;
use Paradox\NepaliDate\NepaliDate;

class NepaliDatePicker extends Component
{
    public int $year;
    public int $month;
    public ?string $value;
    public ?array $selectedDate = null;

    public function __construct(
        protected CalendarBuilder $builder,
        protected NepaliDate $nepaliDate,
        public string $name = 'date',
        public string $id = 'nepali-date',
        ?string $value = null,
        public string $placeholder = 'मिति छान्नुहोस्',
        public bool $required = false,
        ?int $year = null,
        ?int $month = null,
    ) {
        $today = $this->nepaliDate->today();

        $this->value = $value;

        if ($value && preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $matches)) {
            $this->selectedDate = [
                'year' => (int) $matches[1],
                'month' => (int) $matches[2],
                'day' => (int) $matches[3],
            ];
        }

        $this->year = $year
            ?? ($this->selectedDate['year'] ?? $today->year());

        $this->month = $month
            ?? ($this->selectedDate['month'] ?? $today->month());
    }

    public function render(): View
    {
        $calendar = $this->builder->build($this->year, $this->month);

        return view('event-calendar::datepicker', [
            'calendar' => $calendar,
            'selectedDate' => $this->selectedDate,
            'today' => $this->nepaliDate->today(),
        ]);
    }
}
