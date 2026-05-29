<?php

// database/factories/DivisionFactory.php

namespace Database\Factories;

use App\Enums\DivisionDay;
use App\Models\Division;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Division>
 */
class DivisionFactory extends Factory
{
    protected $model = Division::class;

    public function definition(): array
    {
        $day = $this->faker->randomElement(DivisionDay::cases());
        $fieldNumber = $this->faker->numberBetween(1, 6);
        $groupLetter = $this->faker->optional(0.5)->randomElement(['A', 'B', 'C', 'D']);
        $name = ucfirst($day->value).' '.$fieldNumber.($groupLetter ?? '');

        return [
            'legacy_id' => null,
            'tournament_id' => Tournament::factory(),
            'name' => $name,
            'day' => $day,
            'field_number' => $fieldNumber,
            'group_letter' => $groupLetter,
        ];
    }
}
