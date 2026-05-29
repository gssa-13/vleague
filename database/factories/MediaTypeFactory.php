<?php

// database/factories/MediaTypeFactory.php

namespace Database\Factories;

use App\Models\MediaType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaType>
 */
class MediaTypeFactory extends Factory
{
    protected $model = MediaType::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'name' => $this->faker->unique()->word(),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
