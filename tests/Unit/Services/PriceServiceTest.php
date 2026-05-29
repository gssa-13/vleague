<?php

// tests/Unit/Services/PriceServiceTest.php

use App\Models\Discount;
use App\Models\Price;
use App\Repositories\PriceRepository;
use App\Services\PriceService;

beforeEach(function () {
    $this->repository = Mockery::mock(PriceRepository::class);
    $this->service = new PriceService($this->repository);
});

it('calculates price with active discount', function () {
    $price = new Price;
    $price->amount = 1000.00;

    $discount = new Discount;
    $discount->percentage = 10;
    $discount->deleted_at = null;

    $result = $this->service->applyDiscount($price, $discount);

    expect($result)->toBe(900.0);
});

it('does not apply discount when discount is soft-deleted', function () {
    $price = new Price;
    $price->amount = 1000.00;

    $discount = new Discount;
    $discount->percentage = 10;
    $discount->deleted_at = now();

    $result = $this->service->applyDiscount($price, $discount);

    expect($result)->toBe(1000.0);
});

it('creates a price via repository', function () {
    $price = new Price;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($price);

    $result = $this->service->create(['name' => 'Standard', 'amount' => 1500]);

    expect($result)->toBeInstanceOf(Price::class);
});
