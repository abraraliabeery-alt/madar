<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\Product;

class ProductAttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::with('category')->get();
        $attributes = Attribute::all()->groupBy('category_id');

        if ($products->isEmpty() || $attributes->isEmpty()) {
            $this->command?->warn('Missing products/attributes. Ensure ProductSeeder & AttributeSeeder ran.');
            return;
        }

        $created = 0;

        foreach ($products as $product) {
            $categoryId = $product->category_id;
            $attrs = $attributes->get($categoryId);

            if (!$attrs || $attrs->isEmpty()) {
                continue;
            }

            foreach ($attrs as $attr) {
                $value = match ($attr->type) {
                    'number' => (string) random_int(1, 100),
                    default => 'قيمة تجريبية',
                };

                $product->attributes()->syncWithoutDetaching([
                    $attr->id => ['value' => $value],
                ]);

                $created++;
            }
        }

        $this->command?->info("Seeded {$created} product attribute values.");
    }
}
