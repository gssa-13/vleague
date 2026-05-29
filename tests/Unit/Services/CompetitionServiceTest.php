<?php

// tests/Unit/Services/CompetitionServiceTest.php

use App\Models\Competition;
use App\Repositories\CompetitionRepository;
use App\Services\CompetitionService;

beforeEach(function () {
    $this->repository = Mockery::mock(CompetitionRepository::class);
    $this->service = new CompetitionService($this->repository);
});

it('creates a competition via repository', function () {
    $competition = new Competition;

    $this->repository
        ->shouldReceive('existsActive')
        ->once()
        ->with(1, 2, 3)
        ->andReturn(false);

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($competition);

    $result = $this->service->create([
        'venue_id' => 1,
        'tournament_id' => 2,
        'division_id' => 3,
    ]);

    expect($result)->toBeInstanceOf(Competition::class);
});

it('throws exception when active combination already exists', function () {
    $this->repository
        ->shouldReceive('existsActive')
        ->once()
        ->with(1, 2, 3)
        ->andReturn(true);

    $this->repository->shouldNotReceive('create');

    expect(fn () => $this->service->create([
        'venue_id' => 1,
        'tournament_id' => 2,
        'division_id' => 3,
    ]))->toThrow(RuntimeException::class);
});

it('soft deletes a competition via repository', function () {
    $competition = new Competition;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($competition);

    $this->service->delete($competition);
});

it('restores a competition via repository', function () {
    $competition = new Competition;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($competition);

    $this->service->restore($competition);
});
