<?php

namespace App\Filament\Doctor\Resources\AppointmentResource\Pages;

use App\Filament\Doctor\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageAppointments extends ManageRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }


    public function getTitle(): string|Htmlable
    {
        return __('filament::resources.appointments.title');
    }

    public function getHeading(): Htmlable|string
    {
        return __('filament::resources.appointments.title');
    }
}
