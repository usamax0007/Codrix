<?php

namespace App\Filament\Resources\Faqs\Tables;

use App\Filament\Support\CmsTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return CmsTable::defaults($table)
            ->columns([
                CmsTable::sortOrderColumn(),
                TextColumn::make('question')
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                CmsTable::activeColumn(),
                CmsTable::updatedAtColumn(),
            ]);
    }
}
