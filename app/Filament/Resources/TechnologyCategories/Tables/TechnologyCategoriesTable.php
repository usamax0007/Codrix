<?php

namespace App\Filament\Resources\TechnologyCategories\Tables;

use App\Filament\Support\CmsTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TechnologyCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return CmsTable::defaults($table)
            ->columns([
                CmsTable::sortOrderColumn(),
                TextColumn::make('name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('items')
                    ->label('Technologies')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->limit(50)
                    ->wrap(),
                CmsTable::activeColumn(),
                CmsTable::updatedAtColumn(),
            ]);
    }
}
