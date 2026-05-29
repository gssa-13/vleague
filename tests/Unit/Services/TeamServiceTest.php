<?php

// tests/Unit/Services/TeamServiceTest.php

use App\Models\Team;
use App\Models\TeamRoster;
use App\Repositories\TeamRepository;
use App\Services\TeamService;

beforeEach(function () {
    $this->repository = Mockery::mock(TeamRepository::class);
    $this->service = new TeamService($this->repository);
});

it('creates a team via repository', function () {
    $team = new Team;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->with(['name' => 'Barcelona'])
        ->andReturn($team);

    $result = $this->service->create(['name' => 'Barcelona']);

    expect($result)->toBeInstanceOf(Team::class);
});

it('throws when assigning a captain to a team that already has one', function () {
    $this->repository
        ->shouldReceive('hasCaptain')
        ->once()
        ->with(5)
        ->andReturn(true);

    $this->repository->shouldNotReceive('addRosterEntry');

    expect(fn () => $this->service->addRosterEntry(5, [
        'player_id' => 1,
        'is_captain' => true,
    ]))->toThrow(RuntimeException::class);
});

it('adds a captain when the team has none', function () {
    $roster = new TeamRoster;

    $this->repository
        ->shouldReceive('hasCaptain')
        ->once()
        ->with(5)
        ->andReturn(false);

    $this->repository
        ->shouldReceive('addRosterEntry')
        ->once()
        ->andReturn($roster);

    $result = $this->service->addRosterEntry(5, [
        'player_id' => 1,
        'is_captain' => true,
    ]);

    expect($result)->toBeInstanceOf(TeamRoster::class);
});

it('adds a non-captain roster entry without checking captain', function () {
    $roster = new TeamRoster;

    $this->repository->shouldNotReceive('hasCaptain');

    $this->repository
        ->shouldReceive('addRosterEntry')
        ->once()
        ->andReturn($roster);

    $result = $this->service->addRosterEntry(5, [
        'player_id' => 2,
        'is_captain' => false,
    ]);

    expect($result)->toBeInstanceOf(TeamRoster::class);
});

it('soft deletes a team via repository', function () {
    $team = new Team;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($team);

    $this->service->delete($team);
});
