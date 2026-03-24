<?php
// =====================================================
// FILE: database/seeders/DatabaseSeeder.php
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 Starting FixNow Database Seeding...');
        $this->command->info('─────────────────────────────────────');

        $this->call([
            ServiceCategorySeeder::class,  // 1. Categories pehle
            ServiceSeeder::class,          // 2. Services (categories pe depend)
            UserSeeder::class,             // 3. Customers + Providers
            BookingSeeder::class,          // 4. Bookings (users + services pe depend)
            ReviewSeeder::class,           // 5. Reviews (bookings pe depend)
            DisputeSeeder::class,          // 6. Disputes (bookings pe depend)
            NotificationSeeder::class,     // 7. Notifications (users + bookings pe depend)
        ]);

        $this->command->info('─────────────────────────────────────');
        $this->command->info('✅ All done! Login credentials:');
        $this->command->info('   Admin    → admin@fixnow.com / admin@123');
        $this->command->info('   Customer → rahul@test.com / password');
        $this->command->info('   Provider → ravi.elec@test.com / password');
        $this->command->info('─────────────────────────────────────');
        $this->command->info('');
    }
}