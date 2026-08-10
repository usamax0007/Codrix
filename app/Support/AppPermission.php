<?php

namespace App\Support;

final class AppPermission
{
    public const ATTENDANCE_ACCESS = 'attendance.access';

    public const TASKS_ACCESS = 'tasks.access';

    public const TASKS_ASSIGN = 'tasks.assign';

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::ATTENDANCE_ACCESS => 'Attendance access',
            self::TASKS_ACCESS => 'Tasks access',
            self::TASKS_ASSIGN => 'Assign tasks',
        ];
    }

    public static function label(string $name): string
    {
        return self::labels()[$name]
            ?? str($name)->replace(['.', '_', '-'], ' ')->title()->toString();
    }
}
