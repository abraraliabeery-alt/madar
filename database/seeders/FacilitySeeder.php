<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\User;
use App\Models\FacilityCategory;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilityCategories = FacilityCategory::all();
        $facilityUsers = User::where('primary_role', 'facility')->get();

        // Check if there are facility users available
        if ($facilityUsers->isEmpty()) {
            $this->command->warn('No facility users found. Please run UserSeeder first to create facility users.');
            return;
        }

        $facilities = [
            [
                'name' => 'شركة التنفيذ الهندسي المتميزة',
                'description' => 'شركة رائدة في المقاولات وإدارة المشاريع وتنفيذ الأعمال الإنشائية والتشطيبات',
                'address' => 'شارع الملك فهد، الرياض',
                'phone' => '+966501234567',
                'email' => 'info@excellent-contracting.com',
                'website' => 'https://excellent-contracting.com',
                'license_number' => 'CO123456',
                'license_expiry' => '2026-12-31',
                'is_active' => true,
                'is_verified' => true,
                'is_featured' => true,
                'rating' => 4.8,
                'rating_count' => 25,
                'products_count' => 0,
            ],
            [
                'name' => 'مجموعة المقاولات الحديثة',
                'description' => 'مجموعة متخصصة في تنفيذ المشاريع السكنية والتجارية والبنية التحتية',
                'address' => 'شارع التحلية، جدة',
                'phone' => '+966502345678',
                'email' => 'contact@modern-housing.com',
                'website' => 'https://modern-housing.com',
                'license_number' => 'CO234567',
                'license_expiry' => '2026-06-30',
                'is_active' => true,
                'is_verified' => true,
                'is_featured' => false,
                'rating' => 4.5,
                'rating_count' => 18,
                'products_count' => 0,
            ],
            [
                'name' => 'شركة الإنشاءات المتقدمة',
                'description' => 'متخصصون في تنفيذ المباني السكنية وإدارة المشاريع والتسليم حسب المواصفات',
                'address' => 'شارع العليا، الرياض',
                'phone' => '+966503456789',
                'email' => 'info@advanced-build.com',
                'website' => 'https://advanced-build.com',
                'license_number' => 'CO345678',
                'license_expiry' => '2026-09-15',
                'is_active' => true,
                'is_verified' => true,
                'is_featured' => true,
                'rating' => 4.9,
                'rating_count' => 32,
                'products_count' => 0,
            ],
        ];

        foreach ($facilities as $index => $facilityData) {
            // Safely get a facility user by cycling through available users
            $selectedUser = $facilityUsers[$index % $facilityUsers->count()];
            
            $facility = Facility::updateOrCreate(
                ['name' => $facilityData['name']],
                array_merge($facilityData, [
                    'facility_category_id' => $facilityCategories->random()->id,
                    'owner_user_id' => $selectedUser->id,
                ])
            );

            // Also create a many-to-many relationship in facility_user table
            $facility->users()->syncWithoutDetaching([$selectedUser->id]);

            $this->command->info("Created facility '{$facilityData['name']}' and linked to user '{$selectedUser->name}' (ID: {$selectedUser->id})");
        }
    }
}
