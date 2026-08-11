<x-user.layout title="Users">
    <div class="space-y-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Users</h1>
                <p class="mt-1 text-sm text-white/50">Create portal accounts for admins and team members.</p>
            </div>
            <x-user.button type="button" size="sm" id="open-user-modal">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </x-user.button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/10 bg-xc-card/60">
            <div class="border-b border-white/10 px-4 py-3 text-xs font-medium uppercase tracking-wider text-white/40 sm:px-5">
                {{ $users->total() }} user{{ $users->total() === 1 ? '' : 's' }}
            </div>

            <ul class="divide-y divide-white/5">
                @forelse ($users as $managedUser)
                    <li class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-white">{{ $managedUser->name }}</p>
                            <p class="truncate text-sm text-white/45">{{ $managedUser->email }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span @class([
                                'inline-flex rounded-md border px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide',
                                'border-red-400/30 bg-red-500/10 text-red-200' => $managedUser->isAdmin(),
                                'border-sky-400/30 bg-sky-500/10 text-sky-200' => $managedUser->isUser(),
                            ])>
                                {{ $managedUser->role?->getLabel() }}
                            </span>
                            <span class="text-xs text-white/35">
                                Joined {{ $managedUser->created_at?->format('M j, Y') }}
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-white/45">No users yet. Add your first account.</li>
                @endforelse
            </ul>

            @if ($users->hasPages())
                <div class="border-t border-white/10 px-4 py-3 sm:px-5">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <div
        id="user-modal"
        @class([
            'fixed inset-0 z-50 items-center justify-center p-4',
            'flex' => $errors->any(),
            'hidden' => ! $errors->any(),
        ])
        aria-hidden="{{ $errors->any() ? 'false' : 'true' }}"
    >
        <div id="user-modal-backdrop" class="absolute inset-0 bg-xc-darker/80 backdrop-blur-sm"></div>

        <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-white/[0.08] bg-xc-card shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4">
                <h2 class="text-lg font-semibold">Add User</h2>
                <button type="button" id="close-user-modal" class="rounded-lg p-2 text-white/40 hover:bg-white/5 hover:text-white" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('user.users.store') }}" class="space-y-4 px-5 py-4">
                @csrf

                <x-user.input label="Name" name="name" required autocomplete="name" />
                <x-user.input label="Email" name="email" type="email" required autocomplete="email" />

                <div class="space-y-1.5">
                    <label for="role" class="block text-sm font-medium text-white/80">
                        Role <span class="text-xc-cyan">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full rounded-lg border border-white/15 bg-xc-darker/80 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50"
                    >
                        @foreach ($roleOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', 'user') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-xs text-red-300">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-white/40">Admins and users both sign in to this portal.</p>
                </div>

                <x-user.input label="Password" name="password" type="password" required autocomplete="new-password" />
                <x-user.input label="Confirm password" name="password_confirmation" type="password" required autocomplete="new-password" />

                <div class="flex justify-end gap-2 border-t border-white/10 pt-4">
                    <x-user.button type="button" variant="outline" size="sm" id="cancel-user-modal">Cancel</x-user.button>
                    <x-user.button type="submit" size="sm">Create user</x-user.button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const modal = document.getElementById('user-modal');
                const openBtn = document.getElementById('open-user-modal');
                const closeBtn = document.getElementById('close-user-modal');
                const cancelBtn = document.getElementById('cancel-user-modal');
                const backdrop = document.getElementById('user-modal-backdrop');

                const openModal = () => {
                    modal?.classList.remove('hidden');
                    modal?.classList.add('flex');
                    modal?.setAttribute('aria-hidden', 'false');
                };

                const closeModal = () => {
                    modal?.classList.add('hidden');
                    modal?.classList.remove('flex');
                    modal?.setAttribute('aria-hidden', 'true');
                };

                openBtn?.addEventListener('click', openModal);
                closeBtn?.addEventListener('click', closeModal);
                cancelBtn?.addEventListener('click', closeModal);
                backdrop?.addEventListener('click', closeModal);
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') closeModal();
                });
            })();
        </script>
    @endpush
</x-user.layout>
