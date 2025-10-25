<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing attributes to show in cards
        $attributesToShow = [
            'rooms' => 'fas fa-bed',
            'bathrooms' => 'fas fa-bath',
            'area' => 'fas fa-ruler-combined',
            'floor' => 'fas fa-building',
            'floors_count' => 'fas fa-layer-group',
            'parking_spaces' => 'fas fa-car',
        ];

        foreach ($attributesToShow as $type => $icon) {
            Attribute::where('type', $type)->update([
                'show_in_card' => true,
                'icon' => $icon
            ]);
        }

        // You can also create new attributes if they don't exist
        $this->createAttributeIfNotExists('rooms', 'fas fa-bed', true);
        $this->createAttributeIfNotExists('bathrooms', 'fas fa-bath', true);
        $this->createAttributeIfNotExists('area', 'fas fa-ruler-combined', true);
        $this->createAttributeIfNotExists('floor', 'fas fa-building', true);
        $this->createAttributeIfNotExists('floors_count', 'fas fa-layer-group', true);
        $this->createAttributeIfNotExists('parking_spaces', 'fas fa-car', true);
    }

    private function createAttributeIfNotExists($type, $icon, $showInCard = false)
    {
        if (!Attribute::where('type', $type)->exists()) {
            $attribute = Attribute::create([
                'type' => $type,
                'required' => false,
                'category_id' => null, // Global attribute
                'icon' => $icon,
                'Symbol' => null,
                'show_in_card' => $showInCard,
            ]);

            // Create translations for both Arabic and English
            $translations = [
                'ar' => [
                    'name' => $this->getArabicName($type),
                    'symbol' => $this->getArabicSymbol($type),
                ],
                'en' => [
                    'name' => $this->getEnglishName($type),
                    'symbol' => $this->getEnglishSymbol($type),
                ],
            ];

            foreach ($translations as $locale => $translationData) {
                $attribute->translations()->create([
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'symbol' => $translationData['symbol'],
                ]);
            }
        }
    }

    /**
     * Get Arabic name for attribute types
     */
    private function getArabicName($type)
    {
        $translations = [
            'rooms' => 'عدد الغرف',
            'bathrooms' => 'عدد الحمامات',
            'area' => 'المساحة',
            'floor' => 'رقم الطابق',
            'floors_count' => 'عدد الطوابق',
            'parking_spaces' => 'مواقف السيارات',
        ];

        return $translations[$type] ?? $type;
    }

    /**
     * Get Arabic symbol for attribute types
     */
    private function getArabicSymbol($type)
    {
        $translations = [
            'rooms' => 'غ',
            'bathrooms' => 'ح',
            'area' => 'م²',
            'floor' => 'ط',
            'floors_count' => 'طوابق',
            'parking_spaces' => 'موقف',
        ];

        return $translations[$type] ?? $type;
    }

    /**
     * Get English name for attribute types
     */
    private function getEnglishName($type)
    {
        $translations = [
            'rooms' => 'Number of Rooms',
            'bathrooms' => 'Number of Bathrooms',
            'area' => 'Area',
            'floor' => 'Floor Number',
            'floors_count' => 'Number of Floors',
            'parking_spaces' => 'Parking Spaces',
        ];

        return $translations[$type] ?? $type;
    }

    /**
     * Get English symbol for attribute types
     */
    private function getEnglishSymbol($type)
    {
        $translations = [
            'rooms' => 'rooms',
            'bathrooms' => 'bath',
            'area' => 'm²',
            'floor' => 'floor',
            'floors_count' => 'floors',
            'parking_spaces' => 'parking',
        ];

        return $translations[$type] ?? $type;
    }
}
