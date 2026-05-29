<?php

// app/Services/CompetitionService.php

namespace App\Services;

use App\Models\Competition;
use App\Repositories\CompetitionRepository;
use Illuminate\Database\Eloquent\Collection;

class CompetitionService
{
    public function __construct(
        private readonly CompetitionRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Competition
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Competition
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Competition
    {
        if ($this->repository->existsActive($data['venue_id'], $data['tournament_id'], $data['division_id'])) {
            throw new \RuntimeException(
                'An active competition already exists for this venue/tournament/division combination.'
            );
        }

        return $this->repository->create($data);
    }

    public function delete(Competition $competition): void
    {
        $this->repository->delete($competition);
    }

    public function restore(Competition $competition): void
    {
        $this->repository->restore($competition);
    }
}
