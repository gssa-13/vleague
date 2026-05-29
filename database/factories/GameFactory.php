<?php

// database/factories/GameFactory.php

namespace Database\Factories;

use App\Enums\GameStatus;
use App\Enums\GameType;
use App\Models\Competition;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'competition_id' => Competition::factory(),
            'home_team_id' => null,
            'away_team_id' => null,
            'game_type' => GameType::Match,
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d H:i:s'),
            'field_number' => $this->faker->numberBetween(1, 6),
            'status' => GameStatus::Scheduled,
            'home_score' => null,
            'away_score' => null,
            'matchday' => $this->faker->numberBetween(1, 20),
            'cancellation_reason_id' => null,
        ];
    }

    public function played(): static
    {
        return $this->state([
            'status' => GameStatus::Played,
            'home_score' => $this->faker->numberBetween(0, 5),
            'away_score' => $this->faker->numberBetween(0, 5),
        ]);
    }

    public function practice(): static
    {
        return $this->state(['game_type' => GameType::Practice]);
    }
}
