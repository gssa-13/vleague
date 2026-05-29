<?php

// tests/Unit/Services/PlayerEligibilityServiceTest.php

use App\Models\PlayerSanction;
use App\Repositories\PlayerSanctionRepository;
use App\Services\PlayerEligibilityService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->repository = Mockery::mock(PlayerSanctionRepository::class);
    $this->service = new PlayerEligibilityService($this->repository);
});

it('marks player as not eligible when an active sanction exists', function () {
    $sanction = new PlayerSanction;
    $sanction->sanctioned_games = 3;
    $sanction->served_games = 1;

    $this->repository
        ->shouldReceive('activeForPlayer')
        ->once()
        ->with(10)
        ->andReturn(new Collection([$sanction]));

    expect($this->service->isEligible(10))->toBeFalse();
});

it('marks player as eligible when sanction is fully served', function () {
    $sanction = new PlayerSanction;
    $sanction->sanctioned_games = 3;
    $sanction->served_games = 3;

    $this->repository
        ->shouldReceive('activeForPlayer')
        ->once()
        ->with(10)
        ->andReturn(new Collection([$sanction]));

    expect($this->service->isEligible(10))->toBeTrue();
});

it('marks player as eligible when there are no sanctions', function () {
    $this->repository
        ->shouldReceive('activeForPlayer')
        ->once()
        ->with(10)
        ->andReturn(new Collection);

    expect($this->service->isEligible(10))->toBeTrue();
});

it('marks player as not eligible when at least one sanction is unserved among several', function () {
    $served = new PlayerSanction;
    $served->sanctioned_games = 2;
    $served->served_games = 2;

    $pending = new PlayerSanction;
    $pending->sanctioned_games = 4;
    $pending->served_games = 0;

    $this->repository
        ->shouldReceive('activeForPlayer')
        ->once()
        ->with(10)
        ->andReturn(new Collection([$served, $pending]));

    expect($this->service->isEligible(10))->toBeFalse();
});
