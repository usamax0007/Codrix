<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Comment;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'assignee', 'subtasks'])->latest()->get();
        $statuses = \App\Models\TaskStatus::all();
        return view('frontend.user.add-task.index', compact('tasks', 'statuses'));
    }

    public function create()
    {
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        $statuses = \App\Models\TaskStatus::all();
        return view('frontend.user.add-task.create', compact('projects', 'users', 'statuses'));
    }

    public function store(TaskRequest $request)
    {
        Task::create($request->validated());
        return redirect()->route('user.task.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee', 'comments.user', 'subtasks']);
        return view('frontend.user.add-task.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        $statuses = \App\Models\TaskStatus::all();
        return view('frontend.user.add-task.edit', compact('task', 'projects', 'users', 'statuses'));
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

    public function addComment(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return redirect()->route('user.task.show', $task)->with('success', 'Comment added successfully.');
    }

    public function updateComment(Request $request, Task $task, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->route('user.task.show', $task)->with('success', 'Comment updated successfully.');
    }

    public function deleteComment(Task $task, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();
        return redirect()->route('user.task.show', $task)->with('success', 'Comment deleted successfully.');
    }

    public function addSubtask(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->subtasks()->create([
            'title' => $request->title,
        ]);

        return redirect()->route('user.task.show', $task)->with('success', 'Subtask added successfully.');
    }

    public function toggleSubtask(Task $task, Subtask $subtask)
    {
        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        return redirect()->route('user.task.show', $task);
    }

    public function deleteSubtask(Task $task, Subtask $subtask)
    {
        $subtask->delete();
        return redirect()->route('user.task.show', $task)->with('success', 'Subtask deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|string|exists:task_statuses,name',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
        ]);
    }
}