<?php

namespace Database\Factories;

use App\Models\AttributeTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttributeTranslation>
 */
class AttributeTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attribute_id' => null,
            'locale' => $this->faker->randomElement(['ar', 'en']),
            'name' => $this->faker->word(),
            'symbol' => $this->faker->randomElement(['ر.س', 'SAR', '$', '€']),
        ];
    }
}
