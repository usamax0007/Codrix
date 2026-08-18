<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 7)->default('#94A3B8');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        $now = now();

        $defaults = [
            ['name' => 'To Do', 'slug' => 'todo', 'color' => '#94A3B8', 'sort_order' => 0],
            ['name' => 'In Progress', 'slug' => 'in_progress', 'color' => '#0066FF', 'sort_order' => 1],
            ['name' => 'Testing', 'slug' => 'testing', 'color' => '#FBBF24', 'sort_order' => 2],
            ['name' => 'Done', 'slug' => 'done', 'color' => '#00E5C0', 'sort_order' => 3],
        ];

        foreach ($defaults as $status) {
            DB::table('task_statuses')->insert([
                ...$status,
                'is_enabled' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::table('tasks', function (Blueprint $table): void {
            $table->foreignId('task_status_id')
                ->nullable()
                ->after('description')
                ->constrained('task_statuses')
                ->restrictOnDelete();
        });

        $slugToId = DB::table('task_statuses')->pluck('id', 'slug');
        $fallbackId = $slugToId['todo'] ?? $slugToId->first();

        foreach (DB::table('tasks')->select(['id', 'status'])->get() as $task) {
            DB::table('tasks')->where('id', $task->id)->update([
                'task_status_id' => $slugToId[$task->status] ?? $fallbackId,
            ]);
        }

        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropIndex(['status', 'sort_order']);
            $table->dropColumn('status');
        });

        DB::statement('ALTER TABLE tasks MODIFY task_status_id BIGINT UNSIGNED NOT NULL');

        Schema::table('tasks', function (Blueprint $table): void {
            $table->index(['task_status_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->string('status')->default('todo')->after('description');
        });

        $idToSlug = DB::table('task_statuses')->pluck('slug', 'id');

        foreach (DB::table('tasks')->select(['id', 'task_status_id'])->get() as $task) {
            DB::table('tasks')->where('id', $task->id)->update([
                'status' => $idToSlug[$task->task_status_id] ?? 'todo',
            ]);
        }

        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropIndex(['task_status_id', 'sort_order']);
            $table->dropForeign(['task_status_id']);
            $table->dropColumn('task_status_id');
            $table->index(['status', 'sort_order']);
        });

        Schema::dropIfExists('task_statuses');
    }
};
