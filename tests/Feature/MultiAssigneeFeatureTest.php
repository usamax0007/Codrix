<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\TaskStatus;
use App\Models\User;
use App\Support\AppPermission;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MultiAssigneeFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(TaskStatusSeeder::class);
    }

    public function test_admin_can_assign_task_to_multiple_users(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
        ]);
        $alice = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS, AppPermission::PROJECTS_ACCESS]);
        $bob = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS, AppPermission::PROJECTS_ACCESS]);
        $carol = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS, AppPermission::PROJECTS_ACCESS]);

        $project = Project::query()->create([
            'name' => 'Shared',
            'slug' => 'shared-'.uniqid(),
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('user.tasks.store'), [
                'summary' => 'Team task',
                'project_id' => $project->id,
                'priority' => 'medium',
                'task_status_id' => $todo->id,
                'assignee_ids' => [$alice->id, $bob->id],
            ])
            ->assertRedirect(route('user.tasks.index'));

        $task = $project->tasks()->where('summary', 'Team task')->firstOrFail();

        $this->assertEqualsCanonicalizing(
            [$alice->id, $bob->id],
            $task->assignees()->pluck('users.id')->all()
        );

        $this->actingAs($alice)->get(route('user.tasks.show', $task))->assertOk();
        $this->actingAs($bob)->get(route('user.tasks.show', $task))->assertOk();
        $this->actingAs($carol)->get(route('user.tasks.show', $task))->assertForbidden();

        $this->actingAs($alice)
            ->get(route('user.tasks.index'))
            ->assertOk()
            ->assertSee('Team task');

        $this->actingAs($carol)
            ->get(route('user.tasks.index'))
            ->assertOk()
            ->assertDontSee('Team task');
    }

    public function test_create_requires_at_least_one_assignee_when_assigning(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
        ]);
        $project = Project::query()->create([
            'name' => 'Shared',
            'slug' => 'shared-'.uniqid(),
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('user.tasks.store'), [
                'summary' => 'No assignees',
                'project_id' => $project->id,
                'priority' => 'medium',
                'task_status_id' => $todo->id,
                'assignee_ids' => [],
            ])
            ->assertSessionHasErrors('assignee_ids');
    }

    public function test_staff_cannot_create_task_in_unrelated_project(): void
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
        $secret = Project::query()->create([
            'name' => 'Secret',
            'slug' => 'secret-'.uniqid(),
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $this->actingAs($staff)
            ->post(route('user.tasks.store'), [
                'summary' => 'Sneaky join',
                'project_id' => $secret->id,
                'priority' => 'medium',
                'task_status_id' => $todo->id,
            ])
            ->assertSessionHasErrors('project_id');

        $this->assertDatabaseMissing('tasks', [
            'summary' => 'Sneaky join',
            'project_id' => $secret->id,
        ]);
    }

    public function test_admin_can_update_task_assignees(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
        ]);
        $alice = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS, AppPermission::PROJECTS_ACCESS]);
        $bob = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS, AppPermission::PROJECTS_ACCESS]);
        $project = Project::query()->create([
            'name' => 'Shared',
            'slug' => 'shared-'.uniqid(),
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $task = $this->createTask([
            'summary' => 'Reassign me',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $alice);

        $this->actingAs($admin)
            ->patch(route('user.tasks.assignees.update', $task), [
                'assignee_ids' => [$bob->id],
            ])
            ->assertRedirect(route('user.tasks.show', $task));

        $this->assertEqualsCanonicalizing(
            [$bob->id],
            $task->fresh()->assignees()->pluck('users.id')->all()
        );

        $this->actingAs($alice)->get(route('user.tasks.show', $task))->assertForbidden();
        $this->actingAs($bob)->get(route('user.tasks.show', $task))->assertOk();
    }

    public function test_staff_cannot_update_assignees(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
        ]);
        $alice = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS, AppPermission::PROJECTS_ACCESS]);
        $bob = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS, AppPermission::PROJECTS_ACCESS]);
        $project = Project::query()->create([
            'name' => 'Shared',
            'slug' => 'shared-'.uniqid(),
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $task = $this->createTask([
            'summary' => 'Locked assignees',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $alice);

        $this->actingAs($alice)
            ->patch(route('user.tasks.assignees.update', $task), [
                'assignee_ids' => [$alice->id, $bob->id],
            ])
            ->assertForbidden();
    }

    /**
     * @param  list<string>  $permissions
     */
    private function makeUser(UserRole $role, array $permissions): User
    {
        $user = User::query()->create([
            'name' => $role->value.' '.uniqid(),
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
