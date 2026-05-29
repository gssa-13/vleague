<?php

// app/Services/RoleService.php

namespace App\Services;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function __construct(
        private readonly RoleRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Role
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Role
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Role
    {
        $data['guard_name'] = $data['guard_name'] ?? 'web';

        return $this->repository->create($data);
    }

    public function update(Role $role, array $data): Role
    {
        return $this->repository->update($role, $data);
    }

    public function delete(Role $role): void
    {
        $this->repository->delete($role);
    }

    public function restore(Role $role): void
    {
        $this->repository->restore($role);
    }
}
