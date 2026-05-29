<?php

// database/factories/GameRoleAssignmentFactory.php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Game;
use App\Models\GameRole;
use App\Models\GameRoleAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameRoleAssignment>
 */
class GameRoleAssignmentFactory extends Factory
{
    protected $model = GameRoleAssignment::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'game_id' => Game::factory(),
            'game_role_id' => GameRole::factory(),
            'employee_id' => Employee::factory(),
        ];
    }
}
