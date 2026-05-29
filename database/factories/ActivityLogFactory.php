<?php

// database/factories/ActivityLogFactory.php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'action' => $this->faker->randomElement(['created', 'updated', 'deleted', 'restored']),
            'model' => 'App\\Models\\Role',
            'table_name' => 'roles',
            'record_id' => $this->faker->numberBetween(1, 1000),
            'column_name' => $this->faker->optional()->word(),
            'old_value' => $this->faker->optional()->sentence(),
            'new_value' => $this->faker->optional()->sentence(),
            'user_id' => User::factory(),
            'user_ip' => $this->faker->ipv4(),
        ];
    }

    public function created(): static
    {
        return $this->state(fn () => [
            'action' => 'created',
            'column_name' => null,
            'old_value' => null,
            'new_value' => json_encode(['name' => $this->faker->word(), 'guard_name' => 'web']),
        ]);
    }

    public function updated(): static
    {
        return $this->state(fn () => [
            'action' => 'updated',
            'column_name' => 'name',
            'old_value' => $this->faker->word(),
            'new_value' => $this->faker->word(),
        ]);
    }

    public function deleted(): static
    {
        return $this->state(fn () => [
            'action' => 'deleted',
            'column_name' => null,
            'new_value' => null,
            'old_value' => json_encode(['name' => $this->faker->word(), 'guard_name' => 'web']),
        ]);
    }

    public function restored(): static
    {
        return $this->state(fn () => [
            'action' => 'restored',
            'column_name' => null,
            'old_value' => null,
            'new_value' => json_encode(['name' => $this->faker->word(), 'guard_name' => 'web']),
        ]);
    }
}
