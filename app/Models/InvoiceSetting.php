<?php

namespace App\Models;

use App\Models\Concerns\HasSingletonSettings;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'from_company_name',
    'from_registration_no',
    'from_address',
    'from_email',
    'from_mobile',
    'payment_method_name',
    'payment_account_title',
    'payment_bank_name',
    'payment_iban',
    'payment_swift_bic',
    'payment_branch_code',
    'currency',
    'closing_text',
    'sign_off',
])]
class InvoiceSetting extends Model
{
    use HasSingletonSettings;

    public static function defaults(): array
    {
        return [
            'from_company_name' => 'Xcodrix (Registered Sole Proprietorship)',
            'from_registration_no' => 'Z-25-18401/26',
            'from_address' => "Lasani Plaza Office no 2 Chungi no 6\nMultan, Pakistan",
            'from_email' => 'info@xcoderix.com',
            'from_mobile' => '+92 308 6000611',
            'payment_method_name' => 'REMITLY INTERNATIONAL',
            'payment_account_title' => 'Codrix',
            'payment_bank_name' => 'United Bank Limited',
            'payment_iban' => 'PK26UNIL0109000346298275',
            'payment_swift_bic' => 'UNILPKKA741',
            'payment_branch_code' => '0278',
            'currency' => 'US$',
            'closing_text' => 'Yours sincerely,',
            'sign_off' => 'Xcodrix',
        ];
    }
}
