<?php
// =====================================================
// FILE: database/seeders/NotificationSeeder.php
// COMMAND: php artisan make:seeder NotificationSeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role_id', 2)->get();
        $bookings  = Booking::latest()->take(5)->get();

        // Broadcast notifications (user_id = null means all users)
        $broadcasts = [
            ['icon' => '🎉', 'title' => 'Welcome to FixNow!',     'body' => 'Find trusted home service professionals near you.', 'type' => 'general'],
            ['icon' => '🎁', 'title' => 'Special Offer!',          'body' => 'Get 20% off on your first booking. Use code: FIRST20', 'type' => 'promo'],
            ['icon' => '⚙️', 'title' => 'App Updated',             'body' => 'FixNow v2.0 is here with new features and improvements.', 'type' => 'system'],
            ['icon' => '🔧', 'title' => 'New Providers Available', 'body' => 'More verified service providers are now available in your area.', 'type' => 'provider'],
        ];

        foreach ($broadcasts as $n) {
            Notification::create([
                'user_id'    => null,
                'icon'       => $n['icon'],
                'title'      => $n['title'],
                'body'       => $n['body'],
                'type'       => $n['type'],
                'is_read'    => false,
                'created_at' => now()->subDays(rand(1, 7)),
            ]);
        }

        // User specific notifications
        foreach ($customers->take(5) as $customer) {
            Notification::create([
                'user_id'    => $customer->id,
                'icon'       => '📅',
                'title'      => 'Booking Confirmed',
                'body'       => 'Your service booking has been confirmed. Provider will arrive shortly.',
                'type'       => 'booking',
                'is_read'    => false,
                'created_at' => now()->subHours(rand(1, 24)),
            ]);
        }

        // Booking specific notifications
        foreach ($bookings as $booking) {
            Notification::create([
                'user_id'    => $booking->customer_id,
                'icon'       => '✅',
                'title'      => 'Service Completed',
                'body'       => 'Your booking has been completed. Please rate your experience.',
                'type'       => 'booking',
                'ref_id'     => $booking->id,
                'is_read'    => rand(0, 1) ? true : false,
                'created_at' => now()->subDays(rand(1, 5)),
            ]);
        }

        $total = count($broadcasts) + $customers->take(5)->count() + $bookings->count();
        $this->command->info('✅ Notifications seeded: ' . $total);
    }
}