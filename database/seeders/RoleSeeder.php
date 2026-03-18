<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Full platform access and management',
            ],
            [
                'name'        => 'Customer',
                'slug'        => 'customer',
                'description' => 'Can browse and book service providers',
            ],
            [
                'name'        => 'Provider',
                'slug'        => 'provider',
                'description' => 'Service professional — plumber, electrician, etc.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}