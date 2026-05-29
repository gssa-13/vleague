<?php

// app/Services/TournamentService.php

namespace App\Services;

use App\Enums\TournamentStatus;
use App\Models\Tournament;
use App\Repositories\TournamentRepository;
use Illuminate\Database\Eloquent\Collection;

class TournamentService
{
    public function __construct(
        private readonly TournamentRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Tournament
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Tournament
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Tournament
    {
        return $this->repository->create($data);
    }

    /**
     * Creates a new active tournament for a venue.
     * Deactivates any existing active tournament for the same venue first.
     */
    public function createActive(array $data): Tournament
    {
        $existing = $this->repository->findActiveByVenue($data['venue_id']);

        foreach ($existing as $tournament) {
            $this->repository->update($tournament, ['status' => TournamentStatus::Inactive]);
        }

        $data['status'] = TournamentStatus::Active;

        return $this->repository->create($data);
    }

    public function update(Tournament $tournament, array $data): Tournament
    {
        return $this->repository->update($tournament, $data);
    }

    public function delete(Tournament $tournament): void
    {
        $this->repository->delete($tournament);
    }

    public function restore(Tournament $tournament): void
    {
        $this->repository->restore($tournament);
    }
}
