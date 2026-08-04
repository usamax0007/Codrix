<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Service;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') !== Str::slug((string) $old)) {
                            return;
                        }

                        $set('slug', Str::slug((string) $state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Service::class, 'slug', ignoreRecord: true),
                TextInput::make('icon')
                    ->maxLength(50)
                    ->helperText('Optional icon key, e.g. ai, laravel, vue.'),
                Textarea::make('summary')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('what')
                    ->label('What it is')
                    ->rows(4)
                    ->columnSpanFull(),
                Repeater::make('benefits')
                    ->simple(TextInput::make('benefit')->required())
                    ->default([])
                    ->columnSpanFull(),
                TagsInput::make('technologies')
                    ->placeholder('Add technology')
                    ->columnSpanFull(),
                Textarea::make('why')
                    ->label('Why choose us for this')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_popular')
                    ->label('Popular')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
