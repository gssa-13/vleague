<?php

// database/factories/CompetitionTeamFactory.php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompetitionTeam>
 */
class CompetitionTeamFactory extends Factory
{
    protected $model = CompetitionTeam::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'competition_id' => Competition::factory(),
            'team_id' => Team::factory(),
            'group_letter' => $this->faker->optional()->randomElement(['A', 'B', 'C']),
            'assigned_points' => 0,
        ];
    }
}
