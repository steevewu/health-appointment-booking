<?php

namespace App\Filament\Patient\Resources;

use App\Filament\Patient\Resources\AppointmentResource\Pages;
use App\Filament\Patient\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
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


    public static function getModelLabel(): string
    {
        return __('filament::resources.departments.label');
    }


    public static function getPluralModelLabel(): string
    {
        return __('filament::resources.departments.plural_label');
    }


    public static function getNavigationLabel(): string
    {
        return __('filament::resources.navigation_label', ['model' => AppointmentResource::getModelLabel()]);
    }


    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-user-group';
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
                    ->label(__('filament::resources.'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('workshift.event.start_at')
                    ->disableClick()
                    ->dateTime('d/m/Y H:i')
                    ->label(__('filament::resources.appointments.start')),
                Tables\Columns\TextColumn::make('workshift.event.end_at')
                    ->disableClick()
                    ->dateTime('d/m/Y H:i')
                    ->label(__(key: 'filament::resources.appointments.end')),
                Tables\Columns\TextColumn::make('status')
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
                ->label(__('filament::resources.'))
                ->icon('heroicon-o-clipboard-document-list')
                ->visible(
                    fn(Model $record) => $record->status === 'confirmed'
                )
                ->infolist(
                    [
                        Infolists\Components\TextEntry::make('treatment.notes')
                        ->label(__('filament::resources.'))
                        ->default(__('filament::resources.'))
                        ->markdown(),
                        Infolists\Components\TextEntry::make('treatment.medication')
                        ->label(__('filament::resources.'))
                        ->default(__('filament::resources.'))
                        ->markdown()
                    ]
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
            ->with('workshift.event');
    }
}
