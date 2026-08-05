<?php

namespace App\Filament\Pages;

use App\Models\WhyChooseUsSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageWhyChooseUsSettings extends ManageSettingsPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Why Choose Us Settings';

    protected static ?string $title = 'Why Choose Us Settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?int $navigationSort = 5;

    protected function settingsModel(): string
    {
        return WhyChooseUsSetting::class;
    }

    protected function savedNotificationTitle(): string
    {
        return 'Why Choose Us settings saved';
    }

    public function form(Schema $schema): Schema
    {
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
                    Section::make('Partner Section')
                        ->description('Shown at the bottom of the Why Choose Us page.')
                        ->schema([
                            FileUpload::make('partner_image')
                                ->label('Image')
                                ->image()
                                ->directory('why-choose-us')
                                ->disk('public')
                                ->visibility('public')
                                ->imageEditor(),
                            TextInput::make('partner_title')
                                ->label('Title')
                                ->maxLength(255),
                            Textarea::make('partner_content')
                                ->label('Content')
                                ->rows(4),
                            Repeater::make('partner_points')
                                ->label('Bullet Points')
                                ->simple(TextInput::make('point')->required())
                                ->default([])
                                ->columnSpanFull(),
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
