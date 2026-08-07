<?php

namespace App\Console\Commands;

use App\Services\Attendance\AttendanceService;
use Illuminate\Console\Command;

class MarkAbsentAttendancesCommand extends Command
{
    protected $signature = 'attendance:mark-absent';

    protected $description = 'Mark staff as absent for working days with no check-in';

    public function handle(AttendanceService $attendance): int
    {
        $created = $attendance->markAbsentsForAllStaff();

        $this->info("Marked {$created} absent record(s).");

        return self::SUCCESS;
    }
}
