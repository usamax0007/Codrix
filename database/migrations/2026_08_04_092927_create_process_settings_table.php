<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('section_badge')->nullable();
            $table->string('section_title')->nullable();
            $table->text('section_subtitle')->nullable();
            $table->string('footer_title')->nullable();
            $table->text('footer_content_1')->nullable();
            $table->text('footer_content_2')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            for ($i = 1; $i <= 6; $i++) {
                $table->string("step_{$i}_number")->nullable();
                $table->string("step_{$i}_title")->nullable();
                $table->text("step_{$i}_description")->nullable();
            }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_settings');
    }
};
