<x-user.layout title="{{ $task->summary }}" :wide="true">
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <a href="{{ route('user.tasks.index') }}" class="inline-flex items-center gap-1.5 text-sm text-white/50 hover:text-xc-cyan">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to board
                </a>
                <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight break-words">{{ $task->summary }}</h1>
            </div>

            @can('delete', $task)
                <form method="POST" action="{{ route('user.tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                    @csrf
                    @method('DELETE')
                    <x-user.button type="submit" variant="danger" size="sm">Delete</x-user.button>
                </form>
            @endcan
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                <x-user.card title="Description">
                    @if ($task->description)
                        <div class="task-description prose prose-invert max-w-none text-sm text-white/80">
                            {!! $task->safeDescriptionHtml() !!}
                        </div>
                    @else
                        <p class="text-sm text-white/40">No description.</p>
                    @endif
                </x-user.card>

                <x-user.card title="Attachments">
                    @if ($task->attachments->isEmpty())
                        <p class="text-sm text-white/40">No attachments.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($task->attachments as $attachment)
                                <li class="flex items-center justify-between gap-3 rounded-lg border border-white/10 bg-xc-darker/50 px-3 py-2.5">
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
                </x-user.card>

                <x-user.card title="Comments">
                    <form method="POST" action="{{ route('user.tasks.comments.store', $task) }}" class="space-y-3">
                        @csrf
                        <div class="space-y-1.5">
                            <label for="body" class="block text-sm font-medium text-white/80">Add a comment</label>
                            <textarea
                                id="body"
                                name="body"
                                rows="3"
                                required
                                maxlength="5000"
                                class="w-full rounded-lg border border-white/15 bg-xc-darker/80 px-3.5 py-2.5 text-sm text-white placeholder:text-white/35 focus:outline-none focus:ring-2 focus:ring-xc-cyan/50 focus:border-xc-cyan/50"
                                placeholder="Write a comment…"
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-xs text-red-300">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <x-user.button type="submit" size="sm">Comment</x-user.button>
                        </div>
                    </form>

                    <div class="mt-6 space-y-4 border-t border-white/10 pt-5">
                        @forelse ($task->comments as $comment)
                            <div class="rounded-xl border border-white/10 bg-xc-darker/40 p-4">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-xc-blue/30 text-[11px] font-semibold text-xc-cyan">
                                        {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $comment->user?->name }}</p>
                                        <p class="text-[11px] text-white/40">{{ $comment->created_at?->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="mt-3 whitespace-pre-wrap text-sm text-white/75">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-white/40">No comments yet.</p>
                        @endforelse
                    </div>
                </x-user.card>
            </div>

            <aside class="space-y-5">
                <x-user.card title="Details">
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-white/40">Status</dt>
                            <dd class="mt-1 flex items-center gap-2 font-medium">
                                <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $task->status?->color ?? '#94A3B8' }}"></span>
                                {{ $task->status?->name ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-white/40">Priority</dt>
                            <dd class="mt-1 font-medium">{{ $task->priority->getLabel() }}</dd>
                        </div>
                        <div>
                            <dt class="text-white/40">Assignee</dt>
                            <dd class="mt-1 flex items-center gap-2 font-medium">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-xc-blue/30 text-[10px] font-semibold text-xc-cyan">
                                    {{ strtoupper(substr($task->assignee?->name ?? '?', 0, 1)) }}
                                </span>
                                {{ $task->assignee?->name ?? '—' }}
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
                </x-user.card>
            </aside>
        </div>
    </div>

    @push('scripts')
        <style>
            .task-description a { color: #00E5C0; text-decoration: underline; }
            .task-description ul { list-style: disc; margin-left: 1.25rem; }
            .task-description ol { list-style: decimal; margin-left: 1.25rem; }
            .task-description p { margin-bottom: 0.75rem; font-size: 0.875rem; line-height: 1.55; }
            .task-description h1 {
                font-size: 1.75rem !important;
                font-weight: 700 !important;
                line-height: 1.25;
                margin: 0.85rem 0 0.45rem;
                color: #fff;
            }
            .task-description h2 {
                font-size: 1.375rem !important;
                font-weight: 600 !important;
                line-height: 1.3;
                margin: 0.75rem 0 0.4rem;
                color: #fff;
            }
            .task-description h3 {
                font-size: 1.125rem !important;
                font-weight: 600 !important;
                line-height: 1.35;
                margin: 0.65rem 0 0.35rem;
                color: #fff;
            }
            .task-description h1:first-child,
            .task-description h2:first-child,
            .task-description h3:first-child { margin-top: 0; }
        </style>
    @endpush
</x-user.layout>
