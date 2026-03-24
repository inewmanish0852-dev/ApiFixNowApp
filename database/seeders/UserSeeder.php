<?php
// =====================================================
// FILE: database/seeders/UserSeeder.php
// COMMAND: php artisan make:seeder UserSeeder
// =====================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Provider;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Customers (role_id = 2) ───────────────────────────────────────
        $customers = [
            ['name' => 'Rahul Kumar',   'email' => 'rahul@test.com',   'phone' => '9876543210', 'city' => 'Mumbai',    'state' => 'Maharashtra'],
            ['name' => 'Priya Sharma',  'email' => 'priya@test.com',   'phone' => '9876543211', 'city' => 'Delhi',     'state' => 'Delhi'],
            ['name' => 'Amit Mehta',    'email' => 'amit@test.com',    'phone' => '9876543212', 'city' => 'Bangalore', 'state' => 'Karnataka'],
            ['name' => 'Sneha Rao',     'email' => 'sneha@test.com',   'phone' => '9876543213', 'city' => 'Chennai',   'state' => 'Tamil Nadu'],
            ['name' => 'Ravi Tiwari',   'email' => 'ravi@test.com',    'phone' => '9876543214', 'city' => 'Lucknow',   'state' => 'Uttar Pradesh'],
            ['name' => 'Anjali Singh',  'email' => 'anjali@test.com',  'phone' => '9876543215', 'city' => 'Pune',      'state' => 'Maharashtra'],
            ['name' => 'Vikram Patel',  'email' => 'vikram@test.com',  'phone' => '9876543216', 'city' => 'Surat',     'state' => 'Gujarat'],
            ['name' => 'Deepa Nair',    'email' => 'deepa@test.com',   'phone' => '9876543217', 'city' => 'Kochi',     'state' => 'Kerala'],
        ];

        foreach ($customers as $c) {
            User::create([
                'role_id'    => 2,
                'name'       => $c['name'],
                'email'      => $c['email'],
                'phone'      => $c['phone'],
                'password'   => Hash::make('password'),
                'city'       => $c['city'],
                'state'      => $c['state'],
                'is_active'  => true,
                'is_verified'=> true,
            ]);
        }

        $this->command->info('✅ Customers seeded: ' . count($customers));

        // ── Provider Users (role_id = 3) ──────────────────────────────────
        $providerData = [
            [
                'user'     => ['name' => 'Ravi Electricals',  'email' => 'ravi.elec@test.com',   'phone' => '9800000001', 'city' => 'Mumbai',    'state' => 'Maharashtra'],
                'provider' => ['business_name' => 'Ravi Electricals',  'service_area' => 'Mumbai Suburbs',   'experience_years' => '10', 'hourly_rate' => 350, 'verification_status' => 'verified',  'bio' => '10+ years experience in electrical work. Certified electrician.'],
            ],
            [
                'user'     => ['name' => 'Suresh Plumber',    'email' => 'suresh.plmb@test.com', 'phone' => '9800000002', 'city' => 'Mumbai',    'state' => 'Maharashtra'],
                'provider' => ['business_name' => 'Suresh Plumbing Works', 'service_area' => 'Mumbai Central', 'experience_years' => '7',  'hourly_rate' => 300, 'verification_status' => 'verified',  'bio' => 'Expert plumber with 7 years experience. Available 24/7.'],
            ],
            [
                'user'     => ['name' => 'Manoj AC Services', 'email' => 'manoj.ac@test.com',    'phone' => '9800000003', 'city' => 'Delhi',     'state' => 'Delhi'],
                'provider' => ['business_name' => 'Manoj AC Services',   'service_area' => 'Delhi NCR',        'experience_years' => '5',  'hourly_rate' => 400, 'verification_status' => 'verified',  'bio' => 'Certified AC technician. All brands serviced.'],
            ],
            [
                'user'     => ['name' => 'Geeta Cleaners',   'email' => 'geeta.clean@test.com', 'phone' => '9800000004', 'city' => 'Bangalore', 'state' => 'Karnataka'],
                'provider' => ['business_name' => 'Geeta Home Cleaners', 'service_area' => 'Bangalore South',  'experience_years' => '3',  'hourly_rate' => 250, 'verification_status' => 'verified',  'bio' => 'Professional home cleaning service with trained staff.'],
            ],
            [
                'user'     => ['name' => 'Ramesh Carpenter',  'email' => 'ramesh.carp@test.com', 'phone' => '9800000005', 'city' => 'Chennai',   'state' => 'Tamil Nadu'],
                'provider' => ['business_name' => 'Ramesh Carpentry',    'service_area' => 'Chennai Metro',    'experience_years' => '8',  'hourly_rate' => 320, 'verification_status' => 'verified',  'bio' => 'Skilled carpenter for all furniture and woodwork needs.'],
            ],
            [
                'user'     => ['name' => 'Pending Provider',  'email' => 'pending@test.com',     'phone' => '9800000006', 'city' => 'Pune',      'state' => 'Maharashtra'],
                'provider' => ['business_name' => 'New Services Co.',    'service_area' => 'Pune',             'experience_years' => '2',  'hourly_rate' => 200, 'verification_status' => 'pending',   'bio' => 'New provider on the platform.'],
            ],
            [
                'user'     => ['name' => 'Rejected Provider', 'email' => 'rejected@test.com',    'phone' => '9800000007', 'city' => 'Lucknow',   'state' => 'Uttar Pradesh'],
                'provider' => ['business_name' => 'Quick Fix Services',  'service_area' => 'Lucknow',          'experience_years' => '1',  'hourly_rate' => 150, 'verification_status' => 'rejected',  'bio' => 'Service provider.', 'rejection_reason' => 'Incomplete documents submitted.'],
            ],
        ];

        foreach ($providerData as $pd) {
            $user = User::create([
                'role_id'    => 3,
                'name'       => $pd['user']['name'],
                'email'      => $pd['user']['email'],
                'phone'      => $pd['user']['phone'],
                'password'   => Hash::make('password'),
                'city'       => $pd['user']['city'],
                'state'      => $pd['user']['state'],
                'is_active'  => true,
                'is_verified'=> true,
            ]);

            Provider::create([
                'user_id'             => $user->id,
                'business_name'       => $pd['provider']['business_name'],
                'bio'                 => $pd['provider']['bio'],
                'experience_years'    => $pd['provider']['experience_years'],
                'service_area'        => $pd['provider']['service_area'],
                'hourly_rate'         => $pd['provider']['hourly_rate'],
                'verification_status' => $pd['provider']['verification_status'],
                'rejection_reason'    => $pd['provider']['rejection_reason'] ?? null,
                'verified_at'         => $pd['provider']['verification_status'] === 'verified' ? now() : null,
                'avg_rating'          => rand(35, 50) / 10,
                'total_reviews'       => rand(5, 30),
                'total_bookings'      => rand(10, 50),
                'completed_bookings'  => rand(8, 40),
                'is_available'        => true,
            ]);
        }

        $this->command->info('✅ Providers seeded: ' . count($providerData));
    }
}