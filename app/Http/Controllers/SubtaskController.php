<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtask;

class SubtaskController extends Controller
{
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
}