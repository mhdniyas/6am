<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationGroup = 'Tournament Admin';
    
    protected static ?string $recordTitleAttribute = 'task_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Details')
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
                    ]),
                
                Forms\Components\Section::make('Related Entities')
                    ->schema([
                        Forms\Components\Select::make('team_id')
                            ->relationship('team', 'team_name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('sponsor_id')
                            ->relationship('sponsor', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                    
                Forms\Components\Section::make('Additional Information')
                    ->schema([
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
                Tables\Columns\TextColumn::make('task_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->due_date < now() && !$record->done ? 'danger' : null),
                Tables\Columns\IconColumn::make('done')
                    ->boolean()
                    ->action(
                        Tables\Actions\Action::make('toggle_done')
                            ->icon('heroicon-m-check-circle')
                            ->requiresConfirmation()
                            ->action(fn (Task $record) => $record->update(['done' => !$record->done]))
                    ),
                Tables\Columns\TextColumn::make('team.team_name')
                    ->label('Team')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sponsor.name')
                    ->label('Sponsor')
                    ->searchable(),
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
                Tables\Filters\Filter::make('completed')
                    ->query(fn (Builder $query): Builder => $query->where('done', true))
                    ->label('Completed tasks'),
                Tables\Filters\Filter::make('incomplete')
                    ->query(fn (Builder $query): Builder => $query->where('done', false))
                    ->label('Incomplete tasks'),
                Tables\Filters\Filter::make('overdue')
                    ->query(fn (Builder $query): Builder => $query->where('due_date', '<', now())->where('done', false))
                    ->label('Overdue tasks'),
                Tables\Filters\SelectFilter::make('team_id')
                    ->relationship('team', 'team_name')
                    ->label('Team'),
                Tables\Filters\SelectFilter::make('sponsor_id')
                    ->relationship('sponsor', 'name')
                    ->label('Sponsor'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('toggle_done')
                    ->icon(fn (Task $record) => $record->done ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Task $record) => $record->done ? 'danger' : 'success')
                    ->label(fn (Task $record) => $record->done ? 'Mark as Incomplete' : 'Mark as Complete')
                    ->action(fn (Task $record) => $record->update(['done' => !$record->done])),
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
}
