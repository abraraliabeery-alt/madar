<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in order
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            CategorySeeder::class,
            FeatureSeeder::class,
            StatusSeeder::class,
            StatusTranslationSeeder::class,
            UserSeeder::class,
            CitySeeder::class,
            FacilityCategorySeeder::class,
            FacilitySeeder::class,
            AttributeSeeder::class,
            AttributeCardSeeder::class,
            ProductSeeder::class,
            ProductStatusSeeder::class,
            FacilityStatusSeeder::class,
            OfferSeeder::class,
            NotificationSeeder::class,
            FavoriteSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
