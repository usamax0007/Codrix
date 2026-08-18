<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskStatus;
use App\Models\User;
use App\Support\AppPermission;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskAttachmentUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(TaskStatusSeeder::class);
        Storage::fake('public');
    }

    public function test_admin_can_create_task_with_attachment(): void
    {
        $admin = $this->makeUser(UserRole::Admin, [
            AppPermission::TASKS_ACCESS,
            AppPermission::TASKS_ASSIGN,
            AppPermission::PROJECTS_ACCESS,
            AppPermission::PROJECTS_MANAGE,
        ]);
        $staff = $this->makeUser(UserRole::User, [AppPermission::TASKS_ACCESS]);
        $project = Project::query()->create([
            'name' => 'Upload Demo',
            'slug' => 'upload-demo',
            'created_by' => $admin->id,
        ]);
        $todo = TaskStatus::query()->where('slug', 'todo')->firstOrFail();
        $file = UploadedFile::fake()->create('brief.pdf', 500, 'application/pdf');

        $response = $this->actingAs($admin)->post(route('user.tasks.store'), [
            'summary' => 'Task with file',
            'project_id' => $project->id,
            'priority' => 'medium',
            'task_status_id' => $todo->id,
            'assignee_ids' => [$staff->id],
            'attachments' => [$file],
        ]);

        $response->assertRedirect(route('user.tasks.index'));
        $response->assertSessionHas('success');

        $task = Task::query()->where('summary', 'Task with file')->first();
        $this->assertNotNull($task);
        $this->assertDatabaseCount('task_attachments', 1);
        $this->assertTrue(TaskAttachment::query()->where('task_id', $task->id)->exists());
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
