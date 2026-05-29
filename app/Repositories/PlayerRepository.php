<?php

// app/Repositories/PlayerRepository.php

namespace App\Repositories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepository
{
    public function all(): Collection
    {
        return Player::all();
    }

    public function findById(int $id): ?Player
    {
        return Player::find($id);
    }

    public function findTrashedById(int $id): ?Player
    {
        return Player::withTrashed()->find($id);
    }

    public function findActiveByEmail(string $email): ?Player
    {
        return Player::where('email', $email)->first();
    }

    public function create(array $data): Player
    {
        return Player::create($data);
    }

    public function update(Player $player, array $data): Player
    {
        $player->update($data);

        return $player;
    }

    public function delete(Player $player): void
    {
        $player->delete();
    }

    public function restore(Player $player): void
    {
        $player->restore();
    }
}
