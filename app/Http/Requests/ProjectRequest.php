<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:due_date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Project name is required',
            'name.max' => 'Project name cannot exceed 255 characters',
            'end_date.after_or_equal' => 'End date must be after or equal to due date',
        ];
    }
}
