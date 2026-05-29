<?php

// app/Services/PaymentService.php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Database\Eloquent\Collection;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Payment
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Payment
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Payment
    {
        $data = $this->computeBalanceFields($data);

        return $this->repository->create($data);
    }

    public function update(Payment $payment, array $data): Payment
    {
        $data = $this->computeBalanceFields($data);

        return $this->repository->update($payment, $data);
    }

    public function delete(Payment $payment): void
    {
        $this->repository->delete($payment);
    }

    public function restore(Payment $payment): void
    {
        $this->repository->restore($payment);
    }

    public function cancel(Payment $payment, string $reason): void
    {
        $this->repository->cancel($payment, $reason);
    }

    /**
     * Active balance: sum of paid amounts of non-cancelled payments.
     * Cancelled payments are soft-deleted, so the repository excludes them.
     */
    public function calculateBalance(int $competitionId): float
    {
        return (float) $this->repository->activeForCompetition($competitionId)
            ->sum(fn (Payment $payment) => (float) $payment->paid_amount);
    }

    /**
     * Total pending debt across active payments for a competition.
     */
    public function calculatePendingDebt(int $competitionId): float
    {
        return (float) $this->repository->activeForCompetition($competitionId)
            ->sum(fn (Payment $payment) => (float) $payment->debt);
    }

    /**
     * Computes debt and status from amount and paid_amount.
     */
    private function computeBalanceFields(array $data): array
    {
        $amount = (float) ($data['amount'] ?? 0);
        $paid = (float) ($data['paid_amount'] ?? 0);
        $debt = max($amount - $paid, 0);

        $data['debt'] = $debt;
        $data['status'] = $this->resolveStatus($amount, $paid, $debt);

        return $data;
    }

    private function resolveStatus(float $amount, float $paid, float $debt): PaymentStatus
    {
        if ($paid <= 0) {
            return PaymentStatus::Pending;
        }

        if ($debt <= 0) {
            return PaymentStatus::Paid;
        }

        return PaymentStatus::Partial;
    }
}
