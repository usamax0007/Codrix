<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keep one row per user/day: drop older duplicates before unique index.
        $duplicates = DB::table('attendances')
            ->select('user_id', 'work_date', DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'work_date')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('attendances')
                ->where('user_id', $duplicate->user_id)
                ->whereDate('work_date', $duplicate->work_date)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('attendances', function (Blueprint $table): void {
            $table->unique(['user_id', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropUnique(['user_id', 'work_date']);
        });
    }
};
