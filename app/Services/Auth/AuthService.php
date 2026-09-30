<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthService
{
    /* login api used for both admin and user */
    public function login(array $credentials): array
    {
        try {
            if (!Auth::attempt($credentials)) {
                return [
                    'success' => false,
                    'message' => 'Invalid email or password.',
                    'status' => 401,
                ];
            }

            request()->session()->regenerate();

            return [
                'success' => true,
                'message' => 'Login successful.',
                'data' => Auth::user(),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to login at this time.',
                'status' => 500,
            ];
        }
    }

    /* logout api */
    public function logout(): array
    {
        try {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return [
                'success' => true,
                'message' => 'Logout successful.',
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to logout at this time.',
                'status' => 500,
            ];
        }
    }
}