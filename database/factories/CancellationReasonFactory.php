<?php

// database/factories/CancellationReasonFactory.php

namespace Database\Factories;

use App\Models\CancellationReason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CancellationReason>
 */
class CancellationReasonFactory extends Factory
{
    protected $model = CancellationReason::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'name' => $this->faker->unique()->sentence(3),
        ];
    }
}
