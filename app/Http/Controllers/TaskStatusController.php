<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusRequest;
use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function index()
    {
        $statuses = TaskStatus::orderBy('position')->get();
        return view('frontend.user.task-status', compact('statuses'));
    }

    public function store(TaskStatusRequest $request)
    {
        $maxPosition = TaskStatus::max('position') ?? 0;
        TaskStatus::create(array_merge($request->validated(), ['position' => $maxPosition + 1]));

        return redirect()->route('user.task-status.index')->with('success', 'Status added successfully!');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        $taskStatus->delete();
        return redirect()->route('user.task-status.index')->with('success', 'Status deleted successfully!');
    }

    public function updatePositions(Request $request)
    {
        $positions = $request->input('positions', []);
        
        foreach ($positions as $index => $id) {
            TaskStatus::where('id', $id)->update(['position' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Positions updated successfully.',
        ]);
    }
}
