@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Task Status</h1>
                <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                    Back to Tasks
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-white mb-4">Add New Status</h2>
                <form action="{{ route('admin.task-status.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Status Name</label>
                            <input type="text" name="name" class="w-full px-4 py-2 bg-gray-900 border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:border-teal-400" required>
                            @error('name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-300 mb-2">Color</label>
                            <input type="color" name="color" value="#6B7280" class="w-full h-10 px-4 py-2 bg-gray-900 border-gray-600 rounded-lg focus:outline-none focus:border-teal-400" required>
                            @error('color')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition font-semibold">
                                Add Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-gray-800 rounded-lg overflow-hidden overflow-x-auto scrollbar-hide">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Color</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tasks Count</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700" id="statusTableBody">
                        @forelse($statuses as $status)
                            <tr class="hover:bg-gray-750 cursor-move status-row" data-id="{{ $status->id }}" draggable="true">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-2 text-gray-500">
                                        <circle cx="9" cy="12" r="1"/>
                                        <circle cx="9" cy="5" r="1"/>
                                        <circle cx="9" cy="19" r="1"/>
                                        <circle cx="15" cy="12" r="1"/>
                                        <circle cx="15" cy="5" r="1"/>
                                        <circle cx="15" cy="19" r="1"/>
                                    </svg>
                                    {{ $status->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block w-6 h-6 rounded-full" style="background-color: {{ $status->color }}"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $status->tasks->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.task-status.destroy', $status) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this status?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4h8v2"/>
                                                <path d="M19 6l-1 14H6L5 6"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-400">No statuses found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const tableBody = document.getElementById('statusTableBody');
        let draggedRow = null;

        tableBody.addEventListener('dragstart', function(e) {
            draggedRow = e.target.closest('tr');
            e.target.style.opacity = '0.4';
        });

        tableBody.addEventListener('dragend', function(e) {
            e.target.style.opacity = '';
            draggedRow = null;
        });

        tableBody.addEventListener('dragover', function(e) {
            e.preventDefault();
            const afterElement = getDragAfterElement(tableBody, e.clientY);
            if (afterElement == null) {
                tableBody.appendChild(draggedRow);
            } else {
                tableBody.insertBefore(draggedRow, afterElement);
            }
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.status-row:not(.dragging)')];

            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        tableBody.addEventListener('dragend', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const newOrder = [];
            document.querySelectorAll('.status-row').forEach((row, index) => {
                newOrder.push({
                    id: row.getAttribute('data-id'),
                    position: index + 1
                });
            });

            fetch('{{ route("admin.task-status.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ order: newOrder })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Status reordered successfully');
                }
            })
            .catch(error => {
                console.error('Error reordering status:', error);
            });
        });
    </script>
@endsection
