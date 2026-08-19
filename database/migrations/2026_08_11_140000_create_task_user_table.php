<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('task_user')) {
            Schema::create('task_user', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('task_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['task_id', 'user_id']);
            });
        }

        if (Schema::hasColumn('tasks', 'assignee_id')) {
            $now = now();

            DB::table('tasks')
                ->select(['id', 'assignee_id'])
                ->orderBy('id')
                ->chunkById(200, function ($tasks) use ($now): void {
                    $rows = [];

                    foreach ($tasks as $task) {
                        if (! $task->assignee_id) {
                            continue;
                        }

                        $rows[] = [
                            'task_id' => $task->id,
                            'user_id' => $task->assignee_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if ($rows !== []) {
                        DB::table('task_user')->insertOrIgnore($rows);
                    }
                });

            $fkExists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'tasks'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = 'tasks_assignee_id_foreign'
            LIMIT 1
        ");

            Schema::table('tasks', function (Blueprint $table) use ($fkExists): void {
                if ($fkExists) {
                    $table->dropForeign('tasks_assignee_id_foreign');
                }

                $table->dropColumn('assignee_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->foreignId('assignee_id')->nullable()->after('priority')->constrained('users')->nullOnDelete();
        });

        $firstAssignees = DB::table('task_user')
            ->select('task_id', DB::raw('MIN(user_id) as user_id'))
            ->groupBy('task_id')
            ->get();

        foreach ($firstAssignees as $row) {
            DB::table('tasks')
                ->where('id', $row->task_id)
                ->update(['assignee_id' => $row->user_id]);
        }

        Schema::dropIfExists('task_user');
    }
};
