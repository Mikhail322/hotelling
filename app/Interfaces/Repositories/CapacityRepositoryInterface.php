<?php

namespace App\Interfaces\Repositories;

interface CapacityRepositoryInterface
{
    public function findDateWithEmptyCapacity(int $hotelId, string $arrivalDate, string $lastDate);

    public function findDatesWithNonEmptyCapacity(int $hotelId, string $arrivalDate, string $lastDate);
}
