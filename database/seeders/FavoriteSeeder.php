<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample users and facilities
        $users = User::take(5)->get();
        $facilities = Facility::take(5)->get();

        if ($users->isEmpty() || $facilities->isEmpty()) {
            $this->command->info('Skipping favorites seeding - not enough data available.');
            return;
        }

        foreach ($users as $user) {
            // Add some facility favorites
            $randomFacilities = $facilities->random(rand(1, 2));
            foreach ($randomFacilities as $facility) {
                DB::table('favorites')->insert([
                    'user_id' => $user->id,
                    'favoritable_id' => $facility->id,
                    'favoritable_type' => Facility::class,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Favorites seeded successfully!');
    }
}
