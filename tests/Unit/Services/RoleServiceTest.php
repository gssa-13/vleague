<?php

// tests/Unit/Services/RoleServiceTest.php

use App\Models\Role;
use App\Repositories\RoleRepository;
use App\Services\RoleService;

beforeEach(function () {
    $this->repository = Mockery::mock(RoleRepository::class);
    $this->service = new RoleService($this->repository);
});

it('creates a role via repository', function () {
    $role = new Role;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->with(['name' => 'editor', 'guard_name' => 'web'])
        ->andReturn($role);

    $result = $this->service->create(['name' => 'editor', 'guard_name' => 'web']);

    expect($result)->toBeInstanceOf(Role::class);
});

it('updates a role via repository', function () {
    $role = new Role;

    $this->repository
        ->shouldReceive('update')
        ->once()
        ->with($role, ['name' => 'updated-role'])
        ->andReturn($role);

    $result = $this->service->update($role, ['name' => 'updated-role']);

    expect($result)->toBeInstanceOf(Role::class);
});

it('soft deletes a role via repository', function () {
    $role = new Role;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($role);

    $this->service->delete($role);
});

it('restores a soft-deleted role via repository', function () {
    $role = new Role;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($role);

    $this->service->restore($role);
});

it('finds a role by id via repository', function () {
    $role = new Role;

    $this->repository
        ->shouldReceive('findById')
        ->once()
        ->with(5)
        ->andReturn($role);

    $result = $this->service->findById(5);

    expect($result)->toBeInstanceOf(Role::class);
});
