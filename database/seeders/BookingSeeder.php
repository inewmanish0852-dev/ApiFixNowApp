<?php
// =====================================================
// FILE: database/seeders/BookingSeeder.php
// COMMAND: php artisan make:seeder BookingSeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Provider;
use App\Models\Service;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $customers        = User::where('role_id', 2)->get();
        $providerProfiles = Provider::with('user')->get();
        $services         = Service::where('is_active', true)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('⚠️  No customers found. Run UserSeeder first.');
            return;
        }
        if ($providerProfiles->isEmpty()) {
            $this->command->warn('⚠️  No provider profiles found. Run UserSeeder first.');
            return;
        }
        if ($services->isEmpty()) {
            $this->command->warn('⚠️  No services found. Run ServiceSeeder first.');
            return;
        }

        $statuses = [
            'pending', 'accepted', 'on_the_way',
            'in_progress', 'completed', 'completed',
            'completed', 'cancelled', 'disputed'
        ];

        $count = 0;
        for ($i = 0; $i < 20; $i++) {
            $customer        = $customers->random();
            $providerProfile = $providerProfiles->random();
            $service         = $services->random();
            $status          = $statuses[array_rand($statuses)];
            $amount          = $service->base_price;
            $fee             = round($amount * 0.1);

            Booking::create([
                'booking_number'      => Booking::generateNumber(),
                'customer_id'         => $customer->id,
                'provider_id'         => $providerProfile->id, // provider_profiles.id
                'service_type'        => $service->name,
                'scheduled_at'        => now()->addDays(rand(-10, 10)),
                'status'              => $status,
                'amount'              => $amount,
                'total_amount'        => $amount + $fee,
                'platform_fee'        => $fee,
                'service_charge'      => $amount,
                'payment_status'      => match(true) {
                    in_array($status, ['completed', 'in_progress', 'on_the_way', 'accepted']) => 'paid',
                    $status === 'cancelled' => 'refunded',
                    default => 'pending',
                },
                'payment_method'      => ['upi', 'cash', 'card'][array_rand(['upi', 'cash', 'card'])],
                'address'             => '123 Main Street, ' . $customer->city,
                'notes'               => 'Please come on time.',
                'cancellation_reason' => $status === 'cancelled' ? 'Customer requested cancellation.' : null,
                'cancelled_by'        => $status === 'cancelled' ? 'customer' : null,
                'completed_at'        => $status === 'completed' ? now()->subDays(rand(1, 5)) : null,
            ]);
            $count++;
        }

        $this->command->info('✅ Bookings seeded: ' . $count);
    }
}