<?php

namespace Tests\Feature\User;

use App\Models\Tenant;
use App\Models\TicketRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketRequestTest extends TestCase
{
    use RefreshDatabase;

    /* create employee for tests */
    private function createEmployee(?int $tenantId = null): User
    {
        $tenantId ??= Tenant::factory()->create()->id;

        return User::factory()->create([
            'tenant_id' => $tenantId,
            'role' => 'employee',
        ]);
    }

    /* verify employee can access ticket request form */
    public function test_employee_can_access_ticket_request_form(): void
    {
        $employee = $this->createEmployee();

        $response = $this
            ->actingAs($employee)
            ->get(route('user.ticket-requests.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.ticket-requests.create');
    }

    /* verify ticket request validation */
    public function test_ticket_request_requires_required_fields(): void
    {
        $employee = $this->createEmployee();

        $response = $this
            ->actingAs($employee)
            ->post(route('user.ticket-requests.store'), []);

        $response->assertSessionHasErrors([
            'title',
            'description',
            'priority',
        ]);

        $this->assertDatabaseCount('ticket_requests', 0);
    }

    /* verify employee can create a ticket request */
    public function test_employee_can_create_ticket_request(): void
    {
        $employee = $this->createEmployee();

        $response = $this
            ->actingAs($employee)
            ->post(route('user.ticket-requests.store'), [
                'title' => 'Laptop not connecting to Wi-Fi',
                'description' => 'Unable to connect to the office Wi-Fi network.',
                'priority' => 'high',
            ]);

        $response->assertRedirect(route('user.ticket-requests.index'));

        $this->assertDatabaseHas('ticket_requests', [
            'tenant_id' => $employee->tenant_id,
            'created_by' => $employee->id,
            'title' => 'Laptop not connecting to Wi-Fi',
            'priority' => 'high',
            'status' => 'pending',
        ]);
    }

    /* verify admin cannot access employee ticket request form */
    public function test_admin_cannot_access_ticket_request_form(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('user.ticket-requests.create'));

        $response->assertForbidden();
    }

    /* verify guest cannot access ticket request form */
    public function test_guest_cannot_access_ticket_request_form(): void
    {
        $response = $this->get(route('user.ticket-requests.create'));

        $response->assertRedirect(route('login'));
    }

    /* verify employee can access edit form for own pending request */
    public function test_employee_can_access_edit_form_for_own_pending_request(): void
    {
        $employee = $this->createEmployee();

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $employee->tenant_id,
            'created_by' => $employee->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($employee)
            ->get(route('user.ticket-requests.edit', $ticketRequest->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.ticket-requests.user-ticket-request-edit');
        $response->assertSee($ticketRequest->title);
    }

    /* verify employee can update own pending request */
    public function test_employee_can_update_own_pending_request(): void
    {
        $employee = $this->createEmployee();

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $employee->tenant_id,
            'created_by' => $employee->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($employee)
            ->put(route('user.ticket-requests.update', $ticketRequest->id), [
                'title' => 'Updated laptop issue',
                'description' => 'The laptop issue has been updated.',
                'priority' => 'urgent',
            ]);

        $response->assertRedirect(route('user.ticket-requests.index'));

        $this->assertDatabaseHas('ticket_requests', [
            'id' => $ticketRequest->id,
            'tenant_id' => $employee->tenant_id,
            'created_by' => $employee->id,
            'title' => 'Updated laptop issue',
            'description' => 'The laptop issue has been updated.',
            'priority' => 'urgent',
            'status' => 'pending',
        ]);
    }

    /* verify employee cannot update another employee's request */
    public function test_employee_cannot_update_another_employees_request(): void
    {
        $employee = $this->createEmployee();

        $otherEmployee = $this->createEmployee($employee->tenant_id);

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $otherEmployee->tenant_id,
            'created_by' => $otherEmployee->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($employee)
            ->put(route('user.ticket-requests.update', $ticketRequest->id), [
                'title' => 'Unauthorized update',
                'description' => 'This should not be allowed.',
                'priority' => 'urgent',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('ticket_requests', [
            'id' => $ticketRequest->id,
            'created_by' => $otherEmployee->id,
            'title' => $ticketRequest->title,
            'status' => 'pending',
        ]);
    }

    /* verify employee cannot update an already reviewed request */
    public function test_employee_cannot_update_reviewed_request(): void
    {
        $employee = $this->createEmployee();

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $employee->tenant_id,
            'created_by' => $employee->id,
            'status' => 'approved',
        ]);

        $response = $this
            ->actingAs($employee)
            ->put(route('user.ticket-requests.update', $ticketRequest->id), [
                'title' => 'Unauthorized update',
                'description' => 'This should not be allowed.',
                'priority' => 'urgent',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('ticket_requests', [
            'id' => $ticketRequest->id,
            'title' => $ticketRequest->title,
            'status' => 'approved',
        ]);
    }
}