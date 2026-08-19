@extends('user.layout.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Task Statuses</h1>
                <p class="text-gray-400 text-sm">Manage workflow statuses.</p>
            </div>
            <a href="{{ route('tasks.index') }}"
               class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg text-sm transition">
                ← Back to Tasks
            </a>
        </div>

        <!-- Create Status Form -->
        <div class="bg-[#080D16] border border-gray-800 p-4 rounded-xl mb-6">
            <form action="{{ route('task-statuses.store') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Status Name</label>
                    <input type="text" name="name" required placeholder="e.g. In Review"
                           class="bg-[#03060B] border border-gray-800 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-[#00B8D9]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Badge Color</label>
                    <input type="color" name="color" value="#00B8D9"
                           class="bg-[#03060B] border border-gray-800 h-9 w-16 rounded-lg p-1 cursor-pointer">
                </div>
                <button type="submit"
                        class="bg-[#00B8D9] hover:bg-[#0092ad] text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm transition">
                    + Add Status
                </button>
            </form>
        </div>

        <!-- Statuses Table -->
        <div class="bg-[#080D16] border border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-[#03060B] text-gray-400 text-xs uppercase border-b border-gray-800">
                <tr>
                    <th class="p-3 w-10"></th>
                    <th class="p-3">#</th>
                    <th class="p-3">Status Name</th>
                    <th class="p-3">Color Badge</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody id="statusTableBody">
                @forelse($statuses as $status)
                    <tr data-id="{{ $status->id }}"
                        class="border-b border-gray-800 hover:bg-[#0b1320] cursor-move transition">
                        <td class="p-3 text-gray-500 hover:text-white cursor-grab">⋮⋮</td>
                        <td class="p-3 row-number">{{ $loop->iteration }}</td>
                        <td class="p-3 font-semibold text-white">{{ $status->name }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full"
                                      style="background-color: {{ $status->color }}"></span>
                                <code class="text-xs text-gray-400">{{ $status->color }}</code>
                            </div>
                        </td>
                        <td class="p-3 text-right flex justify-end gap-3 items-center">
                            <button type="button"
                                    onclick="openEditStatusModal('{{ $status->id }}', '{{ $status->name }}', '{{ $status->color }}')"
                                    class="text-[#00B8D9] hover:text-cyan-300 text-xs font-medium">
                                Edit
                            </button>

                            <form action="{{ route('task-statuses.destroy', $status->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this status?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">No task statuses found. Add one above!
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Edit Status Modal -->
    <div id="editStatusModal" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4">
        <div class="bg-[#0B1019] border border-gray-800 rounded-lg w-full max-w-md p-6 relative">
            <h3 class="text-lg font-bold text-white mb-4">Edit Task Status</h3>

            <form id="editStatusForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Status Name</label>
                        <input type="text" id="edit_status_name" name="name" required
                               class="w-full bg-[#090D14] border border-gray-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-[#00B8D9]">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Badge Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="edit_status_color_picker"
                                   class="w-10 h-9 bg-transparent border-0 cursor-pointer rounded">
                            <input type="text" id="edit_status_color" name="color" required
                                   class="w-full bg-[#090D14] border border-gray-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-[#00B8D9]">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditStatusModal()"
                            class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded text-xs">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-[#00B8D9] hover:bg-cyan-600 text-black font-semibold rounded text-xs">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openEditStatusModal(id, name, color) {
            const form = document.getElementById('editStatusForm');
            form.action = `/user/task-statuses/${id}`;

            document.getElementById('edit_status_name').value = name;
            document.getElementById('edit_status_color').value = color;
            document.getElementById('edit_status_color_picker').value = color;

            document.getElementById('editStatusModal').classList.remove('hidden');
        }

        function closeEditStatusModal() {
            document.getElementById('editStatusModal').classList.add('hidden');
        }

        document.getElementById('edit_status_color_picker').addEventListener('input', function (e) {
            document.getElementById('edit_status_color').value = e.target.value;
        });

        document.getElementById('edit_status_color').addEventListener('input', function (e) {
            document.getElementById('edit_status_color_picker').value = e.target.value;
        });


        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('statusTableBody');
            if (!el) return;

            Sortable.create(el, {
                animation: 150,
                ghostClass: 'bg-gray-800',
                onEnd: function () {
                    document.querySelectorAll('#statusTableBody tr').forEach((row, index) => {
                        const numCell = row.querySelector('.row-number');
                        if (numCell) numCell.textContent = index + 1;
                    });

                    const statusOrder = Array.from(el.children).map(row => row.getAttribute('data-id'));

                    fetch('/user/task-statuses/reorder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({order: statusOrder})
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                alert('Failed to reorder statuses.');
                            }
                        })
                        .catch(err => console.error('Error reordering statuses:', err));
                }
            });
        });
    </script>
@endsection