<?php

namespace Database\Factories;

use App\Models\CashDrawerSession;
use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashDrawerSession>
 */
class CashDrawerSessionFactory extends Factory
{
    protected $model = CashDrawerSession::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'user_id' => User::factory(),
            'opening_balance' => $this->faker->randomFloat(2, 100, 5000),
            'closing_balance' => null,
            'cash_sales' => 0,
            'cash_refunds' => 0,
            'cash_expenses' => 0,
            'expected_cash' => 0,
            'discrepancy' => 0,
            'opening_note' => $this->faker->optional()->sentence(),
            'closing_note' => null,
            'status' => 'open',
            'opened_at' => now(),
            'closed_at' => null,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn(array $attributes) => [
            'closing_balance' => $this->faker->randomFloat(2, 100, 5000),
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }
}
