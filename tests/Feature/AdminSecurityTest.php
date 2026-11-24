<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite' || config('database.connections.sqlite.database') !== ':memory:') {
            throw new \RuntimeException('Las pruebas requieren SQLite en memoria.');
        }
    }

    public function test_admin_routes_require_authentication_and_admin_permission(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->post(route('admin.vehicles.store'), [])->assertRedirect(route('login'));

        $user = User::factory()->create();
        $this->assertFalse(Gate::forUser($user)->allows('admin'));
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.messages.index'))->assertForbidden();
        $this->actingAs($user)->put(route('admin.settings.update'), [])->assertForbidden();
        $this->actingAs($user)->post(route('admin.promotions.store'), [])->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->assertTrue(Gate::forUser($admin)->allows('admin'));
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->get('/register')->assertNotFound();
    }

    public function test_login_authenticates_and_logout_ends_the_session(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'password' => Hash::make('a-secure-password')]);

        $this->post(route('login.store'), ['email' => $admin->email, 'password' => 'a-secure-password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_non_admin_users_cannot_log_in_to_the_admin_panel(): void
    {
        $user = User::factory()->create(['password' => Hash::make('a-secure-password')]);

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'a-secure-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_rejects_invalid_credentials_and_limits_attempts(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), ['email' => $admin->email, 'password' => 'incorrect'])
                ->assertSessionHasErrors('email');
        }
        $this->post(route('login.store'), ['email' => $admin->email, 'password' => 'incorrect'])->assertTooManyRequests();
        $this->assertGuest();
    }
}
