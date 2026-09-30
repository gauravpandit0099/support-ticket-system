<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserTicketStatusRequest extends FormRequest
{
    /* allow authenticated employees to update assigned ticket status */
    public function authorize(): bool
    {
        return $this->user()?->role === 'employee';
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