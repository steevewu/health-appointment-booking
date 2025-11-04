<?php

namespace App\Filament\Officer\Resources\DoctorResource\Pages;

use App\Filament\Officer\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use App\Notifications\SteeveNotification;
use Date;
use DB;
use Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageDoctors extends ManageRecords
{
    protected static string $resource = DoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];

    }


    public function getTitle(): string|Htmlable
    {
        return __('filament::resources.doctors.title');
    }

    public function getHeading(): Htmlable|string
    {
        return __('filament::resources.doctors.title');
    }

}
