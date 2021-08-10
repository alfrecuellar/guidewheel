<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Metric;
use App\Models\Record;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/demoCompressorWeekData.csv');
        $records = fopen($path, 'r');

        $count = 0;
        while (($row = fgetcsv($records, 0, ',')) != false){
            if ($count) {
                /*Array (
                    [0] => timestamp
                    [1] => metricid
                    [2] => deviceid
                    [3] => recvalue
                    [4] => calcvalue
                    [5] => excthreshold
                    [6] => excthlimit
                    [7] => deviation
                )*/

                $metric = Metric::where('name', $row[1])->first();
                if(!$metric) {
                    $metric = new Metric();
                    $metric->name = $row[1];
                    $metric->save();
                }

                $device = Device::where('name', $row[2])->first();
                if(!$device) {
                    $device = new Device();
                    $device->name = $row[2];
                    $device->save();
                }

                $record = new Record();
                $record->timestamp    = date('Y-m-d H:i:s', intval($row[0] / 1000));
                $record->device_id    = $device->id;
                $record->metric_id    = $metric->id;
                $record->recvalue     = $row[3];
                $record->calcvalue    = $row[4];
                $record->excthreshold = $row[5];
                $record->excthlimit   = $row[6];
                $record->deviation    = $row[7];
                $record->save();
            }
            $count++;
        }
    }
}
