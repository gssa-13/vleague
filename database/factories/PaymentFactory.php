<?php

// database/factories/PaymentFactory.php

namespace Database\Factories;

use App\Enums\PaymentConcept;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Competition;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 100, 5000);
        $paid = $this->faker->randomFloat(2, 0, $amount);

        return [
            'legacy_id' => null,
            'competition_id' => Competition::factory(),
            'competition_team_id' => null,
            'concept' => $this->faker->randomElement(PaymentConcept::cases()),
            'payment_method' => $this->faker->randomElement(PaymentMethod::cases()),
            'status' => PaymentStatus::Partial,
            'amount' => $amount,
            'paid_amount' => $paid,
            'debt' => $amount - $paid,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Paid,
            'paid_amount' => $attributes['amount'] ?? 0,
            'debt' => 0,
        ]);
    }
}
