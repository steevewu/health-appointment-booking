<?php

namespace App\Filament\Officer\Pages;

use App\Filament\Officer\Widgets\AppointmentDistributionChart;
use App\Filament\Officer\Widgets\AppointmentHeatmap;
use DB;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AppointmentStatsPage extends Page
{

    protected static string $view = 'filament.officer.pages.appointment-stats-page';


    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('filament::charts.appointments.label');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('filament::charts.appointments.group');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-chart-pie';
    }





    public function getHeaderWidgets(): array
    {
        return [

            AppointmentDistributionChart::make(),
            AppointmentHeatmap::make()
        ];
    }

}
