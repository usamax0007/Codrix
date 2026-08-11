<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Resources\Users\UserResource;
use App\Services\Admin\AdminDashboardService;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOpsStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Operations overview';

    protected ?string $description = 'Live counts across users, portal work, and attendance.';

    protected int|array|null $columns = 4;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stats = app(AdminDashboardService::class)->stats();
        $completedPct = $stats['tasks'] > 0
            ? (int) round(($stats['tasks_completed'] / $stats['tasks']) * 100)
            : 0;

        return [
            Stat::make('Portal users', number_format($stats['users_total']))
                ->description($stats['users_admin'].' admins · '.$stats['users_staff'].' staff')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('primary')
                ->url(UserResource::getUrl('index')),
            Stat::make('Projects', number_format($stats['projects']))
                ->description('Active project records')
                ->descriptionIcon(Heroicon::OutlinedFolder)
                ->color('info'),
            Stat::make('Tasks', number_format($stats['tasks']))
                ->description($stats['tasks_completed'].' completed ('.$completedPct.'%)')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('success'),
            Stat::make('Attendance today', number_format($stats['attendance_today']))
                ->description($stats['attendance_open'].' still checked in')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('warning')
                ->url(AttendanceResource::getUrl('index')),
            Stat::make('Contacts', number_format($stats['contacts']))
                ->description($stats['contacts_week'].' in the last 7 days')
                ->descriptionIcon(Heroicon::OutlinedEnvelope)
                ->color('danger')
                ->url(ContactResource::getUrl('index')),
        ];
    }
}
