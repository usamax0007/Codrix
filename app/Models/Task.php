<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'summary',
        'description',
        'assignee_id',
        'priority',
        'status',
        'due_date',
        'user_id',
        'content',
        'attachment',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
}
