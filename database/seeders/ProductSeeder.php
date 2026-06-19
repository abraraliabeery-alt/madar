<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\City;
use App\Models\Facility;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Project;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::all();
        $categories = Category::all();
        $cities = City::all();
        $projects = Project::all();

        if ($facilities->isEmpty()) {
            $this->command?->warn('No facilities found. Please run FacilitySeeder first.');
            return;
        }

        $locales = array_keys((array) config('locales.available'));

        $created = 0;

        foreach ($facilities as $facility) {
            $ownerUser = User::query()->where('facility_id', $facility->id)->first() ?? User::query()->inRandomOrder()->first();
            $facilityProjects = $projects->where('facility_id', $facility->id);

            for ($i = 1; $i <= 6; $i++) {
                $project = $facilityProjects->isNotEmpty() ? $facilityProjects->random() : null;

                $product = Product::create([
                    'facility_id' => $facility->id,
                    'owner_user_id' => $ownerUser?->id,
                    'seller_user_id' => $ownerUser?->id,
                    'category_id' => $categories->isNotEmpty() ? $categories->random()->id : null,
                    'city_id' => $cities->isNotEmpty() ? $cities->random()->id : null,
                    'project_id' => $project?->id,
                    'address' => "عنوان تجريبي {$i}",
                    'is_active' => true,
                    'is_featured' => ($i % 3) === 0,
                    'is_verified' => true,
                    'listing_type' => 'sale',
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'area' => 150 + ($i * 10),
                    'available_for_sale' => true,
                    'available_for_rent' => ($i % 2) === 0,
                    'main_image' => null,
                    'additional_info' => 'بيانات تجريبية لعرض المنتج وربطه بالمشروع والمنشأة.',
                ]);

                foreach ($locales as $locale) {
                    ProductTranslation::create([
                        'product_id' => $product->id,
                        'locale' => $locale,
                        'title' => "منتج تجريبي {$i} — {$facility->name}",
                        'description' => 'وصف تجريبي للمنتج لاستخدامه في العرض والبحث والتقارير.',
                    ]);
                }

                $created++;
            }
        }

        $this->command?->info("Seeded {$created} products.");
    }
}
