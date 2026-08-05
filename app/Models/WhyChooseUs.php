<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrderedScopes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'icon',
    'sort_order',
    'is_active',
])]
class WhyChooseUs extends Model
{
    use HasActiveOrderedScopes;

    protected $table = 'why_choose_us';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected function orderedByColumn(): string
    {
        return 'title';
    }
}
