<?php

// database/factories/DiscountFactory.php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Discount>
 */
class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'price_id' => Price::factory(),
            'percentage' => $this->faker->numberBetween(5, 50),
            'valid_from' => now()->subDays(30)->toDateString(),
            'valid_until' => now()->addDays(30)->toDateString(),
        ];
    }
}
