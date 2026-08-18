@if (session('success'))
    <div class="mb-6">
        <x-user.alert type="success">{{ session('success') }}</x-user.alert>
    </div>
@endif

@if (session('error'))
    <div class="mb-6">
        <x-user.alert type="error">{{ session('error') }}</x-user.alert>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6">
        <x-user.alert type="error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-user.alert>
    </div>
@endif
