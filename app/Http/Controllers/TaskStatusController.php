<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function index()
    {
        $statuses = TaskStatus::orderBy('order', 'asc')->get();
        return view('user.task-statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string',
        ]);

        TaskStatus::create([
            'name' => $request->name,
            'color' => $request->color ?? '#00B8D9',
            'order' => TaskStatus::count() + 1
        ]);

        return redirect()->back()->with('success', 'Status created successfully.');
    }

    public function update(Request $request, TaskStatus $taskStatus)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:7',
        ]);

        $taskStatus->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        $taskStatus->delete();
        return redirect()->back()->with('success', 'Status deleted successfully.');
    }
}