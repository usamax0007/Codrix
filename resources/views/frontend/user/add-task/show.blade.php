@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Task Details</h1>
                <div class="flex gap-3">
                    <a href="{{ route('user.task.index') }}" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                        Back
                    </a>
                    <a href="{{ route('user.task.edit', $task) }}" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                        Edit
                    </a>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Task Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Project</label>
                                <p class="text-gray-300">
                                    @if($task->project)
                                        {{ $task->project->name }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Summary</label>
                                <p class="text-gray-300">{{ $task->summary }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Description</label>
                                <p class="text-gray-300">{{ $task->description ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Priority</label>
                                <span class="inline-block text-xs font-semibold {{ $task->priority == 'high' ? 'text-red-400 bg-red-400/10 border-red-400/30' : ($task->priority == 'medium' ? 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30' : 'text-green-400 bg-green-400/10 border-green-400/30') }} border rounded px-2 py-0.5">{{ strtoupper($task->priority) }}</span>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Status</label>
                                <p class="text-gray-300">{{ str_replace('_', ' ', ucfirst($task->status)) }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Due Date</label>
                                <p class="text-gray-300">{{ $task->due_date?->format('M d, Y') ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Assignee</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Assigned To</label>
                                <p class="text-gray-300">
                                    @if($task->assignee)
                                        {{ $task->assignee->name }}
                                    @else
                                        Unassigned
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
