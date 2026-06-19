<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPhone = (string) env('SEED_ADMIN_PHONE', '0500000000');
        $facilityPhone = (string) env('SEED_FACILITY_PHONE', '0500000001');
        $clientPhone = (string) env('SEED_CLIENT_PHONE', '0500000002');

        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@aqar.com'],
            [
                'name' => 'مدير النظام',
                'email' => 'admin@aqar.com',
                'phone_number' => $adminPhone,
                'password' => Hash::make('admin@aqar.com'),
                'primary_role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);
        }

        // Create Facility User
        $facility = User::updateOrCreate(
            ['email' => 'facility@aqar.com'],
            [
                'name' => 'منشأة تجريبية',
                'email' => 'facility@aqar.com',
                'phone_number' => $facilityPhone,
                'password' => Hash::make('facility@aqar.com'),
                'primary_role' => 'facility',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign facility role
        $facilityRole = Role::where('name', 'facility')->first();
        if ($facilityRole) {
            $facility->roles()->sync([$facilityRole->id]);
        }

        // Create Client User
        $client = User::updateOrCreate(
            ['email' => 'client@aqar.com'],
            [
                'name' => 'عميل تجريبي',
                'email' => 'client@aqar.com',
                'phone_number' => $clientPhone,
                'password' => Hash::make('client@aqar.com'),
                'primary_role' => 'client',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign client role
        $clientRole = Role::where('name', 'client')->first();
        if ($clientRole) {
            $client->roles()->sync([$clientRole->id]);
        }

        // Create additional test users
        $testUsers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@aqar.com',
                'primary_role' => 'client',
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@aqar.com',
                'primary_role' => 'client',
            ],
            [
                'name' => 'محمد حسن',
                'email' => 'mohammed@aqar.com',
                'primary_role' => 'facility',
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'sara@aqar.com',
                'primary_role' => 'facility',
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'primary_role' => $userData['primary_role'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Assign role
            $role = Role::where('name', $userData['primary_role'])->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }
    }
}
