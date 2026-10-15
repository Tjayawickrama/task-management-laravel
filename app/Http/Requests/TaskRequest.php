<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date'    => ['nullable', 'date'],
            'status'      => ['required', Rule::enum(TaskStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a title for the task.',
            'title.max'      => 'The title may not be longer than 255 characters.',
            'due_date.date'  => 'Please enter a valid date.',
            'status.required' => 'Please choose a status.',
            'status.enum'    => 'Please choose a valid status.',
        ];
    }
}