@props([
    'comment',
    'task',
    'depth' => 0,
    'interactive' => true,
    'showReplies' => true,
])
@php
    /** @var \App\Models\TaskComment $comment */
    /** @var \App\Models\Task $task */
    $isReply = $depth > 0;
@endphp
<div class="{{ $isReply ? 'ml-8 sm:ml-10' : '' }}" data-comment-id="{{ $comment->id }}">
    <div class="rounded-xl border border-white/10 bg-xc-darker/40 p-3.5 sm:p-4">
        <div class="flex items-start gap-2.5">
            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-xc-blue/30 text-[11px] font-semibold text-xc-cyan">
                {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                    <p class="text-sm font-medium text-white">{{ $comment->user?->name }}</p>
                    <p class="text-[11px] text-white/40">{{ $comment->created_at?->diffForHumans() }}</p>
                    @if ($isReply)
                        <span class="text-[10px] uppercase tracking-wide text-white/30">Reply</span>
                    @endif
                </div>
                <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-white/75">{{ $comment->body }}</p>

                @if ($interactive)
                    <div class="mt-2.5">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 text-xs font-medium text-white/45 transition hover:text-xc-cyan"
                            data-reply-toggle="{{ $comment->id }}"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Reply
                        </button>
                        @if (! $isReply && ($comment->replies_count ?? $comment->replies->count()) > 0)
                            <span class="ml-2 text-[11px] text-white/35">
                                {{ $comment->replies_count ?? $comment->replies->count() }}
                                {{ ($comment->replies_count ?? $comment->replies->count()) === 1 ? 'reply' : 'replies' }}
                            </span>
                        @endif
                    </div>

                    <form
                        method="POST"
                        action="{{ route('user.tasks.comments.store', $task) }}"
                        class="mt-3 hidden space-y-2"
                        data-task-comment-form
                        data-reply-form="{{ $comment->id }}"
                    >
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <input type="hidden" name="return_panel" value="comments">
                        <textarea
                            name="body"
                            rows="2"
                            required
                            maxlength="5000"
                            class="w-full rounded-xl border border-white/10 bg-xc-darker/90 px-3 py-2 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-2 focus:ring-xc-cyan/40 focus:border-xc-cyan/40"
                            placeholder="Write a reply…"
                        ></textarea>
                        <p class="hidden text-xs text-red-300" data-comment-error></p>
                        <div class="flex justify-end gap-2">
                            <button type="button" class="rounded-lg px-3 py-1.5 text-xs text-white/50 hover:text-white" data-reply-cancel="{{ $comment->id }}">Cancel</button>
                            <x-user.button type="submit" size="sm">Reply</x-user.button>
                        </div>
                    </form>
                @elseif (! $isReply && ($comment->replies_count ?? $comment->replies->count()) > 0)
                    <p class="mt-2 text-[11px] text-white/35">
                        {{ $comment->replies_count ?? $comment->replies->count() }}
                        {{ ($comment->replies_count ?? $comment->replies->count()) === 1 ? 'reply' : 'replies' }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if ($showReplies && ! $isReply && $comment->relationLoaded('replies') && $comment->replies->isNotEmpty())
        <div class="mt-2.5 space-y-2.5 border-l border-white/10 pl-3 sm:pl-4">
            @foreach ($comment->replies as $reply)
                <x-user.task-comment :comment="$reply" :task="$task" :depth="1" :interactive="$interactive" :show-replies="false" />
            @endforeach
        </div>
    @endif
</div>
