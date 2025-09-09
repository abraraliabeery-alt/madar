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
                'name' => 'شركة العقارات المتميزة',
                'description' => 'شركة رائدة في مجال العقارات تقدم أفضل الخدمات',
                'address' => 'شارع الملك فهد، الرياض',
                'phone' => '+966501234567',
                'email' => 'info@excellent-realestate.com',
                'website' => 'https://excellent-realestate.com',
                'license_number' => 'RE123456',
                'license_expiry' => '2026-12-31',
                'is_active' => true,
                'is_verified' => true,
                'is_featured' => true,
                'rating' => 4.8,
                'rating_count' => 25,
                'products_count' => 15,
            ],
            [
                'name' => 'مجموعة الإسكان الحديث',
                'description' => 'مجموعة متخصصة في تطوير المشاريع السكنية',
                'address' => 'شارع التحلية، جدة',
                'phone' => '+966502345678',
                'email' => 'contact@modern-housing.com',
                'website' => 'https://modern-housing.com',
                'license_number' => 'RE234567',
                'license_expiry' => '2026-06-30',
                'is_active' => true,
                'is_verified' => true,
                'is_featured' => false,
                'rating' => 4.5,
                'rating_count' => 18,
                'products_count' => 12,
            ],
            [
                'name' => 'شركة الفلل الفاخرة',
                'description' => 'متخصصون في بناء وتأجير الفلل الفاخرة',
                'address' => 'شارع العليا، الرياض',
                'phone' => '+966503456789',
                'email' => 'info@luxury-villas.com',
                'website' => 'https://luxury-villas.com',
                'license_number' => 'RE345678',
                'license_expiry' => '2026-09-15',
                'is_active' => true,
                'is_verified' => true,
                'is_featured' => true,
                'rating' => 4.9,
                'rating_count' => 32,
                'products_count' => 8,
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
