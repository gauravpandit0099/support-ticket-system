<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /* allow authenticated admins to manage employees */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /* validate employee data */
    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $employeeId = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employeeId),
            ],

            'mobile' => [
                'required',
                'string',
                'max:20',
            ],

            'password' => $isUpdate
                ? [
                    'nullable',
                    'string',
                    'min:8',
                    'max:255',
                ]
                : [
                    'required',
                    'string',
                    'min:8',
                    'max:255',
                ],

            'role' => $isUpdate
                ? [
                    'sometimes',
                    Rule::in([
                        'employee',
                    ]),
                ]
                : [
                    'required',
                    Rule::in([
                        'employee',
                    ]),
                ],
        ];
    }

    /* custom validation messages */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',

            'mobile.required' => 'Mobile number is required.',
            'mobile.max' => 'Mobile number cannot exceed 20 characters.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password cannot exceed 255 characters.',

            'role.required' => 'User role is required.',
            'role.in' => 'Only employee users can be managed.',
        ];
    }
}