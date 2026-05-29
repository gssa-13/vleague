<?php

// app/Services/PlayerSanctionService.php

namespace App\Services;

use App\Models\PlayerSanction;
use App\Repositories\PlayerSanctionRepository;
use Illuminate\Database\Eloquent\Collection;

class PlayerSanctionService
{
    public function __construct(
        private readonly PlayerSanctionRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?PlayerSanction
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?PlayerSanction
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): PlayerSanction
    {
        return $this->repository->create($data);
    }

    public function update(PlayerSanction $sanction, array $data): PlayerSanction
    {
        return $this->repository->update($sanction, $data);
    }

    public function delete(PlayerSanction $sanction): void
    {
        $this->repository->delete($sanction);
    }

    public function restore(PlayerSanction $sanction): void
    {
        $this->repository->restore($sanction);
    }
}
