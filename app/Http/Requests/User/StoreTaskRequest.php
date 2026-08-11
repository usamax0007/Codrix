<?php

namespace App\Http\Requests\User;

use App\Enums\TaskPriority;
use App\Enums\UserRole;
use App\Services\Project\ProjectService;
use App\Support\AppPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTaskRequest extends FormRequest
{
    public const MAX_ATTACHMENT_KB = 10240;

    public const MAX_ATTACHMENTS = 5;

    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Task::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('description') && is_string($this->input('description'))) {
            // Quill/paste can embed huge base64 images and blow past PHP post_max_size.
            $description = preg_replace(
                '/<img[^>]+src=["\']data:image\/[^"\']+["\'][^>]*>/i',
                '',
                $this->input('description')
            ) ?? '';

            $description = preg_replace('/src=["\']data:image\/[^"\']+["\']/i', 'src=""', $description) ?? $description;

            $this->merge([
                'description' => $description,
            ]);
        }
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
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'task_status_id' => [
                'nullable',
                'integer',
                Rule::exists('task_statuses', 'id')->where(fn ($query) => $query->where('is_enabled', true)),
            ],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'due_date' => ['nullable', 'date'],
            'assignee_ids' => [
                Rule::requiredIf($canAssign),
                'nullable',
                'array',
                'min:1',
            ],
            'assignee_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::User->value)),
            ],
            'attachments' => ['nullable', 'array', 'max:'.self::MAX_ATTACHMENTS],
            'attachments.*' => [
                'file',
                'max:'.self::MAX_ATTACHMENT_KB,
                'extensions:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt,zip',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->has('project_id')) {
                return;
            }

            $projectId = (int) $this->input('project_id');
            $user = $this->user();

            if (! $user || $projectId < 1) {
                return;
            }

            if (! app(ProjectService::class)->userCanCreateTaskIn($user, $projectId)) {
                $validator->errors()->add('project_id', 'You cannot create tasks in this project.');
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'attachments.max' => 'You may upload at most '.self::MAX_ATTACHMENTS.' files.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.max' => 'Each attachment must be 10MB or smaller.',
            'attachments.*.extensions' => 'Allowed types: jpg, png, gif, webp, pdf, doc, docx, xls, xlsx, txt, zip.',
            'attachments.*.uploaded' => 'A file failed to upload. Keep each file at 10MB or smaller, then try again.',
            'assignee_ids.required' => 'Select at least one assignee.',
            'assignee_ids.min' => 'Select at least one assignee.',
            'project_id.required' => 'Select a project.',
        ];
    }

    /**
     * @return array{summary: string, description?: ?string, project_id: int, task_status_id?: int, priority: string, assignee_ids?: list<int>, due_date?: ?string}
     */
    public function taskData(): array
    {
        $data = $this->safe()->only([
            'summary',
            'description',
            'project_id',
            'task_status_id',
            'priority',
            'assignee_ids',
            'due_date',
        ]);

        if (isset($data['assignee_ids']) && is_array($data['assignee_ids'])) {
            $data['assignee_ids'] = array_values(array_map('intval', $data['assignee_ids']));
        }

        return $data;
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
