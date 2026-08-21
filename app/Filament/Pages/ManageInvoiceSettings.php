<?php

namespace App\Filament\Pages;

use App\Models\InvoiceSetting;
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

class ManageInvoiceSettings extends ManageSettingsPage
{
    protected static string|UnitEnum|null $navigationGroup = 'Invoices';

    protected static ?string $navigationLabel = 'Invoice Settings';

    protected static ?string $title = 'Invoice Settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 2;

    protected function settingsModel(): string
    {
        return InvoiceSetting::class;
    }

    protected function savedNotificationTitle(): string
    {
        return 'Invoice settings saved';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('From')
                        ->columns(2)
                        ->schema([
                            TextInput::make('from_company_name')
                                ->label('Company Name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('from_registration_no')
                                ->label('Registration No')
                                ->maxLength(255),
                            TextInput::make('from_email')
                                ->label('Email')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('from_mobile')
                                ->label('Mobile')
                                ->tel()
                                ->maxLength(50),
                            Textarea::make('from_address')
                                ->label('Address')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    Section::make('Payment Method')
                        ->columns(2)
                        ->schema([
                            TextInput::make('payment_method_name')
                                ->label('Method Name')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('payment_account_title')
                                ->label('Account Title / Name')
                                ->maxLength(255),
                            TextInput::make('payment_bank_name')
                                ->label('Bank Name')
                                ->maxLength(255),
                            TextInput::make('payment_iban')
                                ->label('IBAN Number')
                                ->maxLength(255),
                            TextInput::make('payment_swift_bic')
                                ->label('SWIFT / BIC Code')
                                ->maxLength(255),
                            TextInput::make('payment_branch_code')
                                ->label('Branch Code / Address')
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ]),
                    Section::make('Defaults')
                        ->columns(3)
                        ->schema([
                            TextInput::make('currency')
                                ->required()
                                ->maxLength(20),
                            TextInput::make('closing_text')
                                ->label('Closing Text')
                                ->maxLength(255),
                            TextInput::make('sign_off')
                                ->label('Sign Off')
                                ->maxLength(255),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save settings')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }
}
