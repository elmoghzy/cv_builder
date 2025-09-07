<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cv_id' => Cv::factory(),
            'order_id' => (string) $this->faker->randomNumber(6),
            'transaction_id' => (string) $this->faker->randomNumber(8),
            'amount' => 100.00,
            'currency' => 'EGP',
            'status' => $this->faker->randomElement(['pending', 'success', 'failed', 'refunded']),
            'paymob_data' => [
                'success' => true,
                'amount_cents' => 10000,
            ],
            'paid_at' => $this->faker->optional(0.7)->dateTime(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'success',
            'paid_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'paid_at' => null,
        ]);
    }
}
