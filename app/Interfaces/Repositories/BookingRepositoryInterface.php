<?php

namespace App\Interfaces\Repositories;

interface BookingRepositoryInterface
{
    public function getHotelsWithSmallestWeekendStays(int $limit = 5): array;

    public function getRejectedBookings(int $hotelId);

    public function getApproved($hotel_id);

    public function getRejected();

}
