<?php

namespace App\Filament\Officer\Widgets;

use App\Models\Patient;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class PatientOverView extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $totalPatients = Patient::count();
        $currentMonthPatients = Patient::whereHas(
            'user',
            function (Builder $query) {
                $query->where('created_at', '>=', now()->startOfMonth());
            }
        )->count();


        $previousMonthPatients = Patient::whereHas(
            'user',
            function (Builder $query) {
                $query->whereBetween('created_at', [
                    now()->subMonthNoOverflow()->startOfMonth(),
                    now()->startOfMonth()
                ]);
            }
        )->count();


        $last7DaysPatients = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();

            $count = Patient::whereHas(
                'user',
                function (Builder $query) use ($date) {
                    $query->where('created_at', '<', $date->copy()->addDay());
                }
            )->count();
            $last7DaysPatients[] = $count;
        }


        $difference = $currentMonthPatients - $previousMonthPatients;

        if ($previousMonthPatients === 0) {
            $percentageChange = $currentMonthPatients > 0 ? 100 : 0;
        } else {
            $percentageChange = round(($difference / $previousMonthPatients) * 100, 1);
        }

        $color = $difference >= 0 ? 'success' : 'danger';
        $icon = $difference >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $description = abs($percentageChange) . '% ' . ($difference >= 0 ? __('filament::charts.increase') : __('filament::charts.decrease'));


        return [
            Stat::make(
                __('filament::charts.total_patient_label'),
                number_format($totalPatients)
            )
                ->description(
                    __('filament::charts.total_patient_description')
                )
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->color('primary'),

            Stat::make(
                __('filament::charts.change_patient_label'),
                number_format($currentMonthPatients)
            )
                ->description($description . ' ' . __('filament::charts.change_patient_description'))
                ->descriptionIcon($icon, IconPosition::Before)
                ->color($color)
                ->chart($last7DaysPatients),

        ];
    }
}
