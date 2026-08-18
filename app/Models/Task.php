<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Services\Progress\ProgressService;
use App\Support\SafeHtml;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Task extends Model
{
    protected $fillable = [
        'summary',
        'description',
        'project_id',
        'task_status_id',
        'priority',
        'created_by',
        'sort_order',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'due_date' => 'date',
            'sort_order' => 'integer',
            'task_status_id' => 'integer',
            'project_id' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps()->orderBy('users.name');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class)->latest();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->whereNull('parent_id')->latest();
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('sort_order')->orderBy('id');
    }

    public function isAssignedTo(User $user): bool
    {
        if ($this->relationLoaded('assignees')) {
            return $this->assignees->contains('id', $user->id);
        }

        return $this->assignees()->where('users.id', $user->id)->exists();
    }

    /**
     * @return array{total: int, completed: int, remaining: int, percent: int}
     */
    public function subtaskProgress(): array
    {
        return app(ProgressService::class)->forTask($this);
    }

    public function plainDescriptionPreview(int $limit = 120): ?string
    {
        if (blank($this->description)) {
            return null;
        }

        return Str::limit(trim(html_entity_decode(strip_tags($this->description))), $limit);
    }

    public function safeDescriptionHtml(): string
    {
        if (blank($this->description)) {
            return '';
        }

        return SafeHtml::clean($this->description);
    }
}
