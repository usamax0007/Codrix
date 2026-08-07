<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'work_date',
    'check_in_at',
    'check_out_at',
    'worked_minutes',
    'status',
])]
class Attendance extends Model
{
    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'worked_minutes' => 'integer',
            'status' => AttendanceStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOpen(): bool
    {
        return $this->check_in_at !== null && $this->check_out_at === null;
    }

    public function isAbsent(): bool
    {
        return $this->status === AttendanceStatus::Absent;
    }

    public function durationLabel(): string
    {
        if ($this->worked_minutes === null) {
            return '—';
        }

        $hours = intdiv($this->worked_minutes, 60);
        $minutes = $this->worked_minutes % 60;

        if ($hours === 0) {
            return "{$minutes}m";
        }

        return "{$hours}h {$minutes}m";
    }
}
