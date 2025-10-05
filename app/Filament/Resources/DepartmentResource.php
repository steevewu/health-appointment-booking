<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Http;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


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
        return __('filament::resources.departments.navigation_label');
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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->minLength(10)
                    ->label(__('Tên khoa')),
                Forms\Components\TextInput::make('alias')
                    ->required()
                    ->maxLength(15)
                    ->minLength(2)
                    ->label(__('Tên viết tắt')),
                Forms\Components\MarkdownEditor::make('description')
                    ->label('Mô tả về khoa')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Tên khoa'),
                Tables\Columns\TextColumn::make('alias')
                    ->searchable()
                    ->label('Tên viết tắt')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('viewContent')
                    ->label('Xem mô tả')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('4xl') // Optional: Make the modal wider
                    ->fillForm(fn(Department $record): array => [
                        'description' => $record->desctiption,
                    ])
                    ->infolist([
                        TextEntry::make('description')
                            ->label('Mô tả về khoa')
                            ->markdown(),
                    ])
                    ->modalSubmitAction(false) // Hide the Save/Submit button
                    ->modalCancelActionLabel('Close'), // Rename the Cancel button

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
