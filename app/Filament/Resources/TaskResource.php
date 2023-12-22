<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\RelationManagers\TasksRelationManager;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('due')
                    ->minDate(now())
                    ->native(false),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(1000),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable(['name', 'email'])
                    ->preload()
                    ->hiddenOn(TasksRelationManager::class),
                Forms\Components\Select::make('priority')
                    ->options([
                        'none' => 'None',
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High'
                    ])
                    ->default('none')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('due')
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('priority')
                    ->options(['none', 'low', 'medium', 'high']),
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
