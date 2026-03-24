<?php
// =====================================================
// FILE: database/seeders/DisputeSeeder.php
// COMMAND: php artisan make:seeder DisputeSeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Dispute;

class DisputeSeeder extends Seeder
{
    public function run(): void
    {
        $disputedBookings = Booking::whereIn('status', ['disputed', 'cancelled'])
            ->whereDoesntHave('dispute')
            ->take(5)
            ->get();

        $descriptions = [
            'Provider did not show up at scheduled time.',
            'Work quality was very poor and incomplete.',
            'Provider charged extra without prior notice.',
            'Service was not done as promised.',
            'Provider damaged property during work.',
        ];

        $statuses = ['open', 'open', 'under_review', 'resolved', 'closed'];

        $count = 0;
        foreach ($disputedBookings as $i => $booking) {
            $status = $statuses[$i] ?? 'open';

            Dispute::create([
                'booking_id'  => $booking->id,
                'raised_by'   => $booking->customer_id,
                'description' => $descriptions[$i] ?? $descriptions[0],
                'status'      => $status,
                'admin_notes' => $status !== 'open' ? 'Admin reviewed the case and took necessary action.' : null,
                'resolution'  => $status === 'resolved' ? 'Refund issued to customer. Provider warned.' : null,
                'resolved_at' => $status === 'resolved' ? now() : null,
            ]);
            $count++;
        }

        $this->command->info('✅ Disputes seeded: ' . $count);
    }
}