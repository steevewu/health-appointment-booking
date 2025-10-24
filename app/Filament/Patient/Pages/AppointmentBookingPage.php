<?php

namespace App\Filament\Patient\Pages;

use App\Filament\Patient\Widgets\CalendarWidget;
use Filament\Pages\Page;

class AppointmentBookingPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.patient.pages.appointment-booking-page';



    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['patient']);
    }



    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::make(
                [
                    'doctor_id' => 1
                ]
            )
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
