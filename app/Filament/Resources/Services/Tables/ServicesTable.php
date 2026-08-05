<?php

namespace App\Filament\Resources\Services\Tables;

use App\Filament\Support\CmsTable;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return CmsTable::defaults($table, [
            TernaryFilter::make('is_popular')->label('Popular'),
            TernaryFilter::make('is_active')->label('Active'),
        ])
            ->columns([
                CmsTable::sortOrderColumn(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_popular')
                    ->label('Popular')
                    ->boolean(),
                CmsTable::activeColumn(),
                CmsTable::updatedAtColumn(),
            ]);
    }
}
