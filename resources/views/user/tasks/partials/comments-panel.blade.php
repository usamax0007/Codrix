@php
    /** @var \App\Models\Task $task */
    $commentsCount = (int) ($task->comments_count ?? 0);
    $rootComments = $task->comments;
    $threadId = isset($threadId) ? (int) $threadId : null;
    $selected = $threadId
        ? $rootComments->firstWhere('id', $threadId)
        : null;
    $threadOpen = $selected !== null;
    $commentsOnlyUrl = route('user.tasks.show', ['task' => $task, 'panel' => 'comments']);
@endphp
<div
    class="space-y-4"
    data-task-id="{{ $task->id }}"
    data-task-panel="comments"
    data-task-url="{{ route('user.tasks.show', $task) }}"
    @if ($threadOpen) data-active-thread="{{ $threadId }}" @endif
>
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

    <div @class([
        'grid min-h-[24rem] gap-0 border-t border-white/[0.08]',
        'lg:grid-cols-2' => $threadOpen,
    ])>
        {{-- All comments --}}
        <div @class([
            'flex min-h-0 flex-col',
            'lg:border-r lg:border-white/[0.08]' => $threadOpen,
        ])>
            <div class="px-1 py-3">
                <h3 class="text-sm font-semibold text-white">All comments</h3>
            </div>

            <div class="view-task-modal-scroll flex-1 space-y-0.5 overflow-y-auto px-1 pb-2" data-comments-list>
                @forelse ($rootComments as $comment)
                    <x-user.task-comment
                        :comment="$comment"
                        :task="$task"
                        :interactive="true"
                        :show-replies="false"
                        reply-mode="thread"
                        :selected="$threadOpen && (int) $comment->id === (int) $threadId"
                    />
                @empty
                    <p class="px-1 py-8 text-sm text-white/40">No comments yet. Be the first to comment.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('user.tasks.comments.store', $task) }}" class="mt-auto space-y-2 border-t border-white/[0.06] px-1 pt-3" data-task-comment-form>
                @csrf
                <input type="hidden" name="return_panel" value="comments">
                <textarea
                    id="task-all-comment-body"
                    name="body"
                    rows="2"
                    required
                    maxlength="5000"
                    class="w-full rounded-lg border border-white/10 bg-transparent px-3 py-2 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-1 focus:ring-xc-cyan/40"
                    placeholder="Share an update…"
                ></textarea>
                <p class="hidden text-xs text-red-300" data-comment-error></p>
                <div class="flex justify-end">
                    <x-user.button type="submit" size="sm">Comment</x-user.button>
                </div>
            </form>
        </div>

        {{-- Thread only when opened via Reply --}}
        @if ($threadOpen)
            <div class="flex min-h-0 flex-col border-t border-white/[0.08] lg:border-t-0" data-comment-thread-pane>
                <div class="flex items-center justify-between gap-3 px-1 py-3 lg:pl-4">
                    <h3 class="text-sm font-semibold text-white">Thread</h3>
                    <button
                        type="button"
                        class="rounded-lg p-1.5 text-white/40 transition hover:bg-white/5 hover:text-white"
                        aria-label="Close thread"
                        data-close-comment-thread
                        data-task-url="{{ $commentsOnlyUrl }}"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="view-task-modal-scroll flex-1 overflow-y-auto px-1 pb-2 lg:pl-4">
                    <div class="flex min-h-full flex-col">
                        <div class="space-y-0.5">
                            <x-user.task-comment
                                :comment="$selected"
                                :task="$task"
                                :interactive="false"
                                :show-replies="false"
                                :selected="true"
                            />

                            <div class="my-2 border-t border-white/[0.06]"></div>

                            @forelse ($selected->replies as $reply)
                                <x-user.task-comment
                                    :comment="$reply"
                                    :task="$task"
                                    :depth="1"
                                    :interactive="false"
                                    :show-replies="false"
                                />
                            @empty
                                <p class="px-1 py-4 text-sm text-white/40">No replies yet.</p>
                            @endforelse
                        </div>

                        <form
                            method="POST"
                            action="{{ route('user.tasks.comments.store', $task) }}"
                            class="mt-auto space-y-2 border-t border-white/[0.06] pt-3"
                            data-task-comment-form
                        >
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $selected->id }}">
                            <input type="hidden" name="return_panel" value="comments">
                            <input type="hidden" name="thread_id" value="{{ $selected->id }}">
                            <textarea
                                name="body"
                                rows="2"
                                required
                                maxlength="5000"
                                class="w-full rounded-lg border border-white/10 bg-transparent px-3 py-2 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-1 focus:ring-xc-cyan/40"
                                placeholder="Reply…"
                            ></textarea>
                            <p class="hidden text-xs text-red-300" data-comment-error></p>
                            <div class="flex justify-end">
                                <x-user.button type="submit" size="sm">Reply</x-user.button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
