<?php

// database/factories/PlayerSanctionFactory.php

namespace Database\Factories;

use App\Models\Player;
use App\Models\PlayerSanction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlayerSanction>
 */
class PlayerSanctionFactory extends Factory
{
    protected $model = PlayerSanction::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'player_id' => Player::factory(),
            'competition_team_id' => null,
            'competition_id' => null,
            'reason' => $this->faker->sentence(3),
            'sanctioned_games' => $this->faker->numberBetween(1, 5),
            'served_games' => 0,
        ];
    }

    public function served(): static
    {
        return $this->state(fn (array $attributes) => [
            'served_games' => $attributes['sanctioned_games'] ?? 1,
        ]);
    }
}
