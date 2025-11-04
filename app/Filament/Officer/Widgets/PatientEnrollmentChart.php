<?php

namespace App\Filament\Officer\Widgets;

use Carbon\Carbon;
use DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Forms;

class PatientEnrollmentChart extends ApexChartWidget
{
    protected static ?string $chartId = 'patientChart';
    protected int|string|array $columnSpan = 'full';

    protected static bool $isDiscovered = false;



    protected function getHeading(): ?string
    {
        return __('filament::charts.patients.enrollments.title');
    }

    protected function getDescription(): ?string
    {
        return 'An overview of some analytics.';
    }


    protected function getFormSchema(): array
    {
        $minYear = DB::table('users')->min(DB::raw('YEAR(created_at)')) ?? 2020;
        return [
            Forms\Components\TextInput::make('start')
                ->numeric()
                ->minValue($minYear)
                ->maxValue(now()->year)
                ->default(now()->subYearsNoOverflow(2)->year),
            Forms\Components\TextInput::make('end')
                ->numeric()
                ->minValue($minYear)
                ->maxValue(now()->year)
                ->default(now()->year)
                ->gte('start')

        ];
    }
    protected function getOptions(): array
    {


        if (!$this->readyToLoad) {
            return [];
        }




        $startDate = Carbon::create($this->filterFormData['start'])->startOfYear();
        $endDate = Carbon::create($this->filterFormData['end'])->endOfYear();




        $rawEnrollmentData = DB::table('patients')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->select(
                DB::raw('YEAR(users.created_at) as year'),
                DB::raw('MONTH(users.created_at) as month'),
                DB::raw('count(patients.id) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month')
            ->get();


        $groupedByYear = $rawEnrollmentData->groupBy('year');

        $series = [];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($groupedByYear as $year => $monthlyData) {
            $yearlyData = array_fill(0, 12, 0);

            foreach ($monthlyData as $data) {
                $yearlyData[$data->month - 1] = $data->count;
            }

            $series[] = [
                'name' => "{$year}",
                'data' => $yearlyData,
            ];
        }


        return [
            'chart' => [
                'type' => 'line',
                'height' => 500,
            ],
            'series' => $series,
            'dataLabels' => [
                'enabled' => true
            ],
            'xaxis' => [
                'categories' => $monthNames,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'title' => [
                    'text' => __('filament::charts.patients.enrollments.x-axis')
                ]
            ],
            'yaxis' => [
                'title' => [
                    'text' => __('filament::charts.patients.enrollments.y-axis')
                ],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            // 'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'straight',
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'left',
                'floating' => true,
                'offsetY' => -10,
                'offsetX' => -5

            ],
        ];
    }
}
