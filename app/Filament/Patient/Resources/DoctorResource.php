<?php

namespace App\Filament\Patient\Resources;

use App\Filament\Patient\Resources\DoctorResource\Pages;
use App\Filament\Patient\Resources\DoctorResource\RelationManagers;
use App\Models\Department;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getModelLabel(): string
    {
        return __('filament::resources.doctors.label');
    }


    public static function getPluralModelLabel(): string
    {
        return __('filament::resources.doctors.plural_label');
    }
    public static function getNavigationLabel(): string
    {
        return __('filament::resources.navigation_label', ['model' => DoctorResource::getModelLabel()]);
    }
    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-user-group';
    }


    public static function getNavigationGroup(): ?string
    {
        return __('filament::resources.doctors.group');
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
                    ->disableClick()
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fullname')
                    ->label(__('filament::resources.fullname'))
                    // ->disableClick()
                    ->searchable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament::resources.email'))
                    ->color('primary')
                    ->copyable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament::resources.doctors.belongs_depart'))
                    ->disableClick()
                    ->searchable()
            ])
            ->filters([
                //

                Tables\Filters\SelectFilter::make('depart_id')
                    ->options(
                        fn() => Department::all()->pluck('name', 'id')->all()
                    )
                    ->searchable()
                    ->placeholder(__('filament::resources.departments.place_holder'))
                    ->label(__('filament::resources.departments.label'))
            ])
            ->actions([
                Tables\Actions\Action::make('view-cv')
                    ->label('Xem CV')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('4xl') // Optional: Make the modal wider
                    ->fillForm(fn(Doctor $record): array => [
                        'description' => $record->fullname,
                    ])
                    ->infolist([
                        TextEntry::make('description')
                            ->hiddenLabel()
                            ->markdown()
                    ])
                    ->modalSubmitAction(false) // Hide the Save/Submit button
                    ->modalCancelActionLabel('Close')
                    ->modalAlignment('center'),

                Tables\Actions\Action::make('book')
                    ->url(fn($record) => static::getUrl('book', ['record' => $record]))
                    ->openUrlInNewTab()
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
            'index' => Pages\ManageDoctors::route('/'),
            'book' => Pages\ManageSchedule::route('/{record}/book')
        ];
    }
}