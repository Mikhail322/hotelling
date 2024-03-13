<?php
// Task 1
namespace App\Services\Hotelling;

use App\Enums\BookingStatuses;
use App\Helpers\CalculateBookingPeriod;
use App\Interfaces\Repositories\BookingRepositoryInterface;
use App\Interfaces\Services\Hotelling\HotellingServiceInterface;
use App\Models\Booking;
use App\Models\Hotel;
use Carbon\Carbon;

class TopFiveHotelsWeekendStayService implements HotellingServiceInterface
{
    private array $hotelWeekendStatistics;

    private BookingRepositoryInterface $bookingRepo;

    public function __construct(
        BookingRepositoryInterface $bookingRepo,
    )
    {
        $this->bookingRepo = $bookingRepo;
    }

    public function execute(): array
    {
        $this->fetchHotelWeekendStatistics();
        $this->sortHotelWeekendStatistics();
        $this->limitTopFiveHotels();

        return $this->hotelWeekendStatistics;
    }

    private function fetchHotelWeekendStatistics(): void
    {
        $hotels = Hotel::all();
        foreach ($hotels as $hotel)
        {
            $weekendStays = $this->calculateWeekendStays($hotel);
            $this->hotelWeekendStatistics[] = [
                'id' => $hotel->id,
                'weekend_stays' => $weekendStays,
            ];
        }
    }

    private function calculateWeekendStays(object $hotel): int
    {
        $weekendStays = 0;
        $approvedBookings = $this->bookingRepo->getApproved($hotel->id);
        foreach ($approvedBookings as $approvedBooking)
        {
            $weekendStays += $this->countWeekendStaysForBooking($approvedBooking);
        }
        return $weekendStays;
    }

    private function countWeekendStaysForBooking(object $booking): int
    {
        $weekendStays = 0;
        $bookedDates = CalculateBookingPeriod::calculatePeriod($booking);
        foreach ($bookedDates as $bookedDate)
        {
            if(Carbon::parse($bookedDate)->isFriday() || Carbon::parse($bookedDate)->isSaturday())
            {
                $weekendStays++;
            }
        }
        return $weekendStays;
    }

    private function sortHotelWeekendStatistics(): void
    {
        usort($this->hotelWeekendStatistics, function ($first, $second) {
            return $first['weekend_stays'] - $second['weekend_stays'];
        });
    }

    private function limitTopFiveHotels(): void
    {
        $this->hotelWeekendStatistics = array_slice($this->hotelWeekendStatistics, 0, 5);
    }
}
