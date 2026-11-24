<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite' || config('database.connections.sqlite.database') !== ':memory:') {
            throw new \RuntimeException('Las pruebas requieren SQLite en memoria.');
        }
    }

    public function test_command_creates_a_new_admin_with_a_hashed_password(): void
    {
        $this->artisan('site:create-admin')->expectsQuestion('Nombre', 'Administradora')
            ->expectsQuestion('Correo electrónico', 'admin@example.test')
            ->expectsQuestion('Contraseña (mínimo 12 caracteres)', 'una-clave-segura')
            ->assertSuccessful();

        $user = User::where('email', 'admin@example.test')->firstOrFail();
        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('una-clave-segura', $user->password));
    }

    public function test_command_rejects_short_passwords_without_creating_users(): void
    {
        $this->artisan('site:create-admin')->expectsQuestion('Nombre', 'Ana')
            ->expectsQuestion('Correo electrónico', 'ana@example.test')
            ->expectsQuestion('Contraseña (mínimo 12 caracteres)', 'corta')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_command_does_not_modify_existing_users(): void
    {
        $existing = User::factory()->create(['email' => 'ana@example.test']);
        $oldPassword = $existing->password;
        $this->artisan('site:create-admin')->expectsQuestion('Nombre', 'Otra persona')
            ->expectsQuestion('Correo electrónico', 'ANA@example.test')
            ->expectsQuestion('Contraseña (mínimo 12 caracteres)', 'una-clave-segura')
            ->assertFailed();

        $existing->refresh();
        $this->assertFalse($existing->is_admin);
        $this->assertSame($oldPassword, $existing->password);
        $this->assertDatabaseCount('users', 1);
    }
}
