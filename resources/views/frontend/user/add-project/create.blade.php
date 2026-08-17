@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Add Project</h1>
                <a href="{{ route('user.add-project.index') }}" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                    Back
                </a>
            </div>

            <div class="bg-gray-800 rounded-lg p-6">
                <form action="{{ route('user.add-project.store') }}" method="POST">
                    @csrf
                    @include('frontend.user.add-project.field.add')
                    <div class="mt-6">
                        <button type="submit" class="px-6 py-2 filament-primary-bg filament-primary-text rounded-lg hover:opacity-80 transition">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
