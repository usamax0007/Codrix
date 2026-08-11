<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReorderSubtasksRequest;
use App\Http\Requests\User\StoreSubtaskRequest;
use App\Http\Requests\User\UpdateSubtaskRequest;
use App\Models\Subtask;
use App\Models\Task;
use App\Services\Task\SubtaskService;
use App\Services\Task\TaskBoardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function __construct(
        private readonly SubtaskService $subtasks,
        private readonly TaskBoardService $tasks,
    ) {}

    public function store(StoreSubtaskRequest $request, Task $task): JsonResponse|RedirectResponse
    {
        $this->subtasks->create($task, $request->user(), $request->subtaskData());

        return $this->respond($request, $task, 'Subtask added.');
    }

    public function update(UpdateSubtaskRequest $request, Task $task, Subtask $subtask): JsonResponse|RedirectResponse
    {
        $this->ensureBelongsToTask($task, $subtask);
        $this->subtasks->update($subtask, $request->subtaskData());

        return $this->respond($request, $task, 'Subtask updated.');
    }

    public function toggle(Request $request, Task $task, Subtask $subtask): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $task);
        $this->ensureBelongsToTask($task, $subtask);

        $this->subtasks->toggle($subtask);

        return $this->respond($request, $task, 'Subtask updated.');
    }

    public function reorder(ReorderSubtasksRequest $request, Task $task): JsonResponse
    {
        $this->subtasks->reorder($task, $request->orderedIds());

        return $this->taskPayload($task);
    }

    public function destroy(Request $request, Task $task, Subtask $subtask): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $task);
        $this->ensureBelongsToTask($task, $subtask);

        $this->subtasks->delete($subtask);

        return $this->respond($request, $task, 'Subtask deleted.');
    }

    private function ensureBelongsToTask(Task $task, Subtask $subtask): void
    {
        abort_unless((int) $subtask->task_id === (int) $task->id, 404);
    }

    private function respond(Request $request, Task $task, string $message): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $this->taskPayload($task);
        }

        return redirect()
            ->route('user.tasks.show', $task)
            ->with('success', $message);
    }

    private function taskPayload(Task $task): JsonResponse
    {
        $task = $this->tasks->loadDetails($task->fresh());
        $canAssign = auth()->user()?->can(\App\Support\AppPermission::TASKS_ASSIGN) ?? false;

        return response()->json([
            'summary' => $task->summary,
            'panel' => 'details',
            'task_id' => $task->id,
            'task_url' => route('user.tasks.show', $task),
            'comments_count' => (int) ($task->comments_count ?? 0),
            'progress' => $task->subtaskProgress(),
            'assignees_html' => view('components.user.assignee-stack', [
                'assignees' => $task->assignees,
            ])->render(),
            'html' => view('user.tasks.partials.show-content', [
                'task' => $task,
                'canAssign' => $canAssign,
                'assignableUsers' => $canAssign ? $this->tasks->assignableUsers() : collect(),
            ])->render(),
        ]);
    }
}
