<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Services\Admin\TicketRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketRequestController extends Controller
{
    public function __construct(
        private TicketRequestService $ticketRequestService
    ) {
    }

    /* show all ticket requests for the admin's tenant */
    public function index(): View|JsonResponse
    {
        $result = $this->ticketRequestService->list();

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ], $result['status']);
        }

        /* show ticket requests in admin panel */
        return view('admin.ticket-requests.admin-ticket-requests-list', [
            'ticketRequests' => $result['data'] ?? collect(),
        ]);
    }

    /* show a ticket request for admin review */
    public function show(int $id): View|RedirectResponse|JsonResponse
    {
        $result = $this->ticketRequestService->find($id);

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ], $result['status']);
        }

        /* handle missing ticket request */
        if (!$result['success']) {
            return redirect()
                ->route('admin.ticket-requests.index')
                ->with('error', $result['message']);
        }

        return view('admin.ticket-requests.admin-ticket-request-review', [
            'ticketRequest' => $result['data'],
        ]);
    }

    /* approve or reject a ticket request */
    public function review(
        AdminRequest $request,
        int $id
    ): RedirectResponse|JsonResponse {
        $result = $this->ticketRequestService->review(
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

        /* redirect after successful review */
        if ($result['success']) {
            return redirect()
                ->route('admin.ticket-requests.index')
                ->with('success', $result['message']);
        }

        return back()
            ->with('error', $result['message']);
    }
}