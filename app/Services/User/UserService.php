<?php

namespace App\Services\User;

use App\Models\Ticket;
use App\Models\TicketRequest;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserService
{
    /* get authenticated employee details */
    public function profile(): array
    {
        try {
            return [
                'success' => true,
                'message' => 'Employee details retrieved successfully.',
                'data' => Auth::user(),
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to retrieve employee details.',
                'status' => 500,
            ];
        }
    }

    /* get employee dashboard data */
    public function dashboard(): array
    {
        try {
            $user = Auth::user()->load('tenant');

            /* count pending requests created by the employee */
            $openRequests = TicketRequest::where('tenant_id', $user->tenant_id)
                ->where('created_by', $user->id)
                ->where('status', 'pending')
                ->count();

            /* count tickets currently assigned to the employee */
            $assignedTickets = Ticket::where('tenant_id', $user->tenant_id)
                ->where('assigned_to', $user->id)
                ->whereIn('status', ['open', 'in_progress'])
                ->count();

            /* count resolved tickets assigned to the employee */
            $resolvedTickets = Ticket::where('tenant_id', $user->tenant_id)
                ->where('assigned_to', $user->id)
                ->where('status', 'resolved')
                ->count();

            return [
                'success' => true,
                'message' => 'Employee dashboard data retrieved successfully.',
                'data' => [
                    'user' => $user,
                    'openRequests' => $openRequests,
                    'assignedTickets' => $assignedTickets,
                    'resolvedTickets' => $resolvedTickets,
                ],
                'status' => 200,
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'success' => false,
                'message' => 'Unable to load employee dashboard.',
                'status' => 500,
            ];
        }
    }
}