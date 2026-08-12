<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->string('currency', 20)->default('US$');
            $table->string('amount_in_words')->nullable();
            $table->string('closing_text')->default('Yours sincerely,');
            $table->string('sign_off')->nullable();

            $table->string('from_company_name');
            $table->string('from_registration_no')->nullable();
            $table->text('from_address')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_mobile')->nullable();

            $table->string('to_name');
            $table->string('to_phone')->nullable();
            $table->text('to_address')->nullable();
            $table->string('to_company')->nullable();

            $table->string('payment_method_name')->nullable();
            $table->string('payment_account_title')->nullable();
            $table->string('payment_bank_name')->nullable();
            $table->string('payment_iban')->nullable();
            $table->string('payment_swift_bic')->nullable();
            $table->string('payment_branch_code')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
