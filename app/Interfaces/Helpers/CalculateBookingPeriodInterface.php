<?php

namespace App\Interfaces\Helpers;

interface CalculateBookingPeriodInterface
{
    public static function calculatePeriod($booking): array;
}
