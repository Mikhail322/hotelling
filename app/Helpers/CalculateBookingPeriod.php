<?php

namespace App\Helpers;

use App\Interfaces\Helpers\CalculateBookingPeriodInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalculateBookingPeriod implements CalculateBookingPeriodInterface
{
    /**
     * Get the period of dates for a booking.
     *
     * @param object $booking The booking object.
     * @return array An array of date strings for the booking period.
     */
    public static function calculatePeriod($booking): array
    {
        $arrivalDate = Carbon::parse($booking->arrival_date);
        $lastDate = $arrivalDate->copy()->addDays($booking->nights - 1);

        return array_map(function ($element) {
            return $element->format('Y-m-d');
        }, CarbonPeriod::create($arrivalDate, $lastDate)->toArray());
    }
}
