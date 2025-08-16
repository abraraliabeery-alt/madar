<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample users, products, and facilities
        $users = User::take(5)->get();
        $products = Product::take(10)->get();
        $facilities = Facility::take(5)->get();

        if ($users->isEmpty() || $products->isEmpty() || $facilities->isEmpty()) {
            $this->command->info('Skipping favorites seeding - not enough data available.');
            return;
        }

        // Add some product favorites
        foreach ($users as $user) {
            $randomProducts = $products->random(rand(1, 3));
            foreach ($randomProducts as $product) {
                DB::table('favorites')->insert([
                    'user_id' => $user->id,
                    'favoritable_id' => $product->id,
                    'favoritable_type' => Product::class,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

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
