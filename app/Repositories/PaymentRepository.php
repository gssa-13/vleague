<?php

// app/Repositories/PaymentRepository.php

namespace App\Repositories;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\PaymentCancellation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentRepository
{
    public function all(): Collection
    {
        return Payment::with('competition')->get();
    }

    public function findById(int $id): ?Payment
    {
        return Payment::find($id);
    }

    public function findTrashedById(int $id): ?Payment
    {
        return Payment::withTrashed()->find($id);
    }

    public function activeForCompetition(int $competitionId): Collection
    {
        return Payment::where('competition_id', $competitionId)->get();
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function update(Payment $payment, array $data): Payment
    {
        $payment->update($data);

        return $payment;
    }

    public function delete(Payment $payment): void
    {
        $payment->delete();
    }

    public function restore(Payment $payment): void
    {
        $payment->restore();
    }

    /**
     * Cancels a payment within a transaction: sets cancelled status,
     * records a cancellation entry and soft deletes the payment.
     */
    public function cancel(Payment $payment, string $reason): void
    {
        DB::transaction(function () use ($payment, $reason) {
            $payment->update(['status' => PaymentStatus::Cancelled]);

            PaymentCancellation::create([
                'payment_id' => $payment->id,
                'cancelled_by' => Auth::id(),
                'reason' => $reason,
            ]);

            $payment->delete();
        });
    }
}
