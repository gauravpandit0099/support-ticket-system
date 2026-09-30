<?php

namespace App\Services\User;

use App\Models\TicketRequest;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TicketRequestService
{
    /* get ticket requests created by the authenticated employee */
    public function list(): array
    {
        try {
            $user = Auth::user();

            $ticketRequests = TicketRequest::where('tenant_id', $user->tenant_id)
                ->where('created_by', $user->id)
                ->latest()
                ->get();

            return [
                'success' => true,
                'message' => 'Ticket requests retrieved successfully.',
                'data' => $ticketRequests,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve ticket requests.',
                'status' => 500,
            ];
        }
    }

    /* create a new ticket request for the authenticated employee */
    public function create(array $data): array
    {
        try {
            $user = Auth::user();

            $ticketRequest = TicketRequest::create([
                'tenant_id' => $user->tenant_id,
                'created_by' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'],
                'status' => 'pending',
            ]);

            return [
                'success' => true,
                'message' => 'Ticket request submitted successfully.',
                'data' => $ticketRequest,
                'status' => 201,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to submit ticket request.',
                'status' => 500,
            ];
        }
    }

    /* get a pending ticket request owned by the authenticated employee */
    public function findForEdit(int $id): array
    {
        try {
            $user = Auth::user();

            $ticketRequest = TicketRequest::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->where('created_by', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$ticketRequest) {
                return [
                    'success' => false,
                    'message' => 'Ticket request not found or cannot be edited.',
                    'status' => 404,
                ];
            }

            return [
                'success' => true,
                'message' => 'Ticket request retrieved successfully.',
                'data' => $ticketRequest,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve ticket request.',
                'status' => 500,
            ];
        }
    }

    /* update a pending ticket request owned by the authenticated employee */
    public function update(int $id, array $data): array
    {
        try {
            $user = Auth::user();

            $ticketRequest = TicketRequest::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->where('created_by', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$ticketRequest) {
                return [
                    'success' => false,
                    'message' => 'Ticket request not found or cannot be updated.',
                    'status' => 404,
                ];
            }

            $ticketRequest->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'],
            ]);

            return [
                'success' => true,
                'message' => 'Ticket request updated successfully.',
                'data' => $ticketRequest->fresh(),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to update ticket request.',
                'status' => 500,
            ];
        }
    }
}