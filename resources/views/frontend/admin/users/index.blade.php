@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Manage Users</h1>
                @if(auth()->user()->can('create-users'))
                <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                    Add User
                </button>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-700 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-900 border border-red-700 text-red-300 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-gray-800 rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-750">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach($user->roles as $role)
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $role->name === 'super-admin' ? 'bg-purple-900 text-purple-300' : 
                                               ($role->name === 'admin' ? 'bg-blue-900 text-blue-300' : 'bg-gray-700 text-gray-300') }}">
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        @if(auth()->user()->can('edit-users') && auth()->user()->canAssignRoleTo($user))
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-green-400 hover:text-green-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                            </svg>
                                        </a>
                                        @endif
                                        @if(auth()->user()->can('delete-users') && auth()->user()->canAssignRoleTo($user) && $user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this user?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"/>
                                                    <path d="M8 6V4h8v2"/>
                                                    <path d="M19 6l-1 14H6L5 6"/>
                                                    <path d="M10 11v5"/>
                                                    <path d="M14 11v5"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-400">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add User Modal -->
    @if(auth()->user()->can('create-users'))
    <div id="addUserModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto scrollbar-hide">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white">Add New User</h2>
                <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Name</label>
                        <input type="text" name="name" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="User name">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="user@example.com">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Password</label>
                        <input type="password" name="password" required minlength="8" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Password (min 8 characters)">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Role</label>
                        <select name="role" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                            <option value="">Select a role</option>
                            @foreach($assignableRoles as $role)
                                <option value="{{ $role }}">{{ ucfirst(str_replace('-', ' ', $role)) }}</option>
                            @endforeach
                        </select>
                        @if(auth()->user()->isSuperAdmin())
                            <p class="text-xs text-gray-500 mt-1">As Super Admin, you can assign Admin and User roles</p>
                        @elseif(auth()->user()->isAdmin())
                            <p class="text-xs text-gray-500 mt-1">As Admin, you can only assign User role</p>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endsection
