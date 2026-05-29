<?php

// tests/Unit/Services/PaymentServiceTest.php

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->repository = Mockery::mock(PaymentRepository::class);
    $this->service = new PaymentService($this->repository);
});

it('creates a payment computing debt from price minus paid amount', function () {
    $payment = new Payment;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->withArgs(function (array $data) {
            return abs($data['debt'] - 400.0) < 0.001
                && $data['status'] === PaymentStatus::Partial;
        })
        ->andReturn($payment);

    $result = $this->service->create([
        'amount' => 1000.00,
        'paid_amount' => 600.00,
    ]);

    expect($result)->toBeInstanceOf(Payment::class);
});

it('marks payment as paid when paid amount equals price', function () {
    $payment = new Payment;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->withArgs(function (array $data) {
            return abs($data['debt'] - 0.0) < 0.001
                && $data['status'] === PaymentStatus::Paid;
        })
        ->andReturn($payment);

    $result = $this->service->create([
        'amount' => 1000.00,
        'paid_amount' => 1000.00,
    ]);

    expect($result)->toBeInstanceOf(Payment::class);
});

it('marks payment as pending when nothing has been paid', function () {
    $payment = new Payment;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->withArgs(function (array $data) {
            return abs($data['debt'] - 1000.0) < 0.001
                && $data['status'] === PaymentStatus::Pending;
        })
        ->andReturn($payment);

    $this->service->create([
        'amount' => 1000.00,
        'paid_amount' => 0.00,
    ]);
});

it('calculates active balance excluding cancelled payments', function () {
    $paid = new Payment;
    $paid->paid_amount = 500.00;
    $paid->status = PaymentStatus::Paid;

    $partial = new Payment;
    $partial->paid_amount = 300.00;
    $partial->status = PaymentStatus::Partial;

    $this->repository
        ->shouldReceive('activeForCompetition')
        ->once()
        ->with(7)
        ->andReturn(new Collection([$paid, $partial]));

    $balance = $this->service->calculateBalance(7);

    expect($balance)->toBe(800.0);
});

it('calculates total pending debt for a competition', function () {
    $a = new Payment;
    $a->debt = 200.00;

    $b = new Payment;
    $b->debt = 150.00;

    $this->repository
        ->shouldReceive('activeForCompetition')
        ->once()
        ->with(7)
        ->andReturn(new Collection([$a, $b]));

    expect($this->service->calculatePendingDebt(7))->toBe(350.0);
});

it('cancels a payment setting status, reason and soft deleting it', function () {
    $payment = new Payment;

    $this->repository
        ->shouldReceive('cancel')
        ->once()
        ->with($payment, 'Duplicate charge');

    $this->service->cancel($payment, 'Duplicate charge');
});
