<?php

// database/factories/PayrollFactory.php

namespace Database\Factories;

use App\Enums\PayrollStatus;
use App\Models\Payroll;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payroll>
 */
class PayrollFactory extends Factory
{
    protected $model = Payroll::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'venue_id' => Venue::factory(),
            'period' => $this->faker->numerify('202#-0#'),
            'status' => PayrollStatus::Draft,
            'total' => 0,
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => PayrollStatus::Approved]);
    }
}
