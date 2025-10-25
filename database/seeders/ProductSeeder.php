<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Facility;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::all();
        $categories = Category::all();
        $cities = \App\Models\City::all();
        $facilityUsers = User::where('primary_role', 'facility')->get();
        
        // Get or create attributes that should be shown in product cards
        $attributes = $this->getOrCreateAttributes();

        $products = [
            [
                'translations' => [
                    'ar' => [
                        'title' => 'شقة فاخرة في الرياض',
                        'description' => 'شقة حديثة ومفروشة بالكامل في حي النرجس، الرياض',
                    ],
                    'en' => [
                        'title' => 'Luxury Apartment in Riyadh',
                        'description' => 'Modern and fully furnished apartment in Al Narjis district, Riyadh',
                    ]
                ],
                'address' => 'حي النرجس، الرياض',
                'attributes' => [
                    'rooms' => 3,
                    'bathrooms' => 2,
                    'area' => 120.50,
                    'floor' => 'الثالث',
                    'floors_count' => 15,
                    'parking_spaces' => 1,
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_verified' => true,
                'rating' => 4.7,
                'rating_count' => 12,
                'available_from' => now(),
                'contact_phone' => '+966501234567',
                'contact_email' => 'info@excellent-realestate.com',
            ],
            [
                'translations' => [
                    'ar' => [
                        'title' => 'فيلا فاخرة في جدة',
                        'description' => 'فيلا مستقلة مع حديقة ومسبح في حي الكورنيش، جدة',
                    ],
                    'en' => [
                        'title' => 'Luxury Villa in Jeddah',
                        'description' => 'Independent villa with garden and pool in Al Corniche district, Jeddah',
                    ]
                ],
                'address' => 'حي الكورنيش، جدة',
                'attributes' => [
                    'rooms' => 5,
                    'bathrooms' => 4,
                    'area' => 350.00,
                    'floor' => 'مستقل',
                    'floors_count' => 2,
                    'parking_spaces' => 3,
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_verified' => true,
                'rating' => 4.9,
                'rating_count' => 8,
                'available_from' => now(),
                'contact_phone' => '+966502345678',
                'contact_email' => 'contact@modern-housing.com',
            ],
            [
                'translations' => [
                    'ar' => [
                        'title' => 'مكتب تجاري في الدمام',
                        'description' => 'مكتب حديث في مركز تجاري مميز في الدمام',
                    ],
                    'en' => [
                        'title' => 'Commercial Office in Dammam',
                        'description' => 'Modern office in a distinguished commercial center in Dammam',
                    ]
                ],
                'address' => 'شارع الملك خالد، الدمام',
                'attributes' => [
                    'rooms' => 2,
                    'bathrooms' => 1,
                    'area' => 80.00,
                    'floor' => 'الأول',
                    'floors_count' => 10,
                    'parking_spaces' => 2,
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_verified' => true,
                'rating' => 4.3,
                'rating_count' => 5,
                'available_from' => now(),
                'contact_phone' => '+966503456789',
                'contact_email' => 'info@luxury-villas.com',
            ],
            [
                'translations' => [
                    'ar' => [
                        'title' => 'محل تجاري في الخبر',
                        'description' => 'محل في موقع مميز في الخبر، مناسب للمشاريع التجارية',
                    ],
                    'en' => [
                        'title' => 'Commercial Shop in Khobar',
                        'description' => 'Shop in a distinguished location in Khobar, suitable for commercial projects',
                    ]
                ],
                'address' => 'شارع الأمير محمد، الخبر',
                'attributes' => [
                    'rooms' => 1,
                    'bathrooms' => 1,
                    'area' => 60.00,
                    'floor' => 'الأرضي',
                    'floors_count' => 3,
                    'parking_spaces' => 1,
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_verified' => true,
                'rating' => 4.1,
                'rating_count' => 3,
                'available_from' => now(),
                'contact_phone' => '+966501234567',
                'contact_email' => 'info@excellent-realestate.com',
            ],
            [
                'translations' => [
                    'ar' => [
                        'title' => 'شقة استوديو في الرياض',
                        'description' => 'شقة استوديو حديثة ومناسبة للشباب في الرياض',
                    ],
                    'en' => [
                        'title' => 'Studio Apartment in Riyadh',
                        'description' => 'Modern studio apartment suitable for young people in Riyadh',
                    ]
                ],
                'address' => 'حي السليمانية، الرياض',
                'attributes' => [
                    'rooms' => 1,
                    'bathrooms' => 1,
                    'area' => 45.00,
                    'floor' => 'الخامس',
                    'floors_count' => 12,
                    'parking_spaces' => 1,
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_verified' => true,
                'rating' => 4.5,
                'rating_count' => 7,
                'available_from' => now(),
                'contact_phone' => '+966502345678',
                'contact_email' => 'contact@modern-housing.com',
            ],
        ];

        foreach ($products as $index => $productData) {
            $facility = $facilities->get($index % $facilities->count());
            $facilityUser = $facilityUsers->get($index % $facilityUsers->count());
            $city = $cities->get($index % $cities->count());

            // Extract translations and attributes from product data
            $translations = $productData['translations'] ?? [];
            $productAttributes = $productData['attributes'] ?? [];
            unset($productData['translations'], $productData['attributes']); // Remove from main data

            // Create product without title/description
            $product = Product::create(array_merge($productData, [
                'facility_id' => $facility->id,
                'owner_user_id' => $facilityUser->id,
                'seller_user_id' => $facilityUser->id,
                'category_id' => $categories->random()->id,
                'city_id' => $city->id,
                'booking_number' => 'BK' . strtoupper(Str::random(8)),
                'latitude' => 24.7136 + (rand(-10, 10) / 100),
                'longitude' => 46.6753 + (rand(-10, 10) / 100),
            ]));

            // Create translations
            foreach ($translations as $locale => $translationData) {
                ProductTranslation::create([
                    'product_id' => $product->id,
                    'locale' => $locale,
                    'title' => $translationData['title'],
                    'description' => $translationData['description'],
                ]);
            }

            // Attach attributes to the product
            $this->attachAttributesToProduct($product, $productAttributes, $attributes);
        }
    }

    /**
     * Get or create attributes that should be shown in product cards
     */
    private function getOrCreateAttributes()
    {
        $attributeTypes = [
            'rooms' => [
                'icon' => 'fas fa-bed',
                'translations' => [
                    'ar' => ['name' => 'عدد الغرف', 'symbol' => 'غ'],
                    'en' => ['name' => 'Number of Rooms', 'symbol' => 'rooms'],
                ]
            ],
            'bathrooms' => [
                'icon' => 'fas fa-bath',
                'translations' => [
                    'ar' => ['name' => 'عدد الحمامات', 'symbol' => 'ح'],
                    'en' => ['name' => 'Number of Bathrooms', 'symbol' => 'bath'],
                ]
            ],
            'area' => [
                'icon' => 'fas fa-ruler-combined',
                'translations' => [
                    'ar' => ['name' => 'المساحة', 'symbol' => 'م²'],
                    'en' => ['name' => 'Area', 'symbol' => 'm²'],
                ]
            ],
            'floor' => [
                'icon' => 'fas fa-building',
                'translations' => [
                    'ar' => ['name' => 'رقم الطابق', 'symbol' => 'ط'],
                    'en' => ['name' => 'Floor Number', 'symbol' => 'floor'],
                ]
            ],
            'floors_count' => [
                'icon' => 'fas fa-layer-group',
                'translations' => [
                    'ar' => ['name' => 'عدد الطوابق', 'symbol' => 'طوابق'],
                    'en' => ['name' => 'Number of Floors', 'symbol' => 'floors'],
                ]
            ],
            'parking_spaces' => [
                'icon' => 'fas fa-car',
                'translations' => [
                    'ar' => ['name' => 'مواقف السيارات', 'symbol' => 'موقف'],
                    'en' => ['name' => 'Parking Spaces', 'symbol' => 'parking'],
                ]
            ],
        ];

        $attributes = [];
        foreach ($attributeTypes as $type => $config) {
            $attribute = \App\Models\Attribute::firstOrCreate(
                ['type' => $type],
                [
                    'type' => $type,
                    'required' => false,
                    'category_id' => null, // Global attribute
                    'icon' => $config['icon'],
                    'Symbol' => null,
                    'show_in_card' => true,
                ]
            );

            // Create translations for the attribute
            foreach ($config['translations'] as $locale => $translationData) {
                \App\Models\AttributeTranslation::firstOrCreate(
                    [
                        'attribute_id' => $attribute->id,
                        'locale' => $locale,
                    ],
                    [
                        'attribute_id' => $attribute->id,
                        'locale' => $locale,
                        'name' => $translationData['name'],
                        'symbol' => $translationData['symbol'],
                    ]
                );
            }

            $attributes[$type] = $attribute;
        }

        return $attributes;
    }

    /**
     * Attach attributes to a product
     */
    private function attachAttributesToProduct($product, $productAttributes, $availableAttributes)
    {
        foreach ($productAttributes as $type => $value) {
            if (isset($availableAttributes[$type])) {
                $attribute = $availableAttributes[$type];
                
                // Attach the attribute with its value
                $product->attributes()->syncWithoutDetaching([
                    $attribute->id => ['value' => $value]
                ]);
            }
        }
    }
}
