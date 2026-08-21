<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Fillable([
    'invoice_number',
    'invoice_date',
    'currency',
    'amount_in_words',
    'closing_text',
    'sign_off',
    'from_company_name',
    'from_registration_no',
    'from_address',
    'from_email',
    'from_mobile',
    'to_name',
    'to_phone',
    'to_address',
    'to_company',
    'payment_method_name',
    'payment_account_title',
    'payment_bank_name',
    'payment_iban',
    'payment_swift_bic',
    'payment_branch_code',
    'subtotal',
    'total',
])]
class Invoice extends Model
{
    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice): void {
            if (blank($invoice->invoice_number)) {
                $invoice->invoice_number = static::generateInvoiceNumber($invoice->invoice_date);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function recalculateTotals(): void
    {
        $subtotal = (float) $this->items()->sum('total');
        $this->forceFill([
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ])->save();
    }

    public static function generateInvoiceNumber(?Carbon $date = null): string
    {
        $date ??= now();
        $prefix = 'INV-'.$date->format('Ym');
        $latest = static::query()
            ->where('invoice_number', 'like', $prefix.'-%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $sequence = 1;
        if (is_string($latest) && preg_match('/-(\d+)$/', $latest, $matches)) {
            $sequence = ((int) $matches[1]) + 1;
        }

        return sprintf('%s-%04d', $prefix, $sequence);
    }
}
