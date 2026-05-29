<?php

// app/Repositories/PlayerSanctionRepository.php

namespace App\Repositories;

use App\Models\PlayerSanction;
use Illuminate\Database\Eloquent\Collection;

class PlayerSanctionRepository
{
    public function all(): Collection
    {
        return PlayerSanction::with('player')->get();
    }

    public function findById(int $id): ?PlayerSanction
    {
        return PlayerSanction::find($id);
    }

    public function findTrashedById(int $id): ?PlayerSanction
    {
        return PlayerSanction::withTrashed()->find($id);
    }

    /**
     * Returns all non-deleted sanctions for a given player.
     * Soft-deleted sanctions are excluded by the global SoftDeletes scope.
     */
    public function activeForPlayer(int $playerId): Collection
    {
        return PlayerSanction::where('player_id', $playerId)->get();
    }

    public function create(array $data): PlayerSanction
    {
        return PlayerSanction::create($data);
    }

    public function update(PlayerSanction $sanction, array $data): PlayerSanction
    {
        $sanction->update($data);

        return $sanction;
    }

    public function delete(PlayerSanction $sanction): void
    {
        $sanction->delete();
    }

    public function restore(PlayerSanction $sanction): void
    {
        $sanction->restore();
    }
}
