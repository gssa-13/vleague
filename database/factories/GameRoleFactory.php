<?php

// database/factories/GameRoleFactory.php

namespace Database\Factories;

use App\Models\GameRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameRole>
 */
class GameRoleFactory extends Factory
{
    protected $model = GameRole::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'name' => $this->faker->unique()->randomElement(['Referee', 'Scorer', 'Linesman', 'Timekeeper', 'Coordinator']),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
