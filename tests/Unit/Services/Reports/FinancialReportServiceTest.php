<?php

// tests/Unit/Services/Reports/FinancialReportServiceTest.php

use App\Models\Payment;
use App\Models\PaymentExpense;
use App\Repositories\Reports\ReportRepository;
use App\Services\Reports\FinancialReportService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->repository = Mockery::mock(ReportRepository::class);
    $this->service = new FinancialReportService($this->repository);
});

it('produces correct income, expense and net totals', function () {
    $p1 = new Payment;
    $p1->paid_amount = 1000.00;
    $p2 = new Payment;
    $p2->paid_amount = 500.00;

    $e1 = new PaymentExpense;
    $e1->amount = 300.00;

    $this->repository
        ->shouldReceive('paymentsForVenue')
        ->once()
        ->with(7, false)
        ->andReturn(new Collection([$p1, $p2]));

    $this->repository
        ->shouldReceive('expensesForVenue')
        ->once()
        ->with(7, false)
        ->andReturn(new Collection([$e1]));

    $report = $this->service->generate(7);

    expect($report['total_income'])->toBe(1500.0)
        ->and($report['total_expenses'])->toBe(300.0)
        ->and($report['net'])->toBe(1200.0);
});

it('excludes soft-deleted records by default', function () {
    $this->repository
        ->shouldReceive('paymentsForVenue')
        ->once()
        ->with(7, false)
        ->andReturn(new Collection);

    $this->repository
        ->shouldReceive('expensesForVenue')
        ->once()
        ->with(7, false)
        ->andReturn(new Collection);

    $this->service->generate(7);
});

it('includes soft-deleted records when requested for administrative reports', function () {
    $this->repository
        ->shouldReceive('paymentsForVenue')
        ->once()
        ->with(7, true)
        ->andReturn(new Collection);

    $this->repository
        ->shouldReceive('expensesForVenue')
        ->once()
        ->with(7, true)
        ->andReturn(new Collection);

    $this->service->generate(7, withTrashed: true);
});
