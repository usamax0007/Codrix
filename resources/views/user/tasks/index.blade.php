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

            <div class="flex flex-wrap items-center gap-2">
                @if ($canManageStatuses ?? false)
                    <x-user.button href="{{ route('user.task-statuses.index') }}" variant="outline" size="sm">
                        Manage Statuses
                    </x-user.button>
                @endif
                <x-user.button type="button" id="open-task-modal" size="sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Task
                </x-user.button>
            </div>
        </div>

        <div
            class="grid gap-4"
            style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));"
        >
            @forelse ($statuses as $status)
                @php
                    $columnTasks = $columns[$status->id] ?? collect();
                @endphp
                <section class="flex min-h-[28rem] flex-col rounded-2xl border border-white/10 bg-xc-card/60">
                    <header class="flex items-center justify-between gap-2 border-b border-white/10 px-3.5 py-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $status->color }}"></span>
                            <h2 class="truncate text-sm font-semibold tracking-tight">{{ $status->name }}</h2>
                        </div>
                        <span class="rounded-md bg-white/5 px-2 py-0.5 text-xs text-white/50" data-column-count="{{ $status->id }}">
                            {{ $columnTasks->count() }}
                        </span>
                    </header>

                    <div
                        class="task-column flex flex-1 flex-col gap-2.5 overflow-y-auto p-3"
                        data-status-id="{{ $status->id }}"
                    >
                        @foreach ($columnTasks as $task)
                            <x-user.task-card :task="$task" />
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-white/15 px-6 py-16 text-center text-sm text-white/45">
                    No enabled statuses. @if ($canManageStatuses ?? false)<a href="{{ route('user.task-statuses.index') }}" class="text-xc-cyan hover:underline">Create a status</a> to start the board.@endif
                </div>
            @endforelse
        </div>
    </div>

    {{-- Add Task Modal --}}
    <div
        id="task-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6"
        aria-hidden="true"
    >
        <div id="task-modal-backdrop" class="absolute inset-0 bg-xc-darker/80 backdrop-blur-sm"></div>

        <div class="relative z-10 flex max-h-[92vh] w-full max-w-xl flex-col overflow-hidden rounded-2xl border border-white/[0.08] bg-xc-card shadow-2xl shadow-black/50 ring-1 ring-white/[0.04]">
            <div class="relative flex items-start justify-between gap-3 px-5 pt-5 pb-4 sm:px-6">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-xc-cyan/10 via-xc-blue/5 to-transparent"></div>
                <div class="relative">
                    <h2 class="text-lg font-semibold tracking-tight text-white">Add Task</h2>
                    <p class="mt-0.5 text-sm text-white/45">Fill in the details to create a new board item.</p>
                </div>
                <button type="button" id="close-task-modal" class="relative -mr-1 -mt-1 rounded-xl p-2 text-white/40 transition hover:bg-white/5 hover:text-white" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('user.tasks.store') }}" enctype="multipart/form-data" class="flex min-h-0 flex-1 flex-col" id="create-task-form">
                @csrf

                <div class="flex-1 space-y-5 overflow-y-auto px-5 pb-2 sm:px-6">
                    <div class="space-y-1.5">
                        <label for="project_id" class="block text-sm font-medium text-white/80">
                            Project <span class="text-xc-cyan">*</span>
                        </label>
                        <select
                            id="project_id"
                            name="project_id"
                            required
                            class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
                        >
                            <option value="">Select project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected((string) old('project_id') === (string) $project->id)>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-user.input
                        label="Summary"
                        name="summary"
                        required
                        maxlength="255"
                        placeholder="Short summary of the task"
                    />

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-white/80">Description</label>
                        <div id="description-editor" class="quill-editor overflow-hidden rounded-xl bg-xc-darker/90"></div>
                        <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                        @error('description')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="attachments" class="block text-sm font-medium text-white/80">Attachment</label>
                        <label
                            for="attachments"
                            class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-xc-darker/50 px-4 py-5 text-center transition hover:border-xc-cyan/40 hover:bg-xc-darker/80"
                        >
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-xc-blue/15 text-xc-cyan">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </span>
                            <span class="text-sm text-white/70">
                                <span class="font-medium text-xc-cyan">Choose files</span>
                                <span class="text-white/40"> or drag & drop</span>
                            </span>
                            <span id="attachments-label" class="text-xs text-white/35">No file chosen · up to 5 files, 10MB each</span>
                        </label>
                        <input
                            id="attachments"
                            name="attachments[]"
                            type="file"
                            multiple
                            class="sr-only"
                        >
                        @error('attachments')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                        @error('attachments.*')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @if ($canAssign)
                            <div class="space-y-1.5 sm:col-span-2">
                                <label for="assignee_id" class="block text-sm font-medium text-white/80">
                                    Assignee <span class="text-xc-cyan">*</span>
                                </label>
                                <select
                                    id="assignee_id"
                                    name="assignee_id"
                                    required
                                    class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
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
                                class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
                            >
                                @foreach (\App\Enums\TaskPriority::options() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('priority', 'medium') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="task_status_id" class="block text-sm font-medium text-white/80">Status</label>
                            <select
                                id="task_status_id"
                                name="task_status_id"
                                class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
                            >
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}" @selected((string) old('task_status_id', $statuses->first()?->id) === (string) $status->id)>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5 sm:col-span-2">
                            <label for="due_date" class="block text-sm font-medium text-white/80">Due date</label>
                            <input
                                id="due_date"
                                name="due_date"
                                type="date"
                                value="{{ old('due_date') }}"
                                class="date-input w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
                                style="color-scheme: dark;"
                            >
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 border-t border-white/[0.06] bg-xc-darker/40 px-5 py-4 sm:px-6">
                    <x-user.button type="button" variant="outline" size="sm" id="cancel-task-modal">Cancel</x-user.button>
                    <x-user.button type="submit" size="sm">Create Task</x-user.button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Task Modal --}}
    <div
        id="view-task-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6"
        aria-hidden="true"
    >
        <div id="view-task-modal-backdrop" class="absolute inset-0 bg-xc-darker/80 backdrop-blur-sm"></div>

        <div class="relative z-10 flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl border border-white/[0.08] bg-xc-card shadow-2xl shadow-black/50 ring-1 ring-white/[0.04]">
            <div class="relative flex items-start justify-between gap-3 px-5 pt-5 pb-4 sm:px-6">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-xc-cyan/10 via-xc-blue/5 to-transparent"></div>
                <div class="relative min-w-0 pr-2">
                    <p class="text-xs font-medium uppercase tracking-wider text-white/40">Task</p>
                    <h2 id="view-task-modal-title" class="mt-0.5 truncate text-lg font-semibold tracking-tight text-white">Loading…</h2>
                </div>
                <button type="button" id="close-view-task-modal" class="relative -mr-1 -mt-1 shrink-0 rounded-xl p-2 text-white/40 transition hover:bg-white/5 hover:text-white" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="view-task-modal-body" class="flex-1 overflow-y-auto px-5 pb-5 sm:px-6">
                <div class="flex items-center justify-center py-16 text-sm text-white/40">Loading task…</div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <style>
            .quill-editor,
            .quill-editor .ql-toolbar.ql-snow,
            .quill-editor .ql-container.ql-snow {
                border: none !important;
                box-shadow: none !important;
            }
            .quill-editor .ql-toolbar.ql-snow {
                padding: 0.55rem 0.75rem;
                background: transparent;
            }
            .quill-editor .ql-container.ql-snow {
                color: #fff;
                font-size: 0.875rem;
                min-height: 132px;
            }
            .quill-editor .ql-editor {
                padding: 0.85rem 0.9rem;
                min-height: 132px;
            }
            .quill-editor .ql-editor.ql-blank::before {
                color: rgba(255,255,255,0.35);
                font-style: normal;
                left: 0.9rem;
            }
            .quill-editor .ql-stroke { stroke: rgba(255,255,255,0.55); }
            .quill-editor .ql-fill { fill: rgba(255,255,255,0.55); }
            .quill-editor .ql-picker { color: rgba(255,255,255,0.7); }
            .quill-editor .ql-picker-label { color: rgba(255,255,255,0.75); }
            .quill-editor .ql-picker-options {
                background: #0C1623;
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 0.5rem;
                padding: 0.35rem 0;
                z-index: 30;
            }
            .quill-editor .ql-picker-options .ql-picker-item {
                color: rgba(255,255,255,0.8);
                padding: 0.4rem 0.75rem;
            }
            .quill-editor .ql-picker-options .ql-picker-item:hover {
                color: #00E5C0;
                background: rgba(0, 229, 192, 0.08);
            }
            /* Restore heading sizes (Tailwind preflight resets h1–h3) */
            .quill-editor .ql-editor h1 {
                font-size: 1.75rem !important;
                font-weight: 700 !important;
                line-height: 1.25;
                margin: 0.35rem 0;
            }
            .quill-editor .ql-editor h2 {
                font-size: 1.375rem !important;
                font-weight: 600 !important;
                line-height: 1.3;
                margin: 0.3rem 0;
            }
            .quill-editor .ql-editor h3 {
                font-size: 1.125rem !important;
                font-weight: 600 !important;
                line-height: 1.35;
                margin: 0.25rem 0;
            }
            .quill-editor .ql-picker.ql-header .ql-picker-item[data-value="1"]::before {
                font-size: 1.35rem;
                font-weight: 700;
            }
            .quill-editor .ql-picker.ql-header .ql-picker-item[data-value="2"]::before {
                font-size: 1.15rem;
                font-weight: 600;
            }
            .quill-editor .ql-picker.ql-header .ql-picker-item[data-value="3"]::before {
                font-size: 1rem;
                font-weight: 600;
            }
            .quill-editor .ql-toolbar.ql-snow .ql-picker-label:hover,
            .quill-editor .ql-toolbar.ql-snow button:hover,
            .quill-editor .ql-toolbar.ql-snow button.ql-active {
                color: #00E5C0;
            }
            .quill-editor .ql-toolbar.ql-snow button:hover .ql-stroke,
            .quill-editor .ql-toolbar.ql-snow button.ql-active .ql-stroke {
                stroke: #00E5C0;
            }
            .quill-editor .ql-toolbar.ql-snow button:hover .ql-fill,
            .quill-editor .ql-toolbar.ql-snow button.ql-active .ql-fill {
                fill: #00E5C0;
            }
            .task-description a { color: #00E5C0; text-decoration: underline; }
            .task-description ul { list-style: disc; margin-left: 1.25rem; }
            .task-description ol { list-style: decimal; margin-left: 1.25rem; }
            .task-description p { margin-bottom: 0.75rem; font-size: 0.875rem; line-height: 1.55; }
            .task-description h1 {
                font-size: 1.75rem !important;
                font-weight: 700 !important;
                line-height: 1.25;
                margin: 0.85rem 0 0.45rem;
                color: #fff;
            }
            .task-description h2 {
                font-size: 1.375rem !important;
                font-weight: 600 !important;
                line-height: 1.3;
                margin: 0.75rem 0 0.4rem;
                color: #fff;
            }
            .task-description h3 {
                font-size: 1.125rem !important;
                font-weight: 600 !important;
                line-height: 1.35;
                margin: 0.65rem 0 0.35rem;
                color: #fff;
            }
            .task-description h1:first-child,
            .task-description h2:first-child,
            .task-description h3:first-child { margin-top: 0; }
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

                const attachmentsInput = document.getElementById('attachments');
                const attachmentsLabel = document.getElementById('attachments-label');
                attachmentsInput?.addEventListener('change', () => {
                    if (!attachmentsLabel) return;
                    const count = attachmentsInput.files?.length ?? 0;
                    attachmentsLabel.textContent = count === 0
                        ? 'No file chosen · up to 5 files, 10MB each'
                        : count === 1
                            ? '1 file selected'
                            : `${count} files selected`;
                });

                @if ($errors->any())
                    openModal();
                @endif

                let dragging = false;

                const viewModal = document.getElementById('view-task-modal');
                const viewModalTitle = document.getElementById('view-task-modal-title');
                const viewModalBody = document.getElementById('view-task-modal-body');
                const closeViewBtn = document.getElementById('close-view-task-modal');
                const viewBackdrop = document.getElementById('view-task-modal-backdrop');
                const boardCsrf = root?.dataset.csrf || '{{ csrf_token() }}';

                const openViewModal = () => {
                    viewModal?.classList.remove('hidden');
                    viewModal?.classList.add('flex');
                    viewModal?.setAttribute('aria-hidden', 'false');
                };

                const closeViewModal = () => {
                    viewModal?.classList.add('hidden');
                    viewModal?.classList.remove('flex');
                    viewModal?.setAttribute('aria-hidden', 'true');
                };

                const renderTaskDetails = (data) => {
                    if (viewModalTitle) {
                        viewModalTitle.textContent = data.panel === 'comments'
                            ? `Comments · ${data.summary || 'Task'}`
                            : (data.summary || 'Task');
                    }
                    if (viewModalBody) viewModalBody.innerHTML = data.html || '';
                    bindTaskModalInteractions();
                };

                const loadTaskPanel = async (url) => {
                    if (!url || !viewModalBody) return;

                    viewModalBody.innerHTML = '<div class="flex items-center justify-center py-16 text-sm text-white/40">Loading…</div>';

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) throw new Error('Failed to load');

                        const data = await response.json();
                        renderTaskDetails(data);
                    } catch (error) {
                        viewModalBody.innerHTML = '<div class="flex items-center justify-center py-16 text-sm text-red-300">Could not load this view.</div>';
                    }
                };

                const bindCommentForms = () => {
                    viewModalBody?.querySelectorAll('[data-task-comment-form]').forEach((commentForm) => {
                        commentForm.addEventListener('submit', async (event) => {
                            event.preventDefault();
                            const errorEl = commentForm.querySelector('[data-comment-error]');
                            if (errorEl) {
                                errorEl.classList.add('hidden');
                                errorEl.textContent = '';
                            }

                            const submitBtn = commentForm.querySelector('button[type="submit"]');
                            if (submitBtn) submitBtn.disabled = true;

                            try {
                                const response = await fetch(commentForm.action, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': boardCsrf,
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: new FormData(commentForm),
                                });

                                const data = await response.json().catch(() => ({}));

                                if (!response.ok) {
                                    const message = data?.errors?.body?.[0]
                                        || data?.errors?.parent_id?.[0]
                                        || data?.message
                                        || 'Could not add comment.';
                                    if (errorEl) {
                                        errorEl.textContent = message;
                                        errorEl.classList.remove('hidden');
                                    }
                                    return;
                                }

                                renderTaskDetails(data);
                            } catch (error) {
                                if (errorEl) {
                                    errorEl.textContent = 'Could not add comment.';
                                    errorEl.classList.remove('hidden');
                                }
                            } finally {
                                if (submitBtn) submitBtn.disabled = false;
                            }
                        });
                    });
                };

                const bindTaskModalInteractions = () => {
                    bindCommentForms();

                    viewModalBody?.querySelectorAll('[data-open-comments]').forEach((btn) => {
                        btn.addEventListener('click', () => loadTaskPanel(btn.dataset.taskUrl));
                    });

                    viewModalBody?.querySelectorAll('[data-back-to-task]').forEach((btn) => {
                        btn.addEventListener('click', () => loadTaskPanel(btn.dataset.taskUrl));
                    });

                    viewModalBody?.querySelectorAll('[data-reply-toggle]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const id = btn.getAttribute('data-reply-toggle');
                            const form = viewModalBody.querySelector(`[data-reply-form="${id}"]`);
                            if (!form) return;

                            viewModalBody.querySelectorAll('[data-reply-form]').forEach((other) => {
                                if (other !== form) other.classList.add('hidden');
                            });

                            form.classList.toggle('hidden');
                            if (!form.classList.contains('hidden')) {
                                form.querySelector('textarea')?.focus();
                            }
                        });
                    });

                    viewModalBody?.querySelectorAll('[data-reply-cancel]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const id = btn.getAttribute('data-reply-cancel');
                            const form = viewModalBody.querySelector(`[data-reply-form="${id}"]`);
                            form?.classList.add('hidden');
                            const textarea = form?.querySelector('textarea');
                            if (textarea) textarea.value = '';
                        });
                    });
                };

                const openTaskDetails = async (url) => {
                    if (!url) return;

                    if (viewModalTitle) viewModalTitle.textContent = 'Loading…';
                    if (viewModalBody) {
                        viewModalBody.innerHTML = '<div class="flex items-center justify-center py-16 text-sm text-white/40">Loading task…</div>';
                    }
                    openViewModal();
                    await loadTaskPanel(url);
                };

                closeViewBtn?.addEventListener('click', closeViewModal);
                viewBackdrop?.addEventListener('click', closeViewModal);
                document.addEventListener('keydown', (event) => {
                    if (event.key !== 'Escape') return;
                    if (viewModal && !viewModal.classList.contains('hidden')) closeViewModal();
                    else if (modal && !modal.classList.contains('hidden')) closeModal();
                });

                document.querySelectorAll('.task-card').forEach((card) => {
                    card.addEventListener('click', (event) => {
                        if (dragging) return;
                        if (event.target.closest('.task-card-actions')) return;
                        openTaskDetails(card.dataset.taskUrl);
                    });
                });

                if (!root || typeof Sortable === 'undefined') return;

                const csrf = root.dataset.csrf;
                const urlTemplate = root.dataset.moveUrlTemplate;

                const updateCounts = () => {
                    document.querySelectorAll('.task-column').forEach((column) => {
                        const statusId = column.dataset.statusId;
                        const badge = document.querySelector(`[data-column-count="${statusId}"]`);
                        if (badge) badge.textContent = column.querySelectorAll('.task-card').length;
                    });
                };

                const persistMove = async (evt) => {
                    const card = evt.item;
                    const taskId = card.dataset.taskId;
                    const column = evt.to;
                    const statusId = Number(column.dataset.statusId);
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
                            body: JSON.stringify({ task_status_id: statusId, ordered_ids: orderedIds }),
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
