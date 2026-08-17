@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Task Status</h1>
                <a href="{{ route('user.task.index') }}" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                    Back to Tasks
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-gray-800 rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Summary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Assignee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-750">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                    @if($task->project)
                                        {{ $task->project->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-300">{{ $task->summary }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ Str::limit($task->description, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block text-xs font-semibold {{ $task->priority == 'high' ? 'text-red-400 bg-red-400/10 border-red-400/30' : ($task->priority == 'medium' ? 'text-yellow-400 bg-yellow-400/10 border-yellow-400/30' : 'text-green-400 bg-green-400/10 border-green-400/30') }} border rounded px-2 py-0.5">{{ strtoupper($task->priority) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                    @if($task->assignee)
                                        {{ $task->assignee->name }}
                                    @else
                                        Unassigned
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $task->due_date?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('user.task.show', $task) }}" class="text-blue-400 hover:text-blue-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('user.task.edit', $task) }}" class="text-green-400 hover:text-green-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('user.task.destroy', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this task?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"/>
                                                    <path d="M8 6V4h8v2"/>
                                                    <path d="M19 6l-1 14H6L5 6"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-400">No tasks found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection
