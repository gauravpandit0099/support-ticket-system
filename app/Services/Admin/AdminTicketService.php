<?php

namespace App\Services\Admin;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AdminTicketService
{
    /* get all tickets for the authenticated admin's tenant */
    public function list(): array
    {
        try {
            $user = Auth::user();

            $tickets = Ticket::where('tenant_id', $user->tenant_id)
                ->with(['creator', 'assignee'])
                ->latest()
                ->get();

            return [
                'success' => true,
                'message' => 'Tickets retrieved successfully.',
                'data' => $tickets,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve tickets.',
                'status' => 500,
            ];
        }
    }

    /* get employees for the authenticated admin's tenant */
    public function employees(): array
    {
        try {
            $user = Auth::user();

            $employees = User::where('tenant_id', $user->tenant_id)
                ->where('role', 'employee')
                ->orderBy('name')
                ->get();

            return [
                'success' => true,
                'message' => 'Employees retrieved successfully.',
                'data' => $employees,
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve employees.',
                'status' => 500,
            ];
        }
    }

    /* get a single ticket for the authenticated admin's tenant */
    public function find(int $id): array
    {
        try {
            $user = Auth::user();

            $ticket = Ticket::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->with(['creator', 'assignee'])
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found.',
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

    /* assign a ticket to an employee in the same tenant */
    public function assign(int $id, array $data): array
    {
        try {
            $user = Auth::user();

            $ticket = Ticket::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found.',
                    'status' => 404,
                ];
            }

            $employeeExists = User::where('id', $data['assigned_to'])
                ->where('tenant_id', $user->tenant_id)
                ->where('role', 'employee')
                ->exists();

            if (!$employeeExists) {
                return [
                    'success' => false,
                    'message' => 'Selected employee is not available.',
                    'status' => 422,
                ];
            }

            $ticket->update([
                'assigned_to' => $data['assigned_to'],
            ]);

            return [
                'success' => true,
                'message' => 'Ticket assigned successfully.',
                'data' => $ticket->fresh(['creator', 'assignee']),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to assign ticket.',
                'status' => 500,
            ];
        }
    }

    /* update ticket details for the authenticated admin's tenant */
    public function update(int $id, array $data): array
    {
        try {
            $user = Auth::user();

            $ticket = Ticket::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found.',
                    'status' => 404,
                ];
            }

            $ticket->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'],
            ]);

            return [
                'success' => true,
                'message' => 'Ticket updated successfully.',
                'data' => $ticket->fresh(['creator', 'assignee']),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to update ticket.',
                'status' => 500,
            ];
        }
    }

    /* update ticket status for the authenticated admin's tenant */
    public function updateStatus(int $id, array $data): array
    {
        try {
            $user = Auth::user();

            $ticket = Ticket::where('id', $id)
                ->where('tenant_id', $user->tenant_id)
                ->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found.',
                    'status' => 404,
                ];
            }

            $ticket->update([
                'status' => $data['status'],
            ]);

            return [
                'success' => true,
                'message' => 'Ticket status updated successfully.',
                'data' => $ticket->fresh(['creator', 'assignee']),
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