<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Paradox\EventCalendar\Services\CalendarBuilder;
use Paradox\NepaliDate\NepaliDate;

Route::get('/event-calendar/datepicker/calendar', function (
    Request $request,
    CalendarBuilder $builder,
    NepaliDate $nepaliDate
) {
    $year = (int) $request->query('year');
    $month = (int) $request->query('month');

    if ($year < 2000 || $year > 2090 || $month < 1 || $month > 12) {
        return response()->json([
            'message' => 'Invalid Nepali year or month.',
        ], 422);
    }

    $calendar = $builder->build($year, $month);

    $selectedDate = null;
    $value = $request->query('value');

    if (
        $value &&
        preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $matches)
    ) {
        $selectedDate = [
            'year' => (int) $matches[1],
            'month' => (int) $matches[2],
            'day' => (int) $matches[3],
        ];
    }

    return response()->json([
        'html' => view('event-calendar::datepicker-calendar', [
            'calendar' => $calendar,
            'selectedDate' => $selectedDate,
            'today' => $nepaliDate->today(),
        ])->render(),
    ]);
})->name('event-calendar.datepicker.calendar');
