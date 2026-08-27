@extends('frontend.super-admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Edit Project</h1>
                <a href="{{ route('super-admin.projects.index') }}" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                    Back to Projects
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <form action="{{ route('super-admin.projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Project Name</label>
                            <input type="text" name="name" value="{{ $project->name }}" required class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $project->description }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on-hold" {{ $project->status === 'on-hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                            Update Project
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
