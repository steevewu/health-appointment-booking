<?php

namespace App\Filament\Officer\Widgets;

use DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Forms;

class PatientChart extends ApexChartWidget
{
    protected static ?string $chartId = 'patientChart';
    protected int|string|array $columnSpan = 'full';

    protected static bool $isDiscovered = false;


    protected function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|null
    {
        return __('filament::charts.patient_chart_heading');
    }



    protected function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('start')
                ->label(__('filament::charts.filters.start_date'))
                ->default(now()->startOfYear())
                ->native(false)
                ->displayFormat('Y'),
            Forms\Components\DatePicker::make('end')
                ->label(__('filament::charts.filters.end_date'))
                ->default(now()->endOfYear())
                ->native(false)
                ->displayFormat('Y')

        ];
    }
    protected function getOptions(): array
    {

        $dateStart = $this->filterFormData['start'];
        $dateEnd = $this->filterFormData['end'];

        $rawEnrollmentData = DB::table('patients')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->select(
                DB::raw('YEAR(users.created_at) as year'),
                DB::raw('MONTH(users.created_at) as month'),
                DB::raw('count(patients.id) as count')
            )
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
                        // 'formatter' => fn (int $val) => number_format($val, 0),
                    ],
                ],
                'title' => [
                    'text' => __('filament::charts.hehe')
                ]
            ],
            'yaxis' => [
                'title' => [
                    'text' => __('filament::charts.hehe')
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
                'offsetY' => -5,
                'offsetX' => -5

            ],
        ];
    }
}
