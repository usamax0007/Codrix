@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="mb-6">
                <a href="{{ route('admin.admins.index') }}" class="text-gray-400 hover:text-white mb-4 inline-block">
                    &larr; Back to Admins
                </a>
                <h1 class="text-2xl font-bold text-white">Edit Admin</h1>
            </div>

            <div class="bg-gray-800 rounded-lg p-6 max-w-2xl">
                <form action="{{ route('admin.admins.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Name</label>
                            <input type="text" name="name" required value="{{ $user->name }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Admin name">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" required value="{{ $user->email }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="admin@example.com">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Password (leave blank to keep current)</label>
                            <input type="password" name="password" minlength="8" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="New password (min 8 characters)">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 rounded-md bg-gray-700 text-gray-300 text-sm font-semibold hover:bg-gray-600 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-md filament-primary-bg filament-primary-text text-sm font-semibold hover:opacity-80 transition">
                            Update Admin
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
