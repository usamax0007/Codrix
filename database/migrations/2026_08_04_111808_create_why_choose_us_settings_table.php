<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('why_choose_us_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('section_badge')->nullable();
            $table->string('section_title')->nullable();
            $table->text('section_subtitle')->nullable();
            $table->string('partner_image')->nullable();
            $table->string('partner_title')->nullable();
            $table->text('partner_content')->nullable();
            $table->json('partner_points')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('why_choose_us_settings');
    }
};
