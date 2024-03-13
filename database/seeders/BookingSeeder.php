<?php

namespace Database\Seeders;

use App\Enums\BookingStatuses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LazyCollection::make(function () {
            $handle = fopen(storage_path("app/backup/bookings.csv"), 'r');

            while (($line = fgetcsv($handle, 4096)) !== false) {
                $dataString = implode(", ", $line);
                $row = explode(';', $dataString);
                yield $row;
            }

            fclose($handle);
        })
            ->skip(1)
            ->chunk(50)
            ->each(function (LazyCollection $chunk) {
                $records = $chunk->map(function ($row) {
                    $arrayRecords = explode(',',$row[0]);
//                    dd($arrayRecords);
                    return [
                        "hotel_id" => $arrayRecords[1],
                        "customer_id" => $arrayRecords[2],
                        "sales_price" => $arrayRecords[3],
                        "purchase_price" => $arrayRecords[4],
                        "arrival_date" => $arrayRecords[5],
                        "purchase_day" => $arrayRecords[6],
                        "nights" => $arrayRecords[7],
                        "status" => BookingStatuses::IN_PROGRESS,
                    ];
                })->toArray();

                DB::table('bookings')->insert($records);
            });
    }
}
