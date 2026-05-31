<?php

// database/factories/PaymentExpenseFactory.php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Models\ExpenseCategory;
use App\Models\PaymentExpense;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentExpense>
 */
class PaymentExpenseFactory extends Factory
{
    protected $model = PaymentExpense::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'venue_id' => Venue::factory(),
            'expense_category_id' => ExpenseCategory::factory(),
            'concept' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'payment_method' => $this->faker->randomElement(PaymentMethod::cases()),
            'spent_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
