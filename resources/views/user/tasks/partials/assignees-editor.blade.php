@props([
    'task',
    'assignableUsers' => null,
    'compact' => false,
])
@php
    $canAssign = auth()->user()?->can(\App\Support\AppPermission::TASKS_ASSIGN) ?? false;
    $users = collect($assignableUsers ?? []);
    if ($canAssign && $users->isEmpty()) {
        $users = app(\App\Services\Task\TaskBoardService::class)->assignableUsers();
    }
    $selectedIds = collect(old('assignee_ids', $task->assignees->pluck('id')->all()))
        ->map(fn ($id) => (string) $id);
@endphp
@if ($canAssign)
    <form
        method="POST"
        action="{{ route('user.tasks.assignees.update', $task) }}"
        class="space-y-2"
        data-task-assignees-form
        data-autosave="1"
    >
        @csrf
        @method('PATCH')
        <div class="space-y-1.5" data-assignee-multiselect>
            <div class="relative">
                <button
                    type="button"
                    data-assignee-toggle
                    class="flex w-full items-center justify-between gap-2 rounded-xl border border-white/10 bg-xc-darker/90 px-3 py-2 text-left text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
                >
                    <span data-assignee-placeholder class="truncate text-white/45">Select users</span>
                    <svg class="h-4 w-4 shrink-0 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div
                    data-assignee-menu
                    class="absolute z-20 mt-2 hidden w-full overflow-hidden rounded-xl border border-white/10 bg-xc-card shadow-xl shadow-black/40"
                >
                    <div class="border-b border-white/10 p-2">
                        <input
                            type="search"
                            data-assignee-search
                            placeholder="Search users…"
                            class="w-full rounded-lg border border-white/10 bg-xc-darker/90 px-3 py-2 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-2 focus:ring-xc-cyan/40"
                        >
                    </div>
                    <ul class="max-h-48 overflow-y-auto p-1" data-assignee-options>
                        @forelse ($users as $assignee)
                            <li>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm text-white/80 transition hover:bg-white/5"
                                    data-assignee-option
                                    data-name="{{ strtolower($assignee->name) }}"
                                >
                                    <input
                                        type="checkbox"
                                        name="assignee_ids[]"
                                        value="{{ $assignee->id }}"
                                        data-assignee-checkbox
                                        data-label="{{ $assignee->name }}"
                                        @checked($selectedIds->contains((string) $assignee->id))
                                        class="h-4 w-4 rounded border-white/20 bg-xc-darker text-xc-cyan focus:ring-xc-cyan/40"
                                    >
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-xc-blue/30 text-[10px] font-semibold text-xc-cyan">
                                        {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                    </span>
                                    <span class="min-w-0 truncate">{{ $assignee->name }}</span>
                                </label>
                            </li>
                        @empty
                            <li class="px-3 py-4 text-xs text-white/40">No assignable users available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="flex flex-wrap gap-1.5 empty:hidden" data-assignee-chips></div>
            <p class="hidden text-xs text-red-300" data-assignees-error></p>
            @error('assignee_ids')
                <p class="text-xs text-red-300">{{ $message }}</p>
            @enderror
            @error('assignee_ids.*')
                <p class="text-xs text-red-300">{{ $message }}</p>
            @enderror
            <p class="text-[11px] text-white/35">Changes save when you select or remove someone.</p>
        </div>
    </form>
@else
    <x-user.assignee-stack :assignees="$task->assignees" class="text-sm font-medium text-white" />
@endif
