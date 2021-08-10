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
                $from = $timestamp->copy()->subHour();
                break;
            case 'day':
                $from = $timestamp->copy()->subDay();
                break;
            case 'week':
                $from = $timestamp->copy()->subWeek();
                break;
            case 'month':
                $from = $timestamp->copy()->subMonth();
                break;
        }

        $metric = Metric::where('name', 'Psum_kW')->first();

        $records = Record::select(['timestamp','recvalue'])->where('metric_id', $metric->id)
            ->whereBetween('timestamp', [$from, $timestamp])
            ->get();

        $serie = collect();
        foreach($records as $record) {
            $serie->push([$record->timestamp, $record->recvalue]);
        }
        return response()->json($serie->toArray(), 200);
    }
}
