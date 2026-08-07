<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\AttendanceSetting;
use App\Models\User;
use App\Services\Attendance\AttendanceService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    #[Url]
    public ?string $userId = null;

    /** @var list<string> */
    public array $workingDays = [];

    public string $workFrom = '09:00';

    public string $workTo = '18:00';

    public function mount(): void
    {
        parent::mount();

        $settings = AttendanceSetting::current();

        $this->workingDays = array_values(array_map('strval', $settings->working_days ?? []));
        $this->workFrom = substr((string) $settings->work_from, 0, 5);
        $this->workTo = substr((string) $settings->work_to, 0, 5);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function updatedUserId(): void
    {
        $this->resetPage();
    }

    public function saveSettings(): void
    {
        $validated = $this->validate([
            'workingDays' => ['required', 'array', 'min:1'],
            'workingDays.*' => ['required', 'string', Rule::in(array_keys(AttendanceSetting::weekdayOptions()))],
            'workFrom' => ['required', 'date_format:H:i'],
            'workTo' => ['required', 'date_format:H:i', 'after:workFrom'],
        ], [
            'workingDays.required' => 'Select at least one working day.',
            'workTo.after' => 'Working hours To must be after From.',
        ]);

        $record = AttendanceSetting::query()->first() ?? new AttendanceSetting;

        $record->fill([
            'working_days' => array_values($validated['workingDays']),
            'work_from' => $validated['workFrom'],
            'work_to' => $validated['workTo'],
        ])->save();

        Notification::make()
            ->success()
            ->title('Attendance settings saved')
            ->send();
    }

    /**
     * @return Collection<int, User>
     */
    public function getStaffUsersProperty(): Collection
    {
        return User::query()
            ->where('role', UserRole::User)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @return array<string, string>
     */
    public function getWeekdayOptionsProperty(): array
    {
        return AttendanceSetting::weekdayOptions();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaView::make('filament.resources.attendances.toolbar')
                    ->liberatedFromContainerGrid(),
                RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE),
                EmbeddedTable::make(),
                RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER),
            ]);
    }

    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();

        if (! $query) {
            return null;
        }

        if (blank($this->userId)) {
            return $query->whereRaw('0 = 1');
        }

        $user = User::query()->find($this->userId);

        if (! $user) {
            return $query->whereRaw('0 = 1');
        }

        $attendance = app(AttendanceService::class);
        $attendance->pruneAbsentsBeforeFirstCheckIn($user);

        $firstCheckIn = $attendance->firstCheckInDate($user);

        if (! $firstCheckIn) {
            return $query->where('user_id', $user->id)->whereRaw('0 = 1');
        }

        $attendance->ensureAbsentsForUser($user, $firstCheckIn, now());

        return $query
            ->where('user_id', $user->id)
            ->whereDate('work_date', '>=', $firstCheckIn->toDateString());
    }
}
