<?php

namespace App\Filament\Officer\Resources\DepartmentResource\Pages;

use App\Filament\Officer\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageDepartments extends ManageRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTitle(): string|Htmlable
    {
        return __('filament::resources.departments.title');
    }

    public function getHeading(): Htmlable|string
    {
        return __('filament::resources.departments.title');
    }



}
