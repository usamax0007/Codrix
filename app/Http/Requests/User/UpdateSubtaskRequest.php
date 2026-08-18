<?php

namespace App\Http\Requests\User;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Task $task */
        $task = $this->route('task');
        /** @var Subtask $subtask */
        $subtask = $this->route('subtask');

        if (! $this->user()?->can('update', $task)) {
            return false;
        }

        return (int) $subtask->task_id === (int) $task->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array{title: string, description?: ?string}
     */
    public function subtaskData(): array
    {
        return $this->safe()->only(['title', 'description']);
    }
}
