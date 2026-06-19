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
            BankSeeder::class,
            PackageSeeder::class,
            PageSeeder::class,
            CitySeeder::class,
            NeighborhoodSeeder::class,
            StreetSeeder::class,
            FacilityCategorySeeder::class,
            FacilitySeeder::class,
            FacilityTenderSeeder::class,
            AttributeSeeder::class,
            AttributeCardSeeder::class,
            ProjectLifecycleSeeder::class,
            ProjectSeeder::class,
            ProjectStageSeeder::class,
            FacilityStatusSeeder::class,
            FacilityServiceSeeder::class,
            FacilityProjectSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
            OfferSeeder::class,
            BookingSeeder::class,
            ExecutionRequestSeeder::class,
            ExecutionBidSeeder::class,
            EtimadFlowSeeder::class,
            InvestmentLandPartnershipSeeder::class,
            FavoriteSeeder::class,
            FaqSeeder::class,
            NotificationSeeder::class,
            AjlanPlanLotsSeeder::class,
        ]);
    }
}
