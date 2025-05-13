<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationGroup = 'Tournament Admin';
    
    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Expense Details')
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
                        Forms\Components\Select::make('team_id')
                            ->relationship('team', 'team_name')
                            ->searchable()
                            ->preload(),
                    ]),
                
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\FileUpload::make('receipt_path')
                            ->label('Receipt')
                            ->directory('receipts')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf']),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\TextColumn::make('team.team_name')
                    ->label('Team')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('receipt_path')
                    ->label('Receipt'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                    ]),
                Tables\Filters\SelectFilter::make('expense_type')
                    ->options([
                        'equipment' => 'Equipment',
                        'marketing' => 'Marketing',
                        'venue' => 'Venue',
                        'travel' => 'Travel',
                        'food' => 'Food',
                        'other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('team_id')
                    ->relationship('team', 'team_name')
                    ->label('Team'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
