@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('admin.tasks.index') }}" class="text-gray-400 hover:text-white transition">
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
                        @if($task->attachment)
                            <div class="flex items-center gap-3">
                                @if(in_array(strtolower(pathinfo($task->attachment, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('storage/attachments/' . $task->attachment) }}" alt="Attachment" class="w-32 h-32 object-cover rounded-lg border border-gray-700">
                                @else
                                    <a href="{{ asset('storage/attachments/' . $task->attachment) }}" target="_blank" class="flex items-center gap-2 text-emerald-400 hover:text-emerald-300 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                                        </svg>
                                        {{ $task->attachment }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-500">No attachments.</p>
                        @endif
                    </div>

                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-xs font-semibold text-gray-400 tracking-wider">SUBTASKS</h2>
                            <button onclick="document.getElementById('addSubtaskForm').classList.toggle('hidden')" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition">+ Add Subtask</button>
                        </div>

                        <div id="subtaskList" class="space-y-2 mb-4">
                            @if($task->subtasks->count() > 0)
                                @foreach($task->subtasks as $subtask)
                                    <div class="flex items-center gap-3 bg-gray-800 rounded-lg p-3">
                                        <input type="checkbox" id="subtask-{{ $subtask->id }}" {{ $subtask->is_completed ? 'checked' : '' }} onchange="toggleSubtask({{ $subtask->id }})" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-gray-800 cursor-pointer">

                                        <label for="subtask-{{ $subtask->id }}" class="text-sm text-gray-300 cursor-pointer flex-1 {{ $subtask->is_completed ? 'line-through text-gray-500' : '' }}">
                                            {{ $subtask->title }}
                                        </label>

                                        <button type="button" onclick="deleteSubtask({{ $subtask->id }})" class="text-gray-500 hover:text-red-400 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"/>
                                                <line x1="6" y1="6" x2="18" y2="18"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        @if($task->subtasks->count() === 0)
                            <p id="noSubtasks" class="text-gray-500 mb-4">No subtasks</p>
                        @endif

                        <div id="addSubtaskForm" class="hidden">
                            <form id="subtaskForm" onsubmit="addSubtask(event)">
                                @csrf
                                <input type="text" name="title" id="subtaskTitle" placeholder="Enter subtask title..." class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" required>
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

                    <div id="commentsSection" class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                        <h2 class="text-xs font-semibold text-gray-400 tracking-wider mb-3">
                            COMMENTS
                        </h2>

                        <div id="commentList" class="space-y-4 mb-4">
                            @foreach($task->comments as $comment)
                                <div id="comment-{{ $comment->id }}" class="bg-gray-800 rounded-lg p-4 relative">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-sm flex items-center justify-center font-semibold">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-white">
                                                {{ $comment->user->name }}
                                            </p>

                                            <p class="text-xs text-gray-500">
                                                {{ $comment->created_at->format('M d, Y h:i A') }}
                                            </p>
                                        </div>

                                        @if(auth()->id() === $comment->user_id)
                                            <div class="ml-auto relative">
                                                <button type="button" onclick="toggleCommentDropdown({{ $comment->id }})" class="text-gray-400 hover:text-white p-1 rounded hover:bg-gray-700 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="1"/>
                                                        <circle cx="12" cy="5" r="1"/>
                                                        <circle cx="12" cy="19" r="1"/>
                                                    </svg>
                                                </button>

                                                <div id="commentDropdown-{{ $comment->id }}" class="hidden absolute right-0 top-8 bg-gray-700 border border-gray-600 rounded-lg shadow-lg z-10 min-w-32">
                                                    <button type="button" onclick="deleteComment({{ $comment->id }})" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-600 transition flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <p id="commentContent-{{ $comment->id }}" class="text-gray-300 text-sm">{{ $comment->content }}</p>
                                </div>
                            @endforeach
                        </div>

                        @if($task->comments->count() === 0)
                            <p id="noComments" class="text-gray-500 mb-4">
                                No comments yet.
                            </p>
                        @endif

                        <form id="commentForm" onsubmit="addComment(event)">
                            @csrf
                            <textarea name="content" id="commentContent" placeholder="Write a comment..." class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition resize-none" rows="3" required></textarea>
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
                                <p id="taskProgress" class="text-sm text-gray-300 mb-2">{{ $totalSubtasks > 0 ? $progress . '%' : 'No subtasks - 0%' }}</p>
                                @if($totalSubtasks > 0)
                                    <div class="w-full bg-gray-700 rounded-full h-1.5">
                                        <div class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Subtasks</label>
                                <p id="subtaskCounts" class="text-sm text-gray-300">{{ $totalSubtasks }} total, {{ $completedSubtasks }} completed, {{ $totalSubtasks - $completedSubtasks }} remaining</p>
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
                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
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
        function addSubtask(event) {
            event.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const title = document.getElementById('subtaskTitle').value;

            fetch(`{{ route('admin.tasks.subtask', $task) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ title: title })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error adding subtask:', error);
            });
        }

        function toggleSubtask(subtaskId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const checkbox = document.getElementById('subtask-' + subtaskId);
            const label = checkbox.nextElementSibling;

            fetch(`{{ route('admin.tasks.subtask.toggle', [$task, ':subtask']) }}`.replace(':subtask', subtaskId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (checkbox.checked) {
                        label.classList.add('line-through', 'text-gray-500');
                    } else {
                        label.classList.remove('line-through', 'text-gray-500');
                    }
                    updateProgress();
                }
            })
            .catch(error => {
                console.error('Error toggling subtask:', error);
                checkbox.checked = !checkbox.checked;
            });
        }

        function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`{{ route('admin.tasks.comment.delete', [$task, ':comment']) }}`.replace(':comment', commentId), {
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
                console.error('Error deleting comment:', error);
            });
        }

        function deleteSubtask(subtaskId) {
            if (!confirm('Are you sure you want to delete this subtask?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`{{ route('admin.tasks.subtask.delete', [$task, ':subtask']) }}`.replace(':subtask', subtaskId), {
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
                console.error('Error deleting subtask:', error);
            });
        }

        function addComment(event) {
            event.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const content = document.getElementById('commentContent').value;

            fetch(`{{ route('admin.tasks.comment', $task) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ content: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error adding comment:', error);
            });
        }

        function updateProgress() {
            const subtaskList = document.querySelector('.space-y-2.mb-4');
            const totalSubtasks = subtaskList ? subtaskList.querySelectorAll('.flex').length : 0;
            const completedSubtasks = subtaskList ? subtaskList.querySelectorAll('input[type="checkbox"]:checked').length : 0;
            const progress = totalSubtasks > 0 ? Math.round((completedSubtasks / totalSubtasks) * 100) : 0;

            const progressText = document.getElementById('taskProgress');
            if (progressText) {
                progressText.textContent = totalSubtasks > 0 ? progress + '%' : 'No subtasks - 0%';
            }

            const countsText = document.getElementById('subtaskCounts');
            if (countsText) {
                countsText.textContent = `${totalSubtasks} total, ${completedSubtasks} completed, ${totalSubtasks - completedSubtasks} remaining`;
            }

            const progressBar = document.querySelector('.bg-emerald-500');
            if (progressBar && totalSubtasks > 0) {
                progressBar.style.width = progress + '%';
            }
        }

        function toggleCommentDropdown(commentId) {
            const dropdown = document.getElementById('commentDropdown-' + commentId);
            if (!dropdown) return;
            dropdown.classList.toggle('hidden');
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
