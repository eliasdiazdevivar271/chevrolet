<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\SiteSetting;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Onix', 'category' => 'Automóvil', 'image_path' => 'images/onix.jpg', 'description' => 'Chevrolet Onix es un automóvil de la gama Chevrolet.'],
            ['name' => 'Tracker', 'category' => 'SUV', 'image_path' => 'images/tracker.jpg', 'description' => 'Chevrolet Tracker es una SUV de la gama Chevrolet.'],
            ['name' => 'Montana', 'category' => 'Pickup', 'image_path' => 'images/montana.png', 'description' => 'Chevrolet Montana es una pickup de la gama Chevrolet.'],
        ] as $vehicle) {
            Vehicle::firstOrCreate(['name' => $vehicle['name']], $vehicle + ['active' => true, 'featured' => true]);
        }

        Promotion::firstOrCreate(['title' => 'Promoción de ejemplo'], [
            'description' => 'Contenido de ejemplo: actualice esta promoción desde el panel de administración.',
            'active' => true,
        ]);

        if (! SiteSetting::exists()) {
            SiteSetting::create(['dealer_name' => 'Chevrolet']);
        }
    }
}
