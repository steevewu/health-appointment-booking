<?php

namespace App\Filament\Doctor\Resources;

use App\Filament\Doctor\Resources\AppointmentResource\Pages;
use App\Filament\Doctor\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Notifications\SteeveNotification;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use DB;
use Exception;
use Filament\Forms;
use Filament\Support\Colors\Color;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
        return 'heroicon-o-rectangle-stack';
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


                Tables\Columns\TextColumn::make('patient.fullname')
                    ->label(__('filament::resources.full_name', [
                        'model' => __('filament::resources.patients.label')
                    ]))
                    ->disableClick()
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
                    ->badge()
                    ->disableClick()
                    ->getStateUsing(
                        fn(Appointment $record) => match ($record->status) {
                            'pending' => __('filament::resources.appointments.pending'),
                            'confirmed' => __('filament::resources.appointments.confirmed'),
                            'canceled' => __('filament::resources.appointments.canceled'),
                            default => '-'
                        }
                    )
                    ->color(
                        fn(Appointment $record) => match ($record->status) {
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'canceled' => 'danger',
                            default => 'danger'
                        }
                    )
                    ->icon(
                        fn(Appointment $record) => match ($record->status) {
                            'pending' => 'heroicon-o-clock',
                            'confirmed' => 'heroicon-o-check',
                            'canceled' => 'heroicon-o-x-mark',
                            default => 'heroicon-o-question-mark-circle',
                        }
                    )
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status')
                    ->options(
                        [
                            'pending' => __('filament::resources.appointments.pending'),
                            'confirmed' => __('filament::resources.appointments.confirmed'),
                            'canceled' => __('filament::resources.appointments.canceled')
                        ]
                    )
            ])
            ->actions([

                // appointment confirmation
                Tables\Actions\Action::make('confirm')
                    ->label(__('filament::resources.appointments.confirm'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn(Appointment $record) => $record->status === 'pending')
                    ->action(
                        function (Appointment $record) {
                            try {
                                DB::transaction(
                                    function () use ($record) {
                                        $record->forceFill(
                                            [
                                                'status' => 'confirmed'
                                            ]
                                        );

                                        $record->workshift->appointments()
                                            ->where('id', '!=', $record->id)
                                            ->where('status', 'pending')
                                            ->update(
                                                [
                                                    'status' => 'canceled'
                                                ]
                                            );

                                        $treatment = new Treatment();

                                        $treatment->forceFill(
                                            [
                                                'appointment_id' => $record->id,
                                            ]
                                        );
                                        $treatment->save();
                                        $record->save();
                                    }
                                );

                                SteeveNotification::sendSuccessNotification();
                            } catch (Exception $e) {
                                SteeveNotification::sendFailedNotification();
                            }
                        }
                    )
                    ->successNotificationMessage(null)
                    ->failureNotificationMessage(null),


                // appointment cancellation
                Tables\Actions\Action::make('cancel')
                    ->label(__('filament::resources.appointments.cancel'))
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn(Appointment $record) => $record->status === 'pending')
                    ->action(
                        function (Appointment $record) {
                            try {
                                DB::transaction(
                                    function () use ($record) {
                                        $record->forceFill(
                                            [
                                                'status' => 'canceled'
                                            ]
                                        );
                                        $record->save();
                                    }
                                );

                                SteeveNotification::sendSuccessNotification();
                            } catch (Exception $e) {
                                SteeveNotification::sendFailedNotification(message: $e->getMessage());
                            }
                        }
                    )
                    ->requiresConfirmation()
                    ->successNotificationMessage(null)
                    ->failureNotificationMessage(null),

                // create treatment (only available for 'confirmed' appointment)
                Tables\Actions\Action::make('create-treatment')
                    ->icon('heroicon-o-newspaper')
                    ->label(__('filament::resources.appointments.treatments.create'))
                    ->modalHeading(
                        fn(Appointment $record) => __('filament::resources.appointments.treatments.heading', ['name' => $record->patient->fullname])
                    )
                    ->form(
                        [
                            Forms\Components\TextInput::make('fullname')
                                ->label(__('filament::resources.full_name', ['model' => __('filament::resources.patients.label')]))
                                ->disabled(),
                            Forms\Components\DatePicker::make('date')
                                ->label(__('filament::resources.appointments.treatments.date'))
                                ->disabled()
                                ->displayFormat('H:i d/m/Y')
                                ->native(false),
                            Forms\Components\TextInput::make('doctor')
                                ->label(__('filament::resources.appointments.treatments.doctor'))
                                ->disabled(),
                            Forms\Components\MarkdownEditor::make('notes')
                                ->nullable()
                                ->label(__('filament::resources.appointments.treatments.notes')),
                            Forms\Components\MarkdownEditor::make('medication')
                                ->nullable()
                                ->label(__('filament::resources.appointments.treatments.medication')),

                        ]
                    )
                    ->mountUsing(
                        function (Forms\ComponentContainer $form, Tables\Actions\Action $action, Appointment $record) {
                            $treatment = $record->treatment;


                            $form->fill(
                                [
                                    'fullname' => $record->patient->fullname,
                                    'date' => $record->workshift->event->start_at,
                                    'doctor' => $record->workshift->doctor->fullname,
                                    'notes' => $treatment->notes,
                                    'medication' => $treatment->medication
                                ]
                            );

                        }
                    )
                    ->action(
                        function (Appointment $record, array $data) {
                            try {

                                DB::transaction(
                                    function () use ($record, $data) {

                                        $treatment = $record->treatment;

                                        $treatment->fill(
                                            [
                                                'notes' => $data['notes'],
                                                'medication' => $data['medication']
                                            ]
                                        );

                                        $treatment->save();

                                    }
                                );

                                SteeveNotification::sendSuccessNotification();
                            } catch (Exception $e) {
                                SteeveNotification::sendFailedNotification(message: $e->getMessage());
                            }
                        }
                    )
                    ->modalAlignment('center')
                    ->modalWidth('xl')
                    ->visible(
                        fn(Appointment $record) => $record->status === 'confirmed'
                    )

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
        $doctor = auth()->user()->doctor;

        if (!$doctor)
            return parent::getEloquentQuery()->whereRaw('1 = 0');

        return parent::getEloquentQuery()
            ->whereHas('workshift', function (Builder $query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with('workshift.event')
            ->orderBy('created_at', 'desc');
    }
}
