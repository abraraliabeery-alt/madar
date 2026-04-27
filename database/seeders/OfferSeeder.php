<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command?->info('Skipping legacy real-estate offers seeding (sale/rent). Use ExecutionBidSeeder for contracting mode.');
    }
}
