@php
    /** @var \App\Models\Task $task */
    $commentsCount = (int) ($task->comments_count ?? 0);
    $taskProgress = $task->subtaskProgress();
    $rootComments = $task->comments;
@endphp
<div class="space-y-5" data-task-id="{{ $task->id }}" data-task-panel="details" data-task-url="{{ route('user.tasks.show', $task) }}">
    <div class="grid gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <section>
                <h3 class="mb-2 text-xs font-semibold uppercase tracking-wider text-white/40">Description</h3>
                @if ($task->description)
                    <div class="task-description prose prose-invert max-w-none text-sm text-white/80">
                        {!! $task->safeDescriptionHtml() !!}
                    </div>
                @else
                    <p class="text-sm text-white/40">No description.</p>
                @endif
            </section>

            <section>
                <h3 class="mb-2 text-xs font-semibold uppercase tracking-wider text-white/40">Attachments</h3>
                @if ($task->attachments->isEmpty())
                    <p class="text-sm text-white/40">No attachments.</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($task->attachments as $attachment)
                            <li class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-xc-darker/50 px-3 py-2.5">
                                <div class="min-w-0">
                                    <a href="{{ $attachment->url() }}" target="_blank" class="truncate text-sm font-medium text-xc-cyan hover:underline">
                                        {{ $attachment->original_name }}
                                    </a>
                                    <p class="text-xs text-white/40">
                                        {{ $attachment->humanSize() }}
                                        @if ($attachment->uploader)
                                            · by {{ $attachment->uploader->name }}
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ $attachment->url() }}" target="_blank" class="shrink-0 text-xs text-white/50 hover:text-white">Open</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            @include('user.tasks.partials.subtasks', ['task' => $task])

            <section data-comments-section>
                <div class="mb-2 flex items-center justify-between gap-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-white/40">Comments</h3>
                    @if ($commentsCount > 0)
                        <button
                            type="button"
                            class="text-xs font-medium text-xc-cyan hover:underline"
                            data-open-comments
                            data-task-url="{{ route('user.tasks.show', ['task' => $task, 'panel' => 'comments']) }}"
                            data-comments-all-link
                        >
                            See all comments (<span data-comments-total>{{ $commentsCount }}</span>)
                        </button>
                    @endif
                </div>

                <div>
                    @if ($rootComments->isNotEmpty())
                        <div
                            class="space-y-0.5 overflow-hidden"
                            data-comments-preview
                            style="max-height: min(36vh, 22rem);"
                        >
                            @foreach ($rootComments as $comment)
                                <div data-comment-preview-item>
                                    <x-user.task-comment
                                        :comment="$comment"
                                        :task="$task"
                                        :interactive="true"
                                        :show-replies="false"
                                        reply-mode="thread"
                                    />
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2 hidden" data-comments-overflow>
                            <button
                                type="button"
                                class="text-xs font-medium text-xc-cyan hover:underline"
                                data-open-comments
                                data-task-url="{{ route('user.tasks.show', ['task' => $task, 'panel' => 'comments']) }}"
                            >
                                See all comments
                                <span class="text-white/40">(<span data-comments-total>{{ $commentsCount }}</span>)</span>
                            </button>
                        </div>
                    @else
                        <p class="text-sm text-white/40">No comments yet.</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('user.tasks.comments.store', $task) }}" class="mt-4 space-y-3" data-task-comment-form>
                    @csrf
                    <input type="hidden" name="return_panel" value="details">
                    <div class="space-y-1.5">
                        <label for="task-comment-body" class="sr-only">Add a comment</label>
                        <textarea
                            id="task-comment-body"
                            name="body"
                            rows="2"
                            required
                            maxlength="5000"
                            class="w-full rounded-lg border border-white/10 bg-transparent px-3.5 py-2.5 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-1 focus:ring-xc-cyan/40"
                            placeholder="Write a comment…"
                        ></textarea>
                        <p class="hidden text-xs text-red-300" data-comment-error></p>
                    </div>
                    <div class="flex justify-end">
                        <x-user.button type="submit" size="sm">Comment</x-user.button>
                    </div>
                </form>
            </section>
        </div>

        <aside class="space-y-1">
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-white/40">Details</h3>
            <dl class="space-y-4 rounded-xl border border-white/10 bg-xc-darker/50 p-4 text-sm">
                <div>
                    <dt class="text-white/40">Project</dt>
                    <dd class="mt-1 font-medium">
                        @if ($task->project)
                            <a href="{{ route('user.projects.show', $task->project) }}" class="text-xc-cyan hover:underline">
                                {{ $task->project->name }}
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-white/40">Status</dt>
                    <dd class="mt-1 flex items-center gap-2 font-medium">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $task->status?->color ?? '#94A3B8' }}"></span>
                        {{ $task->status?->name ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-white/40">Task progress</dt>
                    <dd class="mt-2">
                        <x-user.progress-meter
                            :progress="$taskProgress"
                            empty-label="No subtasks · 0%"
                            count-noun="completed"
                            size="sm"
                        />
                    </dd>
                </div>
                <div>
                    <dt class="text-white/40">Subtasks</dt>
                    <dd class="mt-1 space-y-1 font-medium">
                        <p>{{ $taskProgress['total'] }} total</p>
                        <p class="text-white/70">{{ $taskProgress['completed'] }} completed</p>
                        <p class="text-white/70">{{ $taskProgress['remaining'] }} remaining</p>
                    </dd>
                </div>
                <div>
                    <dt class="text-white/40">Priority</dt>
                    <dd class="mt-1 font-medium">{{ $task->priority->getLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-white/40">Assignees</dt>
                    <dd class="mt-2">
                        @include('user.tasks.partials.assignees-editor', [
                            'task' => $task,
                            'assignableUsers' => $assignableUsers ?? null,
                        ])
                    </dd>
                </div>
                <div>
                    <dt class="text-white/40">Reporter</dt>
                    <dd class="mt-1 font-medium">{{ $task->creator?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-white/40">Due date</dt>
                    <dd class="mt-1 font-medium">{{ $task->due_date?->format('M j, Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-white/40">Created</dt>
                    <dd class="mt-1 font-medium">{{ $task->created_at?->format('M j, Y g:i A') }}</dd>
                </div>
            </dl>

            @can('delete', $task)
                <form method="POST" action="{{ route('user.tasks.destroy', $task) }}" class="mt-4" onsubmit="return confirm('Delete this task?')">
                    @csrf
                    @method('DELETE')
                    <x-user.button type="submit" variant="danger" size="sm" class="w-full">Delete Task</x-user.button>
                </form>
            @endcan
        </aside>
    </div>
</div>
