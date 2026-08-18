<?php

namespace App\Http\Requests\User;

use App\Models\Task;
use App\Support\AppPermission;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Task $task */
        $task = $this->route('task');

        return ($this->user()?->can(AppPermission::TASKS_CREATE_SUBTASK) ?? false)
            && ($this->user()?->can('update', $task) ?? false);

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
