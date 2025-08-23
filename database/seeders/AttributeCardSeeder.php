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
            Attribute::create([
                'type' => $type,
                'required' => false,
                'category_id' => null, // Global attribute
                'icon' => $icon,
                'Symbol' => null,
                'show_in_card' => $showInCard,
            ]);
        }
    }
}
