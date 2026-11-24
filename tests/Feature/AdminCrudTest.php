<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Promotion;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite' || config('database.connections.sqlite.database') !== ':memory:') {
            throw new \RuntimeException('Las pruebas requieren SQLite en memoria.');
        }
    }

    public function test_admin_can_create_edit_publish_and_delete_a_vehicle_without_an_image(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->get(route('admin.vehicles.create'))->assertOk();
        $this->post(route('admin.vehicles.store'), [
            'name' => 'Onix', 'category' => 'Automóvil', 'description' => 'Descripción', 'active' => '1', 'featured' => '1',
            'image_path' => 'images/ajena.jpg',
        ])->assertRedirect(route('admin.vehicles.index'));
        $vehicle = Vehicle::firstOrFail();
        $this->assertNull($vehicle->image_path);
        $this->assertTrue($vehicle->active);
        $this->get(route('admin.vehicles.index'))->assertOk()->assertSee('Onix')->assertSee('confirm(');
        $this->get(route('admin.vehicles.edit', $vehicle))->assertOk();

        $this->put(route('admin.vehicles.update', $vehicle), [
            'name' => 'Onix actualizado', 'category' => 'Automóvil', 'description' => 'Nueva descripción', 'price' => 'Consultar',
        ])->assertRedirect(route('admin.vehicles.index'));
        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'name' => 'Onix actualizado', 'price' => 'Consultar', 'active' => false, 'featured' => false]);
        $this->delete(route('admin.vehicles.destroy', $vehicle))->assertRedirect(route('admin.vehicles.index'));
        $this->assertModelMissing($vehicle);
    }

    public function test_image_uploads_are_validated_and_existing_files_are_not_deleted(): void
    {
        $disk = Storage::fake('test_'.Str::random(16));
        Storage::set('public', $disk);
        $disk->put('ajena.jpg', 'contenido ajeno');
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $vehicle = Vehicle::factory()->create(['image_path' => 'storage/ajena.jpg']);

        $this->put(route('admin.vehicles.update', $vehicle), [
            'name' => 'Tracker', 'category' => 'SUV', 'description' => 'Descripción', 'image' => UploadedFile::fake()->image('auto.png'),
        ])->assertRedirect(route('admin.vehicles.index'));
        $vehicle->refresh();
        $this->assertStringStartsWith('storage/vehicles/', $vehicle->image_path);
        $disk->assertExists(substr($vehicle->image_path, strlen('storage/')));
        $this->delete(route('admin.vehicles.destroy', $vehicle))->assertRedirect();
        $disk->assertExists('ajena.jpg');
        $disk->assertExists(substr($vehicle->image_path, strlen('storage/')));

        $this->post(route('admin.vehicles.store'), [
            'name' => 'Tracker', 'category' => 'SUV', 'description' => 'Descripción', 'image' => UploadedFile::fake()->create('archivo.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('image');
        $this->post(route('admin.vehicles.store'), [
            'name' => 'Tracker', 'category' => 'SUV', 'description' => 'Descripción', 'image' => UploadedFile::fake()->image('grande.jpg')->size(4097),
        ])->assertSessionHasErrors('image');
        $this->assertDatabaseCount('vehicles', 0);
    }

    public function test_admin_can_manage_promotions_and_validate_date_order(): void
    {
        $disk = Storage::fake('test_'.Str::random(16));
        Storage::set('public', $disk);
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->get(route('admin.promotions.create'))->assertOk();
        $this->post(route('admin.promotions.store'), [
            'title' => 'Promoción', 'description' => 'Descripción', 'starts_on' => '2026-11-23', 'ends_on' => '2026-11-22',
        ])->assertSessionHasErrors('ends_on');
        $this->assertDatabaseCount('promotions', 0);
        $this->post(route('admin.promotions.store'), [
            'title' => 'Promoción', 'description' => 'Descripción', 'ends_on' => '2026-11-23', 'active' => '1',
            'image' => UploadedFile::fake()->image('promo.webp'),
        ])->assertRedirect(route('admin.promotions.index'));
        $promotion = Promotion::firstOrFail();
        $disk->assertExists(substr($promotion->image_path, strlen('storage/')));
        $this->get(route('admin.promotions.index'))->assertOk()->assertSee('Promoción')->assertSee('confirm(');
        $this->get(route('admin.promotions.edit', $promotion))->assertOk();
        $this->put(route('admin.promotions.update', $promotion), ['title' => 'Editada', 'description' => 'Texto'])
            ->assertRedirect(route('admin.promotions.index'));
        $this->assertDatabaseHas('promotions', ['id' => $promotion->id, 'title' => 'Editada', 'active' => false, 'starts_on' => null, 'ends_on' => null, 'image_path' => $promotion->image_path]);
        $this->delete(route('admin.promotions.destroy', $promotion))->assertRedirect(route('admin.promotions.index'));
        $this->assertModelMissing($promotion);
        $disk->assertExists(substr($promotion->image_path, strlen('storage/')));
    }

    public function test_admin_can_edit_public_text_and_hero_image(): void
    {
        $disk = Storage::fake('test_'.Str::random(16));
        Storage::set('public', $disk);
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->get(route('admin.content.edit'))->assertOk();
        $this->put(route('admin.content.update'), [
            'home_title' => 'Bienvenidos',
            'home_description' => 'Texto de inicio editado',
            'about_title' => 'Nuestra concesionaria',
            'about_description' => 'Texto de Nosotros editado',
            'hero_image' => UploadedFile::fake()->image('portada.webp'),
            'hero_image_path' => 'storage/ajena.jpg',
        ])->assertRedirect(route('admin.content.edit'));

        $settings = SiteSetting::firstOrFail();
        $this->assertSame('Bienvenidos', $settings->home_title);
        $this->assertStringStartsWith('storage/site/', $settings->hero_image_path);
        $disk->assertExists(substr($settings->hero_image_path, strlen('storage/')));
        $this->get(route('home'))->assertOk()->assertSee('Bienvenidos')->assertSee('Texto de inicio editado');
        $this->get(route('about'))->assertOk()->assertSee('Nuestra concesionaria')->assertSee('Texto de Nosotros editado');

        $this->put(route('admin.content.update'), [
            'home_title' => 'Inicio',
            'home_description' => 'Descripción',
            'about_title' => 'Nosotros',
            'about_description' => 'Descripción',
            'hero_image' => UploadedFile::fake()->create('documento.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('hero_image');
        $this->assertSame($settings->hero_image_path, $settings->fresh()->hero_image_path);
    }

    public function test_settings_can_be_created_and_updated_and_messages_are_escaped(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->get(route('admin.settings.edit'))->assertOk();
        $this->put(route('admin.settings.update'), ['dealer_name' => 'Chevrolet', 'email' => 'contacto@example.test'])
            ->assertRedirect(route('admin.settings.edit'));
        $this->put(route('admin.settings.update'), ['dealer_name' => 'Sucursal', 'phone' => '123'])
            ->assertRedirect(route('admin.settings.edit'));
        $this->assertSame(1, SiteSetting::count());
        $this->assertDatabaseHas('site_settings', ['dealer_name' => 'Sucursal', 'phone' => '123']);
        $this->put(route('admin.settings.update'), ['dealer_name' => '', 'email' => 'incorrecto'])->assertSessionHasErrors(['dealer_name', 'email']);
        $this->put(route('admin.settings.update'), ['dealer_name' => 'Sucursal', 'whatsapp' => 'abc'])
            ->assertSessionHasErrors(['whatsapp' => 'Ingrese entre 8 y 15 dígitos, incluido el código de país.']);
        $this->put(route('admin.settings.update'), ['dealer_name' => 'Sucursal', 'whatsapp' => '595981123456'])
            ->assertRedirect(route('admin.settings.edit'));
        $this->get(route('contact.show'))->assertSee('https://wa.me/595981123456');

        $message = ContactMessage::factory()->create(['message' => '<script>alert(1)</script>']);
        $this->get(route('admin.messages.index'))->assertOk()->assertSee($message->name);
        $this->get(route('admin.messages.show', $message))->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }
}
