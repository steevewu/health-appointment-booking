<?php

namespace App\Filament\Shared\Pages;

use App\Filament\Shared\Widgets\CalendarWidget;
use Filament\Pages\Page;

class ManageSchedule extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.shared.pages.manage-schedule';






    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['officer', 'scheduler']);
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
