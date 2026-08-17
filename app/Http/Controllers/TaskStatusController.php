<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'assignee'])->latest()->get();
        return view('frontend.user.task-status', compact('tasks'));
    }
}
