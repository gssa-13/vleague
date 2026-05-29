<?php

// tests/Unit/Services/GameServiceTest.php

use App\Models\Game;
use App\Repositories\GameRepository;
use App\Services\GameService;

beforeEach(function () {
    $this->repository = Mockery::mock(GameRepository::class);
    $this->service = new GameService($this->repository);
});

it('creates a game via repository', function () {
    $game = new Game;

    $this->repository
        ->shouldReceive('hasScheduleConflict')
        ->once()
        ->andReturn(false);

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($game);

    $result = $this->service->create([
        'competition_id' => 1,
        'scheduled_at' => '2026-06-01 18:00:00',
        'field_number' => 1,
    ]);

    expect($result)->toBeInstanceOf(Game::class);
});

it('throws when a schedule conflict exists on the same field and time', function () {
    $this->repository
        ->shouldReceive('hasScheduleConflict')
        ->once()
        ->with(1, '2026-06-01 18:00:00', null)
        ->andReturn(true);

    $this->repository->shouldNotReceive('create');

    expect(fn () => $this->service->create([
        'competition_id' => 5,
        'scheduled_at' => '2026-06-01 18:00:00',
        'field_number' => 1,
    ]))->toThrow(RuntimeException::class);
});

it('rejects scheduling a game in the past', function () {
    expect($this->service->isValidScheduleDate('2020-01-01 10:00:00'))->toBeFalse();
});

it('accepts scheduling a game in the future', function () {
    expect($this->service->isValidScheduleDate('2030-01-01 10:00:00'))->toBeTrue();
});

it('cancels a game by setting status, reason and soft deleting it', function () {
    $game = Mockery::mock(Game::class)->makePartial();

    $this->repository
        ->shouldReceive('cancel')
        ->once()
        ->with($game, 7);

    $this->service->cancel($game, 7);
});

it('soft deletes a game via repository', function () {
    $game = new Game;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($game);

    $this->service->delete($game);
});
