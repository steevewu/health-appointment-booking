<?php

namespace App\Filament\Officer\Pages;

use App\Filament\Officer\Widgets\PatientAgeChart;
use App\Filament\Officer\Widgets\PatientChart;
use App\Filament\Officer\Widgets\PatientEnrollmentChart;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class PatientStatsPage extends Page
{

    protected static string $view = 'filament.officer.pages.statistics-page';

    protected static ?int $navigationSort = 1;

    public function getTitle(): string|Htmlable
    {
        return __('filament::charts.patients.title');
    }

    public function getHeading(): Htmlable|string
    {
        return __('filament::charts.patients.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament::charts.patients.label');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('filament::charts.patients.group');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-presentation-chart-line';
    }


    public function getHeaderWidgets(): array
    {
        return [
            PatientEnrollmentChart::make(),
            PatientAgeChart::make()
        ];
    }
}
