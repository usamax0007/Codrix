<?php

namespace App\Filament\Resources\WhyChooseUs\Tables;

use App\Filament\Support\CmsTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WhyChooseUsTable
{
    public static function configure(Table $table): Table
    {
        return CmsTable::defaults($table)
            ->columns([
                CmsTable::sortOrderColumn(),
                TextColumn::make('title')
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
