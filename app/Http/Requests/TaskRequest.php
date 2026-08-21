<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => 'nullable|exists:projects,id',
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|exists:task_statuses,name',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'summary.required' => 'Task summary is required',
            'summary.max' => 'Task summary cannot exceed 255 characters',
            'priority.required' => 'Priority is required',
            'status.required' => 'Status is required',
        ];
    }
}
