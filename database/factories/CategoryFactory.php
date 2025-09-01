<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'icon' => $this->faker->imageUrl(50, 50),
            'image' => $this->faker->imageUrl(200, 200),
            'is_active' => true,
            'is_featured' => $this->faker->boolean(),
            'order' => $this->faker->numberBetween(1, 100),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
