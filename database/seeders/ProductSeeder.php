<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command?->info('Skipping legacy ProductSeeder. Contracting mode uses ExecutionRequestSeeder, ExecutionBidSeeder, FacilityServiceSeeder, FacilityProjectSeeder.');
    }
}
