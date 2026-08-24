@extends('frontend.admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="bg-gradient-to-r from-[#00E5C0] to-[#0066FF] rounded-2xl p-6 mb-6 text-white">
                <h2 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="opacity-90">
                    @if(Auth::user()->isSuperAdmin())
                        Super Admin Dashboard - Full Access
                    @elseif(Auth::user()->isAdmin())
                        Admin Dashboard - Management Access
                    @endif
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Total Users</p>
                            <p class="text-2xl font-bold text-white">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl filament-primary-bg flex items-center justify-center">
                            <svg class="w-6 h-6 filament-primary-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl border border-gray-700 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Your Role</p>
                            <p class="text-2xl font-bold text-white">
                                @foreach(Auth::user()->roles as $role)
                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                @endforeach
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl filament-info-bg flex items-center justify-center">
                            <svg class="w-6 h-6 filament-info-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl border border-gray-700 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Account Status</p>
                            <p class="text-2xl font-bold text-white">Active</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-green-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

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
                                        {{ $role->name === 'super-admin' ? 'bg-purple-900 text-purple-300' : 'bg-blue-900 text-blue-300' }}">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Quick Actions</h3>
                    </div>
                    <div class="space-y-2">
                        @if(auth()->user()->can('create-users'))
                        <a href="{{ route('admin.users.create') }}" class="w-full px-4 py-3 filament-primary-bg filament-primary-text rounded-lg font-medium transition hover:opacity-80 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add New User
                        </a>
                        @endif
                        @if(auth()->user()->can('view-users'))
                        <a href="{{ route('admin.users.index') }}" class="w-full px-4 py-3 filament-info-bg filament-info-text rounded-lg font-medium transition hover:opacity-80 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Manage Users
                        </a>
                        @endif
                        <button class="w-full px-4 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium transition hover:bg-gray-600 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Account Settings
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
