<?php

// database/factories/PayrollAdjustmentFactory.php

namespace Database\Factories;

use App\Enums\PayrollAdjustmentType;
use App\Models\EmployeePayroll;
use App\Models\PayrollAdjustment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollAdjustment>
 */
class PayrollAdjustmentFactory extends Factory
{
    protected $model = PayrollAdjustment::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'employee_payroll_id' => EmployeePayroll::factory(),
            'type' => $this->faker->randomElement(PayrollAdjustmentType::cases()),
            'amount' => $this->faker->randomFloat(2, 100, 3000),
            'concept' => $this->faker->sentence(2),
        ];
    }

    public function addition(): static
    {
        return $this->state(['type' => PayrollAdjustmentType::Addition]);
    }

    public function deduction(): static
    {
        return $this->state(['type' => PayrollAdjustmentType::Deduction]);
    }
}
