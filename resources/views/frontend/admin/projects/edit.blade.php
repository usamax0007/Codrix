@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Edit Project</h1>
                <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                    Back
                </a>
            </div>

            <div class="bg-gray-800 rounded-lg p-6">
                <form action="{{ route('admin.projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name', $project->name) }}" required class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                            @error('name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400 resize-none">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">Start Date</label>
                                <input type="date" name="due_date" value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                                @error('due_date')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}" class="w-full bg-gray-900 border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:border-teal-400">
                                @error('end_date')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                            Update Project
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
