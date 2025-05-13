<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('expense_type')
                    ->options([
                        'equipment' => 'Equipment',
                        'marketing' => 'Marketing',
                        'venue' => 'Venue',
                        'travel' => 'Travel',
                        'food' => 'Food',
                        'other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\FileUpload::make('receipt_path')
                    ->directory('receipts')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf']),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('expense_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'equipment' => 'info',
                        'marketing' => 'warning',
                        'venue' => 'danger',
                        'travel' => 'success',
                        'food' => 'gray',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('receipt_path')
                    ->label('Receipt'),
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
