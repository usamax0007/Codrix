<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Attendance\AttendanceService;
use App\Support\AppPermission;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly AttendanceService $attendance) {}

    public function __invoke(): View
    {
        $user = auth()->user();
        $canAttendance = $user->can(AppPermission::ATTENDANCE_ACCESS);

        return view('user.dashboard', [
            'user' => $user,
            'canAttendance' => $canAttendance,
            'openSession' => $canAttendance ? $this->attendance->openSession($user) : null,
            'statusByDate' => $canAttendance ? $this->attendance->statusByDate($user) : [],
        ]);
    }
}
