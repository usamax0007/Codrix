<x-user.layout title="Projects" :wide="true">
    <div class="space-y-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Projects</h1>
                <p class="mt-1 text-sm text-white/50">Group tasks by project and track completion progress.</p>
            </div>
            @if ($canManage)
                <x-user.button type="button" size="sm" id="open-project-modal">Add Project</x-user.button>
            @endif
        </div>

        <x-user.flash />

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($projects as $project)
                @php $progress = $project->progressStats(); @endphp
                <a
                    href="{{ route('user.projects.show', $project) }}"
                    class="rounded-2xl border border-white/10 bg-xc-card/60 p-5 transition hover:border-xc-cyan/30"
                >
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-lg font-semibold tracking-tight text-white">{{ $project->name }}</h2>
                        <span class="shrink-0 text-xs text-white/40">{{ $progress['percent'] }}%</span>
                    </div>

                    @if ($project->description)
                        <p class="mt-2 line-clamp-2 text-sm text-white/45">{{ $project->description }}</p>
                    @endif

                    <div class="mt-4">
                        <div class="h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-gradient-to-r from-xc-cyan to-xc-blue" style="width: {{ $progress['percent'] }}%"></div>
                        </div>
                        <p class="mt-2 text-xs text-white/40">
                            {{ $progress['completed'] }} completed · {{ $progress['remaining'] }} remaining · {{ $progress['total'] }} total
                        </p>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-3 text-[11px] text-white/40">
                        @if ($project->start_date)
                            <span>Start {{ $project->start_date->format('M j, Y') }}</span>
                        @endif
                        @if ($project->due_date)
                            <span>Due {{ $project->due_date->format('M j, Y') }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-white/15 px-6 py-16 text-center text-sm text-white/45">
                    No projects yet.
                    @if ($canManage)
                        Create your first project to organize tasks.
                    @else
                        Projects appear here once you have assigned tasks.
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    @if ($canManage)
        <div id="project-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-hidden="true">
            <div id="project-modal-backdrop" class="absolute inset-0 bg-xc-darker/80 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full max-w-lg overflow-hidden rounded-2xl border border-white/[0.08] bg-xc-card shadow-2xl">
                <div class="flex items-center justify-between border-b border-white/10 px-5 py-4">
                    <h2 class="text-lg font-semibold">Add Project</h2>
                    <button type="button" id="close-project-modal" class="rounded-lg p-2 text-white/40 hover:bg-white/5 hover:text-white" aria-label="Close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('user.projects.store') }}" class="space-y-4 px-5 py-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="project_name" class="block text-sm font-medium text-white/80">Name <span class="text-xc-cyan">*</span></label>
                        <input id="project_name" name="name" required maxlength="255" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40" placeholder="Project name">
                    </div>
                    <div class="space-y-1.5">
                        <label for="project_description" class="block text-sm font-medium text-white/80">Description</label>
                        <textarea id="project_description" name="description" rows="3" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40" placeholder="What is this project about?"></textarea>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1.5">
                            <label for="project_start_date" class="block text-sm font-medium text-white/80">Start date</label>
                            <input id="project_start_date" name="start_date" type="date" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40" style="color-scheme: dark;">
                        </div>
                        <div class="space-y-1.5">
                            <label for="project_due_date" class="block text-sm font-medium text-white/80">Due date</label>
                            <input id="project_due_date" name="due_date" type="date" class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-xc-cyan/40" style="color-scheme: dark;">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-white/10 pt-4">
                        <x-user.button type="button" variant="outline" size="sm" id="cancel-project-modal">Cancel</x-user.button>
                        <x-user.button type="submit" size="sm">Create Project</x-user.button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
            <script>
                (function () {
                    const modal = document.getElementById('project-modal');
                    const open = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
                    const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
                    document.getElementById('open-project-modal')?.addEventListener('click', open);
                    document.getElementById('close-project-modal')?.addEventListener('click', close);
                    document.getElementById('cancel-project-modal')?.addEventListener('click', close);
                    document.getElementById('project-modal-backdrop')?.addEventListener('click', close);
                })();
            </script>
        @endpush
    @endif
</x-user.layout>
