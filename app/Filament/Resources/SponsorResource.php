<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SponsorResource\Pages;
use App\Filament\Resources\SponsorResource\RelationManagers;
use App\Models\Sponsor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SponsorResource extends Resource
{
    protected static ?string $model = Sponsor::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationGroup = 'Tournament Admin';
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sponsor Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->options([
                                'food' => 'Food',
                                'venue' => 'Venue',
                                'equipment' => 'Equipment',
                                'transportation' => 'Transportation',
                                'marketing' => 'Marketing',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('contact_person')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                    ]),
                    
                Forms\Components\Section::make('Financial Information')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('committed_amount')
                                    ->label('Committed Amount ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),
                                Forms\Components\TextInput::make('paid_amount')
                                    ->label('Paid Amount ($)')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->directory('sponsor-logos'),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('committed_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('getBalanceAttribute')
                    ->label('Balance')
                    ->money('USD')
                    ->getStateUsing(fn (Sponsor $record): float => $record->getBalanceAttribute())
                    ->color(fn (Sponsor $record): string => $record->getBalanceAttribute() > 0 ? 'warning' : 'success'),
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->circular(),
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
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'food' => 'Food',
                        'venue' => 'Venue',
                        'equipment' => 'Equipment',
                        'transportation' => 'Transportation',
                        'marketing' => 'Marketing',
                        'other' => 'Other',
                    ]),
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
            RelationManagers\SponsorTasksRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSponsors::route('/'),
            'create' => Pages\CreateSponsor::route('/create'),
            'edit' => Pages\EditSponsor::route('/{record}/edit'),
        ];
    }
}
