<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvestmentLandPartnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command?->info('Skipping legacy investment land partnership seeding (land/products/offers) in contracting mode.');
    }
}
