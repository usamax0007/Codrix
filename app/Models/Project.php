<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'due_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $project): void {
            if (blank($project->slug)) {
                $project->slug = static::uniqueSlugFromName($project->name);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public static function uniqueSlugFromName(string $name): string
    {
        $base = Str::slug($name) ?: 'project';
        $slug = $base;
        $i = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    /**
     * @return array{total: int, completed: int, remaining: int, percent: int}
     */
    public function progressStats(): array
    {
        $total = (int) ($this->tasks_count ?? $this->tasks()->count());
        $completed = (int) ($this->completed_tasks_count ?? $this->tasks()
            ->whereHas('status', fn ($query) => $query->where('is_completed', true))
            ->count());

        $remaining = max(0, $total - $completed);
        $percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'remaining' => $remaining,
            'percent' => $percent,
        ];
    }
}
