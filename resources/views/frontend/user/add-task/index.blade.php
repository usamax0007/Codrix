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
                    <a href="{{ route('user.task-status') }}" class="px-4 py-2 rounded-md bg-gray-800 border border-gray-700 text-white text-sm font-medium hover:bg-gray-700 transition">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $todoTasks = $tasks->where('status', 'to_do');
                    $inProgressTasks = $tasks->where('status', 'in_progress');
                    $testingTasks = $tasks->where('status', 'testing');
                    $doneTasks = $tasks->where('status', 'done');
                @endphp

                <!-- To Do Column -->
                <div class="bg-gray-950/40 rounded-lg border border-gray-800 p-4 min-h-screen">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                            <h2 class="font-semibold text-white">To Do</h2>
                        </div>
                        <span class="text-xs bg-gray-800 text-gray-300 rounded-full px-2 py-0.5">{{ $todoTasks->count() }}</span>
                    </div>

                    @foreach($todoTasks as $task)
                        <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3">
                            <div class="absolute top-3 right-3 flex gap-2">
                                <form action="{{ route('user.task.destroy', $task) }}" method="POST" class="inline">
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

                <!-- In Progress Column -->
                <div class="bg-gray-950/40 rounded-lg border border-gray-800 p-4 min-h-screen">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                            <h2 class="font-semibold text-white">In Progress</h2>
                        </div>
                        <span class="text-xs bg-gray-800 text-gray-300 rounded-full px-2 py-0.5">{{ $inProgressTasks->count() }}</span>
                    </div>

                    @foreach($inProgressTasks as $task)
                        <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3">
                            <div class="absolute top-3 right-3 flex gap-2">
                                <form action="{{ route('user.task.destroy', $task) }}" method="POST" class="inline">
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

                <!-- Testing Column -->
                <div class="bg-gray-950/40 rounded-lg border border-gray-800 p-4 min-h-screen">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                            <h2 class="font-semibold text-white">Testing</h2>
                        </div>
                        <span class="text-xs bg-gray-800 text-gray-300 rounded-full px-2 py-0.5">{{ $testingTasks->count() }}</span>
                    </div>

                    @foreach($testingTasks as $task)
                        <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3">
                            <div class="absolute top-3 right-3 flex gap-2">
                                <form action="{{ route('user.task.destroy', $task) }}" method="POST" class="inline">
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

                <!-- Done Column -->
                <div class="bg-gray-950/40 rounded-lg border border-gray-800 p-4 min-h-screen">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            <h2 class="font-semibold text-white">Done</h2>
                        </div>
                        <span class="text-xs bg-gray-800 text-gray-300 rounded-full px-2 py-0.5">{{ $doneTasks->count() }}</span>
                    </div>

                    @foreach($doneTasks as $task)
                        <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 relative hover:border-gray-600 transition cursor-pointer mb-3">
                            <div class="absolute top-3 right-3 flex gap-2">
                                <form action="{{ route('user.task.destroy', $task) }}" method="POST" class="inline">
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
        </main>
    </div>
@endsection
