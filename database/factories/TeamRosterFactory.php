<?php

// database/factories/TeamRosterFactory.php

namespace Database\Factories;

use App\Models\CompetitionTeam;
use App\Models\Player;
use App\Models\TeamRoster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamRoster>
 */
class TeamRosterFactory extends Factory
{
    protected $model = TeamRoster::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'competition_team_id' => CompetitionTeam::factory(),
            'player_id' => Player::factory(),
            'jersey_number' => $this->faker->numberBetween(1, 99),
            'is_captain' => false,
            'is_wildcard' => false,
        ];
    }

    public function captain(): static
    {
        return $this->state(['is_captain' => true]);
    }

    public function wildcard(): static
    {
        return $this->state(['is_wildcard' => true]);
    }
}
