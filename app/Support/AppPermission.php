<?php

namespace App\Support;

final class AppPermission
{
    public const ATTENDANCE_ACCESS = 'attendance.access';

    public const TASKS_ACCESS = 'tasks.access';

    public const TASKS_ASSIGN = 'tasks.assign';

    public const TASKS_DELETE = 'tasks.delete';

    public const TASKS_MANAGE_STATUSES = 'tasks.manage_statuses';

    public const PROJECTS_ACCESS = 'projects.access';

    public const PROJECTS_MANAGE = 'projects.manage';

    public const USERS_MANAGE = 'users.manage';
    
    public const TASKS_CREATE = 'tasks.create';
    
    public const TASKS_CREATE_SUBTASK = 'tasks.create_subtask';

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::ATTENDANCE_ACCESS => 'Attendance access',
            self::TASKS_ACCESS => 'Tasks access',
            self::TASKS_ASSIGN => 'Assign Task',
            self::TASKS_DELETE => 'Delete Task',
            self::TASKS_MANAGE_STATUSES => 'Manage task statuses',
            self::PROJECTS_ACCESS => 'Projects access',
            self::PROJECTS_MANAGE => 'Manage projects',
            self::USERS_MANAGE => 'Manage users',
            self::TASKS_CREATE => 'Create Task',
            self::TASKS_CREATE_SUBTASK => 'Create Subtask',
        ];
    }

    public static function label(string $name): string
    {
        return self::labels()[$name]
            ?? str($name)->replace(['.', '_', '-'], ' ')->title()->toString();
    }
}
