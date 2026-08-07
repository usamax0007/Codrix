<?php

namespace App\Filament\Pages;

use App\Models\AttendanceSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageAttendanceSettings extends ManageSettingsPage
{
    protected static string|UnitEnum|null $navigationGroup = 'User';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Attendance Settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?int $navigationSort = 97;

    protected function settingsModel(): string
    {
        return AttendanceSetting::class;
    }

    protected function savedNotificationTitle(): string
    {
        return 'Attendance settings saved';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Working schedule')
                        ->description('Used to mark late check-ins and absences.')
                        ->columns(2)
                        ->schema([
                            Select::make('working_days')
                                ->label('Working Days')
                                ->multiple()
                                ->options(AttendanceSetting::weekdayOptions())
                                ->required()
                                ->native(false)
                                ->columnSpanFull(),
                            TimePicker::make('work_from')
                                ->label('Working Hours From')
                                ->seconds(false)
                                ->required()
                                ->native(false),
                            TimePicker::make('work_to')
                                ->label('Working Hours To')
                                ->seconds(false)
                                ->required()
                                ->native(false)
                                ->after('work_from'),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save changes')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }
}
