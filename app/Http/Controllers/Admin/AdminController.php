<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {
    }

    /* get authenticated admin details */
    public function profile(): JsonResponse
    {
        $result = $this->adminService->profile();

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['status']);
    }

    /* show admin dashboard */
    public function dashboard(): View
    {
        $result = $this->adminService->dashboard();

        return view('admin.dashboard', $result['data']);
    }
}