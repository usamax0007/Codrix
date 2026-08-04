<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        TextEntry::make('subject')
                            ->columnSpanFull(),
                        TextEntry::make('message')
                            ->columnSpanFull()
                            ->prose(),
                        TextEntry::make('created_at')
                            ->label('Received at')
                            ->dateTime('M d, Y H:i'),
                    ]),
            ]);
    }
}
