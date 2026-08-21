<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Details')
                    ->columns(3)
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->helperText('Leave blank to auto-generate on save.')
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->nullable(),
                        DatePicker::make('invoice_date')
                            ->label('Invoice Date')
                            ->required()
                            ->native(false)
                            ->displayFormat('d-M-Y')
                            ->default(now()),
                        TextInput::make('currency')
                            ->required()
                            ->maxLength(20)
                            ->live(onBlur: true),
                        TextInput::make('closing_text')
                            ->label('Closing Text')
                            ->maxLength(255)
                            ->default('Yours sincerely,'),
                        TextInput::make('sign_off')
                            ->label('Sign Off')
                            ->maxLength(255)
                            ->default('Xcodrix'),
                    ])->columnSpanFull(),

                Section::make('From')
                    ->columns(2)
                    ->schema([
                        TextInput::make('from_company_name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('from_registration_no')
                            ->label('Registration No')
                            ->maxLength(255),
                        Textarea::make('from_address')
                            ->label('Address')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('from_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('from_mobile')
                            ->label('Mobile')
                            ->tel()
                            ->maxLength(50),
                    ]),

                Section::make('To')
                    ->columns(2)
                    ->schema([
                        TextInput::make('to_name')
                            ->label('Client Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('to_phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('to_company')
                            ->label('Company / Project')
                            ->maxLength(255),
                        Textarea::make('to_address')
                            ->label('Address')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Line Items')
                    ->columns(3)
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->orderColumn('sort_order')
                            ->reorderable()
                            ->defaultItems(1)
                            ->columns(12)
                            ->schema([
                                TextInput::make('description')
                                    ->required()
                                    ->columnSpan(5),
                                TextInput::make('qty')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcFromItem($get, $set))
                                    ->columnSpan(2),
                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->minValue(0)
                                    ->prefix(fn (Get $get) => self::currencyPrefix($get))
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcFromItem($get, $set))
                                    ->columnSpan(3),
                                TextInput::make('total')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0)
                                    ->prefix(fn (Get $get) => self::currencyPrefix($get))
                                    ->columnSpan(2),
                            ])
                            ->live()
                            ->afterStateUpdated(function (mixed $state, Set $set): void {
                                $subtotal = self::sumItems(is_array($state) ? $state : []);
                                $set('../subtotal', $subtotal);
                                $set('../total', $subtotal);
                            })
                            ->deleteAction(fn ($action) => $action->after(function (Get $get, Set $set): void {
                                $subtotal = self::sumItems($get('.') ?? []);
                                $set('../subtotal', $subtotal);
                                $set('../total', $subtotal);
                            }))
                            ->columnSpanFull(),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix(fn (Get $get) => self::currencyPrefix($get)),
                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix(fn (Get $get) => self::currencyPrefix($get)),
                        TextInput::make('amount_in_words')
                            ->label('Amount in Words')
                            ->maxLength(255),
                    ])->columnSpanFull(),

                Section::make('Payment Method')
                    ->columns(2)
                    ->schema([
                        TextInput::make('payment_method_name')
                            ->label('Method Name')
                            ->maxLength(255),
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
                            ->maxLength(255),
                    ])->columnSpanFull()->columns(3),
            ]);
    }

    protected static function recalcFromItem(Get $get, Set $set): void
    {
        $qty = (float) ($get('qty') ?? 0);
        $unitPrice = (float) ($get('unit_price') ?? 0);
        $set('total', round($qty * $unitPrice, 2));

        $subtotal = self::sumItems($get('../../items'));
        $set('../../subtotal', $subtotal);
        $set('../../total', $subtotal);
    }

    /**
     * @param  array<string, mixed>|null  $items
     */
    protected static function sumItems(?array $items): float
    {
        $subtotal = 0.0;

        foreach ($items ?? [] as $item) {
            if (! is_array($item)) {
                continue;
            }

            $subtotal += round(((float) ($item['qty'] ?? 0)) * ((float) ($item['unit_price'] ?? 0)), 2);
        }

        return round($subtotal, 2);
    }

    protected static function currencyPrefix(Get $get): ?string
    {
        foreach (['currency', '../currency', '../../currency', '../../../currency'] as $path) {
            $value = $get($path);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return null;
    }
}
