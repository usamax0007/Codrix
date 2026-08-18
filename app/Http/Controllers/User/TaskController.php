<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\MoveTaskRequest;
use App\Http\Requests\User\StoreTaskCommentRequest;
use App\Http\Requests\User\StoreTaskRequest;
use App\Http\Requests\User\UpdateTaskAssigneesRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Services\Project\ProjectService;
use App\Services\Task\TaskBoardService;
use App\Support\AppPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskBoardService $tasks,
        private readonly ProjectService $projects,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $user = $request->user();
        $board = $this->tasks->boardFor($user);

        return view('user.tasks.index', [
            'user' => $user,
            'statuses' => $board['statuses'],
            'columns' => $board['columns'],
            'columnTotals' => $board['totals'],
            'columnPageSize' => $board['page_size'],
            'projects' => $this->projects->optionsForTaskForm($user),
            'canAssign' => $user->can(AppPermission::TASKS_ASSIGN),
            'canManageStatuses' => $user->can(AppPermission::TASKS_MANAGE_STATUSES),
            'assignees' => $user->can(AppPermission::TASKS_ASSIGN)
                ? $this->tasks->assignableUsers()
                : collect(),
        ]);
    }

    public function column(Request $request, TaskStatus $taskStatus): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        abort_unless($taskStatus->is_enabled, 404);

        $afterId = $request->query('after_id');
        $afterId = filled($afterId) ? (int) $afterId : null;

        $result = $this->tasks->columnPage($request->user(), $taskStatus, $afterId);

        $html = $result['tasks']
            ->map(fn (Task $task): string => view('components.user.task-card', ['task' => $task])->render())
            ->implode('');

        return response()->json([
            'html' => $html,
            'per_page' => $result['per_page'],
            'total' => $result['total'],
            'has_more' => $result['has_more'],
        ]);
    }

    public function show(Request $request, Task $task): View|JsonResponse
    {
        $this->authorize('view', $task);

        $task = $this->tasks->loadDetails($task);
        $panel = $request->query('panel') === 'comments' ? 'comments' : 'details';
        $threadId = filled($request->query('thread')) ? (int) $request->query('thread') : null;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($this->taskModalPayload($task, $panel, $threadId));
        }

        return view('user.tasks.show', [
            'user' => $request->user(),
            'task' => $task,
            'canAssign' => $request->user()->can(AppPermission::TASKS_ASSIGN),
            'assignableUsers' => $request->user()->can(AppPermission::TASKS_ASSIGN)
                ? $this->tasks->assignableUsers()
                : collect(),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->tasks->create($request->user(), $request->taskData(), $request->attachments());

        return redirect()
            ->route('user.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function updateAssignees(UpdateTaskAssigneesRequest $request, Task $task): RedirectResponse|JsonResponse
    {
        $this->tasks->syncAssignees($task, $request->assigneeIds());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($this->taskModalPayload($this->tasks->loadDetails($task->fresh())));
        }

        return redirect()
            ->route('user.tasks.show', $task)
            ->with('success', 'Assignees updated.');
    }

    public function storeComment(StoreTaskCommentRequest $request, Task $task): RedirectResponse|JsonResponse
    {
        $this->tasks->addComment($task, $request->user(), $request->body(), $request->parentId());

        if ($request->wantsJson() || $request->ajax()) {
            $task = $this->tasks->loadDetails($task->fresh());

            return response()->json($this->taskModalPayload(
                $task,
                $request->returnPanel(),
                $request->threadId()
            ));
        }

        return redirect()
            ->route('user.tasks.index')
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

    /**
     * @return array{summary: string, panel: string, html: string, task_url: string, task_id: int, comments_count: int, progress: array{total: int, completed: int, remaining: int, percent: int}, assignees_html: string}
     */
    private function taskModalPayload(Task $task, string $panel = 'details', ?int $threadId = null): array
    {
        $panel = $panel === 'comments' ? 'comments' : 'details';

        return [
            'summary' => $task->summary,
            'panel' => $panel,
            'task_id' => $task->id,
            'task_url' => route('user.tasks.show', $task),
            'comments_count' => (int) ($task->comments_count ?? $task->comments()->count()),
            'progress' => $task->subtaskProgress(),
            'assignees_html' => view('components.user.assignee-stack', [
                'assignees' => $task->assignees,
            ])->render(),
            'html' => view(
                $panel === 'comments'
                    ? 'user.tasks.partials.comments-panel'
                    : 'user.tasks.partials.show-content',
                [
                    'task' => $task,
                    'threadId' => $threadId,
                    'canAssign' => auth()->user()?->can(AppPermission::TASKS_ASSIGN) ?? false,
                    'assignableUsers' => (auth()->user()?->can(AppPermission::TASKS_ASSIGN) ?? false)
                        ? $this->tasks->assignableUsers()
                        : collect(),
                ]
            )->render(),
        ];
    }
}
