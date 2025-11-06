<?php

namespace App\Filament\Patient\Pages;

use App\Filament\Patient\Widgets\AppointmentDistributionArea;
use App\Filament\Patient\Widgets\AppointmentHeatmap;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AppointmentStatsPage extends Page
{

    protected static string $view = 'filament.patient.pages.appointment-stats-page';


    public function getHeading(): Htmlable|string
    {
        return __('filament::charts.appointments.title');
    }


    public function getTitle(): Htmlable|string
    {
        return __('filament::charts.appointments.title');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-chart-pie';
    }


    public static function getNavigationLabel(): string
    {
        return __('filament::charts.appointments.label');
    }


    public static function getNavigationGroup(): ?string
    {
        return __('filament::charts.appointments.group');
    }


    protected function getHeaderWidgets(): array{
        return [
            AppointmentDistributionArea::make(),
            AppointmentHeatmap::make()
        ];
    }
}
