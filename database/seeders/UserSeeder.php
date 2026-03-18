<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Role, ProviderProfile, ProviderSkill};

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole    = Role::where('slug', 'admin')->first();
        $customerRole = Role::where('slug', 'customer')->first();
        $providerRole = Role::where('slug', 'provider')->first();

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@fixnow.com'],
            [
                'role_id'     => $adminRole->id,
                'name'        => 'Super Admin',
                'phone'       => '9000000001',
                'password'    => Hash::make('Admin@123'),
                'city'        => 'Prayagraj',
                'state'       => 'Uttar Pradesh',
                'is_verified' => true,
                'is_active'   => true,
            ]
        );

        // Demo Customer
        User::updateOrCreate(
            ['email' => 'customer@fixnow.com'],
            [
                'role_id'     => $customerRole->id,
                'name'        => 'Rahul Kumar',
                'phone'       => '9000000002',
                'password'    => Hash::make('Customer@123'),
                'city'        => 'Prayagraj',
                'state'       => 'Uttar Pradesh',
                'address'     => '123, Civil Lines',
                'lat'         => 25.4358,
                'lng'         => 81.8463,
                'is_verified' => true,
                'is_active'   => true,
            ]
        );

        // Demo Providers
        $providers = [
            [
                'user' => [
                    'role_id'  => $providerRole->id,
                    'name'     => 'Amit Singh',
                    'email'    => 'amit@fixnow.com',
                    'phone'    => '9000000003',
                    'password' => Hash::make('Provider@123'),
                    'city'     => 'Prayagraj',
                    'state'    => 'Uttar Pradesh',
                    'lat'      => 25.4400,
                    'lng'      => 81.8500,
                    'is_verified' => true,
                    'is_active'   => true,
                ],
                'profile' => [
                    'category'         => 'Plumber',
                    'bio'              => 'Expert plumber with 3 years experience.',
                    'experience_years' => 3,
                    'hourly_rate'      => 299.00,
                    'rating'           => 4.80,
                    'total_jobs'       => 142,
                    'is_available'     => true,
                ],
                'skills' => ['Pipe Fitting', 'Leakage Repair', 'Bathroom Fitting'],
            ],
            [
                'user' => [
                    'role_id'  => $providerRole->id,
                    'name'     => 'Ravi Kumar',
                    'email'    => 'ravi@fixnow.com',
                    'phone'    => '9000000004',
                    'password' => Hash::make('Provider@123'),
                    'city'     => 'Prayagraj',
                    'state'    => 'Uttar Pradesh',
                    'lat'      => 25.4320,
                    'lng'      => 81.8420,
                    'is_verified' => true,
                    'is_active'   => true,
                ],
                'profile' => [
                    'category'         => 'Electrician',
                    'bio'              => 'Certified electrician for home and commercial work.',
                    'experience_years' => 5,
                    'hourly_rate'      => 349.00,
                    'rating'           => 4.60,
                    'total_jobs'       => 98,
                    'is_available'     => true,
                ],
                'skills' => ['Wiring', 'Switchboard Repair', 'AC Installation'],
            ],
            [
                'user' => [
                    'role_id'  => $providerRole->id,
                    'name'     => 'Suresh Yadav',
                    'email'    => 'suresh@fixnow.com',
                    'phone'    => '9000000005',
                    'password' => Hash::make('Provider@123'),
                    'city'     => 'Prayagraj',
                    'state'    => 'Uttar Pradesh',
                    'lat'      => 25.4450,
                    'lng'      => 81.8380,
                    'is_verified' => true,
                    'is_active'   => true,
                ],
                'profile' => [
                    'category'         => 'Technician',
                    'bio'              => 'Mobile and laptop repair specialist.',
                    'experience_years' => 4,
                    'hourly_rate'      => 399.00,
                    'rating'           => 4.70,
                    'total_jobs'       => 205,
                    'is_available'     => true,
                ],
                'skills' => ['Mobile Repair', 'Laptop Repair', 'AC Service'],
            ],
        ];

        foreach ($providers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['user']['email']],
                $data['user']
            );

            $profile = ProviderProfile::updateOrCreate(
                ['user_id' => $user->id],
                $data['profile']
            );

            // Skills pehle delete karke fresh insert
            $profile->skills()->delete();
            foreach ($data['skills'] as $skill) {
                ProviderSkill::create([
                    'provider_id' => $profile->id,
                    'skill_name'  => $skill,
                ]);
            }
        }
    }
}