<?php

// tests/Unit/Services/PayrollServiceTest.php

use App\Enums\PayrollStatus;
use App\Models\EmployeePayroll;
use App\Models\Payroll;
use App\Models\PayrollAdjustment;
use App\Repositories\PayrollRepository;
use App\Services\PayrollService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->repository = Mockery::mock(PayrollRepository::class);
    $this->service = new PayrollService($this->repository);
});

it('creates a payroll in draft status', function () {
    $payroll = new Payroll;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->withArgs(fn (array $data) => $data['status'] === PayrollStatus::Draft)
        ->andReturn($payroll);

    $result = $this->service->create(['venue_id' => 1, 'period' => '2026-06']);

    expect($result)->toBeInstanceOf(Payroll::class);
});

it('computes net salary as base plus positive adjustments minus negative ones', function () {
    $line = new EmployeePayroll;
    $line->base_salary = 10000.00;

    $bonus = new PayrollAdjustment;
    $bonus->type = 'addition';
    $bonus->amount = 1500.00;

    $deduction = new PayrollAdjustment;
    $deduction->type = 'deduction';
    $deduction->amount = 500.00;

    $line->setRelation('adjustments', new Collection([$bonus, $deduction]));

    expect($this->service->computeNetSalary($line))->toBe(11000.0);
});

it('computes net salary as base when there are no adjustments', function () {
    $line = new EmployeePayroll;
    $line->base_salary = 8000.00;
    $line->setRelation('adjustments', new Collection);

    expect($this->service->computeNetSalary($line))->toBe(8000.0);
});

it('applies a positive adjustment via repository', function () {
    $adjustment = new PayrollAdjustment;

    $this->repository
        ->shouldReceive('addAdjustment')
        ->once()
        ->with(5, ['type' => 'addition', 'amount' => 1000.0, 'concept' => 'Bonus'])
        ->andReturn($adjustment);

    $result = $this->service->addAdjustment(5, ['type' => 'addition', 'amount' => 1000.0, 'concept' => 'Bonus']);

    expect($result)->toBeInstanceOf(PayrollAdjustment::class);
});

it('cancels a payroll via repository', function () {
    $payroll = new Payroll;

    $this->repository
        ->shouldReceive('cancel')
        ->once()
        ->with($payroll);

    $this->service->cancel($payroll);
});

it('soft deletes a payroll via repository', function () {
    $payroll = new Payroll;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($payroll);

    $this->service->delete($payroll);
});
