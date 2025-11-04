<?php

namespace App\Filament\Officer\Widgets;

use DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PatientAgeChart extends ApexChartWidget
{
    protected static ?string $chartId = 'patientAgeChart';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isDiscovered = false;



    protected function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|null
    {
        return __('filament::charts.patients.ages.title');
    }



    protected function getOptions(): array
    {

        if (!$this->readyToLoad) {
            return [];
        }


        $data = DB::table('patients')
            ->selectRaw("
            COUNT(*) as count,
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 20 THEN '<20'
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 20 AND 34 THEN '20 - 34'
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 35 AND 44 THEN '35 - 44'
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 45 AND 54 THEN '45 - 54'
                    ELSE '55+'
                END as age_group
            ")
            ->groupBy('age_group')
            ->orderByRaw("FIELD(age_group, '<20', '20 - 34', '35 - 44', '45 - 54', '55+')")
            ->pluck('count', 'age_group');

        // dd($data);
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 500,
            ],
            'series' => $data->values()->toArray(),
            'labels' => $data->keys()->toArray()

        ];
    }
}
