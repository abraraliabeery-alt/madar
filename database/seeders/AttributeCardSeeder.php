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
            'activity' => 'fas fa-briefcase',
            'delivery_date' => 'fas fa-calendar-check',
            'duration_days' => 'fas fa-hourglass-half',
            'warranty_months' => 'fas fa-shield-alt',
            'budget' => 'fas fa-sack-dollar',
        ];

        foreach ($attributesToShow as $type => $icon) {
            Attribute::where('type', $type)->update([
                'show_in_card' => true,
                'icon' => $icon
            ]);
        }

        // You can also create new attributes if they don't exist
        $this->createAttributeIfNotExists('activity', 'fas fa-briefcase', true);
        $this->createAttributeIfNotExists('delivery_date', 'fas fa-calendar-check', true);
        $this->createAttributeIfNotExists('duration_days', 'fas fa-hourglass-half', true);
        $this->createAttributeIfNotExists('warranty_months', 'fas fa-shield-alt', true);
        $this->createAttributeIfNotExists('budget', 'fas fa-sack-dollar', true);
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
            'activity' => 'النشاط',
            'delivery_date' => 'موعد التسليم',
            'duration_days' => 'مدة التنفيذ',
            'warranty_months' => 'الضمان',
            'budget' => 'الميزانية',
        ];

        return $translations[$type] ?? $type;
    }

    /**
     * Get Arabic symbol for attribute types
     */
    private function getArabicSymbol($type)
    {
        $translations = [
            'activity' => '',
            'delivery_date' => '',
            'duration_days' => 'يوم',
            'warranty_months' => 'شهر',
            'budget' => 'ر.س',
        ];

        return $translations[$type] ?? $type;
    }

    /**
     * Get English name for attribute types
     */
    private function getEnglishName($type)
    {
        $translations = [
            'activity' => 'Activity',
            'delivery_date' => 'Delivery Date',
            'duration_days' => 'Duration',
            'warranty_months' => 'Warranty',
            'budget' => 'Budget',
        ];

        return $translations[$type] ?? $type;
    }

    /**
     * Get English symbol for attribute types
     */
    private function getEnglishSymbol($type)
    {
        $translations = [
            'activity' => '',
            'delivery_date' => '',
            'duration_days' => 'days',
            'warranty_months' => 'months',
            'budget' => 'SAR',
        ];

        return $translations[$type] ?? $type;
    }
}
