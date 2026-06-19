<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('primary_role', 'client')->get();
        $products = Product::all();
        $offers = Offer::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command?->warn('Missing users/products. Ensure UserSeeder & ProductSeeder ran.');
            return;
        }

        $created = 0;

        foreach ($users as $user) {
            $product = $products->random();
            $offer = $offers->where('product_id', $product->id)->first();

            Booking::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'total_amount' => 1000,
                'payment_method' => 'bank_transfer',
                'expires_at' => now()->addDays(2),
                'is_confirmed' => true,
                'is_paid' => (bool) ($created % 2),
                'status' => 'confirmed',
            ]);

            $created++;
        }

        $this->command?->info("Seeded {$created} bookings.");
    }
}
