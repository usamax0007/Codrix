<?php

namespace App\Http\Requests\User;

use App\Support\AppPermission;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Http\FormRequest;

abstract class AttendancePunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(AppPermission::ATTENDANCE_ACCESS) ?? false;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'occurred_at' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }

    public function messages(): array
    {
        return [
            'occurred_at.required' => 'Device time is required. Please enable JavaScript and try again.',
            'occurred_at.date_format' => 'Invalid device time format.',
        ];
    }

    public function occurredAt(): CarbonInterface
    {
        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->validated('occurred_at'),
        )->settings(['monthOverflow' => false]);
    }
}
