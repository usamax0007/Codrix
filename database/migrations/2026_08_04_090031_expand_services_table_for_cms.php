<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
            $table->text('summary')->nullable()->after('title');
            $table->text('what')->nullable()->after('summary');
            $table->json('benefits')->nullable()->after('what');
            $table->json('technologies')->nullable()->after('benefits');
            $table->text('why')->nullable()->after('technologies');
            $table->unsignedInteger('sort_order')->default(0)->after('is_popular');
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'summary',
                'what',
                'benefits',
                'technologies',
                'why',
                'sort_order',
                'is_active',
            ]);
        });
    }
};
