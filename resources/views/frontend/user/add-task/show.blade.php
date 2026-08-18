@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('user.task.index') }}" class="text-gray-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white">{{ $task->summary }}</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                        <h2 class="text-xs font-semibold text-gray-400 tracking-wider mb-3">TASK</h2>
                        <h3 class="text-xl font-bold text-white">{{ $task->summary }}</h3>
                    </div>

                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                        <h2 class="text-xs font-semibold text-gray-400 tracking-wider mb-3">DESCRIPTION</h2>
                        <p class="text-gray-300">{{ $task->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                        <h2 class="text-xs font-semibold text-gray-400 tracking-wider mb-3">ATTACHMENTS</h2>
                        <p class="text-gray-500">No attachments.</p>
                    </div>

                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-xs font-semibold text-gray-400 tracking-wider">SUBTASKS</h2>
                            <button onclick="document.getElementById('addSubtaskForm').classList.toggle('hidden')" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition">+ Add Subtask</button>
                        </div>
                        
                        @if($task->subtasks->count() > 0)
                            <div class="space-y-2 mb-4">
                                @foreach($task->subtasks as $subtask)
                                    <div class="flex items-center gap-3 bg-gray-800 rounded-lg p-3">
                                        <span class="text-sm text-gray-300">{{ $subtask->title }}</span>
                                        <form action="{{ route('user.task.subtask.delete', [$task, $subtask]) }}" method="POST" class="ml-auto inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-400 transition" onclick="return confirm('Are you sure you want to delete this subtask?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 mb-4">No subtasks</p>
                        @endif

                        <div id="addSubtaskForm" class="hidden">
                            <form action="{{ route('user.task.subtask', $task) }}" method="POST">
                                @csrf
                                <input 
                                    type="text" 
                                    name="title" 
                                    placeholder="Enter subtask title..." 
                                    class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition"
                                    required
                                >
                                <div class="flex justify-end gap-2 mt-3">
                                    <button type="button" onclick="document.getElementById('addSubtaskForm').classList.add('hidden')" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                                        Add Subtask
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                        <h2 class="text-xs font-semibold text-gray-400 tracking-wider mb-3">COMMENTS</h2>
                        
                        @if($task->comments->count() > 0)
                            <div class="space-y-4 mb-4">
                                @foreach($task->comments as $comment)
                                    <div class="bg-gray-800 rounded-lg p-4 relative">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-sm flex items-center justify-center font-semibold">{{ substr($comment->user->name, 0, 1) }}</div>
                                            <div>
                                                <p class="text-sm font-semibold text-white">{{ $comment->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $comment->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                            @if(auth()->id() === $comment->user_id)
                                                <div class="ml-auto relative">
                                                    <button onclick="document.getElementById('commentDropdown-{{ $comment->id }}').classList.toggle('hidden')" class="text-gray-400 hover:text-white p-1 rounded hover:bg-gray-700 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="1"/>
                                                            <circle cx="12" cy="5" r="1"/>
                                                            <circle cx="12" cy="19" r="1"/>
                                                        </svg>
                                                    </button>
                                                    <div id="commentDropdown-{{ $comment->id }}" class="hidden absolute right-0 top-8 bg-gray-700 border border-gray-600 rounded-lg shadow-lg z-10 min-w-32">
                                                        <button onclick="editComment({{ $comment->id }}, {{ json_encode($comment->content) }})" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 transition flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit
                                                        </button>
                                                        <form action="{{ route('user.task.comment.delete', [$task, $comment]) }}" method="POST" class="w-full">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-600 transition flex items-center gap-2" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                                                </svg>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <p id="commentContent-{{ $comment->id }}" class="text-gray-300 text-sm">{{ $comment->content }}</p>
                                        
                                        <div id="editForm-{{ $comment->id }}" class="hidden mt-3">
                                            <form action="{{ route('user.task.comment.update', [$task, $comment]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <textarea 
                                                    name="content"
                                                    id="editTextarea-{{ $comment->id }}"
                                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition resize-none"
                                                    rows="3"
                                                    required
                                                ></textarea>
                                                <div class="flex justify-end gap-2 mt-3">
                                                    <button type="button" onclick="cancelEdit({{ $comment->id }})" class="px-4 py-2 rounded-md bg-gray-600 text-gray-300 text-sm font-semibold hover:bg-gray-500 transition">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                                                        Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 mb-4">No comments yet.</p>
                        @endif
                        
                        <form action="{{ route('user.task.comment', $task) }}" method="POST">
                            @csrf
                            <textarea 
                                name="content"
                                placeholder="Write a comment..." 
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition resize-none"
                                rows="3"
                                required
                            ></textarea>
                            <div class="flex justify-end mt-3">
                                <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                                    Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 sticky top-6">
                        <h2 class="text-xs font-semibold text-gray-400 tracking-wider mb-4">DETAILS</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Project</label>
                                <p class="text-sm text-gray-300">
                                    @if($task->project)
                                        {{ $task->project->name }}
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Status</label>
                                <p class="text-sm text-gray-300">{{ $task->status }}</p>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Task progress</label>
                                @php
                                    $totalSubtasks = $task->subtasks->count();
                                    $completedSubtasks = $task->subtasks->where('is_completed', true)->count();
                                    $progress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                                @endphp
                                <p class="text-sm text-gray-300">{{ $totalSubtasks > 0 ? $progress . '%' : 'No subtasks - 0%' }}</p>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Subtasks</label>
                                <p class="text-sm text-gray-300">{{ $totalSubtasks }} total, {{ $completedSubtasks }} completed, {{ $totalSubtasks - $completedSubtasks }} remaining</p>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Priority</label>
                                <span class="inline-block text-xs font-semibold {{ $task->priority == 'high' ? 'text-red-400 bg-red-400/10 border-red-400/30' : ($task->priority == 'medium' ? 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30' : 'text-green-400 bg-green-400/10 border-green-400/30') }} border rounded px-2 py-0.5">{{ ucfirst($task->priority) }}</span>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Assignees</label>
                                <div class="flex items-center gap-2">
                                    @if($task->assignee)
                                        <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-semibold">{{ substr($task->assignee->name, 0, 1) }}</div>
                                        <span class="text-sm text-gray-300">{{ $task->assignee->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">Unassigned</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Reporter</label>
                                <p class="text-sm text-gray-300">Admin</p>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Due date</label>
                                <p class="text-sm text-gray-300">{{ $task->due_date?->format('M d, Y') ?? '—' }}</p>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Created</label>
                                <p class="text-sm text-gray-300">{{ $task->created_at?->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-800">
                            <form action="{{ route('user.task.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 rounded-md bg-red-600/10 border border-red-600/30 text-red-400 text-sm font-semibold hover:bg-red-600/20 transition">
                                    Delete Task
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function editComment(commentId, content) {
            document.getElementById('commentDropdown-' + commentId).classList.add('hidden');
            document.getElementById('commentContent-' + commentId).classList.add('hidden');
            document.getElementById('editForm-' + commentId).classList.remove('hidden');
            document.getElementById('editTextarea-' + commentId).value = content;
        }

        function cancelEdit(commentId) {
            document.getElementById('editForm-' + commentId).classList.add('hidden');
            document.getElementById('commentContent-' + commentId).classList.remove('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="commentDropdown-"]');
            dropdowns.forEach(function(dropdown) {
                if (!dropdown.classList.contains('hidden')) {
                    const button = dropdown.previousElementSibling;
                    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                        dropdown.classList.add('hidden');
                    }
                }
            });
        });
    </script>
@endsection
