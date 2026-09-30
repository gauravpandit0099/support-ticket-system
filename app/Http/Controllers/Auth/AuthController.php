<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    /* show login page */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /* handle login request */
    public function login(LoginRequest $request): RedirectResponse|JsonResponse
    {
        $result = $this->authService->login($request->validated());

        /* handle failed login */
        if (!$result['success']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], $result['status']);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => $result['message'],
                ]);
        }

        $user = $result['data'];

        /* determine dashboard according to user role */
        $dashboardRoute = match ($user->role) {
            'admin' => 'admin.dashboard',
            'employee' => 'user.dashboard',
            default => null,
        };

        /* prevent users with invalid roles from continuing */
        if (!$dashboardRoute) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Your account does not have a valid role.',
                ]);
        }

        /* return api response when requested */
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $user,
                'redirect' => route($dashboardRoute),
            ], $result['status']);
        }

        /* redirect user to role-specific dashboard */
        return redirect()
            ->route($dashboardRoute)
            ->with('success', $result['message']);
    }

    /* logout authenticated user */
    public function logout(): RedirectResponse|JsonResponse
    {
        $result = $this->authService->logout();

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
            ], $result['status']);
        }

        /* redirect to login after logout */
        return redirect()
            ->route('login')
            ->with('success', $result['message']);
    }
}