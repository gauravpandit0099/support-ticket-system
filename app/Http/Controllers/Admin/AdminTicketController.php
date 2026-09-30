<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminTicketRequest;
use App\Http\Requests\Admin\AdminTicketStatusRequest;
use App\Http\Requests\Admin\AdminTicketUpdateRequest;
use App\Services\Admin\AdminTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminTicketController extends Controller
{
    public function __construct(
        private AdminTicketService $ticketService
    ) {
    }

    /* show all tickets for the admin's tenant */
    public function index(): View|JsonResponse
    {
        $ticketResult = $this->ticketService->list();
        $employeeResult = $this->ticketService->employees();

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $ticketResult['success'],
                'message' => $ticketResult['message'],
                'data' => [
                    'tickets' => $ticketResult['data'] ?? [],
                    'employees' => $employeeResult['data'] ?? [],
                ],
            ], $ticketResult['status']);
        }

        return view('admin.tickets.admin-ticket-list', [
            'tickets' => $ticketResult['data'] ?? collect(),
            'employees' => $employeeResult['data'] ?? collect(),
        ]);
    }

    /* show ticket details for editing */
    public function edit(int $id): View|JsonResponse
    {
        $result = $this->ticketService->find($id);

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ], $result['status']);
        }

        /* stop when ticket is not found */
        if (!$result['success']) {
            abort($result['status'], $result['message']);
        }

        return view('admin.tickets.admin-ticket-edit', [
            'ticket' => $result['data'],
        ]);
    }

    /* assign a ticket to an employee */
    public function assign(
        AdminTicketRequest $request,
        int $id
    ): RedirectResponse|JsonResponse {
        $result = $this->ticketService->assign(
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

        /* redirect after successful assignment */
        if ($result['success']) {
            return redirect()
                ->route('admin.tickets.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /* update ticket details */
    public function update(
        AdminTicketUpdateRequest $request,
        int $id
    ): RedirectResponse|JsonResponse {
        $result = $this->ticketService->update(
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
                ->route('admin.tickets.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /* update ticket status */
    public function updateStatus(
        AdminTicketStatusRequest $request,
        int $id
    ): RedirectResponse|JsonResponse {
        $result = $this->ticketService->updateStatus(
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

        /* redirect after successful status update */
        if ($result['success']) {
            return redirect()
                ->route('admin.tickets.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }
}