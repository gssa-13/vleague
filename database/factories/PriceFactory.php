<?php

// database/factories/PriceFactory.php

namespace Database\Factories;

use App\Enums\PriceStatus;
use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Price>
 */
class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'name' => $this->faker->words(2, true),
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'currency' => 'MXN',
            'status' => PriceStatus::Active,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['status' => PriceStatus::Inactive]);
    }
}
