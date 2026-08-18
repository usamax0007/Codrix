@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Tasks</h1>
                    <p class="text-gray-400 text-sm mt-1">Drag cards between columns, or click a task for details and comments.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('user.task-status.index') }}" class="px-4 py-2 rounded-md bg-gray-800 border border-gray-700 text-white text-sm font-medium hover:bg-gray-700 transition">
                        Manage Status
                    </a>
                    <a href="{{ route('user.task.create') }}" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition flex items-center gap-1">
                        <span class="text-lg leading-none">+</span> Add Task
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="task-board">
                @foreach($statuses as $status)
                    @php
                        $statusTasks = $tasks->where('status', $status->name);
                    @endphp

                    <!-- {{ $status->name }} Column -->
                    <div class="bg-gray-950/40 rounded-lg border border-gray-800 p-4 h-[calc(110vh-200px)] overflow-y-auto scrollbar-hide status-column"
                         data-status="{{ $status->name }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $status->color }}"></span>
                                <h2 class="font-semibold text-white">{{ $status->name }}</h2>
                            </div>
                            <span class="text-xs bg-gray-800 text-gray-300 rounded-full px-2 py-0.5 task-count">{{ $statusTasks->count() }}</span>
                        </div>

                        <div class="task-list">
                            @foreach($statusTasks as $task)
                                @php
                                    $totalSubtasks = $task->subtasks->count();
                                    $completedSubtasks = $task->subtasks->where('is_completed', true)->count();
                                @endphp
                                <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3 task-card"
                                     draggable="true"
                                     data-task-id="{{ $task->id }}"
                                     data-current-status="{{ $task->status }}"
                                     onclick="window.location.href='{{ route('user.task.show', $task) }}'">
                                    <div class="absolute top-3 right-3 flex gap-2">
                                        <form action="{{ route('user.task.destroy', $task) }}" method="POST" class="inline" onclick="event.stopPropagation()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-400" onclick="return confirm('Are you sure you want to delete this task?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    @if($task->project)
                                        <p class="text-xs font-semibold text-emerald-400 tracking-wide mb-1">{{ strtoupper($task->project->name) }}</p>
                                    @endif
                                    <h3 class="text-white font-semibold mb-1">{{ $task->summary }}</h3>
                                    <p class="text-sm text-gray-500 mb-3">{{ Str::limit($task->description, 50) }}</p>
                                    <span class="inline-block text-xs font-semibold {{ $task->priority == 'high' ? 'text-red-400 bg-red-400/10 border-red-400/30' : ($task->priority == 'medium' ? 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30' : 'text-green-400 bg-green-400/10 border-green-400/30') }} border rounded px-2 py-0.5 mb-3">{{ strtoupper($task->priority) }}</span>

                                    @if($totalSubtasks > 0)
                                        <div class="mb-3">
                                            <div class="flex items-center justify-between text-xs text-gray-400 mb-1">
                                                <span>Subtasks</span>
                                                <span>{{ $completedSubtasks }}/{{ $totalSubtasks }}</span>
                                            </div>
                                            <div class="w-full bg-gray-700 rounded-full h-1.5">
                                                <div class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width: {{ ($completedSubtasks / $totalSubtasks) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="border-t border-gray-800 pt-3 flex items-center gap-2">
                                        @if($task->assignee)
                                            <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-semibold">{{ substr($task->assignee->name, 0, 1) }}</div>
                                            <span class="text-sm text-gray-300">{{ $task->assignee->name }}</span>
                                        @else
                                            <span class="text-sm text-gray-500">Unassigned</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>

    <script>
        const cards = document.querySelectorAll('.task-card');
        const columns = document.querySelectorAll('.status-column');
        let draggedCard = null;
        let clone = null;
        let offsetX = 0;
        let offsetY = 0;

        function moveTask(card, column) {
            const taskList = column.querySelector('.task-list');
            const taskId = card.dataset.taskId;
            const oldStatus = card.dataset.currentStatus;
            const newStatus = column.dataset.status;
            if (oldStatus === newStatus) {
                return;
            }
            taskList.appendChild(card);
            card.dataset.currentStatus = newStatus;
            updateCounters();
            updateTaskStatus(taskId, newStatus);
        }

        function highlightColumn(column) {
            const list = column.querySelector('.task-list');
            list.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
            list.style.border = '2px dashed #10b981';
        }

        function clearHighlight() {

            columns.forEach(column => {
                const list = column.querySelector('.task-list');
                list.style.backgroundColor = '';
                list.style.border = '';
            });
        }

        function getColumn(x, y) {
            return [...columns].find(column => {
                const rect = column.getBoundingClientRect();
                return (
                    x >= rect.left &&
                    x <= rect.right &&
                    y >= rect.top &&
                    y <= rect.bottom
                );
            });
        }

        cards.forEach(card => {
            card.addEventListener('dragstart', function(e) {
                draggedCard = this;
                this.style.opacity = '0.4';
                this.style.transform = 'scale(0.95) rotate(2deg)';
                e.dataTransfer.effectAllowed = 'move';
            });

            card.addEventListener('dragend', function() {
                this.style.opacity = '';
                this.style.transform = '';
                draggedCard = null;
                clearHighlight();
            });

            card.addEventListener('touchstart', function(e) {
                const touch = e.touches[0];
                const rect = this.getBoundingClientRect();
                draggedCard = this;
                offsetX = touch.clientX - rect.left;
                offsetY = touch.clientY - rect.top;
                clone = this.cloneNode(true);
                clone.style.position = 'fixed';
                clone.style.width = rect.width + 'px';
                clone.style.height = rect.height + 'px';
                clone.style.left = rect.left + 'px';
                clone.style.top = rect.top + 'px';
                clone.style.zIndex = '9999';
                clone.style.opacity = '0.8';
                clone.style.transform = 'scale(1.05)';
                clone.style.pointerEvents = 'none';
                clone.style.boxShadow = '0 10px 40px rgba(0,0,0,0.4)';
                document.body.appendChild(clone);
                this.style.opacity = '0.3';
            }, { passive: false });

            card.addEventListener('touchmove', function(e) {
                if (!clone) return;
                e.preventDefault();
                const touch = e.touches[0];
                clone.style.left =
                    (touch.clientX - offsetX) + 'px';
                clone.style.top =
                    (touch.clientY - offsetY) + 'px';
                clearHighlight();
                const column = getColumn(
                    touch.clientX,
                    touch.clientY
                );

                if (column) {
                    highlightColumn(column);
                }
            }, { passive: false });

            card.addEventListener('touchend', function(e) {
                if (!clone) return;
                const touch = e.changedTouches[0];
                const column = getColumn(
                    touch.clientX,
                    touch.clientY
                );

                if (column) {
                    moveTask(draggedCard, column);
                }
                finishTouchDrag();
            });

            card.addEventListener('touchcancel', function() {
                finishTouchDrag();
            });
        });

        columns.forEach(column => {
            column.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                highlightColumn(this);
            });

            column.addEventListener('dragleave', function(e) {
                if (!this.contains(e.relatedTarget)) {
                    clearHighlight();
                }
            });

            column.addEventListener('drop', function(e) {
                e.preventDefault();
                clearHighlight();
                if (draggedCard) {
                    moveTask(draggedCard, this);
                }
            });
        });

        function finishTouchDrag() {
            if (draggedCard) {
                draggedCard.style.opacity = '';
            }
            if (clone) {
                clone.remove();
            }
            clone = null;
            draggedCard = null;
            clearHighlight();
        }

        function updateCounters() {
            columns.forEach(column => {
                const count =
                    column.querySelectorAll('.task-card').length;
                column.querySelector('.task-count').textContent = count;
            });
        }

        function updateTaskStatus(taskId, newStatus) {
            const csrfToken =
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content;
            fetch(
                `{{ route('user.task.status.update', ':id') }}`
                    .replace(':id', taskId),
                {
                    method: 'PUT',

                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },

                    body: JSON.stringify({
                        status: newStatus
                    })
                }
            )
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        console.log('Task status updated successfully');
                    }

                })
                .catch(error => {

                    console.error(
                        'Error updating task status:',
                        error
                    );

                });
        }

    </script>

    <style>
        .task-card {
            cursor: grab;
        }

        .task-card:active {
            cursor: grabbing;
        }
    </style>
@endsection
