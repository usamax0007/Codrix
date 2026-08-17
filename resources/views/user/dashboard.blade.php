@extends('user.layout.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gray-800 border border-gray-700 p-5 rounded-lg shadow-sm">
            <p class="text-gray-400 text-sm">Total Users</p>
            <h2 class="text-3xl font-bold mt-2 text-white">120</h2>
        </div>

        <div class="bg-gray-800 border border-gray-700 p-5 rounded-lg shadow-sm">
            <p class="text-gray-400 text-sm">Active Services</p>
            <h2 class="text-3xl font-bold mt-2 text-white">15</h2>
        </div>

        <div class="bg-gray-800 border border-gray-700 p-5 rounded-lg shadow-sm">
            <p class="text-gray-400 text-sm">Pending Requests</p>
            <h2 class="text-3xl font-bold mt-2 text-white">4</h2>
        </div>
    </div>

    <div class="bg-gray-800 border border-gray-700 p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-white mb-2">Welcome to your Dashboard</h3>
    </div>
@endsection