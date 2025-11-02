<?php

namespace App\Filament\Officer\Resources;

use App\Filament\Officer\Resources\DepartmentResource\Pages;
use App\Filament\Officer\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?int $navigationSort = 1;




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
        return __('filament::resources.departments.label');
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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->minLength(10)
                    ->label(__('filament::resources.name', ['model' => DepartmentResource::getModelLabel()])),
                Forms\Components\TextInput::make('alias')
                    ->required()
                    ->maxLength(15)
                    ->minLength(2)
                    ->label(__('filament::resources.alias', ['model' => DepartmentResource::getModelLabel()])),
                Forms\Components\MarkdownEditor::make('description')
                    ->label(__('filament::resources.description', ['model' => DepartmentResource::getModelLabel()]))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id')
                    ->disableClick()
                    ->weight('bold')
                    ->alignCenter()
                    ->label(__('filament::resources.id_label')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->disableClick()
                    ->label(__('filament::resources.name', ['model' => DepartmentResource::getModelLabel()])),
                Tables\Columns\TextColumn::make('alias')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary')
                    ->label(__('filament::resources.alias', ['model' => DepartmentResource::getModelLabel()]))
                    ->disableClick(),
                Tables\Columns\TextColumn::make('doctors_count')
                    ->counts('doctors')
                    ->alignCenter()
                    ->disableClick()
                    ->color('info')
                    ->label(__('filament::resources.departments.working_doctors'))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('viewDescription')
                    ->label(__('filament::resources.departments.description_view'))
                    ->icon('heroicon-o-eye')
                    ->color(color: \Filament\Support\Colors\Color::hex('#BDE3C3'))
                    ->modalWidth('4xl')
                    ->fillForm(fn(Department $record): array => [
                        'description' => $record->desctiption,
                    ])
                    ->infolist([
                        TextEntry::make('description')
                            ->label(__('filament::resources.description', ['model' => DepartmentResource::getModelLabel()]))
                            ->markdown(),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalAlignment('center'),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageDepartments::route('/'),
        ];
    }
}
