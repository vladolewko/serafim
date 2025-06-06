<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->numberBetween(1, 100),
            'total_amount' => $this->faker->randomFloat(2, 50, 5000),
            'payment_method' => $this->faker->randomElement(['cash_on_delivery', 'card', 'bank_transfer']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'recipient_first_name' => $this->faker->firstName,
            'recipient_last_name' => $this->faker->lastName,
            'recipient_middle_name' => $this->faker->optional()->firstName,
            'recipient_phone' => '380' . $this->faker->numerify('#########'),
            'recipient_email' => $this->faker->optional()->safeEmail,
            'recipient_city_ref' => $this->faker->uuid,
            'recipient_warehouse_ref' => $this->faker->uuid,
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'seats_amount' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->optional()->sentence,
            'ttn_number' => $this->faker->optional()->numerify('###########'),
            'ttn_ref' => $this->faker->optional()->uuid,
            'shipping_cost' => $this->faker->optional()->randomFloat(2, 20, 200),
            'notes' => $this->faker->optional()->paragraph,
            'shipped_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PENDING,
            'ttn_number' => null,
            'ttn_ref' => null,
            'shipped_at' => null,
        ]);
    }

    /**
     * Indicate that the order is shipped.
     */
    public function shipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_SHIPPED,
            'ttn_number' => $this->faker->numerify('###########'),
            'ttn_ref' => $this->faker->uuid,
            'shipped_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the order is delivered.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_DELIVERED,
            'ttn_number' => $this->faker->numerify('###########'),
            'ttn_ref' => $this->faker->uuid,
            'shipped_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
        ]);
    }

    /**
     * Indicate cash on delivery payment.
     */
    public function cashOnDelivery(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => Order::PAYMENT_CASH_ON_DELIVERY,
        ]);
    }
}