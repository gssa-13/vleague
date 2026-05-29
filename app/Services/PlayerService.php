<?php

// app/Services/PlayerService.php

namespace App\Services;

use App\Models\Player;
use App\Repositories\PlayerRepository;
use Illuminate\Database\Eloquent\Collection;

class PlayerService
{
    public function __construct(
        private readonly PlayerRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Player
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Player
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Player
    {
        if (! empty($data['email']) && $this->repository->findActiveByEmail($data['email']) !== null) {
            throw new \RuntimeException('An active player with this email already exists.');
        }

        return $this->repository->create($data);
    }

    public function update(Player $player, array $data): Player
    {
        return $this->repository->update($player, $data);
    }

    public function delete(Player $player): void
    {
        $this->repository->delete($player);
    }

    public function restore(Player $player): void
    {
        $this->repository->restore($player);
    }
}
