<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceSetting;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    public function mount(): void
    {
        parent::mount();

        $settings = InvoiceSetting::current();

        $this->form->fill([
            'invoice_date' => now()->toDateString(),
            'currency' => $settings->currency ?: 'US$',
            'closing_text' => $settings->closing_text ?: 'Yours sincerely,',
            'sign_off' => $settings->sign_off ?: 'Xcodrix',
            'from_company_name' => $settings->from_company_name,
            'from_registration_no' => $settings->from_registration_no,
            'from_address' => $settings->from_address,
            'from_email' => $settings->from_email,
            'from_mobile' => $settings->from_mobile,
            'payment_method_name' => $settings->payment_method_name,
            'payment_account_title' => $settings->payment_account_title,
            'payment_bank_name' => $settings->payment_bank_name,
            'payment_iban' => $settings->payment_iban,
            'payment_swift_bic' => $settings->payment_swift_bic,
            'payment_branch_code' => $settings->payment_branch_code,
            'subtotal' => 0,
            'total' => 0,
        ]);
    }

    protected function afterCreate(): void
    {
        /** @var Invoice $invoice */
        $invoice = $this->record;
        $invoice->recalculateTotals();
    }
}
