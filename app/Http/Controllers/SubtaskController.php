<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtask;

class SubtaskController extends Controller
{
    // Subtask Create //
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'title' => 'required|string|max:255',
        ]);

        $subtask = Subtask::create([
            'task_id' => $request->task_id,
            'title' => $request->title,
            'is_completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'subtask' => $subtask
        ]);
    }

    // Subtask Status Toggle //
    public function toggle(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->is_completed = $request->boolean('is_completed');
        $subtask->save();

        return response()->json([
            'success' => true,
            'subtask' => $subtask
        ]);
    }

    // Delete Subtask //
    public function destroy($id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subtask deleted successfully'
        ]);
    }
}