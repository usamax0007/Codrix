<?php

namespace App\Services\Project;

use App\Models\Project;
use App\Models\User;
use App\Support\AppPermission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ProjectService
{
    /**
     * @return Collection<int, Project>
     */
    public function listFor(User $user): Collection
    {
        return $this->visibleQuery($user)
            ->with(['creator:id,name'])
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn (Builder $query) => $query
                    ->whereHas('status', fn (Builder $status) => $status->where('is_completed', true)),
            ])
            ->orderBy('name')
            ->get();
    }

    /**
     * Projects available in the Add Task dropdown (same visibility as the projects list).
     *
     * @return Collection<int, Project>
     */
    public function optionsForTaskForm(User $user): Collection
    {
        if (! $user->can(AppPermission::TASKS_ACCESS)) {
            return collect();
        }

        return $this->visibleQuery($user)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function userCanCreateTaskIn(User $user, int $projectId): bool
    {
        if (! $user->can(AppPermission::TASKS_ACCESS)) {
            return false;
        }

        return $this->visibleQuery($user)->whereKey($projectId)->exists();
    }

    public function loadDetails(Project $project, User $user): Project
    {
        $project->load(['creator:id,name']);
        $project->loadCount([
            'tasks',
            'tasks as completed_tasks_count' => fn (Builder $query) => $query
                ->whereHas('status', fn (Builder $status) => $status->where('is_completed', true)),
        ]);

        $tasksQuery = $project->tasks()
            ->with(['status', 'assignees:id,name', 'project:id,name'])
            ->withCount([
                'allComments as comments_count',
                'attachments',
                'subtasks',
                'subtasks as completed_subtasks_count' => fn (Builder $query) => $query->where('is_completed', true),
            ])
            ->orderByDesc('updated_at');

        if (! $user->can(AppPermission::TASKS_ASSIGN)) {
            $tasksQuery->whereHas('assignees', fn (Builder $assignees) => $assignees->where('users.id', $user->id));
        }

        $project->setRelation('visibleTasks', $tasksQuery->get());

        return $project;
    }

    /**
     * @param  array{name: string, description?: ?string, start_date?: ?string, due_date?: ?string}  $data
     */
    public function create(User $actor, array $data): Project
    {
        return Project::query()->create([
            'name' => $data['name'],
            'slug' => Project::uniqueSlugFromName($data['name']),
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'created_by' => $actor->id,
        ]);
    }

    /**
     * @param  array{name: string, description?: ?string, start_date?: ?string, due_date?: ?string}  $data
     */
    public function update(Project $project, array $data): Project
    {
        $project->fill([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ]);

        // Keep slug stable unless name changed and slug still matches old auto pattern.
        if ($project->isDirty('name')) {
            // Do not auto-change slug on rename to preserve URLs/references.
        }

        $project->save();

        return $project->refresh();
    }

    public function delete(Project $project): void
    {
        if ($project->tasks()->exists()) {
            throw ValidationException::withMessages([
                'project' => 'This project still has tasks. Move or delete those tasks before deleting the project.',
            ]);
        }

        $project->delete();
    }

    private function visibleQuery(User $user): Builder
    {
        $query = Project::query();

        if ($user->can(AppPermission::PROJECTS_MANAGE) || $user->can(AppPermission::TASKS_ASSIGN)) {
            return $query;
        }

        return $query->whereHas(
            'tasks',
            fn (Builder $tasks) => $tasks->whereHas(
                'assignees',
                fn (Builder $assignees) => $assignees->where('users.id', $user->id)
            )
        );
    }
}
