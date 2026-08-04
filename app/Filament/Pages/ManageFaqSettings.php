<?php

namespace App\Filament\Pages;

use App\Models\FaqSetting;
use BackedEnum;
use Filament\Actions\Action;
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
class ManageFaqSettings extends Page
{
    protected string $view = 'filament.pages.manage-faq-settings';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'FAQ Settings';

    protected static ?string $title = 'FAQ Settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?int $navigationSort = 3;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray() ?? FaqSetting::defaults());
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

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord() ?? new FaqSetting;

        $record->fill($data);
        $record->save();

        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title('FAQ settings saved')
            ->send();
    }

    public function getRecord(): ?FaqSetting
    {
        return FaqSetting::query()->first();
    }
}
