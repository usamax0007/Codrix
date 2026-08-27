@extends('frontend.super-admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">{{ $task->title }}</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('super-admin.tasks.edit', $task) }}" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                        Edit Task
                    </a>
                    <a href="{{ route('super-admin.tasks.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                        Back to Tasks
                    </a>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                        <p class="text-white">{{ $task->description }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Project</label>
                            <p class="text-white">{{ $task->project->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Assignee</label>
                            <p class="text-white">{{ $task->assignee->name ?? 'Unassigned' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                            <span class="px-3 py-1 rounded-full text-sm bg-blue-900 text-blue-300">{{ $task->status }}</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Created At</label>
                            <p class="text-white">{{ $task->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @if($task->attachment)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Attachment</label>
                            <a href="{{ asset('storage/attachments/' . $task->attachment) }}" target="_blank" class="text-blue-400 hover:text-blue-300">Download Attachment</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Subtasks</h2>
                <div class="space-y-2">
                    @forelse($task->subtasks as $subtask)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" {{ $subtask->is_completed ? 'checked' : '' }} class="w-4 h-4 rounded">
                                <span class="text-white {{ $subtask->is_completed ? 'line-through text-gray-400' : '' }}">{{ $subtask->title }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">No subtasks</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
@endsection
