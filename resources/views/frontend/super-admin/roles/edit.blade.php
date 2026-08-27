@extends('frontend.super-admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white">Edit Role</h1>
                <p class="text-gray-400 mt-1">Manage permissions for {{ ucfirst(str_replace('-', ' ', $role->name)) }}</p>
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

            <form action="{{ route('super-admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-6">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Role Name</label>
                        <input type="text" name="name" value="{{ $role->name }}" 
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                            @if(in_array($role->name, ['super-admin', 'admin', 'user'])) disabled @endif>
                        @if(in_array($role->name, ['super-admin', 'admin', 'user']))
                            <p class="text-gray-500 text-sm mt-1">Default role names cannot be changed</p>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Permissions</h2>
                    
                    @foreach($permissions as $category => $categoryPermissions)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-300 mb-3 uppercase tracking-wide">{{ ucfirst($category) }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($categoryPermissions as $permission)
                                    <label class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 cursor-pointer transition">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                            {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}
                                            class="w-4 h-4 text-green-500 rounded border-gray-500 focus:ring-green-500 focus:ring-offset-gray-800">
                                        <span class="text-gray-300 text-sm">{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('super-admin.roles.index') }}" class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </main>
    </div>
@endsection
