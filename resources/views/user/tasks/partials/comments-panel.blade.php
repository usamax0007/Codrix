@php
    /** @var \App\Models\Task $task */
    $commentsCount = (int) ($task->comments_count ?? $task->comments->sum(fn ($c) => 1 + $c->replies->count()));
@endphp
<div class="space-y-5" data-task-id="{{ $task->id }}" data-task-panel="comments" data-task-url="{{ route('user.tasks.show', $task) }}">
    <div class="flex items-center justify-between gap-3">
        <button
            type="button"
            class="inline-flex items-center gap-1.5 text-sm text-white/50 transition hover:text-xc-cyan"
            data-back-to-task
            data-task-url="{{ route('user.tasks.show', $task) }}"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to task
        </button>
        <p class="text-xs text-white/40">{{ $commentsCount }} comment{{ $commentsCount === 1 ? '' : 's' }}</p>
    </div>

    <form method="POST" action="{{ route('user.tasks.comments.store', $task) }}" class="space-y-3" data-task-comment-form>
        @csrf
        <input type="hidden" name="return_panel" value="comments">
        <div class="space-y-1.5">
            <label for="task-all-comment-body" class="block text-sm font-medium text-white/80">Add a comment</label>
            <textarea
                id="task-all-comment-body"
                name="body"
                rows="3"
                required
                maxlength="5000"
                class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3.5 py-2.5 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
                placeholder="Share an update…"
            ></textarea>
            <p class="hidden text-xs text-red-300" data-comment-error></p>
        </div>
        <div class="flex justify-end">
            <x-user.button type="submit" size="sm">Comment</x-user.button>
        </div>
    </form>

    <div class="space-y-4 border-t border-white/[0.06] pt-5">
        @forelse ($task->comments as $comment)
            <x-user.task-comment :comment="$comment" :task="$task" />
        @empty
            <p class="text-sm text-white/40">No comments yet. Be the first to comment.</p>
        @endforelse
    </div>
</div>
