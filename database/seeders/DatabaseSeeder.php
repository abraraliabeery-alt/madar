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
            AddMoreRealEstateCategoriesSeeder::class,
            FeatureSeeder::class,
            StatusSeeder::class,
            StatusTranslationSeeder::class,
            UserSeeder::class,
            BankSeeder::class,
            CitySeeder::class,
            NeighborhoodSeeder::class,
            StreetSeeder::class,
            FacilityCategorySeeder::class,
            FacilitySeeder::class,
            FacilityTenderSeeder::class,
            FacilityStatusSeeder::class,
            FacilityServiceSeeder::class,
            FacilityProjectSeeder::class,
            LandAttributesSeeder::class,
            RealEstateModuleSeeder::class,
            Product42UpdateSeeder::class,
            RealisticProjectSeeder::class,
            BookingSeeder::class,
            ExecutionRequestSeeder::class,
            ExecutionBidSeeder::class,
            EtimadFlowSeeder::class,
            InvestmentLandPartnershipSeeder::class,
            FavoriteSeeder::class,
            FaqSeeder::class,
            NotificationSeeder::class,
            AjlanPlanLotsSeeder::class,
            PdfSettingsSeeder::class,
            MakeAdminSeeder::class,
        ]);
    }
}
