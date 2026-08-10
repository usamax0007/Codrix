<?php

namespace App\Http\Requests\User;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Support\AppPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Task::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $canAssign = $this->user()?->can(AppPermission::TASKS_ASSIGN) ?? false;

        return [
            'summary' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:20000'],
            'status' => ['nullable', Rule::enum(TaskStatus::class)],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'due_date' => ['nullable', 'date'],
            'assignee_id' => [
                Rule::requiredIf($canAssign),
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::User->value)),
            ],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt,zip'],
        ];
    }

    /**
     * @return array{summary: string, description?: ?string, status?: string, priority: string, assignee_id?: int, due_date?: ?string}
     */
    public function taskData(): array
    {
        return $this->safe()->only([
            'summary',
            'description',
            'status',
            'priority',
            'assignee_id',
            'due_date',
        ]);
    }

    /**
     * @return array<int, UploadedFile>
     */
    public function attachments(): array
    {
        /** @var array<int, UploadedFile> $files */
        $files = $this->file('attachments', []);

        return is_array($files) ? array_values($files) : [];
    }
}
