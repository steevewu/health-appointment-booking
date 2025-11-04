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

    protected static ?int $navigationSort = 1;


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
        return __('filament::resources.doctors.label');

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
                    ->alignCenter()
                    ->disableClick()
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fullname')
                    ->label(__('filament::resources.fullname'))
                    ->disableClick()
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament::resources.doctors.belongs_depart'))
                    ->disableClick()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament::resources.email'))
                    ->color('info')
                    ->copyable(),
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

                // view doctor's resume
                Tables\Actions\Action::make('view-cv')
                    ->label(__('filament::resources.doctors.view_cv'))
                    ->icon('heroicon-o-eye')
                    ->modalWidth('2xl')
                    ->fillForm(fn(Doctor $record): array => [
                        'description' => $record->fullname,
                    ])
                    ->infolist([
                        TextEntry::make('description')
                            ->hiddenLabel()
                            ->markdown()
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalAlignment('center'),

                // appointment booking action
                Tables\Actions\Action::make('book')
                    ->color('success')
                    ->icon('heroicon-o-chat-bubble-left-right')
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