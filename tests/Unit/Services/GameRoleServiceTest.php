<?php

// tests/Unit/Services/GameRoleServiceTest.php

use App\Models\GameRole;
use App\Models\GameRoleAssignment;
use App\Repositories\GameRoleRepository;
use App\Services\GameRoleService;

beforeEach(function () {
    $this->repository = Mockery::mock(GameRoleRepository::class);
    $this->service = new GameRoleService($this->repository);
});

it('creates a game role via repository', function () {
    $gameRole = new GameRole;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->with(['name' => 'Referee'])
        ->andReturn($gameRole);

    $result = $this->service->create(['name' => 'Referee']);

    expect($result)->toBeInstanceOf(GameRole::class);
});

it('assigns a role to a game via repository', function () {
    $assignment = new GameRoleAssignment;

    $this->repository
        ->shouldReceive('assign')
        ->once()
        ->with(3, ['game_role_id' => 1, 'employee_id' => 2])
        ->andReturn($assignment);

    $result = $this->service->assign(3, ['game_role_id' => 1, 'employee_id' => 2]);

    expect($result)->toBeInstanceOf(GameRoleAssignment::class);
});

it('revokes an assignment via repository', function () {
    $assignment = new GameRoleAssignment;

    $this->repository
        ->shouldReceive('revoke')
        ->once()
        ->with($assignment);

    $this->service->revoke($assignment);
});

it('soft deletes a game role via repository', function () {
    $gameRole = new GameRole;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($gameRole);

    $this->service->delete($gameRole);
});

it('restores a game role via repository', function () {
    $gameRole = new GameRole;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($gameRole);

    $this->service->restore($gameRole);
});
