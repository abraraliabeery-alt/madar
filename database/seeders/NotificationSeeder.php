<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Booking;
use App\Notifications\BookingCreated;
use App\Notifications\NewProductAdded;
use App\Notifications\BookingStatusChanged;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('primary_role', 'client')->take(5)->get();
        $products = Product::take(3)->get();

        if ($users->count() > 0 && $products->count() > 0) {
            foreach ($users as $user) {
                // Create sample booking notifications
                foreach ($products->take(2) as $product) {
                    $booking = Booking::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'total_amount' => rand(1000, 50000),
                        'status' => 'pending',
                    ]);

                    // Send booking created notification
                    $user->notify(new BookingCreated($booking));
                }

                // Create sample product notifications
                foreach ($products->take(1) as $product) {
                    $user->notify(new NewProductAdded($product));
                }

                // Create sample status change notifications
                if ($user->bookings()->count() > 0) {
                    $booking = $user->bookings()->first();
                    $user->notify(new BookingStatusChanged($booking, 'pending', 'confirmed'));
                }
            }
        }
    }
}
