<?php

// app/Repositories/RoleRepository.php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    public function all(): Collection
    {
        return Role::all();
    }

    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    public function findTrashedById(int $id): ?Role
    {
        return Role::withTrashed()->find($id);
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);

        return $role;
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }

    public function restore(Role $role): void
    {
        $role->restore();
    }
}
