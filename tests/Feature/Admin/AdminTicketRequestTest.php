<?php

namespace Tests\Feature\Admin;

use App\Models\Tenant;
use App\Models\TicketRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTicketRequestTest extends TestCase
{
    use RefreshDatabase;

    /* create admin for tests */
    private function createAdmin(): User
    {
        $tenant = Tenant::factory()->create();

        return User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);
    }

    /* create employee for tests */
    private function createEmployee(int $tenantId): User
    {
        return User::factory()->create([
            'tenant_id' => $tenantId,
            'role' => 'employee',
        ]);
    }

    /* create ticket request for tests */
    private function createTicketRequest(User $employee): TicketRequest
    {
        return TicketRequest::factory()->create([
            'tenant_id' => $employee->tenant_id,
            'created_by' => $employee->id,
            'status' => 'pending',
        ]);
    }

    /* verify admin can access ticket request list */
    public function test_admin_can_access_ticket_request_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.ticket-requests.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.ticket-requests.admin-ticket-requests-list');
    }

    /* verify admin can see requests from their own tenant */
    public function test_admin_can_see_requests_from_own_tenant(): void
    {
        $admin = $this->createAdmin();
        $employee = $this->createEmployee($admin->tenant_id);
        $ticketRequest = $this->createTicketRequest($employee);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.ticket-requests.index'));

        $response->assertStatus(200);
        $response->assertSee($ticketRequest->title);
    }

    /* verify admin cannot see requests from another tenant */
    public function test_admin_cannot_see_requests_from_another_tenant(): void
    {
        $admin = $this->createAdmin();

        $otherTenant = Tenant::factory()->create();
        $otherEmployee = $this->createEmployee($otherTenant->id);
        $otherRequest = $this->createTicketRequest($otherEmployee);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.ticket-requests.index'));

        $response->assertStatus(200);
        $response->assertDontSee($otherRequest->title);
    }

    /* verify employee cannot access admin ticket request list */
    public function test_employee_cannot_access_admin_ticket_request_list(): void
    {
        $admin = $this->createAdmin();
        $employee = $this->createEmployee($admin->tenant_id);

        $response = $this
            ->actingAs($employee)
            ->get(route('admin.ticket-requests.index'));

        $response->assertForbidden();
    }

    /* verify guest cannot access admin ticket request list */
    public function test_guest_cannot_access_admin_ticket_request_list(): void
    {
        $response = $this->get(route('admin.ticket-requests.index'));

        $response->assertRedirect(route('login'));
    }

    /* verify admin can access ticket request review page */
    public function test_admin_can_access_ticket_request_review_page(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.ticket-requests.show', $ticketRequest->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.ticket-requests.admin-ticket-request-review');
    }

    /* verify admin can approve a pending ticket request */
    public function test_admin_can_approve_ticket_request(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.ticket-requests.review', $ticketRequest->id), [
                'status' => 'approved',
            ]);

        $response->assertRedirect(route('admin.ticket-requests.index'));

        $this->assertDatabaseHas('ticket_requests', [
            'id' => $ticketRequest->id,
            'tenant_id' => $tenant->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
        ]);
    }

    /* verify admin can reject a pending ticket request */
    public function test_admin_can_reject_ticket_request(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.ticket-requests.review', $ticketRequest->id), [
                'status' => 'rejected',
                'rejection_reason' => 'Insufficient information provided.',
            ]);

        $response->assertRedirect(route('admin.ticket-requests.index'));

        $this->assertDatabaseHas('ticket_requests', [
            'id' => $ticketRequest->id,
            'tenant_id' => $tenant->id,
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'rejection_reason' => 'Insufficient information provided.',
        ]);
    }

    /* verify admin cannot review a request from another tenant */
    public function test_admin_cannot_review_another_tenants_request(): void
    {
        $adminTenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $adminTenant->id,
            'role' => 'admin',
        ]);

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $otherTenant->id,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.ticket-requests.review', $ticketRequest->id), [
                'status' => 'approved',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('ticket_requests', [
            'id' => $ticketRequest->id,
            'tenant_id' => $otherTenant->id,
            'status' => 'pending',
            'reviewed_by' => null,
        ]);
    }

    /* verify reviewed ticket request cannot be reviewed again */
    public function test_admin_cannot_review_already_reviewed_request(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $ticketRequest = TicketRequest::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'approved',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.ticket-requests.review', $ticketRequest->id), [
                'status' => 'rejected',
                'rejection_reason' => 'This should not be allowed.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('ticket_requests', [
            'id' => $ticketRequest->id,
            'status' => 'approved',
        ]);
    }
}