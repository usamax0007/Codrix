<?php

namespace App\Http\Requests\User;

use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var TaskStatus $status */
        $status = $this->route('task_status');

        return $this->user()?->can('update', $status) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_enabled' => ['sometimes', 'boolean'],
            'is_completed' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array{name: string, color: string, is_enabled: bool, is_completed: bool}
     */
    public function statusData(): array
    {
        return [
            'name' => $this->validated('name'),
            'color' => $this->validated('color'),
            'is_enabled' => $this->boolean('is_enabled'),
            'is_completed' => $this->boolean('is_completed'),
        ];
    }
}
