@extends('frontend.super-admin.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Permissions</h1>
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

            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white">Permission Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white">Roles</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($permissions as $permission)
                        <tr class="hover:bg-gray-750">
                            <td class="px-6 py-4">
                                <span class="text-white font-medium">{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($permission->roles->count() > 0)
                                        @foreach($permission->roles as $role)
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-700 text-gray-300">
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500 text-sm">No roles assigned</span>
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
