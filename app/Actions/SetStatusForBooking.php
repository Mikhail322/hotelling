<?php

namespace App\Actions;

use App\Enums\BookingStatuses;
use App\Models\Booking;
use App\Models\Capacity;
use App\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SetStatusForBooking
{
    public function execute(): void
    {
        DB::beginTransaction();

        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            $this->processHotelBookings($hotel);
        }

        DB::commit();
    }

    private function processHotelBookings(Hotel $hotel): void
    {
        $bookings = $this->getHotelBookings($hotel);

        foreach ($bookings as $booking) {
            $this->updateBookingStatus($booking, $hotel);
        }
    }

    private function getHotelBookings(Hotel $hotel)
    {
        return Booking::where('hotel_id', $hotel->id)
            ->orderBy('purchase_day')
            ->orderBy('id')
            ->get();
    }

    private function updateBookingStatus(Booking $booking, Hotel $hotel): void
    {
        $arrivalDate = Carbon::parse($booking->arrival_date);
        $lastDate = $arrivalDate->copy()->addDays($booking->nights - 1);

        $capacityPerDates = $this->getCapacityPerDates($hotel, $arrivalDate, $lastDate);

        if (count($capacityPerDates) === $booking->nights) {
            $this->approveBooking($booking, $capacityPerDates);
        } else {
            $this->rejectBooking($booking);
        }
    }

    private function getCapacityPerDates(Hotel $hotel, Carbon $arrivalDate, Carbon $lastDate)
    {
        return Capacity::where('hotel_id', $hotel->id)
            ->whereBetween('date', [$arrivalDate, $lastDate])
            ->where('capacity', '>', 0)
            ->get();
    }

    private function approveBooking(Booking $booking, $capacityPerDates): void
    {
        Booking::where('id', $booking->id)->update(['status' => BookingStatuses::APPROVED]);

        foreach ($capacityPerDates as $capacityPerDate) {
            Capacity::where('id', $capacityPerDate->id)->decrement('capacity');
        }
    }

    private function rejectBooking(Booking $booking): void
    {
        Booking::where('id', $booking->id)->update(['status' => BookingStatuses::REJECTED]);
    }

}
