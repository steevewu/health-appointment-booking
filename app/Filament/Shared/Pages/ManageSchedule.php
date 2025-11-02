<?php

namespace App\Filament\Shared\Pages;

use App\Filament\Shared\Widgets\CalendarWidget;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ManageSchedule extends Page
{
    protected static string $view = 'filament.shared.pages.manage-schedule';




    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('filament::resources.schedule.label');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('filament::resources.schedule.group');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-calendar-days';
    }

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


}
