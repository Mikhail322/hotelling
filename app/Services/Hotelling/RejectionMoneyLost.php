<?php
//Task 3
namespace App\Services\Hotelling;

use App\Interfaces\Repositories\BookingRepositoryInterface;
use App\Interfaces\Repositories\CapacityRepositoryInterface;
use App\Interfaces\Services\Hotelling\HotellingServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RejectionMoneyLost implements HotellingServiceInterface
{
    private CapacityRepositoryInterface $capacityRepo;
    private BookingRepositoryInterface $bookingRepo;
    private array $rejectionLost;

    public function __construct(
        CapacityRepositoryInterface $capacityRepo,
        BookingRepositoryInterface  $bookingRepo,
    ) {
        $this->capacityRepo = $capacityRepo;
        $this->bookingRepo = $bookingRepo;
    }

    public function execute(): array
    {
        $rejectedBookings = $this->bookingRepo->getRejected();
        $lossPerDate = $this->calculateLossPerDate($rejectedBookings);
        $maxLoss = max($lossPerDate);
        $date = array_search($maxLoss, $lossPerDate);
        $this->setRejectionLost($date, $maxLoss);

        return $this->rejectionLost;
    }

    private function calculateLossPerDate(Collection $rejectedBookings): array
    {
        $lossPerDate = [];
        foreach ($rejectedBookings as $rejectedBooking)
        {
            $dateWithEmptyCapacity = $this->findDateWithEmptyCapacity($rejectedBooking);
            if ($dateWithEmptyCapacity) {
                $this->accumulateLossPerDate($lossPerDate, $dateWithEmptyCapacity, $rejectedBooking->purchase_price);
            }
        }
        return $lossPerDate;
    }

    private function findDateWithEmptyCapacity(object $rejectedBooking): ?string
    {
        $arrivalDate = Carbon::parse($rejectedBooking->arrival_date);
        $lastDate = $arrivalDate->copy()->addDays($rejectedBooking->nights - 1);
        $capacity = $this->capacityRepo->findDateWithEmptyCapacity(
            $rejectedBooking->hotel_id,
            $arrivalDate,
            $lastDate
        );

        return $capacity ? $capacity->date : null;
    }

    private function accumulateLossPerDate(array &$lossPerDate, string $date, float $purchasePrice): void
    {
        if (!array_key_exists($date, $lossPerDate)) {
            $lossPerDate[$date] = $purchasePrice;
        } else {
            $lossPerDate[$date] += $purchasePrice;
        }
    }

    private function setRejectionLost(string $date, float $maxLoss): void
    {
        $this->rejectionLost = [
            'date' => $date,
            'value' => $maxLoss,
        ];
    }

}
