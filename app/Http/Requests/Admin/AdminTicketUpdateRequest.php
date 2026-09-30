<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminTicketUpdateRequest extends FormRequest
{
    /* allow authenticated admins to update ticket details */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /* validate ticket update data */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
                'max:5000',
            ],

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