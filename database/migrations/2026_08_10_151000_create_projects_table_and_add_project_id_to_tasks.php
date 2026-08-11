<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('tasks', function (Blueprint $table): void {
            $table->foreignId('project_id')
                ->nullable()
                ->after('id')
                ->constrained('projects')
                ->restrictOnDelete();
        });

        $creatorId = DB::table('users')->orderBy('id')->value('id');

        if ($creatorId) {
            $now = now();
            $projectId = DB::table('projects')->insertGetId([
                'name' => 'General',
                'slug' => 'general',
                'description' => 'Default project for existing and uncategorized tasks.',
                'start_date' => null,
                'due_date' => null,
                'created_by' => $creatorId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('tasks')->whereNull('project_id')->update([
                'project_id' => $projectId,
            ]);
        }

        DB::statement('ALTER TABLE tasks MODIFY project_id BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('project_id');
        });

        Schema::dropIfExists('projects');
    }
};
