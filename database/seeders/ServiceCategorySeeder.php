<?php
// =====================================================
// FILE: database/seeders/ServiceCategorySeeder.php
// COMMAND: php artisan make:seeder ServiceCategorySeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electrical',       'slug' => 'electrical',       'icon' => '⚡', 'description' => 'All electrical work including wiring, fitting and repair'],
            ['name' => 'Plumbing',         'slug' => 'plumbing',         'icon' => '🔧', 'description' => 'Pipes, taps, drainage and water related work'],
            ['name' => 'AC & Cooling',     'slug' => 'ac-cooling',       'icon' => '❄️', 'description' => 'AC installation, repair and gas refill'],
            ['name' => 'Cleaning',         'slug' => 'cleaning',         'icon' => '🧹', 'description' => 'Home and office deep cleaning services'],
            ['name' => 'Carpentry',        'slug' => 'carpentry',        'icon' => '🪚', 'description' => 'Furniture repair, installation and woodwork'],
            ['name' => 'Painting',         'slug' => 'painting',         'icon' => '🎨', 'description' => 'Interior and exterior painting services'],
            ['name' => 'Appliance Repair', 'slug' => 'appliance-repair', 'icon' => '🔌', 'description' => 'Washing machine, refrigerator and other appliance repair'],
            ['name' => 'Security',         'slug' => 'security',         'icon' => '🔒', 'description' => 'CCTV installation and security systems'],
        ];

        foreach ($categories as $i => $cat) {
            ServiceCategory::create([
                'name'        => $cat['name'],
                'slug'        => $cat['slug'],
                'icon'        => $cat['icon'],
                'description' => $cat['description'],
                'is_active'   => true,
                'sort_order'  => $i + 1,
            ]);
        }

        $this->command->info('✅ Service Categories seeded: ' . count($categories));
    }
}