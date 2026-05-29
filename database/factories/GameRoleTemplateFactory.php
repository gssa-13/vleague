<?php

// database/factories/GameRoleTemplateFactory.php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\GameRole;
use App\Models\GameRoleTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameRoleTemplate>
 */
class GameRoleTemplateFactory extends Factory
{
    protected $model = GameRoleTemplate::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'competition_id' => Competition::factory(),
            'game_role_id' => GameRole::factory(),
            'required_count' => $this->faker->numberBetween(1, 3),
        ];
    }
}
