<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'books_quantity' => $this->faker->numberBetween(1, 10),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'dimension' => '10x10x10',
            'content' => json_encode(['some content', 'more content', 'even more content']),
            'for_whom' => json_encode(['tag1', 'tag2', 'tag3']),
            'appointment' => $this->faker->word(),
        ];
    }
}
