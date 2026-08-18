<x-user.layout title="{{ $project->name }}" :wide="true">
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <a href="{{ route('user.projects.index') }}" class="inline-flex items-center gap-1.5 text-sm text-white/50 hover:text-xc-cyan">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to projects
                </a>
                <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight break-words">{{ $project->name }}</h1>
                @if ($project->description)
                    <p class="mt-2 max-w-3xl text-sm text-white/55">{{ $project->description }}</p>
                @endif
            </div>

            @if ($canManage)
                <div class="flex flex-wrap gap-2">
                    <x-user.button type="button" variant="outline" size="sm" id="open-edit-project-modal">Edit</x-user.button>
                    @if ($progress['total'] === 0)
                        <form method="POST" action="{{ route('user.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                            @csrf
                            @method('DELETE')
                            <x-user.button type="submit" variant="danger" size="sm">Delete</x-user.button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <x-user.flash />

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-xc-card/60 p-5 lg:col-span-2">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-white/40">Project overview</h2>
                <p class="mt-3 text-xl font-semibold tracking-tight">{{ $project->name }}</p>
                <div class="mt-4">
                    <x-user.progress-meter
                        :progress="$progress"
                        empty-label="No tasks · 0%"
                        :show-counts="false"
                        size="lg"
                    />
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-white/10 bg-xc-darker/40 px-4 py-3">
                        <p class="text-2xl font-semibold tracking-tight">{{ $progress['completed'] }}</p>
                        <p class="mt-1 text-xs uppercase tracking-wider text-white/40">Completed</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-xc-darker/40 px-4 py-3">
                        <p class="text-2xl font-semibold tracking-tight">{{ $progress['remaining'] }}</p>
                        <p class="mt-1 text-xs uppercase tracking-wider text-white/40">Remaining</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-xc-darker/40 px-4 py-3">
                        <p class="text-2xl font-semibold tracking-tight">{{ $progress['total'] }}</p>
                        <p class="mt-1 text-xs uppercase tracking-wider text-white/40">Total Tasks</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-xc-card/60 p-5">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-white/40">Details</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-white/40">Start date</dt>
                        <dd class="mt-1 font-medium">{{ $project->start_date?->format('M j, Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-white/40">Due date</dt>
                        <dd class="mt-1 font-medium">{{ $project->due_date?->format('M j, Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-white/40">Created by</dt>
                        <dd class="mt-1 font-medium">{{ $project->creator?->name ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <section class="rounded-2xl border border-white/10 bg-xc-card/60">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4">
                <h2 class="text-sm font-semibold tracking-tight">Tasks in this project</h2>
                <a href="{{ route('user.tasks.index') }}" class="text-xs text-xc-cyan hover:underline">Open board</a>
            </div>
            <div class="divide-y divide-white/5">
                @forelse ($project->visibleTasks as $task)
                    @php $taskProgress = $task->subtaskProgress(); @endphp
                    <a href="{{ route('user.tasks.show', $task) }}" class="flex flex-col gap-3 px-5 py-4 transition hover:bg-white/[0.02] sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium text-white">{{ $task->summary }}</p>
                            <p class="mt-1 text-xs text-white/40">
                                @if ($task->assignees->isNotEmpty())
                                    {{ $task->assignees->pluck('name')->implode(', ') }}
                                @else
                                    Unassigned
                                @endif
                                @if ($task->due_date)
                                    · Due {{ $task->due_date->format('M j, Y') }}
                                @endif
                            </p>
                            <div class="mt-2 max-w-xs">
                                <x-user.progress-meter
                                    :progress="$taskProgress"
                                    empty-label="No subtasks"
                                    count-noun="completed"
                                    size="sm"
                                />
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-2 text-xs text-white/60">
                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $task->status?->color ?? '#94A3B8' }}"></span>
                            {{ $task->status?->name ?? '—' }}
                        </span>
                    </a>
                @empty
                    <p class="px-5 py-10 text-center text-sm text-white/40">No tasks in this project yet.</p>
                @endforelse
            </div>
        </section>
    </div>

    @if ($canManage)
        <div id="edit-project-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-hidden="true">
            <div id="edit-project-modal-backdrop" class="absolute inset-0 bg-xc-darker/80 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full max-w-lg overflow-hidden rounded-2xl border border-white/[0.08] bg-xc-card shadow-2xl">
                <div class="flex items-center justify-between border-b border-white/10 px-5 py-4">
                    <h2 class="text-lg font-semibold">Edit Project</h2>
                    <button type="button" id="close-edit-project-modal" class="rounded-lg p-2 text-white/40 hover:bg-white/5 hover:text-white" aria-label="Close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('user.projects.update', $project) }}" class="space-y-4 px-5 py-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1.5">
                        <label for="edit_project_name" class="block text-sm font-medium text-white/80">Name <span class="text-xc-cyan">*</span></label>
                        <input id="edit_project_name" name="name" required maxlength="255" value="{{ old('name', $project->name) }}" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40">
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit_project_description" class="block text-sm font-medium text-white/80">Description</label>
                        <textarea id="edit_project_description" name="description" rows="3" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40">{{ old('description', $project->description) }}</textarea>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1.5">
                            <label for="edit_project_start_date" class="block text-sm font-medium text-white/80">Start date</label>
                            <input id="edit_project_start_date" name="start_date" type="date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40" style="color-scheme: dark;">
                        </div>
                        <div class="space-y-1.5">
                            <label for="edit_project_due_date" class="block text-sm font-medium text-white/80">Due date</label>
                            <input id="edit_project_due_date" name="due_date" type="date" value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40" style="color-scheme: dark;">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-white/10 pt-4">
                        <x-user.button type="button" variant="outline" size="sm" id="cancel-edit-project-modal">Cancel</x-user.button>
                        <x-user.button type="submit" size="sm">Save Changes</x-user.button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
            <script>
                (function () {
                    const modal = document.getElementById('edit-project-modal');
                    const open = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
                    const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
                    document.getElementById('open-edit-project-modal')?.addEventListener('click', open);
                    document.getElementById('close-edit-project-modal')?.addEventListener('click', close);
                    document.getElementById('cancel-edit-project-modal')?.addEventListener('click', close);
                    document.getElementById('edit-project-modal-backdrop')?.addEventListener('click', close);
                })();
            </script>
        @endpush
    @endif
</x-user.layout>
