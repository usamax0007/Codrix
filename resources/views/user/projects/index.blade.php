@extends('user.layout.app')

@section('content')
    <div class="space-y-6">

        <!-- Top Bar -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Projects</h1>
                <p class="text-gray-400 text-sm">Manage all your projects here</p>
            </div>
            <button id="openProjectModal"
                    class="bg-emerald-500 hover:bg-emerald-600 text-gray-900 font-semibold px-4 py-2 rounded-lg transition">
                + Add Project
            </button>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 p-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($projects as $project)
                <div class="bg-gray-800 border border-gray-700 hover:border-emerald-400 rounded-xl p-5 relative group">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-emerald-400">{{ $project->name }}</h3>

                        <!-- Action Buttons (Edit & Delete) -->
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    data-project='@json($project)'
                                    onclick="openEditProjectModal(this)"
                                    class="text-gray-400 hover:text-emerald-400 p-1 transition flex items-center justify-center"
                                    title="Edit Project">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                </svg>
                            </button>

                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this project?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-400 text-sm p-1 transition"
                                        title="Delete">✕
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $project->description ?? 'No description provided.' }}</p>

                    <div class="flex justify-between text-xs text-gray-500 border-t border-gray-700/50 pt-3">
                        <span>Start: {{ $project->start_date ?? 'N/A' }}</span>
                        <span>Due: {{ $project->due_date ?? 'N/A' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-gray-800 border border-gray-700 rounded-xl p-8 text-center text-gray-400">
                    No projects found. Click "+ Add Project" to create one.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal: Add Project -->
    <div id="projectModal" class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4 hidden">
        <div class="bg-gray-800 border border-gray-700 rounded-xl w-full max-w-md p-6 relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Add Project</h2>
                <button type="button" id="closeProjectModal" class="text-gray-400 hover:text-white text-xl">&times;
                </button>
            </div>

            <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Name <span
                                class="text-emerald-400">*</span></label>
                    <input type="text" name="name" required
                           class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white focus:border-emerald-500 focus:outline-none"
                           placeholder="Project name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white focus:border-emerald-500 focus:outline-none"
                              placeholder="What is this project about?"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Start date</label>
                        <input type="date" name="start_date"
                               class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white [color-scheme:dark] focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Due date</label>
                        <input type="date" name="due_date"
                               class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white [color-scheme:dark] focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" id="cancelProjectModal"
                            class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition">Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-emerald-500 text-gray-900 font-semibold hover:bg-emerald-600 transition">
                        Save Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Project -->
    <div id="editProjectModal" class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4 hidden">
        <div class="bg-gray-800 border border-gray-700 rounded-xl w-full max-w-md p-6 relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Edit Project</h2>
                <button type="button" onclick="closeEditProjectModal()" class="text-gray-400 hover:text-white text-xl">
                    &times;
                </button>
            </div>

            <form id="editProjectForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Name <span
                                class="text-emerald-400">*</span></label>
                    <input type="text" name="name" id="edit_name" required
                           class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white focus:border-emerald-500 focus:outline-none"
                           placeholder="Project name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3"
                              class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white focus:border-emerald-500 focus:outline-none"
                              placeholder="What is this project about?"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Start date</label>
                        <input type="date" name="start_date" id="edit_start_date"
                               class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white [color-scheme:dark] focus:border-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Due date</label>
                        <input type="date" name="due_date" id="edit_due_date"
                               class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white [color-scheme:dark] focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditProjectModal()"
                            class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition">Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-emerald-500 text-gray-900 font-semibold hover:bg-emerald-600 transition">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add Project Modal Toggle //
        let modal = document.getElementById('projectModal');
        let openBtn = document.getElementById('openProjectModal');
        let closeBtn = document.getElementById('closeProjectModal');
        let cancelBtn = document.getElementById('cancelProjectModal');

        if (openBtn) openBtn.onclick = () => modal.classList.remove('hidden');
        if (closeBtn) closeBtn.onclick = () => modal.classList.add('hidden');
        if (cancelBtn) cancelBtn.onclick = () => modal.classList.add('hidden');

        // Edit Project Modal Logic //
        function openEditProjectModal(element) {
            try {
                const project = JSON.parse(element.getAttribute('data-project'));

                document.getElementById('editProjectForm').action = `/user/projects/${project.id}`;

                document.getElementById('edit_name').value = project.name || '';
                document.getElementById('edit_description').value = project.description || '';
                document.getElementById('edit_start_date').value = project.start_date || '';
                document.getElementById('edit_due_date').value = project.due_date || '';

                // Show Modal
                document.getElementById('editProjectModal').classList.remove('hidden');
            } catch (e) {
                console.error("Error opening edit modal:", e);
            }
        }

        function closeEditProjectModal() {
            document.getElementById('editProjectModal').classList.add('hidden');
        }
    </script>
@endsection