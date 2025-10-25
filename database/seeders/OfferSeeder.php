<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use App\Models\Facility;
use Carbon\Carbon;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::where('primary_role', 'facility')->get();
        $facilities = Facility::all();

        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No products or users found. Please run ProductSeeder and UserSeeder first.');
            return;
        }

        $offerTypes = ['sale', 'rent_monthly', 'rent_yearly', 'rent_daily'];
        
        // Sample offer data with realistic pricing
        $offerTemplates = [
            // Sale offers
            [
                'type' => 'sale',
                'price_range' => [500000, 5000000],
                'deposit_range' => [50000, 500000],
                'commission_rate' => 0.025, // 2.5%
                'titles' => [
                    'ar' => 'عرض بيع حصري',
                    'en' => 'Exclusive Sale Offer'
                ],
                'descriptions' => [
                    'ar' => 'عرض بيع حصري مع ضمانات كاملة وخدمات ما بعد البيع',
                    'en' => 'Exclusive sale offer with full guarantees and after-sales services'
                ]
            ],
            // Monthly rent offers
            [
                'type' => 'rent_monthly',
                'price_range' => [2000, 15000],
                'deposit_range' => [2000, 15000],
                'commission_rate' => 0.05, // 5%
                'titles' => [
                    'ar' => 'عرض إيجار شهري',
                    'en' => 'Monthly Rent Offer'
                ],
                'descriptions' => [
                    'ar' => 'عرض إيجار شهري مع خدمات صيانة مجانية',
                    'en' => 'Monthly rent offer with free maintenance services'
                ]
            ],
            // Yearly rent offers
            [
                'type' => 'rent_yearly',
                'price_range' => [20000, 150000],
                'deposit_range' => [20000, 150000],
                'commission_rate' => 0.03, // 3%
                'titles' => [
                    'ar' => 'عرض إيجار سنوي',
                    'en' => 'Yearly Rent Offer'
                ],
                'descriptions' => [
                    'ar' => 'عرض إيجار سنوي مع خصم خاص للدفع السنوي',
                    'en' => 'Yearly rent offer with special discount for annual payment'
                ]
            ],
            // Daily rent offers
            [
                'type' => 'rent_daily',
                'price_range' => [100, 800],
                'deposit_range' => [500, 2000],
                'commission_rate' => 0.1, // 10%
                'titles' => [
                    'ar' => 'عرض إيجار يومي',
                    'en' => 'Daily Rent Offer'
                ],
                'descriptions' => [
                    'ar' => 'عرض إيجار يومي مثالي للزيارات القصيرة',
                    'en' => 'Daily rent offer perfect for short visits'
                ]
            ]
        ];

        $createdOffers = 0;
        $maxOffersPerProduct = 3; // Maximum offers per product

        foreach ($products as $product) {
            // Randomly decide how many offers to create for this product (1-3)
            $numberOfOffers = rand(1, $maxOffersPerProduct);
            $selectedTypes = collect($offerTypes)->random($numberOfOffers)->toArray();

            foreach ($selectedTypes as $offerType) {
                $template = collect($offerTemplates)->firstWhere('type', $offerType);
                
                if (!$template) continue;

                // Generate random price within range
                $price = rand($template['price_range'][0], $template['price_range'][1]);
                $depositAmount = rand($template['deposit_range'][0], $template['deposit_range'][1]);
                
                // Calculate commission
                $commissionAmount = $price * $template['commission_rate'];
                
                // Random validity dates
                $validFrom = Carbon::now()->subDays(rand(0, 30));
                $validTo = Carbon::now()->addDays(rand(30, 365));
                
                // Random user and facility
                $user = $users->random();
                $facility = $facilities->random();

                $offer = Offer::create([
                    'product_id' => $product->id,
                    'offer_type' => $offerType,
                    'price' => $price,
                    'deposit_amount' => $depositAmount,
                    'commission_rate' => $template['commission_rate'],
                    'commission_amount' => $commissionAmount,
                    'is_active' => rand(0, 10) > 1, // 90% chance of being active
                    'is_featured' => rand(0, 10) > 7, // 30% chance of being featured
                    'valid_from' => $validFrom,
                    'valid_to' => $validTo,
                    'terms_conditions' => $this->generateTermsConditions($offerType),
                    'facility_id' => $facility->id,
                    'created_by' => $user->id,
                    'offer_title' => $template['titles']['en'],
                    'offer_description' => $template['descriptions']['en'],
                    'special_conditions' => $this->generateSpecialConditions($offerType),
                    'marketing_notes' => $this->generateMarketingNotes($offerType),
                    'priority' => rand(1, 10),
                    'auto_renew' => $offerType !== 'sale' && rand(0, 1),
                    'min_contract_duration' => $this->getMinContractDuration($offerType),
                    'max_contract_duration' => $this->getMaxContractDuration($offerType),
                ]);

                // Create offer translations (commented out for now due to table structure issues)
                // $this->createOfferTranslations($offer, $template);

                $createdOffers++;
            }
        }

        $this->command->info("Created {$createdOffers} offers for {$products->count()} products.");
    }

    /**
     * Generate terms and conditions based on offer type
     */
    private function generateTermsConditions($offerType)
    {
        $terms = [
            'sale' => 'Terms: Full payment required within 30 days. Property inspection available. All legal fees included.',
            'rent_monthly' => 'Terms: Monthly payment in advance. Security deposit required. 30-day notice for termination.',
            'rent_yearly' => 'Terms: Annual payment with 5% discount. Security deposit required. 60-day notice for termination.',
            'rent_daily' => 'Terms: Daily payment in advance. No security deposit required. 24-hour notice for cancellation.'
        ];

        return $terms[$offerType] ?? 'Standard terms and conditions apply.';
    }

    /**
     * Generate special conditions based on offer type
     */
    private function generateSpecialConditions($offerType)
    {
        $conditions = [
            'sale' => 'Special financing available. Free legal consultation included.',
            'rent_monthly' => 'Free utilities included. Pet-friendly property.',
            'rent_yearly' => 'Free parking space. Gym access included.',
            'rent_daily' => 'Free WiFi. Housekeeping service available.'
        ];

        return $conditions[$offerType] ?? 'Special conditions may apply.';
    }

    /**
     * Generate marketing notes based on offer type
     */
    private function generateMarketingNotes($offerType)
    {
        $notes = [
            'sale' => 'Perfect investment opportunity. High rental yield potential.',
            'rent_monthly' => 'Prime location with excellent amenities. Great for professionals.',
            'rent_yearly' => 'Long-term stability. Perfect for families.',
            'rent_daily' => 'Flexible accommodation. Perfect for business travelers.'
        ];

        return $notes[$offerType] ?? 'Contact us for more information.';
    }

    /**
     * Get minimum contract duration based on offer type
     */
    private function getMinContractDuration($offerType)
    {
        return match($offerType) {
            'sale' => 0,
            'rent_monthly' => 1,
            'rent_yearly' => 12,
            'rent_daily' => 1,
            default => 1
        };
    }

    /**
     * Get maximum contract duration based on offer type
     */
    private function getMaxContractDuration($offerType)
    {
        return match($offerType) {
            'sale' => 0,
            'rent_monthly' => 24,
            'rent_yearly' => 60,
            'rent_daily' => 30,
            default => 12
        };
    }

    /**
     * Create offer translations
     */
    private function createOfferTranslations($offer, $template)
    {
        $translations = [
            [
                'locale' => 'ar',
                'offer_title' => $template['titles']['ar'],
                'offer_description' => $template['descriptions']['ar'],
                'terms_conditions' => $this->getArabicTerms($offer->offer_type),
            ],
            [
                'locale' => 'en',
                'offer_title' => $template['titles']['en'],
                'offer_description' => $template['descriptions']['en'],
                'terms_conditions' => $offer->terms_conditions,
            ]
        ];

        foreach ($translations as $translation) {
            \App\Models\OfferTranslation::create([
                'offer_id' => $offer->id,
                'locale' => $translation['locale'],
                'offer_title' => $translation['offer_title'],
                'offer_description' => $translation['offer_description'],
                'terms_conditions' => $translation['terms_conditions'],
            ]);
        }
    }

    /**
     * Get Arabic terms and conditions
     */
    private function getArabicTerms($offerType)
    {
        $terms = [
            'sale' => 'الشروط: الدفع الكامل مطلوب خلال 30 يوماً. فحص العقار متاح. جميع الرسوم القانونية مشمولة.',
            'rent_monthly' => 'الشروط: الدفع الشهري مقدماً. مطلوب وديعة أمان. إشعار 30 يوماً للإنهاء.',
            'rent_yearly' => 'الشروط: الدفع السنوي مع خصم 5%. مطلوب وديعة أمان. إشعار 60 يوماً للإنهاء.',
            'rent_daily' => 'الشروط: الدفع اليومي مقدماً. لا توجد وديعة أمان مطلوبة. إشعار 24 ساعة للإلغاء.'
        ];

        return $terms[$offerType] ?? 'تطبق الشروط والأحكام المعيارية.';
    }
}
