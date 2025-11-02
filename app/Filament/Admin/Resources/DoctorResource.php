<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DoctorResource\Pages;
use App\Filament\Admin\Resources\DoctorResource\RelationManagers;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;



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
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique('users', 'email')
                    ->label(__('filament::resources.email')),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->confirmed()
                    ->revealable()
                    ->required()
                    ->minLength(8)
                    ->maxLength(128)
                    ->label(__('password')),
                Forms\Components\TextInput::make('password_confirmation')
                    ->required()
                    ->password()
                    ->revealable()
                    ->dehydrated(false)
                    ->label(__('password_confirm')),
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
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('fullname')
                    ->label(__('filament::resources.full_name', ['model' => DoctorResource::getModelLabel()]))
                    ->disabledClick()
                    ->searchable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament::resources.email'))
                    ->disabledClick()
                    ->searchable()
                    ->color('info')
                    ->copyable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('filament::resources.change_pass'))
                    ->icon('heroicon-o-key')
                    ->form(
                        [

                            Forms\Components\TextInput::make('new_password')
                                ->label(__('filament::resources.new_pass'))
                                ->password()
                                ->confirmed()
                                ->nullable()
                                ->minLength(8)
                                ->maxLength(128)
                                ->revealable(),
                            Forms\Components\TextInput::make('new_password_confirmation')
                                ->label(__('filament::resources.new_pass_confirm'))
                                ->password()
                                ->nullable()
                                ->minLength(8)
                                ->maxLength(128)
                                ->revealable()

                        ]
                    )
                    ->using(function (array $data, Model $record) {
                        if (isset($data['new_password']) && !empty($data['new_password'])) {
                            $record->user->update(
                                [
                                    'password' => $data['new_password']
                                ]
                            );
                        }

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make(),
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
        ];
    }
}
