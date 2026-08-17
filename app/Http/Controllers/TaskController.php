<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'assignee'])->latest()->get();
        return view('frontend.user.add-task.index', compact('tasks'));
    }

    public function create()
    {
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        return view('frontend.user.add-task.create', compact('projects', 'users'));
    }

    public function store(TaskRequest $request)
    {
        Task::create($request->validated());
        return redirect()->route('user.task.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee']);
        return view('frontend.user.add-task.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        return view('frontend.user.add-task.edit', compact('task', 'projects', 'users'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return redirect()->route('user.task.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('user.task.index')->with('success', 'Task deleted successfully.');
    }
}