<?php

namespace App\Actions;

use App\Models\Capacity;
use Illuminate\Support\Facades\DB;

class RemoveHotelCapacityDuplicates
{
    public function execute()
    {
        $mergedCapacities = Capacity::select('hotel_id', 'date', Capacity::raw('SUM(capacity) as total_capacity'))
            ->whereIn(DB::raw('(hotel_id, date)'), function($query) {
                $query->select('hotel_id', 'date')
                    ->from('capacities')
                    ->groupBy('hotel_id', 'date')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->groupBy('hotel_id', 'date')
            ->get();

        $mergedCapacities->each(function($item, $key) {
            $duplicates = Capacity::where('hotel_id', $item->hotel_id)
                ->where('date', $item->date)
                ->get();

            $totalCapacity = $duplicates->sum('capacity');

            Capacity::where('hotel_id', $item->hotel_id)
                ->where('date', $item->date)
                ->where('id', '<>', $duplicates->first()->id)
                ->delete();

            $duplicates->first()->update(['capacity' => $totalCapacity]);
        });

        $finalMergedCapacities = Capacity::whereIn(DB::raw('(hotel_id, date)'), function($query) {
            $query->select('hotel_id', 'date')
                ->from('capacities')
                ->groupBy('hotel_id', 'date')
                ->havingRaw('COUNT(*) > 1');
        })
            ->groupBy('hotel_id', 'date')
            ->get();
    }
}
