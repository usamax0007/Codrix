<?php

namespace App\Http\Requests\User;

use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderTaskStatusesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', TaskStatus::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'distinct', Rule::exists('task_statuses', 'id')],
        ];
    }

    /**
     * @return list<int>
     */
    public function orderedIds(): array
    {
        return array_map('intval', $this->validated('ordered_ids'));
    }
}
