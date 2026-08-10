<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\MoveTaskRequest;
use App\Http\Requests\User\StoreTaskCommentRequest;
use App\Http\Requests\User\StoreTaskRequest;
use App\Models\Task;
use App\Services\Task\TaskBoardService;
use App\Support\AppPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(private readonly TaskBoardService $tasks) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $user = $request->user();

        return view('user.tasks.index', [
            'user' => $user,
            'columns' => $this->tasks->boardFor($user),
            'canAssign' => $user->can(AppPermission::TASKS_ASSIGN),
            'assignees' => $user->can(AppPermission::TASKS_ASSIGN)
                ? $this->tasks->assignableUsers()
                : collect(),
        ]);
    }

    public function show(Request $request, Task $task): View
    {
        $this->authorize('view', $task);

        return view('user.tasks.show', [
            'user' => $request->user(),
            'task' => $this->tasks->loadDetails($task),
            'canAssign' => $request->user()->can(AppPermission::TASKS_ASSIGN),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->tasks->create($request->user(), $request->taskData(), $request->attachments());

        return redirect()
            ->route('user.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function storeComment(StoreTaskCommentRequest $request, Task $task): RedirectResponse
    {
        $this->tasks->addComment($task, $request->user(), $request->body());

        return redirect()
            ->route('user.tasks.show', $task)
            ->with('success', 'Comment added.');
    }

    public function move(MoveTaskRequest $request, Task $task): JsonResponse
    {
        $orderedIds = $request->orderedIds();

        if (! in_array($task->id, $orderedIds, true)) {
            return response()->json(['message' => 'Moved task must be included in ordered_ids.'], 422);
        }

        foreach ($orderedIds as $id) {
            if ($id === $task->id) {
                continue;
            }

            $related = Task::query()->find($id);

            if (! $related || $request->user()->cannot('update', $related)) {
                return response()->json(['message' => 'You cannot reorder one or more tasks.'], 403);
            }
        }

        $this->tasks->move($task, $request->status(), $orderedIds);

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $this->tasks->delete($task);

        return redirect()
            ->route('user.tasks.index')
            ->with('success', 'Task deleted.');
    }
}
