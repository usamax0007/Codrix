@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="mb-6">
                <a href="{{ route('user.users.index') }}" class="text-gray-400 hover:text-white flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to Users
                </a>
                <h1 class="text-2xl font-bold text-white">Add New User</h1>
            </div>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-900 border border-red-700 text-red-300 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 max-w-2xl">
                <form action="{{ route('user.users.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="User name">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="user@example.com">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Password</label>
                            <input type="password" name="password" required minlength="8" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Password (min 8 characters)">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Role</label>
                            <select name="role" required class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:border-emerald-500 transition">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                    </option>
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
                        <a href="{{ route('user.users.index') }}" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
