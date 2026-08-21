<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->string('from_company_name')->nullable();
            $table->string('from_registration_no')->nullable();
            $table->text('from_address')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_mobile')->nullable();
            $table->string('payment_method_name')->nullable();
            $table->string('payment_account_title')->nullable();
            $table->string('payment_bank_name')->nullable();
            $table->string('payment_iban')->nullable();
            $table->string('payment_swift_bic')->nullable();
            $table->string('payment_branch_code')->nullable();
            $table->string('currency', 20)->default('US$');
            $table->string('closing_text')->default('Yours sincerely,');
            $table->string('sign_off')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_settings');
    }
};
