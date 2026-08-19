@extends('user.layout.app')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <style>
        .transition {
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <div class="space-y-6">

        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Tasks</h1>
                <p class="text-gray-400 text-sm">Drag cards between columns, or click a task for details.</p>
            </div>
            <div class="flex gap-3">
                @can('reorder-status')
                    <a href="{{ route('task-statuses.index') }}"
                       class="bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-300 font-medium px-4 py-2 rounded-lg transition text-sm flex items-center">
                        Manage Statuses
                    </a>
                @endcan

                @can('create-task')
                    <button id="openTaskModal"
                            class="bg-emerald-500 hover:bg-emerald-600 text-gray-900 font-semibold px-4 py-2 rounded-lg transition text-sm">
                        + Add Task
                    </button>
                @endcan
            </div>
        </div>

        <!-- Kanban Board Container -->
        <div id="kanbanBoard" class="flex flex-row gap-4 w-full overflow-x-auto pb-6 items-stretch no-scrollbar">
            @foreach($statuses as $status)
                <!-- Dynamic uniform height with self-stretch and min-h -->
                <div class="bg-[#0B1019] border border-gray-800/80 rounded-lg w-[320px] min-w-[320px] shrink-0 flex flex-col min-h-[500px] self-stretch">

                    <!-- Status Column Header -->
                    <div class="px-4 py-3 border-b border-gray-800/80 flex justify-between items-center shrink-0">
                        <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full"
                          style="background-color: {{ $status->color ?? '#3B82F6' }}"></span>
                            <h3 class="font-bold text-white text-xs tracking-wide">{{ $status->name }}</h3>
                        </div>
                        <!-- Badge for count -->
                        <span class="task-count text-[11px] bg-[#16202E] text-gray-400 px-2 py-0.5 rounded text-center min-w-[20px]">
                    {{ $status->tasks->count() }}
                </span>
                    </div>

                    <!-- Kanban Body -->
                    <div class="kanban-column p-3 space-y-3 flex-1"
                         data-status-id="{{ $status->id }}">
                        @forelse($status->tasks as $task)
                            <div data-task-id="{{ $task->id }}"
                                 data-task='@json($task)'
                                 onclick="openTaskDetail(this)"
                                 class="task-card bg-[#090D14] border border-gray-800 rounded-lg p-3.5 cursor-grab active:cursor-grabbing">

                                <!-- Project Tag & Delete Button -->
                                <div class="flex justify-between items-start mb-1.5">
                                    <span class="text-[16px] font-bold text-[#00B8D9] tracking-wider uppercase">
                                        {{ $task->project->name ?? 'NO PROJECT' }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                onclick="event.stopPropagation(); openEditModalFromCard(this)"
                                                class="text-gray-500 hover:text-[#00B8D9] transition p-1 cursor-pointer"
                                                title="Edit Task">
                                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                 viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round" stroke-width="2"
                                                      d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                            </svg>
                                        </button>

                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                              onsubmit="return confirm('Delete this task?')" class="inline"
                                              onclick="event.stopPropagation()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-gray-500 hover:text-red-400 text-xs leading-none transition cursor-pointer"
                                                    title="Delete Task">✕
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Task Summary -->
                                <h4 class="font-bold text-white text-lg mb-1">{{ $task->summary }}</h4>

                                <!-- Description -->
                                @if($task->description)
                                    <p class="text-[14px] text-gray-400 mb-3 leading-relaxed line-clamp-2">{{ $task->description }}</p>
                                @endif

                                <!-- Priority Badge -->
                                <div class="mb-3">
                                    <span class="text-[12px] font-bold px-2 py-0.5 rounded border uppercase tracking-wider
                                    @if($task->priority == 'Urgent' || $task->priority == 'High') border-red-500/50 text-red-400 bg-red-500/10
                                    @elseif($task->priority == 'Medium') border-amber-500/50 text-amber-400 bg-amber-500/10
                                    @else border-gray-600 text-gray-400 bg-gray-800/50 @endif">
                                        {{ $task->priority }}
                                    </span>
                                </div>

                                <!-- Subtasks Section -->
                                <div class="mb-3">
                                    <span class="text-[12px] font-bold text-gray-400 tracking-wider uppercase block mb-0.5">SUBTASKS</span>
                                    <div id="card-subtasks-{{ $task->id }}">
                                        @if($task->subtasks && $task->subtasks->count() > 0)
                                            <span class="text-[11px] text-[#00B8D9] font-medium">
                                                {{ $task->subtasks->count() }} Subtask(s)
                                            </span>
                                        @else
                                            <span class="text-[11px] text-gray-400">No subtasks</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Divider & Assignee -->
                                <div class="border-t border-gray-800/80 pt-2.5 mt-2 flex items-center gap-2">
                                    @forelse($task->assignees as $assignee)
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 rounded-full bg-[#00B8D9] text-gray-950 flex items-center justify-center text-[12px] font-bold">
                                                {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                            </div>
                                            <span class="text-[14px] text-gray-300">{{ $assignee->name }}</span>
                                        </div>
                                    @empty
                                        <div class="w-5 h-5 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center text-[10px]">
                                            ?
                                        </div>
                                        <span class="text-[11px] text-gray-400">Unassigned</span>
                                    @endforelse
                                </div>
                            </div>
                        @empty

                        @endforelse

                        <div class="no-tasks-placeholder text-center py-8 text-xs text-gray-600 border border-dashed border-gray-800/60 rounded-lg {{ $status->tasks->count() > 0 ? 'hidden' : '' }}">
                            No tasks here
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal: Add Task -->
    <div id="taskModal"
         class="fixed inset-0 z-50 bg-black/70 backdrop-blur-lg flex items-center justify-center p-4 hidden overflow-y-auto">
        <div class="bg-gray-800 border border-gray-800 rounded-xl w-full max-w-md p-4 relative my-2 shadow-2xl">

            <!-- Header & Close Button -->
            <div class="flex justify-between items-center mb-0.5">
                <h2 class="text-2xl font-bold text-white">Add Task</h2>
                <button type="button" id="closeTaskModal"
                        class="text-gray-400 hover:text-white hover:bg-gray-800/60 rounded-lg w-6 h-6 flex items-center justify-center transition text-xs">
                    ✕
                </button>
            </div>
            <p class="text-[15px] text-gray-300 mb-4">Fill in the details to create a new board item.</p>

            <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-2.5">
                @csrf

                <!-- Project & Status -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Project <span
                                    class="text-[#00B8D9]">*</span></label>
                        <select name="project_id" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-sm text-white focus:border-[#00B8D9] focus:outline-none">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Status <span
                                    class="text-[#00B8D9]">*</span></label>
                        <select name="task_status_id" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-sm text-white focus:border-[#00B8D9] focus:outline-none">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Summary -->
                <div>
                    <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Summary <span
                                class="text-[#00B8D9]">*</span></label>
                    <input type="text" name="summary" required placeholder="Task summary"
                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-sm text-white focus:border-[#00B8D9] focus:outline-none">
                </div>

                <!-- Priority & Due Date -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Priority <span
                                    class="text-[#00B8D9]">*</span></label>
                        <select name="priority" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-sm text-white focus:border-[#00B8D9] focus:outline-none">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Due date</label>
                        <input type="date" name="due_date"
                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-sm text-white [color-scheme:dark] focus:border-[#00B8D9] focus:outline-none">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Description</label>
                    <textarea name="description" rows="3" placeholder="Task details..."
                              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-sm text-white focus:border-[#00B8D9] focus:outline-none resize-none"></textarea>
                </div>

                <!-- Attachment & Assignees -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Attachment</label>
                        <input type="file" name="attachments[]" multiple accept="image/*,.pdf"
                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-sm text-white file:mr-1 file:py-0.5 file:px-1.5 file:rounded file:border-0 file:bg-gray-800 file:text-[#00B8D9] file:text-[10px]">
                    </div>

                    <!-- Multi-select Assignee Dropdown -->
                    <div class="relative">
                        <label class="block text-[15px] font-medium text-gray-300 mb-0.5">Assignees</label>

                        <!-- Dropdown Button -->
                        <button type="button" id="createAssigneesDropdownBtn" onclick="toggleCreateAssigneeDropdown()"
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1.5 text-sm text-white flex justify-between items-center focus:border-[#00B8D9] focus:outline-none">
                            <span id="createSelectedAssigneesText"
                                  class="truncate text-gray-400">Select Assignees...</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Real Hidden Select for Form Submission -->
                        <select id="createAssignees" name="assignees[]" multiple class="hidden">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>

                        <!-- Floating Checkbox Menu -->
                        <div id="createAssigneesDropdownMenu"
                             class="hidden absolute z-50 mt-1 w-full bg-gray-900 border border-gray-800 rounded-lg shadow-xl max-h-48 overflow-y-auto p-1.5">
                            @foreach($users as $user)
                                <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-gray-800 rounded cursor-pointer text-xs text-white">
                                    <input type="checkbox" value="{{ $user->id }}" data-name="{{ $user->name }}"
                                           onchange="updateCreateAssigneeSelection()"
                                           class="create-assignee-checkbox rounded border-gray-700 bg-gray-800 text-[#00B8D9] focus:ring-0">
                                    <span>{{ $user->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-800">
                    <button type="button" id="cancelTaskModal"
                            class="px-3 py-1 rounded-md bg-gray-800 text-gray-300 hover:bg-gray-700 transition text-md font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-3 py-1 rounded-md bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition text-md">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>




    <!-- Task Detail Modal -->
    <div id="taskDetailModal"
         class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto hidden">
        <div class="bg-gray-800 border border-gray-800/80 rounded-2xl w-full max-w-4xl p-6 relative shadow-2xl text-gray-300 max-h-[92vh] overflow-y-auto my-auto">

            <!-- Top Right Close Button -->
            <button type="button" onclick="closeTaskDetailModal()"
                    class="absolute top-5 right-5 text-gray-400 hover:text-white transition text-base">
                ✕
            </button>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- LEFT COLUMN (Content & Comments) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Task Title Header -->
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">TASK</span>
                        <h1 id="detailSummary" class="text-xl font-bold text-white">test</h1>
                    </div>

                    <!-- Description -->
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">DESCRIPTION</span>
                        <p id="detailDescription" class="text-xs text-gray-300 leading-relaxed">test</p>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">ATTACHMENTS</span>
                        <div id="detailAttachments" class="text-xs text-gray-400">
                            No attachments.
                        </div>
                    </div>

                    <!-- Subtasks Section -->
                    <div>
                        <!-- Subtasks Header & Add Button -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">SUBTASKS</span>
                            <button type="button" onclick="toggleSubtaskForm()"
                                    class="text-xs text-[#00B8D9] font-semibold hover:underline flex items-center gap-1 cursor-pointer">
                                + Add Subtask
                            </button>
                        </div>

                        <!-- Add Subtask Form -->
                        <div id="subtaskForm" class="hidden mb-3">
                            <div class="flex gap-2">
                                <input type="text" id="newSubtaskTitle" placeholder="Subtask title..."
                                       onkeydown="if(event.key === 'Enter'){ event.preventDefault(); addSubtask(); }"
                                       class="w-full bg-[#03060B] border border-gray-800 rounded-lg px-3 py-1.5 text-xs text-white focus:border-[#00B8D9] focus:outline-none">

                                <button type="button" onclick="addSubtask()"
                                        class="px-3 py-1.5 bg-[#00B8D9] text-gray-950 font-bold rounded-lg text-xs hover:bg-[#00A3C4] transition shrink-0 cursor-pointer">
                                    Add
                                </button>

                                <button type="button" onclick="toggleSubtaskForm()"
                                        class="px-2.5 py-1.5 bg-gray-800 text-gray-400 rounded-lg text-xs hover:text-white transition cursor-pointer">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <!-- Subtasks List Container -->
                        <div id="detailSubtasks" class="space-y-2">
                            <div class="text-xs text-gray-500 py-2">No subtasks</div>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">COMMENTS</span>
                        <div id="detailComments" class="text-xs text-gray-400 mb-3 space-y-2">
                            No comments yet.
                        </div>

                        <!-- Comment Input Box -->
                        <div class="space-y-2.5">
                            <textarea id="newCommentText" rows="3" placeholder="Write a comment..."
                                      class="w-full bg-gray-900 border border-gray-800 rounded-xl p-3 text-xs text-white focus:border-[#00B8D9] focus:outline-none resize-none"></textarea>
                            <div class="flex justify-end">
                                <button type="button" onclick="saveComment()"
                                        class="px-4 py-2 rounded-lg bg-[#00B8D9] hover:bg-[#00A3C4] text-white font-semibold text-xs shadow-lg shadow-[#00B8D9]/20 transition">
                                    Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN (Sidebar Details) -->
                <div class="lg:col-span-5 space-y-2.5">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">DETAILS</span>

                    <div class=" bg-gray-900 border border-gray-800/80 rounded-xl p-4 space-y-3.5 text-xs">
                        <!-- Project -->
                        <div class="">
                            <span class="text-[10px] text-gray-400 block mb-0.5">Project</span>
                            <a href="#" id="detailProject" class="text-[#00B8D9] hover:underline font-medium text-xs">test
                                project</a>
                        </div>

                        <!-- Status -->
                        <div>
                            <span class="text-xs text-gray-400 block mb-1">Status</span>
                            <div class="flex items-center gap-2">
                                <span id="detailStatusDot" class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span id="detailStatus" class="text-sm font-semibold text-white">To Do</span>
                            </div>
                        </div>

                        <!-- Task Progress -->
                        <div>
                            <span class="text-xs text-gray-400 block mb-1">Task progress</span>
                            <span id="stat-progress-text"
                                  class="text-sm font-semibold text-white">No subtasks · 0%</span>
                        </div>

                        <!-- Subtasks Counters -->
                        <div class="mt-3">
                            <span class="text-xs text-gray-400 block mb-1">Subtasks</span>
                            <p class="text-sm font-bold text-white"><span id="stat-total">0</span> total</p>
                            <p class="text-sm font-bold text-white"><span id="stat-completed">0</span> completed</p>
                            <p class="text-sm font-bold text-white"><span id="stat-remaining">0</span> remaining</p>
                        </div>

                        <!-- Priority -->
                        <div>
                            <span class="text-[10px] text-gray-400 block mb-0.5">Priority</span>
                            <span id="detailPriority" class="text-white font-medium text-xs">Medium</span>
                        </div>

                        <!-- Assignees -->
                        <div>
                            <span class="text-[10px] text-gray-400 block mb-1">Assignees</span>

                            <!-- Dynamic Assignees Container -->
                            <div id="detailAssignees" class="flex flex-wrap gap-1.5">
                            </div>
                        </div>

                        <!-- Reporter -->
                        <div>
                            <span class="text-[10px] text-gray-400 block mb-0.5">Reporter</span>
                            <span id="detailReporter" class="text-white font-medium text-xs">Admin</span>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <span class="text-[10px] text-gray-400 block mb-0.5">Due date</span>
                            <span id="detailDueDate" class="text-gray-400 text-xs">—</span>
                        </div>

                        <!-- Created -->
                        <div>
                            <span class="text-[10px] text-gray-400 block     mb-0.5">Created</span>
                            <span id="detailCreated" class="text-white font-medium text-xs">Aug 12, 2026 1:45 PM</span>
                        </div>
                    </div>

                    <button type="button" onclick="deleteTask()"
                            class="w-full py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-xs transition shadow-lg shadow-red-500/20">
                        Delete Task
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Edit Task Modal -->
    <div id="editTaskModal"
         class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-[#080D16] border border-gray-800 rounded-xl w-full max-w-lg p-6 shadow-2xl relative text-white max-h-[90vh] overflow-y-auto">

            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-800 mb-4">
                <h3 class="text-lg font-semibold text-gray-100">Edit Task</h3>
                <button type="button" onclick="closeEditTaskModal()" class="text-gray-400 hover:text-white text-xl">✕
                </button>
            </div>

            <form id="editTaskForm" onsubmit="submitEditTask(event)">
                <input type="hidden" id="editTaskId">

                <!-- 1. Summary -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Summary *</label>
                    <input type="text" id="editSummary" required
                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                </div>

                <!-- 2. Description -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Description</label>
                    <textarea id="editDescription" rows="3"
                              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:border-[#00B8D9] focus:outline-none"></textarea>
                </div>

                <!-- 3. Project & Status -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Project *</label>
                        <select id="editProject" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Status *</label>
                        <select id="editStatus" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 4. Priority & Due Date -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Priority *</label>
                        <select id="editPriority" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Due Date</label>
                        <input type="date" id="editDueDate"
                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                    </div>
                </div>

                <!-- 5. Assignees Dropdown -->
                <div class="mb-4 relative">
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Assignees</label>

                    <!-- Dropdown Button -->
                    <button type="button" id="assigneesDropdownBtn" onclick="toggleAssigneeDropdown()"
                            class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-xs text-white flex justify-between items-center focus:border-[#00B8D9] focus:outline-none">
                        <span id="selectedAssigneesText" class="truncate text-gray-400">Select Assignees...</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Hidden Real Select (Form Submit ke liye) -->
                    <select id="editAssignees" name="assignees[]" multiple class="hidden">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <!-- Floating Dropdown Menu -->
                    <div id="assigneesDropdownMenu"
                         class="hidden absolute z-50 mt-1 w-full bg-gray-900 border border-gray-800 rounded-lg shadow-xl max-h-48 overflow-y-auto p-1.5">
                        @foreach($users as $user)
                            <label class="flex items-center gap-2.5 px-2 py-1.5 hover:bg-gray-800 rounded cursor-pointer text-xs text-white">
                                <input type="checkbox" value="{{ $user->id }}" data-name="{{ $user->name }}"
                                       onchange="updateAssigneeSelection()"
                                       class="assignee-checkbox rounded border-gray-700 bg-gray-800 text-[#00B8D9] focus:ring-0">
                                <span>{{ $user->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 6. New Attachments -->
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Attach Files</label>
                    <input type="file" id="editAttachments" multiple accept="image/*,.pdf"
                           class="w-full text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-gray-200 hover:file:bg-gray-700 cursor-pointer">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2 pt-3 border-t border-gray-800">
                    <button type="button" onclick="closeEditTaskModal()"
                            class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg text-xs font-medium transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-[#00B8D9] hover:bg-[#00a3bf] text-black font-semibold rounded-lg text-xs transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        window.routes = {
            updateStatus: "{{ route('tasks.updateStatus') }}",
            reorderStatus: "{{ route('task-statuses.reorder') }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>


    <script src="{{ asset('js/tasks.js') }}"></script>
@endsection