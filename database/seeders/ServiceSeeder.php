<?php
// =====================================================
// FILE: database/seeders/ServiceSeeder.php
// COMMAND: php artisan make:seeder ServiceSeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // Electrical
            ['category' => 'electrical',       'name' => 'Fan Installation',          'price' => 299,  'unit' => 'per visit'],
            ['category' => 'electrical',       'name' => 'Switchboard Repair',        'price' => 199,  'unit' => 'per visit'],
            ['category' => 'electrical',       'name' => 'Light Fitting',             'price' => 149,  'unit' => 'per visit'],
            ['category' => 'electrical',       'name' => 'MCB / Fuse Repair',         'price' => 249,  'unit' => 'per visit'],
            ['category' => 'electrical',       'name' => 'Full Home Wiring',          'price' => 4999, 'unit' => 'fixed'],

            // Plumbing
            ['category' => 'plumbing',         'name' => 'Tap Repair',                'price' => 149,  'unit' => 'per visit'],
            ['category' => 'plumbing',         'name' => 'Pipe Leakage Fix',          'price' => 349,  'unit' => 'per visit'],
            ['category' => 'plumbing',         'name' => 'Toilet Repair',             'price' => 299,  'unit' => 'per visit'],
            ['category' => 'plumbing',         'name' => 'Drain Cleaning',            'price' => 399,  'unit' => 'per visit'],
            ['category' => 'plumbing',         'name' => 'Water Tank Cleaning',       'price' => 799,  'unit' => 'fixed'],

            // AC & Cooling
            ['category' => 'ac-cooling',       'name' => 'AC Installation',           'price' => 999,  'unit' => 'fixed'],
            ['category' => 'ac-cooling',       'name' => 'AC Service & Cleaning',     'price' => 499,  'unit' => 'per visit'],
            ['category' => 'ac-cooling',       'name' => 'AC Gas Refill',             'price' => 1299, 'unit' => 'per visit'],
            ['category' => 'ac-cooling',       'name' => 'AC Repair',                 'price' => 699,  'unit' => 'per visit'],

            // Cleaning
            ['category' => 'cleaning',         'name' => 'Home Deep Cleaning',        'price' => 1499, 'unit' => 'fixed'],
            ['category' => 'cleaning',         'name' => 'Bathroom Cleaning',         'price' => 399,  'unit' => 'per visit'],
            ['category' => 'cleaning',         'name' => 'Kitchen Deep Cleaning',     'price' => 599,  'unit' => 'fixed'],
            ['category' => 'cleaning',         'name' => 'Sofa / Carpet Cleaning',    'price' => 799,  'unit' => 'fixed'],

            // Carpentry
            ['category' => 'carpentry',        'name' => 'Furniture Assembly',        'price' => 499,  'unit' => 'per visit'],
            ['category' => 'carpentry',        'name' => 'Door / Window Repair',      'price' => 349,  'unit' => 'per visit'],
            ['category' => 'carpentry',        'name' => 'Wardrobe Installation',     'price' => 1499, 'unit' => 'fixed'],

            // Painting
            ['category' => 'painting',         'name' => 'Room Painting',             'price' => 2999, 'unit' => 'per room'],
            ['category' => 'painting',         'name' => 'Full Home Painting',        'price' => 9999, 'unit' => 'fixed'],
            ['category' => 'painting',         'name' => 'Wall Texture / Design',     'price' => 1999, 'unit' => 'per room'],

            // Appliance Repair
            ['category' => 'appliance-repair', 'name' => 'Washing Machine Repair',   'price' => 499,  'unit' => 'per visit'],
            ['category' => 'appliance-repair', 'name' => 'Refrigerator Repair',      'price' => 599,  'unit' => 'per visit'],
            ['category' => 'appliance-repair', 'name' => 'Microwave Repair',         'price' => 399,  'unit' => 'per visit'],

            // Security
            ['category' => 'security',         'name' => 'CCTV Installation',        'price' => 2499, 'unit' => 'fixed'],
            ['category' => 'security',         'name' => 'Door Lock Installation',   'price' => 499,  'unit' => 'per visit'],
        ];

        foreach ($services as $s) {
            $category = ServiceCategory::where('slug', $s['category'])->first();
            if (!$category) continue;

            Service::create([
                'category_id' => $category->id,
                'name'        => $s['name'],
                'slug'        => Str::slug($s['name']) . '-' . Str::random(4),
                'description' => $s['name'] . ' service by verified professionals.',
                'base_price'  => $s['price'],
                'price_unit'  => $s['unit'],
                'is_active'   => true,
                'sort_order'  => 0,
            ]);
        }

        $this->command->info('✅ Services seeded: ' . count($services));
    }
}