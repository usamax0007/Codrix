<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->string('status', 20)->default('present')->after('worked_minutes');
        });

        // Allow absent rows without punch times.
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dateTime('check_in_at')->nullable()->change();
        });

        DB::table('attendances')->orderBy('id')->chunkById(100, function ($rows): void {
            foreach ($rows as $row) {
                $status = $row->check_out_at === null ? 'open' : 'present';

                DB::table('attendances')
                    ->where('id', $row->id)
                    ->update(['status' => $status]);
            }
        });
    }

    public function down(): void
    {
        DB::table('attendances')->whereNull('check_in_at')->delete();

        Schema::table('attendances', function (Blueprint $table): void {
            $table->dateTime('check_in_at')->nullable(false)->change();
            $table->dropColumn('status');
        });
    }
};
