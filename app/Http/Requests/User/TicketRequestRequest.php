<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketRequestRequest extends FormRequest
{
    /* allow authenticated employees to submit ticket requests */
    public function authorize(): bool
    {
        return $this->user()?->role === 'employee';
    }

    /* validate ticket request data */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'priority' => [
                'required',
                'string',
                Rule::in([
                    'low',
                    'medium',
                    'high',
                    'urgent',
                ]),
            ],
        ];
    }

    /* custom validation messages */
    public function messages(): array
    {
        return [
            'title.required' => 'Ticket title is required.',
            'title.max' => 'Ticket title cannot exceed 255 characters.',
            'description.required' => 'Ticket description is required.',
            'description.max' => 'Ticket description cannot exceed 5000 characters.',
            'priority.required' => 'Ticket priority is required.',
            'priority.in' => 'Please select a valid ticket priority.',
        ];
    }
}