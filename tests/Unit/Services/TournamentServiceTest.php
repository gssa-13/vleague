<?php

// tests/Unit/Services/TournamentServiceTest.php

use App\Enums\TournamentStatus;
use App\Models\Tournament;
use App\Repositories\TournamentRepository;
use App\Services\TournamentService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->repository = Mockery::mock(TournamentRepository::class);
    $this->service = new TournamentService($this->repository);
});

it('creates a tournament via repository', function () {
    $tournament = new Tournament;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($tournament);

    $result = $this->service->create(['name' => 'Copa 2026']);

    expect($result)->toBeInstanceOf(Tournament::class);
});

it('enforces only one active tournament per venue', function () {
    $existing = new Tournament;
    $existing->id = 1;
    $existing->status = TournamentStatus::Active;

    $this->repository
        ->shouldReceive('findActiveByVenue')
        ->once()
        ->with(5)
        ->andReturn(new Collection([$existing]));

    $this->repository
        ->shouldReceive('update')
        ->once()
        ->withArgs(fn ($t, $data) => $data['status'] === TournamentStatus::Inactive);

    $newTournament = new Tournament;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($newTournament);

    $result = $this->service->createActive(['venue_id' => 5, 'name' => 'New Active']);

    expect($result)->toBeInstanceOf(Tournament::class);
});

it('does not deactivate anything when no active tournament exists for venue', function () {
    $this->repository
        ->shouldReceive('findActiveByVenue')
        ->once()
        ->with(5)
        ->andReturn(new Collection);

    $this->repository->shouldNotReceive('update');

    $newTournament = new Tournament;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($newTournament);

    $result = $this->service->createActive(['venue_id' => 5, 'name' => 'First Active']);

    expect($result)->toBeInstanceOf(Tournament::class);
});

it('soft deletes a tournament via repository', function () {
    $tournament = new Tournament;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($tournament);

    $this->service->delete($tournament);
});

it('restores a tournament via repository', function () {
    $tournament = new Tournament;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($tournament);

    $this->service->restore($tournament);
});
