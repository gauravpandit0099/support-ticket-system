<?php

namespace Tests\Feature\Admin;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    /* admin can view employees from their tenant */
    public function test_admin_can_view_employees(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
            'name' => 'Test Employee',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Employee');
    }

    /* admin can create an employee */
    public function test_admin_can_create_employee(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New Employee',
                'email' => 'new.employee@example.com',
                'mobile' => '9876543210',
                'password' => 'password123',
                'role' => 'employee',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Employee created successfully.');

        $this->assertDatabaseHas('users', [
            'tenant_id' => $tenant->id,
            'name' => 'New Employee',
            'email' => 'new.employee@example.com',
            'mobile' => '9876543210',
            'role' => 'employee',
        ]);

        $employee = User::where('email', 'new.employee@example.com')->first();

        $this->assertTrue(
            Hash::check('password123', $employee->password)
        );
    }

    /* created employee belongs to the admin's tenant */
    public function test_created_employee_belongs_to_admin_tenant(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Tenant Employee',
                'email' => 'tenant.employee@example.com',
                'mobile' => '9876543210',
                'password' => 'password123',
                'role' => 'employee',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'tenant.employee@example.com',
            'tenant_id' => $tenant->id,
        ]);
    }

    /* admin can update an employee */
    public function test_admin_can_update_employee(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
            'name' => 'Old Name',
            'email' => 'old.employee@example.com',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $employee->id), [
                'name' => 'Updated Employee',
                'email' => 'updated.employee@example.com',
                'mobile' => '9999999999',
                'password' => '',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Employee updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'tenant_id' => $tenant->id,
            'name' => 'Updated Employee',
            'email' => 'updated.employee@example.com',
            'mobile' => '9999999999',
        ]);
    }

    /* admin cannot access an employee from another tenant */
    public function test_admin_cannot_access_employee_from_another_tenant(): void
    {
        $adminTenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $adminTenant->id,
            'role' => 'admin',
        ]);

        $employee = User::factory()->create([
            'tenant_id' => $otherTenant->id,
            'role' => 'employee',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.edit', $employee->id));

        $response->assertStatus(404);
    }

    /* duplicate email is rejected */
    public function test_duplicate_email_is_rejected(): void
    {
        $tenant = Tenant::factory()->create();

        $admin = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
            'email' => 'existing@example.com',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Another Employee',
                'email' => 'existing@example.com',
                'mobile' => '9876543210',
                'password' => 'password123',
                'role' => 'employee',
            ]);

        $response->assertSessionHasErrors('email');
    }

    /* non-admin cannot access user management */
    public function test_non_admin_cannot_access_user_management(): void
    {
        $tenant = Tenant::factory()->create();

        $employee = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
        ]);

        $response = $this->actingAs($employee)
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }
}