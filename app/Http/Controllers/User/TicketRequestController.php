<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\TicketRequestRequest;
use App\Services\User\TicketRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketRequestController extends Controller
{
    public function __construct(
        private TicketRequestService $ticketRequestService
    ) {
    }

    /* show employee ticket requests */
    public function index(): View
    {
        $result = $this->ticketRequestService->list();

        return view('user.ticket-requests.index', [
            'ticketRequests' => $result['data'] ?? collect(),
        ]);
    }

    /* show ticket request creation form */
    public function create(): View
    {
        return view('user.ticket-requests.create');
    }

    /* create a new ticket request */
    public function store(
        TicketRequestRequest $request
    ): RedirectResponse|JsonResponse {
        $result = $this->ticketRequestService->create(
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

        /* redirect employee after successful submission */
        if ($result['success']) {
            return redirect()
                ->route('user.ticket-requests.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /* show ticket request edit form */
    public function edit(int $id): View|RedirectResponse|JsonResponse
    {
        $result = $this->ticketRequestService->findForEdit($id);

        /* return api response when requested */
        if (request()->expectsJson()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ], $result['status']);
        }

        /* handle request that cannot be edited */
        if (!$result['success']) {
            return redirect()
                ->route('user.ticket-requests.index')
                ->with('error', $result['message']);
        }

        return view('user.ticket-requests.user-ticket-request-edit', [
            'ticketRequest' => $result['data'],
        ]);
    }

    /* update employee ticket request */
    public function update(
        TicketRequestRequest $request,
        int $id
    ): RedirectResponse|JsonResponse {
        $result = $this->ticketRequestService->update(
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
                ->route('user.ticket-requests.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }
}