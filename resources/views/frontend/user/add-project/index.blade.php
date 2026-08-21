@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Projects</h1>
                <button onclick="document.getElementById('addProjectModal').classList.remove('hidden')" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                    Add Project
                </button>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($projects as $project)
                            <tr class="hover:bg-gray-750">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $project->name }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ Str::limit($project->description, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $project->due_date?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $project->end_date?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('user.add-project.show', $project) }}" class="text-blue-400 hover:text-blue-300">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('user.add-project.edit', $project) }}" class="text-green-400 hover:text-green-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('user.add-project.destroy', $project) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this project?')">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="24"
                                                     height="24"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M3 6h18"/>
                                                    <path d="M8 6V4h8v2"/>
                                                    <path d="M19 6l-1 14H6L5 6"/>
                                                    <path d="M10 11v5"/>
                                                    <path d="M14 11v5"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-400">No projects found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add Project Modal -->
    <div id="addProjectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto scrollbar-hide">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white">Add New Project</h2>
                <button onclick="document.getElementById('addProjectModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form id="addProjectForm" onsubmit="addProject(event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Name</label>
                        <input type="text" name="name" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Project name">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition resize-none" placeholder="Project description"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Start Date</label>
                            <input type="date" name="due_date" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">End Date</label>
                            <input type="date" name="end_date" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('addProjectModal').classList.add('hidden')" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                        Add Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addProject(event) {
            event.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const formData = new FormData(event.target);
            
            fetch('{{ route('user.add-project.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error adding project:', error);
            });
        }
    </script>
@endsection
