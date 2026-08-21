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
        $statuses = \App\Models\TaskStatus::orderBy('position')->get();
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        return view('frontend.user.add-task.index', compact('tasks', 'statuses', 'projects', 'users'));
    }

    public function create()
    {
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        $statuses = \App\Models\TaskStatus::orderBy('position')->get();
        return view('frontend.user.add-task.create', compact('projects', 'users', 'statuses'));
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('attachments', $filename, 'public');
            $data['attachment'] = $filename;
        }

        $task = Task::create($data);
        $task->load(['project', 'assignee']);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task' => $task,
        ]);
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
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }

    public function addComment(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $content = $request->input('content');
        
        // Decode if content is JSON-encoded
        if (is_string($content) && (str_starts_with($content, '{') || str_starts_with($content, '['))) {
            $decoded = json_decode($content, true);
            if (is_array($decoded) && isset($decoded['content'])) {
                $content = $decoded['content'];
            }
        }

        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'content' => $content,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'comment' => $comment,
        ]);
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
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }

    public function addSubtask(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subtask = $task->subtasks()->create([
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subtask added successfully.',
            'subtask' => $subtask,
        ]);
    }

    public function toggleSubtask(Task $task, Subtask $subtask)
    {
        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        $totalSubtasks = $task->subtasks()->count();
        $completedSubtasks = $task->subtasks()
            ->where('is_completed', true)
            ->count();

        if ($totalSubtasks > 0 && $completedSubtasks === $totalSubtasks) {
            $task->update([
                'status' => 'Completed',
            ]);
        } elseif ($completedSubtasks > 0) {
            $task->update([
                'status' => 'In Progress',
            ]);
        } else {
            $task->update([
                'status' => 'Pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subtask toggled successfully.',
            'task_status' => $task->status,
        ]);
    }


    public function deleteSubtask(Task $task, Subtask $subtask)
    {
        $subtask->delete();
        return response()->json([
            'success' => true,
            'message' => 'Subtask deleted successfully.',
        ]);
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