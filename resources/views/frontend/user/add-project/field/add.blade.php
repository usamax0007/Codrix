<div class="mb-4">
    <label class="block text-gray-300 mb-2">Name</label>
    <input type="text" name="name" value="{{ old('name', isset($project) ? $project->name : '') }}" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400" required>
    @error('name')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-300 mb-2">Description</label>
    <textarea name="description" rows="4" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400">{{ old('description', isset($project) ? $project->description : '') }}</textarea>
    @error('description')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="mb-4">
    <label class="block text-gray-300 mb-2">Due Date</label>
    <input type="date" name="due_date" value="{{ old('due_date', isset($project) ? $project->due_date?->format('Y-m-d') : '') }}" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400">
    @error('due_date')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-300 mb-2">End Date</label>
    <input type="date" name="end_date" value="{{ old('end_date', isset($project) ? $project->end_date?->format('Y-m-d') : '') }}" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400">
    @error('end_date')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
</div>