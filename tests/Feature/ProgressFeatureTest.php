<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Services\Progress\ProgressService;
use App\Support\AppPermission;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProgressFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(TaskStatusSeeder::class);
    }

    public function test_task_progress_from_subtask_completion(): void
    {
        [, , $task] = $this->seedTaskPair();

        Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'One',
            'is_completed' => true,
            'sort_order' => 0,
            'created_by' => $task->created_by,
        ]);
        Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'Two',
            'is_completed' => false,
            'sort_order' => 1,
            'created_by' => $task->created_by,
        ]);
        Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'Three',
            'is_completed' => false,
            'sort_order' => 2,
            'created_by' => $task->created_by,
        ]);

        $progress = app(ProgressService::class)->forTask($task->fresh()->loadCount([
            'subtasks',
            'subtasks as completed_subtasks_count' => fn ($query) => $query->where('is_completed', true),
        ]));

        $this->assertSame([
            'total' => 3,
            'completed' => 1,
            'remaining' => 2,
            'percent' => 33,
        ], $progress);
    }

    public function test_task_progress_is_zero_with_no_subtasks(): void
    {
        [, , $task] = $this->seedTaskPair();

        $this->assertSame([
            'total' => 0,
            'completed' => 0,
            'remaining' => 0,
            'percent' => 0,
        ], app(ProgressService::class)->forTask($task));
    }

    public function test_project_progress_uses_dynamic_is_completed_status_not_name(): void
    {
        [$admin, $staff, $project] = $this->seedProjectPair();

        $customCompleted = TaskStatus::query()->create([
            'name' => 'Shipped',
            'slug' => 'shipped-'.uniqid(),
            'color' => '#22C55E',
            'sort_order' => 99,
            'is_enabled' => true,
            'is_completed' => true,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $this->createTask([
            'summary' => 'Open',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $staff->id);
        $this->createTask([
            'summary' => 'Closed via custom status',
            'project_id' => $project->id,
            'task_status_id' => $customCompleted->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 1,
        ], $staff->id);
        $this->createTask([
            'summary' => 'Also closed',
            'project_id' => $project->id,
            'task_status_id' => $customCompleted->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 2,
        ], $staff->id);

        $project->loadCount([
            'tasks',
            'tasks as completed_tasks_count' => fn ($query) => $query
                ->whereHas('status', fn ($status) => $status->where('is_completed', true)),
        ]);

        $progress = app(ProgressService::class)->forProject($project);

        $this->assertSame(3, $progress['total']);
        $this->assertSame(2, $progress['completed']);
        $this->assertSame(1, $progress['remaining']);
        $this->assertSame(67, $progress['percent']);
    }

    public function test_project_progress_is_zero_with_no_tasks(): void
    {
        [, , $project] = $this->seedProjectPair();

        $this->assertSame([
            'total' => 0,
            'completed' => 0,
            'remaining' => 0,
            'percent' => 0,
        ], app(ProgressService::class)->forProject($project));
    }

    public function test_dashboard_stats_respect_visibility_for_regular_user(): void
    {
        [$admin, $staff, $project] = $this->seedProjectPair();
        $outsider = $this->makeUser(UserRole::User, [
            AppPermission::TASKS_ACCESS,
            AppPermission::PROJECTS_ACCESS,
        ]);

        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();
        $done = TaskStatus::query()->where('slug', 'done')->firstOrFail();

        $this->createTask([
            'summary' => 'Staff open',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $staff->id);
        $this->createTask([
            'summary' => 'Staff done',
            'project_id' => $project->id,
            'task_status_id' => $done->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 1,
        ], $staff->id);
        $this->createTask([
            'summary' => 'Outsider task',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 2,
        ], $outsider->id);

        $staffStats = app(ProgressService::class)->dashboardFor($staff);
        $this->assertSame(2, $staffStats['total_tasks']);
        $this->assertSame(1, $staffStats['completed_tasks']);
        $this->assertSame(1, $staffStats['pending_tasks']);
        $this->assertSame(1, $staffStats['projects_count']);
        $this->assertSame(50, $staffStats['overall']['percent']);

        $outsiderStats = app(ProgressService::class)->dashboardFor($outsider);
        $this->assertSame(1, $outsiderStats['total_tasks']);
        $this->assertSame(0, $outsiderStats['completed_tasks']);
        $this->assertSame(1, $outsiderStats['pending_tasks']);
        $this->assertSame(1, $outsiderStats['projects_count']);

        $adminStats = app(ProgressService::class)->dashboardFor($admin);
        $this->assertSame(3, $adminStats['total_tasks']);
        $this->assertSame(1, $adminStats['completed_tasks']);
        $this->assertSame(2, $adminStats['pending_tasks']);
    }

    public function test_dashboard_renders_progress_overview(): void
    {
        [$admin, $staff, $project] = $this->seedProjectPair();
        $done = TaskStatus::query()->where('slug', 'done')->firstOrFail();

        $this->createTask([
            'summary' => 'Visible',
            'project_id' => $project->id,
            'task_status_id' => $done->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $staff->id);

        $this->actingAs($staff)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Overall progress')
            ->assertSee('Recent tasks')
            ->assertSee('100%');
    }

    public function test_project_show_and_kanban_include_progress_markup(): void
    {
        [$admin, $staff, $project] = $this->seedProjectPair();
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $task = $this->createTask([
            'summary' => 'Progress card task',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $staff->id);

        Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'A',
            'is_completed' => true,
            'sort_order' => 0,
            'created_by' => $staff->id,
        ]);
        Subtask::query()->create([
            'task_id' => $task->id,
            'title' => 'B',
            'is_completed' => false,
            'sort_order' => 1,
            'created_by' => $staff->id,
        ]);

        $this->actingAs($staff)
            ->get(route('user.projects.show', $project))
            ->assertOk()
            ->assertSee('Project overview')
            ->assertSee('Total Tasks')
            ->assertSee('1 / 2 completed');

        $this->actingAs($staff)
            ->get(route('user.tasks.index'))
            ->assertOk()
            ->assertSee('Subtasks')
            ->assertSee('1 / 2 completed')
            ->assertSee('50%');

        $this->actingAs($staff)
            ->getJson(route('user.tasks.show', $task))
            ->assertOk()
            ->assertJsonPath('progress.percent', 50)
            ->assertJsonPath('progress.total', 2)
            ->assertJsonPath('progress.completed', 1)
            ->assertJsonPath('progress.remaining', 1);
    }

    /**
     * @return array{0: User, 1: User, 2: Task}
     */
    private function seedTaskPair(): array
    {
        [$admin, $staff, $project] = $this->seedProjectPair();
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        $task = $this->createTask([
            'summary' => 'Task',
            'project_id' => $project->id,
            'task_status_id' => $todo->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $staff->id);

        return [$admin, $staff, $task];
    }

    /**
     * @return array{0: User, 1: User, 2: Project}
     */
    private function seedProjectPair(): array
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
            'name' => 'Progress Demo',
            'slug' => 'progress-demo-'.uniqid(),
            'created_by' => $admin->id,
        ]);

        return [$admin, $staff, $project];
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
