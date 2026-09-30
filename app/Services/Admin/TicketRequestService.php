<?php

namespace App\Services\Admin;

use App\Models\Ticket;
use App\Models\TicketRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class TicketRequestService
{
    /* get all ticket requests for the authenticated admin's tenant */
    public function list(): array
    {
        try {
            $user = Auth::user();

            $ticketRequests = TicketRequest::where('tenant_id', $user->tenant_id)
                ->with('creator')
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

    /* get a ticket request for admin review */
    public function find(int $id): array
    {
        try {
            $user = Auth::user();

            $ticketRequest = TicketRequest::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->with('creator')
                ->first();

            if (!$ticketRequest) {
                return [
                    'success' => false,
                    'message' => 'Ticket request not found.',
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

    /* approve or reject a pending ticket request */
    public function review(int $id, array $data): array
    {
        try {
            $user = Auth::user();

            $result = DB::transaction(function () use ($id, $data, $user) {

                /* find only pending requests from the admin's tenant */
                $ticketRequest = TicketRequest::where('id', $id)
                    ->where('tenant_id', $user->tenant_id)
                    ->where('status', 'pending')
                    ->first();

                if (!$ticketRequest) {
                    return [
                        'success' => false,
                        'message' => 'Ticket request not found or has already been reviewed.',
                        'status' => 404,
                    ];
                }

                /* update request review information */
                $ticketRequest->update([
                    'status' => $data['status'],
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                    'rejection_reason' => $data['status'] === 'rejected'
                        ? ($data['rejection_reason'] ?? null)
                        : null,
                ]);

                /* create a ticket automatically after approval */
                if ($data['status'] === 'approved') {
                    Ticket::create([
                        'tenant_id' => $ticketRequest->tenant_id,
                        'request_id' => $ticketRequest->id,
                        'created_by' => $ticketRequest->created_by,
                        'title' => $ticketRequest->title,
                        'description' => $ticketRequest->description,
                        'priority' => $ticketRequest->priority,
                        'status' => 'open',
                    ]);
                }

                return [
                    'success' => true,
                    'message' => $data['status'] === 'approved'
                        ? 'Ticket request approved and ticket created successfully.'
                        : 'Ticket request rejected successfully.',
                    'data' => $ticketRequest->fresh(),
                    'status' => 200,
                ];
            });

            return $result;
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to review ticket request.',
                'status' => 500,
            ];
        }
    }
}