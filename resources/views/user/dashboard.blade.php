<x-user.layout title="Dashboard" :wide="true">
    @php
        $isAdmin = $user->isAdmin();
        $greeting = now()->hour < 12 ? 'Good morning' : (now()->hour < 18 ? 'Good afternoon' : 'Good evening');
    @endphp
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm text-white/45">{{ $greeting }}</p>
                <h1 class="mt-1 text-2xl sm:text-3xl font-semibold tracking-tight">
                    {{ $user->name }}
                </h1>
                <p class="mt-1 text-sm text-white/50">
                    @if ($isAdmin)
                        Team workspace overview — projects, tasks, and progress at a glance.
                    @else
                        Your assigned work, deadlines, and attendance in one place.
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                @can(\App\Support\AppPermission::TASKS_ACCESS)
                    <x-user.button href="{{ route('user.tasks.index') }}" size="sm" variant="outline">Open board</x-user.button>
                @endcan
                @can(\App\Support\AppPermission::PROJECTS_ACCESS)
                    <x-user.button href="{{ route('user.projects.index') }}" size="sm" variant="outline">Projects</x-user.button>
                @endcan
                @can(\App\Support\AppPermission::ATTENDANCE_ACCESS)
                    <x-user.button href="{{ route('user.attendance.index') }}" size="sm" variant="outline">Attendance</x-user.button>
                @endcan
            </div>
        </div>

        @if ($taskStats['can_access_tasks'] || $taskStats['can_access_projects'])
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
                @if ($taskStats['can_access_tasks'])
                    <div class="rounded-2xl border border-white/10 bg-xc-card/70 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">Total tasks</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight">{{ $taskStats['total_tasks'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-xc-card/70 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">Completed</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight text-xc-cyan">{{ $taskStats['completed_tasks'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-xc-card/70 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">In progress</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight">{{ $taskStats['pending_tasks'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-xc-card/70 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">Overdue</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight {{ $taskStats['overdue_tasks'] > 0 ? 'text-red-300' : '' }}">{{ $taskStats['overdue_tasks'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-xc-card/70 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">Due in 7 days</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight">{{ $taskStats['due_soon_tasks'] }}</p>
                    </div>
                @endif
                @if ($taskStats['can_access_projects'])
                    <div class="rounded-2xl border border-white/10 bg-xc-card/70 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">Projects</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight">{{ $taskStats['projects_count'] }}</p>
                    </div>
                @endif
            </div>

            <div class="grid gap-4 xl:grid-cols-3">
                @if ($taskStats['can_access_tasks'])
                    <x-user.card title="Overall progress" description="Completed tasks vs total visible tasks." class="xl:col-span-1">
                        <div class="flex items-end justify-between gap-3">
                            <p class="text-4xl font-semibold tracking-tight">{{ $taskStats['overall']['percent'] }}%</p>
                            <p class="text-sm text-white/45">{{ $taskStats['overall']['completed'] }} / {{ $taskStats['overall']['total'] }}</p>
                        </div>
                        <div class="mt-4">
                            <x-user.progress-meter
                                :progress="$taskStats['overall']"
                                empty-label="No tasks yet"
                                :show-counts="false"
                                size="md"
                            />
                        </div>
                    </x-user.card>

                    <x-user.card title="Recent tasks" description="Latest updates across your board." class="xl:col-span-2">
                        <div class="divide-y divide-white/5">
                            @forelse ($taskStats['recent_tasks'] as $task)
                                @php $subProgress = $task->subtaskProgress(); @endphp
                                <a href="{{ route('user.tasks.show', $task) }}" class="flex flex-col gap-2 py-3 transition hover:bg-white/[0.02] sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-white">{{ $task->summary }}</p>
                                        <p class="mt-1 text-xs text-white/40">
                                            {{ $task->project?->name ?? 'No project' }}
                                            @if ($task->due_date)
                                                · Due {{ $task->due_date->format('M j') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-white/60">
                                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $task->status?->color ?? '#94A3B8' }}"></span>
                                            {{ $task->status?->name ?? '—' }}
                                        </span>
                                        @if ($subProgress['total'] > 0)
                                            <span class="text-xs text-white/40">{{ $subProgress['percent'] }}%</span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <p class="py-8 text-center text-sm text-white/40">No tasks yet.</p>
                            @endforelse
                        </div>
                    </x-user.card>
                @endif
            </div>

            @if ($taskStats['can_access_projects'] && $taskStats['top_projects']->isNotEmpty())
                <x-user.card title="Project health" description="Progress based on completed dynamic statuses.">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($taskStats['top_projects'] as $project)
                            @php $progress = $project->progressStats(); @endphp
                            <a href="{{ route('user.projects.show', $project) }}" class="rounded-xl border border-white/10 bg-xc-darker/40 p-4 transition hover:border-xc-cyan/30">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="truncate font-medium text-white">{{ $project->name }}</p>
                                    <span class="shrink-0 text-xs text-white/40">{{ $progress['percent'] }}%</span>
                                </div>
                                <div class="mt-3">
                                    <x-user.progress-meter
                                        :progress="$progress"
                                        empty-label="No tasks"
                                        :show-counts="false"
                                        size="sm"
                                    />
                                </div>
                                <p class="mt-2 text-xs text-white/40">
                                    {{ $progress['completed'] }} completed · {{ $progress['remaining'] }} remaining
                                </p>
                            </a>
                        @endforeach
                    </div>
                </x-user.card>
            @endif
        @endif

        @if ($canAttendance)
            <div class="grid gap-4 xl:grid-cols-3">
                @if ($attendanceSummary)
                    <div class="rounded-2xl border border-white/10 bg-xc-card/70 p-5">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">This month</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight">{{ $attendanceSummary['present_days'] }}</p>
                        <p class="mt-1 text-sm text-white/45">Days with check-in</p>
                        <p class="mt-4 text-xs {{ $attendanceSummary['open_session'] ? 'text-xc-cyan' : 'text-white/40' }}">
                            {{ $attendanceSummary['open_session'] ? 'Currently checked in' : 'No open session' }}
                        </p>
                    </div>
                @endif
                <div class="xl:col-span-2">
                    <x-user.card title="Attendance" description="Punch in/out and recent daily status.">
                        <x-user.attendance-punch
                            :open-session="$openSession"
                            :status-by-date="$statusByDate"
                        />
                    </x-user.card>
                </div>
            </div>
        @endif
    </div>
</x-user.layout>
