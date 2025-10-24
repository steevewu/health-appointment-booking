<?php

namespace App\Filament\Patient\Resources\DoctorResource\Pages;

use App\Filament\Patient\Resources\DoctorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDoctors extends ManageRecords
{
    protected static string $resource = DoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
