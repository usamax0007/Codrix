<?php

namespace App\Http\Requests\User;

use App\Enums\UserRole;
use App\Models\Task;
use App\Support\AppPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskAssigneesRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Task $task */
        $task = $this->route('task');

        $user = $this->user();

        if (! $user?->can(AppPermission::TASKS_ASSIGN)) {
            return false;
        }

        return $user->can('view', $task);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'assignee_ids' => ['required', 'array', 'min:1'],
            'assignee_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::User->value)),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'assignee_ids.required' => 'Select at least one assignee.',
            'assignee_ids.min' => 'Select at least one assignee.',
        ];
    }

    /**
     * @return list<int>
     */
    public function assigneeIds(): array
    {
        return array_values(array_unique(array_map('intval', $this->validated('assignee_ids'))));
    }
}
