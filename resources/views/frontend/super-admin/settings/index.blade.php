@extends('frontend.super-admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <h1 class="text-2xl font-bold text-white mb-6">Settings</h1>

            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-white mb-4">System Settings</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-white">Site Name</p>
                            <p class="text-xs text-gray-400">The name of your application</p>
                        </div>
                        <input type="text" value="Codrix" class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm w-48 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
                <div class="mt-6">
                    <button class="px-4 py-2 filament-primary-bg filament-primary-text rounded-lg font-medium transition hover:opacity-80">
                        Save Settings
                    </button>
                </div>
            </div>
        </main>
    </div>
@endsection
