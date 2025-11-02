<?php

namespace App\Filament\Officer\Resources;

use App\Filament\Officer\Resources\DoctorResource\Pages;
use App\Filament\Officer\Resources\DoctorResource\RelationManagers;
use App\Models\Department;
use App\Models\Doctor;
use App\Services\AddressResolver;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Http;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Jacobtims\InlineDateTimePicker\Forms\Components\InlineDateTimePicker;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;
    protected static ?int $navigationSort = 2;



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

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->alignCenter()
                    ->disabledClick()
                    ->weight('bold')
                    ->label(__('filament::resources.id_label')),
                Tables\Columns\TextColumn::make('fullname')
                    ->searchable()
                    ->sortable()
                    ->disabledClick()
                    ->label(__('filament::resources.fullname')),
                    Tables\Columns\TextColumn::make('user.email')
                    ->searchable()
                    ->copyable()
                    ->alignCenter()
                    ->color('info')
                    ->label(__('filament::resources.email')),
                Tables\Columns\TextColumn::make('department.name')
                    ->searchable()
                    // ->color('primary')
                    ->label(__('filament::resources.doctors.belongs_depart'))
                    ->default('-')

            ])



            ->filters([
                //
                Tables\Filters\SelectFilter::make('depart_id')
                    ->options(
                        fn() => Department::all()->pluck('name', 'id')->all()
                    )
                    ->placeholder(__('filament::resources.departments.place_holder'))
                    ->label(__('filament::resources.departments.label'))
            ])



            ->actions([



                Tables\Actions\EditAction::make()
                    ->label(__('filament::resources.doctors.assign'))
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->form(
                        [
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->disabled()
                                ->label(__('filament::resources.email')),
                            Forms\Components\TextInput::make('fullname')
                                ->disabled()
                                ->label(__('filament::resources.fullname')),
                            Forms\Components\TextInput::make('address')
                                ->disabled()
                                ->label(__('filament::resources.address')),
                            Forms\Components\Select::make('depart_id')
                                ->options(
                                    function () {
                                        return Department::all()->pluck('name', 'id')->toArray();
                                    }
                                )
                                ->label(__('filament::resources.departments.label'))
                                ->reactive()
                                ->placeholder(__('filament::resources.departments.place_holder'))
                                ->searchable(),
                        ]
                    )
                    ->mutateRecordDataUsing(
                        function (array $data, Model $record) {

                            $data['address'] = join(', ', AddressResolver::resolveName($data['address']));

                            $data['email'] = $record->user->email;

                            return $data;
                        }
                    )
                    ->modalWidth('xl'),




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
