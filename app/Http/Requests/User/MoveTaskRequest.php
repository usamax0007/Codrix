<?php

namespace App\Http\Requests\User;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MoveTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Task $task */
        $task = $this->route('task');

        return $this->user()?->can('update', $task) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'task_status_id' => [
                'required',
                'integer',
                Rule::exists('task_statuses', 'id')->where(fn ($query) => $query->where('is_enabled', true)),
            ],
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'distinct', Rule::exists('tasks', 'id')],
        ];
    }

    public function status(): TaskStatus
    {
        return TaskStatus::query()->findOrFail((int) $this->validated('task_status_id'));
    }

    /**
     * @return list<int>
     */
    public function orderedIds(): array
    {
        return array_map('intval', $this->validated('ordered_ids'));
    }
}
