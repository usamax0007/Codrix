<div class="mb-4">
    <label class="block text-gray-300 mb-2">Project</label>
    <select name="project_id" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400">
        <option value="">Select Project</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ old('project_id', isset($task) ? $task->project_id : '') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
        @endforeach
    </select>
    @error('project_id')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-300 mb-2">Summary</label>
    <input type="text" name="summary" value="{{ old('summary', isset($task) ? $task->summary : '') }}" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400" required>
    @error('summary')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-300 mb-2">Description</label>
    <textarea name="description" rows="4" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400">{{ old('description', isset($task) ? $task->description : '') }}</textarea>
    @error('description')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-300 mb-2">Attachment</label>
    <input type="file" name="attachment" id="attachment" class="hidden" onchange="showFileName(this)">
    <label for="attachment" class="flex items-center justify-center h-24 w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-300 hover:border-teal-400 hover:text-teal-400 cursor-pointer transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none">
            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span id="fileName" class="ml-2 text-sm"></span>
    </label>
</div>

<div class="mb-4">
    <label class="block text-gray-300 mb-2">Assignees</label>
    <select name="assignee_id" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400">
        <option value="">Select Assignee</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('assignee_id', isset($task) ? $task->assignee_id : '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
        @endforeach
    </select>
    @error('assignee_id')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="mb-4">
        <label class="block text-gray-300 mb-2">Priority</label>
        <select name="priority" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400" required>
            <option value="low" {{ old('priority', isset($task) ? $task->priority : '') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ old('priority', isset($task) ? $task->priority : 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ old('priority', isset($task) ? $task->priority : '') == 'high' ? 'selected' : '' }}>High</option>
        </select>
        @error('priority')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block text-gray-300 mb-2">Status</label>
        <select name="status" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400" required>
            <option value="">Select Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status->name }}" {{ old('status', isset($task) ? $task->status : '') == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
            @endforeach
        </select>
        @error('status')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block text-gray-300 mb-2">Due Date</label>
        <input type="date" name="due_date" value="{{ old('due_date', isset($task) ? $task->due_date?->format('Y-m-d') : '') }}" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400">
        @error('due_date')
            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
