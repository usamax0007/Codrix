@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <h1 class="text-2xl font-bold text-white mb-6">Profile</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg filament-primary-bg flex items-center justify-center">
                            <svg class="w-5 h-5 filament-primary-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Account Information</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-700">
                            <span class="text-sm text-gray-400">Full Name</span>
                            <span class="text-sm font-medium text-white">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-700">
                            <span class="text-sm text-gray-400">Email Address</span>
                            <span class="text-sm font-medium text-white">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-700">
                            <span class="text-sm text-gray-400">Role</span>
                            <div class="flex space-x-2">
                                @foreach(Auth::user()->roles as $role)
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $role->name === 'super-admin' ? 'bg-purple-900 text-purple-300' : 
                                           ($role->name === 'admin' ? 'bg-blue-900 text-blue-300' : 'bg-gray-700 text-gray-300') }}">
                                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-400">User ID</span>
                            <span class="text-sm font-medium text-white">#{{ Auth::id() }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg filament-info-bg flex items-center justify-center">
                            <svg class="w-5 h-5 filament-info-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Change Password</h3>
                    </div>
                    <form action="#" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Current Password</label>
                                <input type="password" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Enter current password">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">New Password</label>
                                <input type="password" minlength="8" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Enter new password (min 8 characters)">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Confirm New Password</label>
                                <input type="password" minlength="8" class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 transition" placeholder="Confirm new password">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg font-medium transition hover:opacity-80">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
