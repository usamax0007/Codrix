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
        if (!auth()->user()->hasPermissionTo('view-tasks')) {
            abort(403, 'You do not have permission to view tasks.');
        }

        $tasks = Task::with(['project', 'assignee', 'subtasks'])->latest()->get();
        $statuses = \App\Models\TaskStatus::orderBy('position')->get();
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        
        if (request()->routeIs('admin.tasks.*')) {
            return view('frontend.admin.tasks.index', compact('tasks', 'statuses', 'projects', 'users'));
        } elseif (request()->routeIs('super-admin.tasks.*')) {
            return view('frontend.super-admin.tasks.index', compact('tasks', 'statuses', 'projects', 'users'));
        }
        
        return view('frontend.user.add-task.index', compact('tasks', 'statuses', 'projects', 'users'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-tasks')) {
            abort(403, 'You do not have permission to create tasks.');
        }

        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        $statuses = \App\Models\TaskStatus::orderBy('position')->get();
        
        if (request()->routeIs('admin.tasks.*')) {
            return view('frontend.admin.tasks.create', compact('projects', 'users', 'statuses'));
        } elseif (request()->routeIs('super-admin.tasks.*')) {
            return view('frontend.super-admin.tasks.create', compact('projects', 'users', 'statuses'));
        }
        
        return view('frontend.user.add-task.create', compact('projects', 'users', 'statuses'));
    }

    public function store(TaskRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('create-tasks')) {
            abort(403, 'You do not have permission to create tasks.');
        }

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
        if (!auth()->user()->hasPermissionTo('view-tasks')) {
            abort(403, 'You do not have permission to view tasks.');
        }

        $task->load(['project', 'assignee', 'comments.user', 'subtasks']);
        
        if (request()->routeIs('admin.tasks.*')) {
            return view('frontend.admin.tasks.show', compact('task'));
        } elseif (request()->routeIs('super-admin.tasks.*')) {
            return view('frontend.super-admin.tasks.show', compact('task'));
        }
        
        return view('frontend.user.add-task.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if (!auth()->user()->hasPermissionTo('edit-tasks')) {
            abort(403, 'You do not have permission to edit tasks.');
        }

        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        $statuses = \App\Models\TaskStatus::all();
        
        if (request()->routeIs('admin.tasks.*')) {
            return view('frontend.admin.tasks.edit', compact('task', 'projects', 'users', 'statuses'));
        } elseif (request()->routeIs('super-admin.tasks.*')) {
            return view('frontend.super-admin.tasks.edit', compact('task', 'projects', 'users', 'statuses'));
        }
        
        return view('frontend.user.add-task.edit', compact('task', 'projects', 'users', 'statuses'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        if (!auth()->user()->hasPermissionTo('edit-tasks')) {
            abort(403, 'You do not have permission to edit tasks.');
        }

        $task->update($request->validated());
        
        if (request()->routeIs('admin.tasks.*')) {
            return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
        } elseif (request()->routeIs('super-admin.tasks.*')) {
            return redirect()->route('super-admin.tasks.index')->with('success', 'Task updated successfully.');
        }
        
        return redirect()->route('user.task.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if (!auth()->user()->hasPermissionTo('delete-tasks')) {
            abort(403, 'You do not have permission to delete tasks.');
        }

        $task->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }

    public function addComment(Request $request, Task $task)
    {
        if (!auth()->user()->hasPermissionTo('view-tasks')) {
            abort(403, 'You do not have permission to add comments.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $content = $request->input('content');


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
        if (!auth()->user()->hasPermissionTo('create-subtask')) {
            abort(403, 'You do not have permission to create subtasks.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subtask = $task->subtasks()->create([
            'title' => $request->title,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subtask added successfully.',
            'subtask' => $subtask,
        ]);
    }

    public function toggleSubtask(Task $task, Subtask $subtask)
    {
        // Admin can toggle any subtask
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            if (!auth()->user()->hasPermissionTo('edit-subtask')) {
                abort(403, 'You do not have permission to edit subtasks.');
            }
        } else {
            // Regular users can only toggle subtasks they created
            if ($subtask->user_id !== auth()->id()) {
                abort(403, 'You can only toggle your own subtasks.');
            }
        }

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subtask toggled successfully.',
        ]);
    }


    public function deleteSubtask(Task $task, Subtask $subtask)
    {
        if (!auth()->user()->hasPermissionTo('delete-subtask')) {
            abort(403, 'You do not have permission to delete subtasks.');
        }

        $subtask->delete();
        return response()->json([
            'success' => true,
            'message' => 'Subtask deleted successfully.',
        ]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        // Only admin and super-admin can update task status
        if (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'You do not have permission to update task status.');
        }

        if (!auth()->user()->hasPermissionTo('edit-tasks')) {
            abort(403, 'You do not have permission to update task status.');
        }

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