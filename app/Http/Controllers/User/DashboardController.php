<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Attendance\AttendanceService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly AttendanceService $attendance) {}

    public function __invoke(): View
    {
        $user = auth()->user();

        return view('user.dashboard', [
            'user' => $user,
            'openSession' => $this->attendance->openSession($user),
            'statusByDate' => $this->attendance->statusByDate($user),
        ]);
    }
}
