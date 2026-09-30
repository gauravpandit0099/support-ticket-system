<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    /* allow authenticated admins to review ticket requests */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /* validate ticket request review data */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    'approved',
                    'rejected',
                ]),
            ],
            'rejection_reason' => [
                'nullable',
                'string',
                'max:1000',
                'required_if:status,rejected',
            ],
        ];
    }

    /* custom validation messages */
    public function messages(): array
    {
        return [
            'status.required' => 'Review status is required.',
            'status.in' => 'Please select a valid review status.',
            'rejection_reason.required_if' => 'Rejection reason is required when rejecting a request.',
            'rejection_reason.max' => 'Rejection reason cannot exceed 1000 characters.',
        ];
    }
}