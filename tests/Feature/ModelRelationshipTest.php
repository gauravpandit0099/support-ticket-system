<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\TicketRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_has_users(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $this->assertTrue(
            $tenant->users->contains($user)
        );
    }

    public function test_ticket_request_belongs_to_tenant_and_creator(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $request = TicketRequest::factory()->create([
            'tenant_id' => $tenant->id,
            'created_by' => $user->id,
        ]);

        $this->assertEquals(
            $tenant->id,
            $request->tenant->id
        );

        $this->assertEquals(
            $user->id,
            $request->creator->id
        );
    }

    public function test_ticket_belongs_to_request_and_assignee(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        $request = TicketRequest::factory()->create([
            'tenant_id' => $tenant->id,
            'created_by' => $employee->id,
            'reviewed_by' => $admin->id,
            'status' => 'approved',
        ]);

        $ticket = Ticket::factory()->create([
            'tenant_id' => $tenant->id,
            'request_id' => $request->id,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
        ]);

        $this->assertEquals(
            $request->id,
            $ticket->request->id
        );

        $this->assertEquals(
            $employee->id,
            $ticket->assignee->id
        );
    }
}