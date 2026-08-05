<?php

namespace App\Filament\Pages;

use App\Models\AboutSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageAboutSettings extends ManageSettingsPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Pages';

    protected static ?string $navigationLabel = 'About';

    protected static ?string $title = 'About';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?int $navigationSort = 3;

    protected function settingsModel(): string
    {
        return AboutSetting::class;
    }

    protected function savedNotificationTitle(): string
    {
        return 'About page saved';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Page Hero')
                        ->columns(2)
                        ->schema([
                            TextInput::make('hero_badge')
                                ->label('Badge')
                                ->maxLength(100),
                            TextInput::make('hero_title')
                                ->label('Title')
                                ->maxLength(255),
                            Textarea::make('hero_subtitle')
                                ->label('Subtitle')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    Section::make('About Section')
                        ->columns(2)
                        ->schema([
                            TextInput::make('section_badge')
                                ->label('Section Badge')
                                ->maxLength(100),
                            TextInput::make('section_title')
                                ->label('Section Title')
                                ->helperText('HTML allowed for gradient spans.')
                                ->maxLength(255),
                            Textarea::make('section_subtitle')
                                ->label('Section Subtitle')
                                ->rows(2)
                                ->columnSpanFull(),
                            TextInput::make('intro_heading')
                                ->label('Intro Heading')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Textarea::make('intro_paragraph_1')
                                ->label('Intro Paragraph 1')
                                ->rows(4)
                                ->columnSpanFull(),
                            Textarea::make('intro_paragraph_2')
                                ->label('Intro Paragraph 2')
                                ->rows(4)
                                ->columnSpanFull(),
                            FileUpload::make('image_1')
                                ->label('Image 1')
                                ->image()
                                ->directory('about')
                                ->disk('public')
                                ->visibility('public')
                                ->imageEditor(),
                            FileUpload::make('image_2')
                                ->label('Image 2')
                                ->image()
                                ->directory('about')
                                ->disk('public')
                                ->visibility('public')
                                ->imageEditor(),
                        ]),
                    Section::make('Content Blocks')
                        ->schema([
                            TextInput::make('who_we_help_title')
                                ->label('Title 1')
                                ->maxLength(255),
                            Textarea::make('who_we_help_content')
                                ->label('Content 1')
                                ->rows(4),
                            TextInput::make('what_we_do_title')
                                ->label('Title 2')
                                ->maxLength(255),
                            Textarea::make('what_we_do_content')
                                ->label('Content 2')
                                ->rows(4),
                            TextInput::make('mission_title')
                                ->label('Title 3')
                                ->maxLength(255),
                            Textarea::make('mission_content')
                                ->label('Content 3')
                                ->rows(4),
                        ]),
                    Section::make('Statistics')
                        ->columns(2)
                        ->schema([
                            TextInput::make('stat_1_value')->label('Stat 1 Value')->maxLength(50),
                            TextInput::make('stat_1_label')->label('Stat 1 Label')->maxLength(100),
                            TextInput::make('stat_2_value')->label('Stat 2 Value')->maxLength(50),
                            TextInput::make('stat_2_label')->label('Stat 2 Label')->maxLength(100),
                            TextInput::make('stat_3_value')->label('Stat 3 Value')->maxLength(50),
                            TextInput::make('stat_3_label')->label('Stat 3 Label')->maxLength(100),
                            TextInput::make('stat_4_value')->label('Stat 4 Value')->maxLength(50),
                            TextInput::make('stat_4_label')->label('Stat 4 Label')->maxLength(100),
                        ]),
                    Section::make('SEO')
                        ->schema([
                            TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->maxLength(255),
                            Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(3),
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
