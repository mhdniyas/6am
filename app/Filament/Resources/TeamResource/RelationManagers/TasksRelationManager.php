<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('task_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('assigned_to')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('due_date')
                    ->required(),
                Forms\Components\Toggle::make('done')
                    ->default(false),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('task_name')
            ->columns([
                Tables\Columns\TextColumn::make('task_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\CheckboxColumn::make('done')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
