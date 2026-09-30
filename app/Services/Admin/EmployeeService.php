<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class EmployeeService
{
    /* get all users for the authenticated admin's tenant */
    public function list(): array
    {
        try {
            $user = Auth::user();

            $users = User::where('tenant_id', $user->tenant_id)
                ->where('role', '!=', 'admin')
                ->latest()
                ->get();

            return [
                'success' => true,
                'message' => 'Users retrieved successfully.',
                'data' => $users,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve users.',
                'status' => 500,
            ];
        }
    }

    /* create a new employee for the authenticated admin's tenant */
    public function create(array $data): array
    {
        try {
            $user = Auth::user();

            $employee = User::create([
                'tenant_id' => $user->tenant_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'password' => Hash::make($data['password']),
                'role' => 'employee',
            ]);

            return [
                'success' => true,
                'message' => 'Employee created successfully.',
                'data' => $employee,
                'status' => 201,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to create employee.',
                'status' => 500,
            ];
        }
    }

    /* get a user from the authenticated admin's tenant */
    public function find(int $id): array
    {
        try {
            $user = Auth::user();

            $employee = User::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->first();

            if (!$employee) {
                return [
                    'success' => false,
                    'message' => 'User not found.',
                    'status' => 404,
                ];
            }

            return [
                'success' => true,
                'message' => 'User retrieved successfully.',
                'data' => $employee,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve user.',
                'status' => 500,
            ];
        }
    }

    /* update an employee from the authenticated admin's tenant */
    public function update(int $id, array $data): array
    {
        try {
            $user = Auth::user();

            $employee = User::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->where('role', 'employee')
                ->first();

            if (!$employee) {
                return [
                    'success' => false,
                    'message' => 'Employee not found.',
                    'status' => 404,
                ];
            }

            $employee->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
            ]);

            /* update password only when a new password is provided */
            if (!empty($data['password'])) {
                $employee->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Employee updated successfully.',
                'data' => $employee->fresh(),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to update employee.',
                'status' => 500,
            ];
        }
    }
}