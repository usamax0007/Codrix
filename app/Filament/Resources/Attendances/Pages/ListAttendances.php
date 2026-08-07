<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\User;
use App\Services\Attendance\AttendanceService;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();

        if (! $query) {
            return null;
        }

        $userId = data_get($this->tableFilters, 'user_id.value');

        if (blank($userId)) {
            return $query->whereRaw('0 = 1');
        }

        $user = User::query()->find($userId);

        if ($user) {
            app(AttendanceService::class)->ensureAbsentsForUser(
                $user,
                now()->subDays(60)->startOfDay(),
                now(),
            );
        }

        return $query->where('user_id', $userId);
    }

    public function updatedTableFilters(): void
    {
        $this->resetPage();
    }
}
