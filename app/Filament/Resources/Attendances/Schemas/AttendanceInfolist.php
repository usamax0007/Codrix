<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User'),
                        TextEntry::make('user.email')
                            ->label('Email')
                            ->copyable(),
                        TextEntry::make('work_date')
                            ->label('Date')
                            ->date('M d, Y'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('check_in_at')
                            ->label('Check in')
                            ->dateTime('M d, Y h:i A')
                            ->placeholder('—'),
                        TextEntry::make('check_out_at')
                            ->label('Check out')
                            ->dateTime('M d, Y h:i A')
                            ->placeholder('—'),
                        TextEntry::make('worked_minutes')
                            ->label('Duration')
                            ->formatStateUsing(fn (?int $state, $record): string => $record->durationLabel()),
                    ]),
            ]);
    }
}
