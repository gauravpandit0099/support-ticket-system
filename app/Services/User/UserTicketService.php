<?php

namespace App\Services\User;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserTicketService
{
    /* get all tickets assigned to the authenticated employee */
    public function list(): array
    {
        try {
            $user = Auth::user();

            $tickets = Ticket::where('tenant_id', $user->tenant_id)
                ->where('assigned_to', $user->id)
                ->with(['creator', 'assignee'])
                ->latest()
                ->get();

            return [
                'success' => true,
                'message' => 'Assigned tickets retrieved successfully.',
                'data' => $tickets,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve assigned tickets.',
                'status' => 500,
            ];
        }
    }

    /* get an assigned ticket for the authenticated employee */
    public function find(int $id): array
    {
        try {
            $user = Auth::user();

            $ticket = Ticket::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->where('assigned_to', $user->id)
                ->with(['creator', 'assignee'])
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found or not assigned to you.',
                    'status' => 404,
                ];
            }

            return [
                'success' => true,
                'message' => 'Ticket retrieved successfully.',
                'data' => $ticket,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve ticket.',
                'status' => 500,
            ];
        }
    }

    /* update the status of an assigned ticket */
    public function updateStatus(int $id, array $data): array
    {
        try {
            $user = Auth::user();

            $ticket = Ticket::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->where('assigned_to', $user->id)
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found or not assigned to you.',
                    'status' => 404,
                ];
            }

            $ticket->update([
                'status' => $data['status'],
            ]);

            return [
                'success' => true,
                'message' => 'Ticket status updated successfully.',
                'data' => $ticket->fresh(),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to update ticket status.',
                'status' => 500,
            ];
        }
    }
}