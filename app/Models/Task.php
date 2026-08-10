<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Task extends Model
{
    protected $fillable = [
        'summary',
        'description',
        'status',
        'priority',
        'assignee_id',
        'created_by',
        'sort_order',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'due_date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
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
        return $this->hasMany(TaskComment::class)->oldest();
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

        return strip_tags(
            $this->description,
            '<p><br><strong><b><em><i><u><ul><ol><li><a><h1><h2><h3><blockquote><code><pre><span>'
        );
    }
}
