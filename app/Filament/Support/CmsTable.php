<?php

namespace App\Filament\Support;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CmsTable
{
    /**
     * Shared sort, active filter, edit + bulk-delete actions for CMS list tables.
     *
     * @param  array<int, \Filament\Tables\Filters\BaseFilter>|null  $filters
     */
    public static function defaults(Table $table, ?array $filters = null): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->filters($filters ?? [
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function sortOrderColumn(): TextColumn
    {
        return TextColumn::make('sort_order')
            ->label('#')
            ->sortable();
    }

    public static function activeColumn(): IconColumn
    {
        return IconColumn::make('is_active')
            ->label('Active')
            ->boolean();
    }

    public static function updatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
