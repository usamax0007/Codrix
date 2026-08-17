@extends('user.layout.app')

@section('content')
    <style>
        .transition {
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
                <a href="{{ route('task-statuses.index') }}"
                   class="bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-300 font-medium px-4 py-2 rounded-lg transition text-sm flex items-center">
                    Manage Statuses
                </a>
                <button id="openTaskModal"
                        class="bg-emerald-500 hover:bg-emerald-600 text-gray-900 font-semibold px-4 py-2 rounded-lg transition text-sm">
                    + Add Task
                </button>
            </div>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 w-full pb-6 items-start min-h-[calc(100vh-180px)]">
            @foreach($statuses as $status)
                <div class="bg-[#0B1019] border border-gray-800/80 rounded-lg w-full flex flex-col h-[calc(100vh-220px)] min-h-[550px]">

                    <!-- Status Column Header -->
                    <div class="px-4 py-3 border-b border-gray-800/80 flex justify-between items-center shrink-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full"
                                  style="background-color: {{ $status->color ?? '#3B82F6' }}"></span>
                            <h3 class="font-bold text-white text-xs tracking-wide">{{ $status->name }}</h3>
                        </div>
                        <span class="text-[11px] bg-[#16202E] text-gray-400 px-2 py-0.5 rounded text-center min-w-[20px]">
                    {{ $status->tasks->count() }}
                </span>
                    </div>

                    <!-- Column Cards Container -->
                    <div class="p-3 space-y-3 overflow-y-auto flex-1 custom-scrollbar">
                        @forelse($status->tasks as $task)
                            <div data-task-id="{{ $task->id }}"
                                 data-task='@json($task)'
                                 onclick="openTaskDetail(this)"
                                 class="bg-[#090D14] border border-gray-800 hover:border-[#00B8D9]/40 rounded-lg p-3.5 transition relative group cursor-pointer">

                                <!-- Project Tag & Delete Button -->
                                <div class="flex justify-between items-start mb-1.5">
                                    <span class="text-[16px] font-bold text-[#00B8D9] tracking-wider uppercase">
                                        {{ $task->project->name ?? 'NO PROJECT' }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <!-- Edit Button (SVG) -->
                                        <button type="button"
                                                onclick="event.stopPropagation(); openEditModalFromCard(this)"
                                                class="text-gray-500 hover:text-[#00B8D9] transition p-1"
                                                title="Edit Task">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                              onsubmit="return confirm('Delete this task?')" class="inline"
                                              onclick="event.stopPropagation()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-gray-500 hover:text-red-400 text-xs leading-none transition"
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
                            <div class="text-center py-8 text-xs text-gray-600 border border-dashed border-gray-800/60 rounded-lg">
                                No tasks here
                            </div>
                        @endforelse
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
                <h2 class="text-md font-bold text-white">Add Task</h2>
                <button type="button" id="closeTaskModal"
                        class="text-gray-400 hover:text-white hover:bg-gray-800/60 rounded-lg w-6 h-6 flex items-center justify-center transition text-xs">
                    ✕
                </button>
            </div>
            <p class="text-[12px] text-gray-300 mb-4">Fill in the details to create a new board item.</p>

            <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-2.5">
                @csrf

                <!-- Project & Status -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Project <span
                                    class="text-[#00B8D9]">*</span></label>
                        <select name="project_id" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Status <span
                                    class="text-[#00B8D9]">*</span></label>
                        <select name="task_status_id" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Summary -->
                <div>
                    <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Summary <span
                                class="text-[#00B8D9]">*</span></label>
                    <input type="text" name="summary" required placeholder="Task summary"
                           class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                </div>

                <!-- Priority & Due Date -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Priority <span
                                    class="text-[#00B8D9]">*</span></label>
                        <select name="priority" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Due date</label>
                        <input type="date" name="due_date"
                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white [color-scheme:dark] focus:border-[#00B8D9] focus:outline-none">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Description</label>
                    <textarea name="description" rows="3" placeholder="Task details..."
                              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white focus:border-[#00B8D9] focus:outline-none resize-none"></textarea>
                </div>

                <!-- Attachment & Assignees -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Attachment</label>
                        <input type="file" name="attachments[]" multiple
                               class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-[10px] text-white file:mr-1 file:py-0.5 file:px-1.5 file:rounded file:border-0 file:bg-gray-800 file:text-[#00B8D9] file:text-[10px]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-medium text-gray-300 mb-0.5">Assignee</label>
                        <select name="assignees[]"
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-2.5 py-1 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                            <option value="" disabled selected>Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-800">
                    <button type="button" id="cancelTaskModal"
                            class="px-3 py-1 rounded-md bg-gray-800 text-gray-300 hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-3 py-1 rounded-md bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition text-sm">
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
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">SUBTASKS</span>
                            <button type="button" onclick="toggleSubtaskForm()"
                                    class="text-[11px] text-[#00B8D9] font-semibold hover:underline flex items-center gap-1">
                                + Add Subtask
                            </button>
                        </div>

                        <div id="subtaskForm" class="hidden mb-3">
                            <div class="flex gap-2">
                                <input type="text" id="newSubtaskTitle" placeholder="Subtask title..."
                                       class="w-full bg-[#03060B] border border-gray-800 rounded-lg px-2.5 py-1.5 text-xs text-white focus:border-[#00B8D9] focus:outline-none">
                                <button type="button" onclick="extracted();"
                                        class="px-3 py-1.5 bg-[#00B8D9] text-gray-950 font-bold rounded-lg text-xs hover:bg-[#00A3C4] transition shrink-0">
                                    Add
                                </button>
                                <button type="button" onclick="toggleSubtaskForm()"
                                        class="px-2.5 py-1.5 bg-gray-800 text-gray-400 rounded-lg text-xs hover:text-white transition">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <!-- Subtasks List -->
                        <div id="detailSubtasks" class="text-xs text-gray-400 space-y-1.5">
                            No subtasks
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
                            <span class="text-[10px] text-gray-400 block mb-0.5">Status</span>
                            <div class="flex items-center gap-2 text-white font-medium text-xs">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                <span id="detailStatus">To Do</span>
                            </div>
                        </div>

                        <!-- Task Progress -->
                        <div>
                            <span class="text-[10px] text-gray-400 block mb-0.5">Task progress</span>
                            <span class="text-gray-400 text-[11px]">No subtasks · 0%</span>
                        </div>

                        <!-- Subtasks Summary -->
                        <div>
                            <span class="text-[10px] text-gray-400 block mb-0.5">Subtasks</span>
                            <div class="text-white space-y-0.5 font-medium text-xs">
                                <div>0 total</div>
                                <div>0 completed</div>
                                <div>0 remaining</div>
                            </div>
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

                <!-- 5. Assignees -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Assignees</label>
                    <select id="editAssignees" multiple
                            class="w-full bg-gray-900 border border-gray-800 rounded-lg p-2 text-xs text-white focus:border-[#00B8D9] focus:outline-none h-24">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-[10px] text-gray-500 block mt-1">Press ctrl and select multiple assignee.</span>
                </div>

                <!-- 6. New Attachments -->
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Attach Files</label>
                    <input type="file" id="editAttachments" multiple
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
        // Global Active Task ID Variable
        let currentTaskId = null;

        // Add Task Modal Handlers
        let taskModal = document.getElementById('taskModal');
        let openTaskBtn = document.getElementById('openTaskModal');
        let closeTaskBtn = document.getElementById('closeTaskModal');
        let cancelTaskBtn = document.getElementById('cancelTaskModal');

        if (openTaskBtn) openTaskBtn.onclick = () => taskModal.classList.remove('hidden');
        if (closeTaskBtn) closeTaskBtn.onclick = () => taskModal.classList.add('hidden');
        if (cancelTaskBtn) cancelTaskBtn.onclick = () => taskModal.classList.add('hidden');

        function openTaskDetail(element) {
            try {
                let rawData = element.getAttribute('data-task');
                if (!rawData) return;

                let task = JSON.parse(rawData);
                currentTaskId = task.id;

                // Content Data Fill
                document.getElementById('detailSummary').innerText = task.summary || 'No Summary';
                document.getElementById('detailDescription').innerText = task.description || 'No description provided.';

                // Right Sidebar Data Fill
                document.getElementById('detailProject').innerText = task.project ? task.project.name : 'NO PROJECT';
                document.getElementById('detailStatus').innerText = task.status ? task.status.name : (task.task_status ? task.task_status.name : 'To Do');
                document.getElementById('detailPriority').innerText = task.priority || 'Medium';
                document.getElementById('detailDueDate').innerText = task.due_date || '—';

                if (task.created_at) {
                    let date = new Date(task.created_at);
                    document.getElementById('detailCreated').innerText = date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                }

                // Assignees Display
                let assigneesContainer = document.getElementById('detailAssignees');
                if (assigneesContainer) {
                    if (task.assignees && task.assignees.length > 0) {
                        assigneesContainer.innerHTML = task.assignees.map(user => `<div class="inline-flex items-center gap-1.5 bg-[#080D16] border border-gray-800 px-2.5 py-1 rounded text-[11px] text-gray-300"><span>${user.name}</span></div>`).join('');
                    } else {
                        assigneesContainer.innerHTML = '<span class="text-[11px] text-gray-500">Unassigned</span>';
                    }
                }

                // Attachments Fill
                let attachContainer = document.getElementById('detailAttachments');
                if (attachContainer) {
                    if (task.attachments && task.attachments.length > 0) {
                        let files = typeof task.attachments === 'string' ? JSON.parse(task.attachments) : task.attachments;
                        let fileHtml = files.map(f => {
                            let path = typeof f === 'object' ? f.file_path : f;
                            let name = (typeof f === 'object' && f.original_name) ? f.original_name : path.split('/').pop();
                            return `<div class="mt-1"><a href="/storage/${path}" target="_blank" download class="text-[#00B8D9] hover:underline flex items-center gap-1">📎 ${name}</a></div>`;
                        }).join('');
                        attachContainer.innerHTML = fileHtml;
                    } else {
                        attachContainer.innerText = 'No attachments.';
                    }
                }

                // Render Subtasks
                renderSubtasks(task.subtasks || []);

                renderComments(task.comments || []);

                // Open Modal
                document.getElementById('taskDetailModal').classList.remove('hidden');

            } catch (error) {
                console.error("Error loading task details:", error);
            }
        }

        function closeTaskDetailModal() {
            document.getElementById('taskDetailModal').classList.add('hidden');
        }

        // Toggle Subtask Form Input
        function toggleSubtaskForm() {
            let form = document.getElementById('subtaskForm');
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                document.getElementById('newSubtaskTitle').focus();
            }
        }

        // Render Subtasks List
        function renderSubtasks(subtasks) {
            let container = document.getElementById('detailSubtasks');
            if (!subtasks || subtasks.length === 0) {
                container.innerHTML = '<span class="text-gray-500">No subtasks</span>';
                return;
            }

            container.innerHTML = subtasks.map(st => `
            <div class="flex items-center gap-2 bg-[#03060B] border border-gray-800 px-3 py-1.5 rounded-lg text-xs text-gray-300 my-1">
                <input type="checkbox" ${st.is_completed ? 'checked' : ''} class="rounded bg-gray-900 border-gray-700 text-[#00B8D9] focus:ring-0">
                <span class="${st.is_completed ? 'line-through text-gray-500' : ''}">${st.title}</span>
            </div>
        `).join('');
        }

        // Save Subtask Function
        function saveSubtask() {
            const titleInput = document.getElementById('newSubtaskTitle');
            const title = titleInput ? titleInput.value.trim() : '';

            if (!title) {
                alert('Subtask title is required');
                return;
            }

            if (!currentTaskId) {
                alert('Missing Task ID');
                return;
            }

            fetch('/user/subtasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    task_id: currentTaskId,
                    title: title
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        titleInput.value = '';
                        if (typeof toggleSubtaskForm === 'function') {
                            toggleSubtaskForm();
                        }

                        const container = document.getElementById('detailSubtasks');
                        if (container) {
                            if (container.innerText.includes('No subtasks')) {
                                container.innerHTML = '';
                            }
                            container.innerHTML += `
                        <div class="flex items-center gap-2 bg-[#03060B] border border-gray-800 px-3 py-1.5 rounded-lg text-xs text-gray-300 my-1">
                            <input type="checkbox" class="rounded bg-gray-900 border-gray-700 text-[#00B8D9] focus:ring-0">
                            <span>${data.subtask.title}</span>
                        </div>
                    `;
                        }

                        const taskCard = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                        if (taskCard) {
                            let taskData = JSON.parse(taskCard.getAttribute('data-task'));
                            if (!taskData.subtasks) taskData.subtasks = [];
                            taskData.subtasks.push(data.subtask);
                            taskCard.setAttribute('data-task', JSON.stringify(taskData));

                            const cardSubtaskContainer = document.getElementById(`card-subtasks-${currentTaskId}`);
                            if (cardSubtaskContainer) {
                                cardSubtaskContainer.innerHTML = `<span class="text-[11px] text-[#00B8D9] font-medium">${taskData.subtasks.length} Subtask(s)</span>`;
                            }
                        }

                    } else {
                        alert('Error: ' + (data.error || 'Subtask can not be saved'));
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert('Server Error!');
                });
        }

        function renderComments(comments) {
            let container = document.getElementById('detailComments');
            if (!comments || comments.length === 0) {
                container.innerHTML = '<span class="text-gray-500 block">No comments yet.</span>';
                return;
            }

            container.innerHTML = comments.map(c => `
            <div class="bg-[#03060B] border border-gray-800 p-2.5 rounded-lg text-xs text-gray-300">
                <div class="flex justify-between items-center mb-1 text-[10px] text-gray-500">
                    <span class="font-semibold text-gray-400">${c.user ? c.user.name : 'User'}</span>
                    <span>${c.created_at ? new Date(c.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''}</span>
                </div>
                <p class="text-gray-300">${c.comment}</p>
            </div>
        `).join('');
        }

        function saveComment() {
            const commentInput = document.getElementById('newCommentText');
            const comment = commentInput ? commentInput.value.trim() : '';

            if (!comment) {
                alert('Comment required');
                return;
            }

            if (!currentTaskId) {
                alert('Task ID missing hai');
                return;
            }

            fetch('/user/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    task_id: currentTaskId,
                    comment: comment
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        commentInput.value = '';

                        const container = document.getElementById('detailComments');
                        if (container.innerText.includes('No comments yet.')) {
                            container.innerHTML = '';
                        }

                        container.innerHTML += `
                    <div class="bg-[#03060B] border border-gray-800 p-2.5 rounded-lg text-xs text-gray-300">
                        <div class="flex justify-between items-center mb-1 text-[10px] text-gray-500">
                            <span class="font-semibold text-gray-400">${data.comment.user ? data.comment.user.name : 'You'}</span>
                            <span>Just now</span>
                        </div>
                        <p class="text-gray-300">${data.comment.comment || comment}</p>
                    </div>
                `;
                        const taskCard = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                        if (taskCard) {
                            let taskData = JSON.parse(taskCard.getAttribute('data-task'));
                            if (!taskData.comments) taskData.comments = [];
                            taskData.comments.push(data.comment);
                            taskCard.setAttribute('data-task', JSON.stringify(taskData));
                        }
                    } else {
                        alert('Error: ' + (data.error || 'Comment not saved'));
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert('Server Error!');
                });
        }

        function deleteTask() {
            if (!currentTaskId) {
                alert('Task ID is missing!');
                return;
            }

            if (!confirm('Do you want to delete this task?')) {
                return;
            }

            fetch(`/user/tasks/${currentTaskId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeTaskDetailModal();
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Task not delete'));
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert('Server Error!');
                });
        }




        function openEditTaskModal(task) {
            if (!task) return;

            document.getElementById('editTaskId').value = task.id;
            document.getElementById('editSummary').value = task.summary || '';
            document.getElementById('editDescription').value = task.description || '';

            if (document.getElementById('editProject')) {
                document.getElementById('editProject').value = task.project_id || '';
            }
            if (document.getElementById('editStatus')) {
                document.getElementById('editStatus').value = task.task_status_id || '';
            }
            if (document.getElementById('editPriority')) {
                document.getElementById('editPriority').value = task.priority || 'Medium';
            }
            if (document.getElementById('editDueDate')) {
                document.getElementById('editDueDate').value = task.due_date || '';
            }

            // Assignees Auto-select
            let assigneesSelect = document.getElementById('editAssignees');
            if (assigneesSelect) {
                let assignedIds = task.assignees ? task.assignees.map(u => u.id) : [];
                Array.from(assigneesSelect.options).forEach(option => {
                    option.selected = assignedIds.includes(parseInt(option.value));
                });
            }

            // Attachments Reset
            let fileInput = document.getElementById('editAttachments');
            if (fileInput) fileInput.value = '';

            document.getElementById('editTaskModal').classList.remove('hidden');
        }

        function openEditModalFromCard(btn) {
            const card = btn.closest('[data-task]');
            if (!card) return;

            try {
                let task = JSON.parse(card.getAttribute('data-task'));
                openEditTaskModal(task);
            } catch (e) {
                console.error("Task data parse error:", e);
            }
        }

        function closeEditTaskModal() {
            document.getElementById('editTaskModal').classList.add('hidden');
        }

        function closeEditModal() {
            closeEditTaskModal();
        }

        function submitEditTask(e) {
            e.preventDefault();
            const taskId = document.getElementById('editTaskId').value;
            let formData = new FormData();

            // Laravel Multipart PUT Spoofing
            formData.append('_method', 'PUT');

            formData.append('summary', document.getElementById('editSummary').value);
            formData.append('description', document.getElementById('editDescription').value);

            if (document.getElementById('editProject')) {
                formData.append('project_id', document.getElementById('editProject').value);
            }
            if (document.getElementById('editStatus')) {
                formData.append('task_status_id', document.getElementById('editStatus').value);
            }
            if (document.getElementById('editPriority')) {
                formData.append('priority', document.getElementById('editPriority').value);
            }
            if (document.getElementById('editDueDate')) {
                formData.append('due_date', document.getElementById('editDueDate').value);
            }

            // Assignees
            let assigneesSelect = document.getElementById('editAssignees');
            if (assigneesSelect) {
                let selectedAssignees = Array.from(assigneesSelect.selectedOptions).map(o => o.value);
                selectedAssignees.forEach(id => formData.append('assignees[]', id));
            }

            // Attachments
            let fileInput = document.getElementById('editAttachments');
            if (fileInput && fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('attachments[]', fileInput.files[i]);
                }
            }

            fetch(`/user/tasks/${taskId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(res => res.json())
                .then(resData => {
                    if (resData.success) {
                        closeEditTaskModal();
                        location.reload();
                    } else {
                        alert('Error updating task: ' + (resData.error || 'Unknown error'));
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert('Server Error!');
                });
        }

        function extracted() {
            saveSubtask();
        }
    </script>
@endsection