<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Services\Task\TaskBoardService;
use App\Support\AppPermission;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskBoardColumnPaginationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(TaskStatusSeeder::class);
    }

    public function test_board_loads_only_first_ten_tasks_per_column(): void
    {
        [$admin, $staff, $project, $todo] = $this->seedBoardContext();

        for ($i = 1; $i <= 15; $i++) {
            $this->createTask([
                'summary' => "Todo {$i}",
                'project_id' => $project->id,
                'task_status_id' => $todo->id,
                'priority' => 'medium',
                'created_by' => $admin->id,
                'sort_order' => $i,
            ], $staff);
        }

        $board = app(TaskBoardService::class)->boardFor($staff);

        $this->assertCount(10, $board['columns'][$todo->id]);
        $this->assertSame(15, $board['totals'][$todo->id]);
        $this->assertSame(10, $board['page_size']);

        $this->actingAs($staff)
            ->get(route('user.tasks.index'))
            ->assertOk()
            ->assertSee('Todo 1')
            ->assertSee('Todo 10')
            ->assertDontSee('Todo 11');
    }

    public function test_column_endpoint_loads_next_ten_with_cursor(): void
    {
        [$admin, $staff, $project, $todo] = $this->seedBoardContext();

        $ids = [];
        for ($i = 1; $i <= 25; $i++) {
            $ids[] = $this->createTask([
                'summary' => "Paged {$i}",
                'project_id' => $project->id,
                'task_status_id' => $todo->id,
                'priority' => 'medium',
                'created_by' => $admin->id,
                'sort_order' => $i,
            ], $staff)->id;
        }

        $firstPage = $this->actingAs($staff)
            ->getJson(route('user.tasks.columns', $todo))
            ->assertOk()
            ->json();

        $this->assertTrue($firstPage['has_more']);
        $this->assertSame(25, $firstPage['total']);
        $this->assertStringContainsString('Paged 1', $firstPage['html']);
        $this->assertStringContainsString('Paged 10', $firstPage['html']);
        $this->assertStringNotContainsString('Paged 11', $firstPage['html']);

        $secondPage = $this->actingAs($staff)
            ->getJson(route('user.tasks.columns', ['task_status' => $todo, 'after_id' => $ids[9]]))
            ->assertOk()
            ->json();

        $this->assertTrue($secondPage['has_more']);
        $this->assertStringContainsString('Paged 11', $secondPage['html']);
        $this->assertStringContainsString('Paged 20', $secondPage['html']);
        $this->assertStringNotContainsString('Paged 21', $secondPage['html']);

        $thirdPage = $this->actingAs($staff)
            ->getJson(route('user.tasks.columns', ['task_status' => $todo, 'after_id' => $ids[19]]))
            ->assertOk()
            ->json();

        $this->assertFalse($thirdPage['has_more']);
        $this->assertStringContainsString('Paged 21', $thirdPage['html']);
        $this->assertStringContainsString('Paged 25', $thirdPage['html']);
    }

    public function test_move_with_partial_column_preserves_unloaded_tasks(): void
    {
        [$admin, $staff, $project, $todo] = $this->seedBoardContext();
        $done = TaskStatus::query()->where('slug', 'done')->firstOrFail();

        $tasks = [];
        for ($i = 1; $i <= 12; $i++) {
            $tasks[] = $this->createTask([
                'summary' => "Keep {$i}",
                'project_id' => $project->id,
                'task_status_id' => $todo->id,
                'priority' => 'medium',
                'created_by' => $admin->id,
                'sort_order' => $i,
            ], $staff);
        }

        $moving = $this->createTask([
            'summary' => 'Moving in',
            'project_id' => $project->id,
            'task_status_id' => $done->id,
            'priority' => 'medium',
            'created_by' => $admin->id,
            'sort_order' => 0,
        ], $staff);

        // Simulate only first 10 todo cards loaded + moved card inserted after first.
        $orderedIds = [
            $tasks[0]->id,
            $moving->id,
            $tasks[1]->id,
            $tasks[2]->id,
            $tasks[3]->id,
            $tasks[4]->id,
            $tasks[5]->id,
            $tasks[6]->id,
            $tasks[7]->id,
            $tasks[8]->id,
            $tasks[9]->id,
        ];

        $this->actingAs($admin)
            ->patchJson(route('user.tasks.move', $moving), [
                'task_status_id' => $todo->id,
                'ordered_ids' => $orderedIds,
            ])
            ->assertOk();

        $this->assertSame($todo->id, $moving->fresh()->task_status_id);
        $this->assertSame(12, Task::query()->where('task_status_id', $todo->id)->whereKeyNot($moving->id)->count());
        $this->assertDatabaseHas('tasks', ['id' => $tasks[10]->id, 'task_status_id' => $todo->id]);
        $this->assertDatabaseHas('tasks', ['id' => $tasks[11]->id, 'task_status_id' => $todo->id]);
    }

    /**
     * @return array{0: User, 1: User, 2: Project, 3: TaskStatus}
     */
    private function seedBoardContext(): array
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
            'name' => 'Board',
            'slug' => 'board-'.uniqid(),
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();

        return [$admin, $staff, $project, $todo];
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
