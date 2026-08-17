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
        $statuses = TaskStatus::with('tasks.project', 'tasks.assignees')->orderBy('order', 'asc')->get();
        $users = User::all();

        if ($statuses->isEmpty()) {
            $defaultStatuses = ['To Do', 'In Progress', 'Testing', 'Done'];
            foreach ($defaultStatuses as $index => $name) {
                TaskStatus::create(['name' => $name, 'order' => $index]);
            }
            $statuses = TaskStatus::with('tasks.project', 'tasks.assignees')->orderBy('order', 'asc')->get();
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
            'attachments.*' => 'nullable|file|max:10240',
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
            $task = Task::findOrFail($id);
            $task->update([
                'summary' => $request->summary,
                'description' => $request->description,
                'priority' => $request->priority,
            ]);

            return response()->json([
                'success' => true,
                'task' => $task
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
}