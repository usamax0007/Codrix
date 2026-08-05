<?php

namespace App\Filament\Resources\Portfolios\Tables;

use App\Filament\Support\CmsTable;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PortfoliosTable
{
    public static function configure(Table $table): Table
    {
        return CmsTable::defaults($table)
            ->columns([
                ImageColumn::make('image')
                    ->getStateUsing(fn ($record) => $record->imageUrl())
                    ->height(40),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                CmsTable::sortOrderColumn(),
                CmsTable::activeColumn(),
                CmsTable::updatedAtColumn(),
            ]);
    }
}
