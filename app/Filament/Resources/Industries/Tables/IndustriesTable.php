<?php

namespace App\Filament\Resources\Industries\Tables;

use App\Filament\Support\CmsTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IndustriesTable
{
    public static function configure(Table $table): Table
    {
        return CmsTable::defaults($table)
            ->columns([
                CmsTable::sortOrderColumn(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('icon')
                    ->badge()
                    ->toggleable(),
                CmsTable::activeColumn(),
                CmsTable::updatedAtColumn(),
            ]);
    }
}
