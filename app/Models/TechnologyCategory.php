<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrderedScopes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'items',
    'sort_order',
    'is_active',
])]
class TechnologyCategory extends Model
{
    use HasActiveOrderedScopes;

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected function orderedByColumn(): string
    {
        return 'name';
    }

    /**
     * @return list<string>
     */
    public function itemsList(): array
    {
        $items = $this->items ?? [];

        return array_values(array_filter($items, fn ($item) => filled($item)));
    }
}
