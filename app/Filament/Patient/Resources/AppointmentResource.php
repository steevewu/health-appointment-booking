<?php

namespace App\Filament\Patient\Resources;

use App\Filament\Patient\Resources\AppointmentResource\Pages;
use App\Filament\Patient\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?int $navigationSort = 2;




    public static function getModelLabel(): string
    {
        return __('filament::resources.appointments.label');
    }


    public static function getPluralModelLabel(): string
    {
        return __('filament::resources.appointments.plural_label');
    }


    public static function getNavigationLabel(): string
    {
        return __('filament::resources.appointments.label');

    }


    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-calendar-days';
    }


    public static function getNavigationGroup(): ?string
    {
        return __('filament::resources.departments.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament::resources.id_label'))
                    ->weight('bold')
                    ->disabledClick(),


                Tables\Columns\TextColumn::make('workshift.doctor.fullname')
                    ->disableClick()
                    ->label(__('filament::resources.full_name', ['model' => __('filament::resources.doctors.label')]))
                    ->searchable(),


                Tables\Columns\TextColumn::make('workshift.event.start_at')
                    ->disableClick()
                    ->dateTime('H:i d/m/Y')
                    ->weight('bold')
                    ->label(__('filament::resources.appointments.start')),


                Tables\Columns\TextColumn::make('workshift.event.end_at')
                    ->disableClick()
                    ->weight('bold')
                    ->dateTime('H:i d/m/Y')
                    ->label(__(key: 'filament::resources.appointments.end')),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('H:i d/m/Y')
                    ->label(__('filament::resources.appointments.created_at'))
                    ->disableClick(),

                Tables\Columns\TextColumn::make('status')
                    ->label(
                        __('filament::resources.appointments.status')
                    )
                    ->badge()
                    ->disableClick()
                    ->getStateUsing(
                        fn(Model $record) => match ($record->status) {
                            'pending' => __('filament::resources.appointments.pending'),
                            'confirmed' => __('filament::resources.appointments.confirmed'),
                            'canceled' => __('filament::resources.appointments.canceled'),
                            default => '-'
                        }
                    )
                    ->color(
                        fn(Model $record) => match ($record->status) {
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'canceled' => 'danger',
                            default => 'danger'
                        }
                    )
                    ->icon(
                        fn(Model $record) => match ($record->status) {
                            'pending' => 'heroicon-o-clock',
                            'confirmed' => 'heroicon-o-check-circle',
                            'canceled' => 'heroicon-o-x-circle',
                            default => 'heroicon-o-question-mark-circle',
                        }
                    )


            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\Action::make('view-treatment')
                    ->label(__('filament::resources.appointments.treatments.view'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->visible(
                        fn(Model $record) => $record->status === 'confirmed'
                    )
                    ->infolist(
                        function (Infolist $infolist, Model $record) {
                            return
                                [
                                    Infolists\Components\TextEntry::make('patient.fullname')
                                        ->label(__('filament::resources.full_name', ['model' => __('filament::resources.patients.label')])),
                                    Infolists\Components\TextEntry::make('workshift.event.start_at')
                                        ->dateTime('d/m/Y')
                                        ->label(__('filament::resources.appointments.treatments.date')),
                                    Infolists\Components\TextEntry::make('workshift.doctor.fullname')
                                        ->label(__('filament::resources.appointments.treatments.doctor')),
                                    Infolists\Components\TextEntry::make('treatment.notes')
                                        ->label(__('filament::resources.appointments.treatments.notes'))
                                        ->default('-')
                                        ->markdown(),
                                    Infolists\Components\TextEntry::make('treatment.medication')
                                        ->label(__('filament::resources.appointments.treatments.medication'))
                                        ->default('-')
                                        ->markdown()
                                ];
                        }
                    )
                    ->modalHeading(
                        fn(Appointment $record) => __('filament::resources.appointments.treatments.heading', ['name' => $record->patient->fullname])
                    )
                    ->modalSubmitAction(false)
                    ->modalAlignment('center')
                    ->modalWidth('xl')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAppointments::route('/'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        $patient = auth()->user()->patient;

        if (!$patient)
            return parent::getEloquentQuery()->whereRaw('1 = 0');

        return parent::getEloquentQuery()
            ->where('patient_id', $patient->id)
            ->with('workshift.event')
            ->orderBy('created_at', 'desc');
    }
}
