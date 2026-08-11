<x-user.layout title="Task Statuses" :wide="true">
    <div class="space-y-5" id="status-manager-root" data-reorder-url="{{ route('user.task-statuses.reorder') }}" data-csrf="{{ csrf_token() }}">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Task Statuses</h1>
                <p class="mt-1 text-sm text-white/50">Add, rename, recolor, reorder, enable/disable, or delete Kanban columns.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-user.button href="{{ route('user.tasks.index') }}" variant="outline" size="sm">Back to board</x-user.button>
                <x-user.button type="button" size="sm" id="open-status-modal">Add Status</x-user.button>
            </div>
        </div>

        <x-user.flash />

        <div class="overflow-hidden rounded-2xl border border-white/10 bg-xc-card/60">
            <div class="border-b border-white/10 px-4 py-3 text-xs font-medium uppercase tracking-wider text-white/40 sm:px-5">
                Drag rows to reorder · Disabled statuses stay hidden on the board
            </div>

            <ul id="status-list" class="divide-y divide-white/5">
                @forelse ($statuses as $status)
                    <li
                        class="status-row flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5"
                        data-status-id="{{ $status->id }}"
                    >
                        <div class="flex min-w-0 items-center gap-3">
                            <button type="button" class="status-handle cursor-grab rounded-lg p-1.5 text-white/35 hover:bg-white/5 hover:text-white active:cursor-grabbing" aria-label="Drag to reorder">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm8-12a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0z"/>
                                </svg>
                            </button>
                            <span class="h-3 w-3 shrink-0 rounded-full" style="background-color: {{ $status->color }}"></span>
                            <div class="min-w-0">
                                <p class="truncate font-medium text-white">{{ $status->name }}</p>
                                <p class="text-xs text-white/40">
                                    {{ $status->tasks_count }} task{{ $status->tasks_count === 1 ? '' : 's' }}
                                    · {{ $status->is_enabled ? 'Enabled' : 'Disabled' }}
                                    · {{ $status->is_completed ? 'Completed type' : 'Active type' }}
                                    · {{ $status->color }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                            <form method="POST" action="{{ route('user.task-statuses.toggle', $status) }}">
                                @csrf
                                @method('PATCH')
                                <x-user.button type="submit" variant="outline" size="sm">
                                    {{ $status->is_enabled ? 'Disable' : 'Enable' }}
                                </x-user.button>
                            </form>

                            <x-user.button
                                type="button"
                                variant="outline"
                                size="sm"
                                class="edit-status-btn"
                                data-id="{{ $status->id }}"
                                data-name="{{ $status->name }}"
                                data-color="{{ $status->color }}"
                                data-enabled="{{ $status->is_enabled ? '1' : '0' }}"
                                data-completed="{{ $status->is_completed ? '1' : '0' }}"
                                data-action="{{ route('user.task-statuses.update', $status) }}"
                            >
                                Edit
                            </x-user.button>

                            <form method="POST" action="{{ route('user.task-statuses.destroy', $status) }}" onsubmit="return confirm('Delete this status? Only allowed when no tasks use it.')">
                                @csrf
                                @method('DELETE')
                                @if ($status->tasks_count > 0)
                                    <x-user.button type="button" variant="danger" size="sm" disabled>
                                        Delete
                                    </x-user.button>
                                @else
                                    <x-user.button type="submit" variant="danger" size="sm">
                                        Delete
                                    </x-user.button>
                                @endif
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-white/45">No statuses yet. Add your first column.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Create / Edit modal --}}
    <div id="status-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-hidden="true">
        <div id="status-modal-backdrop" class="absolute inset-0 bg-xc-darker/80 backdrop-blur-sm"></div>
        <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-white/[0.08] bg-xc-card shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4">
                <h2 id="status-modal-title" class="text-lg font-semibold">Add Status</h2>
                <button type="button" id="close-status-modal" class="rounded-lg p-2 text-white/40 hover:bg-white/5 hover:text-white" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="status-form" method="POST" action="{{ route('user.task-statuses.store') }}" class="space-y-4 px-5 py-4">
                @csrf
                <input type="hidden" name="_method" id="status-form-method" value="POST">

                <div class="space-y-1.5">
                    <label for="status_name" class="block text-sm font-medium text-white/80">Name <span class="text-xc-cyan">*</span></label>
                    <input
                        id="status_name"
                        name="name"
                        type="text"
                        required
                        maxlength="100"
                        class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40"
                        placeholder="e.g. Blocked"
                    >
                </div>

                <div class="space-y-1.5">
                    <label for="status_color" class="block text-sm font-medium text-white/80">Color <span class="text-xc-cyan">*</span></label>
                    <div class="flex items-center gap-3">
                        <input id="status_color" name="color" type="color" value="#94A3B8" class="h-10 w-14 cursor-pointer rounded-lg border border-white/10 bg-transparent p-1">
                        <input id="status_color_text" type="text" value="#94A3B8" maxlength="7" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40" pattern="^#[0-9A-Fa-f]{6}$">
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-white/70">
                    <input type="hidden" name="is_enabled" value="0">
                    <input id="status_enabled" type="checkbox" name="is_enabled" value="1" checked class="rounded border-white/20 bg-xc-darker text-xc-cyan focus:ring-xc-cyan/40">
                    Enabled on the board
                </label>

                <label class="flex items-center gap-2 text-sm text-white/70">
                    <input type="hidden" name="is_completed" value="0">
                    <input id="status_completed" type="checkbox" name="is_completed" value="1" class="rounded border-white/20 bg-xc-darker text-xc-cyan focus:ring-xc-cyan/40">
                    Counts as completed for project progress
                </label>

                <div class="flex justify-end gap-2 border-t border-white/10 pt-4">
                    <x-user.button type="button" variant="outline" size="sm" id="cancel-status-modal">Cancel</x-user.button>
                    <x-user.button type="submit" size="sm" id="status-submit-btn">Create Status</x-user.button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
        <script>
            (function () {
                const modal = document.getElementById('status-modal');
                const form = document.getElementById('status-form');
                const methodInput = document.getElementById('status-form-method');
                const title = document.getElementById('status-modal-title');
                const submitBtn = document.getElementById('status-submit-btn');
                const nameInput = document.getElementById('status_name');
                const colorInput = document.getElementById('status_color');
                const colorText = document.getElementById('status_color_text');
                const enabledInput = document.getElementById('status_enabled');
                const completedInput = document.getElementById('status_completed');
                const storeAction = @json(route('user.task-statuses.store'));

                const openModal = () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                };
                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                };

                const resetCreate = () => {
                    form.action = storeAction;
                    methodInput.value = 'POST';
                    title.textContent = 'Add Status';
                    submitBtn.textContent = 'Create Status';
                    nameInput.value = '';
                    colorInput.value = '#94A3B8';
                    colorText.value = '#94A3B8';
                    enabledInput.checked = true;
                    if (completedInput) completedInput.checked = false;
                };

                document.getElementById('open-status-modal')?.addEventListener('click', () => {
                    resetCreate();
                    openModal();
                });
                document.getElementById('close-status-modal')?.addEventListener('click', closeModal);
                document.getElementById('cancel-status-modal')?.addEventListener('click', closeModal);
                document.getElementById('status-modal-backdrop')?.addEventListener('click', closeModal);

                colorInput?.addEventListener('input', () => { colorText.value = colorInput.value.toUpperCase(); });
                colorText?.addEventListener('input', () => {
                    if (/^#[0-9A-Fa-f]{6}$/.test(colorText.value)) {
                        colorInput.value = colorText.value;
                    }
                });

                document.querySelectorAll('.edit-status-btn').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        form.action = btn.dataset.action;
                        methodInput.value = 'PUT';
                        title.textContent = 'Edit Status';
                        submitBtn.textContent = 'Save Changes';
                        nameInput.value = btn.dataset.name || '';
                        colorInput.value = btn.dataset.color || '#94A3B8';
                        colorText.value = (btn.dataset.color || '#94A3B8').toUpperCase();
                        enabledInput.checked = btn.dataset.enabled === '1';
                        if (completedInput) completedInput.checked = btn.dataset.completed === '1';
                        openModal();
                    });
                });

                const root = document.getElementById('status-manager-root');
                const list = document.getElementById('status-list');
                if (root && list && typeof Sortable !== 'undefined') {
                    Sortable.create(list, {
                        handle: '.status-handle',
                        animation: 150,
                        draggable: '.status-row',
                        onEnd: async () => {
                            const orderedIds = Array.from(list.querySelectorAll('.status-row')).map((el) => Number(el.dataset.statusId));
                            try {
                                const response = await fetch(root.dataset.reorderUrl, {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': root.dataset.csrf,
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: JSON.stringify({ ordered_ids: orderedIds }),
                                });
                                if (!response.ok) throw new Error('Reorder failed');
                            } catch (e) {
                                window.location.reload();
                            }
                        },
                    });
                }
            })();
        </script>
    @endpush
</x-user.layout>
