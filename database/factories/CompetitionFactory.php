<?php

// database/factories/CompetitionFactory.php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\Division;
use App\Models\Tournament;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Competition>
 */
class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        $venue = Venue::factory()->create();
        $tournament = Tournament::factory()->create(['venue_id' => $venue->id]);
        $division = Division::factory()->create(['tournament_id' => $tournament->id]);

        return [
            'legacy_id' => null,
            'venue_id' => $venue->id,
            'tournament_id' => $tournament->id,
            'division_id' => $division->id,
            'price_id' => null,
        ];
    }
}
