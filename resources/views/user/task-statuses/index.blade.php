@extends('user.layout.app')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Task Statuses</h1>
                <p class="text-gray-400 text-sm">Manage workflow statuses.</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg text-sm transition">
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
                <button type="submit" class="bg-[#00B8D9] hover:bg-[#0092ad] text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm transition">
                    + Add Status
                </button>
            </form>
        </div>

        <!-- Statuses Table -->
        <div class="bg-[#080D16] border border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-[#03060B] text-gray-400 text-xs uppercase border-b border-gray-800">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Status Name</th>
                    <th class="p-3">Color Badge</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($statuses as $status)
                    <tr class="border-b border-gray-800 hover:bg-[#0b1320]">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3 font-semibold text-white">{{ $status->name }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $status->color }}"></span>
                                <code class="text-xs text-gray-400">{{ $status->color }}</code>
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <form action="{{ route('task-statuses.destroy', $status->id) }}" method="POST" onsubmit="return confirm('Delete this status?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">No task statuses found. Add one above!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection