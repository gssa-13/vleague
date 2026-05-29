<?php

// tests/Unit/Services/PlayerServiceTest.php

use App\Models\Player;
use App\Repositories\PlayerRepository;
use App\Services\PlayerService;

beforeEach(function () {
    $this->repository = Mockery::mock(PlayerRepository::class);
    $this->service = new PlayerService($this->repository);
});

it('creates a player via repository when no duplicate exists', function () {
    $player = new Player;

    $this->repository
        ->shouldReceive('findActiveByEmail')
        ->once()
        ->with('new@example.com')
        ->andReturn(null);

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($player);

    $result = $this->service->create([
        'first_name' => 'Carlos',
        'last_name' => 'López',
        'birth_date' => '1990-01-01',
        'email' => 'new@example.com',
    ]);

    expect($result)->toBeInstanceOf(Player::class);
});

it('throws exception when active player with same email already exists', function () {
    $existing = new Player;

    $this->repository
        ->shouldReceive('findActiveByEmail')
        ->once()
        ->with('taken@example.com')
        ->andReturn($existing);

    $this->repository->shouldNotReceive('create');

    expect(fn () => $this->service->create([
        'first_name' => 'Dup',
        'last_name' => 'Player',
        'birth_date' => '1990-01-01',
        'email' => 'taken@example.com',
    ]))->toThrow(RuntimeException::class);
});

it('soft deletes a player via repository', function () {
    $player = new Player;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($player);

    $this->service->delete($player);
});

it('restores a player via repository', function () {
    $player = new Player;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($player);

    $this->service->restore($player);
});
