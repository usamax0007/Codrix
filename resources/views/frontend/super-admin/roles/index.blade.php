@extends('frontend.super-admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Roles</h1>
                @if(auth()->user()->can('create-roles'))
                <a href="{{ route('super-admin.roles.create') }}" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                    New Role
                </a>
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

            <div class="mb-4">
                <input type="text" placeholder="Search roles..." class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white">Role Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white">Permissions</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white">Users</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($roles as $role)
                        <tr class="hover:bg-gray-750">
                            <td class="px-6 py-4">
                                <span class="text-white font-medium">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($role->permissions->count() > 0)
                                        @foreach($role->permissions->take(5) as $permission)
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-700 text-gray-300">
                                            {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                        </span>
                                        @endforeach
                                        @if($role->permissions->count() > 5)
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-600 text-gray-400">
                                            +{{ $role->permissions->count() - 5 }} more
                                        </span>
                                        @endif
                                    @else
                                        <span class="text-gray-500 text-sm">No permissions</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-300">{{ $role->users->count() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    @if(auth()->user()->can('edit-roles'))
                                    <a href="{{ route('super-admin.roles.edit', $role) }}" class="text-green-400 hover:text-green-300" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"/>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                        </svg>
                                    </a>
                                    @endif
                                    @if(auth()->user()->can('delete-roles') && !in_array($role->name, ['super-admin', 'admin', 'user']))
                                    <form action="{{ route('super-admin.roles.destroy', $role) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" title="Delete" onclick="return confirm('Are you sure you want to delete this role?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection
