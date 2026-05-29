<?php

// database/factories/EmployeePayrollFactory.php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeePayroll;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmployeePayroll>
 */
class EmployeePayrollFactory extends Factory
{
    protected $model = EmployeePayroll::class;

    public function definition(): array
    {
        $base = $this->faker->randomFloat(2, 5000, 20000);

        return [
            'legacy_id' => null,
            'payroll_id' => Payroll::factory(),
            'employee_id' => Employee::factory(),
            'base_salary' => $base,
            'net_salary' => $base,
        ];
    }
}
