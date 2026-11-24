<?php

namespace Tests\Feature;

use App\Models\Promotion;
use App\Models\SiteSetting;
use App\Models\Vehicle;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite' || config('database.connections.sqlite.database') !== ':memory:') {
            throw new \RuntimeException('Las pruebas requieren SQLite en memoria.');
        }
    }

    public function test_empty_public_pages_work_without_seed_data(): void
    {
        $this->get(route('home'))->assertOk()->assertViewHas('vehicles', fn ($vehicles) => $vehicles->isEmpty());
        $this->get(route('about'))->assertOk();
        $this->get(route('vehicles.index'))->assertOk();
        $this->get(route('promotions.index'))->assertOk();
        $this->get(route('contact.show'))->assertOk()->assertViewHas('settings', null);
    }

    public function test_public_lists_only_show_active_and_current_items_including_date_boundaries(): void
    {
        $this->travelTo(Carbon::parse('2026-11-23 12:00:00'));
        Vehicle::factory()->create(['name' => 'Destacado', 'active' => true, 'featured' => true]);
        Vehicle::factory()->create(['name' => 'No destacado', 'active' => true, 'featured' => false]);
        Vehicle::factory()->create(['name' => 'Oculto', 'active' => false, 'featured' => true]);
        Promotion::factory()->create(['title' => 'Hoy', 'starts_on' => '2026-11-23', 'ends_on' => '2026-11-23']);
        Promotion::factory()->create(['title' => 'Sin fechas']);
        Promotion::factory()->create(['title' => 'Futura', 'starts_on' => '2026-11-24']);
        Promotion::factory()->create(['title' => 'Vencida', 'ends_on' => '2026-11-22']);
        Promotion::factory()->create(['title' => 'Inactiva', 'active' => false]);

        $this->get(route('home'))->assertOk()->assertViewHas('vehicles', fn ($vehicles) => $vehicles->pluck('name')->all() === ['Destacado'])
            ->assertViewHas('promotions', fn ($promotions) => $promotions->pluck('title')->sort()->values()->all() === ['Hoy', 'Sin fechas']);
        $this->get(route('vehicles.index'))->assertOk()->assertViewHas('vehicles', fn ($vehicles) => $vehicles->pluck('name')->all() === ['Destacado', 'No destacado']);
        $this->get(route('promotions.index'))->assertOk()->assertViewHas('promotions', fn ($promotions) => $promotions->pluck('title')->sort()->values()->all() === ['Hoy', 'Sin fechas']);
    }

    public function test_contact_validates_saves_and_limits_requests_by_ip(): void
    {
        $this->from(route('contact.show'))->post(route('contact.store'), ['email' => 'incorrecto'])
            ->assertRedirect(route('contact.show'))->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
        $this->post(route('contact.store'), [
            'name' => 'Ana', 'email' => 'ana@example.test', 'message' => 'Consulta', 'vehiculo' => 'Tracker', 'is_admin' => true,
        ])->assertRedirect(route('contact.show'))->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', ['name' => 'Ana', 'email' => 'ana@example.test', 'subject' => null, 'message' => 'Consulta']);
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->post(route('contact.store'), [])->assertSessionHasErrors();
        }
        $this->post(route('contact.store'), [])->assertTooManyRequests();
        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_contact_page_receives_settings_and_prefills_vehicle_in_the_view(): void
    {
        SiteSetting::factory()->create(['dealer_name' => 'Mi concesionaria', 'phone' => '123456']);

        $this->get(route('contact.show', ['vehiculo' => 'Tracker']))->assertOk()
            ->assertViewHas('settings', fn ($settings) => $settings->dealer_name === 'Mi concesionaria')
            ->assertSee('Consulta por Tracker')->assertSee('123456');
        $this->get(route('contact.show', ['vehiculo' => ['Tracker']]))->assertOk()->assertViewHas('subject', '');
        $this->get(route('contact.show', ['vehiculo' => str_repeat('x', 200)]))->assertOk()
            ->assertViewHas('subject', 'Consulta por '.str_repeat('x', 137));
    }

    public function test_example_seeder_is_repeatable_without_creating_users_or_overwriting_edits(): void
    {
        $this->seed(DatabaseSeeder::class);
        Vehicle::where('name', 'Onix')->firstOrFail()->update(['description' => 'Editado']);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('vehicles', 3);
        $this->assertDatabaseCount('promotions', 1);
        $this->assertDatabaseCount('site_settings', 1);
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseHas('vehicles', ['name' => 'Onix', 'image_path' => 'images/onix.jpg', 'description' => 'Editado']);
        $this->assertDatabaseHas('vehicles', ['name' => 'Tracker', 'image_path' => 'images/tracker.jpg']);
        $this->assertDatabaseHas('vehicles', ['name' => 'Montana', 'image_path' => 'images/montana.png']);
    }
}
