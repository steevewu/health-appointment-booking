<?php

namespace App\Filament\Patient\Resources\DoctorResource\Pages;

use App\Filament\Patient\Resources\DoctorResource;
use App\Filament\Patient\Widgets\CalendarWidget;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;



class ManageSchedule extends Page
{
    use InteractsWithRecord;
    protected static string $resource = DoctorResource::class;

    protected static string $view = 'filament.patient.resources.doctor-resource.pages.manage-schedule';

    public function mount(int|string $record)
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::make(
                [
                    'doctor_id' => $this->record->id
                ]
            )
        ];
    }


    public function getHeading(): string
    {
        return __('filament::resources.workshifts.title', ['name' => $this->record->fullname]);
    }
    public function getTitle(): string
    {
        return __('filament::resources.workshifts.title', ['name' => $this->record->fullname]);
    }



}
