<?php

namespace App\Filament\Resources\Attendances\Tables;

use App\Enums\UserRole;
use App\Models\Attendance;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('work_date', 'desc')
            ->columns([
                TextColumn::make('work_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('check_in_at')
                    ->label('Check in')
                    ->dateTime('h:i A')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('check_out_at')
                    ->label('Check out')
                    ->dateTime('h:i A')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('worked_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn (?int $state, Attendance $record): string => $record->durationLabel())
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Select user')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('role', UserRole::User)
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder('Select a user'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(1)
            ->deferFilters(false)
            ->columnManager(false)
            ->emptyStateHeading(function ($livewire): string {
                return blank(data_get($livewire->tableFilters, 'user_id.value'))
                    ? 'Select a user'
                    : 'No attendance records';
            })
            ->emptyStateDescription(function ($livewire): string {
                return blank(data_get($livewire->tableFilters, 'user_id.value'))
                    ? 'Choose a user to view their attendance records.'
                    : 'This user has no attendance records yet.';
            })
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
