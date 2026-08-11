<x-user.layout title="Tasks" :wide="true">
    <div
        class="space-y-5"
        data-move-url-template="{{ url('/user/tasks/__TASK__/move') }}"
        data-column-url-template="{{ url('/user/tasks/columns/__STATUS__') }}"
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
            class="grid items-stretch gap-4"
            style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));"
        >
            @forelse ($statuses as $status)
                @php
                    $columnTasks = $columns[$status->id] ?? collect();
                    $columnTotal = (int) ($columnTotals[$status->id] ?? $columnTasks->count());
                    $hasMore = $columnTasks->count() < $columnTotal;
                @endphp
                <section class="task-board-column flex h-[calc(100dvh-10.5rem)] min-h-[24rem] flex-col overflow-hidden rounded-2xl border border-white/10 bg-xc-card/60">
                    <header class="flex shrink-0 items-center justify-between gap-2 border-b border-white/10 px-3.5 py-3">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $status->color }}"></span>
                            <h2 class="truncate text-sm font-semibold tracking-tight">{{ $status->name }}</h2>
                        </div>
                        <span class="rounded-md bg-white/5 px-2 py-0.5 text-xs text-white/50" data-column-count="{{ $status->id }}">
                            {{ $columnTotal }}
                        </span>
                    </header>

                    <div
                        class="task-column task-column-scroll flex min-h-0 flex-1 flex-col gap-2.5 overflow-y-auto p-3"
                        data-status-id="{{ $status->id }}"
                        data-total="{{ $columnTotal }}"
                        data-has-more="{{ $hasMore ? '1' : '0' }}"
                        data-loading="0"
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
                            data-max-bytes="{{ min(10 * 1024 * 1024, \Illuminate\Http\UploadedFile::getMaxFilesize()) }}"
                            class="sr-only"
                        >
                        <p class="text-xs text-white/35">
                            Server upload limit right now:
                            {{ number_format(min(10 * 1024 * 1024, \Illuminate\Http\UploadedFile::getMaxFilesize()) / 1024 / 1024, 0) }}MB per file.
                        </p>
                        @error('attachments')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                        @error('attachments.*')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @if ($canAssign)
                            <div class="space-y-1.5 sm:col-span-2" data-assignee-multiselect>
                                <label for="assignee-dropdown-toggle" class="block text-sm font-medium text-white/80">
                                    Assignees <span class="text-xc-cyan">*</span>
                                </label>

                                <div class="relative">
                                    <button
                                        type="button"
                                        id="assignee-dropdown-toggle"
                                        data-assignee-toggle
                                        class="flex w-full items-center justify-between gap-2 rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-left text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
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
                                            @forelse ($assignees as $assignee)
                                                @php
                                                    $selected = collect(old('assignee_ids', []))->map(fn ($id) => (string) $id)->contains((string) $assignee->id);
                                                @endphp
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
                                                            @checked($selected)
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
                                <p class="text-xs text-white/40">Open the dropdown and select one or more users.</p>
                                @error('assignee_ids')
                                    <p class="text-xs text-red-300">{{ $message }}</p>
                                @enderror
                                @error('assignee_ids.*')
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

        <div class="relative z-10 flex max-h-[92vh] w-full max-w-5xl flex-col overflow-hidden rounded-2xl border border-white/[0.08] bg-xc-card shadow-2xl shadow-black/50 ring-1 ring-white/[0.04]">
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

            <div id="view-task-modal-body" class="view-task-modal-scroll flex-1 overflow-y-auto px-5 pb-5 sm:px-6">
                <div class="flex items-center justify-center py-16 text-sm text-white/40">Loading task…</div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <style>
            .view-task-modal-scroll {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }
            .view-task-modal-scroll::-webkit-scrollbar {
                display: none;
                width: 0;
                height: 0;
            }
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
            .task-column-scroll {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }
            .task-column-scroll::-webkit-scrollbar {
                display: none;
                width: 0;
                height: 0;
            }
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

                    // Block pasted/dropped images (base64) which can exceed PHP post_max_size.
                    quill.clipboard.addMatcher('IMG', () => {
                        return { ops: [{ insert: '' }] };
                    });
                    quill.root.addEventListener('drop', (event) => {
                        if (event.dataTransfer?.files?.length) {
                            event.preventDefault();
                        }
                    });

                    if (descriptionInput?.value) {
                        quill.root.innerHTML = descriptionInput.value;
                    }
                }

                const attachmentsInput = document.getElementById('attachments');
                const maxAttachmentBytes = Number(attachmentsInput?.dataset.maxBytes || (10 * 1024 * 1024));
                const maxAttachments = 5;
                const attachmentsLabel = document.getElementById('attachments-label');
                let attachmentErrorEl = document.getElementById('attachments-client-error');
                if (!attachmentErrorEl && attachmentsInput?.parentElement) {
                    attachmentErrorEl = document.createElement('p');
                    attachmentErrorEl.id = 'attachments-client-error';
                    attachmentErrorEl.className = 'hidden text-xs text-red-300';
                    attachmentsInput.parentElement.appendChild(attachmentErrorEl);
                }

                const showAttachmentError = (message) => {
                    if (!attachmentErrorEl) return;
                    attachmentErrorEl.textContent = message || '';
                    attachmentErrorEl.classList.toggle('hidden', !message);
                };

                const maxMbLabel = Math.max(1, Math.floor(maxAttachmentBytes / 1024 / 1024));

                const validateAttachments = () => {
                    const files = Array.from(attachmentsInput?.files || []);
                    if (files.length > maxAttachments) {
                        showAttachmentError(`You can upload at most ${maxAttachments} files.`);
                        return false;
                    }

                    const tooLarge = files.find((file) => file.size > maxAttachmentBytes);
                    if (tooLarge) {
                        showAttachmentError(`"${tooLarge.name}" is larger than ${maxMbLabel}MB.`);
                        return false;
                    }

                    showAttachmentError('');
                    return true;
                };

                form?.addEventListener('submit', (event) => {
                    if (quill && descriptionInput) {
                        // Strip any residual data-URI images before submit.
                        quill.root.querySelectorAll('img[src^="data:image"]').forEach((img) => img.remove());
                        const html = quill.root.innerHTML;
                        descriptionInput.value = html === '<p><br></p>' ? '' : html;
                    }

                    const assigneeRoot = document.querySelector('[data-assignee-multiselect]');
                    if (assigneeRoot) {
                        const selectedCount = assigneeRoot.querySelectorAll('[data-assignee-checkbox]:checked').length;
                        if (selectedCount === 0) {
                            event.preventDefault();
                            const placeholder = assigneeRoot.querySelector('[data-assignee-placeholder]');
                            if (placeholder) {
                                placeholder.textContent = 'Select at least one user';
                                placeholder.classList.add('text-red-300');
                                placeholder.classList.remove('text-white/45', 'text-white');
                            }
                            assigneeRoot.querySelector('[data-assignee-toggle]')?.focus();
                            return;
                        }
                    }

                    if (!validateAttachments()) {
                        event.preventDefault();
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

                const initAssigneeMultiselect = (scope = document) => {
                    scope.querySelectorAll('[data-assignee-multiselect]').forEach((rootEl) => {
                        if (rootEl.dataset.assigneeBound === '1') return;
                        rootEl.dataset.assigneeBound = '1';

                        const toggle = rootEl.querySelector('[data-assignee-toggle]');
                        const menu = rootEl.querySelector('[data-assignee-menu]');
                        const search = rootEl.querySelector('[data-assignee-search]');
                        const placeholder = rootEl.querySelector('[data-assignee-placeholder]');
                        const chips = rootEl.querySelector('[data-assignee-chips]');
                        const checkboxes = () => Array.from(rootEl.querySelectorAll('[data-assignee-checkbox]'));

                        const syncUi = () => {
                            const selected = checkboxes().filter((input) => input.checked);
                            if (placeholder) {
                                placeholder.textContent = selected.length === 0
                                    ? 'Select users'
                                    : selected.length === 1
                                        ? selected[0].dataset.label
                                        : `${selected.length} users selected`;
                                placeholder.classList.toggle('text-white/45', selected.length === 0);
                                placeholder.classList.toggle('text-white', selected.length > 0);
                                placeholder.classList.remove('text-red-300');
                            }

                            if (chips) {
                                chips.innerHTML = selected.map((input) => `
                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.04] px-2 py-1 text-xs text-white/80">
                                        ${input.dataset.label}
                                        <button type="button" class="text-white/40 hover:text-white" data-remove-assignee="${input.value}" aria-label="Remove ${input.dataset.label}">×</button>
                                    </span>
                                `).join('');
                            }
                        };

                        const setOpen = (open) => {
                            menu?.classList.toggle('hidden', !open);
                            if (open) search?.focus();
                        };

                        toggle?.addEventListener('click', (event) => {
                            event.preventDefault();
                            setOpen(menu?.classList.contains('hidden'));
                        });

                        search?.addEventListener('input', () => {
                            const q = search.value.trim().toLowerCase();
                            rootEl.querySelectorAll('[data-assignee-option]').forEach((option) => {
                                const match = !q || (option.dataset.name || '').includes(q);
                                option.closest('li')?.classList.toggle('hidden', !match);
                            });
                        });

                        const queueAutosave = () => {
                            const form = rootEl.closest('[data-task-assignees-form][data-autosave="1"]');
                            if (!form) return;

                            const errorEl = form.querySelector('[data-assignees-error]');
                            const selected = checkboxes().filter((input) => input.checked);

                            if (selected.length === 0) {
                                if (errorEl) {
                                    errorEl.textContent = 'Select at least one assignee.';
                                    errorEl.classList.remove('hidden');
                                }
                                return;
                            }

                            if (errorEl) {
                                errorEl.classList.add('hidden');
                                errorEl.textContent = '';
                            }

                            clearTimeout(form._assigneeSaveTimer);
                            form._assigneeSaveTimer = setTimeout(() => {
                                form.requestSubmit();
                            }, 250);
                        };

                        rootEl.addEventListener('change', (event) => {
                            if (!event.target.matches('[data-assignee-checkbox]')) return;

                            const selected = checkboxes().filter((input) => input.checked);
                            if (selected.length === 0) {
                                event.target.checked = true;
                                syncUi();
                                const form = rootEl.closest('[data-task-assignees-form]');
                                const errorEl = form?.querySelector('[data-assignees-error]');
                                if (errorEl) {
                                    errorEl.textContent = 'Select at least one assignee.';
                                    errorEl.classList.remove('hidden');
                                }
                                return;
                            }

                            syncUi();
                            queueAutosave();
                        });

                        chips?.addEventListener('click', (event) => {
                            const btn = event.target.closest('[data-remove-assignee]');
                            if (!btn) return;
                            const input = checkboxes().find((el) => el.value === btn.getAttribute('data-remove-assignee'));
                            if (!input) return;

                            const selected = checkboxes().filter((el) => el.checked);
                            if (selected.length <= 1) {
                                const form = rootEl.closest('[data-task-assignees-form]');
                                const errorEl = form?.querySelector('[data-assignees-error]');
                                if (errorEl) {
                                    errorEl.textContent = 'Select at least one assignee.';
                                    errorEl.classList.remove('hidden');
                                }
                                return;
                            }

                            input.checked = false;
                            syncUi();
                            queueAutosave();
                        });

                        document.addEventListener('click', (event) => {
                            if (!rootEl.contains(event.target)) {
                                setOpen(false);
                            }
                        });

                        syncUi();
                    });
                };

                initAssigneeMultiselect();

                const dueDateInput = document.getElementById('due_date');
                dueDateInput?.addEventListener('click', () => {
                    if (typeof dueDateInput.showPicker === 'function') {
                        try { dueDateInput.showPicker(); } catch (e) {}
                    }
                });

                attachmentsInput?.addEventListener('change', () => {
                    if (!attachmentsLabel) return;
                    if (!validateAttachments()) {
                        attachmentsInput.value = '';
                        attachmentsLabel.textContent = 'No file chosen · up to 5 files, 10MB each';
                        return;
                    }
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

                const updateBoardCardProgress = (taskId, progress) => {
                    if (!taskId || !progress) return;

                    const card = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
                    if (!card) return;

                    let meter = card.querySelector('[data-task-progress]');
                    if (!meter) {
                        meter = document.createElement('div');
                        meter.className = 'task-card-open mt-3 cursor-pointer';
                        meter.setAttribute('data-task-progress', '');
                        const footer = card.querySelector('[data-task-assignees]');
                        if (footer) {
                            card.insertBefore(meter, footer);
                        } else {
                            card.appendChild(meter);
                        }
                    }

                    if (!progress.total) {
                        meter.innerHTML = `
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">Subtasks</p>
                            <p class="mt-1 text-xs text-white/40">No subtasks</p>
                        `;
                        return;
                    }

                    const percent = Number(progress.percent || 0);
                    meter.innerHTML = `
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">Subtasks</p>
                        <div class="mt-1.5 flex items-center justify-between gap-2 text-[11px] text-white/45">
                            <span>${progress.completed} / ${progress.total} completed</span>
                            <span>${percent}%</span>
                        </div>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r from-xc-cyan to-xc-blue transition-all" style="width: ${percent}%"></div>
                        </div>
                    `;
                };

                const updateBoardCardAssignees = (taskId, html) => {
                    if (!taskId || !html) return;
                    const card = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
                    const target = card?.querySelector('[data-task-assignees]');
                    if (target) target.innerHTML = html;
                };

                const renderTaskDetails = (data) => {
                    if (viewModalTitle) {
                        viewModalTitle.textContent = data.panel === 'comments'
                            ? `Comments · ${data.summary || 'Task'}`
                            : (data.summary || 'Task');
                    }
                    if (viewModalBody) viewModalBody.innerHTML = data.html || '';
                    if (data.progress) {
                        updateBoardCardProgress(data.task_id, data.progress);
                    }
                    if (data.assignees_html) {
                        updateBoardCardAssignees(data.task_id, data.assignees_html);
                    }
                    bindTaskModalInteractions();
                    requestAnimationFrame(() => fitCommentsPreview());
                };

                const fitCommentsPreview = () => {
                    const list = viewModalBody?.querySelector('[data-comments-preview]');
                    if (!list) return;

                    const items = Array.from(list.querySelectorAll('[data-comment-preview-item]'));
                    const overflow = viewModalBody.querySelector('[data-comments-overflow]');
                    items.forEach((item) => item.classList.remove('hidden'));

                    const maxHeight = list.clientHeight || 0;
                    if (!maxHeight) return;

                    let used = 0;
                    let hiddenCount = 0;
                    const gap = 2;

                    items.forEach((item, index) => {
                        const height = item.offsetHeight;
                        if (index === 0 || used + height <= maxHeight) {
                            used += height + gap;
                            return;
                        }
                        item.classList.add('hidden');
                        hiddenCount += 1;
                    });

                    if (overflow) {
                        overflow.classList.toggle('hidden', hiddenCount === 0);
                    }
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

                const submitSubtaskForm = async (form) => {
                    const errorEl = form.querySelector('[data-subtask-error]');
                    if (errorEl) {
                        errorEl.classList.add('hidden');
                        errorEl.textContent = '';
                    }

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': boardCsrf,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(form),
                        });

                        const data = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            const message = data?.errors?.title?.[0]
                                || data?.message
                                || 'Could not update subtask.';
                            if (errorEl) {
                                errorEl.textContent = message;
                                errorEl.classList.remove('hidden');
                            }
                            return;
                        }

                        renderTaskDetails(data);
                    } catch (error) {
                        if (errorEl) {
                            errorEl.textContent = 'Could not update subtask.';
                            errorEl.classList.remove('hidden');
                        }
                    }
                };

                const bindSubtaskInteractions = () => {
                    const rootEl = viewModalBody?.querySelector('[data-subtasks-root]');
                    if (!rootEl) return;

                    rootEl.querySelector('[data-open-add-subtask]')?.addEventListener('click', () => {
                        rootEl.querySelector('[data-add-subtask-form]')?.classList.remove('hidden');
                        rootEl.querySelector('[data-add-subtask-form] input[name="title"]')?.focus();
                    });

                    rootEl.querySelector('[data-cancel-add-subtask]')?.addEventListener('click', () => {
                        const form = rootEl.querySelector('[data-add-subtask-form]');
                        form?.classList.add('hidden');
                        form?.reset();
                    });

                    rootEl.querySelectorAll('[data-add-subtask-form], [data-edit-subtask-form], [data-subtask-toggle-form], [data-subtask-delete-form]').forEach((form) => {
                        form.addEventListener('submit', (event) => {
                            event.preventDefault();
                            if (form.hasAttribute('data-subtask-delete-form') && !confirm('Delete this subtask?')) {
                                return;
                            }
                            submitSubtaskForm(form);
                        });
                    });

                    rootEl.querySelectorAll('[data-edit-subtask]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const row = btn.closest('[data-subtask-id]');
                            row?.querySelector('[data-subtask-view]')?.classList.add('hidden');
                            row?.querySelector('[data-edit-subtask-form]')?.classList.remove('hidden');
                        });
                    });

                    rootEl.querySelectorAll('[data-cancel-edit-subtask]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const row = btn.closest('[data-subtask-id]');
                            row?.querySelector('[data-edit-subtask-form]')?.classList.add('hidden');
                            row?.querySelector('[data-subtask-view]')?.classList.remove('hidden');
                        });
                    });

                    const list = rootEl.querySelector('[data-subtask-list]');
                    if (list && typeof Sortable !== 'undefined' && rootEl.querySelector('.subtask-handle')) {
                        Sortable.create(list, {
                            handle: '.subtask-handle',
                            animation: 150,
                            draggable: '.subtask-row',
                            onEnd: async () => {
                                const orderedIds = Array.from(list.querySelectorAll('.subtask-row')).map((el) => Number(el.dataset.subtaskId));
                                try {
                                    const response = await fetch(rootEl.dataset.reorderUrl, {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': boardCsrf,
                                            'X-Requested-With': 'XMLHttpRequest',
                                        },
                                        body: JSON.stringify({ ordered_ids: orderedIds }),
                                    });
                                    if (!response.ok) throw new Error('Reorder failed');
                                    const data = await response.json();
                                    renderTaskDetails(data);
                                } catch (e) {
                                    // Keep local order; next open will refresh from server.
                                }
                            },
                        });
                    }
                };

                const bindAssigneeForms = () => {
                    viewModalBody?.querySelectorAll('[data-task-assignees-form]').forEach((form) => {
                        form.addEventListener('submit', async (event) => {
                            event.preventDefault();
                            const errorEl = form.querySelector('[data-assignees-error]');
                            if (errorEl) {
                                errorEl.classList.add('hidden');
                                errorEl.textContent = '';
                            }

                            const selectedCount = form.querySelectorAll('[data-assignee-checkbox]:checked').length;
                            if (selectedCount === 0) {
                                if (errorEl) {
                                    errorEl.textContent = 'Select at least one assignee.';
                                    errorEl.classList.remove('hidden');
                                }
                                return;
                            }

                            const submitBtn = form.querySelector('button[type="submit"]');
                            if (submitBtn) submitBtn.disabled = true;

                            try {
                                const response = await fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': boardCsrf,
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: new FormData(form),
                                });

                                const data = await response.json().catch(() => ({}));

                                if (!response.ok) {
                                    const message = data?.errors?.assignee_ids?.[0]
                                        || data?.errors?.['assignee_ids.0']?.[0]
                                        || data?.message
                                        || 'Could not update assignees.';
                                    if (errorEl) {
                                        errorEl.textContent = message;
                                        errorEl.classList.remove('hidden');
                                    }
                                    return;
                                }

                                renderTaskDetails(data);
                            } catch (error) {
                                if (errorEl) {
                                    errorEl.textContent = 'Could not update assignees.';
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
                    bindSubtaskInteractions();
                    bindAssigneeForms();
                    initAssigneeMultiselect(viewModalBody || document);

                    viewModalBody?.querySelectorAll('[data-open-comments], [data-open-comment-thread], [data-close-comment-thread]').forEach((btn) => {
                        btn.addEventListener('click', (event) => {
                            event.preventDefault();
                            event.stopPropagation();
                            loadTaskPanel(btn.dataset.taskUrl);
                        });
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

                document.addEventListener('click', (event) => {
                    const card = event.target.closest('.task-card');
                    if (!card || !root?.contains(card)) return;
                    if (dragging) return;
                    if (event.target.closest('.task-card-actions')) return;
                    openTaskDetails(card.dataset.taskUrl);
                });

                if (!root || typeof Sortable === 'undefined') return;

                const csrf = root.dataset.csrf;
                const urlTemplate = root.dataset.moveUrlTemplate;
                const columnUrlTemplate = root.dataset.columnUrlTemplate;

                const syncColumnBadge = (column) => {
                    const statusId = column.dataset.statusId;
                    const total = Number(column.dataset.total || 0);
                    const loaded = column.querySelectorAll('.task-card').length;
                    const badge = document.querySelector(`[data-column-count="${statusId}"]`);
                    if (badge) badge.textContent = String(total);
                    column.dataset.hasMore = loaded < total ? '1' : '0';
                };

                const bumpColumnTotal = (column, delta) => {
                    if (!column) return;
                    const next = Math.max(0, Number(column.dataset.total || 0) + delta);
                    column.dataset.total = String(next);
                    syncColumnBadge(column);
                };

                const loadNextColumnPage = async (column) => {
                    if (!column || column.dataset.loading === '1' || column.dataset.hasMore !== '1') {
                        return false;
                    }

                    const statusId = column.dataset.statusId;
                    const cards = column.querySelectorAll('.task-card');
                    const afterId = cards.length ? cards[cards.length - 1].dataset.taskId : '';
                    column.dataset.loading = '1';

                    try {
                        const params = new URLSearchParams();
                        if (afterId) params.set('after_id', afterId);
                        const url = `${columnUrlTemplate.replace('__STATUS__', statusId)}?${params.toString()}`;
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) throw new Error('Failed to load column page');

                        const data = await response.json();
                        if (data.html) {
                            column.insertAdjacentHTML('beforeend', data.html);
                        }

                        column.dataset.total = String(data.total ?? column.dataset.total);
                        column.dataset.hasMore = data.has_more ? '1' : '0';
                        syncColumnBadge(column);
                        return Boolean(data.html);
                    } catch (error) {
                        return false;
                    } finally {
                        column.dataset.loading = '0';
                    }
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
                        syncColumnBadge(column);
                        if (evt.from && evt.from !== column) {
                            syncColumnBadge(evt.from);
                        }
                    } catch (error) {
                        window.location.reload();
                    }
                };

                document.querySelectorAll('.task-column').forEach((column) => {
                    column.addEventListener('scroll', () => {
                        const remaining = column.scrollHeight - column.scrollTop - column.clientHeight;
                        if (remaining < 80) {
                            loadNextColumnPage(column);
                        }
                    });

                    Sortable.create(column, {
                        group: 'tasks',
                        animation: 150,
                        draggable: '.task-card',
                        ghostClass: 'opacity-40',
                        dragClass: 'shadow-lg',
                        onStart: () => { dragging = true; },
                        onEnd: () => { setTimeout(() => { dragging = false; }, 50); },
                        onAdd: (evt) => {
                            bumpColumnTotal(evt.from, -1);
                            bumpColumnTotal(evt.to, 1);
                            persistMove(evt);
                        },
                        onUpdate: persistMove,
                    });
                });
            })();
        </script>
    @endpush
</x-user.layout>
