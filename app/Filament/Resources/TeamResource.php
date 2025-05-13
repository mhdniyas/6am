<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Tournament Admin';
    
    protected static ?string $recordTitleAttribute = 'team_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Team Information')
                    ->schema([
                        Forms\Components\TextInput::make('team_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_person')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('owner')
                            ->maxLength(255),
                    ]),
                
                Forms\Components\Section::make('Financial Information')
                    ->schema([
                        Forms\Components\TextInput::make('cash_paid')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
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
                Tables\Columns\TextColumn::make('team_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cash_paid')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('getTotalDueAttribute')
                    ->label('Total Due')
                    ->money('USD')
                    ->getStateUsing(fn (Team $record): float => $record->getTotalDueAttribute()),
                Tables\Columns\TextColumn::make('getBalanceAttribute')
                    ->label('Balance')
                    ->money('USD')
                    ->getStateUsing(fn (Team $record): float => $record->getBalanceAttribute())
                    ->color(fn (Team $record): string => $record->getBalanceAttribute() > 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
            RelationManagers\ExpensesRelationManager::make(),
            RelationManagers\TasksRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
