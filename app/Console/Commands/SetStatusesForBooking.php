<?php

namespace App\Console\Commands;

use App\Actions\RemoveHotelCapacityDuplicates;
use App\Actions\SetStatusForBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SetStatusesForBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-statuses-booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all duplicates of capacity(cooperate them)';

    /**
     * Execute the console command.
     */
    public function handle(SetStatusForBooking $statusForBooking)
    {
        try {
            $statusForBooking->execute();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
