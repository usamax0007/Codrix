<?php

namespace App\Filament\Pages;

use App\Models\ProcessSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageProcessSettings extends ManageSettingsPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Pages';

    protected static ?string $navigationLabel = 'Process';

    protected static ?string $title = 'Process';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?int $navigationSort = 4;

    protected function settingsModel(): string
    {
        return ProcessSetting::class;
    }

    protected function savedNotificationTitle(): string
    {
        return 'Process page saved';
    }

    public function form(Schema $schema): Schema
    {
        $stepSections = [];

        for ($i = 1; $i <= 6; $i++) {
            $stepSections[] = Section::make("Step {$i}")
                ->columns(2)
                ->schema([
                    TextInput::make("step_{$i}_number")
                        ->label('Number')
                        ->maxLength(10),
                    TextInput::make("step_{$i}_title")
                        ->label('Title')
                        ->maxLength(255),
                    Textarea::make("step_{$i}_description")
                        ->label('Description')
                        ->rows(3)
                        ->columnSpanFull(),
                ]);
        }

        return $schema
            ->components([
                Form::make([
                    Section::make('Page Hero')
                        ->columns(2)
                        ->schema([
                            TextInput::make('hero_badge')->label('Badge')->maxLength(100),
                            TextInput::make('hero_title')->label('Title')->helperText('HTML allowed for gradient spans.')->maxLength(255),
                            Textarea::make('hero_subtitle')->label('Subtitle')->rows(3)->columnSpanFull(),
                        ]),
                    Section::make('Home Section')
                        ->columns(2)
                        ->schema([
                            TextInput::make('section_badge')->label('Section Badge')->maxLength(100),
                            TextInput::make('section_title')->label('Section Title')->helperText('HTML allowed for gradient spans.')->maxLength(255),
                            Textarea::make('section_subtitle')->label('Section Subtitle')->rows(2)->columnSpanFull(),
                        ]),
                    ...$stepSections,
                    Section::make('Bottom Content')
                        ->schema([
                            TextInput::make('footer_title')->label('Title')->maxLength(255),
                            Textarea::make('footer_content_1')->label('Content 1')->rows(4),
                            Textarea::make('footer_content_2')->label('Content 2')->rows(4),
                        ]),
                    Section::make('SEO')
                        ->schema([
                            TextInput::make('meta_title')->label('Meta Title')->maxLength(255),
                            Textarea::make('meta_description')->label('Meta Description')->rows(3),
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
