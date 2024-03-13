<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CapacityRepositoryInterface;
use App\Models\Capacity;

class CapacityRepository implements CapacityRepositoryInterface
{
    public function findDateWithEmptyCapacity(int $hotelId, string $arrivalDate, string $lastDate)
    {
        return Capacity::where('hotel_id', $hotelId)
            ->whereBetween('date', [$arrivalDate, $lastDate])
            ->where('capacity', 0)
            ->first();
    }

    public function findDatesWithNonEmptyCapacity(int $hotelId, string $arrivalDate, string $lastDate)
    {
        return Capacity::where('hotel_id', $hotelId)
            ->whereBetween('date', [$arrivalDate, $lastDate])
            ->where('capacity', '>', 0)
            ->get();
    }
}
