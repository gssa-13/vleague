<?php

// tests/Unit/Services/UserServiceTest.php

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->repository = Mockery::mock(UserRepository::class);
    $this->service = new UserService($this->repository);
});

it('creates a user with hashed password via repository', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'plain-password',
    ];

    $fakeUser = new User(['name' => 'Test User', 'email' => 'test@example.com']);

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->withArgs(function (array $payload) {
            return $payload['name'] === 'Test User'
                && $payload['email'] === 'test@example.com'
                && Hash::check('plain-password', $payload['password']);
        })
        ->andReturn($fakeUser);

    $result = $this->service->create($data);

    expect($result)->toBeInstanceOf(User::class);
});

it('updates a user via repository', function () {
    $user = new User(['name' => 'Old Name', 'email' => 'old@example.com']);

    $this->repository
        ->shouldReceive('update')
        ->once()
        ->with($user, ['name' => 'New Name', 'email' => 'old@example.com'])
        ->andReturn($user);

    $result = $this->service->update($user, ['name' => 'New Name', 'email' => 'old@example.com']);

    expect($result)->toBeInstanceOf(User::class);
});

it('soft deletes a user via repository', function () {
    $user = new User;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($user);

    $this->service->delete($user);
});

it('restores a soft-deleted user via repository', function () {
    $user = new User;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($user);

    $this->service->restore($user);
});

it('finds a user by id via repository', function () {
    $user = new User;

    $this->repository
        ->shouldReceive('findById')
        ->once()
        ->with(42)
        ->andReturn($user);

    $result = $this->service->findById(42);

    expect($result)->toBeInstanceOf(User::class);
});
