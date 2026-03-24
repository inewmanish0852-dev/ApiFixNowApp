<?php
// =====================================================
// FILE: database/seeders/ReviewSeeder.php
// COMMAND: php artisan make:seeder ReviewSeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $completedBookings = Booking::where('status', 'completed')
            ->whereDoesntHave('review')
            ->get();

        $comments = [
            'Excellent service! Very professional and on time.',
            'Great work, would highly recommend.',
            'Good service but slightly delayed.',
            'Very skilled and clean work.',
            'Satisfied with the service. Will book again.',
            'Average service. Could be better.',
            'Outstanding! Best service provider.',
            'Decent work done at a fair price.',
            'Arrived on time and completed the work efficiently.',
            'Very happy with the results!',
        ];

        $count = 0;
        foreach ($completedBookings as $booking) {
            Review::create([
                'booking_id'  => $booking->id,
                'reviewer_id' => $booking->customer_id,
                'provider_id' => $booking->provider_id, // provider_profiles.id
                'rating'      => rand(3, 5),
                'comment'     => $comments[array_rand($comments)],
                'is_approved' => true,
                'is_flagged'  => false,
            ]);
            $count++;
        }

        $this->command->info('✅ Reviews seeded: ' . $count);
    }
}