<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug',
    'title',
    'description',
    'summary',
    'icon',
    'is_popular',
    'what',
    'benefits',
    'technologies',
    'why',
    'sort_order',
    'is_active',
])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'benefits' => 'array',
            'technologies' => 'array',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Service $service): void {
            if (filled($service->summary) && blank($service->description)) {
                $service->description = $service->summary;
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
