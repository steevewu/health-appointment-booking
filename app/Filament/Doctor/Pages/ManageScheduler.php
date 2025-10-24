<?php

namespace App\Filament\Doctor\Pages;

use App\Filament\Doctor\Widgets\CalendarWidget;
use Filament\Pages\Page;

class ManageScheduler extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.doctor.pages.manage-scheduler';



    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['doctor']);
    }



    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::make()
        ];
    }


    public static function getNavigationLabel(): string
    {
        return __('Custom Navigation Label');
    }


    public static function getNavigationGroup(): ?string
    {
        return __('filament::resources.schedulers.group');
    }
}
