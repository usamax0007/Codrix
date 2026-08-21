@extends('frontend.user.layout.app')

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
                    <a href="{{ route('user.task-status.index') }}" class="px-4 py-2 rounded-md bg-gray-800 border border-gray-700 text-white text-sm font-medium hover:bg-gray-700 transition">
                        Manage Status
                    </a>
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
                                <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3 task-card" draggable="true" data-task-id="{{ $task->id }}" data-current-status="{{ $task->status }}" onclick="window.location.href='{{ route('user.task.show', $task) }}'">
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
            const columns = document.querySelectorAll('.status-column');
            let draggedCard = null;
            let clone = null;
            let offsetX = 0;
            let offsetY = 0;

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
                columns.forEach(column => {
                    const list = column.querySelector('.task-list');
                    list.style.backgroundColor = '';
                    list.style.border = '';
                });
            });

            card.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            card.addEventListener('drop', function(e) {
                e.preventDefault();
            });
        }

        function addTask(event) {
            event.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const formData = new FormData(event.target);
            
            fetch('{{ route('user.task.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const task = result.task;
                    const statusColumn = document.querySelector(`.status-column[data-status="${task.status}"]`);
                    if (statusColumn) {
                        const taskList = statusColumn.querySelector('.task-list');
                        const taskCount = statusColumn.querySelector('.task-count');
                        
                        const newTaskCard = document.createElement('div');
                        newTaskCard.className = 'bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3 task-card';
                        newTaskCard.draggable = true;
                        newTaskCard.setAttribute('data-task-id', task.id);
                        newTaskCard.setAttribute('data-current-status', task.status);
                        newTaskCard.onclick = () => window.location.href = `/user/task/${task.id}`;
                        
                        const priorityClass = task.priority === 'high' ? 'text-red-400 bg-red-400/10 border-red-400/30' : 
                                              (task.priority === 'medium' ? 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30' : 'text-green-400 bg-green-400/10 border-green-400/30');
                        
                        newTaskCard.innerHTML = `
                            <div class="absolute top-3 right-3 flex gap-2">
                                <button type="button" onclick="event.stopPropagation(); deleteTask(${task.id})" class="text-gray-500 hover:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </div>
                            ${task.project ? `<p class="text-xs font-semibold text-emerald-400 tracking-wide mb-1">${task.project.name.toUpperCase()}</p>` : ''}
                            <h3 class="text-white font-semibold mb-1">${task.summary}</h3>
                            <p class="text-sm text-gray-500 mb-3">${task.description ? task.description.substring(0, 50) + (task.description.length > 50 ? '...' : '') : ''}</p>
                            <span class="inline-block text-xs font-semibold ${priorityClass} border rounded px-2 py-0.5 mb-3">${task.priority.toUpperCase()}</span>
                            <div class="border-t border-gray-800 pt-3 flex items-center gap-2">
                                ${task.assignee ? `
                                    <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-semibold">${task.assignee.name.charAt(0)}</div>
                                    <span class="text-sm text-gray-300">${task.assignee.name}</span>
                                ` : '<span class="text-sm text-gray-500">Unassigned</span>'}
                            </div>
                        `;
                        
                        taskList.appendChild(newTaskCard);
                        
                        // Update task count
                        taskCount.textContent = parseInt(taskCount.textContent) + 1;
                        
                        // Initialize drag and drop for new task
                        initializeDragAndDropForCard(newTaskCard);
                    }
                    
                    document.getElementById('addTaskForm').reset();
                    document.getElementById('addTaskModal').classList.add('hidden');
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
            
            fetch(`{{ route('user.task.destroy', ':id') }}`.replace(':id', taskId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
                    if (taskCard) {
                        const statusColumn = taskCard.closest('.status-column');
                        const taskCount = statusColumn.querySelector('.task-count');
                        
                        taskCard.remove();
                        
                        // Update task count
                        taskCount.textContent = parseInt(taskCount.textContent) - 1;
                    }
                }
            })
            .catch(error => {
                console.error('Error deleting task:', error);
            });
        }

        // Listen for localStorage changes to update subtask progress
        window.addEventListener('storage', function(e) {
            if (e.key && e.key.startsWith('task_') && e.key.endsWith('_subtasks')) {
                const taskId = e.key.replace('task_', '').replace('_subtasks', '');
                const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
                
                if (taskCard && e.newValue) {
                    const data = JSON.parse(e.newValue);
                    
                    // Find or create subtask section
                    let subtaskSection = null;
                    const mb3Divs = taskCard.querySelectorAll('.mb-3');
                    mb3Divs.forEach(div => {
                        if (div.querySelector('.flex.items-center.justify-between') && div.textContent.includes('Subtasks')) {
                            subtaskSection = div;
                        }
                    });
                    
                    if (!subtaskSection && data.total > 0) {
                        const prioritySpan = taskCard.querySelector('span.inline-block');
                        if (prioritySpan) {
                            subtaskSection = document.createElement('div');
                            subtaskSection.className = 'mb-3';
                            prioritySpan.after(subtaskSection);
                        }
                    }
                    
                    if (subtaskSection) {
                        if (data.total > 0) {
                            subtaskSection.innerHTML = `
                                <div class="flex items-center justify-between text-xs text-gray-400 mb-1">
                                    <span>Subtasks</span>
                                    <span>${data.completed}/${data.total}</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width: ${data.progress}%"></div>
                                </div>
                            `;
                        } else {
                            subtaskSection.remove();
                        }
                    }
                }
            }
        });

        // Also check localStorage on page load
        document.addEventListener('DOMContentLoaded', function() {
            const taskCards = document.querySelectorAll('.task-card');
            taskCards.forEach(card => {
                const taskId = card.getAttribute('data-task-id');
                const storedData = localStorage.getItem(`task_${taskId}_subtasks`);
                
                if (storedData) {
                    const data = JSON.parse(storedData);
                    
                    let subtaskSection = null;
                    const mb3Divs = card.querySelectorAll('.mb-3');
                    mb3Divs.forEach(div => {
                        if (div.querySelector('.flex.items-center.justify-between') && div.textContent.includes('Subtasks')) {
                            subtaskSection = div;
                        }
                    });
                    
                    if (!subtaskSection && data.total > 0) {
                        const prioritySpan = card.querySelector('span.inline-block');
                        if (prioritySpan) {
                            subtaskSection = document.createElement('div');
                            subtaskSection.className = 'mb-3';
                            prioritySpan.after(subtaskSection);
                        }
                    }
                    
                    if (subtaskSection) {
                        if (data.total > 0) {
                            subtaskSection.innerHTML = `
                                <div class="flex items-center justify-between text-xs text-gray-400 mb-1">
                                    <span>Subtasks</span>
                                    <span>${data.completed}/${data.total}</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width: ${data.progress}%"></div>
                                </div>
                            `;
                        } else {
                            subtaskSection.remove();
                        }
                    }
                }
            });
        });
    </script>
@endsection
