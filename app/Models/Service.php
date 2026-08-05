<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrderedScopes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

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
    use HasActiveOrderedScopes;

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

    protected function orderedByColumn(): string
    {
        return 'title';
    }
}
