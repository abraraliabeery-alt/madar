<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['text', 'number', 'select', 'boolean']),
            'required' => $this->faker->boolean(),
            'category_id' => null,
            'icon' => $this->faker->imageUrl(50, 50),
            'Symbol' => $this->faker->randomElement(['ر.س', 'SAR', '$', '€']),
            'show_in_card' => $this->faker->boolean(),
        ];
    }
}
