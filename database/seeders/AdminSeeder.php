<?php
// =====================================================
// FILE: database/seeders/AdminSeeder.php
// COMMAND: php artisan make:seeder AdminSeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Pehle check karo admin already exist karta hai ya nahi
        $existing = User::where('email', 'admin@fixnow.com')->first();

        if ($existing) {
            $this->command->warn('⚠️  Admin already exists! Updating password...');
            $existing->update([
                'password' => Hash::make('admin@123'),
            ]);
        } else {
            User::create([
                'role_id'    => 1,
                'name'       => 'Super Admin',
                'email'      => 'admin@fixnow.com',
                'phone'      => '9000000001',
                'password'   => Hash::make('admin@123'),
                'city'       => 'Mumbai',
                'state'      => 'Maharashtra',
                'is_active'  => true,
                'is_verified'=> true,
                'is_banned'  => false,
            ]);

            $this->command->info('✅ Admin created successfully!');
        }

        $this->command->info('   Email    : admin@fixnow.com');
        $this->command->info('   Password : admin@123');
    }
}