@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Edit Task</h1>
                <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                    Back
                </a>
            </div>

            <div class="bg-gray-800 rounded-lg p-6">
                <form action="{{ route('admin.tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Project</label>
                            <select name="project_id" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Summary</label>
                            <input type="text" name="summary" value="{{ old('summary', $task->summary) }}" required class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                            @error('summary')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400 resize-none">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Attachment</label>
                            <input type="file" name="attachment" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                            @if($task->attachment)
                                <p class="text-gray-400 text-sm mt-1">Current: {{ $task->attachment }}</p>
                            @endif
                            @error('attachment')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Priority</label>
                            <select name="priority" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                                <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Status</label>
                            <select name="status" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->name }}" {{ old('status', $task->status) == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Assignee</label>
                            <select name="assignee_id" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assignee_id', $task->assignee_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assignee_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Due Date</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                            @error('due_date')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                            Update Task
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
