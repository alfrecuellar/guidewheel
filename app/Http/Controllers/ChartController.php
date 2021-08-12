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

        switch($period) {
            case 'hour':
                $timestamp = Carbon::create($datetime);
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
                break;

            case 'day':
                $timestamp = Carbon::create($datetime)->startOf('day');
                $to = $timestamp->copy()->addDay();

                $categories = collect([
                    [
                        'from' => $timestamp->copy(),
                        'to' => $timestamp->copy()->addHours(4),
                        'name' => $timestamp->copy()->format('H') . '-' . $timestamp->copy()->addHours(4)->format('H')
                    ], [
                        'from' => $timestamp->copy()->addHours(4),
                        'to' => $timestamp->copy()->addHours(8),
                        'name' => $timestamp->copy()->addHours(4)->format('H') . '-' . $timestamp->copy()->addHours(8)->format('H')
                    ], [
                        'from' => $timestamp->copy()->addHours(8),
                        'to' => $timestamp->copy()->addHours(12),
                        'name' => $timestamp->copy()->addHours(8)->format('H') . '-' . $timestamp->copy()->addHours(12)->format('H')
                    ], [
                        'from' => $timestamp->copy()->addHours(12),
                        'to' => $timestamp->copy()->addHours(16),
                        'name' => $timestamp->copy()->addHours(12)->format('H') . '-' . $timestamp->copy()->addHours(16)->format('H')
                    ], [
                        'from' => $timestamp->copy()->addHours(16),
                        'to' => $timestamp->copy()->addHours(20),
                        'name' => $timestamp->copy()->addHours(16)->format('H') . '-' . $timestamp->copy()->addHours(20)->format('H')
                    ], [
                        'from' => $timestamp->copy()->addHours(20),
                        'to' => $timestamp->copy()->addHours(24),
                        'name' => $timestamp->copy()->addHours(20)->format('H') . '-' . $timestamp->copy()->addHours(24)->format('H')
                    ]
                ]);
                break;

            case 'week':
                $timestamp = Carbon::create($datetime)->startOf('day');
                $to = $timestamp->copy()->addWeek();

                $categories = collect([
                    [
                        'from' => $timestamp->copy(),
                        'to' => $timestamp->copy()->addDays(1),
                        'name' => $timestamp->copy()->format('m-d')
                    ], [
                        'from' => $timestamp->copy()->addDays(1),
                        'to' => $timestamp->copy()->addDays(2),
                        'name' => $timestamp->copy()->addDays(1)->format('m-d')
                    ], [
                        'from' => $timestamp->copy()->addDays(2),
                        'to' => $timestamp->copy()->addDays(3),
                        'name' => $timestamp->copy()->addDays(2)->format('m-d')
                    ], [
                        'from' => $timestamp->copy()->addDays(3),
                        'to' => $timestamp->copy()->addDays(4),
                        'name' => $timestamp->copy()->addDays(3)->format('m-d')
                    ], [
                        'from' => $timestamp->copy()->addDays(4),
                        'to' => $timestamp->copy()->addDays(5),
                        'name' => $timestamp->copy()->addDays(4)->format('m-d')
                    ], [
                        'from' => $timestamp->copy()->addDays(5),
                        'to' => $timestamp->copy()->addDays(6),
                        'name' => $timestamp->copy()->addDays(5)->format('m-d')
                    ], [
                        'from' => $timestamp->copy()->addDays(6),
                        'to' => $timestamp->copy()->addDays(7),
                        'name' => $timestamp->copy()->addDays(6)->format('m-d')
                    ]
                ]);
                break;

            case 'month':
                $timestamp = Carbon::create($datetime)->startOf('day');
                $to = $timestamp->copy()->addMonth();

                $categories = collect([
                    [
                        'from' => $timestamp->copy(),
                        'to' => $timestamp->copy()->addDays(7),
                        'name' => $timestamp->copy()->format('d') . '-' . $timestamp->copy()->addDays(7)->format('d')
                    ], [
                        'from' => $timestamp->copy()->addDays(7),
                        'to' => $timestamp->copy()->addDays(14),
                        'name' => $timestamp->copy()->addDays(7)->format('d') . '-' . $timestamp->copy()->addDays(14)->format('d')
                    ], [
                        'from' => $timestamp->copy()->addDays(14),
                        'to' => $timestamp->copy()->addDays(21),
                        'name' => $timestamp->copy()->addDays(14)->format('d') . '-' . $timestamp->copy()->addDays(21)->format('d')
                    ], [
                        'from' => $timestamp->copy()->addDays(21),
                        'to' => $timestamp->copy()->addDays(28),
                        'name' => $timestamp->copy()->addDays(21)->format('d') . '-' . $timestamp->copy()->addDays(28)->format('d')
                    ], [
                        'from' => $timestamp->copy()->addDays(28),
                        'to' => $timestamp->copy()->addDays(35),
                        'name' => $timestamp->copy()->addDays(28)->format('d') . '-' . $timestamp->copy()->addDays(35)->format('d')
                    ]
                ]);
                break;
        }

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

        switch($period) {
            case 'hour':
                $timestamp = Carbon::create($datetime);
                $to = $timestamp->copy()->addHour();
                break;
            case 'day':
                $timestamp = Carbon::create($datetime)->startOf('day');
                $to = $timestamp->copy()->addDay();
                break;
            case 'week':
                $timestamp = Carbon::create($datetime)->startOf('day');
                $to = $timestamp->copy()->addWeek();
                break;
            case 'month':
                $timestamp = Carbon::create($datetime)->startOf('day');
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
