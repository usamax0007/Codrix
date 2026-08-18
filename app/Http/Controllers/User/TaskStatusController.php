<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReorderTaskStatusesRequest;
use App\Http\Requests\User\StoreTaskStatusRequest;
use App\Http\Requests\User\UpdateTaskStatusRequest;
use App\Models\TaskStatus;
use App\Services\Task\TaskStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskStatusController extends Controller
{
    public function __construct(private readonly TaskStatusService $statuses) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', TaskStatus::class);

        return view('user.task-statuses.index', [
            'statuses' => $this->statuses->allOrdered(),
        ]);
    }

    public function store(StoreTaskStatusRequest $request): RedirectResponse
    {
        $this->statuses->create($request->statusData());

        return redirect()
            ->route('user.task-statuses.index')
            ->with('success', 'Status created successfully.');
    }

    public function update(UpdateTaskStatusRequest $request, TaskStatus $taskStatus): RedirectResponse
    {
        $this->statuses->update($taskStatus, $request->statusData());

        return redirect()
            ->route('user.task-statuses.index')
            ->with('success', 'Status updated successfully.');
    }

    public function toggle(Request $request, TaskStatus $taskStatus): RedirectResponse
    {
        $this->authorize('update', $taskStatus);

        $this->statuses->toggle($taskStatus);

        return redirect()
            ->route('user.task-statuses.index')
            ->with('success', $taskStatus->fresh()?->is_enabled ? 'Status enabled.' : 'Status disabled.');
    }

    public function reorder(ReorderTaskStatusesRequest $request): JsonResponse
    {
        $this->statuses->reorder($request->orderedIds());

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, TaskStatus $taskStatus): RedirectResponse
    {
        $this->authorize('delete', $taskStatus);

        $this->statuses->delete($taskStatus);

        return redirect()
            ->route('user.task-statuses.index')
            ->with('success', 'Status deleted successfully.');
    }
}
