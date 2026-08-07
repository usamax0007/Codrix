<div style="display: flex; width: 100%; justify-content: flex-end; padding: 0.875rem 1rem;">
    <div style="width: 12rem; max-width: 12rem;">
        <x-filament::input.wrapper>
            <x-filament::input.select
                id="attendance-user-filter"
                wire:model.live="userId"
                aria-label="Select user"
            >
                <option value="">Select a user</option>
                @foreach ($livewire->staffUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </x-filament::input.select>
        </x-filament::input.wrapper>
    </div>
</div>
