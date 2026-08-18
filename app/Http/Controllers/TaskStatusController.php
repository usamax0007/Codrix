<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusRequest;
use App\Models\TaskStatus;

class TaskStatusController extends Controller
{
    public function index()
    {
        $statuses = TaskStatus::latest()->get();
        return view('frontend.user.task-status', compact('statuses'));
    }

    public function store(TaskStatusRequest $request)
    {
        TaskStatus::create($request->validated());

        return redirect()->route('user.task-status.index')->with('success', 'Status added successfully!');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        $taskStatus->delete();
        return redirect()->route('user.task-status.index')->with('success', 'Status deleted successfully!');
    }
}
