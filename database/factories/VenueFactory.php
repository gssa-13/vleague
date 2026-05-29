<?php

// database/factories/VenueFactory.php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venue>
 */
class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'name' => $this->faker->unique()->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'max_fields' => $this->faker->numberBetween(1, 8),
            'match_duration_minutes' => $this->faker->randomElement([40, 50, 60]),
            'advance_booking_days' => $this->faker->numberBetween(0, 14),
        ];
    }
}
