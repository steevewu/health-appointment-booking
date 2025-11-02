<?php

namespace App\Filament\Officer\Widgets;

use DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Forms;


class AppointmentHeatmap extends ApexChartWidget
{
    protected static ?string $chartId = 'appointmentHeatmap';



    protected int|string|array $columnSpan = 'full';

    protected static bool $isDiscovered = false;



    protected function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|null
    {
        return __('filament::charts.heeh');
    }


    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('year')
                ->label(__('filament::charts.hahe'))
                ->options(
                    function () {
                        $minYear = DB::table('events')->min(DB::raw('YEAR(start_at)')) ?? 2020;
                        $maxYear = now()->year;

                        $intYears = range($minYear, $maxYear);
                        $yearMap = array_combine(
                            $intYears,
                            array_map('strval', $intYears)
                        );
                        return $yearMap;
                    }
                )
                ->default(now()->year)
                ->live()
                ->afterStateUpdated(function () {
                    $this->updateOptions();
                }),
        ];
    }



    protected function getOptions(): array
    {

        if (!$this->readyToLoad)
            return [];


        $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // $rawData = DB::table('appointments')
        //     ->selectRaw('MONTH(created_at) as month, WEEKDAY(created_at) as weekday, COUNT(*) as total')
        //     ->whereIn('status', ['pending', 'confirmed'])
        //     ->groupBy('month', 'weekday')
        //     ->orderBy('month')
        //     ->orderBy('weekday')
        //     ->get();

        $yearFilter = $this->filterFormData['year'];

        $rawData = DB::table('appointments AS a')
            ->join('workshifts AS w', 'a.workshift_id', '=', 'w.id')
            ->join('events AS e', 'w.event_id', '=', 'e.id')
            ->selectRaw("
                WEEKDAY(e.start_at) AS weekday,     
                MONTH(e.start_at) AS month,
                COUNT(a.id) AS total
            ")
            ->whereYear('e.start_at', $yearFilter)
            ->whereIn('a.status', ['pending', 'confirmed'])
            ->groupBy('month', 'weekday')
            ->orderBy('month')
            ->orderBy('weekday')
            ->get();

        // dd($rawData);

        $heatmap = [];
        foreach ($weekDays as $i => $label) {
            $heatmap[$i] = [
                'name' => $label,
                'data' => array_map(function ($month) {
                    return ['x' => $month, 'y' => 0];
                }, $monthNames),
            ];
        }

        foreach ($rawData as $row) {
            $heatmap[$row->weekday]['data'][$row->month - 1]['y'] = $row->total;
        }






        return [
            'chart' => [
                'type' => 'heatmap',
                'height' => 500,
            ],
            'series' => $heatmap,
            'xaxis' => [
                'type' => 'category',
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'title' => [
                'text' => "{$yearFilter}"
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'colors' => ['#008FFB'],
        ];
    }
}
