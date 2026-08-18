<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\Attendance\AttendanceService;
use App\Services\Progress\ProgressService;
use App\Support\AppPermission;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendance,
        private readonly ProgressService $progress,
    ) {}

    public function __invoke(): View
    {
        $user = auth()->user();
        $canAttendance = $user->can(AppPermission::ATTENDANCE_ACCESS);
        $taskStats = $this->progress->dashboardFor($user);

        $attendanceSummary = null;
        if ($canAttendance) {
            $monthStart = now()->startOfMonth()->toDateString();
            $presentDays = Attendance::query()
                ->where('user_id', $user->id)
                ->whereDate('work_date', '>=', $monthStart)
                ->whereNotNull('check_in_at')
                ->count();

            $attendanceSummary = [
                'present_days' => $presentDays,
                'open_session' => (bool) $this->attendance->openSession($user),
            ];
        }

        return view('user.dashboard', [
            'user' => $user,
            'canAttendance' => $canAttendance,
            'openSession' => $canAttendance ? $this->attendance->openSession($user) : null,
            'statusByDate' => $canAttendance ? $this->attendance->statusByDate($user) : [],
            'taskStats' => $taskStats,
            'attendanceSummary' => $attendanceSummary,
        ]);
    }
}
