<?php

// app/Services/GameRoleService.php

namespace App\Services;

use App\Models\GameRole;
use App\Models\GameRoleAssignment;
use App\Repositories\GameRoleRepository;
use Illuminate\Database\Eloquent\Collection;

class GameRoleService
{
    public function __construct(
        private readonly GameRoleRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?GameRole
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?GameRole
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): GameRole
    {
        return $this->repository->create($data);
    }

    public function update(GameRole $gameRole, array $data): GameRole
    {
        return $this->repository->update($gameRole, $data);
    }

    public function delete(GameRole $gameRole): void
    {
        $this->repository->delete($gameRole);
    }

    public function restore(GameRole $gameRole): void
    {
        $this->repository->restore($gameRole);
    }

    public function assign(int $gameId, array $data): GameRoleAssignment
    {
        return $this->repository->assign($gameId, $data);
    }

    public function revoke(GameRoleAssignment $assignment): void
    {
        $this->repository->revoke($assignment);
    }
}
