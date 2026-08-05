<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrderedScopes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'role',
    'image',
    'text',
    'sort_order',
    'is_active',
])]
class Testimonial extends Model
{
    use HasActiveOrderedScopes;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected function orderedByColumn(): string
    {
        return 'name';
    }

    public function imageUrl(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        if ($this->image && file_exists(public_path('images/'.$this->image))) {
            return asset('images/'.$this->image);
        }

        return asset('images/sara-wilsone.webp');
    }
}
