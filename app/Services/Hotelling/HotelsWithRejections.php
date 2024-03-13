<?php
//Task 2
namespace App\Services\Hotelling;

use App\Helpers\CalculateBookingPeriod;
use App\Interfaces\Repositories\BookingRepositoryInterface;
use App\Interfaces\Repositories\CapacityRepositoryInterface;
use App\Interfaces\Services\Hotelling\HotellingServiceInterface;
use App\Models\Hotel;
use Carbon\Carbon;

class HotelsWithRejections implements HotellingServiceInterface
{

    private array $rejectionStatistics;

    public function __construct(
        private readonly CapacityRepositoryInterface $capacityRepository,
        private readonly BookingRepositoryInterface  $bookingRepository,
    ) {
    }

    public function execute(): array
    {
        $this->rejectionStatistics = [];

        $hotels = Hotel::all();
        foreach ($hotels as $hotel)
        {
            $datesWithRejects = $this->getRejectedDatesForHotel($hotel);
            if (!empty($datesWithRejects))
            {
                $this->rejectionStatistics[] = [
                    'id' => $hotel->id,
                    'dates' => array_unique($datesWithRejects),
                ];
            }
        }

        return $this->rejectionStatistics;
    }

    private function getRejectedDatesForHotel(object $hotel): array
    {
        $datesWithRejects = [];
        $rejectedBookings = $this->bookingRepository->getRejectedBookings($hotel->id);
        foreach ($rejectedBookings as $rejectedBooking)
        {
            $rejectedDate = $this->findRejectedDateForBooking($rejectedBooking);
            if ($rejectedDate) {
                $datesWithRejects[] = $rejectedDate;
            }
        }

        return $datesWithRejects;
    }

    private function findRejectedDateForBooking(object $booking): ?string
    {
        $arrivalDate = Carbon::parse($booking->arrival_date);
        $lastDate = $arrivalDate->copy()->addDays($booking->nights - 1);

        $bookedDates = CalculateBookingPeriod::calculatePeriod($booking);

        $dateWithEmptyCapacity = $this->capacityRepository->findDateWithEmptyCapacity(
            $booking->hotel_id,
            $arrivalDate,
            $lastDate
        );

        if (!$dateWithEmptyCapacity)
        {
            $datesWithNonEmptyCapacity = [];
            $capacityPerHotel = $this->capacityRepository->findDatesWithNonEmptyCapacity(
                $booking->hotel_id,
                $arrivalDate,
                $lastDate
            );

            foreach ($capacityPerHotel as $capacity)
            {
                $datesWithNonEmptyCapacity[] = $capacity->date;
            }

            $datesWithDifference = array_diff($bookedDates, $datesWithNonEmptyCapacity);
            return empty($datesWithDifference) ? null : array_values($datesWithDifference)[0];
        }

        return $dateWithEmptyCapacity->date;
    }
}
