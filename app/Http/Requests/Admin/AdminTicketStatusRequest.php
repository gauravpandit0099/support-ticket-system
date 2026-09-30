<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminTicketStatusRequest extends FormRequest
{
    /* allow authenticated admins to update ticket status */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /* validate ticket status */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    'open',
                    'in_progress',
                    'resolved',
                ]),
            ],
        ];
    }

    /* custom validation messages */
    public function messages(): array
    {
        return [
            'status.required' => 'Ticket status is required.',
            'status.in' => 'Please select a valid ticket status.',
        ];
    }
}