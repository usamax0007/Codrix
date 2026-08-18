<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Support\AppPermission;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SubtaskFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(TaskStatusSeeder::class);
    }

    public function test_assignee_can_create_and_toggle_subtasks_and_progress_is_calculated(): void
    {
        [$admin, $staff, $task] = $this->seedTaskPair();

        $this->actingAs($staff)
            ->postJson(route('user.tasks.subtasks.store', $task), [
                'title' => 'Create Header',
            ])
            ->assertOk();

        $this->actingAs($staff)
            ->postJson(route('user.tasks.subtasks.store', $task), [
                'title' => 'Create Footer',
            ])
            ->assertOk();

        $task->loadCount([
            'subtasks',
            'subtasks as completed_subtasks_count' => fn ($query) => $query->where('is_completed', true),
        ]);
        $this->assertSame(0, $task->subtaskProgress()['percent']);

        $subtask = $task->subtasks()->where('title', 'Create Header')->firstOrFail();

        $this->actingAs($staff)
            ->patchJson(route('user.tasks.subtasks.toggle', [$task, $subtask]))
            ->assertOk();

        $task->refresh()->loadCount([
            'subtasks',
            'subtasks as completed_subtasks_count' => fn ($query) => $query->where('is_completed', true),
        ]);

        $progress = $task->subtaskProgress();
        $this->assertSame(2, $progress['total']);
        $this->assertSame(1, $progress['completed']);
        $this->assertSame(50, $progress['percent']);
        $this->assertTrue($subtask->fresh()->is_completed);

        // Completing all subtasks must not change task status.
        $statusId = $task->task_status_id;
        $other = $task->subtasks()->where('title', 'Create Footer')->firstOrFail();
        $this->actingAs($staff)
            ->patchJson(route('user.tasks.subtasks.toggle', [$task, $other]))
            ->assertOk();

        $this->assertSame($statusId, $task->fresh()->task_status_id);
        $this->assertSame(100, $task->fresh()->loadCount([
            'subtasks',
            'subtasks as completed_subtasks_count' => fn ($query) => $query->where('is_completed', true),
        ])->subtaskProgress()['percent']);
    }

    public function test_user_cannot_manage_subtasks_for_inaccessible_task(): void
    {
        [$admin, $assignee, $task] = $this->seedTaskPair();
        $outsider = $this->makeUser(UserRole::User, [
            AppPermission::TASKS_ACCESS,
            AppPermission::PROJECTS_ACCESS,
        ]);

        $this->actingAs($outsider)
            ->postJson(route('user.tasks.subtasks.store', $task), [
                'title' => 'Should fail',
            ])
            ->assertForbidden();

        $subtask = Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'Existing',
            'is_completed' => false,
            'sort_order' => 0,
            'created_by' => $admin->id,
        ]);

        $this->actingAs($outsider)
            ->patchJson(route('user.tasks.subtasks.toggle', [$task, $subtask]))
            ->assertForbidden();
    }

    public function test_can_edit_and_reorder_subtasks(): void
    {
        [$admin, $staff, $task] = $this->seedTaskPair();

        $first = Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'First',
            'sort_order' => 0,
            'created_by' => $staff->id,
        ]);
        $second = Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'Second',
            'sort_order' => 1,
            'created_by' => $staff->id,
        ]);

        $this->actingAs($staff)
            ->putJson(route('user.tasks.subtasks.update', [$task, $first]), [
                'title' => 'First updated',
                'description' => 'Details',
            ])
            ->assertOk();

        $this->assertSame('First updated', $first->fresh()->title);

        $this->actingAs($staff)
            ->patchJson(route('user.tasks.subtasks.reorder', $task), [
                'ordered_ids' => [$second->id, $first->id],
            ])
            ->assertOk();

        $this->assertSame(0, $second->fresh()->sort_order);
        $this->assertSame(1, $first->fresh()->sort_order);
    }

    public function test_progress_is_zero_when_no_subtasks(): void
    {
        [, , $task] = $this->seedTaskPair();

        $this->assertSame([
            'total' => 0,
            'completed' => 0,
            'remaining' => 0,
            'percent' => 0,
        ], $task->subtaskProgress());
    }

    /**
     * @return array{0: User, 1: User, 2: Task}
     */
    private function seedTaskPair(): array
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
        ]);
        $staff = $this->makeUser(UserRole::User, [
            AppPermission::TASKS_ACCESS,
            AppPermission::PROJECTS_ACCESS,
        ]);

        $project = Project::query()->create([
            'name' => 'Demo',
            'slug' => 'demo-'.uniqid(),
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $task = $this->createTask([
            'summary' => 'Build landing page',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $staff);

        return [$admin, $staff, $task];
    }

    /**
     * @param  list<string>  $permissions
     */
    private function makeUser(UserRole $role, array $permissions): User
    {
        $user = User::query()->create([
            'name' => $role->value.' user',
            'email' => $role->value.'-'.uniqid().'@example.com',
            'password' => 'password',
            'role' => $role,
        ]);

        $spatieRole = Role::findByName($role->value);
        $user->syncRoles([$spatieRole]);

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->syncPermissions($permissions);

        return $user;
    }
}
