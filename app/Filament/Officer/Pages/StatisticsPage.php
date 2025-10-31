<?php

namespace App\Filament\Officer\Pages;

use App\Filament\Officer\Widgets\PatientChart;
use Filament\Pages\Page;

class StatisticsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.officer.pages.statistics-page';


    public function getHeaderWidgets(): array{
        return [
            PatientChart::make(),
        ];
    }
}
