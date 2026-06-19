<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Offer;
use App\Models\OfferTranslation;
use App\Models\Product;
use App\Models\User;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $facilities = Facility::all();
        $users = User::all();

        if ($products->isEmpty() || $facilities->isEmpty() || $users->isEmpty()) {
            $this->command?->warn('Missing products/facilities/users. Ensure ProductSeeder & FacilitySeeder ran.');
            return;
        }

        $locales = array_keys((array) config('locales.available'));

        $created = 0;

        foreach ($products as $index => $product) {
            $facilityId = $product->facility_id ?? $facilities->random()->id;
            $createdBy = $users->random()->id;

            $offer = Offer::create([
                'product_id' => $product->id,
                'offer_type' => ($index % 2 === 0) ? 'sale' : 'rent_monthly',
                'price' => ($index % 2 === 0) ? 450000 : 3500,
                'deposit_amount' => ($index % 2 === 0) ? 25000 : 5000,
                'commission_rate' => 0.025,
                'commission_amount' => ($index % 2 === 0) ? 11250 : 87.50,
                'is_active' => true,
                'is_featured' => ($index % 5) === 0,
                'valid_from' => now()->subDays(3)->toDateString(),
                'valid_to' => now()->addDays(30)->toDateString(),
                'terms_conditions' => 'شروط تجريبية للعقد.',
                'facility_id' => $facilityId,
                'created_by' => $createdBy,
                'offer_title' => 'عرض تجريبي',
                'offer_description' => 'وصف تجريبي للعرض.',
                'payment_plan' => null,
                'special_conditions' => null,
                'marketing_notes' => null,
                'priority' => 5,
                'auto_renew' => false,
                'min_contract_duration' => null,
                'max_contract_duration' => null,
            ]);

            foreach ($locales as $locale) {
                OfferTranslation::updateOrCreate(
                    ['offer_id' => $offer->id, 'locale' => $locale],
                    [
                        'offer_title' => 'عرض تجريبي',
                        'offer_description' => 'تفاصيل تجريبية للعرض.',
                        'terms_conditions' => 'شروط تجريبية.',
                    ]
                );
            }

            $created++;
        }

        $this->command?->info("Seeded {$created} offers.");
    }
}
