<?php

namespace App\Http\Requests\User;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTaskCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Task $task */
        $task = $this->route('task');

        return $this->user()?->can('view', $task) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:5000'],
            'parent_id' => ['nullable', 'integer', 'exists:task_comments,id'],
            'return_panel' => ['nullable', 'in:details,comments'],
            'thread_id' => ['nullable', 'integer', 'exists:task_comments,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var Task $task */
            $task = $this->route('task');

            $parentId = $this->input('parent_id');

            if ($parentId) {
                $belongsToTask = TaskComment::query()
                    ->whereKey($parentId)
                    ->where('task_id', $task->id)
                    ->exists();

                if (! $belongsToTask) {
                    $validator->errors()->add('parent_id', 'Reply must belong to this task.');
                }
            }

            $threadId = $this->input('thread_id');

            if ($threadId) {
                $threadBelongs = TaskComment::query()
                    ->whereKey($threadId)
                    ->where('task_id', $task->id)
                    ->whereNull('parent_id')
                    ->exists();

                if (! $threadBelongs) {
                    $validator->errors()->add('thread_id', 'Thread must belong to this task.');
                }
            }
        });
    }

    public function body(): string
    {
        return (string) $this->validated('body');
    }

    public function parentId(): ?int
    {
        $parentId = $this->validated('parent_id') ?? null;

        return $parentId !== null ? (int) $parentId : null;
    }

    public function returnPanel(): string
    {
        return (string) ($this->validated('return_panel') ?? 'comments');
    }

    public function threadId(): ?int
    {
        $threadId = $this->validated('thread_id') ?? $this->parentId();

        return $threadId !== null ? (int) $threadId : null;
    }
}
