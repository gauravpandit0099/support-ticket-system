<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserTicketStatusRequest;
use App\Services\User\UserTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserTicketController extends Controller
{
    public function __construct(
        private UserTicketService $ticketService
    ) {
    }

    /* show tickets assigned to the authenticated employee */
    public function index(): View|JsonResponse
    {
        $result = $this->ticketService->list();

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? [],
            ], $result['status']);
        }

        return view('user.tickets.user-ticket-list', [
            'tickets' => $result['data'] ?? collect(),
        ]);
    }

    /* show assigned ticket details */
    public function show(int $id): View|JsonResponse
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

        return view('user.tickets.user-ticket-show', [
            'ticket' => $result['data'],
        ]);
    }

    /* update status of an assigned ticket */
    public function updateStatus(
        UserTicketStatusRequest $request,
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

        /* stop when ticket is not assigned to the employee */
        if (!$result['success']) {
            abort($result['status'], $result['message']);
        }

        /* redirect after successful status update */
        return redirect()
            ->route('user.tickets.index')
            ->with('success', $result['message']);
    }
}