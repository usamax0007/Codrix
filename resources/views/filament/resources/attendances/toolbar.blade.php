<div style="display: flex; width: 100%; align-items: flex-end; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap;">
    <form wire:submit="saveSettings" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 0.75rem; min-width: 0; flex: 1;">
        <div style="min-width: 16rem; flex: 1;">
            <div style="display: block; font-size: 0.75rem; margin-bottom: 0.35rem; color: rgba(156, 163, 175, 1);">
                Working Days
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                @foreach ($this->weekdayOptions as $value => $label)
                    <label style="display: inline-flex; align-items: center; gap: 0.35rem; border: 1px solid rgba(255,255,255,0.12); border-radius: 0.5rem; padding: 0.35rem 0.55rem; font-size: 0.75rem; cursor: pointer;">
                        <input
                            type="checkbox"
                            value="{{ $value }}"
                            wire:model="workingDays"
                            style="border-radius: 0.25rem;"
                        >
                        <span>{{ str($label)->substr(0, 3) }}</span>
                    </label>
                @endforeach
            </div>
            @error('workingDays')
                <p style="margin-top: 0.25rem; font-size: 0.75rem; color: rgb(248, 113, 113);">{{ $message }}</p>
            @enderror
        </div>

        <div style="width: 8.5rem;">
            <label for="attendance-work-from" style="display: block; font-size: 0.75rem; margin-bottom: 0.25rem; color: rgba(156, 163, 175, 1);">
                From
            </label>
            <x-filament::input.wrapper>
                <x-filament::input
                    id="attendance-work-from"
                    type="time"
                    wire:model="workFrom"
                />
            </x-filament::input.wrapper>
            @error('workFrom')
                <p style="margin-top: 0.25rem; font-size: 0.75rem; color: rgb(248, 113, 113);">{{ $message }}</p>
            @enderror
        </div>

        <div style="width: 8.5rem;">
            <label for="attendance-work-to" style="display: block; font-size: 0.75rem; margin-bottom: 0.25rem; color: rgba(156, 163, 175, 1);">
                To
            </label>
            <x-filament::input.wrapper>
                <x-filament::input
                    id="attendance-work-to"
                    type="time"
                    wire:model="workTo"
                />
            </x-filament::input.wrapper>
            @error('workTo')
                <p style="margin-top: 0.25rem; font-size: 0.75rem; color: rgb(248, 113, 113);">{{ $message }}</p>
            @enderror
        </div>

        <x-filament::button type="submit" size="sm">
            Save
        </x-filament::button>
    </form>
</div>
