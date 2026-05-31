<?php

// app/Services/Reports/FinancialReportService.php

namespace App\Services\Reports;

use App\Models\Payment;
use App\Models\PaymentExpense;
use App\Repositories\Reports\ReportRepository;

class FinancialReportService
{
    public function __construct(
        private readonly ReportRepository $repository,
    ) {}

    /**
     * Generates a financial report for a venue.
     * By default excludes soft-deleted records; administrative reports
     * may include them via $withTrashed.
     *
     * @return array{total_income: float, total_expenses: float, net: float}
     */
    public function generate(int $venueId, bool $withTrashed = false): array
    {
        $income = (float) $this->repository->paymentsForVenue($venueId, $withTrashed)
            ->sum(fn (Payment $payment) => (float) $payment->paid_amount);

        $expenses = (float) $this->repository->expensesForVenue($venueId, $withTrashed)
            ->sum(fn (PaymentExpense $expense) => (float) $expense->amount);

        return [
            'total_income' => $income,
            'total_expenses' => $expenses,
            'net' => $income - $expenses,
        ];
    }
}
