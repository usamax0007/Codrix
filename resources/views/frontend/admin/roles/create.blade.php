@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="mb-6">
                <a href="{{ route('admin.roles.index') }}" class="text-gray-400 hover:text-white mb-4 inline-block">
                    &larr; Back to Roles
                </a>
                <h1 class="text-2xl font-bold text-white">Create Role</h1>
            </div>

            <div class="bg-gray-800 rounded-lg p-6 max-w-4xl">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Role Name</label>
                            <input type="text" name="name" required class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="e.g. manager">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-3">Permissions</label>
                            <div class="space-y-4">
                                @foreach($permissions as $category => $categoryPermissions)
                                <div class="bg-gray-700 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-white mb-3 capitalize">{{ $category }}</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($categoryPermissions as $permission)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="w-4 h-4 rounded border-gray-600 bg-gray-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-gray-800">
                                            <span class="text-sm text-gray-300">{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                            Create Role
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
