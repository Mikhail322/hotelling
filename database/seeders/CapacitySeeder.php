<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class CapacitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LazyCollection::make(function () {
            $handle = fopen(storage_path("app/backup/capacity.csv"), 'r');

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
                    return [
                        "hotel_id" => $arrayRecords[0],
                        "date" => $arrayRecords[1],
                        "capacity" => $arrayRecords[2],
                    ];
                })->toArray();

                DB::table('capacities')->insert($records);
            });
    }
}
