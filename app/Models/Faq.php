<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrderedScopes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'question',
    'answer',
    'sort_order',
    'is_active',
])]
class Faq extends Model
{
    use HasActiveOrderedScopes;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
