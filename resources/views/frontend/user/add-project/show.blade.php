@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Project Details</h1>
                <a href="{{ route('user.add-project.index') }}" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                    Back
                </a>
            </div>

            <div class="bg-gray-800 rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Name</label>
                        <p class="text-gray-200 text-lg">{{ $project->name }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Due Date</label>
                        <p class="text-gray-200 text-lg">{{ $project->due_date?->format('M d, Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">End Date</label>
                        <p class="text-gray-200 text-lg">{{ $project->end_date?->format('M d, Y') ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-400 text-sm mb-2">Description</label>
                        <p class="text-gray-200 text-lg whitespace-pre-wrap">{{ $project->description ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('user.add-project.edit', $project) }}" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                        Edit
                    </a>
                    <form action="{{ route('user.add-project.destroy', $project) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-900 text-red-400 rounded-lg hover:bg-red-800 transition" onclick="return confirm('Are you sure you want to delete this project?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
