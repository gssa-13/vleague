<?php

// app/Services/UserService.php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?User
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?User
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return $this->repository->create($data);
    }

    public function update(User $user, array $data): User
    {
        return $this->repository->update($user, $data);
    }

    public function delete(User $user): void
    {
        $this->repository->delete($user);
    }

    public function restore(User $user): void
    {
        $this->repository->restore($user);
    }

    public function assignRole(User $user, string $roleName): void
    {
        $user->assignRole($roleName);
    }

    public function revokeRole(User $user, string $roleName): void
    {
        $user->removeRole($roleName);
    }

    public function assignPermission(User $user, string $permissionName): void
    {
        $user->givePermissionTo($permissionName);
    }
}
