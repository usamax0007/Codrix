<x-user.layout title="Tasks" :wide="true">
    <div
        class="space-y-5"
        data-move-url-template="{{ url('/user/tasks/__TASK__/move') }}"
        data-csrf="{{ csrf_token() }}"
        id="task-board-root"
    >
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Tasks</h1>
                <p class="mt-1 text-sm text-white/50">Drag cards between columns, or click a task for details and comments.</p>
            </div>

            <x-user.button type="button" id="open-task-modal" size="sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Task
            </x-user.button>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach (\App\Enums\TaskStatus::cases() as $status)
                @php
                    $columnTasks = $columns[$status->value] ?? collect();
                @endphp
                <section class="flex min-h-[28rem] flex-col rounded-2xl border border-white/10 bg-xc-card/60">
                    <header class="flex items-center justify-between gap-2 border-b border-white/10 px-3.5 py-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full
                                @switch($status)
                                    @case(\App\Enums\TaskStatus::Todo) bg-white/40 @break
                                    @case(\App\Enums\TaskStatus::InProgress) bg-xc-blue @break
                                    @case(\App\Enums\TaskStatus::Testing) bg-amber-400 @break
                                    @case(\App\Enums\TaskStatus::Done) bg-xc-cyan @break
                                @endswitch
                            "></span>
                            <h2 class="text-sm font-semibold tracking-tight">{{ $status->getLabel() }}</h2>
                        </div>
                        <span class="rounded-md bg-white/5 px-2 py-0.5 text-xs text-white/50" data-column-count="{{ $status->value }}">
                            {{ $columnTasks->count() }}
                        </span>
                    </header>

                    <div
                        class="task-column flex flex-1 flex-col gap-2.5 overflow-y-auto p-3"
                        data-status="{{ $status->value }}"
                    >
                        @foreach ($columnTasks as $task)
                            <x-user.task-card :task="$task" />
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>

    {{-- Add Task Modal --}}
    <div
        id="task-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center p-4"
        aria-hidden="true"
    >
        <div id="task-modal-backdrop" class="absolute inset-0 bg-black/70"></div>

        <div class="relative z-10 flex max-h-[90vh] w-full max-w-2xl flex-col rounded-2xl border border-white/10 bg-xc-dark shadow-2xl shadow-black/40">
            <div class="flex items-center justify-between gap-3 border-b border-white/10 px-5 py-4 sm:px-6">
                <h2 class="text-lg font-semibold tracking-tight">Add Task</h2>
                <button type="button" id="close-task-modal" class="rounded-lg p-2 text-white/50 hover:bg-white/5 hover:text-white" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('user.tasks.store') }}" enctype="multipart/form-data" class="flex min-h-0 flex-1 flex-col" id="create-task-form">
                @csrf

                <div class="flex-1 space-y-4 overflow-y-auto px-5 py-4 sm:px-6">
                    <x-user.input
                        label="Summary"
                        name="summary"
                        required
                        maxlength="255"
                        placeholder="Short summary of the task"
                    />

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-white/80">Description</label>
                        <div id="description-editor" class="quill-editor overflow-hidden rounded-lg border border-white/15 bg-xc-darker/80"></div>
                        <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                        @error('description')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="attachments" class="block text-sm font-medium text-white/80">Attachment</label>
                        <input
                            id="attachments"
                            name="attachments[]"
                            type="file"
                            multiple
                            class="block w-full text-sm text-white/70 file:mr-3 file:rounded-lg file:border-0 file:bg-xc-blue/20 file:px-3 file:py-2 file:text-sm file:font-medium file:text-xc-cyan hover:file:bg-xc-blue/30"
                        >
                        <p class="text-xs text-white/40">Up to 5 files, 10MB each (images, PDF, Office, zip, txt).</p>
                        @error('attachments')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                        @error('attachments.*')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @if ($canAssign)
                            <div class="space-y-1.5">
                                <label for="assignee_id" class="block text-sm font-medium text-white/80">
                                    Assignee <span class="text-xc-cyan">*</span>
                                </label>
                                <select
                                    id="assignee_id"
                                    name="assignee_id"
                                    required
                                    class="w-full rounded-lg border border-white/15 bg-xc-darker/80 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50"
                                >
                                    <option value="">Select user</option>
                                    @foreach ($assignees as $assignee)
                                        <option value="{{ $assignee->id }}" @selected((string) old('assignee_id') === (string) $assignee->id)>
                                            {{ $assignee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignee_id')
                                    <p class="text-xs text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="space-y-1.5">
                            <label for="priority" class="block text-sm font-medium text-white/80">
                                Priority <span class="text-xc-cyan">*</span>
                            </label>
                            <select
                                id="priority"
                                name="priority"
                                required
                                class="w-full rounded-lg border border-white/15 bg-xc-darker/80 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50"
                            >
                                @foreach (\App\Enums\TaskPriority::options() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('priority', 'medium') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="status" class="block text-sm font-medium text-white/80">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="w-full rounded-lg border border-white/15 bg-xc-darker/80 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50"
                            >
                                @foreach (\App\Enums\TaskStatus::options() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'todo') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="due_date" class="block text-sm font-medium text-white/80">Due date</label>
                            <input
                                id="due_date"
                                name="due_date"
                                type="date"
                                value="{{ old('due_date') }}"
                                class="date-input w-full rounded-lg border border-white/15 bg-xc-darker/80 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50"
                                style="color-scheme: dark;"
                            >
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-white/10 px-5 py-4 sm:px-6">
                    <x-user.button type="button" variant="outline" size="sm" id="cancel-task-modal">Cancel</x-user.button>
                    <x-user.button type="submit" size="sm">Create Task</x-user.button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <style>
            .quill-editor .ql-toolbar.ql-snow {
                border: 0;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                background: rgba(2, 12, 25, 0.6);
            }
            .quill-editor .ql-container.ql-snow {
                border: 0;
                color: #fff;
                font-size: 0.875rem;
                min-height: 140px;
            }
            .quill-editor .ql-editor.ql-blank::before {
                color: rgba(255,255,255,0.35);
                font-style: normal;
            }
            .quill-editor .ql-stroke { stroke: rgba(255,255,255,0.65); }
            .quill-editor .ql-fill { fill: rgba(255,255,255,0.65); }
            .quill-editor .ql-picker { color: rgba(255,255,255,0.75); }
            .quill-editor .ql-picker-options { background: #0C1623; border-color: rgba(255,255,255,0.15); }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
        <script>
            (function () {
                const root = document.getElementById('task-board-root');
                const modal = document.getElementById('task-modal');
                const openBtn = document.getElementById('open-task-modal');
                const closeBtn = document.getElementById('close-task-modal');
                const cancelBtn = document.getElementById('cancel-task-modal');
                const backdrop = document.getElementById('task-modal-backdrop');
                const form = document.getElementById('create-task-form');
                const descriptionInput = document.getElementById('description');

                let quill = null;
                if (document.getElementById('description-editor') && window.Quill) {
                    quill = new Quill('#description-editor', {
                        theme: 'snow',
                        placeholder: 'Describe the task…',
                        modules: {
                            toolbar: [
                                [{ header: [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline'],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                ['link'],
                                ['clean'],
                            ],
                        },
                    });

                    if (descriptionInput?.value) {
                        quill.root.innerHTML = descriptionInput.value;
                    }
                }

                form?.addEventListener('submit', () => {
                    if (quill && descriptionInput) {
                        const html = quill.root.innerHTML;
                        descriptionInput.value = html === '<p><br></p>' ? '' : html;
                    }
                });

                const openModal = () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.setAttribute('aria-hidden', 'false');
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    modal.setAttribute('aria-hidden', 'true');
                };

                openBtn?.addEventListener('click', openModal);
                closeBtn?.addEventListener('click', closeModal);
                cancelBtn?.addEventListener('click', closeModal);
                backdrop?.addEventListener('click', closeModal);

                const dueDateInput = document.getElementById('due_date');
                dueDateInput?.addEventListener('click', () => {
                    if (typeof dueDateInput.showPicker === 'function') {
                        try { dueDateInput.showPicker(); } catch (e) {}
                    }
                });

                @if ($errors->any())
                    openModal();
                @endif

                let dragging = false;

                document.querySelectorAll('.task-card').forEach((card) => {
                    card.addEventListener('click', (event) => {
                        if (dragging) return;
                        if (event.target.closest('.task-card-actions')) return;
                        const url = card.dataset.taskUrl;
                        if (url) window.location.href = url;
                    });
                });

                if (!root || typeof Sortable === 'undefined') return;

                const csrf = root.dataset.csrf;
                const urlTemplate = root.dataset.moveUrlTemplate;

                const updateCounts = () => {
                    document.querySelectorAll('.task-column').forEach((column) => {
                        const status = column.dataset.status;
                        const badge = document.querySelector(`[data-column-count="${status}"]`);
                        if (badge) badge.textContent = column.querySelectorAll('.task-card').length;
                    });
                };

                const persistMove = async (evt) => {
                    const card = evt.item;
                    const taskId = card.dataset.taskId;
                    const column = evt.to;
                    const status = column.dataset.status;
                    const orderedIds = Array.from(column.querySelectorAll('.task-card')).map((el) => Number(el.dataset.taskId));
                    const url = urlTemplate.replace('__TASK__', taskId);

                    try {
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({ status, ordered_ids: orderedIds }),
                        });

                        if (!response.ok) throw new Error('Move failed');
                        updateCounts();
                    } catch (error) {
                        window.location.reload();
                    }
                };

                document.querySelectorAll('.task-column').forEach((column) => {
                    Sortable.create(column, {
                        group: 'tasks',
                        animation: 150,
                        draggable: '.task-card',
                        ghostClass: 'opacity-40',
                        dragClass: 'shadow-lg',
                        onStart: () => { dragging = true; },
                        onEnd: () => { setTimeout(() => { dragging = false; }, 50); },
                        onAdd: persistMove,
                        onUpdate: persistMove,
                    });
                });
            })();
        </script>
    @endpush
</x-user.layout>
