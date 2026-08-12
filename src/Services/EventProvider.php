<?php

namespace Paradox\EventCalendar\Services;


use Illuminate\Support\Facades\Http;
use Paradox\EventCalendar\Objects\CalendarEvent;


class EventProvider
{

    public function fetch(
        string $url
    ): array {


        $response = Http::get($url);


        if (!$response->successful()) {

            return [];
        }


        return collect($response->json())
            ->map(function ($event) {

                return new CalendarEvent(

                    title: $event['title'],

                    date: $event['date'],

                    data: $event

                );
            })
            ->toArray();
    }
}
