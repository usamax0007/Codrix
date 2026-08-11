<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Project;
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

class ProjectFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(TaskStatusSeeder::class);
    }

    public function test_admin_can_create_project_and_view_progress_using_is_completed_status(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
        ]);

        $staff = $this->makeUser(UserRole::User, [
            AppPermission::PROJECTS_ACCESS,
            AppPermission::TASKS_ACCESS,
        ]);

        $this->actingAs($admin)
            ->post(route('user.projects.store'), [
                'name' => 'Portal Redesign',
                'description' => 'Rebuild the portal UX',
                'start_date' => now()->toDateString(),
                'due_date' => now()->addWeeks(2)->toDateString(),
            ])
            ->assertRedirect();

        $project = Project::query()->where('slug', 'portal-redesign')->firstOrFail();
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();
        $done = TaskStatus::query()->where('slug', 'done')->firstOrFail();

        $this->assertFalse($todo->is_completed);
        $this->assertTrue($done->is_completed);

        Task::query()->create([
            'summary' => 'Open task',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'assignee_id' => $staff->id,
            'created_by' => $admin->id,
            'sort_order' => 0,
        ]);

        Task::query()->create([
            'summary' => 'Finished task',
            'project_id' => $project->id,
            'task_status_id' => $done->id,
            'priority' => 'medium',
            'assignee_id' => $staff->id,
            'created_by' => $admin->id,
            'sort_order' => 1,
        ]);

        $project->loadCount([
            'tasks',
            'tasks as completed_tasks_count' => fn ($query) => $query
                ->whereHas('status', fn ($status) => $status->where('is_completed', true)),
        ]);

        $progress = $project->progressStats();

        $this->assertSame(2, $progress['total']);
        $this->assertSame(1, $progress['completed']);
        $this->assertSame(1, $progress['remaining']);
        $this->assertSame(50, $progress['percent']);
    }

    public function test_cannot_delete_project_with_tasks(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
        ]);
        $staff = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS]);
        $project = Project::query()->create([
            'name' => 'Locked Project',
            'slug' => 'locked-project',
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        Task::query()->create([
            'summary' => 'Blocking task',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'assignee_id' => $staff->id,
            'created_by' => $admin->id,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->delete(route('user.projects.destroy', $project))
            ->assertSessionHasErrors('project');

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_task_create_requires_project(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
        ]);
        $staff = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('user.tasks.store'), [
                'summary' => 'Missing project',
                'priority' => 'medium',
                'task_status_id' => $todo->id,
                'assignee_id' => $staff->id,
            ])
            ->assertSessionHasErrors('project_id');
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
