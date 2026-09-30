<?php

namespace Tests\Feature\User;

use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTicketTest extends TestCase
{
    use RefreshDatabase;

    /* employee can view tickets assigned to them */
    public function test_employee_can_view_assigned_tickets(): void
    {
        /* create tenant and employee */
        $tenant = Tenant::factory()->create();

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        /* create assigned ticket */
        $ticket = Ticket::factory()->create([
            'tenant_id' => $tenant->id,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($employee)
            ->get(route('user.tickets.index'));

        $response->assertOk();
        $response->assertSee($ticket->title);
    }

    /* employee cannot view another employee's ticket */
    public function test_employee_cannot_view_another_employees_ticket(): void
    {
        /* create tenant and employees */
        $tenant = Tenant::factory()->create();

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        $otherEmployee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        /* create ticket assigned to another employee */
        $ticket = Ticket::factory()->create([
            'tenant_id' => $tenant->id,
            'created_by' => $otherEmployee->id,
            'assigned_to' => $otherEmployee->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($employee)
            ->get(route('user.tickets.show', $ticket->id));

        $response->assertNotFound();
    }

    /* employee can update the status of an assigned ticket */
    public function test_employee_can_update_assigned_ticket_status(): void
    {
        /* create tenant and employee */
        $tenant = Tenant::factory()->create();

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        /* create assigned ticket */
        $ticket = Ticket::factory()->create([
            'tenant_id' => $tenant->id,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($employee)
            ->put(route('user.tickets.status', $ticket->id), [
                'status' => 'in_progress',
            ]);

        $response->assertRedirect(route('user.tickets.index'));

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'in_progress',
        ]);
    }

    /* employee cannot update another employee's ticket */
    public function test_employee_cannot_update_another_employees_ticket(): void
    {
        /* create tenant and employees */
        $tenant = Tenant::factory()->create();

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        $otherEmployee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        /* create ticket assigned to another employee */
        $ticket = Ticket::factory()->create([
            'tenant_id' => $tenant->id,
            'created_by' => $otherEmployee->id,
            'assigned_to' => $otherEmployee->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($employee)
            ->put(route('user.tickets.status', $ticket->id), [
                'status' => 'resolved',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'open',
        ]);
    }

    /* employee cannot update a ticket from another tenant */
    public function test_employee_cannot_access_ticket_from_another_tenant(): void
    {
        /* create separate tenants */
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        $otherEmployee = User::factory()->create([
            'tenant_id' => $otherTenant->id,
            'role' => 'employee',
        ]);

        /* create ticket in another tenant */
        $ticket = Ticket::factory()->create([
            'tenant_id' => $otherTenant->id,
            'created_by' => $otherEmployee->id,
            'assigned_to' => $otherEmployee->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($employee)
            ->get(route('user.tickets.show', $ticket->id));

        $response->assertNotFound();
    }

    /* invalid ticket status is rejected */
    public function test_invalid_ticket_status_is_rejected(): void
    {
        /* create tenant and employee */
        $tenant = Tenant::factory()->create();

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        /* create assigned ticket */
        $ticket = Ticket::factory()->create([
            'tenant_id' => $tenant->id,
            'created_by' => $employee->id,
            'assigned_to' => $employee->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($employee)
            ->put(route('user.tickets.status', $ticket->id), [
                'status' => 'invalid_status',
            ]);

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'open',
        ]);
    }
}