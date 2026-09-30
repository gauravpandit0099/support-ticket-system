<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;
use Throwable;

class AdminService
{
    /* get authenticated admin details */
    public function profile(): array
    {
        try {
            return [
                'success' => true,
                'message' => 'Admin details retrieved successfully.',
                'data' => Auth::user(),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve admin details.',
                'status' => 500,
            ];
        }
    }

    /* get admin dashboard data */
    public function dashboard(): array
    {
        try {
            $user = Auth::user()->load('tenant');

            return [
                'success' => true,
                'message' => 'Admin dashboard data retrieved successfully.',
                'data' => [
                    'user' => $user,
                ],
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to load admin dashboard.',
                'status' => 500,
            ];
        }
    }
}