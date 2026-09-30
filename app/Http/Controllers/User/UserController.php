<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    /* get authenticated employee details */
    public function profile(): JsonResponse
    {
        $result = $this->userService->profile();

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['status']);
    }

    /* show employee dashboard */
    public function dashboard(): View
    {
        $result = $this->userService->dashboard();

        return view('user.dashboard', $result['data']);
    }
}