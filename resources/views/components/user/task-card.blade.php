@props([
    'task',
])
@php
    $priorityColors = match ($task->priority->value) {
        'high' => 'bg-red-500/15 text-red-300 border-red-500/30',
        'low' => 'bg-white/5 text-white/50 border-white/10',
        default => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
    };
    $subProgress = $task->subtaskProgress();
@endphp
<article
    data-task-id="{{ $task->id }}"
    data-task-url="{{ route('user.tasks.show', $task) }}"
    class="task-card group cursor-grab rounded-xl border border-white/10 bg-xc-darker/90 p-3.5 shadow-sm shadow-black/20 active:cursor-grabbing hover:border-xc-cyan/30"
>
    <div class="flex items-start justify-between gap-2">
        <div class="min-w-0">
            @if ($task->project)
                <p class="task-card-open mb-1 truncate text-[10px] font-semibold uppercase tracking-wider text-xc-cyan/80 cursor-pointer">
                    {{ $task->project->name }}
                </p>
            @endif
            <h3 class="task-card-open text-sm font-medium leading-snug text-white cursor-pointer hover:text-xc-cyan">
                {{ $task->summary }}
            </h3>
        </div>
        <form method="POST" action="{{ route('user.tasks.destroy', $task) }}" class="task-card-actions shrink-0 opacity-0 transition group-hover:opacity-100" onsubmit="return confirm('Delete this task?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded p-1 text-white/40 hover:bg-red-500/10 hover:text-red-300" title="Delete" aria-label="Delete task">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </form>
    </div>

    @if ($preview = $task->plainDescriptionPreview(90))
        <p class="task-card-open mt-2 line-clamp-2 text-xs text-white/45 cursor-pointer">{{ $preview }}</p>
    @endif

    <div class="task-card-open mt-3 flex flex-wrap items-center gap-2 cursor-pointer">
        <span class="inline-flex rounded-md border px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $priorityColors }}">
            {{ $task->priority->getLabel() }}
        </span>

        @if ($task->due_date)
            <span class="text-[11px] text-white/40">
                Due {{ $task->due_date->format('M j') }}
            </span>
        @endif

        @if (($task->comments_count ?? 0) > 0)
            <span class="text-[11px] text-white/40">{{ $task->comments_count }} comment{{ $task->comments_count === 1 ? '' : 's' }}</span>
        @endif

        @if (($task->attachments_count ?? 0) > 0)
            <span class="text-[11px] text-white/40">{{ $task->attachments_count }} file{{ $task->attachments_count === 1 ? '' : 's' }}</span>
        @endif
    </div>

    <div class="task-card-open mt-3 cursor-pointer" data-task-progress>
        <x-user.progress-meter
            :progress="$subProgress"
            label="Subtasks"
            empty-label="No subtasks"
            count-noun="completed"
            size="sm"
        />
    </div>

    <div class="task-card-open mt-3 flex items-center gap-2 border-t border-white/5 pt-2.5 cursor-pointer" data-task-assignees>
        <x-user.assignee-stack :assignees="$task->assignees" />
    </div>
</article>
