<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSiteSettings extends Page
{
    protected string $view = 'filament.pages.manage-site-settings';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 1;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Branding')
                        ->columns(2)
                        ->schema([
                            TextInput::make('site_name')
                                ->label('Site Name')
                                ->required()
                                ->maxLength(255),
                            FileUpload::make('logo')
                                ->label('Logo')
                                ->image()
                                ->directory('settings')
                                ->disk('public')
                                ->visibility('public')
                                ->imageEditor(),
                            Textarea::make('short_description')
                                ->label('Short Description')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    Section::make('Contact')
                        ->columns(2)
                        ->schema([
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Phone')
                                ->tel()
                                ->maxLength(50),
                            Textarea::make('address')
                                ->label('Address')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    Section::make('Social Links')
                        ->columns(1)
                        ->schema([
                            TextInput::make('linkedin')
                                ->label('LinkedIn')
                                ->url()
                                ->maxLength(255),
                            TextInput::make('twitter')
                                ->label('Twitter')
                                ->url()
                                ->maxLength(255),
                            TextInput::make('github')
                                ->label('GitHub')
                                ->url()
                                ->maxLength(255),
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

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord() ?? new SiteSetting;

        $record->fill($data);
        $record->save();

        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title('Site settings saved')
            ->send();
    }

    public function getRecord(): ?SiteSetting
    {
        return SiteSetting::query()->first();
    }
}
