<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmployeeRequest;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeService $employeeService
    ) {
    }

    /* show admin dashboard */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /* show all employees for the authenticated admin's tenant */
    public function users(): View|JsonResponse
    {
        $result = $this->employeeService->list();

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? [],
            ], $result['status']);
        }

        return view('admin.users.employee-list', [
            'users' => $result['data'] ?? collect(),
        ]);
    }

    /* show create employee form */
    public function createUser(): View
    {
        return view('admin.users.employee-create');
    }

    /* create a new employee */
    public function storeUser(
        EmployeeRequest $request
    ): RedirectResponse|JsonResponse {
        $result = $this->employeeService->create(
            $request->validated()
        );

        /* return api response when requested */
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ], $result['status']);
        }

        /* redirect after successful creation */
        if ($result['success']) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /* show employee edit form */
    public function editUser(int $id): View|JsonResponse
    {
        $result = $this->employeeService->find($id);

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ], $result['status']);
        }

        /* stop when employee is not found */
        if (!$result['success']) {
            abort($result['status'], $result['message']);
        }

        return view('admin.users.employee-edit', [
            'user' => $result['data'],
        ]);
    }

    /* update an employee */
    public function updateUser(
        EmployeeRequest $request,
        int $id
    ): RedirectResponse|JsonResponse {
        $result = $this->employeeService->update(
            $id,
            $request->validated()
        );

        /* return api response when requested */
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ], $result['status']);
        }

        /* redirect after successful update */
        if ($result['success']) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }
}