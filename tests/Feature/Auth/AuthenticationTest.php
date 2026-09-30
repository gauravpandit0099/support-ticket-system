<?php

namespace Tests\Feature\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /* create tenant and admin user for tests */
    private function createAdmin(): User
    {
        $tenant = Tenant::factory()->create();

        return User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }

    /* create tenant and employee user for tests */
    private function createEmployee(): User
    {
        $tenant = Tenant::factory()->create();

        return User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'employee',
            'password' => Hash::make('password'),
        ]);
    }

    /* verify login page is accessible */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /* verify login validation */
    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post(route('login.authenticate'), []);

        $response->assertSessionHasErrors([
            'email',
            'password',
        ]);

        $this->assertGuest();
    }

    /* verify invalid credentials are rejected */
    public function test_invalid_credentials_are_rejected(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post(route('login.authenticate'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /* verify admin is redirected to admin dashboard */
    public function test_admin_is_redirected_to_admin_dashboard_after_login(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post(route('login.authenticate'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    /* verify employee is redirected to employee dashboard */
    public function test_employee_is_redirected_to_employee_dashboard_after_login(): void
    {
        $employee = $this->createEmployee();

        $response = $this->post(route('login.authenticate'), [
            'email' => $employee->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard'));

        $this->assertAuthenticatedAs($employee);
    }

    /* verify admin can access admin dashboard */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /* verify employee can access employee dashboard */
    public function test_employee_can_access_employee_dashboard(): void
    {
        $employee = $this->createEmployee();

        $response = $this
            ->actingAs($employee)
            ->get(route('user.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('user.dashboard');
    }

    /* verify employee cannot access admin dashboard */
    public function test_employee_cannot_access_admin_dashboard(): void
    {
        $employee = $this->createEmployee();

        $response = $this
            ->actingAs($employee)
            ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    /* verify admin cannot access employee dashboard */
    public function test_admin_cannot_access_employee_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->get(route('user.dashboard'));

        $response->assertForbidden();
    }

    /* verify guest is redirected from admin dashboard */
    public function test_guest_is_redirected_to_login_from_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /* verify guest is redirected from employee dashboard */
    public function test_guest_is_redirected_to_login_from_employee_dashboard(): void
    {
        $response = $this->get(route('user.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /* verify authenticated user can logout */
    public function test_authenticated_user_can_logout(): void
    {
        $employee = $this->createEmployee();

        $response = $this
            ->actingAs($employee)
            ->post(route('logout'));

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }
}