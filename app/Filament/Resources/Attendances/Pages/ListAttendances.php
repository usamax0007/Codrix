<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
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

        return $query->where('user_id', $userId);
    }

    public function updatedTableFilters(): void
    {
        $this->resetPage();
    }
}
