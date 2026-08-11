<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TaskStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'sort_order',
        'is_enabled',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_enabled' => 'boolean',
            'is_completed' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $status): void {
            if (blank($status->slug)) {
                $status->slug = static::uniqueSlugFromName($status->name);
            }

            if ($status->sort_order === null) {
                $status->sort_order = (int) static::query()->max('sort_order') + 1;
            }
        });

        static::updating(function (self $status): void {
            if ($status->isDirty('name') && ! $status->isDirty('slug')) {
                // Keep slug stable when renaming so historical references stay intact.
            }
        });
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public static function uniqueSlugFromName(string $name): string
    {
        $base = Str::slug($name) ?: 'status';
        $slug = $base;
        $i = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function getLabel(): string
    {
        return $this->name;
    }
}
