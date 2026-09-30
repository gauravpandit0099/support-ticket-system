<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminTicketRequest extends FormRequest
{
    /* allow authenticated admins to assign tickets */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /* validate ticket assignment data */
    public function rules(): array
    {
        return [
            'assigned_to' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query
                        ->where('tenant_id', $this->user()?->tenant_id)
                        ->where('role', 'employee');
                }),
            ],
        ];
    }

    /* custom validation messages */
    public function messages(): array
    {
        return [
            'assigned_to.required' => 'Please select an employee.',
            'assigned_to.integer' => 'Invalid employee selected.',
            'assigned_to.exists' => 'The selected employee is not available.',
        ];
    }
}