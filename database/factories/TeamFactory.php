<?php

// database/factories/TeamFactory.php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'name' => $this->faker->unique()->company(),
        ];
    }
}
