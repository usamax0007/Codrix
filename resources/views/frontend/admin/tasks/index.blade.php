@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <script>
                window.taskStatusUpdateRoute = '{{ route('user.task.status.update', ':id') }}';
            </script>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">Tasks</h1>
                    <p class="text-gray-400 text-sm mt-1">Drag cards between columns, or click a task for details and comments.</p>
                </div>
                <div class="flex gap-3">
                    @if(auth()->user()->hasPermissionTo('view-task-status'))
                    <a href="{{ route('user.task-status.index') }}" class="px-4 py-2 rounded-md bg-gray-800 border border-gray-700 text-white text-sm font-medium hover:bg-gray-700 transition">
                        Manage Status
                    </a>
                    @endif
                    <button onclick="document.getElementById('addTaskModal').classList.remove('hidden')" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition flex items-center gap-1">
                        <span class="text-lg leading-none">+</span> Add Task
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide" id="task-board">
                @foreach($statuses as $status)
                    @php
                        $statusTasks = $tasks->where('status', $status->name);
                    @endphp

                    <!-- {{ $status->name }} Column -->
                    <div class="bg-gray-950/40 rounded-lg border border-gray-800 p-4 h-[calc(110vh-200px)] overflow-y-auto scrollbar-hide status-column flex-shrink-0 w-[280px] sm:w-[320px] lg:w-[340px]"
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
                                <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3 task-card" draggable="true" data-task-id="{{ $task->id }}" data-current-status="{{ $task->status }}" onclick="window.location.href='{{ route('admin.tasks.show', $task) }}'">
                                    <div class="absolute top-3 right-3 flex gap-2">
                                        <button type="button" onclick="event.stopPropagation(); deleteTask({{ $task->id }})" class="text-gray-500 hover:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"/>
                                                <line x1="6" y1="6" x2="18" y2="18"/>
                                            </svg>
                                        </button>
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

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto scrollbar-hide">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white">Add New Task</h2>
                <button onclick="document.getElementById('addTaskModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form id="addTaskForm" onsubmit="addTask(event)" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Project</label>
                        <select name="project_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Summary</label>
                        <input type="text" name="summary" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Task summary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition resize-none" placeholder="Task description"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Attachment</label>
                        <input type="file" name="attachment" id="attachment" class="hidden" onchange="showFileName(this)">
                        <label for="attachment" class="flex items-center justify-center h-24 w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:border-emerald-500 hover:text-emerald-400 cursor-pointer transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span id="fileName" class="ml-2 text-sm"></span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Priority</label>
                        <select name="priority" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Status</label>
                        <select name="status" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                            @foreach($statuses as $status)
                                <option value="{{ $status->name }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Assignee</label>
                        <select name="assignee_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Due Date</label>
                        <input type="date" name="due_date" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('addTaskModal').classList.add('hidden')" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showFileName(input) {
            const fileNameSpan = document.getElementById('fileName');
            if (input.files && input.files.length > 0) {
                fileNameSpan.textContent = input.files[0].name;
            } else {
                fileNameSpan.textContent = '';
            }
        }

        function initializeDragAndDropForCard(card) {
            card.addEventListener('dragstart', function(e) {
                this.style.opacity = '0.4';
                this.style.transform = 'scale(0.95) rotate(2deg)';
                e.dataTransfer.effectAllowed = 'move';
            });

            card.addEventListener('dragend', function() {
                this.style.opacity = '';
                this.style.transform = '';
                
                const columns = document.querySelectorAll('.status-column');
                columns.forEach(column => {
                    const list = column.querySelector('.task-list');
                    list.style.backgroundColor = '';
                    list.style.border = '';
                });
            });
        }

        function initializeColumnDropHandlers() {
            const columns = document.querySelectorAll('.status-column');
            columns.forEach(column => {
                column.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const list = column.querySelector('.task-list');
                    list.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                    list.style.border = '2px dashed #10B981';
                });

                column.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const list = column.querySelector('.task-list');
                    list.style.backgroundColor = '';
                    list.style.border = '';
                });

                column.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const list = column.querySelector('.task-list');
                    list.style.backgroundColor = '';
                    list.style.border = '';

                    const draggedCard = document.querySelector('.task-card[style*="opacity: 0.4"]');
                    if (draggedCard) {
                        const taskId = draggedCard.getAttribute('data-task-id');
                        const newStatus = column.getAttribute('data-status');
                        const oldStatus = draggedCard.getAttribute('data-current-status');

                        if (newStatus !== oldStatus) {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                            fetch(`/user/task/${taskId}/status`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({ status: newStatus })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const taskList = column.querySelector('.task-list');
                                    taskList.appendChild(draggedCard);
                                    draggedCard.setAttribute('data-current-status', newStatus);

                                    const oldColumn = document.querySelector(`.status-column[data-status="${oldStatus}"]`);
                                    const oldTaskCount = oldColumn.querySelector('.task-count');
                                    const newTaskCount = column.querySelector('.task-count');

                                    oldTaskCount.textContent = parseInt(oldTaskCount.textContent) - 1;
                                    newTaskCount.textContent = parseInt(newTaskCount.textContent) + 1;
                                }
                            })
                            .catch(error => {
                                console.error('Error updating task status:', error);
                            });
                        }
                    }
                });
            });
        }

        function addTask(event) {
            event.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const formData = new FormData(event.target);
            
            fetch('{{ route('admin.tasks.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error adding task:', error);
            });
        }

        function deleteTask(taskId) {
            if (!confirm('Are you sure you want to delete this task?')) {
                return;
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(`{{ route('admin.tasks.destroy', ':id') }}`.replace(':id', taskId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error deleting task:', error);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeColumnDropHandlers();
            
            const taskCards = document.querySelectorAll('.task-card');
            taskCards.forEach(card => {
                initializeDragAndDropForCard(card);
            });
        });
    </script>
@endsection
