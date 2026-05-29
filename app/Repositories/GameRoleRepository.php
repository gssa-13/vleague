<?php

// app/Repositories/GameRoleRepository.php

namespace App\Repositories;

use App\Models\GameRole;
use App\Models\GameRoleAssignment;
use Illuminate\Database\Eloquent\Collection;

class GameRoleRepository
{
    public function all(): Collection
    {
        return GameRole::all();
    }

    public function findById(int $id): ?GameRole
    {
        return GameRole::find($id);
    }

    public function findTrashedById(int $id): ?GameRole
    {
        return GameRole::withTrashed()->find($id);
    }

    public function create(array $data): GameRole
    {
        return GameRole::create($data);
    }

    public function update(GameRole $gameRole, array $data): GameRole
    {
        $gameRole->update($data);

        return $gameRole;
    }

    public function delete(GameRole $gameRole): void
    {
        $gameRole->delete();
    }

    public function restore(GameRole $gameRole): void
    {
        $gameRole->restore();
    }

    public function assign(int $gameId, array $data): GameRoleAssignment
    {
        $data['game_id'] = $gameId;

        return GameRoleAssignment::create($data);
    }

    public function revoke(GameRoleAssignment $assignment): void
    {
        $assignment->delete();
    }
}
