<?php

namespace App\Services\Progress;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Support\AppPermission;
use Illuminate\Database\Eloquent\Builder;

class ProgressService
{
    /**
     * @return array{total: int, completed: int, remaining: int, percent: int}
     */
    public function ratio(int $completed, int $total): array
    {
        $total = max(0, $total);
        $completed = max(0, min($completed, $total));
        $remaining = max(0, $total - $completed);
        $percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'remaining' => $remaining,
            'percent' => $percent,
        ];
    }

    /**
     * Task progress from subtasks.
     *
     * @return array{total: int, completed: int, remaining: int, percent: int}
     */
    public function forTask(Task $task): array
    {
        $total = (int) ($task->subtasks_count ?? $task->subtasks()->count());
        $completed = (int) ($task->completed_subtasks_count ?? $task->subtasks()->where('is_completed', true)->count());

        return $this->ratio($completed, $total);
    }

    /**
     * Project progress from tasks whose dynamic status is_completed = true.
     *
     * @return array{total: int, completed: int, remaining: int, percent: int}
     */
    public function forProject(Project $project): array
    {
        $total = (int) ($project->tasks_count ?? $project->tasks()->count());
        $completed = (int) ($project->completed_tasks_count ?? $project->tasks()
            ->whereHas('status', fn (Builder $query) => $query->where('is_completed', true))
            ->count());

        return $this->ratio($completed, $total);
    }

    /**
     * Dashboard aggregates for the signed-in user (respects task visibility rules).
     *
     * @return array{
     *     can_access_tasks: bool,
     *     can_access_projects: bool,
     *     total_tasks: int,
     *     completed_tasks: int,
     *     pending_tasks: int,
     *     overdue_tasks: int,
     *     due_soon_tasks: int,
     *     projects_count: int,
     *     overall: array{total: int, completed: int, remaining: int, percent: int},
     *     recent_tasks: \Illuminate\Support\Collection<int, Task>,
     *     top_projects: \Illuminate\Support\Collection<int, Project>
     * }
     */
    public function dashboardFor(User $user): array
    {
        $canAccessTasks = $user->can(AppPermission::TASKS_ACCESS);
        $canAccessProjects = $user->can(AppPermission::PROJECTS_ACCESS);

        $totalTasks = 0;
        $completedTasks = 0;
        $overdueTasks = 0;
        $dueSoonTasks = 0;
        $projectsCount = 0;
        $recentTasks = collect();
        $topProjects = collect();

        if ($canAccessTasks) {
            $taskQuery = Task::query();

            if (! $user->can(AppPermission::TASKS_ASSIGN)) {
                $taskQuery->whereHas('assignees', fn (Builder $assignees) => $assignees->where('users.id', $user->id));
            }

            $totalTasks = (clone $taskQuery)->count();
            $completedTasks = (clone $taskQuery)
                ->whereHas('status', fn (Builder $query) => $query->where('is_completed', true))
                ->count();

            $openTaskQuery = (clone $taskQuery)
                ->whereHas('status', fn (Builder $query) => $query->where('is_completed', false));

            $overdueTasks = (clone $openTaskQuery)
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', now()->toDateString())
                ->count();

            $dueSoonTasks = (clone $openTaskQuery)
                ->whereNotNull('due_date')
                ->whereDate('due_date', '>=', now()->toDateString())
                ->whereDate('due_date', '<=', now()->addDays(7)->toDateString())
                ->count();

            $recentTasks = (clone $taskQuery)
                ->with(['status', 'project:id,name', 'assignees:id,name'])
                ->withCount([
                    'subtasks',
                    'subtasks as completed_subtasks_count' => fn (Builder $query) => $query->where('is_completed', true),
                ])
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();
        }

        if ($canAccessProjects) {
            $projectQuery = Project::query();

            if (! $user->can(AppPermission::PROJECTS_MANAGE) && ! $user->can(AppPermission::TASKS_ASSIGN)) {
                $projectQuery->whereHas(
                    'tasks',
                    fn (Builder $tasks) => $tasks->whereHas(
                        'assignees',
                        fn (Builder $assignees) => $assignees->where('users.id', $user->id)
                    )
                );
            }

            $projectsCount = (clone $projectQuery)->count();

            $topProjects = (clone $projectQuery)
                ->withCount([
                    'tasks',
                    'tasks as completed_tasks_count' => fn (Builder $query) => $query
                        ->whereHas('status', fn (Builder $status) => $status->where('is_completed', true)),
                ])
                ->orderByDesc('updated_at')
                ->limit(4)
                ->get();
        }

        $overall = $this->ratio($completedTasks, $totalTasks);

        return [
            'can_access_tasks' => $canAccessTasks,
            'can_access_projects' => $canAccessProjects,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => max(0, $totalTasks - $completedTasks),
            'overdue_tasks' => $overdueTasks,
            'due_soon_tasks' => $dueSoonTasks,
            'projects_count' => $projectsCount,
            'overall' => $overall,
            'recent_tasks' => $recentTasks,
            'top_projects' => $topProjects,
        ];
    }

    /**
     * ASCII-style progress bar string (20 cells).
     */
    public function asciiBar(int $percent): string
    {
        $percent = max(0, min(100, $percent));
        $filled = (int) floor($percent / 5);

        return str_repeat('█', $filled).str_repeat('░', 20 - $filled);
    }
}
