<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Metric;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function cols(Request $request)
    {
        $metric = Metric::where('name', 'Psum_kW')->first();
        return response()->json($metric, 200);
    }

    public function line(Request $request)
    {
        $datetime = $request->input('datetime');
        $period = $request->input('period');

        $timestamp = Carbon::create($datetime);
        switch($period) {
            case 'hour':
                $to = $timestamp->copy()->addHour();
                break;
            case 'day':
                $to = $timestamp->copy()->addDay();
                break;
            case 'week':
                $to = $timestamp->copy()->addWeek();
                break;
            case 'month':
                $to = $timestamp->copy()->addMonth();
                break;
        }

        $metric = Metric::where('name', 'Psum_kW')->first();

        $records = Record::select(['timestamp','recvalue'])->where('metric_id', $metric->id)
            ->whereBetween('timestamp', [$timestamp, $to])
            ->get();

        $serie = collect();
        foreach($records as $record) {
            $serie->push([$record->timestamp, $record->recvalue]);
        }
        return response()->json($serie->toArray(), 200);
    }
}
