<?php

namespace App\Filament\Patient\Widgets;

use DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Forms;

class AppointmentDistributionArea extends ApexChartWidget
{
    protected static ?string $chartId = 'appointmentDistributionArea';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isDiscovered = false;

    protected function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|null
    {
        return __('filament::charts.appointments.distribution.title');
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
        if (!$this->readyToLoad) {
            return [];
        }

        $yearFilter = $this->filterFormData['year'];

        $appointmentCounts = DB::table('departments')
            ->select([
                'departments.name',
                DB::raw('COUNT(appointments.id) as total_appointments'),
            ])
            ->leftJoin('doctors', 'doctors.depart_id', '=', 'departments.id')
            ->leftJoin('workshifts', 'workshifts.doctor_id', '=', 'doctors.id')
            ->leftJoin('appointments', 'appointments.workshift_id', '=', 'workshifts.id')
            ->leftJoin('events', 'workshifts.event_id', '=', 'events.id')
            ->whereYear('events.start_at', $yearFilter)
            ->where('appointments.patient_id', '=', auth()->user()->patient->id)
            ->groupBy('departments.id', 'departments.name')
            ->get();


        return [
            'chart' => [
                'type' => 'polarArea',
                'height' => 500,
            ],
            'series' => $appointmentCounts->pluck('total_appointments'),
            'labels' => $appointmentCounts->pluck('name')->toArray(),
            'legend' => [
                'labels' => [
                    'colors' => '#9ca3af',
                    'fontWeight' => 600,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
            'title' => [
                'text' => "{$yearFilter}",
                'align' => 'left'
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
        ];
    }



}
