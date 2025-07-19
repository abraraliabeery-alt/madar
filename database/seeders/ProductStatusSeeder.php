<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;

class ProductStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $statuses = Status::all();
        $users = User::all();

        foreach ($products as $index => $product) {
            // Assign a random status to each product
            $status = $statuses->random();
            $user = $users->random();

            $product->statuses()->attach($status->id, [
                'notes' => 'تم تعيين الحالة تلقائياً',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
