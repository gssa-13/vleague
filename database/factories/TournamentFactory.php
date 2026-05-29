<?php

// database/factories/TournamentFactory.php

namespace Database\Factories;

use App\Enums\TournamentStatus;
use App\Models\Tournament;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tournament>
 */
class TournamentFactory extends Factory
{
    protected $model = Tournament::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'venue_id' => Venue::factory(),
            'name' => 'Copa '.$this->faker->unique()->year(),
            'starts_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'ends_at' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'status' => TournamentStatus::Active,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['status' => TournamentStatus::Inactive]);
    }

    public function finished(): static
    {
        return $this->state(['status' => TournamentStatus::Finished]);
    }
}
