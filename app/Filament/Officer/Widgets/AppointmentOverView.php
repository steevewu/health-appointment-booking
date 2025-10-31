<?php

namespace App\Filament\Officer\Widgets;

use App\Models\Appointment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class AppointmentOverView extends BaseWidget
{

    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        $totalAppointments = Appointment::where('status', 'confirmed')->get()->count();


        $currentMonthAppointments = Appointment::where('status', 'confirmed')
            ->where('created_at', '>=', now()->startOfMonth())
            ->get()
            ->count();
        $previousMonthAppointments = Appointment::where('status', 'confirmed')
            ->whereBetween('created_at', [
                now()->subMonthNoOverflow()->startOfMonth(),
                now()->startOfMonth()

            ])
            ->count();

        $last7DaysAppointments = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();

            $count = Appointment::where('status', 'confirmed')
                ->where('created_at', '<', $date->copy()->startOfDay())
                ->get()
                ->count();
            $last7DaysAppointments[] = $count;
        }


        $difference = $currentMonthAppointments - $previousMonthAppointments;

        if ($previousMonthAppointments === 0) {
            $percentageChange = $currentMonthAppointments > 0 ? 100 : 0;
        } else {
            $percentageChange = round(($difference / $previousMonthAppointments) * 100, 1);
        }

        $color = $difference >= 0 ? 'success' : 'danger';
        $icon = $difference >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $description = abs($percentageChange) . '% ' . ($difference >= 0 ? __('filament::charts.increase') : __('filament::charts.decrease'));


        return [
            Stat::make(
                __('filament::charts.total_appointment_label'),
                number_format($totalAppointments)
            )
                ->description(
                    __('filament::charts.total_appointment_description')
                )
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->color('primary'),

            Stat::make(
                __('filament::charts.change_appointment_label'),
                number_format($currentMonthAppointments)
            )
                ->description($description . ' ' . __('filament::charts.change_appointment_description'))
                ->descriptionIcon($icon, IconPosition::Before)
                ->color($color)
                ->chart($last7DaysAppointments),

        ];
    }
}
