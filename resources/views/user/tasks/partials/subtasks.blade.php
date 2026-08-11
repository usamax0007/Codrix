@php
    /** @var \App\Models\Task $task */
    $progress = $task->subtaskProgress();
    $canManageSubtasks = auth()->user()?->can('update', $task) ?? false;
@endphp
<section data-subtasks-root data-task-id="{{ $task->id }}" data-reorder-url="{{ route('user.tasks.subtasks.reorder', $task) }}" data-store-url="{{ route('user.tasks.subtasks.store', $task) }}">
    <div class="mb-3 flex items-center justify-between gap-3">
        <h3 class="text-xs font-semibold uppercase tracking-wider text-white/40">Subtasks</h3>
        @if ($canManageSubtasks)
            <button type="button" class="text-xs font-medium text-xc-cyan hover:underline" data-open-add-subtask>+ Add Subtask</button>
        @endif
    </div>

    <div class="mb-4">
        <x-user.progress-meter
            :progress="$progress"
            empty-label="No subtasks"
            count-noun="complete"
            size="md"
        />
    </div>

    @if ($canManageSubtasks)
        <form method="POST" action="{{ route('user.tasks.subtasks.store', $task) }}" class="mb-4 hidden space-y-2 rounded-xl border border-white/10 bg-xc-darker/50 p-3" data-add-subtask-form>
            @csrf
            <input
                type="text"
                name="title"
                required
                maxlength="255"
                placeholder="Subtask title"
                class="w-full rounded-lg border border-white/10 bg-xc-darker/90 px-3 py-2 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-2 focus:ring-xc-cyan/40"
            >
            <textarea
                name="description"
                rows="2"
                maxlength="5000"
                placeholder="Description (optional)"
                class="w-full rounded-lg border border-white/10 bg-xc-darker/90 px-3 py-2 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-2 focus:ring-xc-cyan/40"
            ></textarea>
            <p class="hidden text-xs text-red-300" data-subtask-error></p>
            <div class="flex justify-end gap-2">
                <button type="button" class="rounded-lg px-3 py-1.5 text-xs text-white/50 hover:text-white" data-cancel-add-subtask>Cancel</button>
                <x-user.button type="submit" size="sm">Add</x-user.button>
            </div>
        </form>
    @endif

    <ul class="space-y-2" data-subtask-list>
        @forelse ($task->subtasks as $subtask)
            <li
                class="subtask-row rounded-xl border border-white/10 bg-xc-darker/40 px-3 py-2.5"
                data-subtask-id="{{ $subtask->id }}"
                data-toggle-url="{{ route('user.tasks.subtasks.toggle', [$task, $subtask]) }}"
                data-update-url="{{ route('user.tasks.subtasks.update', [$task, $subtask]) }}"
                data-delete-url="{{ route('user.tasks.subtasks.destroy', [$task, $subtask]) }}"
            >
                <div class="flex items-start gap-2.5" data-subtask-view>
                    @if ($canManageSubtasks)
                        <button type="button" class="subtask-handle mt-0.5 cursor-grab rounded p-0.5 text-white/30 hover:text-white/60 active:cursor-grabbing" aria-label="Drag to reorder">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm8-12a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('user.tasks.subtasks.toggle', [$task, $subtask]) }}" data-subtask-toggle-form>
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="mt-0.5 flex h-5 w-5 items-center justify-center rounded border {{ $subtask->is_completed ? 'border-xc-cyan bg-xc-cyan/20 text-xc-cyan' : 'border-white/25 text-transparent' }}" aria-label="{{ $subtask->is_completed ? 'Mark incomplete' : 'Mark complete' }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                    @else
                        <span class="mt-0.5 flex h-5 w-5 items-center justify-center rounded border {{ $subtask->is_completed ? 'border-xc-cyan bg-xc-cyan/20 text-xc-cyan' : 'border-white/25 text-transparent' }}">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="text-sm {{ $subtask->is_completed ? 'text-white/45 line-through' : 'text-white/85' }}">{{ $subtask->title }}</p>
                        @if ($subtask->description)
                            <p class="mt-0.5 text-xs text-white/40">{{ $subtask->description }}</p>
                        @endif
                    </div>

                    @if ($canManageSubtasks)
                        <div class="flex shrink-0 items-center gap-1">
                            <button type="button" class="rounded p-1 text-white/35 hover:text-xc-cyan" data-edit-subtask aria-label="Edit subtask">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('user.tasks.subtasks.destroy', [$task, $subtask]) }}" data-subtask-delete-form>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded p-1 text-white/35 hover:text-red-300" aria-label="Delete subtask">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                @if ($canManageSubtasks)
                    <form method="POST" action="{{ route('user.tasks.subtasks.update', [$task, $subtask]) }}" class="mt-2 hidden space-y-2" data-edit-subtask-form>
                        @csrf
                        @method('PUT')
                        <input type="text" name="title" required maxlength="255" value="{{ $subtask->title }}" class="w-full rounded-lg border border-white/10 bg-xc-darker/90 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40">
                        <textarea name="description" rows="2" maxlength="5000" class="w-full rounded-lg border border-white/10 bg-xc-darker/90 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40">{{ $subtask->description }}</textarea>
                        <p class="hidden text-xs text-red-300" data-subtask-error></p>
                        <div class="flex justify-end gap-2">
                            <button type="button" class="rounded-lg px-3 py-1.5 text-xs text-white/50 hover:text-white" data-cancel-edit-subtask>Cancel</button>
                            <x-user.button type="submit" size="sm">Save</x-user.button>
                        </div>
                    </form>
                @endif
            </li>
        @empty
            @unless ($canManageSubtasks)
                <li class="text-sm text-white/40">No subtasks yet.</li>
            @endunless
        @endforelse
    </ul>
</section>
