<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Filament\Support\CmsTable;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return CmsTable::defaults($table)
            ->columns([
                ImageColumn::make('image')
                    ->getStateUsing(fn ($record) => $record->imageUrl())
                    ->circular()
                    ->height(40),
                CmsTable::sortOrderColumn(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('text')
                    ->limit(40)
                    ->wrap(),
                CmsTable::activeColumn(),
                CmsTable::updatedAtColumn(),
            ]);
    }
}
