<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('section_badge')->nullable();
            $table->string('section_title')->nullable();
            $table->text('section_subtitle')->nullable();
            $table->string('intro_heading')->nullable();
            $table->text('intro_paragraph_1')->nullable();
            $table->text('intro_paragraph_2')->nullable();
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('who_we_help_title')->nullable();
            $table->text('who_we_help_content')->nullable();
            $table->string('what_we_do_title')->nullable();
            $table->text('what_we_do_content')->nullable();
            $table->string('mission_title')->nullable();
            $table->text('mission_content')->nullable();
            $table->string('stat_1_value')->nullable();
            $table->string('stat_1_label')->nullable();
            $table->string('stat_2_value')->nullable();
            $table->string('stat_2_label')->nullable();
            $table->string('stat_3_value')->nullable();
            $table->string('stat_3_label')->nullable();
            $table->string('stat_4_value')->nullable();
            $table->string('stat_4_label')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
