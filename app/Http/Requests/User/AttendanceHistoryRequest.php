<?php

namespace App\Http\Requests\User;

use App\Enums\AttendanceHistoryRange;
use App\Support\AppPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(AppPermission::ATTENDANCE_ACCESS) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'range' => ['sometimes', 'string', Rule::enum(AttendanceHistoryRange::class)],
        ];
    }

    public function range(): AttendanceHistoryRange
    {
        return AttendanceHistoryRange::fromRequest($this->query('range'));
    }
}
