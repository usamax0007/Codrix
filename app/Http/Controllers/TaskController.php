<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        $statuses = TaskStatus::with([
            'tasks.status',      // <-- YE MISSING THA (Status details pass karne ke liye)
            'tasks.project',
            'tasks.assignees',
            'tasks.subtasks',
            'tasks.comments.user',
            'tasks.attachments'
        ])->orderBy('order', 'asc')->get();

        $users = User::all();

        if ($statuses->isEmpty()) {
            $defaultStatuses = ['To Do', 'In Progress', 'Testing', 'Done'];
            foreach ($defaultStatuses as $index => $name) {
                TaskStatus::create(['name' => $name, 'order' => $index]);
            }
            $statuses = TaskStatus::with([
                'tasks.status',
                'tasks.project',
                'tasks.assignees',
                'tasks.subtasks',
                'tasks.comments.user',
                'tasks.attachments'
            ])->orderBy('order', 'asc')->get();
        }

        return view('user.tasks.index', compact('projects', 'statuses', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'task_status_id' => 'required|exists:task_statuses,id',
            'due_date' => 'nullable|date',
            'assignees' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:10240',
        ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'summary' => $request->summary,
            'description' => $request->description,
            'priority' => $request->priority,
            'task_status_id' => $request->task_status_id,
            'due_date' => $request->due_date,
        ]);

        if ($request->has('assignees')) {
            $task->assignees()->sync($request->assignees);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task_attachments', 'public');
                TaskAttachment::create([
                    'task_id' => $task->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'Task created successfully!');
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'summary' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'task_status_id' => 'required|exists:task_statuses,id',
                'priority' => 'required|in:Low,Medium,High,Urgent',
                'due_date' => 'nullable|date',
                'assignees' => 'nullable|array',
                'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:10240',
            ]);

            $task = Task::findOrFail($id);

            // Basic fields update
            $task->update([
                'summary' => $request->summary,
                'description' => $request->description,
                'project_id' => $request->project_id,
                'task_status_id' => $request->task_status_id,
                'priority' => $request->priority,
                'due_date' => $request->due_date,
            ]);

            // Assignees sync
            if ($request->has('assignees')) {
                $task->assignees()->sync($request->assignees);
            } else {
                $task->assignees()->detach();
            }

            // New Attachments save
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('task_attachments', 'public');
                    TaskAttachment::create([
                        'task_id' => $task->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status_id' => 'required|exists:task_statuses,id',
        ]);

        $task = Task::findOrFail($request->task_id);
        $task->task_status_id = $request->status_id;
        $task->save();

        $task->load('status');

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully!',
            'status_name' => $task->status->name ?? '',
            'status_color' => $task->status->color ?? '#3B82F6',
        ]);
    }
}