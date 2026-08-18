@props([
    'comment',
    'task',
    'depth' => 0,
    'interactive' => true,
    'showReplies' => true,
    'replyMode' => 'inline',
    'selected' => false,
])
@php
    /** @var \App\Models\TaskComment $comment */
    /** @var \App\Models\Task $task */
    $isReply = $depth > 0;
    $repliesCount = (int) ($comment->replies_count ?? ($comment->relationLoaded('replies') ? $comment->replies->count() : 0));
    $threadUrl = route('user.tasks.show', ['task' => $task, 'panel' => 'comments', 'thread' => $comment->id]);
@endphp
<div
    @class([
        'group/comment -mx-1 rounded-lg px-1 py-2 transition',
        'bg-xc-cyan/[0.06]' => $selected,
        'hover:bg-white/[0.03]' => ! $selected,
    ])
    data-comment-id="{{ $comment->id }}"
>
    <div class="flex items-start gap-2.5">
        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-xc-blue/25 text-[11px] font-semibold text-xc-cyan">
            {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
        </span>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                <span class="text-sm font-semibold text-white">{{ $comment->user?->name }}</span>
                <span class="text-[11px] text-white/35">{{ $comment->created_at?->diffForHumans() }}</span>
            </div>
            <p class="mt-0.5 whitespace-pre-wrap text-sm leading-relaxed text-white/75">{{ $comment->body }}</p>

            @if ($interactive && ! $isReply)
                <div class="mt-1.5 flex flex-wrap items-center gap-3 opacity-80 transition group-hover/comment:opacity-100">
                    @if ($replyMode === 'thread')
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 text-xs font-medium text-white/40 transition hover:text-xc-cyan"
                            data-open-comment-thread
                            data-task-url="{{ $threadUrl }}"
                            data-thread-id="{{ $comment->id }}"
                        >
                            Reply
                        </button>
                    @else
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 text-xs font-medium text-white/40 transition hover:text-xc-cyan"
                            data-reply-toggle="{{ $comment->id }}"
                        >
                            Reply
                        </button>
                    @endif

                    @if ($repliesCount > 0)
                        <button
                            type="button"
                            class="text-xs font-medium text-xc-cyan/80 transition hover:text-xc-cyan"
                            data-open-comment-thread
                            data-task-url="{{ $threadUrl }}"
                            data-thread-id="{{ $comment->id }}"
                        >
                            {{ $repliesCount }} {{ $repliesCount === 1 ? 'reply' : 'replies' }}
                        </button>
                    @endif
                </div>

                @if ($replyMode === 'inline')
                    <form
                        method="POST"
                        action="{{ route('user.tasks.comments.store', $task) }}"
                        class="mt-2.5 hidden space-y-2"
                        data-task-comment-form
                        data-reply-form="{{ $comment->id }}"
                    >
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <input type="hidden" name="return_panel" value="comments">
                        <input type="hidden" name="thread_id" value="{{ $comment->id }}">
                        <textarea
                            name="body"
                            rows="2"
                            required
                            maxlength="5000"
                            class="w-full rounded-lg border border-white/10 bg-transparent px-3 py-2 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-1 focus:ring-xc-cyan/40"
                            placeholder="Reply…"
                        ></textarea>
                        <p class="hidden text-xs text-red-300" data-comment-error></p>
                        <div class="flex justify-end gap-2">
                            <button type="button" class="px-2 py-1 text-xs text-white/45 hover:text-white" data-reply-cancel="{{ $comment->id }}">Cancel</button>
                            <x-user.button type="submit" size="sm">Reply</x-user.button>
                        </div>
                    </form>
                @endif
            @elseif (! $interactive && ! $isReply && $repliesCount > 0)
                <p class="mt-1.5 text-xs font-medium text-xc-cyan/70">
                    {{ $repliesCount }} {{ $repliesCount === 1 ? 'reply' : 'replies' }}
                </p>
            @endif
        </div>
    </div>

    @if ($showReplies && ! $isReply && $comment->relationLoaded('replies') && $comment->replies->isNotEmpty())
        <div class="mt-1 space-y-0.5 pl-10">
            @foreach ($comment->replies as $reply)
                <x-user.task-comment :comment="$reply" :task="$task" :depth="1" :interactive="false" :show-replies="false" />
            @endforeach
        </div>
    @endif
</div>
