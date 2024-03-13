<?php

namespace App\Repositories;

use App\Enums\BookingStatuses;
use App\Interfaces\Repositories\BookingRepositoryInterface;
use App\Models\Booking;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{
    public function getHotelsWithSmallestWeekendStays(int $limit = 5): array
    {
        $subQuery = Booking::select('hotel_id', 'arrival_date', 'nights', DB::raw('arrival_date as date'))
            ->unionAll(
                Booking::select('hotel_id', 'arrival_date', 'nights', DB::raw('DATE_ADD(arrival_date, INTERVAL 1 DAY) as date'))
                    ->whereRaw('DATE_ADD(arrival_date, INTERVAL 1 DAY) < DATE_ADD(arrival_date, INTERVAL nights - 1 DAY)')
            )->toSql();

        $query = DB::table(DB::raw("($subQuery) as all_dates"))
            ->select('hotel_id', DB::raw('COUNT(date) as weekend_stays'))
            ->whereIn(DB::raw('WEEKDAY(date)'), [4, 5])
            ->groupBy('hotel_id')
            ->orderBy('weekend_stays')
            ->limit($limit);

        $results = $query->get();

        return $results->toArray();
    }

    public function getRejectedBookings(int $hotelId)
    {
        return Booking::where('hotel_id', '=', $hotelId)
            ->where('status', '=', BookingStatuses::REJECTED)
            ->get();
    }

    public function getApproved($hotel_id)
    {
        return Booking::where('hotel_id', '=', $hotel_id)
            ->where('status', '=', BookingStatuses::APPROVED)
            ->get();
    }

    public function getRejected()
    {
        return Booking::where('status', '=', BookingStatuses::REJECTED)
            ->orderBy('arrival_date')
            ->get();
    }
}
