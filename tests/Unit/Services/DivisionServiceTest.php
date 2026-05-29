<?php

// tests/Unit/Services/DivisionServiceTest.php

use App\Enums\DivisionDay;
use App\Repositories\DivisionRepository;
use App\Services\DivisionService;

beforeEach(function () {
    $this->repository = Mockery::mock(DivisionRepository::class);
    $this->service = new DivisionService($this->repository);
});

it('generates display name for saturday field 1 group A', function () {
    $name = $this->service->generateDisplayName(DivisionDay::Saturday, 1, 'A');

    expect($name)->toBe('Saturday 1A');
});

it('generates display name for monday field 2 without group letter', function () {
    $name = $this->service->generateDisplayName(DivisionDay::Monday, 2, null);

    expect($name)->toBe('Monday 2');
});

it('generates display name for sunday field 3 group B', function () {
    $name = $this->service->generateDisplayName(DivisionDay::Sunday, 3, 'B');

    expect($name)->toBe('Sunday 3B');
});

it('generates display name for wednesday field 1 without group letter', function () {
    $name = $this->service->generateDisplayName(DivisionDay::Wednesday, 1, null);

    expect($name)->toBe('Wednesday 1');
});
