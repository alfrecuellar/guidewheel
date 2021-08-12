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
        $datetime = $request->input('datetime');
        $period = $request->input('period');

        $metric = Metric::where('name', 'Psum_kW')->first();

        $timestamp = Carbon::create($datetime);
        switch($period) {
            case 'hour':
                $to = $timestamp->copy()->addHour();

                $categories = collect([
                    [
                        'from' => $timestamp->copy(),
                        'to' => $timestamp->copy()->addMinutes(10),
                        'name' => $timestamp->copy()->format('i') . '-' . $timestamp->copy()->addMinutes(10)->format('i')
                    ], [
                        'from' => $timestamp->copy()->addMinutes(10),
                        'to' => $timestamp->copy()->addMinutes(20),
                        'name' => $timestamp->copy()->addMinutes(10)->format('i') . '-' . $timestamp->copy()->addMinutes(20)->format('i')
                    ], [
                        'from' => $timestamp->copy()->addMinutes(20),
                        'to' => $timestamp->copy()->addMinutes(30),
                        'name' => $timestamp->copy()->addMinutes(20)->format('i') . '-' . $timestamp->copy()->addMinutes(30)->format('i')
                    ], [
                        'from' => $timestamp->copy()->addMinutes(30),
                        'to' => $timestamp->copy()->addMinutes(40),
                        'name' => $timestamp->copy()->addMinutes(30)->format('i') . '-' . $timestamp->copy()->addMinutes(40)->format('i')
                    ], [
                        'from' => $timestamp->copy()->addMinutes(40),
                        'to' => $timestamp->copy()->addMinutes(50),
                        'name' => $timestamp->copy()->addMinutes(40)->format('i') . '-' . $timestamp->copy()->addMinutes(50)->format('i')
                    ], [
                        'from' => $timestamp->copy()->addMinutes(50),
                        'to' => $timestamp->copy()->addMinutes(60),
                        'name' => $timestamp->copy()->addMinutes(50)->format('i') . '-' . $timestamp->copy()->addMinutes(60)->format('i')
                    ]
                ]);

                $series = [
                    [
                        'name' => 'Loaded',
                        'data' => collect(),
                        'min' => 20,
                        'max' => 125,
                    ], [
                        'name' => 'Idle',
                        'data' => collect(),
                        'min' => 1,
                        'max' => 20,
                    ], [
                        'name' => 'Unloaded',
                        'data' => collect(),
                        'min' => 0,1,
                        'max' => 1,
                    ], [
                        'name' => 'Off',
                        'data' => collect(),
                        'min' => 0,
                        'max' => 0,1,
                    ],
                ];

                foreach($series as $i => $serie) {
                    $data = collect();
                    foreach($categories as $category) {
                        $records = Record::select(['timestamp','recvalue'])
                            ->where('metric_id', $metric->id)
                            ->where(function($query) use ($category) {
                                $query->where('timestamp', '>=', $category['from'])
                                    ->where('timestamp', '<', $category['to']);
                            })
                            ->where(function($query) use ($serie) {
                                $query->where('recvalue', '>=', $serie['min'])
                                    ->where('recvalue', '<', $serie['max']);
                            })
                            ->get();
                        $data->push($records->count());
                    }
                    $series[$i]['data'] = $data;
                }

                break;
            case 'day':
                $to = $timestamp->copy()->addDay();
                $categories = ['00-04', '04-08', '08-12', '12-16', '16-20', '20-00'];
                break;
            case 'week':
                $to = $timestamp->copy()->addWeek();
                break;
            case 'month':
                $to = $timestamp->copy()->addMonth();
                break;
        }

        $response = [
            'series' => $series,
            'categories' => $categories->pluck('name')->toArray()
        ];
        return response()->json($response, 200);
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
