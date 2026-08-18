<?php

namespace App\Http\Controllers\User;

use App\Exceptions\AttendanceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AttendanceHistoryRequest;
use App\Http\Requests\User\CheckInRequest;
use App\Http\Requests\User\CheckOutRequest;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendance) {}

    public function index(AttendanceHistoryRequest $request): View
    {
        $user = $request->user();
        $range = $request->range();

        return view('user.attendance.index', [
            'user' => $user,
            'range' => $range,
            'openSession' => $this->attendance->openSession($user),
            'statusByDate' => $this->attendance->statusByDate($user),
            'attendances' => $this->attendance->paginateForUser($user, $range),
        ]);
    }

    public function checkIn(CheckInRequest $request): RedirectResponse
    {
        try {
            $this->attendance->checkIn($request->user(), $request->occurredAt());
        } catch (AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Checked in successfully.');
    }

    public function checkOut(CheckOutRequest $request): RedirectResponse
    {
        try {
            $record = $this->attendance->checkOut($request->user(), $request->occurredAt());
        } catch (AttendanceException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with(
            'success',
            "Checked out successfully. Time worked: {$record->durationLabel()}."
        );
    }
}
