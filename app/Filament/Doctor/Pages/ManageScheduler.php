<?php

namespace App\Filament\Doctor\Pages;

use App\Filament\Doctor\Widgets\CalendarWidget;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ManageScheduler extends Page
{

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

    public function getHeading(): Htmlable|string
    {
        return __('filament::resources.schedule.title');
    }


    public function getTitle(): Htmlable|string
    {
        return __('filament::resources.schedule.title');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-calendar-days';
    }


    public static function getNavigationLabel(): string
    {
        return __('filament::resources.schedule.label');
    }


    public static function getNavigationGroup(): ?string
    {
        return __('filament::resources.schedulers.group');
    }
}
