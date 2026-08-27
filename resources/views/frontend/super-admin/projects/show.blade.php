@extends('frontend.super-admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">{{ $project->name }}</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('super-admin.projects.edit', $project) }}" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                        Edit Project
                    </a>
                    <a href="{{ route('super-admin.projects.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                        Back to Projects
                    </a>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                        <p class="text-white">{{ $project->description }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                        <span class="px-3 py-1 rounded-full text-sm {{ $project->status === 'active' ? 'bg-green-900 text-green-300' : ($project->status === 'completed' ? 'bg-blue-900 text-blue-300' : 'bg-yellow-900 text-yellow-300') }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Created At</label>
                        <p class="text-white">{{ $project->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Last Updated</label>
                        <p class="text-white">{{ $project->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
